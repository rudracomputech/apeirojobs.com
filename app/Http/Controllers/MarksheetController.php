<?php

namespace App\Http\Controllers;

use App\Models\Marksheet;
use App\Models\Student;
use App\Services\Marksheet\MarksheetGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class MarksheetController extends Controller
{
    /**
     * Display the Marksheet Studio editor form.
     */
    public function index(Request $request): View
    {
        $record = null;
        if ($request->filled('id')) {
            $record = Marksheet::find($request->query('id'));
        }

        $students = Student::select('id', 'name', 'first_name', 'last_name', 'student_code')
            ->orderBy('name')
            ->limit(200)
            ->get();

        return view('backend.marksheet.index', compact('students', 'record'));
    }

    /**
     * Display a separate paginated list of all generated Marksheets in Admin.
     */
    public function records(Request $request): View
    {
        $query = Marksheet::query()->with('student');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('student_name', 'like', "%{$search}%")
                  ->orWhere('roll_number', 'like', "%{$search}%")
                  ->orWhere('enrollment_no', 'like', "%{$search}%")
                  ->orWhere('course_name', 'like', "%{$search}%")
                  ->orWhere('session', 'like', "%{$search}%");
            });
        }

        $sort = $request->input('sort', 'id');
        $direction = $request->input('direction', 'desc');
        $allowedSorts = ['id', 'student_name', 'roll_number', 'enrollment_no', 'course_name', 'percentage', 'overall_grade', 'issue_date', 'created_at'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        $limit = $request->input('limit', 15);
        $marksheets = ($limit === 'all') ? $query->paginate(500) : $query->paginate((int) $limit);

        return view('backend.marksheet.records', compact('marksheets'));
    }

    /**
     * Helper to persist marksheet record to database.
     */
    protected function persistMarksheet(array $postData, array $filesData): Marksheet
    {
        $studentName = !empty($postData['student_name']) ? trim($postData['student_name']) : 'Student';
        $rollNumber = !empty($postData['roll_number']) ? trim($postData['roll_number']) : null;
        $enrollmentNo = !empty($postData['enrollment_no']) ? trim($postData['enrollment_no']) : null;
        $courseName = !empty($postData['course_name']) ? trim($postData['course_name']) : null;
        $session = !empty($postData['session']) ? trim($postData['session']) : null;
        $fatherName = !empty($postData['father_name']) ? trim($postData['father_name']) : null;
        $motherName = !empty($postData['mother_name']) ? trim($postData['mother_name']) : null;
        $dateOfBirth = !empty($postData['date_of_birth']) ? trim($postData['date_of_birth']) : null;
        $issueDate = !empty($postData['issue_date']) ? trim($postData['issue_date']) : null;

        // Parse subjects array
        $subjects = [];
        $codes = $postData['subject_code'] ?? [];
        $names = $postData['subject_name'] ?? [];
        $maxs = $postData['max_marks'] ?? [];
        $mins = $postData['min_marks'] ?? [];
        $obts = $postData['marks_obtained'] ?? [];
        $internals = $postData['internal_marks'] ?? [];
        $totals = $postData['total_marks'] ?? [];

        $totObt = 0;
        $totMax = 0;

        foreach ($codes as $idx => $code) {
            $subName = $names[$idx] ?? '';
            if (empty($code) && empty($subName)) {
                continue;
            }
            $mMax = floatval($maxs[$idx] ?? 100);
            $mMin = floatval($mins[$idx] ?? 40);
            $mObt = floatval($obts[$idx] ?? 0);
            $mInt = floatval($internals[$idx] ?? 0);
            $mTot = floatval($totals[$idx] ?? ($mObt + $mInt));

            $totObt += $mTot;
            $totMax += $mMax;

            $grade = ($mTot >= 80) ? 'A+' : (($mTot >= 70) ? 'A' : (($mTot >= 60) ? 'B' : (($mTot >= 50) ? 'C' : (($mTot >= 40) ? 'D' : 'F'))));

            $subjects[] = [
                'code' => $code,
                'name' => $subName,
                'max_marks' => $mMax,
                'min_marks' => $mMin,
                'marks' => $mObt,
                'internal' => $mInt,
                'total' => $mTot,
                'grade' => $grade,
            ];
        }

        $percentage = ($totMax > 0) ? number_format(($totObt / $totMax) * 100, 2) . '%' : (!empty($postData['percentage']) ? $postData['percentage'] : '0.00%');
        $overallGrade = !empty($postData['overall_grade']) ? $postData['overall_grade'] : (($totMax > 0 && ($totObt / $totMax) >= 0.6) ? '1st Division' : 'Pass');
        $result = !empty($postData['result_status']) ? $postData['result_status'] : 'PASSED';

        // Photo handling
        $photo = !empty($postData['student_photo_base64']) ? $postData['student_photo_base64'] : null;
        if (!empty($filesData['student_photo']['tmp_name']) && is_uploaded_file($filesData['student_photo']['tmp_name'])) {
            $mime = mime_content_type($filesData['student_photo']['tmp_name']);
            $imgData = file_get_contents($filesData['student_photo']['tmp_name']);
            $photo = 'data:' . $mime . ';base64,' . base64_encode($imgData);
        }

        $studentId = $postData['student_id'] ?? null;
        if (!$studentId && $enrollmentNo) {
            $matchedStudent = Student::where('student_code', $enrollmentNo)->first();
            if ($matchedStudent) {
                $studentId = $matchedStudent->id;
            }
        }

        $data = [
            'student_id' => $studentId,
            'student_name' => $studentName,
            'father_name' => $fatherName,
            'mother_name' => $motherName,
            'roll_number' => $rollNumber,
            'enrollment_no' => $enrollmentNo,
            'course_name' => $courseName,
            'session' => $session,
            'date_of_birth' => $dateOfBirth,
            'issue_date' => $issueDate,
            'total_marks_obtained' => (string) $totObt,
            'grand_total_max' => (string) $totMax,
            'percentage' => $percentage,
            'overall_grade' => $overallGrade,
            'result' => $result,
            'subjects' => $subjects,
            'photo' => $photo,
            'payload' => $postData,
            'created_by' => auth()->id(),
        ];

        $recordId = $postData['record_id'] ?? ($postData['id'] ?? null);
        if ($recordId && $existing = Marksheet::find($recordId)) {
            $existing->update($data);
            return $existing;
        }

        return Marksheet::create($data);
    }

    /**
     * Handle Marksheet PDF or HTML generation and auto-persist to database.
     */
    public function generate(Request $request): Response|RedirectResponse
    {
        $postData = $request->all();
        $filesData = $_FILES ?? [];

        // Save generated values to database
        $savedMarksheet = $this->persistMarksheet($postData, $filesData);

        $action = $request->input('form_action', 'download');

        if ($action === 'save') {
            return redirect()->route('marksheet.records')
                ->with('success', "Marksheet for '{$savedMarksheet->student_name}' saved successfully to database!");
        }

        $generator = new MarksheetGenerator($postData, $filesData);

        if ($action === 'html') {
            return response($generator->renderHtml(), 200, [
                'Content-Type' => 'text/html; charset=UTF-8',
            ]);
        }

        $studentName = !empty($postData['student_name']) ? trim($postData['student_name']) : 'Student';
        $rollNumber = !empty($postData['roll_number']) ? trim($postData['roll_number']) : 'Marksheet';
        $safeName = preg_replace('/[^A-Za-z0-9_-]/', '_', $studentName);
        $safeRoll = preg_replace('/[^A-Za-z0-9_-]/', '_', $rollNumber);
        $filename = "Marksheet_{$safeName}_{$safeRoll}.pdf";

        $disposition = ($action === 'preview') ? 'inline' : 'attachment';

        $pdfOutput = $generator->getPdfOutput();

        return response($pdfOutput, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "{$disposition}; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Delete a Marksheet record.
     */
    public function destroy($id): RedirectResponse
    {
        $marksheet = Marksheet::findOrFail($id);
        $name = $marksheet->student_name;
        $marksheet->delete();

        return redirect()->route('marksheet.records')->with('success', "Marksheet for '{$name}' deleted successfully.");
    }

    /**
     * Return student details for studio quick-fill.
     */
    public function studentData($id): JsonResponse
    {
        $student = Student::with('courses')->findOrFail($id);

        $courseName = $student->courses->first()?->name ?? '';
        $dobFormatted = $student->dob ? $student->dob->format('d-m-Y') : '';

        return response()->json([
            'id' => $student->id,
            'student_name' => $student->name,
            'father_name' => $student->father_name ?? '',
            'mother_name' => $student->mother_name ?? '',
            'roll_number' => $student->student_code ?? '',
            'enrollment_no' => $student->student_code ?? '',
            'date_of_birth' => $dobFormatted,
            'course_name' => $courseName,
            'session' => 'Jun ' . (date('Y') - 1) . ' - Jun ' . date('Y'),
            'issue_date' => date('d-m-Y'),
        ]);
    }
}
