<?= $this->extend('template/template') ?>
<?= $this->section('content') ?>

<div class="content-card mb-4">
  <div class="card-body-custom">
    <form method="get" action="<?= base_url('location-list') ?>" class="row g-2 align-items-end">
      <div class="col-md-6">
        <label class="form-label small text-muted mb-1">Search</label>
        <div class="search-box">
          <i class="bi bi-search"></i>
          <input type="text" name="q" class="form-control" value="<?= esc($search) ?>" placeholder="Search locations...">
        </div>
      </div>
      <div class="col-md-6 text-md-end">
        <button type="submit" class="btn btn-primary-custom btn-custom me-2">Search</button>
        <a href="<?= base_url('location-list') ?>" class="btn btn-outline-secondary btn-custom">Reset</a>
        <a href="<?= base_url('create-location') ?>" class="btn btn-primary-custom btn-custom ms-md-2">
          <i class="bi bi-plus-circle"></i> New Location
        </a>
      </div>
    </form>
  </div>
</div>

<div class="row" id="locationsGrid">
  <?php if (empty($locations)) : ?>
    <div class="col-12">
      <div class="empty-state">
        <div class="empty-state-icon"><i class="bi bi-geo-alt"></i></div>
        <h4>No Locations Found</h4>
        <p>Add your first venue</p>
        <a href="<?= base_url('create-location') ?>" class="btn btn-primary-custom btn-custom">
          <i class="bi bi-plus-circle"></i> Add Location
        </a>
      </div>
    </div>
  <?php else : ?>
    <?php foreach ($locations as $loc) : ?>
      <div class="col-xl-4 col-md-6 mb-4">
        <div class="location-card">
          <div class="location-card-image <?= ! empty($loc['image']) ? 'has-photo' : '' ?>">
            <?php $locImg = location_image_url($loc['image'] ?? null); ?>
            <?php if ($locImg) : ?>
              <img src="<?= esc($locImg) ?>" alt="<?= esc($loc['name']) ?>">
            <?php else : ?>
              <i class="bi bi-building"></i>
            <?php endif; ?>
          </div>
          <div class="location-card-body">
            <h5><?= esc($loc['name']) ?></h5>
            <p class="text-truncate"><?= esc($loc['address']) ?></p>
            <div class="d-flex justify-content-between align-items-center mb-3">
              <span class="badge bg-secondary"><?= esc($loc['type']) ?></span>
              <div class="location-capacity">
                <i class="bi bi-people"></i>
                <span><?= esc((string) $loc['capacity']) ?></span>
              </div>
            </div>
            <div class="d-flex gap-2">
              <a class="btn btn-sm btn-view btn-action" href="<?= base_url('create-location/' . $loc['id']) ?>" title="Edit">
                <i class="bi bi-pencil"></i>
              </a>
              <form action="<?= base_url('crud/location/delete/' . $loc['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Delete this location?');">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-sm btn-delete btn-action border-0 bg-transparent p-0" title="Delete">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<div class="content-card">
  <div class="card-header-custom">
    <h4><i class="bi bi-list-ul"></i> All Locations</h4>
  </div>
  <div class="card-body-custom p-0">
    <div class="table-responsive">
      <table class="table table-custom" id="locationsTable">
        <thead>
          <tr>
            <th style="width:72px">Photo</th>
            <th>Name</th>
            <th>Type</th>
            <th>Address</th>
            <th>Capacity</th>
            <th>Contact</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($locations)) : ?>
            <tr>
              <td colspan="7" class="text-center py-4 text-muted">No locations</td>
            </tr>
          <?php else : ?>
            <?php foreach ($locations as $loc) : ?>
              <tr>
                <td>
                  <?php $timg = location_image_url($loc['image'] ?? null); ?>
                  <?php if ($timg) : ?>
                    <img src="<?= esc($timg) ?>" alt="" class="rounded" style="width:56px;height:56px;object-fit:cover;">
                  <?php else : ?>
                    <span class="text-muted small">—</span>
                  <?php endif; ?>
                </td>
                <td><strong><?= esc($loc['name']) ?></strong></td>
                <td><?= esc($loc['type']) ?></td>
                <td><?= esc($loc['address']) ?></td>
                <td><?= esc((string) $loc['capacity']) ?></td>
                <td><?= esc($loc['contact_phone'] ?? '-') ?></td>
                <td>
                  <a class="btn-action btn-edit" href="<?= base_url('create-location/' . $loc['id']) ?>"><i class="bi bi-pencil"></i></a>
                  <form action="<?= base_url('crud/location/delete/' . $loc['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Delete this location?');">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-action btn-delete border-0 bg-transparent p-0"><i class="bi bi-trash"></i></button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
