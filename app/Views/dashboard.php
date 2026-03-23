<?= $this->extend('template/template') ?>
<?= $this->section('content') ?>

<div class="row stats-row">
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="stat-card">
      <div class="stat-icon red">
        <i class="bi bi-calendar-event"></i>
      </div>
      <div class="stat-content">
        <h3><?= esc((string) $totalEvents) ?></h3>
        <p>Total Events</p>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="stat-card">
      <div class="stat-icon gold">
        <i class="bi bi-geo-alt"></i>
      </div>
      <div class="stat-content">
        <h3><?= esc((string) $totalLocations) ?></h3>
        <p>Total Locations</p>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="stat-card">
      <div class="stat-icon success">
        <i class="bi bi-check-circle"></i>
      </div>
      <div class="stat-content">
        <h3><?= esc((string) $completedEvents) ?></h3>
        <p>Completed Events</p>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6 mb-4">
    <div class="stat-card">
      <div class="stat-icon info">
        <i class="bi bi-hourglass-split"></i>
      </div>
      <div class="stat-content">
        <h3><?= esc((string) $pendingEvents) ?></h3>
        <p>Pending Events</p>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-8 mb-4">
    <div class="content-card">
      <div class="card-header-custom">
        <h4><i class="bi bi-calendar3"></i> Recent Events</h4>
        <a href="<?= base_url('event-list') ?>" class="btn btn-sm btn-outline-custom">View All</a>
      </div>
      <div class="card-body-custom p-0">
        <div class="table-responsive">
          <table class="table table-custom">
            <thead>
              <tr>
                <th>Event</th>
                <th>Couple</th>
                <th>Date</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($recentEvents)) : ?>
                <tr>
                  <td colspan="4" class="text-center py-4 text-muted">No events yet</td>
                </tr>
              <?php else : ?>
                <?php foreach ($recentEvents as $ev) : ?>
                  <tr>
                    <td>
                      <strong><?= esc($ev['name']) ?></strong>
                      <br><small class="text-muted"><?= esc($ev['event_type']) ?></small>
                    </td>
                    <td><?= esc($ev['client_name']) ?></td>
                    <td><?= esc($ev['event_date']) ?></td>
                    <td><span class="status-badge status-<?= esc($ev['status']) ?>"><?= esc($ev['status']) ?></span></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4 mb-4">
    <div class="content-card">
      <div class="card-header-custom">
        <h4><i class="bi bi-lightning"></i> Quick Actions</h4>
      </div>
      <div class="card-body-custom">
        <div class="d-grid gap-3">
          <a href="<?= base_url('create-event') ?>" class="btn btn-primary-custom btn-custom">
            <i class="bi bi-plus-circle"></i> Create New Event
          </a>
          <a href="<?= base_url('create-location') ?>" class="btn btn-gold btn-custom">
            <i class="bi bi-geo-alt"></i> Add New Location
          </a>
          <?php if (! empty($isAdmin)) : ?>
            <a href="<?= base_url('admin/create-account') ?>" class="btn btn-outline-custom btn-custom">
              <i class="bi bi-person-plus"></i> Create Staff Account
            </a>
          <?php endif; ?>
        </div>

        <hr class="my-4">
        <h6 class="mb-3" style="font-weight: 600;">Upcoming Events</h6>
        <?php if (empty($upcomingEvents)) : ?>
          <p class="text-muted small mb-0">No upcoming events</p>
        <?php else : ?>
          <?php foreach ($upcomingEvents as $ev) : ?>
            <div class="d-flex align-items-center mb-3 p-2" style="background: var(--batak-light-gray); border-radius: 10px;">
              <div class="text-center me-3" style="min-width: 50px;">
                <?php $d = strtotime($ev['event_date']); ?>
                <div style="font-size: 20px; font-weight: 700; color: var(--batak-red); line-height: 1;"><?= esc(date('j', $d)) ?></div>
                <div style="font-size: 11px; text-transform: uppercase; color: var(--batak-gray);"><?= esc(date('M', $d)) ?></div>
              </div>
              <div>
                <h6 class="mb-1" style="font-size: 14px; font-weight: 600;"><?= esc($ev['name']) ?></h6>
                <p class="mb-0 text-muted" style="font-size: 12px;"><i class="bi bi-geo-alt me-1"></i><?= esc($ev['location_name']) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="content-card">
      <div class="card-header-custom">
        <h4><i class="bi bi-geo-alt-fill"></i> Locations</h4>
        <a href="<?= base_url('location-list') ?>" class="btn btn-sm btn-outline-custom">View All</a>
      </div>
      <div class="card-body-custom">
        <div class="row">
          <?php if (empty($popularLocations)) : ?>
            <div class="col-12 text-center py-4 text-muted">No locations yet</div>
          <?php else : ?>
            <?php foreach ($popularLocations as $loc) : ?>
              <div class="col-md-4 mb-3">
                <div class="location-card">
                  <div class="location-card-image <?= ! empty($loc['image']) ? 'has-photo' : '' ?>">
                    <?php $pimg = location_image_url($loc['image'] ?? null); ?>
                    <?php if ($pimg) : ?>
                      <img src="<?= esc($pimg) ?>" alt="<?= esc($loc['name']) ?>">
                    <?php else : ?>
                      <i class="bi bi-building"></i>
                    <?php endif; ?>
                  </div>
                  <div class="location-card-body">
                    <h5><?= esc($loc['name']) ?></h5>
                    <p class="text-truncate"><?= esc($loc['address']) ?></p>
                    <div class="location-capacity">
                      <i class="bi bi-people"></i>
                      <span>Capacity: <?= esc((string) $loc['capacity']) ?> guests</span>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
