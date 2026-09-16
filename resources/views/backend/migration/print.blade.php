<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Migration Certificate - {{ $data['studentName'] }}</title>
  <link rel="stylesheet" href="{{ asset('migration-studio/style.css') }}?v={{ time() }}">
  <style>
    /* Standalone Print View Specific Tweaks */
    body {
      background: #334155;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 20px;
    }

    .print-floating-bar {
      position: fixed;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      background: rgba(15, 23, 42, 0.9);
      backdrop-filter: blur(8px);
      padding: 10px 20px;
      border-radius: 30px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
      display: flex;
      gap: 12px;
      z-index: 1000;
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    @media print {
      body {
        background: #ffffff !important;
        padding: 0 !important;
      }

      .print-floating-bar {
        display: none !important;
      }

      .certificate-sheet {
        box-shadow: none !important;
      }
    }
  </style>
</head>

<body>

  <!-- Floating Print Control Bar (Hidden on Print) -->
  <div class="print-floating-bar no-print">
    <a href="{{ route('migration.index') }}" class="btn btn-secondary">
      <span>⬅️</span> Back to Editor
    </a>
    <button type="button" class="btn btn-primary" onclick="window.print()">
      <span>🖨️</span> Print / Save as PDF
    </button>
  </div>

  <div class="certificate-wrapper">
    <div class="certificate-sheet">

      <!-- Greek Key Ornamental Border -->
      <img src="{{ asset('migration-studio/assets/greek-border-hd.png') }}" class="cert-greek-border" alt="Decorative Border" />

      <!-- Main Inner Certificate Container -->
      <div class="cert-inner">

        <!-- Dynamic Council Security Watermark Text & Crest Watermarks -->
        <div class="cert-watermark-pattern" aria-hidden="true">
          <svg class="cert-watermark-svg" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
            <defs>
              <pattern id="watermarkTextPattern" width="750" height="24" patternUnits="userSpaceOnUse">
                <text id="cert_watermarkPatternText" x="0" y="16"
                  font-family="'Cinzel', 'Playfair Display', 'Times New Roman', serif" font-size="8" font-weight="700"
                  fill="#a48c66" opacity="0.22" letter-spacing="1.5">{{ $watermarkRepeat }}</text>
              </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#watermarkTextPattern)" />
          </svg>
        </div>
        <img src="{{ asset('migration-studio/assets/council-logo.svg') }}" class="cert-watermark-crest" alt="Watermark Crest" />

        <!-- ═════════ HEADER ═════════ -->
        <div class="cert-header">

          <!-- Serial Number Row -->
          <div class="cert-top-bar">
            <div class="cert-serial-no">
              Serial No.: {{ $data['serialNo'] }}
            </div>
          </div>

          <!-- Council Logo Emblem -->
          <div class="cert-logo-container">
            <img src="{{ $data['logoSrc'] }}" class="cert-logo-img" alt="Council Emblem" />
          </div>

          <!-- Main Council Title -->
          <h1 class="cert-council-title">
            {{ $data['councilName'] }}
          </h1>

          <!-- Credential Bullets (2-Column Grid) -->
          <div class="cert-meta-grid">
            <div class="meta-item">
              <span class="meta-bullet">•</span>
              {{ $data['bullet1'] }}
            </div>
            <div class="meta-item">
              <span class="meta-bullet">•</span>
              {{ $data['bullet3'] }}
            </div>
            <div class="meta-item">
              <span class="meta-bullet">•</span>
              {{ $data['bullet2'] }}
            </div>
            <div class="meta-item">
              <span class="meta-bullet">•</span>
              {{ $data['bullet4'] }}
            </div>
          </div>

        </div>

        <!-- ═════════ ORNAMENTAL BANNER: MIGRATION CERTIFICATE ═════════ -->
        <div class="cert-cartouche-wrapper">
          <div class="cartouche-frame">
            <img src="{{ asset('migration-studio/assets/cartouche-filigree-left.png') }}" class="cartouche-ear ear-left" alt="" />
            <div class="cartouche-pill-outer">
              <div class="cartouche-pill">
                <span class="cartouche-text">{{ $data['certTitle'] }}</span>
              </div>
            </div>
            <img src="{{ asset('migration-studio/assets/cartouche-filigree-right.png') }}" class="cartouche-ear ear-right" alt="" />
          </div>
        </div>

        <!-- ═════════ STATEMENT BODY (5 EXACT LINES) ═════════ -->
        <div class="cert-statement-body">

          <!-- LINE 1 -->
          <div class="statement-row row-1">
            <span class="static-prompt">{{ $data['studentPrefix'] }}</span>
            <div class="fill-slot slot-student">
              <span class="fill-value">{{ $data['studentName'] }}</span>
            </div>
            <span class="static-prompt">{{ $data['sdPrefix'] }}</span>
            <div class="fill-slot slot-parent">
              <span class="fill-value">{{ $data['parentName'] }}</span>
            </div>
          </div>

          <!-- LINE 2 -->
          <div class="statement-row row-2">
            <span class="static-prompt">{{ $data['passedText'] }}</span>
            <div class="fill-slot slot-course">
              <span class="fill-value">{{ $data['courseName'] }}</span>
            </div>
            <span class="static-prompt">{{ $data['fromText'] }}</span>
          </div>

          <!-- LINE 3 -->
          <div class="statement-row row-3">
            <div class="fill-slot slot-inst">
              <span class="fill-value">{{ $data['instName'] }}</span>
            </div>
            <span class="static-prompt">{{ $data['inTheYearText'] }}</span>
            <div class="fill-slot slot-year">
              <span class="fill-value">{{ $data['passYear'] }}</span>
            </div>
            <span class="static-prompt">{{ $data['bearingText'] }}</span>
          </div>

          <!-- LINE 4 -->
          <div class="statement-row row-4">
            <span class="static-prompt">{{ $data['enrollmentLabel'] }}</span>
            <div class="fill-slot slot-enroll">
              <span class="fill-value">{{ $data['enrollmentNo'] }}</span>
            </div>
            <span class="static-prompt prompt-clause-1">{{ $data['clause1'] }}</span>
          </div>

          <!-- LINE 5 -->
          <div class="statement-row row-5">
            <span class="prompt-clause-2">{{ $data['clause2'] }}</span>
          </div>

        </div>

        <!-- ═════════ FOOTER (DELHI, DATED, STAMP, DIRECTOR) ═════════ -->
        <div class="cert-footer">

          <!-- Left: Location & Date -->
          <div class="footer-left">
            <div class="footer-place">{{ $data['placeName'] }}</div>
            <div class="footer-date">
              Dated : <span>{{ $data['certDate'] }}</span>
            </div>
          </div>

          <!-- Right: Stamp, Signature & Title -->
          <div class="footer-right">
            <div class="stamp-signature-box">
              <img src="{{ $data['stampSrc'] }}" class="official-stamp-img" alt="Official Stamp and Signature" />
            </div>
            <div class="footer-signatory-title">
              {{ $data['signatoryTitle'] }}
            </div>
          </div>

        </div>

      </div>
    </div>
  </div>

  <script>
    // Auto trigger print when loaded if ?autoprint=1 is in query
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('autoprint') === '1') {
      window.addEventListener('load', () => {
        if (document.fonts) {
          document.fonts.ready.then(() => {
            setTimeout(() => window.print(), 300);
          });
        } else {
          setTimeout(() => window.print(), 500);
        }
      });
    }
  </script>
</body>

</html>
