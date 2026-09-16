/**
 * Admit Card Generator - Client-side Live Synchronization & Interactivity
 */

document.addEventListener('DOMContentLoaded', () => {
  const assetBase = window.admitcardAssetBase || '/admitcard-studio/assets/';
  const printUrl = window.admitcardPrintUrl || '/admitcard/print';

  // Mapping between Form Inputs and Certificate Preview Elements
  const fieldBindings = [
    { inputId: 'inp_rollNo', certId: 'cert_rollNo' },
    { inputId: 'inp_enrollmentNo', certId: 'cert_enrollmentNo' },
    { inputId: 'inp_councilName', certId: 'cert_councilName' },
    { inputId: 'inp_bullet1', certId: 'cert_bullet1' },
    { inputId: 'inp_bullet2', certId: 'cert_bullet2' },
    { inputId: 'inp_bullet3', certId: 'cert_bullet3' },
    { inputId: 'inp_bullet4', certId: 'cert_bullet4' },
    { inputId: 'inp_cardTitle', certId: 'cert_cardTitle' },
    { inputId: 'inp_studentName', certId: 'cert_studentName' },
    { inputId: 'inp_parentName', certId: 'cert_parentName' },
    { inputId: 'inp_batch', certId: 'cert_batch' },
    { inputId: 'inp_courseName', certId: 'cert_courseName' },
    { inputId: 'inp_passYear', certId: 'cert_passYear' },
    { inputId: 'inp_examCentre', certId: 'cert_examCentre' },
    { inputId: 'inp_disclaimer', certId: 'cert_disclaimer' },
    { inputId: 'inp_candidateSigTitle', certId: 'cert_candidateSigTitle' },
    { inputId: 'inp_coordinatorTitle', certId: 'cert_coordinatorTitle' },
    { inputId: 'inp_controllerTitle', certId: 'cert_controllerTitle' }
  ];

  // Attach live input listeners for real-time preview updates
  fieldBindings.forEach(binding => {
    const inputEl = document.getElementById(binding.inputId);
    const certEl = document.getElementById(binding.certId);

    if (inputEl && certEl) {
      inputEl.addEventListener('input', () => {
        certEl.textContent = inputEl.value;
      });
    }
  });

  // Dynamic Repeating Watermark Text Sync from Council Main Heading
  const councilInput = document.getElementById('inp_councilName');
  const watermarkPatternText = document.getElementById('cert_watermarkPatternText');

  function updateWatermarkPattern(text) {
    if (!watermarkPatternText) return;
    const clean = (text || 'All India Council For Vocational & Paramedical Science').toUpperCase().trim();
    let repeated = clean + ' • ';
    while (repeated.length < 180) {
      repeated += clean + ' • ';
    }
    watermarkPatternText.textContent = repeated;
  }

  if (councilInput) {
    councilInput.addEventListener('input', () => {
      updateWatermarkPattern(councilInput.value);
    });
  }

  // Candidate Photo Upload & Preview
  const photoInput = document.getElementById('inp_photoUpload');
  const photoPreviewThumb = document.getElementById('photoPreviewThumb');
  const certPhotoImg = document.getElementById('cert_photoImg');
  const photoHiddenInput = document.getElementById('inp_photoBase64');
  const btnResetPhoto = document.getElementById('btnResetPhoto');
  const defaultPhotoSrc = assetBase + 'student-photo.png';

  if (photoInput) {
    photoInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file) {
        if (!file.type.startsWith('image/')) {
          alert('Please upload a valid image file (JPG, PNG, or WEBP).');
          return;
        }

        const reader = new FileReader();
        reader.onload = (event) => {
          const dataUrl = event.target.result;
          if (certPhotoImg) certPhotoImg.src = dataUrl;
          if (photoPreviewThumb) photoPreviewThumb.src = dataUrl;
          if (photoHiddenInput) photoHiddenInput.value = dataUrl;
        };
        reader.readAsDataURL(file);
      }
    });
  }

  if (btnResetPhoto) {
    btnResetPhoto.addEventListener('click', () => {
      if (photoInput) photoInput.value = '';
      if (certPhotoImg) certPhotoImg.src = defaultPhotoSrc;
      if (photoPreviewThumb) photoPreviewThumb.src = defaultPhotoSrc;
      if (photoHiddenInput) photoHiddenInput.value = '';
    });
  }

  // Council Logo Emblem Upload & Preview
  const logoInput = document.getElementById('inp_logoUpload');
  const logoPreviewThumb = document.getElementById('logoPreviewThumb');
  const certTopLogo = document.getElementById('cert_topLogo');
  const certWatermarkCrest = document.getElementById('cert_watermarkCrest');
  const logoHiddenInput = document.getElementById('inp_logoBase64');
  const btnResetLogo = document.getElementById('btnResetLogo');
  const defaultLogoSrc = assetBase + 'aicvps-logo.png';

  if (logoInput) {
    logoInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file) {
        if (!file.type.startsWith('image/')) {
          alert('Please upload a valid logo image file (PNG, SVG, JPG, or WEBP).');
          return;
        }

        const reader = new FileReader();
        reader.onload = (event) => {
          const dataUrl = event.target.result;
          if (certTopLogo) certTopLogo.src = dataUrl;
          if (certWatermarkCrest) certWatermarkCrest.src = dataUrl;
          if (logoPreviewThumb) logoPreviewThumb.src = dataUrl;
          if (logoHiddenInput) logoHiddenInput.value = dataUrl;
        };
        reader.readAsDataURL(file);
      }
    });
  }

  if (btnResetLogo) {
    btnResetLogo.addEventListener('click', () => {
      if (logoInput) logoInput.value = '';
      if (certTopLogo) certTopLogo.src = defaultLogoSrc;
      if (certWatermarkCrest) certWatermarkCrest.src = defaultLogoSrc;
      if (logoPreviewThumb) logoPreviewThumb.src = defaultLogoSrc;
      if (logoHiddenInput) logoHiddenInput.value = '';
    });
  }

  // Stamp / Signature Visibility Toggle
  const toggleStamp = document.getElementById('inp_showStamp');
  const stampWrap = document.getElementById('cert_stampWrap');
  if (toggleStamp && stampWrap) {
    toggleStamp.addEventListener('change', () => {
      stampWrap.style.display = toggleStamp.checked ? 'block' : 'none';
    });
  }

  // Official Stamp Upload Handling
  const stampInput = document.getElementById('inp_stampUpload');
  const stampPreviewThumb = document.getElementById('stampPreviewThumb');
  const certStampImg = document.getElementById('cert_stampImg');
  const stampHiddenInput = document.getElementById('inp_stampBase64');
  const btnResetStamp = document.getElementById('btnResetStamp');
  const defaultStampSrc = assetBase + 'official-stamp.svg';

  if (stampInput) {
    stampInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (ev) => {
          const dataUrl = ev.target.result;
          if (certStampImg) certStampImg.src = dataUrl;
          if (stampPreviewThumb) stampPreviewThumb.src = dataUrl;
          if (stampHiddenInput) stampHiddenInput.value = dataUrl;
        };
        reader.readAsDataURL(file);
      }
    });
  }

  if (btnResetStamp) {
    btnResetStamp.addEventListener('click', () => {
      if (stampInput) stampInput.value = '';
      if (certStampImg) certStampImg.src = defaultStampSrc;
      if (stampPreviewThumb) stampPreviewThumb.src = defaultStampSrc;
      if (stampHiddenInput) stampHiddenInput.value = '';
    });
  }

  // Reset Form to Reference Image Defaults
  const btnResetDefaults = document.getElementById('btnResetDefaults');
  if (btnResetDefaults) {
    btnResetDefaults.addEventListener('click', (e) => {
      e.preventDefault();
      if (confirm('Reset all fields to reference Admit Card defaults?')) {
        const defaults = {
          inp_rollNo: '20235801',
          inp_enrollmentNo: 'ACI2023233750',
          inp_councilName: 'All India Council For Vocational & Paramedical Science',
          inp_bullet1: 'Run by All India Council for Vocational & Paramedical Science',
          inp_bullet2: 'Regd. Under MSME, Govt. of India',
          inp_bullet3: 'An Autonomous Institution Registered Under the Trust Act of 1882',
          inp_bullet4: 'AN ISO 9001 : 2008 Certified Organization',
          inp_cardTitle: 'Admit Card',
          inp_studentName: 'Laxman Patole',
          inp_parentName: 'Balasaheb Patole',
          inp_batch: 'June 2023',
          inp_courseName: 'Diploma in General Nursing And Midwifery (GNM)',
          inp_passYear: '2025',
          inp_examCentre: 'HS Institute Powered by NBS Welfare Foundation',
          inp_candidateSigTitle: 'Candidate Signature',
          inp_coordinatorTitle: 'Centre Coordinator',
          inp_controllerTitle: 'Examination Controller',
          inp_disclaimer: 'Disclaimer : In case of any change in the Examination Schedule, the Examination schedule placed on AICVPS website will be Treated as Final.'
        };

        for (const [id, val] of Object.entries(defaults)) {
          const el = document.getElementById(id);
          if (el) {
            el.value = val;
            el.dispatchEvent(new Event('input'));
          }
        }

        if (btnResetPhoto) btnResetPhoto.click();
        if (btnResetLogo) btnResetLogo.click();
        if (btnResetStamp) btnResetStamp.click();
        if (toggleStamp) {
          toggleStamp.checked = true;
          toggleStamp.dispatchEvent(new Event('change'));
        }

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
        const res = await fetch(`/admitcard/student/${studentId}`);
        if (!res.ok) throw new Error('Failed to fetch student data');
        const data = await res.json();

        function setField(id, val) {
          const inp = document.getElementById(id);
          if (inp && val !== undefined && val !== null) {
            inp.value = val;
            inp.dispatchEvent(new Event('input'));
          }
        }

        if (data.name) setField('inp_studentName', data.name);
        if (data.father_name) setField('inp_parentName', data.father_name);
        if (data.course_name) setField('inp_courseName', data.course_name);
        if (data.enrollment_no) setField('inp_enrollmentNo', data.enrollment_no);
        if (data.roll_no) setField('inp_rollNo', data.roll_no);
        if (data.batch) setField('inp_batch', data.batch);
        if (data.pass_year) setField('inp_passYear', data.pass_year);
      } catch (err) {
        console.error('Error autofilling student admit card:', err);
      }
    });
  }

  // Auto-fit Zoom Controls
  const certSheet = document.getElementById('admitcardSheet');
  const certViewport = document.querySelector('.cert-viewport');
  const zoomInBtn = document.getElementById('btnZoomIn');
  const zoomOutBtn = document.getElementById('btnZoomOut');
  const zoomResetBtn = document.getElementById('btnZoomReset');
  let currentZoom = 1.0;

  function applyZoom(zoom) {
    currentZoom = Math.max(0.5, Math.min(1.5, zoom));
    if (certSheet) {
      certSheet.style.transform = `scale(${currentZoom})`;
      certSheet.style.transformOrigin = 'top center';
      if (certViewport) {
        const scaledHeight = 720 * currentZoom + 48;
        certViewport.style.minHeight = `${scaledHeight}px`;
      }
    }
  }

  function autoFitPreview() {
    if (certViewport && certSheet) {
      const availWidth = certViewport.clientWidth - 48;
      if (availWidth > 0 && availWidth < 1040) {
        const fitScale = (availWidth / 1040) * 0.98;
        applyZoom(Math.min(1.0, Math.max(0.6, fitScale)));
      } else {
        applyZoom(1.0);
      }
    }
  }

  window.addEventListener('resize', autoFitPreview);
  setTimeout(autoFitPreview, 100);

  if (zoomInBtn) zoomInBtn.addEventListener('click', () => applyZoom(currentZoom + 0.08));
  if (zoomOutBtn) zoomOutBtn.addEventListener('click', () => applyZoom(currentZoom - 0.08));
  if (zoomResetBtn) zoomResetBtn.addEventListener('click', () => applyZoom(1.0));

  // Quick Print Action
  const btnPrintQuick = document.getElementById('btnPrintQuick');
  if (btnPrintQuick) {
    btnPrintQuick.addEventListener('click', () => {
      window.print();
    });
  }

  // Open Clean Print View Handler
  const btnOpenPrintView = document.getElementById('btnOpenPrintView');
  if (btnOpenPrintView) {
    btnOpenPrintView.addEventListener('click', (e) => {
      e.preventDefault();
      const form = document.getElementById('admitcardForm');
      if (form) {
        form.action = printUrl;
        form.target = '_blank';
        form.method = 'POST';
        form.submit();
      }
    });
  }
});
