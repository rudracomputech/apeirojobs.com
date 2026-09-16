<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Diploma Certificate Studio - <?php echo e($data['studentName']); ?></title>
  <meta name="description"
    content="Official Diploma Certificate Studio for All India Council For Vocational & Paramedical Science" />

  <!-- Google Fonts: Old English / Gothic, Serifs, Cursive Script, and Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=UnifrakturCook:wght@700&family=Great+Vibes&family=IM+Fell+English:ital@0;1&family=Inter:wght@400;500;600;700;800&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Cinzel:wght@600;700;800&display=swap"
    rel="stylesheet">

  <link rel="stylesheet" href="<?php echo e(asset('diploma-studio/style.css')); ?>?v=<?php echo e(time()); ?>" />

  <style>
    /* Integration Topbar */
    .app-header {
      background: #0f172a;
      border-bottom: 1px solid #1e293b;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 12px;
      z-index: 100;
      position: relative;
    }

    .header-branding {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .header-branding h2 {
      font-size: 1.1rem;
      font-weight: 700;
      color: #f8fafc;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .header-badge {
      background: rgba(14, 116, 144, 0.2);
      color: #38bdf8;
      border: 1px solid rgba(56, 189, 248, 0.3);
      font-size: 0.72rem;
      font-weight: 600;
      padding: 2px 8px;
      border-radius: 999px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
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
      background: rgba(255, 255, 255, 0.06);
      padding: 5px 12px;
      border-radius: 8px;
      border: 1px solid rgba(255, 255, 255, 0.12);
    }

    .student-select-wrapper label {
      font-size: 0.8rem;
      color: #94a3b8;
      font-weight: 500;
      white-space: nowrap;
    }

    .student-select-wrapper select {
      background: #1e293b;
      color: #f8fafc;
      border: 1px solid #475569;
      border-radius: 6px;
      padding: 5px 10px;
      font-size: 0.82rem;
      outline: none;
      min-width: 200px;
      cursor: pointer;
    }

    .btn-dashboard-nav {
      background: transparent;
      color: #cbd5e1;
      border: 1px solid #475569;
      padding: 6px 14px;
      border-radius: 6px;
      text-decoration: none;
      font-size: 0.82rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s;
    }

    .btn-dashboard-nav:hover {
      background: #1e293b;
      color: #fff;
      border-color: #64748b;
    }

    .app-shell {
      height: calc(100vh - 56px);
    }

    .scanner-preview-thumb {
      max-height: 48px;
      object-fit: contain;
    }
  </style>
</head>

<body>

  <!-- ══════════════════════════════════════
       TOP HEADER: HSCouching Integration Bar
  ══════════════════════════════════════ -->
  <header class="app-header no-print">
    <div class="header-branding">
      <a href="<?php echo e(route('dashboard')); ?>" class="btn-dashboard-nav">
        ← Back to Dashboard
      </a>
      <h2>
        <span>🎓 Diploma Certificate Studio</span>
        <span class="header-badge">Admin Studio</span>
      </h2>
    </div>

    <div class="header-actions">
      <a href="<?php echo e(route('diploma.records')); ?>" class="btn btn-secondary-outline" style="background: #2563eb; color: #fff; text-decoration: none; border-radius: 6px; padding: 6px 12px; font-weight: 600; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
        📋 All Diplomas
      </a>
      <button type="button" id="btnSaveDiplomaTop" class="btn btn-primary-glow" style="background: #10b981; border: none; padding: 6px 14px; font-size: 13px; font-weight: 600; cursor: pointer;">
        💾 Save Record
      </button>
      <?php if(isset($students) && $students->count() > 0): ?>
        <div class="student-select-wrapper">
          <label for="studentQuickSelect">⚡ Quick Fill Student:</label>
          <select id="studentQuickSelect">
            <option value="">-- Select Registered Student --</option>
            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($st->id); ?>">
                <?php echo e($st->name); ?> (<?php echo e($st->student_code ?? 'ID: ' . $st->id); ?>)
              </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
      <?php endif; ?>
    </div>
  </header>

  <div class="app-shell">

    <!-- ══════════════════════════════════════
       LEFT SIDEBAR: CONTROLS & FORM
  ══════════════════════════════════════ -->
    <aside class="sidebar no-print" id="sidebar">
      <div class="sidebar-header">
        <div class="sidebar-logo">
          <img id="sidebarLogoImg" src="<?php echo e($data['instituteLogo']); ?>" alt="AICVPS Logo" />
        </div>
        <h1 class="sidebar-title">Diploma Generator</h1>
        <p class="sidebar-sub" id="sidebarCouncilSub"><?php echo e($data['councilName']); ?></p>
      </div>

      <form id="diplomaForm" method="POST" action="<?php echo e(route('diploma.print')); ?>" target="_blank" class="diploma-form" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="record_id" value="<?php echo e($data['id'] ?? ''); ?>">
        <input type="hidden" name="student_id" id="inp_student_id" value="<?php echo e($data['student_id'] ?? ''); ?>">

        <!-- Hidden inputs to persist Base64 uploads across print view -->
        <input type="hidden" id="studentPhoto_data" name="studentPhoto_data" value="<?php echo e($data['studentPhoto']); ?>" />
        <input type="hidden" id="instituteLogo_data" name="instituteLogo_data" value="<?php echo e($data['instituteLogo']); ?>" />
        <input type="hidden" id="footerSeal_data" name="footerSeal_data" value="<?php echo e($data['footerSeal']); ?>" />
        <input type="hidden" id="scannerImage_data" name="scannerImage_data" value="<?php echo e($data['scannerImage']); ?>" />

        <!-- QUICK ACTIONS -->
        <div class="quick-actions-bar">
          <button type="button" id="saveDiplomaBtn" class="btn" style="background: #10b981; color: #fff; font-weight: 700; padding: 10px; border: none; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; width: 100%;">
            💾 Save Diploma Record
          </button>
          <button type="button" id="printBtn" class="btn btn-primary-glow">
            <span class="btn-icon">🖨️</span> <strong>Print / Save PDF (Exact A4)</strong>
          </button>
          <button type="button" id="openDirectPrintBtn" class="btn btn-secondary-outline">
            <span class="btn-icon">↗️</span> Open Printable View
          </button>
        </div>

        <!-- STUDENT DETAILS -->
        <section class="form-section">
          <h2 class="section-heading"><span class="section-icon">👤</span> Student Information</h2>

          <div class="form-group">
            <label for="serialNo">Serial Number</label>
            <input type="text" id="serialNo" name="serialNo" value="<?php echo e($data['serialNo']); ?>"
              placeholder="e.g. 20235801" />
          </div>

          <div class="form-row-2">
            <div class="form-group">
              <label for="gender">Prefix</label>
              <select id="gender" name="gender">
                <option value="Mr." <?php echo e($data['gender'] === 'Mr.' ? 'selected' : ''); ?>>Mr.</option>
                <option value="Ms." <?php echo e($data['gender'] === 'Ms.' ? 'selected' : ''); ?>>Ms.</option>
                <option value="Mrs." <?php echo e($data['gender'] === 'Mrs.' ? 'selected' : ''); ?>>Mrs.</option>
                <option value="Dr." <?php echo e($data['gender'] === 'Dr.' ? 'selected' : ''); ?>>Dr.</option>
              </select>
            </div>
            <div class="form-group flex-2">
              <label for="studentName">Student Name <span class="req">*</span></label>
              <input type="text" id="studentName" name="studentName"
                value="<?php echo e($data['studentName']); ?>" required placeholder="e.g. Laxman Patole" />
            </div>
          </div>

          <div class="form-group">
            <label for="fatherName">Son/Daughter of <span class="req">*</span></label>
            <input type="text" id="fatherName" name="fatherName" value="<?php echo e($data['fatherName']); ?>"
              required placeholder="e.g. Balasaheb Patole" />
          </div>

          <div class="form-group">
            <label for="enrollNo">Enrollment No. <span class="req">*</span></label>
            <input type="text" id="enrollNo" name="enrollNo" value="<?php echo e($data['enrollNo']); ?>" required
              placeholder="e.g. ACI2023233750" />
          </div>
        </section>

        <!-- COURSE DETAILS -->
        <section class="form-section">
          <h2 class="section-heading"><span class="section-icon">🎓</span> Qualification Details</h2>

          <div class="form-group">
            <label for="courseCategory">Course Category</label>
            <input type="text" id="courseCategory" name="courseCategory"
              value="<?php echo e($data['courseCategory']); ?>" placeholder="e.g. Paramedical Courses" />
          </div>

          <div class="form-group">
            <label for="courseName">Diploma / Degree Name <span class="req">*</span></label>
            <input type="text" id="courseName" name="courseName" value="<?php echo e($data['courseName']); ?>"
              required placeholder="e.g. Diploma in General Nursing And Midwifery (GNM)" />
          </div>

          <div class="form-group">
            <label for="division">Award / Division</label>
            <select id="division" name="division">
              <option value="1st Division" <?php echo e($data['division'] === '1st Division' ? 'selected' : ''); ?>>1st Division</option>
              <option value="2nd Division" <?php echo e($data['division'] === '2nd Division' ? 'selected' : ''); ?>>2nd Division</option>
              <option value="3rd Division" <?php echo e($data['division'] === '3rd Division' ? 'selected' : ''); ?>>3rd Division</option>
              <option value="Distinction" <?php echo e($data['division'] === 'Distinction' ? 'selected' : ''); ?>>Distinction</option>
              <option value="Pass" <?php echo e($data['division'] === 'Pass' ? 'selected' : ''); ?>>Pass</option>
            </select>
          </div>

          <div class="form-group">
            <label for="instituteName">Affiliated Institute</label>
            <input type="text" id="instituteName" name="instituteName"
              value="<?php echo e($data['instituteName']); ?>" />
          </div>

          <div class="form-group">
            <label for="location">Institute Location</label>
            <input type="text" id="location" name="location" value="<?php echo e($data['location']); ?>"
              placeholder="e.g. Palghar (Maharashtra)" />
          </div>

          <div class="form-group">
            <label for="councilName">Council / Authority Name <span class="req">*</span></label>
            <input type="text" id="councilName" name="councilName" value="<?php echo e($data['councilName']); ?>"
              required placeholder="e.g. All India Council for Vocational &amp; Paramedical Science" />
          </div>
        </section>

        <!-- ORGANIZATION HEADER META -->
        <section class="form-section">
          <h2 class="section-heading"><span class="section-icon">📜</span> Organization Header Meta</h2>

          <div class="form-group">
            <label for="metaRunBy">Run By Line</label>
            <input type="text" id="metaRunBy" name="metaRunBy" value="<?php echo e($data['metaRunBy']); ?>"
              placeholder="e.g. Run by All India Council for Vocational &amp; Paramedical Science" />
          </div>

          <div class="form-group">
            <label for="metaRegd">Registration Line</label>
            <input type="text" id="metaRegd" name="metaRegd" value="<?php echo e($data['metaRegd']); ?>"
              placeholder="e.g. Regd. Under MSME, Govt. of India" />
          </div>

          <div class="form-group">
            <label for="metaAutonomous">Institution Type / Act Line</label>
            <input type="text" id="metaAutonomous" name="metaAutonomous"
              value="<?php echo e($data['metaAutonomous']); ?>"
              placeholder="e.g. An Autonomous Institution Registered Under the Trust Act of 1882" />
          </div>

          <div class="form-group">
            <label for="metaIso">ISO Certification Line</label>
            <input type="text" id="metaIso" name="metaIso" value="<?php echo e($data['metaIso']); ?>"
              placeholder="e.g. AN ISO 9001 : 2008 Certified Organization" />
          </div>
        </section>

        <!-- DATE & PLACE -->
        <section class="form-section">
          <h2 class="section-heading"><span class="section-icon">📅</span> Issue Date &amp; Place</h2>

          <div class="form-row-2">
            <div class="form-group">
              <label for="certDate">Issue Date <span class="req">*</span></label>
              <input type="date" id="certDate" name="certDate" value="<?php echo e($data['certDate']); ?>"
                required />
            </div>
            <div class="form-group">
              <label for="place">Place</label>
              <input type="text" id="place" name="place" value="<?php echo e($data['place']); ?>" />
            </div>
          </div>
        </section>

        <!-- STUDENT PHOTO UPLOAD -->
        <section class="form-section">
          <h2 class="section-heading"><span class="section-icon">📷</span> Student Photo</h2>
          <div class="form-group">
            <div class="file-drop-zone" id="photoDropZone">
              <input type="file" id="studentPhotoInput" accept="image/*" />
              <div class="drop-zone-content">
                <span class="drop-icon">🖼️</span>
                <span>Upload Student Photo</span>
                <span class="drop-hint">PNG / JPG (Passport Ratio)</span>
              </div>
              <img id="photoThumbPreview" src="<?php echo e($data['studentPhoto']); ?>" alt="Preview" />
            </div>
          </div>
        </section>

        <!-- INSTITUTE LOGO UPLOAD -->
        <section class="form-section">
          <h2 class="section-heading"><span class="section-icon">🏫</span> Official Logo</h2>
          <div class="form-group">
            <div class="file-drop-zone" id="logoDropZone">
              <input type="file" id="logoInput" accept="image/*" />
              <div class="drop-zone-content">
                <span class="drop-icon">🏫</span>
                <span>Upload Custom Emblem</span>
                <span class="drop-hint">PNG / SVG / JPG</span>
              </div>
              <img id="logoThumbPreview" src="<?php echo e($data['instituteLogo']); ?>" alt="Preview" />
            </div>
          </div>
        </section>

        <!-- FOOTER SEAL UPLOAD & INPUT -->
        <section class="form-section">
          <h2 class="section-heading"><span class="section-icon">🎖️</span> Circular Official Seal</h2>
          <div class="form-group">
            <div class="file-drop-zone" id="footerSealDropZone">
              <input type="file" id="footerSealInput" accept="image/*" />
              <div class="drop-zone-content">
                <span class="drop-icon">🎖️</span>
                <span>Upload Custom Seal</span>
                <span class="drop-hint">PNG / SVG</span>
              </div>
              <img id="footerSealThumbPreview" src="<?php echo e($data['footerSeal']); ?>" alt="Seal Preview" />
            </div>
          </div>
          <div class="form-group">
            <label for="footerSeal">Seal Image File / URL</label>
            <input type="text" id="footerSeal" name="footerSeal" value="<?php echo e($data['footerSeal']); ?>"
              placeholder="e.g. india-seal.svg" />
          </div>
        </section>

        <!-- SCANNER IMAGE UPLOAD & INPUT -->
        <section class="form-section">
          <h2 class="section-heading"><span class="section-icon">📱</span> Scanner / QR Code</h2>
          <div class="form-group">
            <div class="file-drop-zone" id="scannerDropZone">
              <input type="file" id="scannerImageInput" accept="image/*" />
              <div class="drop-zone-content">
                <span class="drop-icon">📱</span>
                <span>Upload Scanner / QR Code</span>
                <span class="drop-hint">PNG / SVG / JPG</span>
              </div>
              <img id="scannerThumbPreview" class="scanner-preview-thumb"
                src="<?php echo e($data['scannerImage']); ?>" alt="Scanner Preview" />
            </div>
          </div>
          <div class="form-group">
            <label for="scannerImage">Scanner Image File / URL</label>
            <input type="text" id="scannerImage" name="scannerImage" value="<?php echo e($data['scannerImage']); ?>"
              placeholder="e.g. images/qr_code.png" />
          </div>
        </section>

        <div class="form-actions">
          <button type="button" id="resetBtn" class="btn btn-ghost">
            <span class="btn-icon">🔄</span> Reset to Default
          </button>
        </div>

      </form>
    </aside>


    <!-- ══════════════════════════════════════
       RIGHT MAIN: LIVE PREVIEW CANVAS
  ══════════════════════════════════════ -->
    <main class="preview-area" id="previewArea">

      <!-- TOOLBAR (Hidden in Print) -->
      <div class="preview-toolbar no-print">
        <div class="toolbar-left">
          <span class="toolbar-badge">Live Exact Preview</span>
          <span class="toolbar-hint">Changes update instantly</span>
        </div>
        <div class="toolbar-actions">
          <button class="tool-btn" id="zoomOut" title="Zoom Out">－</button>
          <span class="zoom-level" id="zoomLevel">100%</span>
          <button class="tool-btn" id="zoomIn" title="Zoom In">＋</button>
          <button class="btn btn-sm btn-primary-glow" onclick="triggerPrint()">
            🖨️ Print / Save PDF
          </button>
        </div>
      </div>

      <!-- PREVIEW SCROLL VIEWPORT -->
      <div class="preview-canvas" id="previewCanvas">

        <div class="diploma-wrapper" id="diplomaWrapper">

          <!-- ════════════════════════════════════════════════════════════
             OFFICIAL DIPLOMA CERTIFICATE (A4 - 794px x 1123px)
        ════════════════════════════════════════════════════════════ -->
          <div class="diploma-certificate" id="diplomaCertificate">

            <!-- 1. GREEK KEY MEANDER BORDER FRAME (Vector SVG) -->
            <div class="greek-border-frame" aria-hidden="true">
              <svg class="greek-svg" viewBox="0 0 794 1123" preserveAspectRatio="none"
                xmlns="http://www.w3.org/2000/svg">
                <defs>
                  <pattern id="greekKeyPattern" width="24" height="24" patternUnits="userSpaceOnUse">
                    <path d="M 0,2 H 22 V 22 H 4 V 6 H 18 V 18 H 8 V 10 H 14 V 14 H 11" fill="none" stroke="#163e72"
                      stroke-width="2" stroke-linecap="square" />
                  </pattern>
                </defs>
                <!-- Outer Navy Line -->
                <rect x="5" y="5" width="784" height="1113" fill="none" stroke="#12305c" stroke-width="5" />
                <!-- Greek Key Bands -->
                <rect x="10" y="10" width="774" height="24" fill="url(#greekKeyPattern)" />
                <rect x="10" y="1089" width="774" height="24" fill="url(#greekKeyPattern)" />
                <rect x="10" y="10" width="24" height="1103" fill="url(#greekKeyPattern)" />
                <rect x="760" y="10" width="24" height="1103" fill="url(#greekKeyPattern)" />
                <!-- Inner Navy Rule -->
                <rect x="34" y="34" width="726" height="1055" fill="none" stroke="#12305c" stroke-width="2.5" />
                <!-- Double Gold Accent Inset -->
                <rect x="39" y="39" width="716" height="1045" fill="none" stroke="#c09328" stroke-width="1.8" />
                <rect x="42" y="42" width="710" height="1039" fill="none" stroke="#c09328" stroke-width="0.8"
                  opacity="0.7" />
              </svg>
            </div>

            <!-- 2. REPEATING COLOR WATERMARK TEXT BACKGROUND (Runs across whole parchment) -->
            <div class="parchment-watermark-text" aria-hidden="true">
              <svg class="parchment-watermark-svg" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                  <pattern id="watermarkTextPattern" width="700" height="24" patternUnits="userSpaceOnUse">
                    <text id="d_watermarkPatternText" x="0" y="17" font-family="'Inter', 'Arial', sans-serif"
                      font-size="8.5" font-weight="800" fill="#c4aa72" opacity="0.62"
                      letter-spacing="1.2"><?php echo e($watermarkRepeat); ?></text>
                  </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#watermarkTextPattern)" />
              </svg>
            </div>

            <!-- 3. LARGE CENTER COLORED CREST WATERMARK -->
            <div class="center-crest-watermark" aria-hidden="true">
              <img src="<?php echo e($data['instituteLogo']); ?>" id="d_watermarkLogo" alt="Watermark Crest" />
            </div>

            <!-- 4. INNER CERTIFICATE CONTENT -->
            <div class="certificate-inner">

              <!-- HEADER ROW: SERIAL NO + LOGO EMBLEM -->
              <div class="cert-header">
                <div class="cert-header-left">
                  <div class="serial-tag">
                    <strong>Serial No.:</strong> <span id="d_serialNo"><?php echo e($data['serialNo']); ?></span>
                  </div>
                </div>
                <div class="cert-header-center">
                  <div class="logo-badge-container">
                    <img id="d_logo" class="cert-logo-img" src="<?php echo e($data['instituteLogo']); ?>"
                      alt="Emblem" />
                  </div>
                </div>
                <div class="cert-header-right"></div>
              </div>

              <!-- MAIN ORGANIZATION TITLE -->
              <div class="cert-org-block">
                <h1 class="org-title-main" id="d_orgTitle"><?php echo e($data['councilName']); ?></h1>

                <div class="org-meta-columns">
                  <div class="org-meta-col left">
                    <p class="meta-item"><span class="meta-bullet">▶</span> <span
                        id="d_metaRunBy"><?php echo e($data['metaRunBy']); ?></span></p>
                    <p class="meta-item"><span class="meta-bullet">▶</span> <span
                        id="d_metaRegd"><?php echo e($data['metaRegd']); ?></span></p>
                  </div>
                  <div class="org-meta-col right">
                    <p class="meta-item"><span class="meta-bullet">•</span> <span
                        id="d_metaAutonomous"><?php echo e($data['metaAutonomous']); ?></span></p>
                    <p class="meta-item"><span class="meta-bullet">•</span> <span
                        id="d_metaIso"><?php echo e($data['metaIso']); ?></span></p>
                  </div>
                </div>
              </div>

              <!-- "DIPLOMA" ORNAMENTAL BANNER & STUDENT PHOTO ROW -->
              <div class="diploma-banner-row">
                <div class="diploma-cartouche">
                  <div class="cartouche-filigree-left"></div>
                  <div class="cartouche-body">
                    <span class="diploma-gothic-text">Diploma</span>
                  </div>
                  <div class="cartouche-filigree-right"></div>
                </div>

                <!-- Student Passport Photo Frame -->
                <div class="student-photo-frame">
                  <img id="d_studentPhoto" src="<?php echo e($data['studentPhoto']); ?>" alt="Student Photo" />
                  <span class="photo-alt-label" id="d_photoPlaceholder"
                    style="<?php echo e($data['studentPhoto'] ? 'display:none;' : ''); ?>">PHOTO</span>
                </div>
              </div>

              <!-- CERTIFICATE BODY TEXT LINES -->
              <div class="cert-body">

                <!-- 1. Certification line -->
                <p class="cert-line certify-text">
                  <em>This is to certify that <span id="d_gender"><?php echo e($data['gender']); ?></span></em>
                </p>

                <!-- 2. Student Name (Large Bold Clean Font) -->
                <h2 class="cert-line student-name-text" id="d_studentName">
                  <?php echo e($data['studentName']); ?>

                </h2>

                <!-- 3. Son/Daughter of -->
                <p class="cert-line parent-label">
                  <em>Son/Daughter of</em>
                </p>

                <!-- 4. Father Name (Bold Clean Font) -->
                <h3 class="cert-line parent-name-text" id="d_fatherName">
                  <?php echo e($data['fatherName']); ?>

                </h3>

                <!-- 5. Enrollment No. -->
                <p class="cert-line enroll-text">
                  <em>Enrollment No. : </em> <strong id="d_enrollNo"><?php echo e($data['enrollNo']); ?></strong>
                </p>

                <!-- 6. Institute & Location -->
                <p class="cert-line institute-text">
                  From <span id="d_instituteName"><?php echo e($data['instituteName']); ?></span> , <span
                    id="d_location"><?php echo e($data['location']); ?></span>
                </p>

                <!-- 7. Award statement -->
                <p class="cert-line awarded-text">
                  <em>has been Awarded the Diploma of</em>
                </p>

                <!-- 8. Course Category -->
                <p class="cert-line course-category-text" id="d_courseCategory">
                  <?php echo e($data['courseCategory']); ?>

                </p>

                <!-- 9. Diploma Course Title -->
                <h4 class="cert-line course-title-text" id="d_courseName">
                  <?php echo e($data['courseName']); ?>

                </h4>

                <!-- 10. Division -->
                <p class="cert-line division-text">
                  <em>with <span id="d_division"><?php echo \App\Http\Controllers\DiplomaController::formatDivision($data['division']); ?></span></em>
                </p>

                <!-- 11. Given under seal line -->
                <p class="cert-line given-seal-text">
                  <em>given under the seal of</em>
                </p>

                <!-- 12. Council Name in Elegant Blue Script -->
                <p class="cert-line council-script-text" id="d_councilName">
                  <?php echo e($data['councilName']); ?>

                </p>

              </div>

              <!-- FOOTER ROW: PLACE, ISSUE DATE, CIRCULAR SEAL & DIRECTOR SIGNATURE -->
              <div class="cert-footer">

                <!-- Bottom Left: Place -->
                <div class="footer-col footer-left">
                  <span class="place-text" id="d_place"><?php echo e($data['place']); ?></span>
                </div>

                <div class="footer-col footer-scanner">
                  <div class="scanner-wrap">
                    <img id="d_scannerImage" class="footer-scanner-img" src="<?php echo e($data['scannerImage']); ?>"
                      alt="Scanner / QR Code" />
                  </div>
                </div>

                <!-- Bottom Center: Date & Official Circular Seal -->
                <div class="footer-col footer-center">
                  <div class="footer-seal-wrapper">
                    <img class="footer-seal-img" id="d_footerSeal" src="<?php echo e($data['footerSeal']); ?>"
                      alt="Seal" />
                  </div>
                  <p class="issue-date-text">
                    This <strong id="d_day"><?php echo e($dateParts['day']); ?></strong> day of <strong
                      id="d_monthYear"><?php echo e($dateParts['monthYear']); ?></strong>
                  </p>
                </div>

                <!-- Bottom Right: Director Signature -->
                <div class="footer-col footer-right">
                  <div class="sig-box">
                    <div class="sig-img-wrap">
                      <img class="director-sig-img" src="<?php echo e(asset('diploma-studio/assets/director-signature.svg')); ?>" alt="Director Signature" />
                    </div>
                    <div class="sig-line"></div>
                    <span class="sig-title">Director</span>
                  </div>
                </div>

              </div>

            </div><!-- .certificate-inner -->

          </div><!-- .diploma-certificate -->

        </div><!-- .diploma-wrapper -->

      </div><!-- .preview-canvas -->

    </main>

  </div><!-- .app-shell -->

  <script>
    window.diplomaPrintUrl = "<?php echo e(route('diploma.print')); ?>";
    window.diplomaIndexUrl = "<?php echo e(route('diploma.index')); ?>";
    window.diplomaSaveUrl  = "<?php echo e(route('diploma.save')); ?>";

    const triggerDiplomaSave = () => {
      const form = document.getElementById('diplomaForm');
      if (form) {
        form.action = window.diplomaSaveUrl;
        form.target = '_self';
        form.method = 'POST';
        form.submit();
      }
    };
    document.getElementById('btnSaveDiplomaTop')?.addEventListener('click', triggerDiplomaSave);
    document.getElementById('saveDiplomaBtn')?.addEventListener('click', triggerDiplomaSave);
  </script>
  <script src="<?php echo e(asset('diploma-studio/script.js')); ?>?v=<?php echo e(time()); ?>"></script>
</body>
</html>
<?php /**PATH /home/willpowe/domains/apeirojobs.com/resources/views/backend/diploma/index.blade.php ENDPATH**/ ?>