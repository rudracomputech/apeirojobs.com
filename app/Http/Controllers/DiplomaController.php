<?php

namespace App\Http\Controllers;

use App\Models\Diploma;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiplomaController extends Controller
{
    /**
     * Get default certificate data (matching official reference).
     */
    protected function getDefaultData(): array
    {
        return [
            'id' => null,
            'serialNo' => '20235801',
            'gender' => 'Mr.',
            'studentName' => 'Laxman Patole',
            'fatherName' => 'Balasaheb Patole',
            'enrollNo' => 'ACI2023233750',
            'instituteName' => 'HS Institute Powered by NBS Welfare Foundation',
            'location' => 'Palghar (Maharashtra)',
            'courseCategory' => 'Paramedical Courses',
            'courseName' => 'Diploma in General Nursing And Midwifery (GNM)',
            'division' => '1st Division',
            'certDate' => '2025-06-29',
            'place' => 'Delhi',
            'studentPhoto' => asset('diploma-studio/assets/student-sample.jpg'),
            'instituteLogo' => asset('diploma-studio/assets/aicvps-logo.png'),
            'councilName' => 'All India Council for Vocational & Paramedical Science',
            'footerSeal' => asset('diploma-studio/assets/india-seal.svg'),
            'scannerImage' => asset('diploma-studio/assets/images/qr_code.png'),
            'metaRunBy' => 'Run by All India Council for Vocational & Paramedical Science',
            'metaRegd' => 'Regd. Under MSME, Govt. of India',
            'metaAutonomous' => 'An Autonomous Institution Registered Under the Trust Act of 1882',
            'metaIso' => 'AN ISO 9001 : 2008 Certified Organization',
        ];
    }

    /**
     * Dynamic repeating watermark text based on council name.
     */
    protected function computeWatermarkText(string $councilName): string
    {
        $clean = strtoupper(trim($councilName ?: 'All India Council for Vocational & Paramedical Science'));
        $repeat = $clean . ' • ';
        while (strlen($repeat) < 160) {
            $repeat .= $clean . ' • ';
        }
        return $repeat;
    }

    /**
     * Format division string with superscript ordinals.
     */
    public static function formatDivision(?string $text): string
    {
        if (!$text) {
            return '';
        }
        return preg_replace('/(\b\d+)(st|nd|rd|th)\b/i', '$1<sup>$2</sup>', e((string) $text));
    }

    /**
     * Compute formatted date parts.
     */
    protected function computeDateParts(string $certDate): array
    {
        $timestamp = strtotime($certDate) ?: strtotime('2025-06-29');
        $dayNum = (int) date('j', $timestamp);
        $suffix = match (true) {
            in_array($dayNum, [11, 12, 13]) => 'th',
            $dayNum % 10 === 1 => 'st',
            $dayNum % 10 === 2 => 'nd',
            $dayNum % 10 === 3 => 'rd',
            default => 'th',
        };

        return [
            'day' => $dayNum . $suffix,
            'monthYear' => date('M Y', $timestamp),
        ];
    }

    /**
     * Display the Diploma Generator Studio.
     */
    public function index(Request $request): View
    {
        $defaultData = $this->getDefaultData();
        $inputData = $request->all();

        if ($request->filled('id')) {
            $record = Diploma::find($request->query('id'));
            if ($record) {
                $defaultData = array_merge($defaultData, [
                    'id' => $record->id,
                    'student_id' => $record->student_id,
                    'serialNo' => $record->serial_no,
                    'gender' => $record->gender,
                    'studentName' => $record->student_name,
                    'fatherName' => $record->father_name,
                    'enrollNo' => $record->enrollment_no,
                    'courseName' => $record->course_name,
                    'courseCategory' => $record->course_category,
                    'instituteName' => $record->institute_name,
                    'location' => $record->location,
                    'division' => $record->division,
                    'certDate' => $record->cert_date,
                    'place' => $record->place,
                    'studentPhoto' => $record->photo ?: $defaultData['studentPhoto'],
                ]);
                if (!empty($record->payload) && is_array($record->payload)) {
                    $defaultData = array_merge($defaultData, $record->payload);
                }
            }
        }

        $data = array_merge($defaultData, array_intersect_key($inputData, $defaultData));

        if (!empty($inputData['studentPhoto_data'])) {
            $data['studentPhoto'] = $inputData['studentPhoto_data'];
        }
        if (!empty($inputData['instituteLogo_data'])) {
            $data['instituteLogo'] = $inputData['instituteLogo_data'];
        }
        if (!empty($inputData['footerSeal_data'])) {
            $data['footerSeal'] = $inputData['footerSeal_data'];
        }
        if (!empty($inputData['scannerImage_data'])) {
            $data['scannerImage'] = $inputData['scannerImage_data'];
        }

        $watermarkRepeat = $this->computeWatermarkText($data['councilName']);
        $dateParts = $this->computeDateParts($data['certDate']);

        $students = Student::select('id', 'name', 'first_name', 'last_name', 'student_code')
            ->orderBy('name')
            ->limit(200)
            ->get();

        return view('backend.diploma.index', compact('data', 'watermarkRepeat', 'dateParts', 'students'));
    }

    /**
     * Display a separate paginated list of all generated Diplomas in Admin.
     */
    public function records(Request $request): View
    {
        $query = Diploma::query()->with('student');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('student_name', 'like', "%{$search}%")
                  ->orWhere('serial_no', 'like', "%{$search}%")
                  ->orWhere('enrollment_no', 'like', "%{$search}%")
                  ->orWhere('course_name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('division', 'like', "%{$search}%");
            });
        }

        $sort = $request->input('sort', 'id');
        $direction = $request->input('direction', 'desc');
        $allowedSorts = ['id', 'student_name', 'serial_no', 'enrollment_no', 'course_name', 'division', 'cert_date', 'created_at'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        $limit = $request->input('limit', 15);
        $diplomas = ($limit === 'all') ? $query->paginate(500) : $query->paginate((int) $limit);

        return view('backend.diploma.records', compact('diplomas'));
    }

    /**
     * Save generated Diploma to database.
     */
    public function save(Request $request): RedirectResponse
    {
        $inputData = $request->all();
        $studentName = trim($request->input('studentName', ''));
        $fatherName = trim($request->input('fatherName', ''));
        $gender = trim($request->input('gender', 'Mr.'));
        $serialNo = trim($request->input('serialNo', ''));
        $enrollmentNo = trim($request->input('enrollNo', ''));
        $courseName = trim($request->input('courseName', ''));
        $courseCategory = trim($request->input('courseCategory', ''));
        $instituteName = trim($request->input('instituteName', ''));
        $location = trim($request->input('location', ''));
        $division = trim($request->input('division', ''));
        $certDate = trim($request->input('certDate', ''));
        $place = trim($request->input('place', ''));

        $photo = $request->input('studentPhoto_data') ?: $request->input('studentPhoto');

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
            'father_name' => $fatherName,
            'gender' => $gender,
            'serial_no' => $serialNo,
            'enrollment_no' => $enrollmentNo,
            'course_name' => $courseName,
            'course_category' => $courseCategory,
            'institute_name' => $instituteName,
            'location' => $location,
            'division' => $division,
            'cert_date' => $certDate,
            'place' => $place,
            'photo' => $photo,
            'payload' => $inputData,
            'created_by' => auth()->id(),
        ];

        $recordId = $request->input('record_id') ?: $request->input('id');
        if ($recordId && $record = Diploma::find($recordId)) {
            $record->update($recordData);
        } else {
            Diploma::create($recordData);
        }

        return redirect()->route('diploma.records')
            ->with('success', "Diploma for '{$studentName}' saved successfully to database!");
    }

    /**
     * Display standalone clean print view and auto-persist to database.
     */
    public function print(Request $request): View
    {
        $defaultData = $this->getDefaultData();
        $inputData = $request->all();

        if ($request->filled('id')) {
            $record = Diploma::find($request->query('id'));
            if ($record) {
                $defaultData = array_merge($defaultData, [
                    'id' => $record->id,
                    'serialNo' => $record->serial_no,
                    'gender' => $record->gender,
                    'studentName' => $record->student_name,
                    'fatherName' => $record->father_name,
                    'enrollNo' => $record->enrollment_no,
                    'courseName' => $record->course_name,
                    'courseCategory' => $record->course_category,
                    'instituteName' => $record->institute_name,
                    'location' => $record->location,
                    'division' => $record->division,
                    'certDate' => $record->cert_date,
                    'place' => $record->place,
                    'studentPhoto' => $record->photo ?: $defaultData['studentPhoto'],
                ]);
                if (!empty($record->payload) && is_array($record->payload)) {
                    $defaultData = array_merge($defaultData, $record->payload);
                }
            }
        }

        $data = array_merge($defaultData, array_intersect_key($inputData, $defaultData));

        if (!empty($inputData['studentPhoto_data'])) {
            $data['studentPhoto'] = $inputData['studentPhoto_data'];
        }
        if (!empty($inputData['instituteLogo_data'])) {
            $data['instituteLogo'] = $inputData['instituteLogo_data'];
        }
        if (!empty($inputData['footerSeal_data'])) {
            $data['footerSeal'] = $inputData['footerSeal_data'];
        }
        if (!empty($inputData['scannerImage_data'])) {
            $data['scannerImage'] = $inputData['scannerImage_data'];
        }

        // Auto-save generated diploma to database when submitting via POST
        if ($request->isMethod('post') && !empty($data['studentName'])) {
            $studentId = $request->input('student_id');
            if (!$studentId && !empty($data['enrollNo'])) {
                $matchedStudent = Student::where('student_code', $data['enrollNo'])->first();
                if ($matchedStudent) {
                    $studentId = $matchedStudent->id;
                }
            }

            $recordData = [
                'student_id' => $studentId,
                'student_name' => $data['studentName'],
                'father_name' => $data['fatherName'] ?? null,
                'gender' => $data['gender'] ?? 'Mr.',
                'serial_no' => $data['serialNo'] ?? null,
                'enrollment_no' => $data['enrollNo'] ?? null,
                'course_name' => $data['courseName'] ?? null,
                'course_category' => $data['courseCategory'] ?? null,
                'institute_name' => $data['instituteName'] ?? null,
                'location' => $data['location'] ?? null,
                'division' => $data['division'] ?? null,
                'cert_date' => $data['certDate'] ?? null,
                'place' => $data['place'] ?? null,
                'photo' => $data['studentPhoto'] ?? null,
                'payload' => $data,
                'created_by' => auth()->id(),
            ];

            $recordId = $request->input('record_id') ?: $request->input('id');
            if ($recordId && $existing = Diploma::find($recordId)) {
                $existing->update($recordData);
            } else {
                Diploma::create($recordData);
            }
        }

        $watermarkRepeat = $this->computeWatermarkText($data['councilName']);
        $dateParts = $this->computeDateParts($data['certDate']);

        return view('backend.diploma.print', compact('data', 'watermarkRepeat', 'dateParts'));
    }

    /**
     * Delete a Diploma record.
     */
    public function destroy($id): RedirectResponse
    {
        $diploma = Diploma::findOrFail($id);
        $name = $diploma->student_name;
        $diploma->delete();

        return redirect()->route('diploma.records')->with('success', "Diploma for '{$name}' deleted successfully.");
    }

    /**
     * Return student details for studio quick-fill.
     */
    public function studentData($id): JsonResponse
    {
        $student = Student::with(['courses' => function ($q) {
            $q->withPivot('batch_id');
        }])->findOrFail($id);

        $firstCourse = $student->courses->first();
        $courseName = $firstCourse?->name ?? '';

        $prefix = match (strtolower((string) $student->gender)) {
            'female', 'f' => 'Ms.',
            default => 'Mr.',
        };

        $location = 'Palghar (Maharashtra)';
        if (!empty($student->city)) {
            $location = $student->city . (!empty($student->state) ? ' (' . $student->state . ')' : '');
        }

        return response()->json([
            'id' => $student->id,
            'gender' => $prefix,
            'name' => $student->name,
            'father_name' => $student->father_name ?? '',
            'enrollment_no' => $student->student_code ?? '',
            'serial_no' => '20' . str_pad((string) $student->id, 6, '0', STR_PAD_LEFT),
            'course_name' => $courseName,
            'course_category' => 'Vocational & Paramedical Courses',
            'location' => $location,
            'division' => '1st Division',
            'cert_date' => date('Y-m-d'),
            'place' => !empty($student->city) ? $student->city : 'Delhi',
        ]);
    }
}
