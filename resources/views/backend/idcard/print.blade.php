<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Print ID Card - {{ $card['student_name'] }}</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Montserrat:wght@600;700;800;900&family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <link rel="stylesheet" href="{{ asset('idcard-studio/assets/css/id_card.css') }}?v={{ time() }}">
  <style>
    body {
      background: #f1f5f9;
      margin: 0;
      padding: 30px;
      font-family: 'Inter', sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      min-height: 100vh;
    }

    .print-bar {
      background: #0f172a;
      color: #ffffff;
      padding: 12px 24px;
      border-radius: 8px;
      margin-bottom: 25px;
      display: flex;
      align-items: center;
      gap: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .print-btn {
      background: #2563eb;
      color: white;
      border: none;
      padding: 8px 18px;
      border-radius: 6px;
      font-weight: 600;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 13px;
    }

    .print-btn:hover {
      background: #1d4ed8;
    }

    .back-btn {
      background: transparent;
      color: #cbd5e1;
      border: 1px solid rgba(255,255,255,0.25);
      padding: 7px 16px;
      border-radius: 6px;
      font-size: 13px;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s;
    }

    .back-btn:hover {
      background: rgba(255,255,255,0.1);
      color: #ffffff;
    }

    .print-sheet {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      justify-content: center;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      max-width: 900px;
    }

    .card-print-block {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
    }

    .card-side-label {
      font-size: 12px;
      font-weight: 700;
      color: #64748b;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    @media print {
      body {
        background: transparent !important;
        padding: 0 !important;
      }

      .print-bar {
        display: none !important;
      }

      .print-sheet {
        padding: 0 !important;
        box-shadow: none !important;
        background: transparent !important;
        gap: 15mm !important;
      }

      .card-side-label {
        display: none !important;
      }
    }
  </style>
</head>
<body>

  <!-- Floating Print Bar (Hidden in Print) -->
  <div class="print-bar no-print">
    <button class="print-btn" onclick="window.print()">
      <i class="fa-solid fa-print"></i> Print ID Card (CR-80)
    </button>
    <a href="{{ route('idcard.index') }}" class="back-btn">
      <i class="fa-solid fa-arrow-left"></i> Back to Studio
    </a>
  </div>

  <!-- Sheet Container for Double-Sided Print -->
  <div class="print-sheet">
    
    <!-- FRONT SIDE -->
    <div class="card-print-block">
      <span class="card-side-label">Front Side (CR-80)</span>
      <div class="id-card-wrapper">
        <div class="id-card">
          
          <!-- Top Blue Header Bar with Logo -->
          <div class="id-card-header">
            <div class="id-header-logo-container">
              <img src="{{ $card['logo'] ?? asset('idcard-studio/assets/images/logo.png') }}" alt="Institute Emblem" class="id-header-logo">
            </div>
            <div class="id-header-title-container">
              <div class="id-header-org-name">{{ $card['org_name'] }}</div>
              <div class="id-header-subtitle">{{ $card['org_subtitle'] }}</div>
            </div>
          </div>

          <!-- Main Card Body -->
          <div class="id-card-body">
            
            <!-- Background Decorative Wave Accents -->
            <div class="id-bg-accent-left"></div>
            <div class="id-bg-accent-ribbon">{{ $card['watermark_text'] }}</div>

            <!-- Sub-meta Line: Roll No & Enrollment No -->
            <div class="id-sub-meta">
              <div class="id-meta-item">
                <span class="id-meta-label">Roll No :</span>
                <span class="id-meta-val">{{ $card['roll_no'] }}</span>
              </div>
              <div class="id-meta-item">
                <span class="id-meta-label">Enrollment No :</span>
                <span class="id-meta-val">{{ $card['enrollment_no'] }}</span>
              </div>
            </div>

            <!-- Main Card Title -->
            <div class="id-title-row">
              <div class="id-title-bar"></div>
              <div class="id-card-title">{{ $card['card_title'] }}</div>
            </div>

            <!-- Content Grid -->
            <div class="id-content-grid">
              
              <!-- Left Details -->
              <div class="id-details-list">
                <div class="id-field-row">
                  <span class="id-field-label">Student Name</span>
                  <span class="id-field-colon">:</span>
                  <span class="id-field-value name-value">{{ $card['student_name'] }}</span>
                </div>

                <div class="id-field-row">
                  <span class="id-field-label">Course</span>
                  <span class="id-field-colon">:</span>
                  <span class="id-field-value course-value">{{ $card['course'] }}</span>
                </div>

                <div class="id-field-row">
                  <span class="id-field-label">Session</span>
                  <span class="id-field-colon">:</span>
                  <span class="id-field-value">{{ $card['session'] }}</span>
                </div>

                <div class="id-field-row">
                  <span class="id-field-label">Center</span>
                  <span class="id-field-colon">:</span>
                  <span class="id-field-value center-value">{{ $card['center_name'] }}</span>
                </div>
              </div>

              <!-- Right Photo & Signatory Box -->
              <div class="id-right-column">
                <div class="id-photo-frame">
                  <img src="{{ $card['photo'] ?? asset('idcard-studio/assets/images/default_avatar.svg') }}" alt="Student Photo" class="id-student-photo">
                </div>

                <div class="id-signatory-box">
                  <div class="id-stamp-signature-wrap">
                    <img src="{{ $card['stamp'] ?? asset('idcard-studio/assets/images/stamp_signature.svg') }}" alt="Stamp & Signature" class="id-stamp-img">
                  </div>
                  <div class="id-signatory-label">{{ $card['signatory_title'] }}</div>
                </div>
              </div>

            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- BACK SIDE -->
    <div class="card-print-block">
      <span class="card-side-label">Back Side (CR-80)</span>
      <div class="id-card-wrapper">
        <div class="id-card id-card-back">
          <div class="id-card-back-header">
            {{ $card['org_name'] }}
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
                <div><strong>Blood Group:</strong> <span>{{ $card['blood_group'] }}</span></div>
                <div><strong>Emergency Tel:</strong> <span>{{ $card['emergency_contact'] }}</span></div>
                <div><strong>Valid Upto:</strong> <span>{{ $card['valid_upto'] }}</span></div>
                <div style="font-size: 8px;"><strong>Address:</strong> <span>{{ $card['student_address'] }}</span></div>
              </div>
            </div>

            <div class="id-back-right">
              <div id="qrcode_print_container" class="id-qr-box"></div>
              <div style="font-size: 7.5px; font-weight: 700; color: #475569; text-align: center;">Scan to Verify</div>
            </div>
          </div>

          <div class="id-back-footer">
            If found, please return to HS Institute / Nearest Police Station
          </div>
        </div>
      </div>
    </div>

  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const qrContainer = document.getElementById('qrcode_print_container');
      if (qrContainer && typeof QRCode !== 'undefined') {
        const qrData = JSON.stringify({
          roll: @json($card['roll_no']),
          enroll: @json($card['enrollment_no']),
          name: @json($card['student_name']),
          course: @json($card['course']),
          center: @json($card['center_name'])
        });

        new QRCode(qrContainer, {
          text: qrData,
          width: 64,
          height: 64,
          colorDark: '#0f172a',
          colorLight: '#ffffff',
          correctLevel: QRCode.CorrectLevel.M
        });
      }
    });
  </script>
</body>
</html>
