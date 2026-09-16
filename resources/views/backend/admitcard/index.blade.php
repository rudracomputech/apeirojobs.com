<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admit Card Generator - {{ $data['studentName'] }}</title>
  <link rel="stylesheet" href="{{ asset('admitcard-studio/style.css') }}?v={{ time() }}">
  <style>
    /* Integrated Header Enhancements */
    .app-header .header-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 12px;
      padding: 10px 24px;
    }
    .student-select-wrapper {
      display: flex;
      align-items: center;
      gap: 8px;
      background: rgba(255, 255, 255, 0.08);
      padding: 6px 12px;
      border-radius: 8px;
      border: 1px solid rgba(255, 255, 255, 0.15);
    }
    .student-select-wrapper label {
      font-size: 13px;
      color: #94a3b8;
      font-weight: 500;
      white-space: nowrap;
    }
    .student-select-wrapper select {
      background: #1e293b;
      color: #f8fafc;
      border: 1px solid #475569;
      border-radius: 6px;
      padding: 6px 10px;
      font-size: 13px;
      outline: none;
      max-width: 200px;
    }
    .btn-dashboard {
      background: rgba(255, 255, 255, 0.1);
      color: #f8fafc;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 14px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      transition: all 0.2s;
    }
    .btn-dashboard:hover {
      background: rgba(255, 255, 255, 0.2);
    }
  </style>
