<?php
/**
 * Professional Marksheet PDF Generator for HS Institute
 * Healthcare & Paramedical Training
 */

namespace App\Services\Marksheet;

use Dompdf\Dompdf;
use Dompdf\Options;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class MarksheetGenerator
{
    private array $data;
    private string $baseDir;
    private array $postData = [];

    public function __construct(array $postData = [], array $files = [], bool $forceSample = false)
    {
        $this->baseDir = __DIR__ . '/assets';
        $this->postData = $postData;
        $this->data = $this->sanitizeAndPrepareData($postData, $files, $forceSample);
    }

    /**
     * Sanitize and format form inputs
     */
    private function sanitizeAndPrepareData(array $post, array $files, bool $forceSample = false): array
    {
        $isSample = $forceSample || empty($post);

        // 1. Institution Details
        $instituteName = isset($post['institute_name']) ? trim((string) $post['institute_name']) : ($isSample ? 'HS INSTITUTE' : '');
        $tagline = isset($post['tagline']) ? trim((string) $post['tagline']) : ($isSample ? 'Healthcare & Paramedical Training' : '');

        $subHeadingLeft1 = isset($post['sub_left_1']) ? trim((string) $post['sub_left_1']) : ($isSample ? '• Run by HS Institute for Vocational & Paramedical Science' : '');
        $subHeadingLeft2 = isset($post['sub_left_2']) ? trim((string) $post['sub_left_2']) : ($isSample ? '• Regd. Under MSME, Govt. of India' : '');
        $subHeadingRight1 = isset($post['sub_right_1']) ? trim((string) $post['sub_right_1']) : ($isSample ? '• An Autonomous Institution Registered Under the Trust Act of 1882' : '');
        $subHeadingRight2 = isset($post['sub_right_2']) ? trim((string) $post['sub_right_2']) : ($isSample ? '• AN ISO 9001 : 2015 Certified Organization' : '');

        // 2. Student Details
        $serialNo = isset($post['serial_no']) ? trim((string) $post['serial_no']) : ($isSample ? '20235801' : '');
        $enrollmentNo = isset($post['enrollment_no']) ? trim((string) $post['enrollment_no']) : ($isSample ? 'ACI2023233750' : '');
        $studentName = isset($post['student_name']) ? trim((string) $post['student_name']) : ($isSample ? 'Laxman Patole' : '');
        $rollNumber = isset($post['roll_number']) ? trim((string) $post['roll_number']) : ($isSample ? '20243951' : '');
        $fatherName = isset($post['father_name']) ? trim((string) $post['father_name']) : ($isSample ? 'Balasaheb' : '');
        $motherName = isset($post['mother_name']) ? trim((string) $post['mother_name']) : ($isSample ? 'Rupali' : '');
        $dateOfBirth = isset($post['date_of_birth']) ? trim((string) $post['date_of_birth']) : ($isSample ? '25-06-2000' : '');
        $session = isset($post['session']) ? trim((string) $post['session']) : ($isSample ? 'Jun 2024 - Jun 2025' : '');
        $courseName = isset($post['course_name']) ? trim((string) $post['course_name']) : ($isSample ? 'Diploma in General Nursing And Midwifery (GNM)' : '');

        // 3. Marksheet Title
        $marksheetTitle = isset($post['marksheet_title']) ? trim((string) $post['marksheet_title']) : ($isSample ? 'Performance Statement/Marksheet' : 'Performance Statement/Marksheet');
        $statementTitle = isset($post['statement_title']) ? trim((string) $post['statement_title']) : ($isSample ? 'Statement of Marks 2nd Year' : '');

        // 4. Subjects & Marks
        $subjects = $this->parseSubjects($post, $isSample);

        // Calculate Totals for Subject Table
        $totalMarksObtained = 0;
        $totalInternal = 0;
        $grandTotalCurrentYear = 0;
        $totalMinMarks = 0;
        $totalMaxMarks = 0;

        foreach ($subjects as $sub) {
            $totalMarksObtained += (float) ($sub['marks'] ?? 0);
            $totalInternal += (float) ($sub['internal'] ?? 0);
            $grandTotalCurrentYear += (float) ($sub['total'] ?? 0);
            $totalMinMarks += (float) ($sub['min_marks'] ?? 0);
            $totalMaxMarks += (float) ($sub['max_marks'] ?? 0);
        }

        // Percentage & Grade
        if (!empty($subjects)) {
            if (isset($post['percentage']) && trim((string) $post['percentage']) !== '') {
                $percentage = trim((string) $post['percentage']);
            } else {
                $percentage = ($totalMaxMarks > 0) ? number_format(($grandTotalCurrentYear / $totalMaxMarks) * 100, 2) . '%' : '0.00%';
            }
            $overallGrade = !empty($post['overall_grade']) ? trim((string) $post['overall_grade']) : $this->calculateGrade(($totalMaxMarks > 0) ? ($grandTotalCurrentYear / $totalMaxMarks) * 100 : 0);
        } else {
            $percentage = '';
            $overallGrade = '';
        }

        // 5. Year-wise Summary Table
        $y1Max = isset($post['year_1_max']) && trim((string) $post['year_1_max']) !== '' ? trim((string) $post['year_1_max']) : ($isSample ? '700' : '');
        $y1Obt = isset($post['year_1_obtained']) && trim((string) $post['year_1_obtained']) !== '' ? trim((string) $post['year_1_obtained']) : ($isSample ? '499' : '');
        $y2Max = isset($post['year_2_max']) && trim((string) $post['year_2_max']) !== '' ? trim((string) $post['year_2_max']) : ($isSample ? ($totalMaxMarks > 0 ? (string) $totalMaxMarks : '700') : '');
        $y2Obt = isset($post['year_2_obtained']) && trim((string) $post['year_2_obtained']) !== '' ? trim((string) $post['year_2_obtained']) : ($isSample ? ($grandTotalCurrentYear > 0 ? (string) $grandTotalCurrentYear : '495') : '');

        $gMax = isset($post['grand_max']) && trim((string) $post['grand_max']) !== '' ? trim((string) $post['grand_max']) : ($isSample ? (string) ((float) $y1Max + (float) $y2Max) : '');
        $gObt = isset($post['grand_obtained']) && trim((string) $post['grand_obtained']) !== '' ? trim((string) $post['grand_obtained']) : ($isSample ? (string) ((float) $y1Obt + (float) $y2Obt) : '');

        $yearSummary = [
            'year_1_max' => $y1Max,
            'year_1_obtained' => $y1Obt,
            'year_2_max' => $y2Max,
            'year_2_obtained' => $y2Obt,
            'grand_max' => $gMax,
            'grand_obtained' => $gObt,
        ];

        // 6. Verification and Footer
        $verificationUrl = isset($post['verification_url']) ? trim((string) $post['verification_url']) : ($isSample ? 'www.aicvps.org' : '');
        $verificationEmail = isset($post['verification_email']) ? trim((string) $post['verification_email']) : ($isSample ? 'verify.marksheet@aicvps.org' : '');
        $issueDate = isset($post['issue_date']) ? trim((string) $post['issue_date']) : ($isSample ? '29-06-2025' : '');
        $controllerTitle = isset($post['controller_title']) ? trim((string) $post['controller_title']) : ($isSample ? 'Examination Controller' : '');
        $directorTitle = isset($post['director_title']) ? trim((string) $post['director_title']) : ($isSample ? 'Director' : '');

        // 7. Student Photo Handling
        $photoBase64 = $this->getStudentPhotoBase64($files, $isSample);

        // 8. Logo Handling
        $logoBase64 = $this->getLogoBase64($files, $isSample);

        // 9. QR Code Handling (Supports uploaded QR image or dynamic generated QR)
        $qrUpload = !empty($files['qr_file']['tmp_name']) ? $files['qr_file'] : (!empty($files['qr_code_file']['tmp_name']) ? $files['qr_code_file'] : (!empty($files['qr_code']['tmp_name']) ? $files['qr_code'] : null));
        if ($qrUpload && is_uploaded_file($qrUpload['tmp_name'])) {
            $mime = mime_content_type($qrUpload['tmp_name']);
            $data = file_get_contents($qrUpload['tmp_name']);
            $qrCodeBase64 = 'data:' . $mime . ';base64,' . base64_encode($data);
            $qrData = '';
        } elseif (!empty($post['qr_base64'])) {
            $qrCodeBase64 = $post['qr_base64'];
            $qrData = '';
        } else {
            // Check if user explicitly removed QR code
            if (isset($post['use_default_qr']) && $post['use_default_qr'] === '0' && empty($post['qr_data']) && empty($post['qr_url'])) {
                $qrData = '';
                $qrCodeBase64 = '';
            } else {
                if (!empty($post['qr_data'])) {
                    $qrData = trim((string) $post['qr_data']);
                } elseif (isset($post['qr_url'])) {
                    $qrData = trim((string) $post['qr_url']);
                } elseif ($isSample && !empty($rollNumber)) {
                    $qrData = "http://apeirojobs.com/results/view?rollno=" . urlencode($rollNumber);
                } else {
                    $qrData = '';
                }
                $qrCodeBase64 = ($qrData !== '') ? $this->generateQrCodeBase64($qrData) : '';
            }
        }

        // 10. Greek Border Frame Base64
        $borderBase64 = $this->getGreekBorderBase64();

        // 11. Official Seal Base64
        $sealBase64 = $this->getSealBase64($files, $isSample);

        // 12. Signatures Base64
        $sigControllerBase64 = !empty($controllerTitle) ? $this->getControllerSigBase64() : '';
        $sigDirectorBase64 = !empty($directorTitle) ? $this->getDirectorSigBase64() : '';

        // 13. Cartouche Banner Base64
        $cartoucheBase64 = ($marksheetTitle !== '') ? $this->getCartoucheBase64($marksheetTitle) : '';

        // 14. Background Watermark Pattern ("hs institute" small letters, low opacity)
        $watermarkText = !empty($post['watermark_text']) ? trim((string) $post['watermark_text']) : (!empty($instituteName) ? strtolower(trim((string) $instituteName)) : 'hs institute');
        $watermarkPatternBase64 = $this->getWatermarkPatternBase64($watermarkText);

        return [
            'institute_name' => $instituteName,
            'tagline' => $tagline,
            'sub_left_1' => $subHeadingLeft1,
            'sub_left_2' => $subHeadingLeft2,
            'sub_right_1' => $subHeadingRight1,
            'sub_right_2' => $subHeadingRight2,
            'serial_no' => $serialNo,
            'enrollment_no' => $enrollmentNo,
            'student_name' => $studentName,
            'roll_number' => $rollNumber,
            'father_name' => $fatherName,
            'mother_name' => $motherName,
            'date_of_birth' => $dateOfBirth,
            'session' => $session,
            'course_name' => $courseName,
            'marksheet_title' => $marksheetTitle,
            'statement_title' => $statementTitle,
            'subjects' => $subjects,
            'total_marks_obtained' => $totalMarksObtained,
            'total_internal' => $totalInternal,
            'grand_total_current' => $grandTotalCurrentYear,
            'total_min_marks' => $totalMinMarks,
            'total_max_marks' => $totalMaxMarks,
            'overall_grade' => $overallGrade,
            'percentage' => $percentage,
            'year_summary' => $yearSummary,
            'verification_url' => $verificationUrl,
            'verification_email' => $verificationEmail,
            'issue_date' => $issueDate,
            'controller_title' => $controllerTitle,
            'director_title' => $directorTitle,
            'photo_base64' => $photoBase64,
            'logo_base64' => $logoBase64,
            'qr_code_base64' => $qrCodeBase64,
            'border_base64' => $borderBase64,
            'seal_base64' => $sealBase64,
            'sig_controller_base64' => $sigControllerBase64,
            'sig_director_base64' => $sigDirectorBase64,
            'cartouche_base64' => $cartoucheBase64,
            'watermark_pattern_base64' => $watermarkPatternBase64,
            'qr_data_url' => $qrData,
        ];
    }

    /**
     * Parse subject rows from structured inputs or textarea
     */
    private function parseSubjects(array $post, bool $isSample = false): array
    {
        $subjects = [];

        if (isset($post['subject_code']) && is_array($post['subject_code'])) {
            $codes = $post['subject_code'];
            $names = $post['subject_name'] ?? [];
            $marks = $post['subject_marks'] ?? [];
            $internals = $post['subject_internal'] ?? [];
            $totals = $post['subject_total'] ?? [];
            $mins = $post['subject_min'] ?? [];
            $maxs = $post['subject_max'] ?? [];
            $grades = $post['subject_grade'] ?? [];

            foreach ($codes as $idx => $code) {
                $name = trim((string) ($names[$idx] ?? ''));
                $c = trim((string) $code);
                $mStr = trim((string) ($marks[$idx] ?? ''));
                $iStr = trim((string) ($internals[$idx] ?? ''));
                $tStr = trim((string) ($totals[$idx] ?? ''));

                // If entire row is blank, skip it
                if ($name === '' && $c === '' && $mStr === '' && $iStr === '' && $tStr === '') {
                    continue;
                }

                $m = $mStr !== '' ? (float) $mStr : 0;
                $i = $iStr !== '' ? (float) $iStr : 0;
                $t = $tStr !== '' ? (float) $tStr : ($m + $i);
                $min = isset($mins[$idx]) && trim((string) $mins[$idx]) !== '' ? (float) $mins[$idx] : 40;
                $max = isset($maxs[$idx]) && trim((string) $maxs[$idx]) !== '' ? (float) $maxs[$idx] : 100;
                $g = !empty($grades[$idx]) ? trim((string) $grades[$idx]) : $this->calculateGrade($t);

                $subjects[] = [
                    'code' => $c,
                    'name' => $name,
                    'marks' => $m,
                    'internal' => $i,
                    'total' => $t,
                    'min_marks' => $min,
                    'max_marks' => $max,
                    'grade' => $g,
                ];
            }
        } elseif (!empty($post['subjects'])) {
            $lines = preg_split('/\r\n|\r|\n/', (string) $post['subjects']);
            $defaultCode = 201;

            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '')
                    continue;

                $parts = array_map('trim', explode('|', $line));
                if (count($parts) >= 7) {
                    if (is_numeric($parts[0]) && count($parts) >= 8) {
                        $code = $parts[0];
                        $name = $parts[1];
                        $m = (float) $parts[2];
                        $i = (float) $parts[3];
                        $t = (float) $parts[4];
                        $min = (float) $parts[5];
                        $max = (float) $parts[6];
                        $g = $parts[7];
                    } else {
                        $code = (string) $defaultCode++;
                        $name = $parts[0];
                        $m = (float) $parts[1];
                        $i = (float) $parts[2];
                        $t = (float) $parts[3];
                        $min = (float) $parts[4];
                        $max = (float) $parts[5];
                        $g = $parts[6] ?? 'B';
                    }

                    $subjects[] = [
                        'code' => $code,
                        'name' => $name,
                        'marks' => $m,
                        'internal' => $i,
                        'total' => $t,
                        'min_marks' => $min,
                        'max_marks' => $max,
                        'grade' => $g,
                    ];
                }
            }
        }

        if (empty($subjects) && $isSample) {
            $subjects = [
                ['code' => '201', 'name' => 'Medical Surgical Nursing', 'marks' => 53, 'internal' => 12, 'total' => 65, 'min_marks' => 40, 'max_marks' => 100, 'grade' => 'B'],
                ['code' => '202', 'name' => 'Mental Health Nursing', 'marks' => 53, 'internal' => 18, 'total' => 71, 'min_marks' => 40, 'max_marks' => 100, 'grade' => 'B+'],
                ['code' => '203', 'name' => 'Child Health Nursing', 'marks' => 60, 'internal' => 12, 'total' => 72, 'min_marks' => 40, 'max_marks' => 100, 'grade' => 'B+'],
                ['code' => '204', 'name' => 'Midwifery Nursing', 'marks' => 67, 'internal' => 11, 'total' => 78, 'min_marks' => 40, 'max_marks' => 100, 'grade' => 'A'],
                ['code' => '205', 'name' => 'Gynecology Nursing', 'marks' => 59, 'internal' => 10, 'total' => 69, 'min_marks' => 40, 'max_marks' => 100, 'grade' => 'B'],
                ['code' => '206', 'name' => 'Community Health Nursing', 'marks' => 51, 'internal' => 17, 'total' => 68, 'min_marks' => 40, 'max_marks' => 100, 'grade' => 'B'],
                ['code' => '207', 'name' => 'Computer Application', 'marks' => 60, 'internal' => 12, 'total' => 72, 'min_marks' => 40, 'max_marks' => 100, 'grade' => 'B+'],
            ];
        }

        return $subjects;
    }

    private function calculateGrade(float $percentage): string
    {
        if ($percentage >= 80)
            return 'A';
        if ($percentage >= 70)
            return 'B+';
        if ($percentage >= 60)
            return 'B';
        if ($percentage >= 50)
            return 'C';
        if ($percentage >= 40)
            return 'D';
        return 'F';
    }

    /**
     * Generate or return seamless high-res Greek key border PNG
     */
    private function getGreekBorderBase64(): string
    {
        $borderFile = $this->baseDir . '/greek_border_pro.png';
        if (!file_exists($borderFile)) {
            $borderFile = $this->baseDir . '/greek_border_seamless.png';
        }
        $data = file_get_contents($borderFile);
        return 'data:image/png;base64,' . base64_encode($data);
    }

    /**
     * Generate seamless high-res security background pattern watermark ("hs institute" with small letters, low opacity)
     */
    private function getWatermarkPatternBase64(string $text = 'hs institute'): string
    {
        if (!function_exists('imagecreatetruecolor')) {
            return '';
        }

        $w = 85;
        $h = 42;
        $im = imagecreatetruecolor($w, $h);
        if (!$im) {
            return '';
        }

        imagealphablending($im, false);
        imagesavealpha($im, true);
        $trans = imagecolorallocatealpha($im, 255, 255, 255, 127);
        imagefilledrectangle($im, 0, 0, $w, $h, $trans);
        imagealphablending($im, true);

        // Security slate-blue tone with enhanced visibility (alpha 104 / 127 ~ 18% opacity)
        $color = imagecolorallocatealpha($im, 8, 65, 125, 104);

        $fontCandidates = [
            $this->baseDir . '/Almendra-Bold.ttf',
            $this->baseDir . '/MedievalSharp.ttf',
            $this->baseDir . '/GermaniaOne-Regular.ttf',
            $this->baseDir . '/Chomsky.ttf',
            $this->baseDir . '/CinzelDecorative-Bold.ttf',
            $this->baseDir . '/UnifrakturCook-Bold.ttf',
            $this->baseDir . '/UnifrakturMaguntia-Book.ttf',
        ];

        $fontFile = null;
        foreach ($fontCandidates as $fc) {
            if (file_exists($fc)) {
                $fontFile = $fc;
                break;
            }
        }

        $fontSize = 8.0;
        $angle = 0;

        if ($fontFile) {
            // Staggered 2-row repeat for seamless diagonal brick pattern
            @imagettftext($im, $fontSize, $angle, (int) ($w * 0.05), (int) ($h * 0.45), $color, $fontFile, $text);
            @imagettftext($im, $fontSize, $angle, (int) ($w * 0.55), (int) ($h * 0.95), $color, $fontFile, $text);
        } else {
            imagestring($im, 2, 5, 12, $text, $color);
            imagestring($im, 2, 45, 28, $text, $color);
        }

        ob_start();
        imagepng($im);
        $pngData = ob_get_clean();
        imagedestroy($im);

        return 'data:image/png;base64,' . base64_encode($pngData);
    }

    /**
     * Generate or return Official Seal PNG / uploaded seal
     */
    private function getSealBase64(array $files, bool $isSample = false): string
    {
        // 1. Uploaded file under 'seal_file' or 'seal'
        $sealUpload = !empty($files['seal_file']['tmp_name']) ? $files['seal_file'] : (!empty($files['seal']['tmp_name']) ? $files['seal'] : null);
        if ($sealUpload && is_uploaded_file($sealUpload['tmp_name'])) {
            $mime = mime_content_type($sealUpload['tmp_name']);
            $data = file_get_contents($sealUpload['tmp_name']);
            return 'data:' . $mime . ';base64,' . base64_encode($data);
        }

        // 2. Base64 string directly in POST
        if (!empty($this->postData['seal_base64'])) {
            return $this->postData['seal_base64'];
        }

        // 3. User opted to use default sample seal
        if (!empty($this->postData['use_default_seal']) && $this->postData['use_default_seal'] === '1') {
            $candidates = [
                $this->baseDir . '/exact_seal.png',
                $this->baseDir . '/ref_seal.png',
                $this->baseDir . '/official_seal_pro.png',
                $this->baseDir . '/official_seal_hd.png',
            ];
            foreach ($candidates as $sealFile) {
                if (file_exists($sealFile)) {
                    $data = file_get_contents($sealFile);
                    return 'data:image/png;base64,' . base64_encode($data);
                }
            }
        }

        // 4. Sample mode fallback
        if ($isSample) {
            $candidates = [
                $this->baseDir . '/exact_seal.png',
                $this->baseDir . '/ref_seal.png',
                $this->baseDir . '/official_seal_pro.png',
                $this->baseDir . '/official_seal_hd.png',
            ];
            foreach ($candidates as $sealFile) {
                if (file_exists($sealFile)) {
                    $data = file_get_contents($sealFile);
                    return 'data:image/png;base64,' . base64_encode($data);
                }
            }
        }

        return '';
    }

    /**
     * Generate Cartouche Banner with title text baked in via GD
     */
    private function getCartoucheBase64(string $title = 'Performance Statement/Marksheet'): string
    {
        if (trim($title) === '') {
            return '';
        }

        $frameCandidates = [
            $this->baseDir . '/performance-img.png',
            $this->baseDir . '/cartouche_frame_empty.png',
            $this->baseDir . '/cartouche_filigree_gold.png',
            $this->baseDir . '/exact_cartouche.png',
        ];
        $frameFile = '';
        foreach ($frameCandidates as $f) {
            if (file_exists($f)) {
                $frameFile = $f;
                break;
            }
        }

        if ($frameFile && function_exists('imagecreatefrompng') && str_ends_with($frameFile, 'cartouche_frame_empty.png')) {
            $src = @imagecreatefrompng($frameFile);
            if ($src) {
                // --- Critical: correct alpha handling ---
                imagealphablending($src, true);

                $w = imagesx($src);
                $h = imagesy($src);

                $fontFile = $this->baseDir . '/UnifrakturMaguntia-Book.ttf';
                $fallbackFont = $this->baseDir . '/DejaVuSerifCondensed-Bold.ttf';
                $font = file_exists($fontFile) ? $fontFile : (file_exists($fallbackFont) ? $fallbackFont : null);

                if ($font) {
                    $white = imagecolorallocate($src, 255, 255, 255);
                    $shadow = imagecolorallocatealpha($src, 0, 0, 0, 60);

                    // Leave margin so text doesn't touch the filigree corners
                    $maxTextWidth = $w * 0.78;
                    $maxTextHeight = $h * 0.55;

                    // --- Shrink-to-fit: find the largest font size that fits ---
                    $fontSize = 48;
                    $bbox = imagettfbbox($fontSize, 0, $font, $title);
                    $tw = abs($bbox[2] - $bbox[0]);
                    $th = abs($bbox[1] - $bbox[7]);

                    while (($tw > $maxTextWidth || $th > $maxTextHeight) && $fontSize > 8) {
                        $fontSize--;
                        $bbox = imagettfbbox($fontSize, 0, $font, $title);
                        $tw = abs($bbox[2] - $bbox[0]);
                        $th = abs($bbox[1] - $bbox[7]);
                    }

                    // --- Correct centering ---
                    // x: center using bbox width, offset by left bearing (bbox[0])
                    $x = (int) (($w - $tw) / 2) - $bbox[0];
                    // y: center using the glyph's actual ink height, not raw font size
                    $y = (int) (($h - $th) / 2) - $bbox[7];

                    // Drop shadow, then main text
                    imagettftext($src, $fontSize, 0, $x + 2, $y + 2, $shadow, $font, $title);
                    imagettftext($src, $fontSize, 0, $x, $y, $white, $font, $title);
                }

                // --- Critical: preserve alpha on output ---
                imagesavealpha($src, true);

                ob_start();
                imagepng($src);
                $data = ob_get_clean();
                imagedestroy($src);
                return 'data:image/png;base64,' . base64_encode($data);
            }
        }

        if ($frameFile) {
            $data = file_get_contents($frameFile);
            return 'data:image/png;base64,' . base64_encode($data);
        }
        return '';
    }

    /**
     * Generate or return Green Controller Signature SVG Data URI
     */
    private function getControllerSigBase64(): string
    {
        $candidates = [
            $this->baseDir . '/exact_controller_sig.png',
            $this->baseDir . '/ref_controller_sig.png',
        ];
        foreach ($candidates as $sigFile) {
            if (file_exists($sigFile)) {
                $data = file_get_contents($sigFile);
                return 'data:image/png;base64,' . base64_encode($data);
            }
        }
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 140 40" width="130" height="36"><path d="M 10 28 Q 24 2, 40 22 T 62 10 T 84 26 T 105 14 T 125 28 M 30 14 L 80 32 M 70 8 Q 78 22, 88 32" fill="none" stroke="#007744" stroke-width="2" stroke-linecap="round"/></svg>';
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Generate or return Blue Director Signature SVG Data URI
     */
    private function getDirectorSigBase64(): string
    {
        $candidates = [
            $this->baseDir . '/exact_director_sig.png',
            $this->baseDir . '/ref_director_sig.png',
        ];
        foreach ($candidates as $sigFile) {
            if (file_exists($sigFile)) {
                $data = file_get_contents($sigFile);
                return 'data:image/png;base64,' . base64_encode($data);
            }
        }
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 140 40" width="130" height="36"><path d="M 12 30 C 28 6, 38 36, 52 16 C 65 34, 78 10, 92 28 C 105 16, 115 32, 128 14 M 18 24 L 122 24" fill="none" stroke="#004882" stroke-width="2" stroke-linecap="round"/></svg>';
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Convert Logo to Base64
     */
    private function getLogoBase64(array $files, bool $isSample = false): string
    {
        // 1. Uploaded file under 'logo_file' or 'logo'
        $logoUpload = !empty($files['logo_file']['tmp_name']) ? $files['logo_file'] : (!empty($files['logo']['tmp_name']) ? $files['logo'] : null);
        if ($logoUpload && is_uploaded_file($logoUpload['tmp_name'])) {
            $mime = mime_content_type($logoUpload['tmp_name']);
            $data = file_get_contents($logoUpload['tmp_name']);
            return 'data:' . $mime . ';base64,' . base64_encode($data);
        }

        // 2. Base64 string directly in POST
        if (!empty($this->postData['logo_base64'])) {
            return $this->postData['logo_base64'];
        }

        // 3. User opted to keep default sample logo
        if (!empty($this->postData['use_default_logo']) && $this->postData['use_default_logo'] === '1') {
            $defaultLogo = $this->baseDir . '/logo.png';
            if (file_exists($defaultLogo)) {
                $data = file_get_contents($defaultLogo);
                return 'data:image/png;base64,' . base64_encode($data);
            }
        }

        // 4. Sample mode fallback
        if ($isSample) {
            $defaultLogo = $this->baseDir . '/logo.png';
            if (file_exists($defaultLogo)) {
                $data = file_get_contents($defaultLogo);
                return 'data:image/png;base64,' . base64_encode($data);
            }
        }

        return '';
    }

    /**
     * Convert Student Photo to Base64
     */
    private function getStudentPhotoBase64(array $files, bool $isSample = false): string
    {
        if (!empty($files['student_photo']['tmp_name']) && is_uploaded_file($files['student_photo']['tmp_name'])) {
            $mime = mime_content_type($files['student_photo']['tmp_name']);
            $data = file_get_contents($files['student_photo']['tmp_name']);
            return 'data:' . $mime . ';base64,' . base64_encode($data);
        }

        if (!empty($this->postData['student_photo_base64'])) {
            return $this->postData['student_photo_base64'];
        }

        if (!empty($this->postData['use_sample_photo']) && $this->postData['use_sample_photo'] === '1') {
            $samplePhoto = $this->baseDir . '/sample_student.jpg';
            if (file_exists($samplePhoto)) {
                $data = file_get_contents($samplePhoto);
                return 'data:image/jpeg;base64,' . base64_encode($data);
            }
        }

        if ($isSample) {
            $samplePhoto = $this->baseDir . '/sample_student.jpg';
            if (file_exists($samplePhoto)) {
                $data = file_get_contents($samplePhoto);
                return 'data:image/jpeg;base64,' . base64_encode($data);
            }
        }

        return '';
    }

    /**
     * Generate dynamic QR Code Base64 Data URI
     */
    private function generateQrCodeBase64(string $data): string
    {
        try {
            $options = new QROptions([
                'version' => 4,
                'outputType' => QRCode::OUTPUT_MARKUP_SVG,
                'eccLevel' => QRCode::ECC_M,
                'scale' => 3,
                'addQuietzone' => false,
            ]);
            $qrcode = new QRCode($options);
            $svg = $qrcode->render($data);
            return 'data:image/svg+xml;base64,' . base64_encode($svg);
        } catch (\Throwable $e) {
            return 'data:image/svg+xml;base64,' . base64_encode('
                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80">
                    <rect width="80" height="80" fill="#ffffff"/>
                    <rect x="5" y="5" width="22" height="22" fill="#000" />
                    <rect x="9" y="9" width="14" height="14" fill="#fff" />
                    <rect x="12" y="12" width="8" height="8" fill="#000" />
                    <rect x="53" y="5" width="22" height="22" fill="#000" />
                    <rect x="57" y="9" width="14" height="14" fill="#fff" />
                    <rect x="60" y="12" width="8" height="8" fill="#000" />
                    <rect x="5" y="53" width="22" height="22" fill="#000" />
                    <rect x="9" y="57" width="14" height="14" fill="#fff" />
                    <rect x="12" y="60" width="8" height="8" fill="#000" />
                    <rect x="35" y="15" width="8" height="8" fill="#000" />
                    <rect x="32" y="32" width="16" height="16" fill="#000" />
                    <rect x="55" y="45" width="10" height="10" fill="#000" />
                </svg>
            ');
        }
    }

    /**
     * Render the complete HTML for the certificate (exact 1-page A4)
     */
    public function renderHtml(): string
    {
        $d = $this->data;

        $instituteName = htmlspecialchars($d['institute_name']);
        $tagline = htmlspecialchars($d['tagline']);
        $subLeft1 = htmlspecialchars($d['sub_left_1']);
        $subLeft2 = htmlspecialchars($d['sub_left_2']);
        $subRight1 = htmlspecialchars($d['sub_right_1']);
        $subRight2 = htmlspecialchars($d['sub_right_2']);
        $serialNo = htmlspecialchars($d['serial_no']);
        $enrollmentNo = htmlspecialchars($d['enrollment_no']);
        $studentName = htmlspecialchars($d['student_name']);
        $rollNumber = htmlspecialchars($d['roll_number']);
        $fatherName = htmlspecialchars($d['father_name']);
        $motherName = htmlspecialchars($d['mother_name']);
        $dateOfBirth = htmlspecialchars($d['date_of_birth']);
        $session = htmlspecialchars($d['session']);
        $courseName = htmlspecialchars($d['course_name']);
        $marksheetTitle = htmlspecialchars($d['marksheet_title']);
        $statementTitle = htmlspecialchars($d['statement_title']);
        $percentage = htmlspecialchars($d['percentage']);
        $overallGrade = htmlspecialchars($d['overall_grade']);
        $verificationUrl = htmlspecialchars($d['verification_url']);
        $verificationEmail = htmlspecialchars($d['verification_email']);
        $issueDate = htmlspecialchars($d['issue_date']);
        $controllerTitle = htmlspecialchars($d['controller_title']);
        $directorTitle = htmlspecialchars($d['director_title']);
        $photoSrc = $d['photo_base64'];
        $logoSrc = $d['logo_base64'];
        $qrSrc = $d['qr_code_base64'];
        $borderSrc = $d['border_base64'];
        $sealSrc = $d['seal_base64'];
        $sigControllerSrc = $d['sig_controller_base64'];
        $sigDirectorSrc = $d['sig_director_base64'];
        $cartoucheSrc = $d['cartouche_base64'];
        $watermarkPatternSrc = $d['watermark_pattern_base64'] ?? '';
        $yearSummary = $d['year_summary'];

        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <title>Marksheet - <?= $studentName ?></title>
            <style>
                @font-face {
                    font-family: 'OldEnglish';
                    src: url('<?= $this->baseDir ?>/UnifrakturMaguntia-Book.ttf') format('truetype');
                    font-weight: normal;
                    font-style: normal;
                }

                @page {
                    size: 210mm 297mm;
                    margin: 0;
                }

                * {
                    box-sizing: border-box;
                }

                body {
                    margin: 0;
                    padding: 0;
                    width: 210mm;
                    height: 297mm;
                    font-family: Helvetica, Arial, sans-serif;
                    color: #000000;
                    font-size: 10pt;
                    background-color: #ffffff;
                }

                /* Screen preview enhancements for browser customization */
                @media screen {
                    html {
                        background-color: #1e293b;
                        padding: 18px 0;
                    }

                    body {
                        margin: 0 auto !important;
                        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.45);
                        position: relative;
                        background-color: #ffffff;
                    }
                }

                /* Fixed border frame spanning the full page */
                .bg-border {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 210mm;
                    height: 297mm;
                    z-index: -100;
                }

                /* Seamless Whole-page Security Watermark Pattern */
                .bg-watermark-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 210mm;
                    height: 297mm;
                    background-image: url('<?= $watermarkPatternSrc ?>');
                    background-repeat: repeat;
                    z-index: -60;
                    pointer-events: none;
                }

                /* Watermark positioned in middle behind marks table */
                .watermark {
                    position: fixed;
                    top: 106mm;
                    left: 54mm;
                    width: 102mm;
                    height: 102mm;
                    opacity: 0.15;
                    z-index: -50;
                }

                /* Main Content Container fitting comfortably inside Greek border */
                .content-box {
                    position: relative;
                    padding: 13mm 15mm 0 15mm;
                }

                /* Top Metadata Header: Serial, Logo, Enrollment */
                .header-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 6px;
                }

                .header-table td {
                    vertical-align: middle;
                }

                .serial-text {
                    font-size: 12pt;
                    color: #004882;
                    font-weight: bold;
                }

                .serial-val {
                    color: #000000;
                    font-weight: bold;
                }

                .enroll-text {
                    font-size: 12pt;
                    color: #004882;
                    font-weight: bold;
                    text-align: right;
                }

                .enroll-val {
                    color: #000000;
                    font-weight: bold;
                }

                .top-logo-img {
                    max-width: 140px;
                    max-height: 120px;
                    width: auto;
                    height: auto;
                    display: block;
                    margin: 0 auto;
                }

                /* Institute Name & Tagline */
                .institute-title {
                    text-align: center;
                    font-size: 20pt;
                    font-weight: bold;
                    color: #003366;
                    font-family: 'Times New Roman', Times, serif;
                    letter-spacing: 0.5px;
                    margin: 2px 0 0 0;
                    text-transform: uppercase;
                    line-height: 1.9;
                }

                .institute-tagline {
                    text-align: center;
                    font-size: 12pt;
                    font-weight: bold;
                    color: #996515;
                    margin: 1px 0 4px 0;
                    letter-spacing: 0.3px;
                }

                /* Accreditation Sub-lines */
                .accreditation-table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 7.7pt;
                    color: #053a74;
                    font-weight: bold;
                    margin-top: 25px;
                    margin-bottom: 25px;
                }

                .accreditation-table td {
                    padding: 1px 2px;
                    line-height: 1.3;
                }

                /* Performance Statement / Marksheet Ribbon Cartouche */
                .ribbon-banner-wrap {
                    text-align: center;
                    margin: 6px auto 10px auto;
                    width: 100%;
                }

                .ribbon-banner-box {
                    width: 500px;
                    height: 46px;
                    margin: 0 auto;
                    text-align: center;
                    line-height: 1.9;
                    /* Background image set inline */
                }

                .ribbon-banner-text {
                    font-family: 'OldEnglish', 'UnifrakturMaguntia', 'Cinzel Decorative', 'Georgia', serif;
                    font-size: 18pt;
                    font-weight: normal;
                    font-style: normal;
                    color: #ffffff;
                    letter-spacing: 1px;
                    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
                }

                /* Student Info & Photo */
                .student-info-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 8px;
                }

                .student-info-table td {
                    padding: 3px 2px;
                    vertical-align: top;
                    font-size: 11pt;
                }

                .info-label {
                    color: #004882;
                    font-weight: bold;
                    white-space: nowrap;
                }

                .info-val {
                    color: #000000;
                    font-weight: bold;
                }

                .photo-cell {
                    width: 88px;
                    text-align: right;
                    vertical-align: top;
                }

                .photo-img {
                    width: 82px;
                    height: 105px;
                    border: 1.5px solid #004882;
                    padding: 1px;
                    background: #ffffff;
                    object-fit: cover;
                }

                /* Statement of Marks Header Banner */
                .statement-banner {
                    background-color: #cb9639;
                    color: #ffffff;
                    text-align: center;
                    font-size: 12pt;
                    font-weight: bold;
                    padding: 8px 0;
                    border: 1px solid #996515;
                    margin-bottom: 0px;
                    letter-spacing: 0.3px;
                }

                /* Marks Table */
                .marks-table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 12pt;
                    margin-top: 0;
                }

                .marks-table th,
                .marks-table td {
                    border: 1px solid #7c8f9f;
                    padding: 10px 3px;
                    text-align: center;
                }

                .marks-table thead th {

                    color: #004882;
                    font-weight: bold;
                    font-size: 11.5pt;
                    padding: 4px 3px;
                }

                .marks-table td.sub-name {
                    text-align: left;
                    padding-left: 14px;
                    color: #000000;
                    font-weight: 500;
                }

                .marks-table td.val-cell {
                    color: #000000;
                    font-weight: 500;
                }

                .marks-table tr.total-row td {
                    font-weight: bold;
                    color: #000000;
                    background-color: #fbfcfd;
                    padding: 4px 3px;
                    font-size: 11.5pt;
                }

                /* Percentage Line */
                .percentage-row {
                    width: 100%;
                    text-align: right;
                    margin: 10px 0 10px 0;
                    font-size: 11.5pt;
                }

                .percent-label {
                    color: #000000;
                    font-weight: bold;
                    margin-right: 12px;
                }

                .percent-val {
                    color: #000000;
                    font-weight: bold;
                    display: inline-block;
                    min-width: 75px;
                    text-align: center;
                }

                /* Year Grand Summary Table */
                .summary-table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 11.5pt;
                    margin-bottom: 6px;
                }

                .summary-table th,
                .summary-table td {
                    border: 1px solid #7c8f9f;
                    padding: 10px 3px;
                    text-align: center;
                }

                .summary-table th {
                    background-color: #ffffff;
                    color: #004882;
                    font-weight: bold;
                    font-size: 11.5pt;
                    padding: 4px 3px;
                }

                .summary-table td.metric-label {
                    color: #004882;
                    font-weight: bold;
                    text-align: center;
                }

                .summary-table td.metric-data {
                    color: #000000;
                    font-weight: bold;
                }

                /* Main body content — reserve space so footer never overlaps */
                .main-body {
                    padding-bottom: 38mm;
                }

                /* Notes */
                .notes-box {
                    font-size: 8.5pt;
                    color: #053a74;
                    font-weight: bold;
                    line-height: 1.7;
                    margin-bottom: 4px;
                }

                /* Bottom Footer Wrapper (fixed div, then table inside) */
                .footer-wrapper {
                    width: 180mm;
                    position: fixed;
                    bottom: 15mm;
                    left: 15mm;
                }

                /* Footer Table - normal flow inside the fixed wrapper */
                .footer-table {
                    width: 100%;
                    border-collapse: collapse;
                }

                .footer-table td {
                    vertical-align: bottom;
                    text-align: center;
                    padding: 0;
                }

                .qr-td {
                    width: 30mm;
                    text-align: left;
                    vertical-align: bottom;
                }

                .qr-image {
                    max-width: 120px;
                    max-height: 120px;
                    width: auto;
                    height: auto;
                    display: block;
                    margin-bottom: 3px;
                }

                .dated-line {
                    font-size: 8.5pt;
                    font-weight: bold;
                    color: #004882;
                    white-space: nowrap;
                }

                .dated-val {
                    color: #000000;
                    font-weight: bold;
                }

                .sig-box {
                    height: 60px;
                    margin-bottom: 2px;
                    position: relative;
                }

                .sig-img {
                    height: 55px;
                    object-fit: contain;
                    display: block;
                    margin: 0 auto;
                }

                .sig-title-text {
                    font-size: 12pt;
                    font-weight: bold;
                    color: #004882;
                    border-top: 1.5px solid #8ba1b2;
                    padding-top: 2px;
                    display: inline-block;
                    min-width: 130px;
                }

                .seal-box {
                    width: 28mm;
                    text-align: center;
                    vertical-align: bottom;
                }

                .seal-img {
                    max-width: 120px;
                    max-height: 120px;
                    width: auto;
                    height: auto;
                    object-fit: contain;
                    display: block;
                    margin: 0 auto;
                }
            </style>
        </head>

        <body>

            <!-- High-Res Greek Key Seamless Frame -->
            <img src="<?= $borderSrc ?>" class="bg-border" alt="Border Frame">

            <!-- Whole-page Security Watermark Pattern -->
            <?php if (!empty($watermarkPatternSrc)): ?>
                <div class="bg-watermark-overlay"></div>
            <?php endif; ?>

            <!-- Watermark Logo -->
            <?php if (!empty($logoSrc)): ?>
                <img src="<?= $logoSrc ?>" class="watermark" alt="Watermark">
            <?php endif; ?>

            <div class="content-box">

                <div class="main-body">

                    <!-- Top Serial No, Logo, Enrollment No -->
                    <?php if (!empty($serialNo) || !empty($logoSrc) || !empty($enrollmentNo)): ?>
                        <table class="header-table">
                            <tr>
                                <td style="width: 38%; text-align: left;">
                                    <?php if (!empty($serialNo)): ?>
                                        <span class="serial-text">Serial No.: </span>
                                        <span class="serial-val"><?= $serialNo ?></span>
                                    <?php endif; ?>
                                </td>
                                <td style="width: 24%; text-align: center;">
                                    <?php if (!empty($logoSrc)): ?>
                                        <img src="<?= $logoSrc ?>" alt="Logo" class="top-logo-img">
                                    <?php endif; ?>
                                </td>
                                <td style="width: 38%; text-align: right;">
                                    <?php if (!empty($enrollmentNo)): ?>
                                        <span class="enroll-text">Enrollment No.: </span>
                                        <span class="enroll-val"><?= $enrollmentNo ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    <?php endif; ?>

                    <!-- Institute Title & Tagline -->
                    <?php if (!empty($instituteName)): ?>
                        <div class="institute-title"><?= $instituteName ?></div>
                    <?php endif; ?>
                    <?php if (!empty($tagline)): ?>
                        <div class="institute-tagline"><?= $tagline ?></div>
                    <?php endif; ?>

                    <!-- Accreditation Sub-lines -->
                    <?php
                    $hasSub1 = !empty($subLeft1) || !empty($subRight1);
                    $hasSub2 = !empty($subLeft2) || !empty($subRight2);
                    if ($hasSub1 || $hasSub2):
                        ?>
                        <table class="accreditation-table">
                            <?php if ($hasSub1): ?>
                                <tr>
                                    <td style="width: 50%; text-align: left;"><?= $subLeft1 ?></td>
                                    <td style="width: 50%; text-align: right;"><?= $subRight1 ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($hasSub2): ?>
                                <tr>
                                    <td style="width: 50%; text-align: left;"><?= $subLeft2 ?></td>
                                    <td style="width: 50%; text-align: right;"><?= $subRight2 ?></td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    <?php endif; ?>

                    <!-- Performance Statement / Marksheet Ribbon Banner (text baked into image via GD or reference image) -->
                    <?php if (!empty($cartoucheSrc) || !empty($marksheetTitle)): ?>
                        <div class="ribbon-banner-wrap">
                            <?php if (!empty($cartoucheSrc)): ?>
                                <img src="<?= $cartoucheSrc ?>" alt="<?= htmlspecialchars($marksheetTitle) ?>"
                                    style="width: 70%; height: 120px; display: block; object-fit: contain;">
                            <?php else: ?>
                                <div
                                    style="width: 100%; height: 44px; margin: 0 auto; background-color: #0a3060; border: 2px solid #dab258; border-radius: 22px; text-align: center; line-height: 1.7;">
                                    <span
                                        style="font-family: 'Georgia', 'Times New Roman', serif; font-size: 14pt; font-weight: bold; font-style: italic; color: #ffffff; letter-spacing: 0.5px;"><?= $marksheetTitle ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Student Info Grid & Photo -->
                    <?php
                    $hasLeftCol = !empty($studentName) || !empty($fatherName) || !empty($motherName) || !empty($courseName);
                    $hasRightCol = !empty($rollNumber) || !empty($dateOfBirth) || !empty($session);
                    $hasPhoto = !empty($photoSrc);
                    if ($hasLeftCol || $hasRightCol || $hasPhoto):
                        if ($hasPhoto) {
                            if ($hasLeftCol && $hasRightCol) {
                                $leftColWidth = '47%';
                                $rightColWidth = '38%';
                            } elseif ($hasLeftCol) {
                                $leftColWidth = '82%';
                                $rightColWidth = '0%';
                            } else {
                                $leftColWidth = '0%';
                                $rightColWidth = '82%';
                            }
                        } else {
                            if ($hasLeftCol && $hasRightCol) {
                                $leftColWidth = '56%';
                                $rightColWidth = '44%';
                            } elseif ($hasLeftCol) {
                                $leftColWidth = '100%';
                                $rightColWidth = '0%';
                            } else {
                                $leftColWidth = '0%';
                                $rightColWidth = '100%';
                            }
                        }
                        ?>
                        <table class="student-info-table">
                            <tr>
                                <?php if ($hasLeftCol): ?>
                                    <td style="width: <?= $leftColWidth ?>; vertical-align: top;">
                                        <table style="width: 100%; border-collapse: collapse;">
                                            <?php if (!empty($studentName)): ?>
                                                <tr>
                                                    <td class="info-label" style="width: 105px;">Student Name:</td>
                                                    <td class="info-val"><?= $studentName ?></td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php if (!empty($fatherName)): ?>
                                                <tr>
                                                    <td class="info-label">Father's Name:</td>
                                                    <td class="info-val"><?= $fatherName ?></td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php if (!empty($motherName)): ?>
                                                <tr>
                                                    <td class="info-label">Mother's Name:</td>
                                                    <td class="info-val"><?= $motherName ?></td>
                                                </tr>
                                            <?php endif; ?>

                                        </table>
                                    </td>
                                <?php endif; ?>

                                <?php if ($hasRightCol): ?>
                                    <td style="width: <?= $rightColWidth ?>; vertical-align: top;">
                                        <table style="width: 100%; border-collapse: collapse;">
                                            <?php if (!empty($rollNumber)): ?>
                                                <tr>
                                                    <td class="info-label" style="width: 90px;">Roll Number:</td>
                                                    <td class="info-val"><?= $rollNumber ?></td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php if (!empty($dateOfBirth)): ?>
                                                <tr>
                                                    <td class="info-label">Date of Birth:</td>
                                                    <td class="info-val"><?= $dateOfBirth ?></td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php if (!empty($session)): ?>
                                                <tr>
                                                    <td class="info-label">Session:</td>
                                                    <td class="info-val"><?= $session ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        </table>
                                    </td>
                                <?php endif; ?>

                                <?php if ($hasPhoto): ?>
                                    <td class="photo-cell">
                                        <img src="<?= $photoSrc ?>" alt="Student Photo" class="photo-img">
                                    </td>
                                <?php endif; ?>
                            </tr>
                            <?php if (!empty($courseName)): ?>
                                <tr>
                                    <td colspan="2" class="info-label">Course Name:
                                        <span class="info-val">
                                            <?= $courseName ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    <?php endif; ?>

                    <!-- Section Title: Statement of Marks -->
                    <?php if (!empty($statementTitle)): ?>
                        <div class="statement-banner">
                            <?= $statementTitle ?>
                        </div>
                    <?php endif; ?>

                    <!-- Statement of Marks Table -->
                    <?php if (!empty($d['subjects'])): ?>
                        <table class="marks-table">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width: 7%;">Code</th>
                                    <th rowspan="2" style="width: 35%;">Subject</th>
                                    <th colspan="3" style="width: 28%;">Marks Obtained</th>
                                    <th rowspan="2" style="width: 10%;">Min.<br>Marks</th>
                                    <th rowspan="2" style="width: 10%;">Max.<br>Marks</th>
                                    <th rowspan="2" style="width: 10%;">Grade</th>
                                </tr>
                                <tr>
                                    <th style="width: 9%;">Marks</th>
                                    <th style="width: 9%;">Internal</th>
                                    <th style="width: 10%;">Total<br>Marks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($d['subjects'] as $sub): ?>
                                    <tr>
                                        <td class="val-cell"><?= htmlspecialchars((string) $sub['code']) ?></td>
                                        <td class="sub-name"><?= htmlspecialchars((string) $sub['name']) ?></td>
                                        <td class="val-cell"><?= htmlspecialchars((string) $sub['marks']) ?></td>
                                        <td class="val-cell"><?= htmlspecialchars((string) $sub['internal']) ?></td>
                                        <td class="val-cell"><?= htmlspecialchars((string) $sub['total']) ?></td>
                                        <td class="val-cell"><?= htmlspecialchars((string) $sub['min_marks']) ?></td>
                                        <td class="val-cell"><?= htmlspecialchars((string) $sub['max_marks']) ?></td>
                                        <td class="val-cell"><strong><?= htmlspecialchars((string) $sub['grade']) ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>

                                <!-- Total Row -->
                                <tr class="total-row">
                                    <td colspan="2" style="text-align: right; padding-right: 15px;"><strong>Total</strong></td>
                                    <td class="val-cell"><?= $d['total_marks_obtained'] ?></td>
                                    <td class="val-cell"><?= $d['total_internal'] ?></td>
                                    <td class="val-cell"><?= $d['grand_total_current'] ?></td>
                                    <td class="val-cell"><?= $d['total_min_marks'] ?></td>
                                    <td class="val-cell"><?= $d['total_max_marks'] ?></td>
                                    <td class="val-cell"><strong><?= $overallGrade ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    <?php endif; ?>

                    <!-- Percentage Line -->
                    <?php if (!empty($percentage) && !empty($d['subjects'])): ?>
                        <div class="percentage-row">
                            <span class="percent-label">Percentage</span>
                            <span class="percent-val"><?= $percentage ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- Grand Total Summary Multi-Year Table -->
                    <?php
                    $cols = [];
                    if (!empty($yearSummary['year_1_max']) || !empty($yearSummary['year_1_obtained'])) {
                        $cols['y1'] = ['label' => '1st Year', 'max' => $yearSummary['year_1_max'] ?? '', 'obt' => $yearSummary['year_1_obtained'] ?? ''];
                    }
                    if (!empty($yearSummary['year_2_max']) || !empty($yearSummary['year_2_obtained'])) {
                        $cols['y2'] = ['label' => '2nd Year', 'max' => $yearSummary['year_2_max'] ?? '', 'obt' => $yearSummary['year_2_obtained'] ?? ''];
                    }
                    if (!empty($yearSummary['grand_max']) || !empty($yearSummary['grand_obtained'])) {
                        $cols['grand'] = ['label' => 'Grand Total', 'max' => $yearSummary['grand_max'] ?? '', 'obt' => $yearSummary['grand_obtained'] ?? ''];
                    }
                    if (!empty($cols)):
                        $numCols = count($cols);
                        $dataColWidth = floor(68 / $numCols) . '%';
                        $labelColWidth = (100 - (floor(68 / $numCols) * $numCols)) . '%';
                        ?>
                        <table class="summary-table">
                            <thead>
                                <tr>
                                    <th style="width: <?= $labelColWidth ?>;">Year</th>
                                    <?php foreach ($cols as $col): ?>
                                        <th style="width: <?= $dataColWidth ?>;"><?= htmlspecialchars($col['label']) ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="metric-label">Maximum Marks</td>
                                    <?php foreach ($cols as $col): ?>
                                        <td class="metric-data"><?= htmlspecialchars((string) $col['max']) ?></td>
                                    <?php endforeach; ?>
                                </tr>
                                <tr>
                                    <td class="metric-label">Marks Obtained</td>
                                    <?php foreach ($cols as $col): ?>
                                        <td class="metric-data"><?= htmlspecialchars((string) $col['obt']) ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            </tbody>
                        </table>
                    <?php endif; ?>

                    <!-- Verification Notes -->
                    <?php
                    $hasVerif = !empty($verificationUrl) || !empty($verificationEmail);
                    if ($hasVerif || !empty($d['subjects'])):
                        ?>
                        <div class="notes-box">
                            <?php if ($hasVerif): ?>
                                <div><strong>Note: 1.</strong> For verification of this document<?php
                                if (!empty($verificationUrl) && !empty($verificationEmail)) {
                                    echo ', log on to <strong>' . $verificationUrl . '</strong> or by emailing on <strong>' . $verificationEmail . '</strong>';
                                } elseif (!empty($verificationUrl)) {
                                    echo ', log on to <strong>' . $verificationUrl . '</strong>';
                                } elseif (!empty($verificationEmail)) {
                                    echo ', by emailing on <strong>' . $verificationEmail . '</strong>';
                                }
                                ?></div>
                                <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>2.</strong> Details of Grading
                                    given on reverse.</div>
                            <?php else: ?>
                                <div><strong>Note:</strong> Details of Grading given on reverse.</div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                </div><!-- /.main-body -->
            </div><!-- /.content-box -->

            <!-- Footer Wrapper: position:fixed div > table inside -->
            <?php
            $hasQrCell = !empty($qrSrc) || !empty($issueDate);
            $hasController = !empty($controllerTitle);
            $hasDirector = !empty($directorTitle);
            $hasSeal = !empty($sealSrc);
            if ($hasQrCell || $hasController || $hasDirector || $hasSeal):
                ?>
                <div class="footer-wrapper">
                    <table class="footer-table">
                        <tr>
                            <td class="qr-td">
                                <?php if (!empty($qrSrc)): ?>
                                    <img src="<?= $qrSrc ?>" alt="QR Code" class="qr-image">
                                <?php endif; ?>
                                <?php if (!empty($issueDate)): ?>
                                    <div class="dated-line">Dated : <span class="dated-val"><?= $issueDate ?></span></div>
                                <?php endif; ?>
                            </td>
                            <td style="width: 32%;">
                                <?php if ($hasController): ?>
                                    <div class="sig-box">
                                        <?php if (!empty($sigControllerSrc)): ?>
                                            <img src="<?= $sigControllerSrc ?>" alt="Controller Signature" class="sig-img">
                                        <?php endif; ?>
                                    </div>
                                    <div class="sig-title-text"><?= $controllerTitle ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="seal-box">
                                <!-- Circular Official Seal Image -->
                                <?php if (!empty($sealSrc)): ?>
                                    <img src="<?= $sealSrc ?>" alt="Official Seal" class="seal-img">
                                <?php endif; ?>
                            </td>
                            <td style="width: 32%;">
                                <?php if ($hasDirector): ?>
                                    <div class="sig-box">
                                        <?php if (!empty($sigDirectorSrc)): ?>
                                            <img src="<?= $sigDirectorSrc ?>" alt="Director Signature" class="sig-img">
                                        <?php endif; ?>
                                    </div>
                                    <div class="sig-title-text"><?= $directorTitle ?></div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            <?php endif; ?>

        </body>

        </html>
        <?php
        return ob_get_clean();
    }

    public function generatePdf(string $mode = 'download', ?string $filename = null): void
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Helvetica');
        $options->set('dpi', 150);

        $dompdf = new Dompdf($options);
        $html = $this->renderHtml();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        if ($filename === null) {
            $safeName = preg_replace('/[^A-Za-z0-9_-]/', '_', $this->data['student_name'] ?? 'Student');
            $filename = 'Marksheet_' . ($safeName ?: 'Student') . '_' . ($this->data['roll_number'] ?? 'Marksheet') . '.pdf';
        }

        // Clear any previous output or warnings from the buffer to ensure pure PDF binary
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        if ($mode === 'preview') {
            $dompdf->stream($filename, ['Attachment' => false]);
        } elseif ($mode === 'string') {
            echo $dompdf->output();
        } else {
            $dompdf->stream($filename, ['Attachment' => true]);
        }
    }

    public function getPdfOutput(): string
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Helvetica');
        $options->set('dpi', 150);

        $dompdf = new Dompdf($options);
        $html = $this->renderHtml();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }
}
