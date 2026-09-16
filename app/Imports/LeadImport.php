<?php

namespace App\Imports;

use App\Models\Lead;
use App\Models\Course;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;

class LeadImport
{
    public function import($filePath, $columnMap = null)
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        if (empty($rows)) {
            return ['success' => false, 'message' => 'The uploaded file is empty.'];
        }

        // Determine if first row looks like headers
        $firstRow = $rows[0];
        $hasHeaders = $this->rowLooksLikeHeader($firstRow);

        // Build numeric column index map
        if (is_array($columnMap) && ! empty($columnMap)) {
            $colIndices = $this->normalizeColumnMap($columnMap);
        } elseif ($hasHeaders) {
            $headers = array_map('strtolower', array_map('trim', $firstRow));
            $colIndices = $this->mapColumns($headers);
        } else {
            // Default mapping by Excel letters when there are no headers
            $default = [
                'name' => 'A',
                'mobile' => 'B',
                'email' => 'C',
                'address' => 'D',
                'location' => 'E',
                'city' => 'F',
                'state' => 'G',
                'source' => 'H',
                'status' => 'I',
                'course' => 'J',
                'assigned_to' => 'K',
                'followup_date' => 'L',
                'converted_at' => 'M',
                'remarks' => 'N',
            ];
            $colIndices = $this->normalizeColumnMap($default);
        }

        

        if (empty($colIndices) || ! ($colIndices['valid'] ?? false)) {
            return ['success' => false, 'message' => 'Invalid Excel format. Required columns: name, mobile, (optional: email, source, status, course, assigned_to)'];
        }

        $imported = 0;
        $errors = [];

        $dataRows = $hasHeaders ? array_slice($rows, 1) : $rows;

      

        foreach ($dataRows as $index => $row) {
            $rowNum = ($hasHeaders ? $index + 2 : $index + 1);

            // Skip empty rows
            if (empty($row[$colIndices['name']] ?? null) || empty($row[$colIndices['mobile']] ?? null)) {
                continue;
            }

            try {
                $data = [
                    'name' => (string)($this->getCellValue($row, $colIndices, 'name') ?? ''),
                    'mobile' => (string)($this->getCellValue($row, $colIndices, 'mobile') ?? ''),
                    'email' => $this->getCellValue($row, $colIndices, 'email'),
                    'address' => $this->getCellValue($row, $colIndices, 'address'),
                    'location' => $this->getCellValue($row, $colIndices, 'location'),
                    'city' => $this->getCellValue($row, $colIndices, 'city'),
                    'state' => $this->getCellValue($row, $colIndices, 'state'),
                    'source' => $this->getCellValue($row, $colIndices, 'source'),
                    'status' => $this->getCellValue($row, $colIndices, 'status') ?? 'new',
                    'course_interest_id' => $this->resolveCourse($this->getCellValue($row, $colIndices, 'course')),
                    'assigned_to' => $this->resolveUser($this->getCellValue($row, $colIndices, 'assigned_to')),
                    'next_followup_date' => $this->parseDate($this->getCellValue($row, $colIndices, 'followup_date')),
                    'converted_at' => $this->parseDate($this->getCellValue($row, $colIndices, 'converted_at')),
                    'remarks' => $this->getCellValue($row, $colIndices, 'remarks'),
                ];

                // Validate required fields
                if (strlen($data['name']) < 2 || strlen($data['name']) > 255) {
                    $errors[] = "Row {$rowNum}: Invalid name length.";
                    continue;
                }

                if (strlen($data['mobile']) < 5 || strlen($data['mobile']) > 50) {
                    $errors[] = "Row {$rowNum}: Invalid mobile length.";
                    continue;
                }

                if ($data['email'] && ! filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Row {$rowNum}: Invalid email format.";
                    continue;
                }

                // Check for duplicates
                $exists = Lead::where('name', $data['name'])
                    ->where('mobile', $data['mobile'])
                    ->exists();

                if ($exists) {
                    $errors[] = "Row {$rowNum}: Lead '{$data['name']}' already exists.";
                    continue;
                }

                Lead::create($data);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Row {$rowNum}: " . $e->getMessage();
            }
        }

        return [
            'success' => true,
            'imported' => $imported,
            'errors' => $errors,
            'message' => "Imported {$imported} leads successfully." . (! empty($errors) ? ' ' . count($errors) . ' errors occurred.' : ''),
        ];
    }
