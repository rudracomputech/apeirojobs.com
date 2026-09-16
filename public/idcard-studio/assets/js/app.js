/**
 * HS Institute ID Card Generator - Main JS Logic
 */

document.addEventListener('DOMContentLoaded', () => {
  // Elements
  const form = document.getElementById('idCardForm');
  const frontCard = document.getElementById('idCardFront');
  const backCard = document.getElementById('idCardBack');
  const viewFrontBtn = document.getElementById('viewFrontBtn');
  const viewBackBtn = document.getElementById('viewBackBtn');
  const viewBothBtn = document.getElementById('viewBothBtn');
  const cardContainer = document.getElementById('cardContainer');
  
  // Input fields
  const inputs = {
    orgName: document.getElementById('org_name'),
    orgSubtitle: document.getElementById('org_subtitle'),
    rollNo: document.getElementById('roll_no'),
    enrollmentNo: document.getElementById('enrollment_no'),
    cardTitle: document.getElementById('card_title'),
    studentName: document.getElementById('student_name'),
    course: document.getElementById('course'),
    session: document.getElementById('session'),
    centerName: document.getElementById('center_name'),
    watermarkText: document.getElementById('watermark_text'),
    signatoryTitle: document.getElementById('signatory_title'),
    emergencyContact: document.getElementById('emergency_contact'),
    bloodGroup: document.getElementById('blood_group'),
    studentAddress: document.getElementById('student_address'),
    validUpto: document.getElementById('valid_upto')
  };

  // Preview elements
  const previews = {
    orgName: document.getElementById('preview_org_name'),
    orgSubtitle: document.getElementById('preview_org_subtitle'),
    rollNo: document.getElementById('preview_roll_no'),
    enrollmentNo: document.getElementById('preview_enrollment_no'),
    cardTitle: document.getElementById('preview_card_title'),
    studentName: document.getElementById('preview_student_name'),
    course: document.getElementById('preview_course'),
    session: document.getElementById('preview_session'),
    centerName: document.getElementById('preview_center_name'),
    watermarkText: document.getElementById('preview_watermark_text'),
    signatoryTitle: document.getElementById('preview_signatory_title'),
    backOrgName: document.getElementById('preview_back_org_name'),
    emergencyContact: document.getElementById('preview_emergency_contact'),
    bloodGroup: document.getElementById('preview_blood_group'),
    studentAddress: document.getElementById('preview_student_address'),
    validUpto: document.getElementById('preview_valid_upto'),
    studentPhoto: document.getElementById('preview_student_photo'),
    orgLogo: document.getElementById('preview_org_logo'),
    stampSignature: document.getElementById('preview_stamp_signature')
  };

  // Update text bindings
  function updateCardPreview() {
    if (previews.orgName && inputs.orgName) {
      previews.orgName.textContent = inputs.orgName.value || 'ALL INDIA COUNCIL FOR VOCATIONAL & PARAMEDICAL SCIENCE';
      if (previews.backOrgName) previews.backOrgName.textContent = inputs.orgName.value;
    }
    if (previews.orgSubtitle && inputs.orgSubtitle) previews.orgSubtitle.textContent = inputs.orgSubtitle.value || '';
    if (previews.rollNo && inputs.rollNo) previews.rollNo.textContent = inputs.rollNo.value || '-';
    if (previews.enrollmentNo && inputs.enrollmentNo) previews.enrollmentNo.textContent = inputs.enrollmentNo.value || '-';
    if (previews.cardTitle && inputs.cardTitle) previews.cardTitle.textContent = inputs.cardTitle.value || 'IDENTITY CARD';
    if (previews.studentName && inputs.studentName) previews.studentName.textContent = inputs.studentName.value || 'Student Name';
    if (previews.course && inputs.course) previews.course.textContent = inputs.course.value || '-';
    if (previews.session && inputs.session) previews.session.textContent = inputs.session.value || '-';
    if (previews.centerName && inputs.centerName) previews.centerName.textContent = inputs.centerName.value || '-';
    if (previews.watermarkText && inputs.watermarkText) previews.watermarkText.textContent = inputs.watermarkText.value || 'HS Institute';
    if (previews.signatoryTitle && inputs.signatoryTitle) previews.signatoryTitle.textContent = inputs.signatoryTitle.value || 'Authorised Signatory';
    
    // Back Side
    if (previews.emergencyContact && inputs.emergencyContact) previews.emergencyContact.textContent = inputs.emergencyContact.value || '-';
    if (previews.bloodGroup && inputs.bloodGroup) previews.bloodGroup.textContent = inputs.bloodGroup.value || '-';
    if (previews.studentAddress && inputs.studentAddress) previews.studentAddress.textContent = inputs.studentAddress.value || '-';
    if (previews.validUpto && inputs.validUpto) previews.validUpto.textContent = inputs.validUpto.value || '-';

    // Generate/update QR code
    generateQRCode();
  }

  // Attach live input listeners
  Object.values(inputs).forEach(input => {
    if (input) {
      input.addEventListener('input', updateCardPreview);
      input.addEventListener('change', updateCardPreview);
    }
  });

  // Handle Preset Course Selection
  const coursePreset = document.getElementById('course_preset');
  if (coursePreset) {
    coursePreset.addEventListener('change', (e) => {
      if (e.target.value && e.target.value !== 'custom') {
        inputs.course.value = e.target.value;
        updateCardPreview();
      }
    });
  }

  // Image Upload Handlers
  function handleImageUpload(inputEl, previewImgEl, thumbPreviewEl) {
    if (!inputEl) return;
    inputEl.addEventListener('change', function(e) {
      const file = this.files[0];
      if (file) {
        if (!file.type.startsWith('image/')) {
          showToast('Please select a valid image file (PNG, JPG, WEBP)', 'danger');
          return;
        }
        const reader = new FileReader();
        reader.onload = function(evt) {
          if (previewImgEl) previewImgEl.src = evt.target.result;
          if (thumbPreviewEl) thumbPreviewEl.src = evt.target.result;
          if (inputEl.id === 'photo_upload') {
            const photoDataInput = document.getElementById('photo_data');
            if (photoDataInput) photoDataInput.value = evt.target.result;
          }
        };
        reader.readAsDataURL(file);
      }
    });
  }

  handleImageUpload(
    document.getElementById('photo_upload'),
    previews.studentPhoto,
    document.getElementById('thumb_photo_preview')
  );

  handleImageUpload(
    document.getElementById('logo_upload'),
    previews.orgLogo,
    document.getElementById('thumb_logo_preview')
  );

  handleImageUpload(
    document.getElementById('stamp_upload'),
    previews.stampSignature,
    document.getElementById('thumb_stamp_preview')
  );

  // Student Quick Select Handler
  const studentSelect = document.getElementById('studentQuickSelect');
  if (studentSelect) {
    studentSelect.addEventListener('change', async (e) => {
      const studentId = e.target.value;
      if (!studentId) return;

      try {
        const res = await fetch(`/idcard/student/${studentId}`);
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

        if (data.student_name) setField('student_name', data.student_name);
        if (data.roll_no) setField('roll_no', data.roll_no);
        if (data.enrollment_no) setField('enrollment_no', data.enrollment_no);
        if (data.course) setField('course', data.course);
        if (data.session) setField('session', data.session);
        if (data.center_name) setField('center_name', data.center_name);
        if (data.watermark_text) setField('watermark_text', data.watermark_text);
        if (data.emergency_contact) setField('emergency_contact', data.emergency_contact);
        if (data.blood_group) setField('blood_group', data.blood_group);
        if (data.student_address) setField('student_address', data.student_address);
        if (data.valid_upto) setField('valid_upto', data.valid_upto);

        updateCardPreview();
        showToast(`Loaded student ${data.student_name} details!`, 'success');
      } catch (err) {
        console.error('Error autofilling student id card:', err);
        showToast('Error loading student details', 'danger');
      }
    });
  }

  // QR Code generator
  function generateQRCode() {
    const qrContainer = document.getElementById('qrcode_container');
    if (!qrContainer) return;
    qrContainer.innerHTML = '';
    
    const qrData = JSON.stringify({
      roll: inputs.rollNo ? inputs.rollNo.value : '',
      enroll: inputs.enrollmentNo ? inputs.enrollmentNo.value : '',
      name: inputs.studentName ? inputs.studentName.value : '',
      course: inputs.course ? inputs.course.value : '',
      center: inputs.centerName ? inputs.centerName.value : ''
    });

    if (typeof QRCode !== 'undefined') {
      new QRCode(qrContainer, {
        text: qrData,
        width: 64,
        height: 64,
        colorDark: '#0f172a',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.M
      });
    }
  }

  // View Switcher (Front / Back / Both)
  if (viewFrontBtn && viewBackBtn && viewBothBtn) {
    viewFrontBtn.addEventListener('click', () => {
      viewFrontBtn.classList.add('active');
      viewBackBtn.classList.remove('active');
      viewBothBtn.classList.remove('active');
      frontCard.style.display = 'block';
      backCard.style.display = 'none';
      cardContainer.className = 'id-cards-display single-view';
    });

    viewBackBtn.addEventListener('click', () => {
      viewBackBtn.classList.add('active');
      viewFrontBtn.classList.remove('active');
      viewBothBtn.classList.remove('active');
      frontCard.style.display = 'none';
      backCard.style.display = 'block';
      cardContainer.className = 'id-cards-display single-view';
    });

    viewBothBtn.addEventListener('click', () => {
      viewBothBtn.classList.add('active');
      viewFrontBtn.classList.remove('active');
      viewBackBtn.classList.remove('active');
      frontCard.style.display = 'block';
      backCard.style.display = 'block';
      cardContainer.className = 'id-cards-display dual-view';
    });
  }

  // Load Sample Data from Reference Image
  const sampleBtn = document.getElementById('loadSampleBtn');
  if (sampleBtn) {
    sampleBtn.addEventListener('click', () => {
      inputs.orgName.value = 'ALL INDIA COUNCIL FOR VOCATIONAL & PARAMEDICAL SCIENCE';
      inputs.orgSubtitle.value = 'GOVERNMENT RECOGNIZED AUTONOMOUS BODY';
      inputs.rollNo.value = '20235801';
      inputs.enrollmentNo.value = 'ACI2023233750';
      inputs.cardTitle.value = 'IDENTITY CARD';
      inputs.studentName.value = 'Laxman Patole';
      inputs.course.value = 'Diploma in General Nursing And Midwifery (GNM)';
      inputs.session.value = 'Jun 2023 - Jun 2025';
      inputs.centerName.value = 'HS Institute Powered by NBS Welfare Foundation.org';
      inputs.watermarkText.value = 'HS Institute Powered by NBS Welfare Foundation.org';
      inputs.signatoryTitle.value = 'Authorised Signatory';
      inputs.emergencyContact.value = '+91 98765 43210';
      inputs.bloodGroup.value = 'B+';
      inputs.studentAddress.value = 'At Post Pune, Maharashtra, India - 411001';
      inputs.validUpto.value = 'June 2025';
      
      // Update preview
      updateCardPreview();
      showToast('Loaded exact sample data from reference ID card!', 'success');
    });
  }

  // Clear Form
  const resetBtn = document.getElementById('resetFormBtn');
  if (resetBtn) {
    resetBtn.addEventListener('click', () => {
      if (confirm('Are you sure you want to clear the form?')) {
        form.reset();
        updateCardPreview();
        showToast('Form reset successfully', 'info');
      }
    });
  }

  // Export as High-Resolution PNG
  const downloadPngBtn = document.getElementById('downloadPngBtn');
  if (downloadPngBtn) {
    downloadPngBtn.addEventListener('click', async () => {
      const activeCard = (frontCard.style.display !== 'none') ? frontCard : backCard;
      showToast('Generating high-resolution ID card image...', 'info');
      
      try {
        if (typeof html2canvas === 'undefined') {
          await loadScript('https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js');
        }
        
        const canvas = await html2canvas(activeCard, {
          scale: 3, // 300DPI crisp resolution
          useCORS: true,
          allowTaint: true,
          backgroundColor: '#ffffff'
        });

        const studentName = (inputs.studentName.value || 'Student').replace(/[^a-z0-9]/gi, '_').toLowerCase();
        const side = (activeCard === frontCard) ? 'front' : 'back';
        const link = document.createElement('a');
        link.download = `${studentName}_id_card_${side}.png`;
        link.href = canvas.toDataURL('image/png');
        link.click();
        showToast('ID Card PNG downloaded successfully!', 'success');
      } catch (err) {
        console.error('PNG download error:', err);
        showToast('Error generating PNG. Please check browser permissions.', 'danger');
      }
    });
  }

  // Export as PDF
  const downloadPdfBtn = document.getElementById('downloadPdfBtn');
  if (downloadPdfBtn) {
    downloadPdfBtn.addEventListener('click', async () => {
      showToast('Generating print-ready PDF document...', 'info');
      
      try {
        if (typeof html2canvas === 'undefined') {
          await loadScript('https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js');
        }
        if (typeof window.jspdf === 'undefined') {
          await loadScript('https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js');
        }

        const { jsPDF } = window.jspdf;
        // Standard CR-80 card size: 85.6mm x 54mm (landscape)
        const pdf = new jsPDF({
          orientation: 'landscape',
          unit: 'mm',
          format: [85.6, 54]
        });

        // Render Front
        const canvasFront = await html2canvas(frontCard, {
          scale: 3,
          useCORS: true,
          allowTaint: true,
          backgroundColor: '#ffffff'
        });
        const imgDataFront = canvasFront.toDataURL('image/jpeg', 0.98);
        pdf.addImage(imgDataFront, 'JPEG', 0, 0, 85.6, 54);

        // If back card is available, add page 2
        if (backCard) {
          const originalDisplay = backCard.style.display;
          backCard.style.display = 'block';
          const canvasBack = await html2canvas(backCard, {
            scale: 3,
            useCORS: true,
            allowTaint: true,
            backgroundColor: '#ffffff'
          });
          backCard.style.display = originalDisplay;
          
          pdf.addPage([85.6, 54], 'landscape');
          const imgDataBack = canvasBack.toDataURL('image/jpeg', 0.98);
          pdf.addImage(imgDataBack, 'JPEG', 0, 0, 85.6, 54);
        }

        const studentName = (inputs.studentName.value || 'Student').replace(/[^a-z0-9]/gi, '_').toLowerCase();
        pdf.save(`${studentName}_id_card.pdf`);
        showToast('ID Card PDF downloaded successfully!', 'success');
      } catch (err) {
        console.error('PDF error:', err);
        showToast('Error generating PDF', 'danger');
      }
    });
  }

  // Direct Browser Print
  const printBtn = document.getElementById('printCardBtn');
  if (printBtn) {
    printBtn.addEventListener('click', () => {
      window.print();
    });
  }

  // Toast Notification helper
  function showToast(message, type = 'info') {
    let container = document.querySelector('.toast-container');
    if (!container) {
      container = document.createElement('div');
      container.className = 'toast-container';
      document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `<span>${message}</span>`;
    container.appendChild(toast);

    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transition = 'opacity 0.4s';
      setTimeout(() => toast.remove(), 400);
    }, 3500);
  }

  // Dynamic script loader utility
  function loadScript(src) {
    return new Promise((resolve, reject) => {
      const script = document.createElement('script');
      script.src = src;
      script.onload = resolve;
      script.onerror = reject;
      document.head.appendChild(script);
    });
  }

  // Initialize on start
  updateCardPreview();
});
