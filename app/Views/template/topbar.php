<?php
$pageTitle = $title ?? 'Page';
$crumbs    = $breadcrumbs ?? [
    ['label' => 'Home', 'url' => base_url('dashboard')],
    ['label' => $pageTitle],
];
?>
<header class="topbar">
  <div class="topbar-left">
    <button class="btn-toggle" type="button" id="sidebarToggle" aria-label="Toggle sidebar">
      <i class="bi bi-list"></i>
    </button>
    <div>
      <h2 class="page-title"><?= esc($pageTitle) ?></h2>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <?php foreach ($crumbs as $c) : ?>
            <?php if (isset($c['url']) && $c['url'] !== '') : ?>
              <li class="breadcrumb-item"><a href="<?= esc($c['url']) ?>"><?= esc($c['label']) ?></a></li>
            <?php else : ?>
              <li class="breadcrumb-item active" aria-current="page"><?= esc($c['label']) ?></li>
            <?php endif; ?>
          <?php endforeach; ?>
        </ol>
      </nav>
    </div>
  </div>

  <div class="topbar-right">
    <a class="btn-icon" href="<?= base_url('logout') ?>" title="Logout" role="button">
      <i class="bi bi-box-arrow-right"></i>
    </a>
  </div>
</header>
