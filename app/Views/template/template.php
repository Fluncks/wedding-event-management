<!DOCTYPE html>
<html lang="en">
<head>
  <?= $this->include('template/head') ?>
</head>
<body>
  <?= $this->include('template/sidebar') ?>

  <div class="overlay" id="overlay"></div>

  <main class="main-content">
    <?= $this->include('template/topbar') ?>

    <div class="content-area px-3 pb-4">
      <?php if ($m = session()->getFlashdata('message')) : ?>
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
          <?= esc($m) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
      <?php if ($e = session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
          <?= esc($e) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <?= $this->renderSection('content') ?>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url('js/main.js') ?>"></script>
  <?= $this->renderSection('scripts') ?>
</body>
</html>
