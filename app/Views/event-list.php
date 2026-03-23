<?= $this->extend('template/template') ?>
<?= $this->section('content') ?>

<div class="content-card mb-4">
  <div class="card-body-custom">
    <form method="get" action="<?= base_url('event-list') ?>" class="row g-2 align-items-end">
      <div class="col-md-6">
        <label class="form-label small text-muted mb-1">Search</label>
        <div class="search-box">
          <i class="bi bi-search"></i>
          <input type="text" name="q" class="form-control" value="<?= esc($search) ?>" placeholder="Search events...">
        </div>
      </div>
      <div class="col-md-6 text-md-end">
        <button type="submit" class="btn btn-primary-custom btn-custom me-2">Search</button>
        <a href="<?= base_url('event-list') ?>" class="btn btn-outline-secondary btn-custom">Reset</a>
        <a href="<?= base_url('create-event') ?>" class="btn btn-primary-custom btn-custom ms-md-2">
          <i class="bi bi-plus-circle"></i> New Event
        </a>
      </div>
    </form>
  </div>
</div>

<div class="content-card">
  <div class="card-header-custom">
    <h4><i class="bi bi-list-ul"></i> All Events</h4>
  </div>
  <div class="card-body-custom p-0">
    <div class="table-responsive">
      <table class="table table-custom" id="eventsTable">
        <thead>
          <tr>
            <th>Event Name</th>
            <th>Couple (Bride &amp; Groom)</th>
            <th>Type</th>
            <th>Date</th>
            <th>Location</th>
            <th>Guests</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($events)) : ?>
            <tr>
              <td colspan="8" class="text-center py-4 text-muted">No events found</td>
            </tr>
          <?php else : ?>
            <?php foreach ($events as $ev) : ?>
              <tr>
                <td><strong><?= esc($ev['name']) ?></strong></td>
                <td><?= esc($ev['client_name']) ?></td>
                <td><?= esc($ev['event_type']) ?></td>
                <td><?= esc($ev['event_date']) ?></td>
                <td><?= esc($ev['location_name']) ?></td>
                <td><?= esc((string) $ev['guests']) ?></td>
                <td><span class="status-badge status-<?= esc($ev['status']) ?>"><?= esc($ev['status']) ?></span></td>
                <td>
                  <a class="btn-action btn-edit" href="<?= base_url('create-event/' . $ev['id']) ?>" title="Edit">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <form action="<?= base_url('crud/event/delete/' . $ev['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Delete this event?');">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-action btn-delete border-0 bg-transparent p-0" title="Delete">
                      <i class="bi bi-trash"></i>
                    </button>
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
