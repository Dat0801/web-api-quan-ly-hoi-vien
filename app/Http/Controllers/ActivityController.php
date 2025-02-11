<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\BoardCustomer;
use App\Models\BusinessCustomer;
use App\Models\IndividualCustomer;
use App\Models\BusinessPartner;
use App\Models\IndividualPartner;

class ActivityController extends Controller
{
    //
    public function index(Request $request)
    {
        $search = $request->search;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $activities = Activity::with('participants')
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'LIKE', "%$search%");
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('start_time', [$startDate, $endDate]);
            })
            ->paginate(10);

        return view('activities.index', compact('activities'));
    }

    public function create()
    {
        $participantTypes = [
            BoardCustomer::class => 'Ban chấp hành',
            BusinessCustomer::class => 'Khách hàng doanh nghiệp',
            IndividualCustomer::class => 'Khách hàng cá nhân',
            BusinessPartner::class => 'Đối tác doanh nghiệp',
            IndividualPartner::class => 'Đối tác cá nhân',
        ];

        return view('activities.create', compact('participantTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'location' => 'required|string',
            'content' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpg,png,pdf,doc,docx|max:10240',
            'participants' => 'required|array',
            'participants.*' => 'string',
            'external_participants' => 'nullable|array',
            'external_participants.*.name' => 'nullable|string',
            'external_participants.*.email' => 'nullable|email',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = $file->getClientOriginalName();
            $uniqueFileName = uniqid() . '_' . $fileName;
            $attachmentPath = $file->storeAs('attachments', $uniqueFileName, 'public');
        }

        $activity = Activity::create([
            'name' => $validated['name'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'location' => $validated['location'],
            'content' => $validated['content'],
            'attachment' => $attachmentPath,
        ]);

        if (in_array('all', $validated['participants'])) {
            $participantTypes = [
                BoardCustomer::class,
                BusinessCustomer::class,
                IndividualCustomer::class,
                BusinessPartner::class,
                IndividualPartner::class,
            ];

            foreach ($participantTypes as $type) {
                $activity->participants()->create([
                    'participantable_type' => $type,
                    'activity_id' => $activity->id,
                ]);
            }
        } else {
            foreach ($validated['participants'] as $participantType) {
                $activity->participants()->create([
                    'participantable_type' => $participantType,
                    'activity_id' => $activity->id,
                ]);
            }
        }

        if (!empty($validated['external_participants'])) {
            foreach ($validated['external_participants'] as $externalParticipant) {
                $activity->participants()->create([
                    'activity_id' => $activity->id,
                    'external_name' => $externalParticipant['name'],
                    'external_email' => $externalParticipant['email'],
                ]);
            }
        }


        return redirect()->route('activity.index')->with('success', 'Hoạt động đã được tạo thành công.');
    }


    public function show($id)
    {
        $activity = Activity::with('participants.participantable')->findOrFail($id);

        $participantTypes = [
            BoardCustomer::class => 'Ban chấp hành',
            BusinessCustomer::class => 'Khách hàng doanh nghiệp',
            IndividualCustomer::class => 'Khách hàng cá nhân',
            BusinessPartner::class => 'Đối tác doanh nghiệp',
            IndividualPartner::class => 'Đối tác cá nhân',
        ];

        $externalParticipants = $activity->participants->filter(function ($participant) {
            return $participant->participantable_type === null;
        })->map(function ($participant) {
            return [
                'name' => $participant->external_name,
                'email' => $participant->external_email,
            ];
        });

        return view('activities.show', compact('activity', 'participantTypes', 'externalParticipants'));
    }

    public function edit($id)
    {
        $activity = Activity::with('participants.participantable')->findOrFail($id);

        $participantTypes = [
            BoardCustomer::class => 'Ban chấp hành',
            BusinessCustomer::class => 'Khách hàng doanh nghiệp',
            IndividualCustomer::class => 'Khách hàng cá nhân',
            BusinessPartner::class => 'Đối tác doanh nghiệp',
            IndividualPartner::class => 'Đối tác cá nhân',
        ];

        $externalParticipants = $activity->participants->filter(function ($participant) {
            return $participant->participantable_type === null;
        })->map(function ($participant) {
            return [
                'name' => $participant->external_name,
                'email' => $participant->external_email,
            ];
        });

        return view('activities.edit', compact('activity', 'participantTypes', 'externalParticipants'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|date',
            'location' => 'required|string|max:255',
            'content' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,pdf,docx,zip|max:2048',
            'participants' => 'nullable|array',
            'participants.*' => 'nullable|string',
            'external_participants' => 'nullable|array',
            'external_participants.*.name' => 'nullable|string',
            'external_participants.*.email' => 'nullable|email',
        ]);


        $startTime = Carbon::parse($request->input('start_time'));
        $endTime = Carbon::parse($request->input('end_time'));

        if ($endTime->lt($startTime)) {
            $endTime->addDay();
        }

        if ($endTime->lt($startTime)) {
            return back()->withErrors(['end_time' => 'Thời gian kết thúc phải sau hoặc bằng thời gian bắt đầu.']);
        }

        $activity = Activity::findOrFail($id);

        $activity->update([
            'name' => $validatedData['name'],
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
            'location' => $validatedData['location'],
            'content' => $validatedData['content'],
        ]);

        if ($request->hasFile('attachment')) {
            if ($activity->attachment) {
                $oldAttachmentPath = public_path('storage/' . $activity->attachment);
                if (file_exists($oldAttachmentPath)) {
                    unlink($oldAttachmentPath);
                }
            }

            $file = $request->file('attachment');
            $fileName = $file->getClientOriginalName();
            $uniqueFileName = uniqid() . '_' . $fileName;
            $attachmentPath = $file->storeAs('attachments', $uniqueFileName, 'public');
            $activity->attachment = $attachmentPath;
            $activity->save();
        }

        if (isset($validatedData['participants'])) {
            $activity->participants()->delete();
            foreach ($validatedData['participants'] as $participantType) {
                $activity->participants()->create([
                    'participantable_type' => $participantType,
                ]);
            }
        }

        if (isset($validatedData['external_participants'])) {
            foreach ($validatedData['external_participants'] as $externalParticipant) {
                $activity->participants()->create([
                    'external_name' => $externalParticipant['name'],
                    'external_email' => $externalParticipant['email'],
                ]);
            }
        }

        return redirect()->route('activity.index')->with('success', 'Hoạt động đã được cập nhật thành công.');
    }

    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);

        foreach ($activity->participants as $participant) {
            if (is_null($participant->participantable_type)) {
            }

            $participant->delete();
        }

        $activity->delete();

        return redirect()->route('activity.index')->with('success', 'Hoạt động đã được xóa thành công.');
    }

    public function showParticipants($id)
    {
        $activity = Activity::with('participants.participantable')->findOrFail($id);
        $participants = $activity->participants;

        $participantTypeFilter = request('participant_type');

        $typeMappings = [
            BoardCustomer::class => ['name' => 'full_name', 'email' => 'email', 'login_code' => 'login_code'],
            BusinessCustomer::class => ['name' => 'business_name_vi', 'email' => 'email', 'login_code' => 'login_code'],
            IndividualCustomer::class => ['name' => 'full_name', 'email' => 'email', 'login_code' => 'login_code'],
            BusinessPartner::class => ['name' => 'business_name_vi', 'email' => 'email', 'login_code' => 'login_code'],
            IndividualPartner::class => ['name' => 'full_name', 'email' => 'email', 'login_code' => 'login_code'],
        ];

        $participantDetails = $participants->map(function ($participant) use ($typeMappings) {
            $participantData = [
                'name' => $participant->external_name,
                'email' => $participant->external_email,
                'type' => 'External Participant',
                'login_code' => $participant->external_login_code ?? '-',
                'participated' => $participant->participated,
                'participation_date' => $participant->participation_date,
            ];

            if ($participant->participantable_type) {
                $participantableType = $participant->participantable_type;

                if (isset($typeMappings[$participantableType])) {
                    $attributes = $typeMappings[$participantableType];

                    $modelClass = $participantableType;

                    $participantableRecords = $modelClass::all();

                    foreach ($participantableRecords as $participantable) {
                        if ($participantable->some_identifier == $participant->some_identifier) {
                            $participantData['name'] = $participantable->{$attributes['name']} ?? '-';
                            $participantData['email'] = $participantable->{$attributes['email']} ?? '-';
                            $participantData['login_code'] = $participantable->{$attributes['login_code']} ?? '-';
                            $participantData['type'] = class_basename($participantable);
                        }
                    }
                }
            }

            return $participantData;
        });

        if ($participantTypeFilter) {
            $participantDetails = $participantDetails->filter(function ($participant) use ($participantTypeFilter) {
                return $participant['type'] === class_basename($participantTypeFilter);
            });
        }

        $totalCustomers = $participants->count();
        $participatingCustomers = 0;

        if (now() >= $activity->start_time) {
            $participatingCustomers = $totalCustomers;
        }
        
        $nonParticipatingCustomers = $totalCustomers - $participatingCustomers;

        $participantTypes = [
            BoardCustomer::class => 'Ban chấp hành',
            BusinessCustomer::class => 'Khách hàng doanh nghiệp',
            IndividualCustomer::class => 'Khách hàng cá nhân',
            BusinessPartner::class => 'Đối tác doanh nghiệp',
            IndividualPartner::class => 'Đối tác cá nhân',
        ];

        return view('activities.participants', compact(
            'activity',
            'participantDetails',
            'totalCustomers',
            'participatingCustomers',
            'nonParticipatingCustomers',
            'participantTypes'
        ));
    }
}
