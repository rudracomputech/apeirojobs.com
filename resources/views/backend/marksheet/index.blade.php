<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marksheet &amp; Certificate Generator - HS Institute</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Cinzel:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #086ea8;
            --primary-dark: #053a74;
            --primary-light: #e8f4fb;
            --accent-gold: #c4913c;
            --accent-gold-dark: #996515;
            --bg-page: #0f172a;
            --surface: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border: #334155;
            --border-light: #475569;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 18px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.2);
            --shadow-md: 0 6px 18px rgba(0,0,0,0.3);
            --shadow-lg: 0 12px 32px rgba(0,0,0,0.4);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-page);
            color: var(--text-main);
            line-height: 1.5;
            padding: 20px 16px 60px;
        }

        .main-wrapper {
            max-width: 1240px;
            margin: 0 auto;
        }

        /* Top Brand Header */
        .brand-header {
            background: linear-gradient(135deg, #053a74 0%, #086ea8 50%, #0d5089 100%);
            border-radius: var(--radius-lg);
            padding: 24px;
            color: #ffffff;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
            border: 2px solid rgba(196, 145, 60, 0.4);
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .brand-group {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .logo-circle {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #ffffff;
            padding: 4px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--accent-gold);
            flex-shrink: 0;
        }

        .logo-circle img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .brand-titles h1 {
            font-family: 'Cinzel', serif, 'Plus Jakarta Sans';
            font-size: clamp(1.4rem, 2.5vw, 1.9rem);
            letter-spacing: 1px;
            text-transform: uppercase;
            font-weight: 800;
            color: #ffffff;
        }

        .brand-titles .tagline {
            font-size: 0.95rem;
            color: #fce7b2;
            font-weight: 600;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .student-select-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.12);
            padding: 6px 12px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .student-select-wrapper label {
            font-size: 13px;
            color: #cbd5e1;
            font-weight: 600;
            white-space: nowrap;
        }

        .student-select-wrapper select {
            background: #0f172a;
            color: #f8fafc;
            border: 1px solid #475569;
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 13px;
            outline: none;
            max-width: 200px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 16px;
            border-radius: var(--radius-sm);
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all 0.2s;
        }

        .btn-dashboard {
            background: rgba(255, 255, 255, 0.14);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.25);
        }
        .btn-dashboard:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .btn-primary {
            background: #2563eb;
            color: #ffffff;
        }
        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-secondary {
            background: #0284c7;
            color: #ffffff;
        }
        .btn-secondary:hover {
            background: #0369a1;
        }

        .btn-accent {
            background: #d97706;
            color: #ffffff;
        }
        .btn-accent:hover {
            background: #b45309;
        }

        .btn-danger {
            background: #dc2626;
            color: #ffffff;
        }
        .btn-danger:hover {
            background: #b91c1c;
        }

        /* Form Container */
        .form-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        /* Cards */
        .card {
            background: var(--surface);
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-md);
            padding: 22px;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .card-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #60a5fa;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .grid-4 {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        @media (max-width: 768px) {
            .grid-2, .grid-3, .grid-4 {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #cbd5e1;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
        input[type="email"],
        select,
        textarea {
            width: 100%;
            padding: 9px 12px;
            font-size: 0.92rem;
            font-family: inherit;
            border: 1px solid var(--border-light);
            border-radius: var(--radius-sm);
            background-color: #0f172a;
            color: #f8fafc;
            font-weight: 500;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
        }

        /* Photo Upload Container */
        .photo-upload-container {
            display: flex;
            align-items: center;
            gap: 18px;
            background: #0f172a;
            border: 2px dashed #3b82f6;
            padding: 14px;
            border-radius: var(--radius-sm);
        }

        .photo-preview {
            width: 80px;
            height: 100px;
            border: 2px solid #3b82f6;
            border-radius: 6px;
            overflow: hidden;
            background: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Interactive Subjects Table */
        .table-responsive {
            overflow-x: auto;
            margin-top: 10px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
        }

        .subjects-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.88rem;
        }

        .subjects-table th {
            background: #0f172a;
            color: #93c5fd;
            font-weight: 700;
            padding: 10px 8px;
            border-bottom: 2px solid var(--border);
            text-align: center;
            white-space: nowrap;
        }

        .subjects-table th:nth-child(2) {
            text-align: left;
        }

        .subjects-table td {
            padding: 6px 8px;
            border-bottom: 1px solid var(--border);
            text-align: center;
            vertical-align: middle;
        }

        .subjects-table td:nth-child(2) {
            text-align: left;
        }

        .subjects-table input {
            padding: 6px 8px;
            font-size: 0.85rem;
            text-align: center;
            border-radius: 6px;
        }

        .subjects-table td:nth-child(2) input {
            text-align: left;
        }

        .subjects-table tr.total-row {
            background-color: #0f172a;
            font-weight: bold;
        }

        .subjects-table tr.total-row td {
            border-top: 2px solid var(--border-light);
            padding: 10px 8px;
            color: #60a5fa;
        }

        .btn-delete-row {
            background: #450a0a;
            color: #f87171;
            border: 1px solid #7f1d1d;
            padding: 5px 8px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 700;
        }
        .btn-delete-row:hover {
            background: #dc2626;
            color: #ffffff;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .badge-percentage {
            background: #1e3a8a;
            color: #bfdbfe;
            border: 1px solid #3b82f6;
        }

        .bottom-bar {
            position: sticky;
            bottom: 0;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            padding: 16px 24px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            margin-top: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.5);
            z-index: 100;
        }
    </style>
</head>
<body>

<div class="main-wrapper">

    <!-- Top Brand Header -->
    <header class="brand-header">
        <div class="brand-group">
            <div class="logo-circle">
                <img src="{{ asset('marksheet-studio/assets/logo.png') }}" alt="Logo">
            </div>
            <div class="brand-titles">
                <h1>Marksheet &amp; Certificate Generator</h1>
                <p class="tagline">All India Council For Vocational &amp; Paramedical Science</p>
            </div>
        </div>

        <div class="header-actions">
            @if(isset($students) && $students->count() > 0)
            <div class="student-select-wrapper">
                <label for="studentQuickSelect">🎓 Quick Fill Student:</label>
                <select id="studentQuickSelect">
                    <option value="">-- Select Student --</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->student_code ?: '#' . $student->id }})</option>
                    @endforeach
                </select>
            </div>
            @endif
            <a href="{{ route('marksheet.records') }}" class="btn" style="background: #2563eb; color: #fff;">
                <span>📋</span> All Marksheets
            </a>

            <button type="button" class="btn" style="background: #10b981; color: #fff; font-weight: 700;" onclick="submitForm('save')">
                <span>💾</span> Save Marksheet
            </button>

            <a href="{{ route('dashboard') }}" class="btn btn-dashboard">
                <span>⬅️</span> Dashboard
            </a>

            <button type="button" class="btn btn-secondary" onclick="submitForm('html')">
                <span>🌐</span> View HTML
            </button>

            <button type="button" class="btn btn-primary" onclick="submitForm('preview')">
                <span>👁️</span> Preview PDF
            </button>

            <button type="button" class="btn btn-accent" onclick="submitForm('download')">
                <span>📄</span> Download PDF
            </button>
        </div>
    </header>

    <!-- Main Generator Form -->
    <form id="marksheetForm" action="{{ route('marksheet.generate') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="form_action" id="form_action" value="preview">
        <input type="hidden" name="record_id" value="{{ $record->id ?? '' }}">
        <input type="hidden" name="student_id" id="inp_student_id" value="{{ $record->student_id ?? '' }}">

        <div class="form-container">

            <!-- Card 1: Institution Details -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <span>🏛️</span> Institution Header &amp; Credentials
                    </div>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label for="institute_name">Institute / Council Name</label>
                        <input type="text" id="institute_name" name="institute_name" value="All India Council For Vocational &amp; Paramedical Science">
                    </div>
                    <div class="form-group">
                        <label for="tagline">Header Tagline / Subtitle</label>
                        <input type="text" id="tagline" name="tagline" value="Healthcare &amp; Paramedical Training">
                    </div>
                    <div class="form-group">
                        <label for="sub_left_1">Credential Bullet 1 (Left Top)</label>
                        <input type="text" id="sub_left_1" name="sub_left_1" value="• Run by All India Council for Vocational &amp; Paramedical Science">
                    </div>
                    <div class="form-group">
                        <label for="sub_right_1">Credential Bullet 3 (Right Top)</label>
                        <input type="text" id="sub_right_1" name="sub_right_1" value="• An Autonomous Institution Registered Under the Trust Act of 1882">
                    </div>
                    <div class="form-group">
                        <label for="sub_left_2">Credential Bullet 2 (Left Bottom)</label>
                        <input type="text" id="sub_left_2" name="sub_left_2" value="• Regd. Under MSME, Govt. of India">
                    </div>
                    <div class="form-group">
                        <label for="sub_right_2">Credential Bullet 4 (Right Bottom)</label>
                        <input type="text" id="sub_right_2" name="sub_right_2" value="• AN ISO 9001 : 2015 Certified Organization">
                    </div>
                </div>
            </div>

            <!-- Card 2: Student & Course Information -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <span>👤</span> Student &amp; Examination Details
                    </div>
                </div>
                <div class="grid-3">
                    <div class="form-group">
                        <label for="serial_no">Serial Number</label>
                        <input type="text" id="serial_no" name="serial_no" value="20235801">
                    </div>
                    <div class="form-group">
                        <label for="roll_number">Roll Number</label>
                        <input type="text" id="roll_number" name="roll_number" value="20243951">
                    </div>
                    <div class="form-group">
                        <label for="enrollment_no">Enrollment Number</label>
                        <input type="text" id="enrollment_no" name="enrollment_no" value="ACI2023233750">
                    </div>
                    <div class="form-group">
                        <label for="student_name">Candidate Full Name</label>
                        <input type="text" id="student_name" name="student_name" value="Laxman Patole">
                    </div>
                    <div class="form-group">
                        <label for="father_name">Father's Name</label>
                        <input type="text" id="father_name" name="father_name" value="Balasaheb">
                    </div>
                    <div class="form-group">
                        <label for="mother_name">Mother's Name</label>
                        <input type="text" id="mother_name" name="mother_name" value="Rupali">
                    </div>
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth</label>
                        <input type="text" id="date_of_birth" name="date_of_birth" value="25-06-2000">
                    </div>
                    <div class="form-group">
                        <label for="session">Academic Session</label>
                        <input type="text" id="session" name="session" value="Jun 2024 - Jun 2025">
                    </div>
                    <div class="form-group">
                        <label for="course_name">Course / Qualification Passed</label>
                        <input type="text" id="course_name" name="course_name" value="Diploma in General Nursing And Midwifery (GNM)">
                    </div>

                    <!-- Photo Upload Column -->
                    <div class="form-group full" style="margin-top: 10px;">
                        <label>Candidate Passport Photograph</label>
                        <div class="photo-upload-container">
                            <div class="photo-preview">
                                <img id="studentPhotoPreview" src="{{ asset('marksheet-studio/assets/sample_student.jpg') }}" alt="Student Photo">
                            </div>
                            <div>
                                <span style="font-size: 0.85rem; color: #94a3b8;">Upload passport-style student photograph (JPG, PNG)</span>
                                <input type="file" name="student_photo" id="student_photo" accept="image/*" onchange="previewPhoto(this)">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Subjects & Marks Matrix -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <span>📊</span> Subjects &amp; Marks Matrix
                    </div>
                    <div>
                        <span class="badge badge-percentage" id="disp_percentage_badge">Percentage: 70.71%</span>
                        <input type="hidden" name="percentage" id="hidden_percentage" value="70.71%">
                        <input type="hidden" name="overall_grade" id="hidden_grade" value="A">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="subjects-table">
                        <thead>
                            <tr>
                                <th style="width: 12%;">Code</th>
                                <th style="width: 40%;">Subject Title</th>
                                <th style="width: 10%;">Max</th>
                                <th style="width: 10%;">Min</th>
                                <th style="width: 10%;">Obt. (Th)</th>
                                <th style="width: 10%;">Internal</th>
                                <th style="width: 10%;">Total</th>
                                <th style="width: 8%;">Grade</th>
                                <th style="width: 6%;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="subjectsBody">
                            <!-- Pre-filled Sample Row 1 -->
                            <tr>
                                <td><input type="text" name="subject_code[]" value="GNM-201"></td>
                                <td><input type="text" name="subject_name[]" value="Medical Surgical Nursing - I"></td>
                                <td><input type="number" name="max_marks[]" class="calc-max" value="100" oninput="recalcTotals()"></td>
                                <td><input type="number" name="min_marks[]" class="calc-min" value="40" oninput="recalcTotals()"></td>
                                <td><input type="number" name="marks_obtained[]" class="calc-m" value="51" oninput="recalcTotals()"></td>
                                <td><input type="number" name="internal_marks[]" class="calc-i" value="20" oninput="recalcTotals()"></td>
                                <td><input type="text" name="total_marks[]" class="calc-t" value="71" readonly></td>
                                <td><input type="text" class="calc-g" value="A" readonly style="width: 45px;"></td>
                                <td><button type="button" class="btn-delete-row" onclick="deleteRow(this)">✕</button></td>
                            </tr>
                            <!-- Sample Row 2 -->
                            <tr>
                                <td><input type="text" name="subject_code[]" value="GNM-202"></td>
                                <td><input type="text" name="subject_name[]" value="Medical Surgical Nursing - II"></td>
                                <td><input type="number" name="max_marks[]" class="calc-max" value="100" oninput="recalcTotals()"></td>
                                <td><input type="number" name="min_marks[]" class="calc-min" value="40" oninput="recalcTotals()"></td>
                                <td><input type="number" name="marks_obtained[]" class="calc-m" value="53" oninput="recalcTotals()"></td>
                                <td><input type="number" name="internal_marks[]" class="calc-i" value="20" oninput="recalcTotals()"></td>
                                <td><input type="text" name="total_marks[]" class="calc-t" value="73" readonly></td>
                                <td><input type="text" class="calc-g" value="A" readonly style="width: 45px;"></td>
                                <td><button type="button" class="btn-delete-row" onclick="deleteRow(this)">✕</button></td>
                            </tr>
                            <!-- Sample Row 3 -->
                            <tr>
                                <td><input type="text" name="subject_code[]" value="GNM-203"></td>
                                <td><input type="text" name="subject_name[]" value="Mental Health &amp; Psychiatric Nursing"></td>
                                <td><input type="number" name="max_marks[]" class="calc-max" value="100" oninput="recalcTotals()"></td>
                                <td><input type="number" name="min_marks[]" class="calc-min" value="40" oninput="recalcTotals()"></td>
                                <td><input type="number" name="marks_obtained[]" class="calc-m" value="49" oninput="recalcTotals()"></td>
                                <td><input type="number" name="internal_marks[]" class="calc-i" value="19" oninput="recalcTotals()"></td>
                                <td><input type="text" name="total_marks[]" class="calc-t" value="68" readonly></td>
                                <td><input type="text" class="calc-g" value="B+" readonly style="width: 45px;"></td>
                                <td><button type="button" class="btn-delete-row" onclick="deleteRow(this)">✕</button></td>
                            </tr>
                            <!-- Sample Row 4 -->
                            <tr>
                                <td><input type="text" name="subject_code[]" value="GNM-204"></td>
                                <td><input type="text" name="subject_name[]" value="Child Health Nursing"></td>
                                <td><input type="number" name="max_marks[]" class="calc-max" value="100" oninput="recalcTotals()"></td>
                                <td><input type="number" name="min_marks[]" class="calc-min" value="40" oninput="recalcTotals()"></td>
                                <td><input type="number" name="marks_obtained[]" class="calc-m" value="52" oninput="recalcTotals()"></td>
                                <td><input type="number" name="internal_marks[]" class="calc-i" value="19" oninput="recalcTotals()"></td>
                                <td><input type="text" name="total_marks[]" class="calc-t" value="71" readonly></td>
                                <td><input type="text" class="calc-g" value="A" readonly style="width: 45px;"></td>
                                <td><button type="button" class="btn-delete-row" onclick="deleteRow(this)">✕</button></td>
                            </tr>
                            <!-- Sample Row 5 -->
                            <tr>
                                <td><input type="text" name="subject_code[]" value="GNM-205"></td>
                                <td><input type="text" name="subject_name[]" value="Practical - I (Medical Surgical Nursing)"></td>
                                <td><input type="number" name="max_marks[]" class="calc-max" value="100" oninput="recalcTotals()"></td>
                                <td><input type="number" name="min_marks[]" class="calc-min" value="50" oninput="recalcTotals()"></td>
                                <td><input type="number" name="marks_obtained[]" class="calc-m" value="52" oninput="recalcTotals()"></td>
                                <td><input type="number" name="internal_marks[]" class="calc-i" value="19" oninput="recalcTotals()"></td>
                                <td><input type="text" name="total_marks[]" class="calc-t" value="71" readonly></td>
                                <td><input type="text" class="calc-g" value="A" readonly style="width: 45px;"></td>
                                <td><button type="button" class="btn-delete-row" onclick="deleteRow(this)">✕</button></td>
                            </tr>
                            <!-- Sample Row 6 -->
                            <tr>
                                <td><input type="text" name="subject_code[]" value="GNM-206"></td>
                                <td><input type="text" name="subject_name[]" value="Practical - II (Child Health Nursing)"></td>
                                <td><input type="number" name="max_marks[]" class="calc-max" value="100" oninput="recalcTotals()"></td>
                                <td><input type="number" name="min_marks[]" class="calc-min" value="50" oninput="recalcTotals()"></td>
                                <td><input type="number" name="marks_obtained[]" class="calc-m" value="51" oninput="recalcTotals()"></td>
                                <td><input type="number" name="internal_marks[]" class="calc-i" value="20" oninput="recalcTotals()"></td>
                                <td><input type="text" name="total_marks[]" class="calc-t" value="71" readonly></td>
                                <td><input type="text" class="calc-g" value="A" readonly style="width: 45px;"></td>
                                <td><button type="button" class="btn-delete-row" onclick="deleteRow(this)">✕</button></td>
                            </tr>
                            <!-- Sample Row 7 -->
                            <tr>
                                <td><input type="text" name="subject_code[]" value="GNM-207"></td>
                                <td><input type="text" name="subject_name[]" value="Practical - III (Mental Health Nursing)"></td>
                                <td><input type="number" name="max_marks[]" class="calc-max" value="100" oninput="recalcTotals()"></td>
                                <td><input type="number" name="min_marks[]" class="calc-min" value="50" oninput="recalcTotals()"></td>
                                <td><input type="number" name="marks_obtained[]" class="calc-m" value="51" oninput="recalcTotals()"></td>
                                <td><input type="number" name="internal_marks[]" class="calc-i" value="19" oninput="recalcTotals()"></td>
                                <td><input type="text" name="total_marks[]" class="calc-t" value="70" readonly></td>
                                <td><input type="text" class="calc-g" value="A" readonly style="width: 45px;"></td>
                                <td><button type="button" class="btn-delete-row" onclick="deleteRow(this)">✕</button></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td colspan="2" style="text-align: right; padding-right: 15px;">Grand Total:</td>
                                <td id="disp_tot_max">700</td>
                                <td id="disp_tot_min">330</td>
                                <td id="disp_tot_m">361</td>
                                <td id="disp_tot_i">134</td>
                                <td id="disp_tot_t">495</td>
                                <td id="disp_tot_grade">A</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div style="margin-top: 14px; display: flex; justify-content: flex-start;">
                    <button type="button" class="btn btn-secondary" onclick="addRow()">
                        <span>➕</span> Add Subject Row
                    </button>
                </div>
            </div>

            <!-- Card 4: Multi-Year Academic Summary -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <span>📈</span> Multi-Year Cumulative Summary
                    </div>
                </div>
                <div class="grid-3">
                    <div class="form-group">
                        <label for="year_1_max">1st Year Max Marks</label>
                        <input type="number" id="year_1_max" name="year_1_max" value="700" oninput="recalcSummary()">
                    </div>
                    <div class="form-group">
                        <label for="year_1_obtained">1st Year Obtained Marks</label>
                        <input type="number" id="year_1_obtained" name="year_1_obtained" value="499" oninput="recalcSummary()">
                    </div>
                    <div class="form-group">
                        <label for="year_2_max">2nd Year Max Marks</label>
                        <input type="number" id="year_2_max" name="year_2_max" value="700" oninput="recalcSummary()">
                    </div>
                    <div class="form-group">
                        <label for="year_2_obtained">2nd Year Obtained Marks</label>
                        <input type="number" id="year_2_obtained" name="year_2_obtained" value="495" oninput="recalcSummary()">
                    </div>
                    <div class="form-group">
                        <label for="grand_max">Grand Maximum Marks</label>
                        <input type="number" id="grand_max" name="grand_max" value="1400">
                    </div>
                    <div class="form-group">
                        <label for="grand_obtained">Grand Total Marks Obtained</label>
                        <input type="number" id="grand_obtained" name="grand_obtained" value="994">
                    </div>
                </div>
            </div>

            <!-- Card 5: Marksheet Titles, Verification & Signatures -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <span>✒️</span> Titles, Verification &amp; Authorities
                    </div>
                </div>
                <div class="grid-3">
                    <div class="form-group">
                        <label for="marksheet_title">Main Marksheet Title</label>
                        <input type="text" id="marksheet_title" name="marksheet_title" value="Performance Statement/Marksheet">
                    </div>
                    <div class="form-group">
                        <label for="statement_title">Statement / Sub Title</label>
                        <input type="text" id="statement_title" name="statement_title" value="Statement of Marks 2nd Year">
                    </div>
                    <div class="form-group">
                        <label for="issue_date">Issue Date</label>
                        <input type="text" id="issue_date" name="issue_date" value="29-06-2025">
                    </div>
                    <div class="form-group">
                        <label for="controller_title">Left Signatory Title</label>
                        <input type="text" id="controller_title" name="controller_title" value="Examination Controller">
                    </div>
                    <div class="form-group">
                        <label for="director_title">Right Signatory Title</label>
                        <input type="text" id="director_title" name="director_title" value="Director">
                    </div>
                    <div class="form-group">
                        <label for="verification_url">Online Verification URL</label>
                        <input type="text" id="verification_url" name="verification_url" value="www.aicvps.org">
                    </div>
                    <div class="form-group full">
                        <label for="qr_data">Dynamic QR Code Data / URL</label>
                        <input type="text" id="qr_data" name="qr_data" value="http://apeirojobs.com/results/view?rollno=20243951">
                    </div>
                </div>
            </div>

        </div>

        <!-- Sticky Bottom Bar -->
        <div class="bottom-bar">
            <div style="font-size: 0.9rem; color: #94a3b8;">
                Generate official high-resolution A4 portrait Marksheet PDF with Greek meander borders, calligraphy typography, and verification QR code.
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="button" class="btn" style="background: #10b981; color: #fff; font-weight: 700;" onclick="submitForm('save')">
                    <span>💾</span> Save Marksheet
                </button>
                <button type="button" class="btn btn-secondary" onclick="submitForm('html')">
                    <span>🌐</span> View HTML
                </button>
                <button type="button" class="btn btn-primary" onclick="submitForm('preview')">
                    <span>👁️</span> Preview PDF
                </button>
                <button type="button" class="btn btn-accent" onclick="submitForm('download')">
                    <span>📄</span> Download PDF
                </button>
            </div>
        </div>

    </form>

