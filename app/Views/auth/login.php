<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Horas Wedding</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body class="auth-page">

  <div class="auth-container">
    <div class="auth-card fade-in">
      <div class="auth-header">
        <div class="auth-logo">
          <i class="bi bi-heart-fill"></i>
        </div>
        <h1>Horas Wedding</h1>
        <p>welcome to Horas Wedding - Event Management</p>
      </div>

      <div class="auth-body">
        <?php if ($m = session()->getFlashdata('message')) : ?>
          <div class="alert alert-success"><?= esc($m) ?></div>
        <?php endif; ?>
        <?php if ($e = session()->getFlashdata('error')) : ?>
          <div class="alert alert-danger"><?= esc($e) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('login') ?>">
          <?= csrf_field() ?>
          <div class="form-floating">
            <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" value="<?= esc(old('email')) ?>" required autocomplete="username">
            <label for="email"><i class="bi bi-envelope me-2"></i>Email address</label>
          </div>

          <div class="form-floating">
            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required autocomplete="current-password">
            <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
          </div>

          <button type="submit" class="btn btn-auth mt-2">
            <i class="bi bi-box-arrow-in-right me-2"></i>Login
          </button>
        </form>

        <div class="auth-footer">
          <p>Need an account? Ask your administrator.</p>
        </div>
      </div>
    </div>

    <div class="text-center mt-4" style="color: rgba(255,255,255,0.7); font-size: 13px;">
      <p class="mb-1">&copy; <?= esc(date('Y')) ?> Horas Wedding Event Management</p>
      <p>Horas! Celebrating Batak Traditions</p>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
