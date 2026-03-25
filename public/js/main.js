/**
 * Horas Wedding - UI helpers (sidebar, notifications, formatting)
 */

document.addEventListener('DOMContentLoaded', function () {
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('overlay');

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', function () {
      sidebar.classList.toggle('active');
      if (overlay) overlay.classList.toggle('active');
    });
  }

  if (overlay && sidebar) {
    overlay.addEventListener('click', function () {
      sidebar.classList.remove('active');
      overlay.classList.remove('active');
    });
  }
});

function validateForm(formId) {
  const form = document.getElementById(formId);
  if (!form) return true;

  let isValid = true;
  form.querySelectorAll('[required]').forEach(function (field) {
    if (!field.value.trim()) {
      field.classList.add('is-invalid');
      isValid = false;
    } else {
      field.classList.remove('is-invalid');
      field.classList.add('is-valid');
    }
  });
  return isValid;
}

function clearForm(formId) {
  const form = document.getElementById(formId);
  if (!form) return;
  form.reset();
  form.querySelectorAll('.is-valid, .is-invalid').forEach(function (field) {
    field.classList.remove('is-valid', 'is-invalid');
  });
}

function confirmDelete(itemName) {
  return confirm('Are you sure you want to delete "' + itemName + '"?');
}

function showNotification(message, type) {
  type = type || 'success';
  document.querySelectorAll('.custom-notification').forEach(function (n) {
    n.remove();
  });

  const notification = document.createElement('div');
  notification.className = 'custom-notification alert alert-' + type + ' alert-dismissible fade show';
  notification.innerHTML =
    '<i class="bi bi-info-circle me-2"></i>' +
    message +
    '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
  notification.style.cssText =
    'position:fixed;top:20px;right:20px;z-index:9999;min-width:300px;box-shadow:0 10px 30px rgba(0,0,0,0.2);border-radius:12px;border:none;padding:15px 20px;';
  document.body.appendChild(notification);
  setTimeout(function () {
    notification.remove();
  }, 5000);
}

function formatDate(dateString) {
  if (!dateString) return '-';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { day: 'numeric', month: 'long', year: 'numeric' });
}

function formatCurrency(amount) {
  if (!amount) return 'Rp 0';
  return 'Rp ' + parseInt(amount, 10).toLocaleString('id-ID');
}

window.showNotification = showNotification;
window.validateForm = validateForm;
window.clearForm = clearForm;
window.confirmDelete = confirmDelete;
window.formatDate = formatDate;
window.formatCurrency = formatCurrency;
