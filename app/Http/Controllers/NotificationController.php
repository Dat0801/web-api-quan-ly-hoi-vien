<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\NotificationRecipient;
use App\Models\BoardCustomer;
use App\Models\BusinessCustomer;
use App\Models\IndividualCustomer;
use App\Models\BusinessPartner;
use App\Models\IndividualPartner;

class NotificationController extends Controller
{
    //
    public function index(Request $request)
    {
        $search = $request->get('search');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $type = $request->get('type');

        $notifications = Notification::query()
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'LIKE', "%$search%");
            })
            ->when($type, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->with('creator')
            ->paginate(10);

        return view('notification.index', compact('notifications'));
    }


    public function create()
    {
        $boardCustomers = BoardCustomer::all()->map(function ($item) {
            $item->type_name = 'Ban chấp hành';
            $item->type = BoardCustomer::class;
            $item->target_customer_group = $item->targetCustomerGroup->group_name ?? '-';
            $item->business_scale = $item->business_scale ?? '-';
            return $item;
        });

        $businessCustomers = BusinessCustomer::all()->map(function ($item) {
            $item->type_name = 'Khách hàng doanh nghiệp';
            $item->type = BusinessCustomer::class;
            $item->target_customer_group = $item->targetCustomerGroup->group_name ?? '-';
            $item->business_scale = $item->business_scale ?? '-';
            return $item;
        });

        $individualCustomers = IndividualCustomer::all()->map(function ($item) {
            $item->type_name = 'Khách hàng cá nhân';
            $item->type = IndividualCustomer::class;
            $item->target_customer_group = $item->targetCustomerGroup->group_name ?? '-';
            $item->business_scale = $item->business_scale ?? '-';
            return $item;
        });

        $businessPartners = BusinessPartner::all()->map(function ($item) {
            $item->type_name = 'Đối tác doanh nghiệp';
            $item->type = BusinessPartner::class;
            $item->target_customer_group = $item->targetCustomerGroup->group_name ?? '-';
            $item->business_scale = $item->business_scale ?? '-';
            return $item;
        });

        $individualPartners = IndividualPartner::all()->map(function ($item) {
            $item->type_name = 'Đối tác cá nhân';
            $item->type = IndividualPartner::class;
            $item->target_customer_group = $item->targetCustomerGroup->group_name ?? '-';
            $item->business_scale = $item->business_scale ?? '-';
            return $item;
        });

        $allParticipants = $boardCustomers
            ->concat($businessCustomers)
            ->concat($individualCustomers)
            ->concat($businessPartners)
            ->concat($individualPartners);

        $participantTypes = [
            'board_customer' => 'Ban chấp hành',
            'business_customer' => 'Khách hàng doanh nghiệp',
            'individual_customer' => 'Khách hàng cá nhân',
            'business_partner' => 'Đối tác doanh nghiệp',
            'individual_partner' => 'Đối tác cá nhân',
        ];

        $fields = $allParticipants->pluck('field')->filter()->unique();
        $markets = $allParticipants->pluck('market')->filter()->unique();
        $targetCustomerGroups = $allParticipants->pluck('target_customer_group')->filter()->unique();
        $businessScales = $allParticipants->pluck('business_scale')->filter()->unique();

        return view(
            'notification.create',
            compact('allParticipants', 'fields', 'markets', 'targetCustomerGroups', 'businessScales', 'participantTypes')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'format' => 'required|string|max:255',
            'send_time_option' => 'required|string',
            'send_time' => 'nullable|date',
            'content' => 'nullable|string',
            'selected_members' => 'nullable|array',
            'new_participants' => 'nullable|array',
            'new_participants.*.email' => 'nullable|email',
        ]);

        $notification = new Notification();
        $notification->title = $request->title;
        $notification->format = $request->format;
        if (request('send_time_option') == 'immediate') {
            $notification->sent_at = now();
        } else {
            $notification->sent_at = $request->send_time;
        }
        $notification->content = $request->content;
        $notification->user_id = auth()->id();
        $notification->save();
        if ($request->has('selected_members')) {
            foreach ($request->selected_members as $selected_member) {
                $customerModel = null;
                $type = $selected_member['type'];
                $participantId = $selected_member['id'];
                $customerModel = $type::find($participantId);
                if ($customerModel) {
                    $notificationParticipant = new NotificationRecipient();
                    $notificationParticipant->notification_id = $notification->id;
                    $notificationParticipant->recipientable_id = $customerModel->id;
                    $notificationParticipant->recipientable_type = get_class($customerModel);
                    $notificationParticipant->save();
                }
            }
        }

        if ($request->has('new_participants')) {
            foreach ($request->new_participants as $newParticipant) {
                $notificationParticipant = new NotificationRecipient();
                $notificationParticipant->notification_id = $notification->id;
                $notificationParticipant->email = $newParticipant['email'];
                $notificationParticipant->save();
            }
        }

        return redirect()->route('notification.index')->with('success', 'Thêm thông báo thành công!');
    }

    public function show($id)
    {
        $notification = Notification::with('recipients')->findOrFail($id);

        $notification->recipients->each(function ($recipients) {
            if ($recipients->recipientable && method_exists($recipients->recipientable, 'market')) {
                $recipients->recipientable->load('market');
            }
        });

        $participantTypes = [
            BoardCustomer::class => 'Ban chấp hành',
            BusinessCustomer::class => 'Khách hàng doanh nghiệp',
            IndividualCustomer::class => 'Khách hàng cá nhân',
            BusinessPartner::class => 'Đối tác doanh nghiệp',
            IndividualPartner::class => 'Đối tác cá nhân',
        ];

        $externalParticipants = $notification->recipients->filter(function ($recipients) {
            return $recipients->recipientable_type === null;
        });
        return view('notification.show', compact('notification', 'participantTypes', 'externalParticipants'));
    }

    public function edit($id)
    {
        $notification = Notification::findOrFail($id);
        $boardCustomers = BoardCustomer::all()->map(function ($item) {
            $item->type_name = 'Ban chấp hành';
            $item->type = BoardCustomer::class;
            $item->target_customer_group = $item->targetCustomerGroup->group_name ?? '-';
            $item->business_scale = $item->business_scale ?? '-';
            return $item;
        });

        $businessCustomers = BusinessCustomer::all()->map(function ($item) {
            $item->type_name = 'Khách hàng doanh nghiệp';
            $item->type = BusinessCustomer::class;
            $item->target_customer_group = $item->targetCustomerGroup->group_name ?? '-';
            $item->business_scale = $item->business_scale ?? '-';
            return $item;
        });

        $individualCustomers = IndividualCustomer::all()->map(function ($item) {
            $item->type_name = 'Khách hàng cá nhân';
            $item->type = IndividualCustomer::class;
            $item->target_customer_group = $item->targetCustomerGroup->group_name ?? '-';
            $item->business_scale = $item->business_scale ?? '-';
            return $item;
        });

        $businessPartners = BusinessPartner::all()->map(function ($item) {
            $item->type_name = 'Đối tác doanh nghiệp';
            $item->type = BusinessPartner::class;
            $item->target_customer_group = $item->targetCustomerGroup->group_name ?? '-';
            $item->business_scale = $item->business_scale ?? '-';
            return $item;
        });

        $individualPartners = IndividualPartner::all()->map(function ($item) {
            $item->type_name = 'Đối tác cá nhân';
            $item->type = IndividualPartner::class;
            $item->target_customer_group = $item->targetCustomerGroup->group_name ?? '-';
            $item->business_scale = $item->business_scale ?? '-';
            return $item;
        });

        $allParticipants = $boardCustomers
            ->concat($businessCustomers)
            ->concat($individualCustomers)
            ->concat($businessPartners)
            ->concat($individualPartners);

        $participantTypes = [
            'board_customer' => 'Ban chấp hành',
            'business_customer' => 'Khách hàng doanh nghiệp',
            'individual_customer' => 'Khách hàng cá nhân',
            'business_partner' => 'Đối tác doanh nghiệp',
            'individual_partner' => 'Đối tác cá nhân',
        ];

        $fields = $allParticipants->pluck('field')->filter()->unique();
        $markets = $allParticipants->pluck('market')->filter()->unique();
        $targetCustomerGroups = $allParticipants->pluck('target_customer_group')->filter()->unique();
        $businessScales = $allParticipants->pluck('business_scale')->filter()->unique();

        $externalParticipants = $notification->recipients->filter(function ($recipient) {
            return $recipient->recipientable_type === null;
        });

        return view(
            'notification.edit',
            compact(
                'notification',
                'allParticipants',
                'fields',
                'markets',
                'targetCustomerGroups',
                'businessScales',
                'participantTypes',
                'externalParticipants'
            )
        );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'format' => 'required|string|max:255',
            'sent_at' => 'nullable|date',
            'content' => 'nullable|string',
            'selected_members' => 'nullable|array',
            'external_participants' => 'nullable|array',
            'external_participants.*.email' => 'nullable|email',
        ]);

        $notification = Notification::findOrFail($id);
        $notification->title = $request->title;
        $notification->format = $request->format;
        if (request('send_time_option') == 'immediate') {
            $notification->sent_at = now();
        } else {
            $notification->sent_at = $request->send_time;
        }
        $notification->content = $request->content;
        $notification->user_id = auth()->id();
        $notification->save();

        if ($request->has('selected_members')) {
            $existingParticipants = $notification->recipients()
                ->select('recipientable_id', 'recipientable_type')
                ->get()
                ->map(function ($recipient) {
                    return [
                        'id' => $recipient->recipientable_id,
                        'type' => $recipient->recipientable_type
                    ];
                })
                ->toArray();

            $newParticipants = array_map(function ($selectedMember) {
                return [
                    'id' => $selectedMember['id'],
                    'type' => $selectedMember['type']
                ];
            }, $request->selected_members);

            $participantsToRemove = array_udiff($existingParticipants, $newParticipants, function ($a, $b) {
                return ($a['id'] === $b['id'] && $a['type'] === $b['type']) ? 0 : 1;
            });

            $idsToRemove = array_column($participantsToRemove, 'id');

            NotificationRecipient::whereIn('recipientable_id', $idsToRemove)
                ->where('notification_id', $notification->id)
                ->delete();

            foreach ($request->selected_members as $selected_member) {
                $type = $selected_member['type'];
                $participantId = $selected_member['id'];
                $existing = $notification->recipients->where('recipientable_id', $participantId)
                    ->where('recipientable_type', $type)
                    ->first();
                if (!$existing) {
                    $customerModel = $type::find($participantId);
                    if ($customerModel) {
                        $meetingParticipant = new NotificationRecipient();
                        $meetingParticipant->notification_id = $notification->id;
                        $meetingParticipant->recipientable_id = $customerModel->id;
                        $meetingParticipant->recipientable_type = get_class($customerModel);
                        $meetingParticipant->save();
                    }
                }
            }
        } else {
            $notification->recipients()->delete();
        }
        if ($request->has('external_participants')) {
            foreach ($request->external_participants as $externalParticipant) {
                if (!empty($externalParticipant['checked']) && !empty($externalParticipant['email'])) {
                    $existing = $notification->recipients()
                        ->where('email', $externalParticipant['email'])
                        ->first();

                    if (!$existing) {
                        $meetingParticipant = new NotificationRecipient();
                        $meetingParticipant->notification_id = $notification->id;
                        $meetingParticipant->email = $externalParticipant['email'];
                        $meetingParticipant->save();
                    }
                } else {
                    $notification->recipients()
                        ->where('email', $externalParticipant['email'])
                        ->delete();
                }
            }
        }

        return redirect()->route('notification.index')->with('success', 'Thông báo đã được cập nhật!');
    }

    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->recipients()->delete();
        $notification->delete();

        return redirect()->route('notification.index')->with('success', 'Thông báo đã được xóa!');
    }
}
