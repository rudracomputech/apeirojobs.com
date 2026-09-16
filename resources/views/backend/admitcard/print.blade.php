<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admit Card - {{ $data['studentName'] }} ({{ $data['rollNo'] }})</title>
  <link rel="stylesheet" href="{{ asset('admitcard-studio/style.css') }}?v={{ time() }}">
  <style>
    body {
      background: #1e293b;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 40px 10px;
    }

    .print-floating-bar {
      position: fixed;
      top: 18px;
      left: 50%;
      transform: translateX(-50%);
      background: rgba(15, 23, 42, 0.94);
      backdrop-filter: blur(10px);
      padding: 9px 22px;
      border-radius: 30px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
      display: flex;
      gap: 12px;
      z-index: 1000;
      border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .admitcard-sheet {
      margin-top: 48px;
    }

    @media print {
      body {
        background: #ffffff !important;
        padding: 0 !important;
        min-height: auto !important;
        display: block !important;
      }
      .print-floating-bar {
        display: none !important;
      }
      .admitcard-sheet {
        box-shadow: none !important;
        margin-top: 0 !important;
      }
    }
  </style>
</head>
<body>

  <!-- Floating Navigation Bar (Hidden during printing) -->
  <div class="print-floating-bar no-print">
    <a href="{{ route('admitcard.index') }}" class="btn btn-secondary">
      <span>⬅️</span> Back to Editor
    </a>
    <button type="button" class="btn btn-primary" onclick="window.print()">
      <span>🖨️</span> Print / Save PDF
    </button>
  </div>

  <!-- Authentic Admit Card Sheet -->
  <div class="admitcard-sheet">
    
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
    <img src="{{ $data['logoSrc'] }}" class="cert-watermark-crest" alt="Watermark Crest">

    <!-- Inner Certificate Content Structure -->
    <div class="cert-content">
      
      <!-- Top Bar: Roll No, Center Emblem, Enrollment No -->
      <div class="cert-top-bar">
        <div class="cert-num-badge">
          Roll No.: <span>{{ $data['rollNo'] }}</span>
        </div>
        <div class="cert-top-emblem-wrap">
          <img src="{{ $data['logoSrc'] }}" class="cert-top-emblem" alt="AICVPS Emblem">
        </div>
        <div class="cert-num-badge">
          Enrollment No.: <span>{{ $data['enrollmentNo'] }}</span>
        </div>
      </div>

      <!-- Header Block: Council Title & 4 Bullets -->
      <div class="cert-header-block">
        <h2 class="cert-council-title">
          {{ $data['councilName'] }}
        </h2>
        <div class="cert-bullets-grid">
          <div class="bullet-item">
            <span class="bullet-dot">•</span>
            <span>{{ $data['bullet1'] }}</span>
          </div>
          <div class="bullet-item">
            <span class="bullet-dot">•</span>
            <span>{{ $data['bullet3'] }}</span>
          </div>
          <div class="bullet-item">
            <span class="bullet-dot">•</span>
            <span>{{ $data['bullet2'] }}</span>
          </div>
          <div class="bullet-item">
            <span class="bullet-dot">•</span>
            <span>{{ $data['bullet4'] }}</span>
          </div>
        </div>
      </div>

      <!-- Center Cartouche: "Admit Card" (Authentic Gothic Design) -->
      <div class="cert-cartouche-container">
        <img src="{{ asset('admitcard-studio/assets/cartouche-filigree-left.png') }}" class="cartouche-bracket left" alt="Flourish Bracket">
        <div class="cert-cartouche-pill">
          <span class="cert-cartouche-text">{{ $data['cardTitle'] }}</span>
        </div>
        <img src="{{ asset('admitcard-studio/assets/cartouche-filigree-right.png') }}" class="cartouche-bracket right" alt="Flourish Bracket">
      </div>

      <!-- Main Information Body: Left Table + Right Photo -->
      <div class="cert-body-layout">
        <div class="cert-details-table">
          
          <div class="detail-row">
            <span class="detail-label">Student Name</span>
            <span class="detail-colon">:</span>
            <span class="detail-value">{{ $data['studentName'] }}</span>
          </div>

          <div class="detail-row">
            <span class="detail-label">Father's Name</span>
            <span class="detail-colon">:</span>
            <span class="detail-value">{{ $data['parentName'] }}</span>
          </div>

          <div class="detail-row">
            <span class="detail-label">Batch</span>
            <span class="detail-colon">:</span>
            <span class="detail-value">{{ $data['batch'] }}</span>
          </div>

          <div class="detail-row">
            <span class="detail-label">Course</span>
            <span class="detail-colon">:</span>
            <span class="detail-value">{{ $data['courseName'] }}</span>
          </div>

          <div class="detail-row">
            <span class="detail-label">Year</span>
            <span class="detail-colon">:</span>
            <span class="detail-value">{{ $data['passYear'] }}</span>
          </div>

          <div class="detail-row">
            <span class="detail-label">Exam Centre</span>
            <span class="detail-colon">:</span>
            <span class="detail-value">{{ $data['examCentre'] }}</span>
          </div>

        </div>

        <!-- Candidate Passport Photo Mount -->
        <div class="cert-photo-column">
          <div class="cert-photo-frame">
            <img src="{{ $data['photoSrc'] }}" class="cert-photo-img" alt="Candidate Photograph">
          </div>
        </div>
      </div>

      <!-- Signatures & Stamp Row (3 Columns) -->
      <div class="cert-signatures-row">
        
        <!-- Left: Candidate Signature -->
        <div class="sig-col col-left">
          <div class="sig-dots-line"></div>
          <div class="sig-title">{{ $data['candidateSigTitle'] }}</div>
        </div>

        <!-- Center: Centre Coordinator -->
        <div class="sig-col col-center">
          <div class="sig-dots-line"></div>
          <div class="sig-title">{{ $data['coordinatorTitle'] }}</div>
        </div>

        <!-- Right: Examination Controller with Official Seal -->
        <div class="sig-col col-right">
          @if(!empty($data['showStamp']) && $data['showStamp'] !== '0')
            <div class="controller-stamp-wrap">
              <img id="cert_stampImg" src="{{ $data['stampSrc'] }}" class="controller-stamp-img" alt="Controller Seal and Signature">
            </div>
          @endif
          <div class="sig-dots-line"></div>
          <div class="sig-title">{{ $data['controllerTitle'] }}</div>
        </div>

      </div>

      <!-- Bottom Disclaimer -->
      <div class="cert-disclaimer">
        {{ $data['disclaimer'] }}
      </div>

    </div>
  </div>

  <script>
    // Auto-trigger print if ?autoprint=1 is in URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('autoprint') === '1') {
      window.addEventListener('load', () => {
        if (document.fonts) {
          document.fonts.ready.then(() => {
            setTimeout(() => window.print(), 350);
          });
        } else {
          setTimeout(() => window.print(), 500);
        }
      });
    }
  </script>
</body>
</html>
