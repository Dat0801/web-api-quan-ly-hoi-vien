<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\BoardCustomer;
use App\Models\BusinessCustomer;
use App\Models\IndividualCustomer;
use App\Models\BusinessPartner;
use App\Models\IndividualPartner;
use App\Models\MeetingParticipant;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    //
    public function index(Request $request)
    {
        $search = $request->search;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $meetings = Meeting::with('participants')
            ->when($search, function ($query) use ($search) {
                return $query->where('title', 'LIKE', "%$search%");
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('start_time', [$startDate, $endDate]);
            })
            ->paginate(10);

        return view('meeting.index', compact('meetings'));
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
            'meeting.create',
            compact('allParticipants', 'fields', 'markets', 'targetCustomerGroups', 'businessScales', 'participantTypes')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'host' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date',
            'selected_members' => 'nullable|array',
            'new_participants' => 'nullable|array',
            'new_participants.*.email' => 'nullable|email',
        ]);
        $meeting = new Meeting();
        $meeting->host = $request->host;
        $meeting->title = $request->title;
        $meeting->content = $request->content;
        $meeting->location = $request->location;
        $meeting->start_time = $request->start_time;
        $meeting->save();

        if ($request->has('selected_members')) {
            foreach ($request->selected_members as $selected_member) {
                $customerModel = null;
                $type = $selected_member['type'];
                $participantId = $selected_member['id'];
                $customerModel = $type::find($participantId);
                if ($customerModel) {
                    $meetingParticipant = new MeetingParticipant();
                    $meetingParticipant->meeting_id = $meeting->id;
                    $meetingParticipant->participantable_id = $customerModel->id;
                    $meetingParticipant->participantable_type = get_class($customerModel);
                    $meetingParticipant->save();
                }
            }
        }

        if ($request->has('new_participants')) {
            foreach ($request->new_participants as $newParticipant) {
                $meetingParticipant = new MeetingParticipant();
                $meetingParticipant->meeting_id = $meeting->id;
                $meetingParticipant->external_email = $newParticipant['email'];
                $meetingParticipant->save();
            }
        }

        return redirect()->route('meeting.index')->with('success', 'Meeting created successfully!');
    }

    public function show($id)
    {
        $meeting = Meeting::with(['participants.participantable'])
            ->findOrFail($id);

        $meeting->participants->each(function ($participant) {
            if ($participant->participantable && method_exists($participant->participantable, 'market')) {
                $participant->participantable->load('market');
            }
        });

        $participantTypes = [
            BoardCustomer::class => 'Ban chấp hành',
            BusinessCustomer::class => 'Khách hàng doanh nghiệp',
            IndividualCustomer::class => 'Khách hàng cá nhân',
            BusinessPartner::class => 'Đối tác doanh nghiệp',
            IndividualPartner::class => 'Đối tác cá nhân',
        ];

        $externalParticipants = $meeting->participants->filter(function ($participant) {
            return $participant->participantable_type === null;
        });
        return view('meeting.show', compact('meeting', 'participantTypes', 'externalParticipants'));
    }

    public function edit($id)
    {
        $meeting = Meeting::findOrFail($id);
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

        $externalParticipants = $meeting->participants->filter(function ($participant) {
            return $participant->participantable_type === null;
        });

        return view(
            'meeting.edit',
            compact(
                'meeting',
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
            'host' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date',
            'selected_members' => 'nullable|array',
            'external_participants' => 'nullable|array',
            'external_participants.*.email' => 'nullable|email',
        ]);

        $meeting = Meeting::findOrFail($id);
        $meeting->host = $request->host;
        $meeting->title = $request->title;
        $meeting->content = $request->content;
        $meeting->location = $request->location;
        $meeting->start_time = $request->start_time;
        $meeting->save();

        if ($request->has('selected_members')) {
            $existingParticipants = $meeting->participants()
                ->select('participantable_id', 'participantable_type') 
                ->get()
                ->map(function ($participant) {
                    return [
                        'id' => $participant->participantable_id,
                        'type' => $participant->participantable_type
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

            MeetingParticipant::whereIn('participantable_id', $idsToRemove)
                ->where('meeting_id', $meeting->id)
                ->delete();

            foreach ($request->selected_members as $selected_member) {
                $type = $selected_member['type'];
                $participantId = $selected_member['id'];
                $existing = $meeting->participants->where('participantable_id', $participantId)
                    ->where('participantable_type', $type)
                    ->first();

                if (!$existing) {
                    $customerModel = $type::find($participantId);
                    if ($customerModel) {
                        $meetingParticipant = new MeetingParticipant();
                        $meetingParticipant->meeting_id = $meeting->id;
                        $meetingParticipant->participantable_id = $customerModel->id;
                        $meetingParticipant->participantable_type = get_class($customerModel);
                        $meetingParticipant->save();
                    }
                }
            }
        } else {
            $meeting->participants()->delete();
        }
        if ($request->has('external_participants')) {
            foreach ($request->external_participants as $externalParticipant) {
                if (!empty($externalParticipant['checked']) && !empty($externalParticipant['email'])) {
                    $existing = $meeting->participants()
                        ->where('external_email', $externalParticipant['email'])
                        ->first();

                    if (!$existing) {
                        $meetingParticipant = new MeetingParticipant();
                        $meetingParticipant->meeting_id = $meeting->id;
                        $meetingParticipant->external_email = $externalParticipant['email'];
                        $meetingParticipant->save();
                    }
                } else {
                    $meeting->participants()
                        ->where('external_email', $externalParticipant['email'])
                        ->delete();
                }
            }
        }

        return redirect()->route('meeting.index')->with('success', 'Cuộc họp đã được cập nhật thành công.');
    }

    public function destroy($id)
    {
        $meeting = Meeting::findOrFail($id);
        $meeting->participants()->delete();
        $meeting->delete();

        return redirect()->route('meeting.index')->with('success', 'Cuộc họp đã được xóa thành công.');
    }
}
