/**
 * AICVPS Official Diploma Generator – JavaScript Controller
 * Instant Real-time Synchronisation, Upload Handling, Quick Fill & Print Engine
 */

'use strict';

// DOM Elements
const form = document.getElementById('diplomaForm');
const printBtn = document.getElementById('printBtn');
const openDirectPrintBtn = document.getElementById('openDirectPrintBtn');
const resetBtn = document.getElementById('resetBtn');
const zoomInBtn = document.getElementById('zoomIn');
const zoomOutBtn = document.getElementById('zoomOut');
const zoomLevelEl = document.getElementById('zoomLevel');
const diplomaWrapper = document.getElementById('diplomaWrapper');

// Zoom state
let zoomScale = 1;
const ZOOM_STEP = 0.1;
const ZOOM_MIN  = 0.4;
const ZOOM_MAX  = 1.5;

// Initial setup
document.addEventListener('DOMContentLoaded', () => {
  setupLivePreview();
  setupUploads();
  setupZoomControls();
  setupActions();
  setupQuickFill();
});

/**
 * Live two-way synchronization from form inputs to diploma certificate
 */
function setupLivePreview() {
  const bindings = [
    { id: 'serialNo',       target: 'd_serialNo' },
    { id: 'studentName',    target: 'd_studentName' },
    { id: 'fatherName',     target: 'd_fatherName' },
    { id: 'enrollNo',       target: 'd_enrollNo' },
    { id: 'gender',         target: 'd_gender' },
    { id: 'courseCategory', target: 'd_courseCategory' },
    { id: 'courseName',     target: 'd_courseName' },
    { id: 'division',       target: 'd_division' },
    { id: 'instituteName',  target: 'd_instituteName' },
    { id: 'location',       target: 'd_location' },
    { id: 'place',          target: 'd_place' },
    { id: 'councilName',    target: 'd_councilName' },
    { id: 'metaRunBy',      target: 'd_metaRunBy' },
    { id: 'metaRegd',       target: 'd_metaRegd' },
    { id: 'metaAutonomous', target: 'd_metaAutonomous' },
    { id: 'metaIso',        target: 'd_metaIso' },
  ];

  bindings.forEach(({ id, target }) => {
    const inputEl = document.getElementById(id);
    const targetEl = document.getElementById(target);
    if (!inputEl || !targetEl) return;

    const updateValue = () => {
      if (id === 'division') {
        targetEl.innerHTML = formatDivision(inputEl.value || '');
      } else {
        targetEl.textContent = inputEl.value || '';
      }
    };

    inputEl.addEventListener('input', updateValue);
    inputEl.addEventListener('change', updateValue);
  });

  // Dynamic Council / Organization Name synchronization across all occurrences
  const councilInput = document.getElementById('councilName');
  if (councilInput) {
    const updateCouncil = () => {
      const val = councilInput.value || '';
      const dCouncil = document.getElementById('d_councilName');
      const dOrg = document.getElementById('d_orgTitle');
      const sidebarSub = document.getElementById('sidebarCouncilSub');
      const watermarkPatternText = document.getElementById('d_watermarkPatternText');

      if (dCouncil) dCouncil.textContent = val;
      if (dOrg) dOrg.textContent = val;
      if (sidebarSub) sidebarSub.textContent = val;

      if (watermarkPatternText) {
        const clean = (val || 'All India Council for Vocational & Paramedical Science').toUpperCase().trim();
        let repeated = clean + ' • ';
        while (repeated.length < 100) {
          repeated += clean + ' • ';
        }
        watermarkPatternText.textContent = repeated;
      }
    };
    councilInput.addEventListener('input', updateCouncil);
    councilInput.addEventListener('change', updateCouncil);
  }

  // Dynamic Footer Seal image path / URL text input
  const footerSealTextInput = document.getElementById('footerSeal');
  if (footerSealTextInput) {
    const updateSeal = () => {
      const val = footerSealTextInput.value || 'india-seal.svg';
      const sealEl = document.getElementById('d_footerSeal');
      const thumbEl = document.getElementById('footerSealThumbPreview');
      if (sealEl) sealEl.src = val;
      if (thumbEl) thumbEl.src = val;
    };
    footerSealTextInput.addEventListener('input', updateSeal);
    footerSealTextInput.addEventListener('change', updateSeal);
  }

  // Dynamic Scanner Image path / URL text input
  const scannerTextInput = document.getElementById('scannerImage');
  if (scannerTextInput) {
    const updateScanner = () => {
      const val = scannerTextInput.value || 'images/qr_code.png';
      const scannerEl = document.getElementById('d_scannerImage');
      const thumbEl = document.getElementById('scannerThumbPreview');
      if (scannerEl) scannerEl.src = val;
      if (thumbEl) thumbEl.src = val;
    };
    scannerTextInput.addEventListener('input', updateScanner);
    scannerTextInput.addEventListener('change', updateScanner);
  }

  // Dynamic Date Formatting
  const certDateInput = document.getElementById('certDate');
  if (certDateInput) {
    const updateDate = () => {
      const val = certDateInput.value;
      if (!val) return;
      const dateObj = new Date(val + 'T00:00:00');
      if (isNaN(dateObj.getTime())) return;

      const day = dateObj.getDate();
      const suffix = getOrdinal(day);
      const monthNames = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
      const monthStr = monthNames[dateObj.getMonth()];
      const yearStr = dateObj.getFullYear();

      const dayEl = document.getElementById('d_day');
      const monthYearEl = document.getElementById('d_monthYear');
      if (dayEl) dayEl.textContent = day + suffix;
      if (monthYearEl) monthYearEl.textContent = monthStr + ' ' + yearStr;
    };

    certDateInput.addEventListener('input', updateDate);
    certDateInput.addEventListener('change', updateDate);
  }
}

