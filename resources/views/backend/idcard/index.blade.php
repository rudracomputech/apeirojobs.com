<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HS Institute - ID Card Generator &amp; Management</title>
  <meta name="description" content="Generate high quality paramedical and nursing student identity cards with live preview, custom inputs, logo, and instant export.">
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Montserrat:wght@500;600;700;800;900&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- FontAwesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <!-- Stylesheets -->
  <link rel="stylesheet" href="{{ asset('idcard-studio/assets/css/style.css') }}?v={{ time() }}">
  <link rel="stylesheet" href="{{ asset('idcard-studio/assets/css/id_card.css') }}?v={{ time() }}">

  <style>
    /* Topbar Enhancements */
    .app-header {
      background: #0f172a;
      border-bottom: 1px solid #1e293b;
      padding: 10px 24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 12px;
    }

    .brand-section {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .btn-dashboard-nav {
      background: transparent;
      color: #94a3b8;
      border: 1px solid #334155;
      padding: 6px 14px;
      border-radius: 6px;
      text-decoration: none;
      font-size: 13px;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s;
    }

    .btn-dashboard-nav:hover {
      background: #1e293b;
      color: #f8fafc;
      border-color: #475569;
    }

    .header-quick-select {
      display: flex;
      align-items: center;
      gap: 8px;
      background: rgba(255, 255, 255, 0.07);
      padding: 5px 12px;
      border-radius: 6px;
      border: 1px solid rgba(255, 255, 255, 0.12);
    }

    .header-quick-select label {
      font-size: 12px;
      color: #94a3b8;
      font-weight: 500;
      white-space: nowrap;
    }

    .header-quick-select select {
      background: #1e293b;
      color: #f8fafc;
      border: 1px solid #475569;
      border-radius: 4px;
      padding: 4px 8px;
      font-size: 12px;
      outline: none;
      min-width: 200px;
      cursor: pointer;
    }

    .toast-session {
      background: #10b981;
      color: #ffffff;
      padding: 10px 16px;
      border-radius: 6px;
      margin-bottom: 14px;
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 13px;
      font-weight: 600;
    }
  </style>
</head>
<body>

  <!-- Top App Navigation -->
  <header class="app-header no-print">
    <div class="brand-section">
      <a href="{{ route('dashboard') }}" class="btn-dashboard-nav">
        <i class="fa-solid fa-arrow-left"></i> Dashboard
      </a>
      <div class="header-brand">
        <img src="{{ asset('idcard-studio/assets/images/logo.png') }}" alt="HS Institute Logo" class="brand-logo-img">
        <div>
          <h1 class="brand-title">HS Institute ID Studio</h1>
          <p class="brand-subtitle">Healthcare &amp; Paramedical Training Identity Card Generator</p>
        </div>
      </div>
    </div>

    <div class="header-actions">
      <a href="{{ route('idcard.records') }}" class="btn btn-outline btn-sm" style="background: #2563eb; color: #ffffff; border-color: #2563eb;">
        <i class="fa-solid fa-table-list"></i> All ID Cards
      </a>

      @if(isset($students) && $students->count() > 0)
        <div class="header-quick-select">
          <label for="studentQuickSelect"><i class="fa-solid fa-bolt text-warning"></i> Quick Fill Student:</label>
          <select id="studentQuickSelect">
            <option value="">-- Select Registered Student --</option>
            @foreach($students as $st)
              <option value="{{ $st->id }}">
                {{ $st->name }} ({{ $st->student_code ?? 'ID: ' . $st->id }})
              </option>
            @endforeach
          </select>
        </div>
      @endif

      <button type="button" id="loadSampleBtn" class="btn btn-outline btn-sm">
        <i class="fa-solid fa-wand-magic-sparkles"></i> Load Sample
      </button>
      <button type="button" id="printCardBtn" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-print"></i> Print ID Card
      </button>
    </div>
  </header>

  <!-- Main Application Container -->
  <main class="app-container">

    <!-- Left Control Panel: Form Inputs -->
    <section class="panel no-print">
      <div class="panel-header">
        <h2 class="panel-title">
          <i class="fa-solid fa-sliders text-primary"></i> ID Card Details &amp; Customization
        </h2>
        <button type="button" id="resetFormBtn" class="btn btn-outline btn-sm" title="Clear all fields">
          <i class="fa-solid fa-rotate-left"></i> Reset
        </button>
      </div>

      <div class="panel-body">
        @if(session('success'))
          <div class="toast-session">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
          </div>
        @endif

        <form id="idCardForm" action="{{ route('idcard.save') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="record_id" value="{{ $default['id'] ?? '' }}">
          <input type="hidden" name="card_uid" value="{{ $default['card_uid'] ?? '' }}">
          <input type="hidden" name="student_id" id="inp_student_id" value="{{ $default['student_id'] ?? '' }}">
          <input type="hidden" name="existing_photo" value="{{ $default['photo'] }}">
          <input type="hidden" id="photo_data" name="photo_data" value="">

          <!-- 1. Organization & Header Section -->
          <div class="form-section">
            <div class="section-legend">
              <i class="fa-solid fa-building-columns"></i> 1. Organization &amp; Header
            </div>
            
            <div class="form-group">
              <label class="form-label" for="org_name">Council / Institute Title</label>
              <input type="text" id="org_name" name="org_name" class="form-input" 
                     value="{{ $default['org_name'] }}" 
                     placeholder="e.g. ALL INDIA COUNCIL FOR VOCATIONAL & PARAMEDICAL SCIENCE">
            </div>

            <div class="form-grid-2">
              <div class="form-group">
                <label class="form-label" for="org_subtitle">Sub-title / Tagline (Optional)</label>
                <input type="text" id="org_subtitle" name="org_subtitle" class="form-input" 
                       value="{{ $default['org_subtitle'] }}" 
                       placeholder="e.g. HEALTHCARE & PARAMEDICAL TRAINING">
              </div>
              <div class="form-group">
                <label class="form-label" for="card_title">Badge / Card Title</label>
                <input type="text" id="card_title" name="card_title" class="form-input" 
                       value="{{ $default['card_title'] }}" 
                       placeholder="IDENTITY CARD">
              </div>
            </div>

            <!-- Custom Logo Upload -->
            <div class="form-group">
              <label class="form-label">Institute Logo (Left Header Badge)</label>
              <div class="file-upload-wrapper">
                <img src="{{ $default['logo'] }}" id="thumb_logo_preview" class="file-thumb-preview" alt="Logo preview">
                <div class="file-input-custom">
                  <input type="file" id="logo_upload" name="logo_file" accept="image/*">
                  <div class="file-custom-btn">
                    <i class="fa-solid fa-upload"></i> Change Custom Logo
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- 2. Student Identification Section -->
          <div class="form-section">
            <div class="section-legend">
              <i class="fa-solid fa-user-graduate"></i> 2. Student Information
            </div>

            <div class="form-grid-2">
              <div class="form-group">
                <label class="form-label" for="roll_no">Roll No *</label>
                <input type="text" id="roll_no" name="roll_no" class="form-input" 
                       value="{{ $default['roll_no'] }}" 
                       placeholder="e.g. 20235801" required>
              </div>
              <div class="form-group">
                <label class="form-label" for="enrollment_no">Enrollment No *</label>
                <input type="text" id="enrollment_no" name="enrollment_no" class="form-input" 
                       value="{{ $default['enrollment_no'] }}" 
                       placeholder="e.g. ACI2023233750" required>
              </div>
            </div>

            <div class="form-group">
              <label class="form-label" for="student_name">Student Full Name *</label>
              <input type="text" id="student_name" name="student_name" class="form-input" 
                     value="{{ $default['student_name'] }}" 
                     placeholder="e.g. Laxman Patole" required>
            </div>

            <!-- Course Selection with Presets -->
            <div class="form-group">
              <label class="form-label" for="course">Course / Specialization *</label>
              <select id="course_preset" class="form-select" style="margin-bottom: 6px;">
                <option value="">-- Quick Course Preset --</option>
                <option value="Diploma in General Nursing And Midwifery (GNM)" {{ $default['course'] === 'Diploma in General Nursing And Midwifery (GNM)' ? 'selected' : '' }}>Diploma in General Nursing And Midwifery (GNM)</option>
                <option value="Auxiliary Nursing & Midwifery (ANM)">Auxiliary Nursing & Midwifery (ANM)</option>
                <option value="Diploma in Medical Laboratory Technology (DMLT)">Diploma in Medical Laboratory Technology (DMLT)</option>
                <option value="Bachelor of Science in Nursing (B.Sc Nursing)">Bachelor of Science in Nursing (B.Sc Nursing)</option>
                <option value="Certificate in Community Health (CCH)">Certificate in Community Health (CCH)</option>
                <option value="Diploma in Operation Theatre Technology (DOTT)">Diploma in Operation Theatre Technology (DOTT)</option>
                <option value="Diploma in Radiology & Imaging Technology (DRIT)">Diploma in Radiology & Imaging Technology (DRIT)</option>
                <option value="Diploma in Emergency Medical Services (DEMS)">Diploma in Emergency Medical Services (DEMS)</option>
                <option value="custom">-- Custom Course Title --</option>
              </select>
              <input type="text" id="course" name="course" class="form-input" 
                     value="{{ $default['course'] }}" 
                     placeholder="Enter course name">
            </div>

            <div class="form-group">
              <label class="form-label" for="session">Session / Academic Duration *</label>
              <input type="text" id="session" name="session" class="form-input" 
                     value="{{ $default['session'] }}" 
                     placeholder="e.g. Jun 2023 - Jun 2025">
            </div>

            <div class="form-group">
              <label class="form-label" for="center_name">Training Center / Institute</label>
              <textarea id="center_name" name="center_name" class="form-textarea" rows="2" 
                        placeholder="e.g. HS Institute Powered by NBS Welfare Foundation.org">{{ $default['center_name'] }}</textarea>
            </div>
          </div>

          <!-- 3. Photo & Authorised Signatory -->
          <div class="form-section">
            <div class="section-legend">
              <i class="fa-solid fa-camera"></i> 3. Student Photo &amp; Signatory
            </div>

            <!-- Student Photo Upload -->
            <div class="form-group">
              <label class="form-label">Student Passport Photo</label>
              <div class="file-upload-wrapper">
                <img src="{{ $default['photo'] }}" id="thumb_photo_preview" class="file-thumb-preview" alt="Student preview">
                <div class="file-input-custom">
                  <input type="file" id="photo_upload" name="photo_file" accept="image/*">
                  <div class="file-custom-btn">
                    <i class="fa-solid fa-camera"></i> Choose Student Photo
                  </div>
                </div>
              </div>
            </div>

            <!-- Stamp & Signature -->
            <div class="form-group">
              <label class="form-label">Authorised Signatory Title</label>
              <input type="text" id="signatory_title" name="signatory_title" class="form-input" 
                     value="{{ $default['signatory_title'] }}" 
                     placeholder="Authorised Signatory">
            </div>

            <div class="form-group">
              <label class="form-label">Official Stamp &amp; Signature</label>
              <div class="file-upload-wrapper">
                <img src="{{ $default['stamp'] }}" id="thumb_stamp_preview" class="file-thumb-preview" alt="Stamp preview">
                <div class="file-input-custom">
                  <input type="file" id="stamp_upload" name="stamp_file" accept="image/*">
                  <div class="file-custom-btn">
                    <i class="fa-solid fa-signature"></i> Upload Custom Seal / Stamp
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- 4. Back Side Details (Optional / Extra) -->
          <div class="form-section">
            <div class="section-legend">
              <i class="fa-solid fa-id-card"></i> 4. Back Side / Security Details
            </div>

            <div class="form-grid-2">
              <div class="form-group">
                <label class="form-label" for="blood_group">Blood Group</label>
                <input type="text" id="blood_group" name="blood_group" class="form-input" 
                       value="{{ $default['blood_group'] }}" 
                       placeholder="e.g. B+, O+, A+">
              </div>
              <div class="form-group">
                <label class="form-label" for="emergency_contact">Emergency Contact</label>
                <input type="text" id="emergency_contact" name="emergency_contact" class="form-input" 
                       value="{{ $default['emergency_contact'] }}" 
                       placeholder="e.g. +91 9876543210">
              </div>
            </div>

            <div class="form-grid-2">
              <div class="form-group">
                <label class="form-label" for="valid_upto">Valid Upto</label>
                <input type="text" id="valid_upto" name="valid_upto" class="form-input" 
                       value="{{ $default['valid_upto'] }}" 
                       placeholder="e.g. June 2025">
              </div>
              <div class="form-group">
                <label class="form-label" for="watermark_text">Bottom Badge Text</label>
                <input type="text" id="watermark_text" name="watermark_text" class="form-input" 
                       value="{{ $default['watermark_text'] }}" 
                       placeholder="Badge / Website">
              </div>
            </div>

            <div class="form-group">
              <label class="form-label" for="student_address">Student Permanent Address</label>
              <textarea id="student_address" name="student_address" class="form-textarea" rows="2" 
                        placeholder="Full student address">{{ $default['student_address'] }}</textarea>
            </div>
          </div>

          <!-- Action Buttons -->
          <div style="display: flex; gap: 10px; margin-top: 10px;">
            <button type="submit" class="btn btn-success" style="flex: 1;">
              <i class="fa-solid fa-floppy-disk"></i> Save Card Record
            </button>
          </div>
        </form>
      </div>
    </section>

    <!-- Right Control Panel: Live Preview & Export Toolbar -->
    <section class="preview-container">
      
      <!-- Toolbar -->
      <div class="preview-toolbar no-print">
        <div class="preview-toolbar-group">
          <button type="button" id="viewFrontBtn" class="tab-btn active">
            <i class="fa-solid fa-address-card"></i> Front Side
          </button>
          <button type="button" id="viewBackBtn" class="tab-btn">
            <i class="fa-solid fa-repeat"></i> Back Side
          </button>
          <button type="button" id="viewBothBtn" class="tab-btn">
            <i class="fa-solid fa-table-columns"></i> Both Sides
          </button>
        </div>

        <div class="preview-toolbar-group">
          <button type="button" id="downloadPngBtn" class="btn btn-primary btn-sm" title="Download crystal clear 300DPI PNG">
            <i class="fa-solid fa-file-image"></i> PNG (HD)
          </button>
          <button type="button" id="downloadPdfBtn" class="btn btn-outline btn-sm" title="Download standard CR80 printable PDF">
            <i class="fa-solid fa-file-pdf text-danger"></i> PDF
          </button>
          <a href="{{ route('idcard.print') }}" target="_blank" class="btn btn-outline btn-sm" title="Open printable sheet in new window">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> Full Print View
          </a>
        </div>
      </div>

      <!-- Live Preview Canvas Stage -->
      <div class="preview-stage">
        
        <div id="cardContainer" class="printable-area" style="display: flex; flex-direction: column; gap: 24px; align-items: center;">
          
          <!-- ==================== FRONT SIDE OF ID CARD ==================== -->
          <div id="idCardFront" class="id-card-wrapper">
            <div class="id-card">
              
              <!-- Top Blue Header Bar with Logo -->
              <div class="id-card-header">
                <div class="id-header-logo-container">
                  <img id="preview_org_logo" src="{{ $default['logo'] }}" alt="Institute Emblem" class="id-header-logo">
                </div>
                <div class="id-header-title-container">
                  <div id="preview_org_name" class="id-header-org-name">
                    {{ $default['org_name'] }}
                  </div>
                  <div id="preview_org_subtitle" class="id-header-subtitle">
                    {{ $default['org_subtitle'] }}
                  </div>
                </div>
              </div>

              <!-- Main Card Body -->
              <div class="id-card-body">
                
                <!-- Background Decorative Wave Accents -->
                <div class="id-bg-accent-left"></div>
                <div class="id-bg-accent-ribbon" id="preview_watermark_text">
                  {{ $default['watermark_text'] }}
                </div>

                <!-- Sub-meta Line: Roll No & Enrollment No -->
                <div class="id-sub-meta">
                  <div class="id-meta-item">
                    <span class="id-meta-label">Roll No :</span>
                    <span id="preview_roll_no" class="id-meta-val">{{ $default['roll_no'] }}</span>
                  </div>
                  <div class="id-meta-item">
                    <span class="id-meta-label">Enrollment No :</span>
                    <span id="preview_enrollment_no" class="id-meta-val">{{ $default['enrollment_no'] }}</span>
                  </div>
                </div>

                <!-- Main Card Title -->
                <div class="id-title-row">
                  <div class="id-title-bar"></div>
                  <div id="preview_card_title" class="id-card-title">{{ $default['card_title'] }}</div>
                </div>

                <!-- Content Grid: Left Details & Right Photo/Signatory -->
                <div class="id-content-grid">
                  
                  <!-- Left Details -->
                  <div class="id-details-list">
                    <div class="id-field-row">
                      <span class="id-field-label">Student Name</span>
                      <span class="id-field-colon">:</span>
                      <span id="preview_student_name" class="id-field-value name-value">
                        {{ $default['student_name'] }}
                      </span>
                    </div>

                    <div class="id-field-row">
                      <span class="id-field-label">Course</span>
                      <span class="id-field-colon">:</span>
                      <span id="preview_course" class="id-field-value course-value">
                        {{ $default['course'] }}
                      </span>
                    </div>

                    <div class="id-field-row">
                      <span class="id-field-label">Session</span>
                      <span class="id-field-colon">:</span>
                      <span id="preview_session" class="id-field-value">
                        {{ $default['session'] }}
                      </span>
                    </div>

                    <div class="id-field-row">
                      <span class="id-field-label">Center</span>
                      <span class="id-field-colon">:</span>
                      <span id="preview_center_name" class="id-field-value center-value">
                        {{ $default['center_name'] }}
                      </span>
                    </div>
                  </div>

                  <!-- Right Photo & Signatory Box -->
                  <div class="id-right-column">
                    <div class="id-photo-frame">
                      <img id="preview_student_photo" src="{{ $default['photo'] }}" alt="Student Photo" class="id-student-photo">
                    </div>

                    <div class="id-signatory-box">
                      <div class="id-stamp-signature-wrap">
                        <img id="preview_stamp_signature" src="{{ $default['stamp'] }}" alt="Stamp & Signature" class="id-stamp-img">
                      </div>
                      <div id="preview_signatory_title" class="id-signatory-label">
                        {{ $default['signatory_title'] }}
                      </div>
                    </div>
                  </div>

                </div>
              </div>

            </div>
          </div>

          <!-- ==================== BACK SIDE OF ID CARD ==================== -->
          <div id="idCardBack" class="id-card-wrapper" style="display: none;">
            <div class="id-card id-card-back">
              <div class="id-card-back-header" id="preview_back_org_name">
                {{ $default['org_name'] }}
              </div>

              <div class="id-card-back-body">
                <div class="id-back-instructions">
                  <div class="id-back-title">Terms &amp; Instructions</div>
                  <ol>
                    <li>This card is non-transferable and property of the institution.</li>
                    <li>Loss of this card must be reported immediately to administration.</li>
                    <li>Must be presented during clinical training, practicals &amp; examinations.</li>
                  </ol>

                  <div class="id-back-details">
                    <div><strong>Blood Group:</strong> <span id="preview_blood_group">{{ $default['blood_group'] }}</span></div>
                    <div><strong>Emergency Tel:</strong> <span id="preview_emergency_contact">{{ $default['emergency_contact'] }}</span></div>
                    <div><strong>Valid Upto:</strong> <span id="preview_valid_upto">{{ $default['valid_upto'] }}</span></div>
                    <div style="font-size: 8px;"><strong>Address:</strong> <span id="preview_student_address">{{ $default['student_address'] }}</span></div>
                  </div>
                </div>

                <div class="id-back-right">
                  <div id="qrcode_container" class="id-qr-box"></div>
                  <div style="font-size: 7.5px; font-weight: 700; color: #475569; text-align: center;">Scan to Verify</div>
                </div>
              </div>

              <div class="id-back-footer">
                If found, please return to HS Institute / Nearest Police Station
              </div>
            </div>
          </div>

        </div>

        <div class="card-meta-badge no-print">
          <i class="fa-solid fa-ruler-combined"></i> Standard CR-80 (85.6mm &times; 54mm) &bull; 300 DPI Ready
        </div>
      </div>

      <!-- Recent Saved Cards Section -->
      @if (!empty($savedCards))
      <div class="recent-cards-section no-print">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
          <h3 style="font-size: 14px; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-clock-rotate-left text-primary"></i> Saved ID Cards History ({{ count($savedCards) }})
          </h3>
          <a href="{{ route('idcard.records') }}" style="font-size: 12px; font-weight: 600; color: #2563eb; text-decoration: none;">
            View All Records &rarr;
          </a>
        </div>
        <div style="overflow-x: auto;">
          <table class="cards-table">
            <thead>
              <tr>
                <th>Photo</th>
                <th>Student Name</th>
                <th>Roll No</th>
                <th>Enrollment No</th>
                <th>Course</th>
                <th>Session</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach (array_slice($savedCards, 0, 8) as $card)
              <tr>
                <td>
                  <img src="{{ $card['photo'] ?? asset('idcard-studio/assets/images/default_avatar.svg') }}" style="width: 28px; height: 32px; object-fit: cover; border-radius: 3px;" alt="">
                </td>
                <td style="font-weight: 700;">{{ $card['student_name'] }}</td>
                <td>{{ $card['roll_no'] }}</td>
                <td>{{ $card['enrollment_no'] }}</td>
                <td>{{ $card['course'] }}</td>
                <td>{{ $card['session'] }}</td>
                <td style="display: flex; gap: 6px;">
                  <a href="{{ route('idcard.index', ['id' => $card['id']]) }}" class="btn btn-outline btn-sm" style="padding: 3px 8px; font-size: 11px;">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                  </a>
                  <a href="{{ route('idcard.print', ['id' => $card['id']]) }}" target="_blank" class="btn btn-primary btn-sm" style="padding: 3px 8px; font-size: 11px;">
                    <i class="fa-solid fa-print"></i> Print
                  </a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      @endif

    </section>

  </main>

  <!-- External JavaScript Libraries -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  
  <!-- Application JS -->
  <script>
    window.idcardPrintUrl = "{{ route('idcard.print') }}";
  </script>
  <script src="{{ asset('idcard-studio/assets/js/app.js') }}?v={{ time() }}"></script>
</body>
</html>
