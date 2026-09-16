/**
 * Migration Certificate Studio - script.js
 * Handles live synchronization, file previews, zoom, print, and student autofill
 */

document.addEventListener('DOMContentLoaded', () => {
  const assetBase = window.migrationAssetBase || '/migration-studio/assets/';
  const printUrl = window.migrationPrintUrl || '/migration/print';

  // Default data from reference
  const defaultData = {
    serialNo: '20251997',
    councilName: 'All India Council For Vocational & Paramedical Science',
    bullet1: 'Run by All India Council for Vocational & Paramedical Science',
    bullet2: 'Regd. Under MSME, Govt. of India',
    bullet3: 'An Autonomous Institution Registered Under the Trust Act of 1882',
    bullet4: 'AN ISO 9001 : 2008 Certified Organization',
    certTitle: 'Migration Certificate',
    studentPrefix: 'Mr./Ms.',
    studentName: 'Laxman Patole',
    sdPrefix: 'S/D of',
    parentName: 'Balasaheb Patole',
    passedText: 'has passed the',
    courseName: 'Diploma In General Nursing And Midwifery (GNM)',
    fromText: 'from',
    instName: 'All India Council for Vocational & Paramedical Science',
    inTheYearText: 'in the year',
    passYear: '2025',
    bearingText: 'bearing',
    enrollmentLabel: 'Enrollment Number',
    enrollmentNo: 'ACI2023233750',
    clause1: 'This Institution has no objection in his/her joining',
    clause2: 'any recognized College/Institution or taking examination of any Board Established by law.',
    placeName: 'Delhi',
    certDate: '29-06-2025',
    signatoryTitle: 'Director',
    logoSrc: assetBase + 'council-logo.svg',
    stampSrc: assetBase + 'official-stamp.svg',
    watermarkText: 'All India Council For Vocational & Paramedical Science'
  };

  // Map input IDs to certificate target IDs
  const fieldMapping = {
    'input_serialNo': 'cert_serialNo',
    'input_councilName': 'cert_councilName',
    'input_bullet1': 'cert_bullet1',
    'input_bullet2': 'cert_bullet2',
    'input_bullet3': 'cert_bullet3',
    'input_bullet4': 'cert_bullet4',
    'input_certTitle': 'cert_certTitle',
    'input_studentPrefix': 'cert_studentPrefix',
    'input_studentName': 'cert_studentName',
    'input_sdPrefix': 'cert_sdPrefix',
    'input_parentName': 'cert_parentName',
    'input_passedText': 'cert_passedText',
    'input_courseName': 'cert_courseName',
    'input_fromText': 'cert_fromText',
    'input_instName': 'cert_instName',
    'input_inTheYearText': 'cert_inTheYearText',
    'input_passYear': 'cert_passYear',
    'input_bearingText': 'cert_bearingText',
    'input_enrollmentLabel': 'cert_enrollmentLabel',
    'input_enrollmentNo': 'cert_enrollmentNo',
    'input_clause1': 'cert_clause1',
    'input_clause2': 'cert_clause2',
    'input_placeName': 'cert_placeName',
    'input_certDate': 'cert_certDate',
    'input_signatoryTitle': 'cert_signatoryTitle'
  };

  // Attach live input listeners
  Object.keys(fieldMapping).forEach(inputId => {
    const inputEl = document.getElementById(inputId);
    const targetEl = document.getElementById(fieldMapping[inputId]);

    if (inputEl && targetEl) {
      inputEl.addEventListener('input', () => {
        targetEl.textContent = inputEl.value;
      });
    }
  });

  // Dynamic Watermark synchronization based on Council Main Name
  const councilInput = document.getElementById('input_councilName');
  const watermarkInput = document.getElementById('input_watermarkText');
  const watermarkSvgText = document.getElementById('cert_watermarkPatternText');

  function updateWatermark(text) {
    if (!watermarkSvgText) return;
    const clean = (text || 'All India Council For Vocational & Paramedical Science').toUpperCase().trim();
    let repeated = clean + ' • ';
    while (repeated.length < 160) {
      repeated += clean + ' • ';
    }
    watermarkSvgText.textContent = repeated;
  }

  if (councilInput) {
    councilInput.addEventListener('input', () => {
      if (watermarkInput) {
        watermarkInput.value = councilInput.value;
      }
      updateWatermark(councilInput.value);
    });
  }

  if (watermarkInput) {
    watermarkInput.addEventListener('input', () => {
      updateWatermark(watermarkInput.value);
    });
  }

  // Custom Logo Upload Handling
  const logoInput = document.getElementById('input_logoFile');
  const certLogo = document.getElementById('cert_logo');
  if (logoInput && certLogo) {
    logoInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (event) => {
          certLogo.src = event.target.result;
          const logoHidden = document.getElementById('input_logoSrc_data');
          if (logoHidden) logoHidden.value = event.target.result;
        };
        reader.readAsDataURL(file);
      }
    });
  }

  // Custom Stamp/Signature Upload Handling
  const stampInput = document.getElementById('input_stampFile');
  const certStamp = document.getElementById('cert_stamp');
  if (stampInput && certStamp) {
    stampInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (event) => {
          certStamp.src = event.target.result;
          const stampHidden = document.getElementById('input_stampSrc_data');
          if (stampHidden) stampHidden.value = event.target.result;
        };
        reader.readAsDataURL(file);
      }
    });
  }

  // Reset to Defaults Button
  const resetBtn = document.getElementById('resetBtn');
  if (resetBtn) {
    resetBtn.addEventListener('click', () => {
      if (confirm('Reset all fields to reference default values?')) {
        Object.keys(defaultData).forEach(key => {
          const inputEl = document.getElementById('input_' + key);
          const targetEl = document.getElementById('cert_' + key);
          if (inputEl) {
            inputEl.value = defaultData[key];
          }
          if (targetEl) {
            targetEl.textContent = defaultData[key];
          }
        });

        if (certLogo) certLogo.src = defaultData.logoSrc;
        if (certStamp) certStamp.src = defaultData.stampSrc;
        updateWatermark(defaultData.watermarkText || defaultData.councilName);

        const studentSelect = document.getElementById('studentQuickSelect');
        if (studentSelect) studentSelect.value = '';
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
        const res = await fetch(`/migration/student/${studentId}`);
        if (!res.ok) throw new Error('Failed to fetch student data');
        const data = await res.json();

        function setField(id, val) {
          const inp = document.getElementById(id);
          const target = document.getElementById(fieldMapping[id]);
          if (inp && val !== undefined && val !== null) {
            inp.value = val;
            if (target) target.textContent = val;
          }
        }

        if (data.name) setField('input_studentName', data.name);
        if (data.father_name) setField('input_parentName', data.father_name);
        if (data.course_name) setField('input_courseName', data.course_name);
        if (data.enrollment_no) setField('input_enrollmentNo', data.enrollment_no);
        if (data.pass_year) setField('input_passYear', data.pass_year);
      } catch (err) {
        console.error('Error autofilling student data:', err);
      }
    });
  }

  // Zoom Handling
  let currentZoom = 1.0;
  const certWrapper = document.getElementById('certWrapper');
  const zoomInBtn = document.getElementById('zoomInBtn');
  const zoomOutBtn = document.getElementById('zoomOutBtn');
  const zoomResetBtn = document.getElementById('zoomResetBtn');
  const zoomLevelDisplay = document.getElementById('zoomLevelDisplay');

  function updateZoom(newZoom) {
    currentZoom = Math.min(Math.max(newZoom, 0.4), 1.6);
    if (certWrapper) {
      certWrapper.style.transform = `scale(${currentZoom})`;
    }
    if (zoomLevelDisplay) {
      zoomLevelDisplay.textContent = `${Math.round(currentZoom * 100)}%`;
    }
  }

  if (zoomInBtn) zoomInBtn.addEventListener('click', () => updateZoom(currentZoom + 0.1));
  if (zoomOutBtn) zoomOutBtn.addEventListener('click', () => updateZoom(currentZoom - 0.1));
  if (zoomResetBtn) zoomResetBtn.addEventListener('click', () => updateZoom(1.0));

  // Auto-fit to screen on load if viewport is small
  function autoFit() {
    const viewport = document.querySelector('.canvas-viewport');
    if (viewport && certWrapper) {
      const vWidth = viewport.clientWidth - 60;
      const vHeight = viewport.clientHeight - 60;
      const certW = 1050;
      const certH = 742;

      const fitScale = Math.min(vWidth / certW, vHeight / certH, 1.0);
      if (fitScale < 1.0) {
        updateZoom(Math.floor(fitScale * 10) / 10);
      }
    }
  }

  window.addEventListener('resize', autoFit);
  autoFit();

  // Print Handlers
  const printDirectBtn = document.getElementById('printDirectBtn');
  if (printDirectBtn) {
    printDirectBtn.addEventListener('click', () => {
      window.print();
    });
  }

  // Open Clean Print View Handler
  const openPrintViewBtn = document.getElementById('openPrintViewBtn');
  if (openPrintViewBtn) {
    openPrintViewBtn.addEventListener('click', (e) => {
      e.preventDefault();
      const form = document.getElementById('certForm');
      if (form) {
        form.action = printUrl;
        form.target = '_blank';
        form.method = 'POST';
        form.submit();
      }
    });
  }
});
