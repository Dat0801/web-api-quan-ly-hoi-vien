<?php

namespace App\Http\Controllers\Club;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Club;
use App\Models\Industry;
use App\Models\Field;
use App\Models\Market;
use App\Models\Connector;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClubController extends Controller
{
    //
    public function index(Request $request)
    {
        $fields = Field::all();
        $markets = Market::all();

        $query = Club::query();

        if ($request->filled('field_id')) {
            $query->where('field_id', $request->field_id);
        }

        if ($request->filled('market_id')) {
            $query->where('market_id', $request->market_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_vi', 'like', "%{$search}%")
                    ->orWhere('name_en', 'like', "%{$search}%");
            });
        }

        $clubs = $query->withCount([
            'boardCustomers',
            'businessCustomers',
            'individualCustomers',
            'businessPartners',
            'individualPartners'
        ])->paginate(3);

        return view('club.index', compact('clubs', 'fields', 'markets'));
    }


    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $fileData = array_map('str_getcsv', file($file->getRealPath()));

        $headers = $fileData[0];
        unset($fileData[0]);

        $expectedHeaders = [
            'club_code',
            'name_vi',
            'name_en',
            'name_abbr',
            'address',
            'tax_code',
            'phone',
            'email',
            'website',
            'fanpage',
            'established_date',
            'established_decision',
            'industry_id',
            'field_id',
            'market_id',
            'connector_id',
            'status',
        ];

        if ($headers !== $expectedHeaders) {
            return redirect()->back()->withErrors(['file' => 'Cấu trúc file không hợp lệ.']);
        }

        foreach ($fileData as $row) {
            $clubData = array_combine($headers, $row);
            try {
                Club::create([
                    'club_code' => $clubData['club_code'] ?? null,
                    'name_vi' => $clubData['name_vi'] ?? null,
                    'name_en' => $clubData['name_en'] ?? null,
                    'name_abbr' => $clubData['name_abbr'] ?? null,
                    'address' => $clubData['address'] ?? null,
                    'tax_code' => $clubData['tax_code'] ?? null,
                    'phone' => $clubData['phone'] ?? null,
                    'email' => $clubData['email'] ?? null,
                    'website' => $clubData['website'] ?? null,
                    'fanpage' => $clubData['fanpage'] ?? null,
                    'established_date' => $clubData['established_date'] ?? null,
                    'established_decision' => $clubData['established_decision'] ?? null,
                    'industry_id' => $clubData['industry_id'] ?? null,
                    'field_id' => $clubData['field_id'] ?? null,
                    'market_id' => $clubData['market_id'] ?? null,
                    'status' => $clubData['status'] ?? 'active',
                ]);
            } catch (\Exception $e) {
                continue;
            }
        }

        return redirect()->route('club.index')->with('success', 'File được tải lên và dữ liệu được nhập thành công.');
    }


    public function export()
    {
        $clubs = Club::all();
        $headers = [
            'club_code',
            'name_vi',
            'name_en',
            'name_abbr',
            'address',
            'tax_code',
            'phone',
            'email',
            'website',
            'fanpage',
            'established_date',
            'established_decision',
            'industry_id',
            'field_id',
            'market_id',
            'connector_id',
            'status',
        ];

        $response = new StreamedResponse(function () use ($clubs, $headers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);

            foreach ($clubs as $club) {
                fputcsv($handle, [
                    $club->club_code,
                    $club->name_vi,
                    $club->name_en,
                    $club->name_abbr,
                    $club->address,
                    $club->tax_code,
                    $club->phone,
                    $club->email,
                    $club->website,
                    $club->fanpage,
                    $club->established_date,
                    $club->established_decision,
                    $club->industry_id,
                    $club->field_id,
                    $club->market_id,
                    $club->connector_id,
                    $club->status,
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="clubs.csv"');

        return $response;
    }

    public function create()
    {
        $industries = Industry::all();
        $fields = Field::all();
        $markets = Market::all();
        return view('club.create', compact('industries', 'fields', 'markets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'club_code' => 'required|unique:clubs,club_code',
            'name_vi' => 'required|string',
            'name_en' => 'nullable|string',
            'name_abbr' => 'nullable|string',
            'address' => 'nullable|string',
            'tax_code' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|string',
            'fanpage' => 'nullable|string',
            'established_date' => 'nullable|date',
            'established_decision' => 'nullable|string',
            'industry_id' => 'required|exists:industries,id',
            'field_id' => 'required|exists:fields,id',
            'market_id' => 'required|exists:markets,id',
            'responsible_name.*' => 'nullable|string',
            'responsible_position.*' => 'nullable|string',
            'responsible_phone.*' => 'nullable|string',
            'responsible_gender.*' => 'nullable|in:male,female',
            'responsible_email.*' => 'nullable|email',
        ]);

        $club = Club::create($validated);

        if ($request->has('responsible_name') && count($request->responsible_name) > 0) {
            $connectors = [];
            foreach ($request->responsible_name as $index => $name) {
                if (!empty($name)) {
                    $connectors[] = [
                        'club_id' => $club->id,
                        'name' => $name,
                        'position' => $request->responsible_position[$index] ?? null,
                        'phone' => $request->responsible_phone[$index] ?? null,
                        'gender' => $request->responsible_gender[$index] ?? null,
                        'email' => $request->responsible_email[$index] ?? null,
                    ];
                }
            }

            if (!empty($connectors)) {
                Connector::insert($connectors);
            }
        }

        return redirect()->route('club.index')->with('success', 'Câu lạc bộ được tạo thành công.');
    }


    public function show(Club $club)
    {
        return view('club.show', compact('club'));
    }

    public function edit(Club $club)
    {
        $industries = Industry::all();
        $fields = Field::all();
        $markets = Market::all();
        return view('club.edit', compact('club', 'industries', 'fields', 'markets'));
    }

    public function update(Request $request, Club $club)
    {
        $validated = $request->validate([
            'club_code' => 'required|unique:clubs,club_code,' . $club->id,
            'name_vi' => 'required|string',
            'name_en' => 'nullable|string',
            'name_abbr' => 'nullable|string',
            'address' => 'nullable|string',
            'tax_code' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|string',
            'fanpage' => 'nullable|string',
            'established_date' => 'nullable|date',
            'established_decision' => 'nullable|string',
            'industry_id' => 'required|exists:industries,id',
            'field_id' => 'required|exists:fields,id',
            'market_id' => 'required|exists:markets,id',
            'responsible_name.*' => 'nullable|string',
            'responsible_position.*' => 'nullable|string',
            'responsible_phone.*' => 'nullable|string',
            'responsible_gender.*' => 'nullable|in:male,female',
            'responsible_email.*' => 'nullable|email',
        ]);

        $club->update([
            'club_code' => $request->club_code,
            'name_vi' => $request->name_vi,
            'name_en' => $request->name_en,
            'name_abbr' => $request->name_abbr,
            'address' => $request->address,
            'tax_code' => $request->tax_code,
            'phone' => $request->phone,
            'email' => $request->email,
            'website' => $request->website,
            'fanpage' => $request->fanpage,
            'established_date' => $request->established_date,
            'established_decision' => $request->established_decision,
            'industry_id' => $request->industry_id,
            'field_id' => $request->field_id,
            'market_id' => $request->market_id,
        ]);

        if ($request->has('responsible_name') && count($request->responsible_name) > 0) {
            $club->connector()->delete();

            $connectors = [];
            foreach ($request->responsible_name as $index => $name) {
                if (!empty($name)) {
                    $connectors[] = [
                        'club_id' => $club->id,
                        'name' => $name,
                        'position' => $request->responsible_position[$index] ?? null,
                        'phone' => $request->responsible_phone[$index] ?? null,
                        'gender' => $request->responsible_gender[$index] ?? null,
                        'email' => $request->responsible_email[$index] ?? null,
                    ];
                }
            }

            if (!empty($connectors)) {
                Connector::insert($connectors);
            }
        }

        return redirect()->route('club.index')->with('success', 'Câu lạc bộ được cập nhật thành công.');
    }

    public function destroy(Club $club)
    {
        $club->delete();
        return redirect()->route('club.index')->with('success', 'Câu lạc bộ được xóa thành công.');
    }
}
