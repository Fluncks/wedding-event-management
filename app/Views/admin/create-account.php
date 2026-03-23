<?= $this->extend('template/template') ?>
<?= $this->section('content') ?>

<div class="row">
  <div class="col-lg-8">
    <div class="content-card">
      <div class="card-header-custom">
        <h4><i class="bi bi-person-plus"></i> Create New Staff Account</h4>
        <p class="text-muted small mb-0 mt-2">Administrators can create and remove both staff and admin accounts. You cannot delete the account you are logged in with.</p>
      </div>
      <div class="card-body-custom">
        <form action="<?= base_url('admin/create-user') ?>" method="post">
          <?= csrf_field() ?>
          <div class="form-section">
            <h5 class="form-section-title"><i class="bi bi-person"></i> Personal Information</h5>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label-custom">Full Name</label>
                <input type="text" name="name" class="form-control form-control-custom" required value="<?= esc(old('name')) ?>">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label-custom">Email Address</label>
                <input type="email" name="email" class="form-control form-control-custom" required value="<?= esc(old('email')) ?>">
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label-custom">Phone Number</label>
                <input type="tel" name="phone" class="form-control form-control-custom" value="<?= esc(old('phone')) ?>">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label-custom">Role</label>
                <?php $r = old('role', 'staff'); ?>
                <select name="role" class="form-select form-select-custom" required>
                  <option value="staff" <?= $r === 'staff' ? 'selected' : '' ?>>Staff</option>
                  <option value="admin" <?= $r === 'admin' ? 'selected' : '' ?>>Administrator</option>
                </select>
              </div>
            </div>
          </div>

          <div class="form-section">
            <h5 class="form-section-title"><i class="bi bi-shield-lock"></i> Account Security</h5>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label-custom">Password</label>
                <input type="password" name="password" class="form-control form-control-custom" required minlength="6">
                <div class="form-text">Minimum 6 characters</div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label-custom">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control form-control-custom" required>
              </div>
            </div>
          </div>

          <div class="d-flex gap-3 mt-4 flex-wrap">
            <button type="submit" class="btn btn-primary-custom btn-custom">
              <i class="bi bi-person-check"></i> Create Account
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="content-card">
      <div class="card-header-custom">
        <h4><i class="bi bi-people"></i> Staff Members</h4>
      </div>
      <div class="card-body-custom p-0">
        <div class="table-responsive">
          <table class="table table-custom">
            <thead>
              <tr>
                <th>Name</th>
                <th>Role</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($users as $u) : ?>
                <tr>
                  <td>
                    <strong><?= esc($u['name']) ?></strong>
                    <br><small class="text-muted"><?= esc($u['email']) ?></small>
                  </td>
                  <td><span class="badge bg-secondary"><?= esc($u['role']) ?></span></td>
                  <td>
                    <?php if ((int) $u['id'] !== (int) session('user_id')) : ?>
                      <form action="<?= base_url('admin/delete-user/' . $u['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Remove this user?');">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn-action btn-delete border-0 bg-transparent p-0" title="Delete">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                    <?php else : ?>
                      <span class="text-muted small">You</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
