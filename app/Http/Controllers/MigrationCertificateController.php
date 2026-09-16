<?php

namespace App\Http\Controllers;

use App\Models\MigrationCertificate;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MigrationCertificateController extends Controller
{
    /**
     * Get default certificate data.
     */
    protected function getDefaultData(): array
    {
        return [
            'id' => null,
            'serialNo' => '20251997',
            'councilName' => 'All India Council For Vocational & Paramedical Science',
            'bullet1' => 'Run by All India Council for Vocational & Paramedical Science',
            'bullet2' => 'Regd. Under MSME, Govt. of India',
            'bullet3' => 'An Autonomous Institution Registered Under the Trust Act of 1882',
            'bullet4' => 'AN ISO 9001 : 2008 Certified Organization',
            'certTitle' => 'Migration Certificate',
            'studentPrefix' => 'Mr./Ms.',
            'studentName' => 'Laxman Patole',
            'sdPrefix' => 'S/D of',
            'parentName' => 'Balasaheb Patole',
            'passedText' => 'has passed the',
            'courseName' => 'Diploma In General Nursing And Midwifery (GNM)',
            'fromText' => 'from',
            'instName' => 'All India Council for Vocational & Paramedical Science',
            'inTheYearText' => 'in the year',
            'passYear' => (string) date('Y'),
            'bearingText' => 'bearing',
            'enrollmentLabel' => 'Enrollment Number',
            'enrollmentNo' => 'ACI2023233750',
            'clause1' => 'This Institution has no objection in his/her joining',
            'clause2' => 'any recognized College/Institution or taking examination of any Board Established by law.',
            'placeName' => 'Delhi',
            'certDate' => date('d-m-Y'),
            'signatoryTitle' => 'Director',
            'logoSrc' => asset('migration-studio/assets/council-logo.svg'),
            'stampSrc' => asset('migration-studio/assets/official-stamp.svg'),
            'watermarkText' => 'All India Council For Vocational & Paramedical Science',
        ];
    }

    /**
     * Generate repeating watermark pattern text.
     */
    protected function computeWatermarkText(string $source): string
    {
        $clean = strtoupper(trim($source ?: 'All India Council For Vocational & Paramedical Science'));
        $repeat = $clean . ' • ';
        while (strlen($repeat) < 160) {
            $repeat .= $clean . ' • ';
        }
        return $repeat;
    }

    /**
     * Display the Migration Certificate Studio editor.
     */
    public function index(Request $request): View
    {
        $defaultData = $this->getDefaultData();
        $inputData = $request->all();

        if ($request->filled('id')) {
            $record = MigrationCertificate::find($request->query('id'));
            if ($record) {
                $defaultData = array_merge($defaultData, [
                    'id' => $record->id,
                    'student_id' => $record->student_id,
                    'serialNo' => $record->serial_no,
                    'studentPrefix' => $record->student_prefix,
                    'studentName' => $record->student_name,
                    'sdPrefix' => $record->sd_prefix,
                    'parentName' => $record->parent_name,
                    'enrollmentNo' => $record->enrollment_no,
                    'courseName' => $record->course_name,
                    'passYear' => $record->pass_year,
                    'instName' => $record->inst_name,
                    'placeName' => $record->place_name,
                    'certDate' => $record->cert_date,
                    'signatoryTitle' => $record->signatory_title,
                ]);
                if (!empty($record->payload) && is_array($record->payload)) {
                    $defaultData = array_merge($defaultData, $record->payload);
                }
            }
        }

        $data = array_merge($defaultData, array_intersect_key($inputData, $defaultData));

        if (!empty($inputData['logoSrc_data'])) {
            $data['logoSrc'] = $inputData['logoSrc_data'];
        }
        if (!empty($inputData['stampSrc_data'])) {
            $data['stampSrc'] = $inputData['stampSrc_data'];
        }

        $watermarkSource = !empty($data['watermarkText']) ? $data['watermarkText'] : $data['councilName'];
        $watermarkRepeat = $this->computeWatermarkText($watermarkSource);

        $students = Student::select('id', 'name', 'first_name', 'last_name', 'student_code')
            ->orderBy('name')
            ->limit(200)
            ->get();

        return view('backend.migration.index', compact('data', 'watermarkRepeat', 'watermarkSource', 'students'));
    }

    /**
     * Display a separate paginated list of all generated Migration Certificates in Admin.
     */
    public function records(Request $request): View
    {
        $query = MigrationCertificate::query()->with('student');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('student_name', 'like', "%{$search}%")
                  ->orWhere('serial_no', 'like', "%{$search}%")
                  ->orWhere('enrollment_no', 'like', "%{$search}%")
                  ->orWhere('course_name', 'like', "%{$search}%")
                  ->orWhere('parent_name', 'like', "%{$search}%")
                  ->orWhere('inst_name', 'like', "%{$search}%");
            });
        }

        $sort = $request->input('sort', 'id');
        $direction = $request->input('direction', 'desc');
        $allowedSorts = ['id', 'student_name', 'serial_no', 'enrollment_no', 'course_name', 'pass_year', 'cert_date', 'created_at'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        $limit = $request->input('limit', 15);
        $certificates = ($limit === 'all') ? $query->paginate(500) : $query->paginate((int) $limit);

        return view('backend.migration.records', compact('certificates'));
    }

    /**
     * Save generated Migration Certificate to database.
     */
    public function save(Request $request): RedirectResponse
    {
        $inputData = $request->all();
        $studentName = trim($request->input('studentName', ''));
        $studentPrefix = trim($request->input('studentPrefix', 'Mr./Ms.'));
        $sdPrefix = trim($request->input('sdPrefix', 'S/D of'));
        $parentName = trim($request->input('parentName', ''));
        $serialNo = trim($request->input('serialNo', ''));
        $enrollmentNo = trim($request->input('enrollmentNo', ''));
        $courseName = trim($request->input('courseName', ''));
        $passYear = trim($request->input('passYear', ''));
        $instName = trim($request->input('instName', ''));
        $placeName = trim($request->input('placeName', ''));
        $certDate = trim($request->input('certDate', ''));
        $signatoryTitle = trim($request->input('signatoryTitle', ''));

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
            'student_prefix' => $studentPrefix,
            'sd_prefix' => $sdPrefix,
            'parent_name' => $parentName,
            'serial_no' => $serialNo,
            'enrollment_no' => $enrollmentNo,
            'course_name' => $courseName,
            'pass_year' => $passYear,
            'inst_name' => $instName,
            'place_name' => $placeName,
            'cert_date' => $certDate,
            'signatory_title' => $signatoryTitle,
            'payload' => $inputData,
            'created_by' => auth()->id(),
        ];

        $recordId = $request->input('record_id') ?: $request->input('id');
        if ($recordId && $record = MigrationCertificate::find($recordId)) {
            $record->update($recordData);
        } else {
            MigrationCertificate::create($recordData);
        }

        return redirect()->route('migration.records')
            ->with('success', "Migration Certificate for '{$studentName}' saved successfully to database!");
    }

    /**
     * Display the standalone clean print view and auto-save to database.
     */
    public function print(Request $request): View
    {
        $defaultData = $this->getDefaultData();
        $inputData = $request->all();

        if ($request->filled('id')) {
            $record = MigrationCertificate::find($request->query('id'));
            if ($record) {
                $defaultData = array_merge($defaultData, [
                    'id' => $record->id,
                    'serialNo' => $record->serial_no,
                    'studentPrefix' => $record->student_prefix,
                    'studentName' => $record->student_name,
                    'sdPrefix' => $record->sd_prefix,
                    'parentName' => $record->parent_name,
                    'enrollmentNo' => $record->enrollment_no,
                    'courseName' => $record->course_name,
                    'passYear' => $record->pass_year,
                    'instName' => $record->inst_name,
                    'placeName' => $record->place_name,
                    'certDate' => $record->cert_date,
                    'signatoryTitle' => $record->signatory_title,
                ]);
                if (!empty($record->payload) && is_array($record->payload)) {
                    $defaultData = array_merge($defaultData, $record->payload);
                }
            }
        }

        $data = array_merge($defaultData, array_intersect_key($inputData, $defaultData));

        if (!empty($inputData['logoSrc_data'])) {
            $data['logoSrc'] = $inputData['logoSrc_data'];
        }
        if (!empty($inputData['stampSrc_data'])) {
            $data['stampSrc'] = $inputData['stampSrc_data'];
        }

        // Auto-save generated migration certificate to DB when printing via POST
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
                'student_prefix' => $data['studentPrefix'] ?? 'Mr./Ms.',
                'sd_prefix' => $data['sdPrefix'] ?? 'S/D of',
                'parent_name' => $data['parentName'] ?? null,
                'serial_no' => $data['serialNo'] ?? null,
                'enrollment_no' => $data['enrollmentNo'] ?? null,
                'course_name' => $data['courseName'] ?? null,
                'pass_year' => $data['passYear'] ?? null,
                'inst_name' => $data['instName'] ?? null,
                'place_name' => $data['placeName'] ?? null,
                'cert_date' => $data['certDate'] ?? null,
                'signatory_title' => $data['signatoryTitle'] ?? null,
                'payload' => $data,
                'created_by' => auth()->id(),
            ];

            $recordId = $request->input('record_id') ?: $request->input('id');
            if ($recordId && $existing = MigrationCertificate::find($recordId)) {
                $existing->update($recordData);
            } else {
                MigrationCertificate::create($recordData);
            }
        }

        $watermarkSource = !empty($data['watermarkText']) ? $data['watermarkText'] : $data['councilName'];
        $watermarkRepeat = $this->computeWatermarkText($watermarkSource);

        return view('backend.migration.print', compact('data', 'watermarkRepeat'));
    }

    /**
     * Delete a Migration Certificate record.
     */
    public function destroy($id): RedirectResponse
    {
        $cert = MigrationCertificate::findOrFail($id);
        $name = $cert->student_name;
        $cert->delete();

        return redirect()->route('migration.records')->with('success', "Migration Certificate for '{$name}' deleted successfully.");
    }

    /**
     * Return student details for studio quick-fill.
     */
    public function studentData($id): JsonResponse
    {
        $student = Student::with('courses')->findOrFail($id);

        $courseName = $student->courses->first()?->name ?? '';

        return response()->json([
            'id' => $student->id,
            'name' => $student->name,
            'father_name' => $student->father_name ?? '',
            'enrollment_no' => $student->student_code ?? '',
            'course_name' => $courseName,
            'pass_year' => date('Y'),
        ]);
    }
}