</head>
<body>

  <!-- Top App Navigation -->
  <header class="app-header no-print">
    <div class="header-container">
      <div class="brand-group">
        <img src="{{ asset('admitcard-studio/assets/aicvps-logo.png') }}" class="brand-logo" alt="Council Logo">
        <div>
          <h1 class="brand-title">Admit Card Generator</h1>
          <p class="brand-subtitle">All India Council For Vocational &amp; Paramedical Science</p>
        </div>
      </div>

      <div class="header-actions" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
        @if(isset($students) && $students->count() > 0)
        <div class="student-select-wrapper">
          <label for="studentQuickSelect">🎓 Quick Fill Student:</label>
          <select id="studentQuickSelect">
            <option value="">-- Choose Student --</option>
            @foreach($students as $student)
              <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->student_code ?: '#' . $student->id }})</option>
            @endforeach
          </select>
        </div>
        @endif

        <a href="{{ route('admitcard.records') }}" class="btn btn-secondary" style="background: #2563eb; color: #fff;">
          <span class="btn-icon">📋</span> All Admit Cards
        </a>

        <button type="button" class="btn btn-success" id="btnSaveRecord" style="background: #10b981; color: #fff; font-weight: 600;">
          <span class="btn-icon">💾</span> Save Record
        </button>

        <a href="{{ route('dashboard') }}" class="btn btn-dashboard" title="Return to Dashboard">
          <span>⬅️</span> Dashboard
        </a>

        <button type="button" class="btn btn-secondary" id="btnOpenPrintView">
          <span class="btn-icon">📄</span> Standalone Print View
        </button>

        <button type="button" class="btn btn-primary" id="btnPrintQuick">
          <span class="btn-icon">🖨️</span> Print / Save as PDF
        </button>
      </div>
    </div>
  </header>

  <!-- Workspace Grid Layout -->
  <main class="workspace-layout">
    
    <!-- LEFT PANEL: Dynamic Form Editor -->
    <section class="editor-panel no-print">
      <div class="panel-header">
        <h2 class="panel-title">
          <span>📝</span> Edit Admit Card Details
        </h2>
        <button type="button" class="btn btn-danger" id="btnResetDefaults" style="padding: 5px 10px; font-size: 0.78rem;">
          Reset to Sample
        </button>
      </div>

      <form id="admitcardForm" method="POST" action="{{ route('admitcard.print') }}" target="_blank" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="record_id" value="{{ $data['id'] ?? '' }}">
        <input type="hidden" name="student_id" id="inp_student_id" value="{{ $data['student_id'] ?? '' }}">
        <!-- Hidden base64 containers -->
        <input type="hidden" name="photoBase64" id="inp_photoBase64" value="{{ $data['photoSrc'] }}">
        <input type="hidden" name="logoBase64" id="inp_logoBase64" value="{{ $data['logoSrc'] }}">
        <input type="hidden" name="stampBase64" id="inp_stampBase64" value="{{ $data['stampSrc'] }}">

        <div class="form-accordion">
          
          <!-- Section 1: Roll & Enrollment Numbers -->
          <div class="form-section">
            <div class="section-header">
              <span>🆔</span> Card Identification
            </div>
            <div class="section-body">
              <div class="form-row-2">
                <div class="form-group">
                  <label class="form-label" for="inp_rollNo">Roll Number</label>
                  <input type="text" id="inp_rollNo" name="rollNo" class="form-input" value="{{ $data['rollNo'] }}" required>
                </div>
                <div class="form-group">
                  <label class="form-label" for="inp_enrollmentNo">Enrollment Number</label>
                  <input type="text" id="inp_enrollmentNo" name="enrollmentNo" class="form-input" value="{{ $data['enrollmentNo'] }}" required>
                </div>
              </div>
            </div>
          </div>

          <!-- Section 2: Candidate Information -->
          <div class="form-section">
            <div class="section-header">
              <span>👤</span> Candidate Details
            </div>
            <div class="section-body">
              <div class="form-group">
                <label class="form-label" for="inp_studentName">Student Full Name</label>
                <input type="text" id="inp_studentName" name="studentName" class="form-input" value="{{ $data['studentName'] }}" required>
              </div>

              <div class="form-group">
                <label class="form-label" for="inp_parentName">Father's Name</label>
                <input type="text" id="inp_parentName" name="parentName" class="form-input" value="{{ $data['parentName'] }}" required>
              </div>

              <div class="form-row-2">
                <div class="form-group">
                  <label class="form-label" for="inp_batch">Batch</label>
                  <input type="text" id="inp_batch" name="batch" class="form-input" value="{{ $data['batch'] }}">
                </div>
                <div class="form-group">
                  <label class="form-label" for="inp_passYear">Examination Year</label>
                  <input type="text" id="inp_passYear" name="passYear" class="form-input" value="{{ $data['passYear'] }}">
                </div>
              </div>

              <div class="form-group">
                <label class="form-label" for="inp_courseName">Course Title</label>
                <input type="text" id="inp_courseName" name="courseName" class="form-input" value="{{ $data['courseName'] }}" required>
              </div>

              <div class="form-group">
                <label class="form-label" for="inp_examCentre">Exam Centre</label>
                <textarea id="inp_examCentre" name="examCentre" class="form-textarea" rows="2">{{ $data['examCentre'] }}</textarea>
              </div>
            </div>
          </div>

          <!-- Section 3: Candidate Photograph -->
          <div class="form-section">
            <div class="section-header">
              <span>📷</span> Candidate Photograph
            </div>
            <div class="section-body">
              <div class="photo-upload-box">
                <img id="photoPreviewThumb" src="{{ $data['photoSrc'] }}" class="photo-preview-thumb" alt="Student Thumbnail">
                <div class="photo-upload-details">
                  <span style="font-size: 0.8rem; font-weight: 600; color: #fff;">Upload Passport Photo</span>
                  <span style="font-size: 0.72rem; color: var(--text-muted);">Standard passport ratio (JPG / PNG)</span>
                  <div style="display: flex; gap: 8px; margin-top: 6px;">
                    <label class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.76rem; cursor: pointer;">
                      Browse Photo
                      <input type="file" id="inp_photoUpload" name="photoFile" accept="image/*" style="display: none;">
                    </label>
                    <button type="button" id="btnResetPhoto" class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.76rem;">
                      Reset Photo
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Section 3b: Council Logo Emblem -->
          <div class="form-section">
            <div class="section-header">
              <span>🏛️</span> Council Logo Emblem
            </div>
            <div class="section-body">
              <div class="photo-upload-box">
                <img id="logoPreviewThumb" src="{{ $data['logoSrc'] }}" class="photo-preview-thumb" style="object-fit: contain; background: #ffffff; padding: 3px;" alt="Logo Thumbnail">
                <div class="photo-upload-details">
                  <span style="font-size: 0.8rem; font-weight: 600; color: #fff;">Upload Council Emblem</span>
                  <span style="font-size: 0.72rem; color: var(--text-muted);">Round logo emblem (PNG / SVG / JPG)</span>
                  <div style="display: flex; gap: 8px; margin-top: 6px;">
                    <label class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.76rem; cursor: pointer;">
                      Browse Logo
                      <input type="file" id="inp_logoUpload" name="logoFile" accept="image/*" style="display: none;">
                    </label>
                    <button type="button" id="btnResetLogo" class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.76rem;">
                      Reset Logo
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Section 4: Institution & Council Header Details -->
          <div class="form-section">
            <div class="section-header">
              <span>🏛️</span> Institution Header &amp; Credentials
            </div>
            <div class="section-body">
              <div class="form-group">
                <label class="form-label" for="inp_councilName">Council Main Heading</label>
                <input type="text" id="inp_councilName" name="councilName" class="form-input" value="{{ $data['councilName'] }}">
              </div>

              <div class="form-group">
                <label class="form-label" for="inp_bullet1">Credential Bullet 1 (Left)</label>
                <input type="text" id="inp_bullet1" name="bullet1" class="form-input" value="{{ $data['bullet1'] }}">
              </div>

              <div class="form-group">
                <label class="form-label" for="inp_bullet2">Credential Bullet 2 (Left)</label>
                <input type="text" id="inp_bullet2" name="bullet2" class="form-input" value="{{ $data['bullet2'] }}">
              </div>

              <div class="form-group">
                <label class="form-label" for="inp_bullet3">Credential Bullet 3 (Right)</label>
                <input type="text" id="inp_bullet3" name="bullet3" class="form-input" value="{{ $data['bullet3'] }}">
              </div>

              <div class="form-group">
                <label class="form-label" for="inp_bullet4">Credential Bullet 4 (Right)</label>
                <input type="text" id="inp_bullet4" name="bullet4" class="form-input" value="{{ $data['bullet4'] }}">
              </div>

              <div class="form-group">
                <label class="form-label" for="inp_cardTitle">Card Title (Cartouche)</label>
                <input type="text" id="inp_cardTitle" name="cardTitle" class="form-input" value="{{ $data['cardTitle'] }}">
              </div>
            </div>
          </div>

          <!-- Section 5: Signatures & Disclaimer -->
          <div class="form-section">
            <div class="section-header">
              <span>✍️</span> Signatures &amp; Disclaimer
            </div>
            <div class="section-body">
              <div class="form-group">
                <label class="form-label" for="inp_candidateSigTitle">Signature 1 Title</label>
                <input type="text" id="inp_candidateSigTitle" name="candidateSigTitle" class="form-input" value="{{ $data['candidateSigTitle'] }}">
              </div>

              <div class="form-group">
                <label class="form-label" for="inp_coordinatorTitle">Signature 2 Title</label>
                <input type="text" id="inp_coordinatorTitle" name="coordinatorTitle" class="form-input" value="{{ $data['coordinatorTitle'] }}">
              </div>

              <div class="form-group">
                <label class="form-label" for="inp_controllerTitle">Signature 3 Title</label>
                <input type="text" id="inp_controllerTitle" name="controllerTitle" class="form-input" value="{{ $data['controllerTitle'] }}">
              </div>

              <div style="display: flex; align-items: center; gap: 8px; margin: 4px 0 8px 0;">
                <input type="checkbox" id="inp_showStamp" name="showStamp" value="1" {{ !empty($data['showStamp']) ? 'checked' : '' }}>
                <label for="inp_showStamp" style="font-size: 0.8rem; font-weight: 600; color: #fff; cursor: pointer;">
                  Display Controller Official Stamp &amp; Signature
                </label>
              </div>

              <!-- Controller Stamp Upload Box -->
              <div class="photo-upload-box" style="margin-bottom: 12px;">
                <img id="stampPreviewThumb" src="{{ $data['stampSrc'] }}" class="photo-preview-thumb" style="object-fit: contain; background: #ffffff; padding: 4px;" alt="Stamp Thumbnail">
                <div class="photo-upload-details">
                  <span style="font-size: 0.8rem; font-weight: 600; color: #fff;">Upload Official Seal / Stamp</span>
                  <span style="font-size: 0.72rem; color: var(--text-muted);">Transparent PNG, SVG, or JPG signature seal</span>
                  <div style="display: flex; gap: 8px; margin-top: 6px;">
                    <label class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.76rem; cursor: pointer;">
                      Browse Stamp
                      <input type="file" id="inp_stampUpload" name="stampFile" accept="image/*" style="display: none;">
                    </label>
                    <button type="button" id="btnResetStamp" class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.76rem;">
                      Reset Stamp
                    </button>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label" for="inp_disclaimer">Bottom Disclaimer Note</label>
                <textarea id="inp_disclaimer" name="disclaimer" class="form-textarea">{{ $data['disclaimer'] }}</textarea>
              </div>
            </div>
          </div>

          <div style="margin-top: 16px; display: flex; gap: 10px;">
            <button type="button" id="btnSaveRecordBottom" class="btn btn-primary" style="flex: 1; background: #10b981; border: none; padding: 10px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;">
              💾 Save Admit Card Record
            </button>
          </div>
        </div>

      </form>
    </section>

    <!-- RIGHT PANEL: Authentic Live Admit Card Preview -->
    <section class="preview-panel">
      
      <!-- Preview Header Toolbar -->
      <div class="preview-toolbar no-print">
        <div class="preview-title">
          <span>👁️</span> Real-time Live Preview
          <span class="live-indicator">
            <span class="live-dot"></span> Live Sync
          </span>
        </div>
        <div class="zoom-controls">
          <span style="font-size: 0.8rem; color: var(--text-muted); margin-right: 6px;">Zoom:</span>
          <button type="button" class="zoom-btn" id="btnZoomOut" title="Zoom Out">−</button>
          <button type="button" class="zoom-btn" id="btnZoomReset" title="Reset Zoom">1:1</button>
          <button type="button" class="zoom-btn" id="btnZoomIn" title="Zoom In">+</button>
        </div>
      </div>

      <!-- Certificate Viewport Container -->
      <div class="cert-viewport">
        <div class="admitcard-sheet" id="admitcardSheet">
          
          <!-- Greek Key Ornamental Meander Border -->
          <img src="{{ asset('admitcard-studio/assets/greek-border-hd.png') }}" class="cert-greek-border" alt="Decorative Border">

          <!-- Dynamic Council Security Watermark Text Pattern -->
          <div class="cert-watermark-pattern" aria-hidden="true">
            <svg class="cert-watermark-svg" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
              <defs>
                <pattern id="watermarkTextPattern" width="760" height="24" patternUnits="userSpaceOnUse">
                  <text id="cert_watermarkPatternText" x="0" y="16" font-family="'Cinzel', 'Playfair Display', 'Times New Roman', serif" font-size="8.5" font-weight="700" fill="#a48c66" opacity="0.22" letter-spacing="1.5">{{ $watermarkRepeat }}</text>
                </pattern>
              </defs>
              <rect width="100%" height="100%" fill="url(#watermarkTextPattern)" />
            </svg>
          </div>

          <!-- Watermark Logo Crest in Background -->
          <img id="cert_watermarkCrest" src="{{ $data['logoSrc'] }}" class="cert-watermark-crest" alt="Watermark Emblem">

          <!-- Inner Certificate Content Structure -->
          <div class="cert-content">
            
            <!-- ── Top Bar: Roll No, Center Emblem, Enrollment No ── -->
            <div class="cert-top-bar">
              <div class="cert-num-badge">
                Roll No.: <span id="cert_rollNo">{{ $data['rollNo'] }}</span>
              </div>
              <div class="cert-top-emblem-wrap">
                <img id="cert_topLogo" src="{{ $data['logoSrc'] }}" class="cert-top-emblem" alt="AICVPS Emblem">
              </div>
              <div class="cert-num-badge">
                Enrollment No.: <span id="cert_enrollmentNo">{{ $data['enrollmentNo'] }}</span>
              </div>
            </div>

            <!-- ── Header Block: Council Title & 4 Bullets ── -->
            <div class="cert-header-block">
              <h2 class="cert-council-title" id="cert_councilName">
                {{ $data['councilName'] }}
              </h2>
              <div class="cert-bullets-grid">
                <div class="bullet-item">
                  <span class="bullet-dot">•</span>
                  <span id="cert_bullet1">{{ $data['bullet1'] }}</span>
                </div>
                <div class="bullet-item">
                  <span class="bullet-dot">•</span>
                  <span id="cert_bullet3">{{ $data['bullet3'] }}</span>
                </div>
                <div class="bullet-item">
                  <span class="bullet-dot">•</span>
                  <span id="cert_bullet2">{{ $data['bullet2'] }}</span>
                </div>
                <div class="bullet-item">
                  <span class="bullet-dot">•</span>
                  <span id="cert_bullet4">{{ $data['bullet4'] }}</span>
                </div>
              </div>
            </div>

            <!-- ── Center Cartouche: "Admit Card" (Authentic Design) ── -->
            <div class="cert-cartouche-container">
              <img src="{{ asset('admitcard-studio/assets/cartouche-filigree-left.png') }}" class="cartouche-bracket left" alt="Flourish Bracket">
              <div class="cert-cartouche-pill">
                <span class="cert-cartouche-text" id="cert_cardTitle">{{ $data['cardTitle'] }}</span>
              </div>
              <img src="{{ asset('admitcard-studio/assets/cartouche-filigree-right.png') }}" class="cartouche-bracket right" alt="Flourish Bracket">
            </div>

            <!-- ── Main Information Body: Left Table + Right Photo ── -->
            <div class="cert-body-layout">
              <div class="cert-details-table">
                
                <div class="detail-row">
                  <span class="detail-label">Student Name</span>
                  <span class="detail-colon">:</span>
                  <span class="detail-value" id="cert_studentName">{{ $data['studentName'] }}</span>
                </div>

                <div class="detail-row">
                  <span class="detail-label">Father's Name</span>
                  <span class="detail-colon">:</span>
                  <span class="detail-value" id="cert_parentName">{{ $data['parentName'] }}</span>
                </div>

                <div class="detail-row">
                  <span class="detail-label">Batch</span>
                  <span class="detail-colon">:</span>
                  <span class="detail-value" id="cert_batch">{{ $data['batch'] }}</span>
                </div>

                <div class="detail-row">
                  <span class="detail-label">Course</span>
                  <span class="detail-colon">:</span>
                  <span class="detail-value" id="cert_courseName">{{ $data['courseName'] }}</span>
                </div>

                <div class="detail-row">
                  <span class="detail-label">Year</span>
                  <span class="detail-colon">:</span>
                  <span class="detail-value" id="cert_passYear">{{ $data['passYear'] }}</span>
                </div>

                <div class="detail-row">
                  <span class="detail-label">Exam Centre</span>
                  <span class="detail-colon">:</span>
                  <span class="detail-value" id="cert_examCentre">{{ $data['examCentre'] }}</span>
                </div>

              </div>

              <!-- Candidate Passport Photo Mount -->
              <div class="cert-photo-column">
                <div class="cert-photo-frame">
                  <img id="cert_photoImg" src="{{ $data['photoSrc'] }}" class="cert-photo-img" alt="Candidate Photograph">
                </div>
              </div>
            </div>

            <!-- ── Signatures & Stamp Row (3 Columns) ── -->
            <div class="cert-signatures-row">
              
              <!-- Left: Candidate Signature -->
              <div class="sig-col col-left">
                <div class="sig-dots-line"></div>
                <div class="sig-title" id="cert_candidateSigTitle">{{ $data['candidateSigTitle'] }}</div>
              </div>

              <!-- Center: Centre Coordinator -->
              <div class="sig-col col-center">
                <div class="sig-dots-line"></div>
                <div class="sig-title" id="cert_coordinatorTitle">{{ $data['coordinatorTitle'] }}</div>
              </div>

              <!-- Right: Examination Controller with Official Seal -->
              <div class="sig-col col-right">
                <div class="controller-stamp-wrap" id="cert_stampWrap" style="{{ !empty($data['showStamp']) ? '' : 'display:none;' }}">
                  <img id="cert_stampImg" src="{{ $data['stampSrc'] }}" class="controller-stamp-img" alt="Controller Seal and Signature">
                </div>
                <div class="sig-dots-line"></div>
                <div class="sig-title" id="cert_controllerTitle">{{ $data['controllerTitle'] }}</div>
              </div>

            </div>

            <!-- ── Bottom Disclaimer ── -->
            <div class="cert-disclaimer" id="cert_disclaimer">
              {{ $data['disclaimer'] }}
            </div>

          </div>
        </div>
      </div>

    </section>

  </main>

  <script>
    window.admitcardAssetBase = "{{ asset('admitcard-studio/assets') }}/";
    window.admitcardPrintUrl = "{{ route('admitcard.print') }}";
    window.admitcardSaveUrl = "{{ route('admitcard.save') }}";

    const triggerSave = () => {
      const form = document.getElementById('admitcardForm');
      if (form) {
        form.action = window.admitcardSaveUrl;
        form.target = '_self';
        form.method = 'POST';
        form.submit();
      }
    };
    document.getElementById('btnSaveRecord')?.addEventListener('click', triggerSave);
    document.getElementById('btnSaveRecordBottom')?.addEventListener('click', triggerSave);
  </script>
  <script src="{{ asset('admitcard-studio/script.js') }}?v={{ time() }}"></script>
</body>
</html>