private function mapColumns(array $headers): array
{
    $map = [
        'name'            => null,
        'mobile'          => null,
        'email'           => null,
        'address'         => null,
        'location'        => null,
        'city'            => null,
        'state'           => null,
        'source'          => null,
        'course'          => null,
        'remarks'         => null,
        'followup_date'   => null,
        'assigned_to'     => null,
        'status'          => null,
        'converted_at'    => null,
    ];

    foreach ($headers as $index => $header) {

        $header = strtolower(trim($header));

        switch ($header) {

            case 'candidate name':
            case 'name':
            case 'lead name':
                $map['name'] = $index;
                break;

            case 'contact no':
            case 'contact number':
            case 'mobile':
            case 'mobile no':
            case 'phone':
                $map['mobile'] = $index;
                break;

            case 'email id':
            case 'email':
            case 'email address':
                $map['email'] = $index;
                break;

            case 'address':
            case 'street address':
                $map['address'] = $index;
                break;

            case 'location':
            case 'area':
            case 'locality':
                $map['location'] = $index;
                break;

            case 'city':
            case 'town':
                $map['city'] = $index;
                break;

            case 'state':
            case 'province':
                $map['state'] = $index;
                break;

            case 'lead source':
            case 'source':
                $map['source'] = $index;
                break;

            case 'courses applied for':
            case 'course':
            case 'course interest':
                $map['course'] = $index;
                break;

            case 'date':
                $map['followup_date'] = $index;
                break;
        }
    }

    $map['valid'] =
        $map['name'] !== null &&
        $map['mobile'] !== null;

    return $map;
}


    private function rowLooksLikeHeader(array $row): bool
    {
        foreach ($row as $cell) {
            $cell = strtolower(trim((string)$cell));
            if ($cell === '') {
                continue;
            }

            if (str_contains($cell, 'name') || str_contains($cell, 'mobile') || str_contains($cell, 'email')) {
                return true;
            }
        }

        return false;
    }

    private function normalizeColumnMap(array $map): array
    {
        $keys = ['name','mobile','email','address','location','city','state','source','status','course','assigned_to','followup_date','converted_at','remarks'];
        $res = array_fill_keys($keys, null);

        foreach ($keys as $key) {
            if (! isset($map[$key])) {
                continue;
            }

            $val = $map[$key];
            if (is_int($val)) {
                $res[$key] = $val;
                continue;
            }

            $val = trim((string)$val);
            if ($val === '') {
                continue;
            }

            // If it's a column letter like 'A' or 'AA'
            if (preg_match('/^[A-Za-z]+$/', $val)) {
                $res[$key] = $this->columnLetterToIndex($val);
                continue;
            }

            // If it's numeric string
            if (is_numeric($val)) {
                $res[$key] = (int)$val;
                continue;
            }
        }

        $res['valid'] = $res['name'] !== null && $res['mobile'] !== null;

        return $res;
    }

    private function columnLetterToIndex(string $letters): int
    {
        $letters = strtoupper($letters);
        $len = strlen($letters);
        $index = 0;

        for ($i = 0; $i < $len; $i++) {
            $index = $index * 26 + (ord($letters[$i]) - ord('A') + 1);
        }

        return $index - 1; // zero-based
    }

    private function resolveCourse($courseName)
    {
        if (empty($courseName)) {
            return null;
        }

        $course = Course::where('name', 'like', "%{$courseName}%")->first();
        
        return $course?->id;
    }

    private function resolveUser($userName)
    {
        if (empty($userName)) {
            return null;
        }

        $user = User::where('name', 'like', "%{$userName}%")
            ->orWhere('email', 'like', "%{$userName}%")
            ->first();
        
        return $user?->id;
    }

    private function parseDate($dateValue)
    {
        if (empty($dateValue)) {
            return null;
        }

        try {
            // Handle Excel date format (numeric)
            if (is_numeric($dateValue)) {
                $excelDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateValue);
                return $excelDate->format('Y-m-d');
            }

            // Handle string dates
            $date = \DateTime::createFromFormat('Y-m-d', $dateValue) 
                ?? \DateTime::createFromFormat('m/d/Y', $dateValue)
                ?? \DateTime::createFromFormat('d/m/Y', $dateValue);
            
            return $date ? $date->format('Y-m-d') : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getCellValue(array $row, array $colIndices, string $key): ?string
    {
        if (! isset($colIndices[$key]) || $colIndices[$key] === null) {
            return null;
        }

        $index = $colIndices[$key];
        if (! isset($row[$index])) {
            return null;
        }

        $val = trim((string)$row[$index]);

        return $val !== '' ? $val : null;
    }
}