/**
 * Handle student photo and logo uploads with immediate previews and hidden inputs
 */
function setupUploads() {
  // Student Photo Upload
  setupDropZone('studentPhotoInput', 'photoDropZone', (dataUrl) => {
    const photoEl = document.getElementById('d_studentPhoto');
    const placeholderEl = document.getElementById('d_photoPlaceholder');
    const thumbEl = document.getElementById('photoThumbPreview');
    const hiddenEl = document.getElementById('studentPhoto_data');

    if (photoEl) {
      photoEl.src = dataUrl;
      photoEl.style.display = 'block';
    }
    if (placeholderEl) {
      placeholderEl.style.display = 'none';
    }
    if (thumbEl) {
      thumbEl.src = dataUrl;
    }
    if (hiddenEl) {
      hiddenEl.value = dataUrl;
    }
  });

  // Institute Logo Upload
  setupDropZone('logoInput', 'logoDropZone', (dataUrl) => {
    const logoEl = document.getElementById('d_logo');
    const watermarkLogo = document.getElementById('d_watermarkLogo');
    const sidebarLogo = document.getElementById('sidebarLogoImg');
    const thumbEl = document.getElementById('logoThumbPreview');
    const hiddenEl = document.getElementById('instituteLogo_data');

    if (logoEl) logoEl.src = dataUrl;
    if (watermarkLogo) watermarkLogo.src = dataUrl;
    if (sidebarLogo) sidebarLogo.src = dataUrl;
    if (thumbEl) thumbEl.src = dataUrl;
    if (hiddenEl) hiddenEl.value = dataUrl;
  });

  // Footer Seal Upload
  setupDropZone('footerSealInput', 'footerSealDropZone', (dataUrl) => {
    const sealEl = document.getElementById('d_footerSeal');
    const thumbEl = document.getElementById('footerSealThumbPreview');
    const textInput = document.getElementById('footerSeal');
    const hiddenEl = document.getElementById('footerSeal_data');

    if (sealEl) sealEl.src = dataUrl;
    if (thumbEl) thumbEl.src = dataUrl;
    if (textInput) textInput.value = dataUrl;
    if (hiddenEl) hiddenEl.value = dataUrl;
  });

  // Scanner / QR Code Upload
  setupDropZone('scannerImageInput', 'scannerDropZone', (dataUrl) => {
    const scannerEl = document.getElementById('d_scannerImage');
    const thumbEl = document.getElementById('scannerThumbPreview');
    const textInput = document.getElementById('scannerImage');
    const hiddenEl = document.getElementById('scannerImage_data');

    if (scannerEl) scannerEl.src = dataUrl;
    if (thumbEl) thumbEl.src = dataUrl;
    if (textInput) textInput.value = dataUrl;
    if (hiddenEl) hiddenEl.value = dataUrl;
  });
}

