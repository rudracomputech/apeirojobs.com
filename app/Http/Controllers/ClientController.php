<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CountryState;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $query = Client::query();
        
        if ($search) {
            $query->where('client_name', 'like', "%{$search}%")
                  ->orWhere('email_id', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('mobile_no', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
        }
        
        $clients = $query->orderBy('date', 'desc')
                         ->paginate(10);
        
        return view('backend.clients.index', compact('clients', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $client = null;
        $state = CountryState::pluck('default_name', 'default_name');
        return view('backend.clients.create-edit', compact('client', 'state'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'nullable|date',
            'client_name' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:255',
            'contact_person_name' => 'nullable|string|max:255',
            'mobile_no' => 'nullable|string|max:255',
            'email_id' => 'nullable|email|max:255',
            'area' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'client_details' => 'nullable|string',
            'calling_status' => 'nullable|string|max:255',
            'feedback' => 'nullable|string',
            'vacancy_status' => 'nullable|string|max:255',
            'requirement_status' => 'nullable|string|max:255',
            'proposal_status' => 'nullable|string|max:255',
            'empannel' => 'nullable|string|max:255',
            'internship_payment' => 'nullable|string|max:255',
            'final_remark' => 'nullable|string',
        ]);

        Client::create($validated);

        return redirect()
            ->route('clients.index')
            ->with(['status' => 'success', 'message' => 'Client created successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return view('backend.clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        $state = CountryState::pluck('default_name', 'default_name');
        return view('backend.clients.create-edit', compact('client', 'state'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'date' => 'nullable|date',
            'client_name' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:255',
            'contact_person_name' => 'nullable|string|max:255',
            'mobile_no' => 'nullable|string|max:255',
            'email_id' => 'nullable|email|max:255',
            'area' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'client_details' => 'nullable|string',
            'calling_status' => 'nullable|string|max:255',
            'feedback' => 'nullable|string',
            'vacancy_status' => 'nullable|string|max:255',
            'requirement_status' => 'nullable|string|max:255',
            'proposal_status' => 'nullable|string|max:255',
            'empannel' => 'nullable|string|max:255',
            'internship_payment' => 'nullable|string|max:255',
            'final_remark' => 'nullable|string',
        ]);

        $client->update($validated);

        return redirect()
            ->route('clients.index')
            ->with(['status' => 'success', 'message' => 'Client updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()
            ->route('clients.index')
            ->with(['status' => 'success', 'message' => 'Client deleted successfully.']);
    }

    /**
     * Import leads from Excel file.
     */
    public function import(Request $request)
    {


        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120', // 5MB max
        ]);

        try {
            $file = $request->file('file');

            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            if (count($rows) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'The uploaded file does not contain any data rows.',
                ], 422);
            }

            $headerRow = array_shift($rows);
            $headerMap = [];
            foreach ($headerRow as $col => $title) {
                $label = Str::of(strtolower(trim($title)))
                    ->replaceMatches('/[^a-z0-9]+/', ' ')
                    ->trim()
                    ->__toString();

                if (str_contains($label, 'client name') || ($label === 'name' && !isset($headerMap['client_name']))) {
                    $headerMap[$col] = 'client_name';
                } elseif (str_contains($label, 'contact number') || str_contains($label, 'phone')) {
                    $headerMap[$col] = 'contact_number';
                } elseif (str_contains($label, 'contact person') || str_contains($label, 'contact name')) {
                    $headerMap[$col] = 'contact_person_name';
                } elseif (str_contains($label, 'mobile')) {
                    $headerMap[$col] = 'mobile_no';
                } elseif (str_contains($label, 'email')) {
                    $headerMap[$col] = 'email_id';
                } elseif ($label === 'area') {
                    $headerMap[$col] = 'area';
                } elseif ($label === 'city') {
                    $headerMap[$col] = 'city';
                } elseif ($label === 'state') {
                    $headerMap[$col] = 'state';
                } elseif (str_contains($label, 'client details') || str_contains($label, 'details') || str_contains($label, 'description') || str_contains($label, 'remarks')) {
                    $headerMap[$col] = 'client_details';
                } elseif (str_contains($label, 'calling status')) {
                    $headerMap[$col] = 'calling_status';
                } elseif (str_contains($label, 'feedback')) {
                    $headerMap[$col] = 'feedback';
                } elseif (str_contains($label, 'vacancy status')) {
                    $headerMap[$col] = 'vacancy_status';
                } elseif (str_contains($label, 'requirement status')) {
                    $headerMap[$col] = 'requirement_status';
                } elseif (str_contains($label, 'proposal status')) {
                    $headerMap[$col] = 'proposal_status';
                } elseif (str_contains($label, 'empannel') || str_contains($label, 'empanel')) {
                    $headerMap[$col] = 'empannel';
                } elseif (str_contains($label, 'internship payment')) {
                    $headerMap[$col] = 'internship_payment';
                } elseif (str_contains($label, 'final remark') || str_contains($label, 'final remarks')) {
                    $headerMap[$col] = 'final_remark';
                } elseif ($label === 'date') {
                    $headerMap[$col] = 'date';
                }
            }

            if (empty($headerMap)) {
                return response()->json([
                    'success' => false,
                    'message' => 'The file headers could not be mapped to client fields.',
                ], 422);
            }

            $allowed = [
                'date','client_name','contact_number','contact_person_name','mobile_no','email_id','area','city','state','client_details','calling_status','feedback','vacancy_status','requirement_status','proposal_status','empannel','internship_payment','final_remark'
            ];

            $imported = 0;
            $skipped = 0;
            $failed = 0;
            $errors = [];

            foreach ($rows as $rowIndex => $row) {
                if (!array_filter($row)) {
                    continue; // skip empty rows
                }

                $data = [];
                foreach ($headerMap as $col => $field) {
                    if (!in_array($field, $allowed, true)) {
                        continue;
                    }
                    $data[$field] = isset($row[$col]) ? trim($row[$col]) : null;
                }

                if (empty($data['client_name'])) {
                    $skipped++;
                    continue;
                }

                if (!empty($data['date'])) {
                    $timestamp = strtotime($data['date']);
                    if ($timestamp !== false) {
                        $data['date'] = date('Y-m-d', $timestamp);
                    } else {
                        unset($data['date']);
                    }
                }

                try {
                    Client::create($data);
                    $imported++;
                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = "Row " . ($rowIndex + 2) . ": " . $e->getMessage();
                }
            }

            $message = "Imported {$imported} clients.";
            if ($skipped > 0) {
                $message .= " Skipped {$skipped} rows with missing client name.";
            }
            if ($failed > 0) {
                $message .= " Failed {$failed} rows.";
            }

            return response()->json([
                'success' => $imported > 0 && $failed === 0,
                'message' => $message,
                'imported' => $imported,
                'skipped' => $skipped,
                'failed' => $failed,
                'errors' => $errors,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while importing: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Export clients to XLSX.
     */
    public function export(Request $request)
    {
        $ids = $request->query('ids', []);

        $query = Client::query();
        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        }

        $clients = $query->orderBy('date', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'ID','Date','Client Name','Contact Number','Contact Person Name','Mobile No','Email','Area','City','State','Client Details','Calling Status','Vacancy Status','Requirement Status','Proposal Status','Empannel','Internship Payment','Final Remark'
        ];

        $sheet->fromArray($headers, null, 'A1');

        $rowNum = 2;
        foreach ($clients as $c) {
            $row = [
                $c->id,
                $c->date ? $c->date->format('Y-m-d') : null,
                $c->client_name,
                $c->contact_number,
                $c->contact_person_name,
                $c->mobile_no,
                $c->email_id,
                $c->area,
                $c->city,
                $c->state,
                $c->client_details,
                $c->calling_status,
                $c->vacancy_status,
                $c->requirement_status,
                $c->proposal_status,
                $c->empannel,
                $c->internship_payment,
                $c->final_remark,
            ];

            $sheet->fromArray($row, null, 'A' . $rowNum);
            $rowNum++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'clients_export_' . date('Ymd_His') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'clients_export_');
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Handle bulk actions for clients.
     */
    public function bulkAction(Request $request)
    {
        $selectedIds = $request->input('selected_ids', []);
        $action = $request->input('action_type');

        if (empty($selectedIds)) {
            return redirect()->route('clients.index')->with(['status' => 'error', 'message' => 'No clients selected.']);
        }

        switch ($action) {
            case 'delete':
                Client::whereIn('id', $selectedIds)->delete();
                return redirect()->route('clients.index')->with(['status' => 'success', 'message' => 'Clients deleted successfully.']);
            default:
                return redirect()->route('clients.index')->with(['status' => 'error', 'message' => 'Invalid action.']);
        }
    }
}
