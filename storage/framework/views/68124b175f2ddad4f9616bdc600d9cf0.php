<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Migration Certificate Studio - HSCouching</title>
  <link rel="stylesheet" href="<?php echo e(asset('migration-studio/style.css')); ?>?v=<?php echo e(time()); ?>">
  <style>
    /* Integration bar enhancements */
    .app-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 24px;
      flex-wrap: wrap;
      gap: 12px;
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

  <!-- Top App Navigation Bar -->
  <header class="app-header">
    <div class="brand-wrapper">
      <div class="brand-icon">📜</div>
      <div class="brand-titles">
        <h1>Migration Certificate Studio</h1>
        <p>All India Council For Vocational &amp; Paramedical Science</p>
      </div>
    </div>

    <!-- Student Quick Select & Actions -->
    <div class="header-actions">
      <?php if(isset($students) && $students->count() > 0): ?>
      <div class="student-select-wrapper">
        <label for="studentQuickSelect">🎓 Quick Fill Student:</label>
        <select id="studentQuickSelect">
          <option value="">-- Choose Student --</option>
          <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($student->id); ?>"><?php echo e($student->name); ?> (<?php echo e($student->student_code ?: '#' . $student->id); ?>)</option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <?php endif; ?>
      <a href="<?php echo e(route('migration.records')); ?>" class="btn btn-secondary" style="background: #2563eb; color: #fff;">
        <span>📋</span> All Migration Certificates
      </a>

      <button type="button" id="btnSaveMigrationTop" class="btn btn-success" style="background: #10b981; color: #fff; font-weight: 600;">
        <span>💾</span> Save Record
      </button>

      <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-dashboard" title="Return to Dashboard">
        <span>⬅️</span> Dashboard
      </a>

      <button type="button" id="resetBtn" class="btn btn-ghost" title="Reset all fields to default values">
        <span>🔄</span> Reset Defaults
      </button>

      <button type="button" id="openPrintViewBtn" class="btn btn-secondary"
        title="Open clean print window without interface">
        <span>🖨️</span> Standalone Print View
      </button>

      <button type="button" id="printDirectBtn" class="btn btn-primary" title="Print directly or save as PDF">
        <span>📄</span> Print / Save PDF
      </button>
    </div>
  </header>

  <!-- Main Studio Workspace -->
  <div class="studio-layout">

    <!-- LEFT SIDEBAR: INPUT CONTROLS -->
    <aside class="sidebar-panel">
      <div class="sidebar-header">
        <h2>Certificate Details</h2>
        <div class="sync-badge">
          <span class="sync-dot"></span> Live Sync
        </div>
      </div>

      <div class="sidebar-scroll">
        <form id="certForm" method="POST" action="<?php echo e(route('migration.print')); ?>" target="_blank">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="record_id" value="<?php echo e($data['id'] ?? ''); ?>">
          <input type="hidden" name="student_id" id="inp_student_id" value="<?php echo e($data['student_id'] ?? ''); ?>">
          <input type="hidden" id="input_logoSrc_data" name="logoSrc_data" value="">
          <input type="hidden" id="input_stampSrc_data" name="stampSrc_data" value="">

          <!-- 1. CERTIFICATE METADATA -->
          <div class="form-card">
            <div class="form-card-title">
              <span>📌</span> Certificate Header &amp; Serial
            </div>
            <div class="form-card-body">
              <div class="form-group">
                <label class="form-label" for="input_serialNo">Serial Number</label>
                <input type="text" id="input_serialNo" name="serialNo" class="form-input"
                  value="<?php echo e($data['serialNo']); ?>">
              </div>
              <div class="form-group">
                <label class="form-label" for="input_certTitle">Certificate Title</label>
                <input type="text" id="input_certTitle" name="certTitle" class="form-input"
                  value="<?php echo e($data['certTitle']); ?>">
              </div>
            </div>
          </div>

          <!-- 2. COUNCIL / ISSUING BODY -->
          <div class="form-card">
            <div class="form-card-title">
              <span>🏛️</span> Council Details &amp; Registration
            </div>
            <div class="form-card-body">
              <div class="form-group">
                <label class="form-label" for="input_councilName">Council Main Name</label>
                <input type="text" id="input_councilName" name="councilName" class="form-input"
                  value="<?php echo e($data['councilName']); ?>">
              </div>

              <div class="form-group">
                <label class="form-label" for="input_watermarkText">Council Watermark Text</label>
                <input type="text" id="input_watermarkText" name="watermarkText" class="form-input"
                  value="<?php echo e($watermarkSource); ?>" placeholder="Auto-syncs with Council Main Name">
              </div>

              <div class="form-group">
                <label class="form-label" for="input_bullet1">Bullet 1 (Top Left)</label>
                <input type="text" id="input_bullet1" name="bullet1" class="form-input"
                  value="<?php echo e($data['bullet1']); ?>">
              </div>
              <div class="form-group">
                <label class="form-label" for="input_bullet2">Bullet 2 (Bottom Left)</label>
                <input type="text" id="input_bullet2" name="bullet2" class="form-input"
                  value="<?php echo e($data['bullet2']); ?>">
              </div>
              <div class="form-group">
                <label class="form-label" for="input_bullet3">Bullet 3 (Top Right)</label>
                <input type="text" id="input_bullet3" name="bullet3" class="form-input"
                  value="<?php echo e($data['bullet3']); ?>">
              </div>
              <div class="form-group">
                <label class="form-label" for="input_bullet4">Bullet 4 (Bottom Right)</label>
                <input type="text" id="input_bullet4" name="bullet4" class="form-input"
                  value="<?php echo e($data['bullet4']); ?>">
              </div>

              <!-- Custom Logo Upload -->
              <div class="form-group">
                <label class="form-label">Council Crest Logo</label>
                <div class="file-upload-wrapper">
                  <label class="file-label-btn" for="input_logoFile">📁 Choose Custom Logo</label>
                  <input type="file" id="input_logoFile" class="file-input" accept="image/*">
                </div>
              </div>
            </div>
          </div>

          <!-- 3. CANDIDATE DETAILS -->
          <div class="form-card">
            <div class="form-card-title">
              <span>👤</span> Candidate &amp; Parent Details
            </div>
            <div class="form-card-body">
              <div class="form-row">
                <div class="form-group" style="flex: 0.35;">
                  <label class="form-label" for="input_studentPrefix">Salutation</label>
                  <input type="text" id="input_studentPrefix" name="studentPrefix" class="form-input"
                    value="<?php echo e($data['studentPrefix']); ?>">
                </div>
                <div class="form-group" style="flex: 0.65;">
                  <label class="form-label" for="input_studentName">Student Name</label>
                  <input type="text" id="input_studentName" name="studentName" class="form-input"
                    value="<?php echo e($data['studentName']); ?>">
                </div>
              </div>

              <div class="form-row">
                <div class="form-group" style="flex: 0.35;">
                  <label class="form-label" for="input_sdPrefix">Relation</label>
                  <input type="text" id="input_sdPrefix" name="sdPrefix" class="form-input"
                    value="<?php echo e($data['sdPrefix']); ?>">
                </div>
                <div class="form-group" style="flex: 0.65;">
                  <label class="form-label" for="input_parentName">Parent / Guardian Name</label>
                  <input type="text" id="input_parentName" name="parentName" class="form-input"
                    value="<?php echo e($data['parentName']); ?>">
                </div>
              </div>
            </div>
          </div>

          <!-- 4. ACADEMIC & COURSE INFORMATION -->
          <div class="form-card">
            <div class="form-card-title">
              <span>🎓</span> Academic &amp; Passing Details
            </div>
            <div class="form-card-body">
              <div class="form-group">
                <label class="form-label" for="input_courseName">Course / Qualification Passed</label>
                <input type="text" id="input_courseName" name="courseName" class="form-input"
                  value="<?php echo e($data['courseName']); ?>">
              </div>

              <div class="form-group">
                <label class="form-label" for="input_instName">Examining Body / Institution</label>
                <input type="text" id="input_instName" name="instName" class="form-input"
                  value="<?php echo e($data['instName']); ?>">
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label" for="input_passYear">Year of Passing</label>
                  <input type="text" id="input_passYear" name="passYear" class="form-input"
                    value="<?php echo e($data['passYear']); ?>">
                </div>
                <div class="form-group">
                  <label class="form-label" for="input_enrollmentNo">Enrollment Number</label>
                  <input type="text" id="input_enrollmentNo" name="enrollmentNo" class="form-input"
                    value="<?php echo e($data['enrollmentNo']); ?>">
                </div>
              </div>
            </div>
          </div>

          <!-- 5. NO OBJECTION CLAUSE -->
          <div class="form-card">
            <div class="form-card-title">
              <span>⚖️</span> Objection Clause Statement
            </div>
            <div class="form-card-body">
              <div class="form-group">
                <label class="form-label" for="input_clause1">Clause Line 1</label>
                <input type="text" id="input_clause1" name="clause1" class="form-input"
                  value="<?php echo e($data['clause1']); ?>">
              </div>
              <div class="form-group">
                <label class="form-label" for="input_clause2">Clause Line 2</label>
                <textarea id="input_clause2" name="clause2" class="form-textarea"><?php echo e($data['clause2']); ?></textarea>
              </div>
            </div>
          </div>

          <!-- 6. FOOTER, DATE & SIGNATURE -->
          <div class="form-card">
            <div class="form-card-title">
              <span>✒️</span> Date, Location &amp; Authority
            </div>
            <div class="form-card-body">
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label" for="input_placeName">Location / Place</label>
                  <input type="text" id="input_placeName" name="placeName" class="form-input"
                    value="<?php echo e($data['placeName']); ?>">
                </div>
                <div class="form-group">
                  <label class="form-label" for="input_certDate">Date</label>
                  <input type="text" id="input_certDate" name="certDate" class="form-input"
                    value="<?php echo e($data['certDate']); ?>">
                </div>
              </div>

              <div class="form-group">
                <label class="form-label" for="input_signatoryTitle">Signatory Title</label>
                <input type="text" id="input_signatoryTitle" name="signatoryTitle" class="form-input"
                  value="<?php echo e($data['signatoryTitle']); ?>">
              </div>

              <!-- Custom Stamp Upload -->
              <div class="form-group">
                <label class="form-label">Official Seal &amp; Signature</label>
                <div class="file-upload-wrapper">
                  <label class="file-label-btn" for="input_stampFile">📁 Choose Custom Stamp/Sign</label>
                  <input type="file" id="input_stampFile" class="file-input" accept="image/*">
                </div>
              </div>
            </div>
          </div>

          <div style="margin-top: 14px; margin-bottom: 20px;">
            <button type="button" id="btnSaveMigrationBottom" class="btn btn-primary" style="width: 100%; background: #10b981; border: none; padding: 10px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;">
              💾 Save Migration Certificate Record
            </button>
          </div>

        </form>
      </div>
    </aside>


    <!-- RIGHT CANVAS: REAL-TIME PREVIEW -->
    <main class="preview-canvas-area">

      <!-- Canvas Control Toolbar -->
      <div class="canvas-toolbar">
        <div class="toolbar-info">
          <span><strong>Format:</strong> A4 Landscape (297mm × 210mm)</span>
          <span><strong>Scale:</strong> <span id="zoomLevelDisplay">100%</span></span>
        </div>
        <div class="toolbar-controls">
          <button type="button" id="zoomOutBtn" class="zoom-btn" title="Zoom Out">−</button>
          <button type="button" id="zoomResetBtn" class="zoom-btn" title="Reset Zoom">1:1</button>
          <button type="button" id="zoomInBtn" class="zoom-btn" title="Zoom In">+</button>
        </div>
      </div>

      <!-- Viewport with Certificate Canvas -->
      <div class="canvas-viewport">
        <div class="certificate-wrapper" id="certWrapper">

          <div class="certificate-sheet" id="certificateSheet">

            <!-- Greek Key Ornamental Border -->
            <img src="<?php echo e(asset('migration-studio/assets/greek-border-hd.png')); ?>" class="cert-greek-border" alt="Decorative Border" />

            <!-- Main Inner Certificate Container -->
            <div class="cert-inner">

              <!-- Dynamic Council Security Watermark Text & Crest Watermarks -->
              <div class="cert-watermark-pattern" aria-hidden="true">
                <svg class="cert-watermark-svg" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                  <defs>
                    <pattern id="watermarkTextPattern" width="750" height="24" patternUnits="userSpaceOnUse">
                      <text id="cert_watermarkPatternText" x="0" y="16"
                        font-family="'Cinzel', 'Playfair Display', 'Times New Roman', serif" font-size="8"
                        font-weight="700" fill="#a48c66" opacity="0.22"
                        letter-spacing="1.5"><?php echo e($watermarkRepeat); ?></text>
                    </pattern>
                  </defs>
                  <rect width="100%" height="100%" fill="url(#watermarkTextPattern)" />
                </svg>
              </div>
              <img src="<?php echo e(asset('migration-studio/assets/council-logo.svg')); ?>" class="cert-watermark-crest" alt="Watermark Crest" />

              <!-- ═════════ HEADER ═════════ -->
              <div class="cert-header">

                <!-- Serial Number Row -->
                <div class="cert-top-bar">
                  <div class="cert-serial-no">
                    Serial No.: <span id="cert_serialNo"><?php echo e($data['serialNo']); ?></span>
                  </div>
                </div>

                <!-- Council Logo Emblem -->
                <div class="cert-logo-container">
                  <img id="cert_logo" src="<?php echo e($data['logoSrc']); ?>" class="cert-logo-img" alt="Council Emblem" />
                </div>

                <!-- Main Council Title -->
                <h1 class="cert-council-title" id="cert_councilName">
                  <?php echo e($data['councilName']); ?>

                </h1>

                <!-- Credential Bullets (2-Column Grid) -->
                <div class="cert-meta-grid">
                  <div class="meta-item">
                    <span class="meta-bullet">•</span>
                    <span id="cert_bullet1"><?php echo e($data['bullet1']); ?></span>
                  </div>
                  <div class="meta-item">
                    <span class="meta-bullet">•</span>
                    <span id="cert_bullet3"><?php echo e($data['bullet3']); ?></span>
                  </div>
                  <div class="meta-item">
                    <span class="meta-bullet">•</span>
                    <span id="cert_bullet2"><?php echo e($data['bullet2']); ?></span>
                  </div>
                  <div class="meta-item">
                    <span class="meta-bullet">•</span>
                    <span id="cert_bullet4"><?php echo e($data['bullet4']); ?></span>
                  </div>
                </div>

              </div>

              <!-- ═════════ ORNAMENTAL BANNER: MIGRATION CERTIFICATE ═════════ -->
              <div class="cert-cartouche-wrapper">
                <div class="cartouche-frame">
                  <img src="<?php echo e(asset('migration-studio/assets/cartouche-filigree-left.png')); ?>" class="cartouche-ear ear-left" alt="" />
                  <div class="cartouche-pill-outer">
                    <div class="cartouche-pill">
                      <span class="cartouche-text" id="cert_certTitle"><?php echo e($data['certTitle']); ?></span>
                    </div>
                  </div>
                  <img src="<?php echo e(asset('migration-studio/assets/cartouche-filigree-right.png')); ?>" class="cartouche-ear ear-right" alt="" />
                </div>
              </div>

              <!-- ═════════ STATEMENT BODY (5 EXACT LINES) ═════════ -->
              <div class="cert-statement-body">

                <!-- LINE 1 -->
                <div class="statement-row row-1">
                  <span class="static-prompt" id="cert_studentPrefix"><?php echo e($data['studentPrefix']); ?></span>
                  <div class="fill-slot slot-student">
                    <span class="fill-value" id="cert_studentName"><?php echo e($data['studentName']); ?></span>
                  </div>
                  <span class="static-prompt" id="cert_sdPrefix"><?php echo e($data['sdPrefix']); ?></span>
                  <div class="fill-slot slot-parent">
                    <span class="fill-value" id="cert_parentName"><?php echo e($data['parentName']); ?></span>
                  </div>
                </div>

                <!-- LINE 2 -->
                <div class="statement-row row-2">
                  <span class="static-prompt" id="cert_passedText"><?php echo e($data['passedText']); ?></span>
                  <div class="fill-slot slot-course">
                    <span class="fill-value" id="cert_courseName"><?php echo e($data['courseName']); ?></span>
                  </div>
                  <span class="static-prompt" id="cert_fromText"><?php echo e($data['fromText']); ?></span>
                </div>

                <!-- LINE 3 -->
                <div class="statement-row row-3">
                  <div class="fill-slot slot-inst">
                    <span class="fill-value" id="cert_instName"><?php echo e($data['instName']); ?></span>
                  </div>
                  <span class="static-prompt" id="cert_inTheYearText"><?php echo e($data['inTheYearText']); ?></span>
                  <div class="fill-slot slot-year">
                    <span class="fill-value" id="cert_passYear"><?php echo e($data['passYear']); ?></span>
                  </div>
                  <span class="static-prompt" id="cert_bearingText"><?php echo e($data['bearingText']); ?></span>
                </div>

                <!-- LINE 4 -->
                <div class="statement-row row-4">
                  <span class="static-prompt" id="cert_enrollmentLabel"><?php echo e($data['enrollmentLabel']); ?></span>
                  <div class="fill-slot slot-enroll">
                    <span class="fill-value" id="cert_enrollmentNo"><?php echo e($data['enrollmentNo']); ?></span>
                  </div>
                  <span class="static-prompt prompt-clause-1" id="cert_clause1"><?php echo e($data['clause1']); ?></span>
                </div>

                <!-- LINE 5 -->
                <div class="statement-row row-5">
                  <span class="prompt-clause-2" id="cert_clause2"><?php echo e($data['clause2']); ?></span>
                </div>

              </div>

              <!-- ═════════ FOOTER (DELHI, DATED, STAMP, DIRECTOR) ═════════ -->
              <div class="cert-footer">

                <!-- Left: Location & Date -->
                <div class="footer-left">
                  <div class="footer-place" id="cert_placeName"><?php echo e($data['placeName']); ?></div>
                  <div class="footer-date">
                    Dated : <span id="cert_certDate"><?php echo e($data['certDate']); ?></span>
                  </div>
                </div>

                <!-- Right: Stamp, Signature & Title -->
                <div class="footer-right">
                  <div class="stamp-signature-box">
                    <img id="cert_stamp" src="<?php echo e($data['stampSrc']); ?>" class="official-stamp-img"
                      alt="Official Stamp and Signature" />
                  </div>
                  <div class="footer-signatory-title" id="cert_signatoryTitle">
                    <?php echo e($data['signatoryTitle']); ?>

                  </div>
                </div>

              </div>

            </div>
          </div>

        </div>
      </div>

    </main>

  </div>

  <script>
    window.migrationAssetBase = "<?php echo e(asset('migration-studio/assets')); ?>/";
    window.migrationPrintUrl = "<?php echo e(route('migration.print')); ?>";
    window.migrationSaveUrl  = "<?php echo e(route('migration.save')); ?>";

    const triggerMigrationSave = () => {
      const form = document.getElementById('certForm');
      if (form) {
        form.action = window.migrationSaveUrl;
        form.target = '_self';
        form.method = 'POST';
        form.submit();
      }
    };
    document.getElementById('btnSaveMigrationTop')?.addEventListener('click', triggerMigrationSave);
    document.getElementById('btnSaveMigrationBottom')?.addEventListener('click', triggerMigrationSave);
  </script>
  <script src="<?php echo e(asset('migration-studio/script.js')); ?>?v=<?php echo e(time()); ?>"></script>
</body>

</html>
<?php /**PATH /home/willpowe/domains/apeirojobs.com/resources/views/backend/migration/index.blade.php ENDPATH**/ ?>