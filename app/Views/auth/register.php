<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Horas Wedding Event Management</title>
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-page">
  
  <div class="auth-container">
    <div class="auth-card fade-in">
      <!-- Header -->
      <div class="auth-header">
        <div class="auth-logo">
          <i class="bi bi-person-plus-fill"></i>
        </div>
        <h1>Create Account</h1>
        <p>Join Horas Wedding Staff Portal</p>
      </div>
      
      <!-- Body -->
      <div class="auth-body">
        <form id="registerForm">
          <div class="form-floating">
            <input type="text" class="form-control" id="fullName" placeholder="Full Name" required>
            <label for="fullName"><i class="bi bi-person me-2"></i>Full Name</label>
          </div>
          
          <div class="form-floating">
            <input type="email" class="form-control" id="email" placeholder="name@example.com" required>
            <label for="email"><i class="bi bi-envelope me-2"></i>Email address</label>
          </div>
          
          <div class="form-floating">
            <input type="tel" class="form-control" id="phone" placeholder="Phone Number" required>
            <label for="phone"><i class="bi bi-telephone me-2"></i>Phone Number</label>
          </div>
          
          <div class="form-floating">
            <input type="password" class="form-control" id="password" placeholder="Password" required minlength="6">
            <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
          </div>
          
          <div class="form-floating">
            <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm Password" required>
            <label for="confirmPassword"><i class="bi bi-lock-fill me-2"></i>Confirm Password</label>
          </div>
          
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="agreeTerms" required>
            <label class="form-check-label" for="agreeTerms" style="font-size: 13px; color: var(--batak-gray);">
              I agree to the <a href="#" style="color: var(--batak-red);">Terms of Service</a> and <a href="#" style="color: var(--batak-red);">Privacy Policy</a>
            </label>
          </div>
          
          <button type="submit" class="btn btn-auth">
            <i class="bi bi-person-check me-2"></i>Register
          </button>
        </form>
        
        <div class="auth-footer">
          <p>Already have an account? <a href="index.html">Login here</a></p>
        </div>
      </div>
    </div>
    
    <!-- Footer -->
    <div class="text-center mt-4" style="color: rgba(255,255,255,0.7); font-size: 13px;">
      <p class="mb-1">&copy; 2024 Horas Wedding Event Management</p>
      <p>Horas! Celebrating Batak Traditions</p>
    </div>
  </div>
  
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Custom JS -->
  <script src="js/main.js"></script>
  
  <script>
    document.getElementById('registerForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const fullName = document.getElementById('fullName').value;
      const email = document.getElementById('email').value;
      const phone = document.getElementById('phone').value;
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirmPassword').value;
      
      // Validate passwords match
      if (password !== confirmPassword) {
        showNotification('Passwords do not match!', 'danger');
        return;
      }
      
      // Create user object
      const user = {
        name: fullName,
        email: email,
        phone: phone,
        password: password,
        role: 'staff'
      };
      
      // Add user
      const result = UserManager.add(user);
      
      if (result.success) {
        showNotification('Registration successful! Please login.', 'success');
        setTimeout(() => {
          window.location.href = 'index.html';
        }, 1500);
      } else {
        showNotification(result.message, 'danger');
      }
    });
  </script>
</body>
</html>
