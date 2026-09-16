<?php

namespace App\Http\Controllers;

use App\Models\AdmitCard;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdmitCardController extends Controller
{
    /**
     * Get default admit card data.
     */
    protected function getDefaultData(): array
    {
        return [
            'id'                 => null,
            'rollNo'            => '20235801',
            'enrollmentNo'       => 'ACI2023233750',
            'councilName'        => 'All India Council For Vocational & Paramedical Science',
            'bullet1'            => 'Run by All India Council for Vocational & Paramedical Science',
            'bullet2'            => 'Regd. Under MSME, Govt. of India',
            'bullet3'            => 'An Autonomous Institution Registered Under the Trust Act of 1882',
            'bullet4'            => 'AN ISO 9001 : 2008 Certified Organization',
            'cardTitle'          => 'Admit Card',
            'studentName'        => 'Laxman Patole',
            'parentName'         => 'Balasaheb Patole',
            'batch'              => 'June 2023',
            'courseName'         => 'Diploma in General Nursing And Midwifery (GNM)',
            'passYear'           => (string) date('Y'),
            'examCentre'         => 'HS Institute Powered by NBS Welfare Foundation',
            'candidateSigTitle'  => 'Candidate Signature',
            'coordinatorTitle'   => 'Centre Coordinator',
            'controllerTitle'    => 'Examination Controller',
            'disclaimer'         => 'Disclaimer : In case of any change in the Examination Schedule, the Examination schedule placed on AICVPS website will be Treated as Final.',
            'photoSrc'           => asset('admitcard-studio/assets/student-photo.png'),
            'logoSrc'            => asset('admitcard-studio/assets/aicvps-logo.png'),
            'stampSrc'           => asset('admitcard-studio/assets/official-stamp.svg'),
            'showStamp'          => '1',
        ];
    }

    /**
     * Generate repeating watermark pattern text.
     */
    protected function computeWatermarkText(string $source): string
    {
        $clean = strtoupper(trim($source ?: 'All India Council For Vocational & Paramedical Science'));
        $repeat = $clean . ' • ';
        while (strlen($repeat) < 180) {
            $repeat .= $clean . ' • ';
        }
        return $repeat;
    }

    /**
     * Display the Admit Card Studio editor.
     */
    public function index(Request $request): View
    {
        $defaultData = $this->getDefaultData();
        $inputData = $request->all();

        if ($request->filled('id')) {
            $record = AdmitCard::find($request->query('id'));
            if ($record) {
                $defaultData = array_merge($defaultData, [
                    'id' => $record->id,
                    'student_id' => $record->student_id,
                    'rollNo' => $record->roll_no,
                    'enrollmentNo' => $record->enrollment_no,
                    'studentName' => $record->student_name,
                    'parentName' => $record->parent_name,
                    'courseName' => $record->course_name,
                    'batch' => $record->batch,
                    'passYear' => $record->pass_year,
                    'examCentre' => $record->exam_centre,
                    'councilName' => $record->council_name,
                    'photoSrc' => $record->photo ?: $defaultData['photoSrc'],
                ]);
                if (!empty($record->payload) && is_array($record->payload)) {
                    $defaultData = array_merge($defaultData, $record->payload);
                }
            }
        }

        $data = array_merge($defaultData, array_intersect_key($inputData, $defaultData));

        if (!empty($inputData['photoBase64'])) {
            $data['photoSrc'] = $inputData['photoBase64'];
        }
        if (!empty($inputData['logoBase64'])) {
            $data['logoSrc'] = $inputData['logoBase64'];
        }
        if (!empty($inputData['stampBase64'])) {
            $data['stampSrc'] = $inputData['stampBase64'];
        }

        $watermarkSource = !empty($data['councilName']) ? $data['councilName'] : 'All India Council For Vocational & Paramedical Science';
        $watermarkRepeat = $this->computeWatermarkText($watermarkSource);

        $students = Student::select('id', 'name', 'first_name', 'last_name', 'student_code')
            ->orderBy('name')
            ->limit(200)
            ->get();

        return view('backend.admitcard.index', compact('data', 'watermarkRepeat', 'students'));
    }

    /**
     * Display a separate paginated list of all generated Admit Cards in Admin.
     */
    public function records(Request $request): View
    {
        $query = AdmitCard::query()->with('student');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('student_name', 'like', "%{$search}%")
                  ->orWhere('roll_no', 'like', "%{$search}%")
                  ->orWhere('enrollment_no', 'like', "%{$search}%")
                  ->orWhere('course_name', 'like', "%{$search}%")
                  ->orWhere('exam_centre', 'like', "%{$search}%");
            });
        }

        $sort = $request->input('sort', 'id');
        $direction = $request->input('direction', 'desc');
        $allowedSorts = ['id', 'student_name', 'roll_no', 'enrollment_no', 'course_name', 'batch', 'pass_year', 'created_at'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        $limit = $request->input('limit', 15);
        $cards = ($limit === 'all') ? $query->paginate(500) : $query->paginate((int) $limit);

        return view('backend.admitcard.records', compact('cards'));
    }

    /**
     * Save generated Admit Card to database.
     */
    public function save(Request $request): RedirectResponse
    {
        $inputData = $request->all();
        $studentName = trim($request->input('studentName', ''));
        $rollNo = trim($request->input('rollNo', ''));
        $enrollmentNo = trim($request->input('enrollmentNo', ''));
        $courseName = trim($request->input('courseName', ''));
        $parentName = trim($request->input('parentName', ''));
        $batch = trim($request->input('batch', ''));
        $passYear = trim($request->input('passYear', ''));
        $examCentre = trim($request->input('examCentre', ''));
        $councilName = trim($request->input('councilName', ''));

        $photoSrc = $request->input('photoBase64') ?: $request->input('photoSrc');

        $studentId = $request->input('student_id');
        if (!$studentId && !empty($enrollmentNo)) {
            $matchedStudent = Student::where('student_code', $enrollmentNo)->first();
            if ($matchedStudent) {
                $studentId = $matchedStudent->id;
            }
        }

        $recordData = [
            'student_id' => $studentId,
            'student_name' => $studentName,
            'roll_no' => $rollNo,
            'enrollment_no' => $enrollmentNo,
            'course_name' => $courseName,
            'parent_name' => $parentName,
            'batch' => $batch,
            'pass_year' => $passYear,
            'exam_centre' => $examCentre,
            'council_name' => $councilName,
            'photo' => $photoSrc,
            'payload' => $inputData,
            'created_by' => auth()->id(),
        ];

        $recordId = $request->input('record_id') ?: $request->input('id');
        if ($recordId && $record = AdmitCard::find($recordId)) {
            $record->update($recordData);
        } else {
            AdmitCard::create($recordData);
        }

        return redirect()->route('admitcard.records')
            ->with('success', "Admit Card for '{$studentName}' saved successfully to database!");
    }

    /**
     * Display standalone clean print view and persist to database.
     */
    public function print(Request $request): View
    {
        $defaultData = $this->getDefaultData();
        $inputData = $request->all();

        // If printing by ID
        if ($request->filled('id')) {
            $record = AdmitCard::find($request->query('id'));
            if ($record) {
                $defaultData = array_merge($defaultData, [
                    'id' => $record->id,
                    'rollNo' => $record->roll_no,
                    'enrollmentNo' => $record->enrollment_no,
                    'studentName' => $record->student_name,
                    'parentName' => $record->parent_name,
                    'courseName' => $record->course_name,
                    'batch' => $record->batch,
                    'passYear' => $record->pass_year,
                    'examCentre' => $record->exam_centre,
                    'councilName' => $record->council_name,
                    'photoSrc' => $record->photo ?: $defaultData['photoSrc'],
                ]);
                if (!empty($record->payload) && is_array($record->payload)) {
                    $defaultData = array_merge($defaultData, $record->payload);
                }
            }
        }

        $data = array_merge($defaultData, array_intersect_key($inputData, $defaultData));

        if (!empty($inputData['photoBase64'])) {
            $data['photoSrc'] = $inputData['photoBase64'];
        }
        if (!empty($inputData['logoBase64'])) {
            $data['logoSrc'] = $inputData['logoBase64'];
        }
        if (!empty($inputData['stampBase64'])) {
            $data['stampSrc'] = $inputData['stampBase64'];
        }

        // Auto-save generated admit card to DB when printing via POST
        if ($request->isMethod('post') && !empty($data['studentName'])) {
            $studentId = $request->input('student_id');
            if (!$studentId && !empty($data['enrollmentNo'])) {
                $matchedStudent = Student::where('student_code', $data['enrollmentNo'])->first();
                if ($matchedStudent) {
                    $studentId = $matchedStudent->id;
                }
            }

            $recordData = [
                'student_id' => $studentId,
                'student_name' => $data['studentName'],
                'roll_no' => $data['rollNo'] ?? null,
                'enrollment_no' => $data['enrollmentNo'] ?? null,
                'course_name' => $data['courseName'] ?? null,
                'parent_name' => $data['parentName'] ?? null,
                'batch' => $data['batch'] ?? null,
                'pass_year' => $data['passYear'] ?? null,
                'exam_centre' => $data['examCentre'] ?? null,
                'council_name' => $data['councilName'] ?? null,
                'photo' => $data['photoSrc'] ?? null,
                'payload' => $data,
                'created_by' => auth()->id(),
            ];

            $recordId = $request->input('record_id') ?: $request->input('id');
            if ($recordId && $existing = AdmitCard::find($recordId)) {
                $existing->update($recordData);
            } else {
                AdmitCard::create($recordData);
            }
        }

        $watermarkSource = !empty($data['councilName']) ? $data['councilName'] : 'All India Council For Vocational & Paramedical Science';
        $watermarkRepeat = $this->computeWatermarkText($watermarkSource);

        return view('backend.admitcard.print', compact('data', 'watermarkRepeat'));
    }

    /**
     * Delete an Admit Card record.
     */
    public function destroy($id): RedirectResponse
    {
        $card = AdmitCard::findOrFail($id);
        $name = $card->student_name;
        $card->delete();

        return redirect()->route('admitcard.records')->with('success', "Admit Card for '{$name}' deleted successfully.");
    }

    /**
     * Return student details for studio quick-fill.
     */
    public function studentData($id): JsonResponse
    {
        $student = Student::with(['courses' => function ($q) {
            $q->withPivot('batch_id');
        }])->findOrFail($id);

        $courseName = $student->courses->first()?->name ?? '';

        return response()->json([
            'id' => $student->id,
            'name' => $student->name,
            'father_name' => $student->father_name ?? '',
            'enrollment_no' => $student->student_code ?? '',
            'roll_no' => $student->student_code ?? '',
            'course_name' => $courseName,
            'batch' => date('F Y'),
            'pass_year' => date('Y'),
        ]);
    }
}