function setupDropZone(inputId, zoneId, callback) {
  const inputEl = document.getElementById(inputId);
  const zoneEl = document.getElementById(zoneId);
  if (!inputEl || !zoneEl) return;

  inputEl.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) readFile(file, callback);
  });

  zoneEl.addEventListener('dragover', (e) => {
    e.preventDefault();
    zoneEl.classList.add('dragover');
  });

  zoneEl.addEventListener('dragleave', () => {
    zoneEl.classList.remove('dragover');
  });

  zoneEl.addEventListener('drop', (e) => {
    e.preventDefault();
    zoneEl.classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
      readFile(file, callback);
    }
  });
}

function readFile(file, callback) {
  const reader = new FileReader();
  reader.onload = (e) => callback(e.target.result);
  reader.readAsDataURL(file);
}

/**
 * Print & Export Actions
 */
function setupActions() {
  if (printBtn) {
    printBtn.addEventListener('click', triggerPrint);
  }

  if (openDirectPrintBtn) {
    openDirectPrintBtn.addEventListener('click', () => {
      if (form) {
        form.action = window.diplomaPrintUrl || '/diploma/print';
        form.target = '_blank';
        form.submit();
      }
    });
  }

  if (resetBtn) {
    resetBtn.addEventListener('click', () => {
      if (confirm('Reset all diploma details to default?')) {
        window.location.href = window.diplomaIndexUrl || '/diploma';
      }
    });
  }
}

/**
 * Setup Student Quick Fill
 */
function setupQuickFill() {
  const studentSelect = document.getElementById('studentQuickSelect');
  if (!studentSelect) return;

  studentSelect.addEventListener('change', async (e) => {
    const studentId = e.target.value;
    if (!studentId) return;

    try {
      const res = await fetch(`/diploma/student/${studentId}`);
      if (!res.ok) throw new Error('Failed to fetch student data');
      const data = await res.json();

      function setField(id, val) {
        const inp = document.getElementById(id);
        if (inp && val !== undefined && val !== null) {
          inp.value = val;
          inp.dispatchEvent(new Event('input'));
          inp.dispatchEvent(new Event('change'));
        }
      }

      if (data.gender) setField('gender', data.gender);
      if (data.name) setField('studentName', data.name);
      if (data.father_name) setField('fatherName', data.father_name);
      if (data.enrollment_no) setField('enrollNo', data.enrollment_no);
      if (data.serial_no) setField('serialNo', data.serial_no);
      if (data.course_name) setField('courseName', data.course_name);
      if (data.course_category) setField('courseCategory', data.course_category);
      if (data.location) setField('location', data.location);
      if (data.division) setField('division', data.division);
      if (data.place) setField('place', data.place);
      if (data.cert_date) setField('certDate', data.cert_date);
    } catch (err) {
      console.error('Error autofilling student diploma data:', err);
    }
  });
}

/**
 * Trigger print with guaranteed A4 layout
 */
function triggerPrint() {
  // Reset zoom before printing to avoid scaling artifacts
  const currentScale = zoomScale;
  diplomaWrapper.style.transform = 'none';

  setTimeout(() => {
    window.print();
    // Restore zoom after print dialog closes
    setTimeout(() => {
      applyZoom();
    }, 500);
  }, 100);
}

/**
 * Zoom Controls
 */
function setupZoomControls() {
  if (zoomInBtn) {
    zoomInBtn.addEventListener('click', () => {
      zoomScale = Math.min(ZOOM_MAX, +(zoomScale + ZOOM_STEP).toFixed(1));
      applyZoom();
    });
  }

  if (zoomOutBtn) {
    zoomOutBtn.addEventListener('click', () => {
      zoomScale = Math.max(ZOOM_MIN, +(zoomScale - ZOOM_STEP).toFixed(1));
      applyZoom();
    });
  }
}

function applyZoom() {
  if (diplomaWrapper) {
    diplomaWrapper.style.transform = `scale(${zoomScale})`;
  }
  if (zoomLevelEl) {
    zoomLevelEl.textContent = `${Math.round(zoomScale * 100)}%`;
  }
}

function getOrdinal(n) {
  const s = ['th', 'st', 'nd', 'rd'];
  const v = n % 100;
  return s[(v - 20) % 10] || s[v] || s[0];
}

function formatDivision(val) {
  if (!val) return '';
  const safe = String(val)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;');
  return safe.replace(/(\b\d+)(st|nd|rd|th)\b/gi, '$1<sup>$2</sup>');
}
