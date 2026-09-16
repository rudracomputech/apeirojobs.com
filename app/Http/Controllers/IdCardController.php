<?php

namespace App\Http\Controllers;

use App\Models\IdCard;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IdCardController extends Controller
{
    /**
     * Get default initial card values (matching reference card).
     */
    protected function getDefaultData(): array
    {
        return [
            'id' => null,
            'org_name' => 'ALL INDIA COUNCIL FOR VOCATIONAL & PARAMEDICAL SCIENCE',
            'org_subtitle' => 'HS INSTITUTE HEALTHCARE & PARAMEDICAL TRAINING',
            'roll_no' => '20235801',
            'enrollment_no' => 'ACI2023233750',
            'card_title' => 'IDENTITY CARD',
            'student_name' => 'Laxman Patole',
            'course' => 'Diploma in General Nursing And Midwifery (GNM)',
            'session' => 'Jun 2023 - Jun 2025',
            'center_name' => 'HS Institute Powered by NBS Welfare Foundation.org',
            'watermark_text' => 'HS Institute Powered by NBS Welfare Foundation.org',
            'signatory_title' => 'Authorised Signatory',
            'photo' => asset('idcard-studio/assets/images/default_avatar.svg'),
            'logo' => asset('idcard-studio/assets/images/logo.png'),
            'stamp' => asset('idcard-studio/assets/images/stamp_signature.svg'),
            'emergency_contact' => '+91 98765 43210',
            'blood_group' => 'B+',
            'student_address' => 'At Post Pune, Maharashtra, India - 411001',
            'valid_upto' => 'June 2025',
        ];
    }

    /**
     * Display the ID Card Generator Studio.
     */
    public function index(Request $request): View
    {
        $default = $this->getDefaultData();

        if ($request->filled('id')) {
            $record = IdCard::where('id', $request->query('id'))
                ->orWhere('card_uid', $request->query('id'))
                ->first();

            if ($record) {
                $default = array_merge($default, [
                    'id' => $record->id,
                    'student_id' => $record->student_id,
                    'student_name' => $record->student_name,
                    'roll_no' => $record->roll_no,
                    'enrollment_no' => $record->enrollment_no,
                    'course' => $record->course,
                    'session' => $record->session,
                    'center_name' => $record->center_name,
                    'org_name' => $record->org_name,
                    'org_subtitle' => $record->org_subtitle,
                    'card_title' => $record->card_title,
                    'blood_group' => $record->blood_group,
                    'emergency_contact' => $record->emergency_contact,
                    'valid_upto' => $record->valid_upto,
                    'student_address' => $record->student_address,
                    'photo' => $record->photo ?: $default['photo'],
                ]);
                if (!empty($record->payload) && is_array($record->payload)) {
                    $default = array_merge($default, $record->payload);
                }
            }
        }

        $savedCards = IdCard::latest()->limit(8)->get()->map(function ($c) {
            return [
                'id' => $c->id,
                'card_uid' => $c->card_uid,
                'student_name' => $c->student_name,
                'roll_no' => $c->roll_no,
                'enrollment_no' => $c->enrollment_no,
                'course' => $c->course,
                'session' => $c->session,
                'photo' => $c->photo ?: asset('idcard-studio/assets/images/default_avatar.svg'),
            ];
        })->toArray();

        $students = Student::select('id', 'name', 'first_name', 'last_name', 'student_code')
            ->orderBy('name')
            ->limit(200)
            ->get();

        return view('backend.idcard.index', compact('default', 'savedCards', 'students'));
    }

    /**
     * Display a separate paginated list of all generated ID cards in Admin.
     */
    public function records(Request $request): View
    {
        $query = IdCard::query()->with('student');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('student_name', 'like', "%{$search}%")
                  ->orWhere('roll_no', 'like', "%{$search}%")
                  ->orWhere('enrollment_no', 'like', "%{$search}%")
                  ->orWhere('course', 'like', "%{$search}%")
                  ->orWhere('center_name', 'like', "%{$search}%")
                  ->orWhere('emergency_contact', 'like', "%{$search}%");
            });
        }

        $sort = $request->input('sort', 'id');
        $direction = $request->input('direction', 'desc');
        $allowedSorts = ['id', 'student_name', 'roll_no', 'enrollment_no', 'course', 'session', 'valid_upto', 'created_at'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        $limit = $request->input('limit', 15);
        $cards = ($limit === 'all') ? $query->paginate(500) : $query->paginate((int) $limit);

        return view('backend.idcard.records', compact('cards'));
    }

    /**
     * Save or update a generated ID card in the database.
     */
    public function save(Request $request): RedirectResponse
    {
        $cardUid = $request->input('card_uid') ?: uniqid('card_');
        $studentName = trim($request->input('student_name', ''));
        $rollNo = trim($request->input('roll_no', ''));
        $enrollmentNo = trim($request->input('enrollment_no', ''));
        $course = trim($request->input('course', ''));
        $session = trim($request->input('session', ''));
        $centerName = trim($request->input('center_name', ''));
        $orgName = trim($request->input('org_name', 'ALL INDIA COUNCIL FOR VOCATIONAL & PARAMEDICAL SCIENCE'));
        $orgSubtitle = trim($request->input('org_subtitle', ''));
        $cardTitle = trim($request->input('card_title', 'IDENTITY CARD'));
        $watermarkText = trim($request->input('watermark_text', 'HS Institute Powered by NBS Welfare Foundation.org'));
        $signatoryTitle = trim($request->input('signatory_title', 'Authorised Signatory'));

        $emergencyContact = trim($request->input('emergency_contact', ''));
        $bloodGroup = trim($request->input('blood_group', ''));
        $studentAddress = trim($request->input('student_address', ''));
        $validUpto = trim($request->input('valid_upto', ''));

        // Handle Photo Upload
        $photoPath = $request->input('existing_photo', asset('idcard-studio/assets/images/default_avatar.svg'));
        if ($request->hasFile('photo_file')) {
            $file = $request->file('photo_file');
            if ($file->isValid()) {
                $ext = $file->getClientOriginalExtension();
                $filename = 'photo_' . time() . '_' . uniqid() . '.' . $ext;
                $file->move(public_path('uploads/idcards'), $filename);
                $photoPath = asset('uploads/idcards/' . $filename);
            }
        } elseif (!empty($request->input('photo_data'))) {
            $photoPath = $request->input('photo_data');
        }

        $payload = [
            'card_uid' => $cardUid,
            'org_name' => $orgName,
            'org_subtitle' => $orgSubtitle,
            'card_title' => $cardTitle,
            'watermark_text' => $watermarkText,
            'signatory_title' => $signatoryTitle,
            'photo' => $photoPath,
            'logo' => $request->input('existing_logo', asset('idcard-studio/assets/images/logo.png')),
            'stamp' => $request->input('existing_stamp', asset('idcard-studio/assets/images/stamp_signature.svg')),
        ];

        // Find linked student if exists
        $studentId = $request->input('student_id');
        if (!$studentId && !empty($enrollmentNo)) {
            $matchedStudent = Student::where('student_code', $enrollmentNo)->first();
            if ($matchedStudent) {
                $studentId = $matchedStudent->id;
            }
        }

        $recordId = $request->input('record_id');
        $cardRecord = null;
        if ($recordId) {
            $cardRecord = IdCard::find($recordId);
        }

        if (!$cardRecord && $request->filled('id')) {
            $cardRecord = IdCard::where('id', $request->input('id'))
                ->orWhere('card_uid', $request->input('id'))
                ->first();
        }

        $cardData = [
            'student_id' => $studentId,
            'card_uid' => $cardUid,
            'student_name' => $studentName,
            'roll_no' => $rollNo,
            'enrollment_no' => $enrollmentNo,
            'course' => $course,
            'session' => $session,
            'center_name' => $centerName,
            'org_name' => $orgName,
            'org_subtitle' => $orgSubtitle,
            'card_title' => $cardTitle,
            'blood_group' => $bloodGroup,
            'emergency_contact' => $emergencyContact,
            'valid_upto' => $validUpto,
            'student_address' => $studentAddress,
            'photo' => $photoPath,
            'payload' => $payload,
            'created_by' => auth()->id(),
        ];

        if ($cardRecord) {
            $cardRecord->update($cardData);
            $savedId = $cardRecord->id;
        } else {
            $cardRecord = IdCard::create($cardData);
            $savedId = $cardRecord->id;
        }

        return redirect()->route('idcard.records')
            ->with('success', "ID Card for '{$studentName}' saved successfully to database!");
    }

    /**
     * Standalone Print View.
     */
    public function print(Request $request): View
    {
        $card = $this->getDefaultData();

        if ($request->filled('id')) {
            $record = IdCard::where('id', $request->query('id'))
                ->orWhere('card_uid', $request->query('id'))
                ->first();

            if ($record) {
                $card = array_merge($card, [
                    'id' => $record->id,
                    'student_name' => $record->student_name,
                    'roll_no' => $record->roll_no,
                    'enrollment_no' => $record->enrollment_no,
                    'course' => $record->course,
                    'session' => $record->session,
                    'center_name' => $record->center_name,
                    'org_name' => $record->org_name,
                    'org_subtitle' => $record->org_subtitle,
                    'card_title' => $record->card_title,
                    'blood_group' => $record->blood_group,
                    'emergency_contact' => $record->emergency_contact,
                    'valid_upto' => $record->valid_upto,
                    'student_address' => $record->student_address,
                    'photo' => $record->photo ?: $card['photo'],
                ]);
                if (!empty($record->payload) && is_array($record->payload)) {
                    $card = array_merge($card, $record->payload);
                }
            }
        } else {
            $card = array_merge($card, array_filter($request->all()));
        }

        if (!empty($card['photo']) && str_starts_with($card['photo'], 'assets/')) {
            $card['photo'] = asset('idcard-studio/' . $card['photo']);
        }

        return view('backend.idcard.print', compact('card'));
    }

    /**
     * Delete an ID Card record.
     */
    public function destroy($id): RedirectResponse
    {
        $card = IdCard::where('id', $id)->orWhere('card_uid', $id)->firstOrFail();
        $name = $card->student_name;
        $card->delete();

        return redirect()->route('idcard.records')->with('success', "ID Card for '{$name}' deleted successfully.");
    }

    /**
     * Quick-fill student data endpoint.
     */
    public function studentData($id): JsonResponse
    {
        $student = Student::with('courses')->findOrFail($id);

        $courseName = $student->courses->first()?->name ?? 'Diploma in General Nursing And Midwifery (GNM)';
        $startYear = date('Y');
        $endYear = date('Y', strtotime('+2 years'));
        $startMonth = 'Jun';
        $endMonth = 'Jun';

        $fullAddress = trim(
            ($student->address ? $student->address . ', ' : '') .
            ($student->city ? $student->city . ', ' : '') .
            ($student->state ?? '')
        );

        return response()->json([
            'id' => $student->id,
            'student_name' => $student->name,
            'roll_no' => $student->student_code ?? '2023' . str_pad((string) $student->id, 4, '0', STR_PAD_LEFT),
            'enrollment_no' => $student->student_code ? 'ACI' . $student->student_code : 'ACI' . date('Y') . str_pad((string) $student->id, 6, '0', STR_PAD_LEFT),
            'course' => $courseName,
            'session' => "{$startMonth} {$startYear} - {$endMonth} {$endYear}",
            'center_name' => 'HS Institute Powered by NBS Welfare Foundation.org',
            'watermark_text' => 'HS Institute Powered by NBS Welfare Foundation.org',
            'emergency_contact' => $student->mobile ?? $student->alternate_mobile ?? '+91 98765 43210',
            'blood_group' => 'B+',
            'student_address' => $fullAddress ?: 'At Post Pune, Maharashtra, India - 411001',
            'valid_upto' => "{$endMonth} {$endYear}",
        ]);
    }
}
