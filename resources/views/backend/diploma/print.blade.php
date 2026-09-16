<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Diploma – {{ $data['studentName'] }} ({{ $data['serialNo'] }})</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=UnifrakturCook:wght@700&family=Great+Vibes&family=IM+Fell+English:ital@0;1&family=Inter:wght@400;500;600;700;800&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Cinzel:wght@600;700;800&display=swap"
    rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('diploma-studio/style.css') }}?v={{ time() }}" />

  <style>
    /* Direct print view specific styles */
    body {
      background: #475569;
      margin: 0;
      padding: 24px 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      min-height: 100vh;
    }

    .print-floating-bar {
      position: sticky;
      top: 12px;
      z-index: 999;
      background: rgba(15, 23, 42, 0.95);
      backdrop-filter: blur(8px);
      padding: 10px 24px;
      border-radius: 30px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
      display: flex;
      gap: 12px;
      align-items: center;
      margin-bottom: 20px;
    }

    .print-btn-action {
      background: linear-gradient(135deg, #0e7490, #164e63);
      color: #fff;
      border: none;
      padding: 8px 18px;
      border-radius: 20px;
      font-weight: 700;
      font-size: 0.9rem;
      cursor: pointer;
    }

    .back-btn-action {
      background: transparent;
      color: #94a3b8;
      border: 1px solid rgba(255, 255, 255, 0.2);
      padding: 8px 16px;
      border-radius: 20px;
      text-decoration: none;
      font-size: 0.85rem;
    }

    @media print {
      body {
        background: transparent !important;
        padding: 0 !important;
      }

      .print-floating-bar {
        display: none !important;
      }

      .diploma-certificate {
        box-shadow: none !important;
        margin: 0 !important;
      }
    }
  </style>
</head>

<body>

  <!-- Floating Print Bar (Hidden during Print) -->
  <div class="print-floating-bar no-print">
    <button class="print-btn-action" onclick="window.print()">🖨️ Print / Save as PDF</button>
    <a href="{{ route('diploma.index') }}" class="back-btn-action">← Edit in Form</a>
  </div>

  <!-- THE DIPLOMA CERTIFICATE -->
  <div class="diploma-certificate" id="diplomaCertificate">

    <!-- GREEK KEY FRAME (SVG) -->
    <div class="greek-border-frame" aria-hidden="true">
      <svg class="greek-svg" viewBox="0 0 794 1123" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
        <defs>
          <pattern id="greekKeyPatternPrint" width="24" height="24" patternUnits="userSpaceOnUse">
            <path d="M 0,2 H 22 V 22 H 4 V 6 H 18 V 18 H 8 V 10 H 14 V 14 H 11" fill="none" stroke="#163e72"
              stroke-width="2" stroke-linecap="square" />
          </pattern>
        </defs>
        <rect x="5" y="5" width="784" height="1113" fill="none" stroke="#12305c" stroke-width="5" />
        <rect x="10" y="10" width="774" height="24" fill="url(#greekKeyPatternPrint)" />
        <rect x="10" y="1089" width="774" height="24" fill="url(#greekKeyPatternPrint)" />
        <rect x="10" y="10" width="24" height="1103" fill="url(#greekKeyPatternPrint)" />
        <rect x="760" y="10" width="24" height="1103" fill="url(#greekKeyPatternPrint)" />
        <rect x="34" y="34" width="726" height="1055" fill="none" stroke="#12305c" stroke-width="2.5" />
        <rect x="39" y="39" width="716" height="1045" fill="none" stroke="#c09328" stroke-width="1.8" />
        <rect x="42" y="42" width="710" height="1039" fill="none" stroke="#c09328" stroke-width="0.8" opacity="0.7" />
      </svg>
    </div>

    <!-- REPEATING COLOR WATERMARK TEXT BACKGROUND -->
    <div class="parchment-watermark-text" aria-hidden="true">
      <svg class="parchment-watermark-svg" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
        <defs>
          <pattern id="watermarkTextPatternPrint" width="700" height="24" patternUnits="userSpaceOnUse">
            <text x="0" y="17" font-family="'Inter', 'Arial', sans-serif" font-size="8.5" font-weight="800"
              fill="#c4aa72" opacity="0.62" letter-spacing="1.2">{{ $watermarkRepeat }}</text>
          </pattern>
        </defs>
        <rect width="100%" height="100%" fill="url(#watermarkTextPatternPrint)" />
      </svg>
    </div>

    <!-- LARGE CENTER CREST WATERMARK -->
    <div class="center-crest-watermark" aria-hidden="true">
      <img src="{{ $data['instituteLogo'] }}" alt="Watermark Crest" />
    </div>

    <!-- INNER CERTIFICATE CONTENT -->
    <div class="certificate-inner">

      <!-- HEADER ROW: SERIAL NO + LOGO EMBLEM -->
      <div class="cert-header">
        <div class="cert-header-left">
          <div class="serial-tag">
            <strong>Serial No.:</strong> <span>{{ $data['serialNo'] }}</span>
          </div>
        </div>
        <div class="cert-header-center">
          <div class="logo-badge-container">
            <img class="cert-logo-img" src="{{ $data['instituteLogo'] }}" alt="Emblem" />
          </div>
        </div>
        <div class="cert-header-right"></div>
      </div>

      <!-- MAIN ORGANIZATION TITLE -->
      <div class="cert-org-block">
        <h1 class="org-title-main">{{ $data['councilName'] }}</h1>

        <div class="org-meta-columns">
          <div class="org-meta-col left">
            <p class="meta-item"><span class="meta-bullet">▶</span> <span>{{ $data['metaRunBy'] }}</span></p>
            <p class="meta-item"><span class="meta-bullet">▶</span> <span>{{ $data['metaRegd'] }}</span></p>
          </div>
          <div class="org-meta-col right">
            <p class="meta-item"><span class="meta-bullet">•</span> <span>{{ $data['metaAutonomous'] }}</span></p>
            <p class="meta-item"><span class="meta-bullet">•</span> <span>{{ $data['metaIso'] }}</span></p>
          </div>
        </div>
      </div>

      <!-- DIPLOMA BANNER & STUDENT PHOTO -->
      <div class="diploma-banner-row">
        <div class="diploma-cartouche">
          <div class="cartouche-filigree-left"></div>
          <div class="cartouche-body">
            <span class="diploma-gothic-text">Diploma</span>
          </div>
          <div class="cartouche-filigree-right"></div>
        </div>

        <div class="student-photo-frame">
          <img src="{{ $data['studentPhoto'] }}" alt="Student Photo" />
        </div>
      </div>

      <!-- CERTIFICATE BODY TEXT LINES -->
      <div class="cert-body">

        <p class="cert-line certify-text">
          <em>This is to certify that <span>{{ $data['gender'] }}</span></em>
        </p>

        <h2 class="cert-line student-name-text">
          {{ $data['studentName'] }}
        </h2>

        <p class="cert-line parent-label">
          <em>Son/Daughter of</em>
        </p>

        <h3 class="cert-line parent-name-text">
          {{ $data['fatherName'] }}
        </h3>

        <p class="cert-line enroll-text">
          <em>Enrollment No. : </em> <strong>{{ $data['enrollNo'] }}</strong>
        </p>

        <p class="cert-line institute-text">
          From <span>{{ $data['instituteName'] }}</span> , <span>{{ $data['location'] }}</span>
        </p>

        <p class="cert-line awarded-text">
          <em>has been Awarded the Diploma of</em>
        </p>

        <p class="cert-line course-category-text">
          {{ $data['courseCategory'] }}
        </p>

        <h4 class="cert-line course-title-text">
          {{ $data['courseName'] }}
        </h4>

        <p class="cert-line division-text">
          <em>with {!! \App\Http\Controllers\DiplomaController::formatDivision($data['division']) !!}</em>
        </p>

        <p class="cert-line given-seal-text">
          <em>given under the seal of</em>
        </p>

        <p class="cert-line council-script-text">
          {{ $data['councilName'] }}
        </p>

      </div>

      <!-- FOOTER ROW -->
      <div class="cert-footer">
        <div class="footer-col footer-left">
          <span class="place-text">{{ $data['place'] }}</span>
        </div>

        <div class="footer-col footer-scanner">
          <div class="scanner-wrap">
            <img class="footer-scanner-img" src="{{ $data['scannerImage'] }}" alt="Scanner / QR Code" />
          </div>
        </div>

        <div class="footer-col footer-center">
          <div class="footer-seal-wrapper">
            <img class="footer-seal-img" src="{{ $data['footerSeal'] }}" alt="Seal" />
          </div>
          <p class="issue-date-text">
            This <strong>{{ $dateParts['day'] }}</strong> day of <strong>{{ $dateParts['monthYear'] }}</strong>
          </p>
        </div>

        <div class="footer-col footer-right">
          <div class="sig-box">
            <div class="sig-img-wrap">
              <img class="director-sig-img" src="{{ asset('diploma-studio/assets/director-signature.svg') }}" alt="Director Signature" />
            </div>
            <div class="sig-line"></div>
            <span class="sig-title">Director</span>
          </div>
        </div>
      </div>

    </div><!-- .certificate-inner -->

  </div><!-- .diploma-certificate -->

</body>
</html>
