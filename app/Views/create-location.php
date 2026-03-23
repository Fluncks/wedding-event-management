<?php
$isEdit = ! empty($location);
$lid    = $isEdit ? (int) $location['id'] : 0;
?>
<?= $this->extend('template/template') ?>
<?= $this->section('content') ?>

<div class="row">
  <div class="col-lg-8">
    <div class="content-card">
      <div class="card-header-custom">
        <h4><i class="bi bi-geo-alt-plus"></i> <?= $isEdit ? 'Edit Location' : 'Add New Location' ?></h4>
      </div>
      <div class="card-body-custom">
        <form action="<?= base_url('crud/location/save') ?>" method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <input type="hidden" name="location_id" value="<?= $lid ?>">

          <div class="form-section">
            <h5 class="form-section-title"><i class="bi bi-info-circle"></i> Location Information</h5>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label-custom">Location Name</label>
                <input type="text" name="name" class="form-control form-control-custom" required
                  value="<?= esc(old('name', $isEdit ? $location['name'] : '')) ?>">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label-custom">Location Type</label>
                <?php $ty = old('type', $isEdit ? $location['type'] : ''); ?>
                <select name="type" class="form-select form-select-custom" required>
                  <option value="">Select Type</option>
                  <option value="Indoor Hall" <?= $ty === 'Indoor Hall' ? 'selected' : '' ?>>Indoor Hall (Aula)</option>
                  <option value="Hotel Ballroom" <?= $ty === 'Hotel Ballroom' ? 'selected' : '' ?>>Hotel Ballroom</option>
                  <option value="Outdoor Garden" <?= $ty === 'Outdoor Garden' ? 'selected' : '' ?>>Outdoor Garden</option>
                  <option value="Restaurant" <?= $ty === 'Restaurant' ? 'selected' : '' ?>>Restaurant</option>
                  <option value="Community Center" <?= $ty === 'Community Center' ? 'selected' : '' ?>>Community Center</option>
                  <option value="Private Residence" <?= $ty === 'Private Residence' ? 'selected' : '' ?>>Private Residence</option>
                </select>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label-custom">Address</label>
              <textarea name="address" class="form-control form-control-custom" rows="2" required><?= esc(old('address', $isEdit ? $location['address'] : '')) ?></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label-custom">Photo (optional)</label>
              <input type="file" name="image" class="form-control form-control-custom" accept="image/jpeg,image/png,image/gif,image/webp">
              <div class="form-text">JPG, PNG, GIF, or WebP. Max 3MB.</div>
              <?php if ($isEdit && ! empty($location['image'])) : ?>
                <div class="mt-2">
                  <p class="small text-muted mb-1">Current photo:</p>
                  <?php $imgUrl = location_image_url($location['image']); ?>
                  <?php if ($imgUrl) : ?>
                    <img src="<?= esc($imgUrl) ?>" alt="" class="rounded" style="max-height: 140px; max-width: 100%; object-fit: cover;">
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label-custom">Contact Number</label>
                <input type="tel" name="contact_phone" class="form-control form-control-custom"
                  value="<?= esc(old('contact_phone', $isEdit ? ($location['contact_phone'] ?? '') : '')) ?>">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label-custom">Capacity (Guests)</label>
                <input type="number" name="capacity" class="form-control form-control-custom" min="1" required
                  value="<?= esc(old('capacity', $isEdit ? (string) $location['capacity'] : '')) ?>">
              </div>
            </div>
          </div>

          <div class="form-section">
            <h5 class="form-section-title"><i class="bi bi-stars"></i> Facilities &amp; Description</h5>
            <div class="mb-3">
              <label class="form-label-custom">Facilities (comma-separated)</label>
              <input type="text" name="facilities" class="form-control form-control-custom" placeholder="AC, Parking, Sound System"
                value="<?= esc(old('facilities', $isEdit ? ($location['facilities'] ?? '') : '')) ?>">
            </div>
            <div class="mb-3">
              <label class="form-label-custom">Description</label>
              <textarea name="description" class="form-control form-control-custom" rows="3"><?= esc(old('description', $isEdit ? ($location['description'] ?? '') : '')) ?></textarea>
            </div>
          </div>

          <div class="d-flex gap-3 mt-4 flex-wrap">
            <button type="submit" class="btn btn-primary-custom btn-custom">
              <i class="bi bi-check-circle"></i> <?= $isEdit ? 'Update Location' : 'Save Location' ?>
            </button>
            <a href="<?= base_url('location-list') ?>" class="btn btn-outline-custom btn-custom">
              <i class="bi bi-arrow-left"></i> Back to List
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="content-card">
      <div class="card-header-custom">
        <h4><i class="bi bi-lightbulb"></i> Tips</h4>
      </div>
      <div class="card-body-custom">
        <p class="text-muted small mb-0">Add accurate capacity and contact details so events can be planned safely.</p>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