</div>

<script>
    function getGrade(score) {
        if (score >= 75) return 'A+';
        if (score >= 65) return 'A';
        if (score >= 55) return 'B+';
        if (score >= 50) return 'B';
        if (score >= 40) return 'C';
        return 'F';
    }

    function recalcTotals() {
        let totalM = 0;
        let totalI = 0;
        let totalT = 0;
        let totalMin = 0;
        let totalMax = 0;
        let hasRows = false;

        const rows = document.querySelectorAll('#subjectsBody tr');
        rows.forEach(row => {
            const mVal = row.querySelector('.calc-m').value.trim();
            const iVal = row.querySelector('.calc-i').value.trim();
            const minVal = row.querySelector('.calc-min').value.trim();
            const maxVal = row.querySelector('.calc-max').value.trim();

            const m = parseFloat(mVal) || 0;
            const i = parseFloat(iVal) || 0;
            const min = parseFloat(minVal) || 0;
            const max = parseFloat(maxVal) || 0;

            const t = m + i;
            row.querySelector('.calc-t').value = t;
            row.querySelector('.calc-g').value = max > 0 ? getGrade((t / max) * 100) : getGrade(t);

            hasRows = true;
            totalM += m;
            totalI += i;
            totalT += t;
            totalMin += min;
            totalMax += max;
        });

        document.getElementById('disp_tot_m').textContent = hasRows ? totalM : '-';
        document.getElementById('disp_tot_i').textContent = hasRows ? totalI : '-';
        document.getElementById('disp_tot_t').textContent = hasRows ? totalT : '-';
        document.getElementById('disp_tot_min').textContent = hasRows ? totalMin : '-';
        document.getElementById('disp_tot_max').textContent = hasRows ? totalMax : '-';

        if (hasRows && totalMax > 0) {
            const pct = ((totalT / totalMax) * 100).toFixed(2);
            const finalGrade = getGrade((totalT / totalMax) * 100);

            document.getElementById('disp_tot_grade').textContent = finalGrade;
            document.getElementById('disp_percentage_badge').textContent = `Percentage: ${pct}%`;
            document.getElementById('hidden_percentage').value = `${pct}%`;
            document.getElementById('hidden_grade').value = finalGrade;
        }

        // Keep 2nd year in sync with current year totals
        const y2Max = document.getElementById('year_2_max');
        const y2Obt = document.getElementById('year_2_obtained');
        if (y2Max && y2Obt) {
            y2Max.value = totalMax;
            y2Obt.value = totalT;
            recalcSummary();
        }
    }

    function recalcSummary() {
        const y1Max = parseFloat(document.getElementById('year_1_max').value) || 0;
        const y2Max = parseFloat(document.getElementById('year_2_max').value) || 0;
        const y1Obt = parseFloat(document.getElementById('year_1_obtained').value) || 0;
        const y2Obt = parseFloat(document.getElementById('year_2_obtained').value) || 0;

        document.getElementById('grand_max').value = y1Max + y2Max;
        document.getElementById('grand_obtained').value = y1Obt + y2Obt;
    }

    function addRow() {
        const tbody = document.getElementById('subjectsBody');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" name="subject_code[]" value=""></td>
            <td><input type="text" name="subject_name[]" value=""></td>
            <td><input type="number" name="max_marks[]" class="calc-max" value="100" oninput="recalcTotals()"></td>
            <td><input type="number" name="min_marks[]" class="calc-min" value="40" oninput="recalcTotals()"></td>
            <td><input type="number" name="marks_obtained[]" class="calc-m" value="0" oninput="recalcTotals()"></td>
            <td><input type="number" name="internal_marks[]" class="calc-i" value="0" oninput="recalcTotals()"></td>
            <td><input type="text" name="total_marks[]" class="calc-t" value="0" readonly></td>
            <td><input type="text" class="calc-g" value="F" readonly style="width: 45px;"></td>
            <td><button type="button" class="btn-delete-row" onclick="deleteRow(this)">✕</button></td>
        `;
        tbody.appendChild(tr);
        recalcTotals();
    }

    function deleteRow(btn) {
        const row = btn.closest('tr');
        if (document.querySelectorAll('#subjectsBody tr').length > 1) {
            row.remove();
            recalcTotals();
        } else {
            alert('At least one subject row is required.');
        }
    }

    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('studentPhotoPreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function submitForm(mode) {
        const form = document.getElementById('marksheetForm');
        document.getElementById('form_action').value = mode;

        if (mode === 'preview' || mode === 'html') {
            form.target = '_blank';
        } else {
            form.target = '_self';
        }
        form.submit();
    }

    // Auto-update QR Code URL when roll number input changes
    const rollInput = document.getElementById('roll_number');
    if (rollInput) {
        rollInput.addEventListener('input', () => {
            const roll = rollInput.value.trim();
            const qrInput = document.getElementById('qr_data');
            if (qrInput) {
                qrInput.value = roll !== '' ? `http://apeirojobs.com/results/view?rollno=${encodeURIComponent(roll)}` : '';
            }
        });
    }

    // Student Quick Select Handler
    const studentSelect = document.getElementById('studentQuickSelect');
    if (studentSelect) {
        studentSelect.addEventListener('change', async (e) => {
            const studentId = e.target.value;
            if (!studentId) return;

            try {
                const res = await fetch(`/marksheet/student/${studentId}`);
                if (!res.ok) throw new Error('Failed to fetch student data');
                const data = await res.json();

                if (data.student_name) document.getElementById('student_name').value = data.student_name;
                if (data.father_name) document.getElementById('father_name').value = data.father_name;
                if (data.mother_name) document.getElementById('mother_name').value = data.mother_name;
                if (data.roll_number) {
                    document.getElementById('roll_number').value = data.roll_number;
                    document.getElementById('roll_number').dispatchEvent(new Event('input'));
                }
                if (data.enrollment_no) document.getElementById('enrollment_no').value = data.enrollment_no;
                if (data.date_of_birth) document.getElementById('date_of_birth').value = data.date_of_birth;
                if (data.course_name) document.getElementById('course_name').value = data.course_name;
                if (data.session) document.getElementById('session').value = data.session;
                if (data.issue_date) document.getElementById('issue_date').value = data.issue_date;
            } catch (err) {
                console.error('Error autofilling student marksheet:', err);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        recalcTotals();
    });
</script>

</body>
</html>
