<?php
$isEdit = ! empty($event);
$eid    = $isEdit ? (int) $event['id'] : 0;
$cl     = $client ?? [];
$et     = $isEdit && ! empty($event['event_time']) ? substr((string) $event['event_time'], 0, 5) : '';
?>
<?= $this->extend('template/template') ?>
<?= $this->section('content') ?>

<div class="content-card">
  <div class="card-header-custom">
    <h4><i class="bi bi-calendar-plus"></i> <?= $isEdit ? 'Edit Event' : 'Create New Event' ?></h4>
  </div>
  <div class="card-body-custom">
    <form action="<?= base_url('crud/event/save') ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="event_id" value="<?= $eid ?>">

      <div class="form-section">
        <h5 class="form-section-title"><i class="bi bi-info-circle"></i> Event Information</h5>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label-custom">Event Name</label>
            <input type="text" name="name" class="form-control form-control-custom" required
              value="<?= esc(old('name', $isEdit ? $event['name'] : '')) ?>">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label-custom">Event Type</label>
            <select name="event_type" class="form-select form-select-custom" required>
              <?php $t = old('event_type', $isEdit ? $event['event_type'] : ''); ?>
              <option value="">Select Event Type</option>
              <option value="Traditional Wedding" <?= $t === 'Traditional Wedding' ? 'selected' : '' ?>>Traditional Wedding (Pesta Adat)</option>
              <option value="Modern Wedding" <?= $t === 'Modern Wedding' ? 'selected' : '' ?>>Modern Wedding</option>
              <option value="Engagement" <?= $t === 'Engagement' ? 'selected' : '' ?>>Engagement (Mangain/Martupol)</option>
              <option value="Reception" <?= $t === 'Reception' ? 'selected' : '' ?>>Reception</option>
              <option value="Blessing Ceremony" <?= $t === 'Blessing Ceremony' ? 'selected' : '' ?>>Blessing Ceremony</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label-custom">Event Date</label>
            <input type="date" name="event_date" class="form-control form-control-custom" required
              value="<?= esc(old('event_date', $isEdit ? $event['event_date'] : '')) ?>">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label-custom">Event Time</label>
            <input type="time" name="event_time" class="form-control form-control-custom" value="<?= esc(old('event_time', $et)) ?>">
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label-custom">Description</label>
          <textarea name="description" class="form-control form-control-custom" rows="3"><?= esc(old('description', $isEdit ? ($event['description'] ?? '') : '')) ?></textarea>
        </div>
      </div>

      <div class="form-section">
        <h5 class="form-section-title"><i class="bi bi-people"></i> Couple (Bride &amp; Groom)</h5>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label-custom">Bride's full name</label>
            <input type="text" name="bride_name" class="form-control form-control-custom" required
              value="<?= esc(old('bride_name', $isEdit ? ($cl['bride_name'] ?? '') : '')) ?>">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label-custom">Groom's full name</label>
            <input type="text" name="groom_name" class="form-control form-control-custom" required
              value="<?= esc(old('groom_name', $isEdit ? ($cl['groom_name'] ?? '') : '')) ?>">
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label-custom">Contact Number</label>
            <input type="tel" name="client_phone" class="form-control form-control-custom"
              value="<?= esc(old('client_phone', $isEdit ? ($cl['phone'] ?? '') : '')) ?>">
          </div>
        </div>
      </div>

      <div class="form-section">
        <h5 class="form-section-title"><i class="bi bi-geo-alt"></i> Location &amp; Capacity</h5>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label-custom">Location</label>
            <select name="location_id" class="form-select form-select-custom" required>
              <option value="">Select Location</option>
              <?php $lid = (int) old('location_id', $isEdit ? $event['location_id'] : 0); ?>
              <?php foreach ($locations as $loc) : ?>
                <option value="<?= (int) $loc['id'] ?>" <?= $lid === (int) $loc['id'] ? 'selected' : '' ?>>
                  <?= esc($loc['name']) ?> (<?= esc((string) $loc['capacity']) ?> guests)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label-custom">Number of Guests</label>
            <input type="number" name="guests" class="form-control form-control-custom" min="1" required
              value="<?= esc(old('guests', $isEdit ? (string) $event['guests'] : '')) ?>">
          </div>
        </div>
      </div>

      <div class="form-section">
        <h5 class="form-section-title"><i class="bi bi-cash"></i> Budget &amp; Status</h5>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label-custom">Budget (Rp)</label>
            <input type="number" name="budget" class="form-control form-control-custom" min="0"
              value="<?= esc(old('budget', $isEdit ? (string) $event['budget'] : '0')) ?>">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label-custom">Event Status</label>
            <?php $st = old('status', $isEdit ? $event['status'] : 'pending'); ?>
            <select name="status" class="form-select form-select-custom" required>
              <option value="pending" <?= $st === 'pending' ? 'selected' : '' ?>>Pending</option>
              <option value="active" <?= $st === 'active' ? 'selected' : '' ?>>Active</option>
              <option value="completed" <?= $st === 'completed' ? 'selected' : '' ?>>Completed</option>
              <option value="cancelled" <?= $st === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
          </div>
        </div>
      </div>

      <div class="d-flex gap-3 mt-4 flex-wrap">
        <button type="submit" class="btn btn-primary-custom btn-custom">
          <i class="bi bi-check-circle"></i> <?= $isEdit ? 'Update Event' : 'Create Event' ?>
        </button>
        <a href="<?= base_url('event-list') ?>" class="btn btn-outline-custom btn-custom">
          <i class="bi bi-arrow-left"></i> Back to List
        </a>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>
