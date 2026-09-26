/* =====================================================================
 |  BLOSSOM PUBLIC SCHOOL  —  ADMIN PANEL INTERACTIVE SCRIPT
 * ===================================================================== */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Sidebar Desktop Collapse & Mobile Toggle
  const sidebar = document.getElementById('adminSidebar');
  const toggleBtn = document.getElementById('sidebarToggleBtn');
  const wrapper = document.querySelector('.admin-wrapper');

  // Load saved sidebar state for desktop
  if (wrapper && localStorage.getItem('sidebar_collapsed') === 'true' && window.innerWidth > 992) {
    wrapper.classList.add('sidebar-collapsed');
  }

  if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
      if (window.innerWidth <= 992) {
        if (sidebar) sidebar.classList.toggle('show');
      } else {
        if (wrapper) {
          wrapper.classList.toggle('sidebar-collapsed');
          const isCollapsed = wrapper.classList.contains('sidebar-collapsed');
          localStorage.setItem('sidebar_collapsed', isCollapsed ? 'true' : 'false');
        }
      }
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', (e) => {
      if (window.innerWidth <= 992 && sidebar && sidebar.classList.contains('show')) {
        if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
          sidebar.classList.remove('show');
        }
      }
    });
  }

  // 2. Modal Handler
  window.openModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }
  };

  window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.classList.remove('active');
      document.body.style.overflow = '';
    }
  };

  // Close modals when clicking overlay
  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) {
        overlay.classList.remove('active');
        document.body.style.overflow = '';
      }
    });
  });

  // 3. Tab Switching Handler
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const target = btn.dataset.tab;
      const tabGroup = btn.closest('.tabs-container') || document;
      
      tabGroup.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      tabGroup.querySelectorAll('.tab-pane').forEach(p => p.style.display = 'none');

      btn.classList.add('active');
      const activePane = tabGroup.querySelector('#' + target);
      if (activePane) {
        activePane.style.display = 'block';
      }
    });
  });

  // 4. File Upload Image Preview
  document.querySelectorAll('.image-input-preview').forEach(input => {
    input.addEventListener('change', function() {
      const targetPreviewId = this.dataset.preview;
      const previewImg = document.getElementById(targetPreviewId);
      if (previewImg && this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          previewImg.src = e.target.result;
          previewImg.style.display = 'block';
        };
        reader.readAsDataURL(this.files[0]);
      }
    });
  });
});
