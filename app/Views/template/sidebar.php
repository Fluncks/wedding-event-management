<?php
$isAdmin = $isAdmin ?? false;
$name    = $currentUser['name'] ?? 'User';
$initial = $name !== '' ? strtoupper(substr($name, 0, 1)) : '?';
$u       = uri_string() ?? '';
$eventListActive   = $u === 'event-list';
$createEventActive = $u === 'create-event' || preg_match('#^create-event/\d+$#', $u);
$locListActive     = $u === 'location-list';
$createLocActive   = $u === 'create-location' || preg_match('#^create-location/\d+$#', $u);
$dashActive        = $u === 'dashboard' || $u === '';
$staffActive       = str_starts_with($u, 'admin/create-account');
?>
<aside class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <div class="sidebar-logo">
      <i class="bi bi-heart-fill"></i>
    </div>
    <h3>Horas Wedding</h3>
    <p>Staff Portal</p>
  </div>

  <div class="sidebar-menu">
    <div class="menu-category">Main Menu</div>
    <a href="<?= base_url('dashboard') ?>" class="menu-item <?= $dashActive ? 'active' : '' ?>">
      <i class="bi bi-speedometer2"></i>
      <span>Dashboard</span>
    </a>

    <div class="menu-category">Events</div>
    <a href="<?= base_url('event-list') ?>" class="menu-item <?= $eventListActive ? 'active' : '' ?>">
      <i class="bi bi-calendar-event"></i>
      <span>Event List</span>
    </a>
    <a href="<?= base_url('create-event') ?>" class="menu-item <?= $createEventActive ? 'active' : '' ?>">
      <i class="bi bi-plus-circle"></i>
      <span>Create Event</span>
    </a>

    <div class="menu-category">Locations</div>
    <a href="<?= base_url('location-list') ?>" class="menu-item <?= $locListActive ? 'active' : '' ?>">
      <i class="bi bi-geo-alt"></i>
      <span>Location List</span>
    </a>
    <a href="<?= base_url('create-location') ?>" class="menu-item <?= $createLocActive ? 'active' : '' ?>">
      <i class="bi bi-plus-circle"></i>
      <span>Create Location</span>
    </a>

    <?php if ($isAdmin) : ?>
      <div class="menu-category">Staff</div>
      <a href="<?= base_url('admin/create-account') ?>" class="menu-item <?= $staffActive ? 'active' : '' ?>">
        <i class="bi bi-person-plus"></i>
        <span>Create Account</span>
      </a>
    <?php endif; ?>
  </div>

  <div class="sidebar-footer">
    <div class="user-profile">
      <div class="user-avatar" id="userAvatar"><?= esc($initial) ?></div>
      <div class="user-info">
        <h5 id="userName"><?= esc($name) ?></h5>
        <p><?= esc($currentUser['role'] ?? 'staff') ?></p>
      </div>
    </div>
  </div>
</aside>
