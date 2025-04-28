<form id="formValidate" action="<?= base_url('action/edit_user.php') ?>" method="post" novalidate>
  <input type="hidden" class="form-control" name="id" value="<?= $id ?>"/>

  <div class="form-group mb-3">
    <label for="nama" class="form-label">Nama <small class="text-danger">*</small></label>
    <input
      type="text"
      class="form-control"
      name="nama"
      id="nama"
      placeholder="Nama Lengkap"
      data-maxlength="128"
      value="<?= $nama ?>"
      required
    />
  </div>

  <div class="form-group mb-3">
    <label for="email" class="form-label">Email <small class="text-danger">*</small></label>
    <input
      type="email"
      class="form-control"
      name="email"
      id="email"
      placeholder="example@email.com"
      maxlength="255"
      value="<?= $email ?>"
      required
    />
  </div>

  <div class="form-group mb-3">
    <label for="no_telp" class="form-label">No Telepon <small class="text-danger">*</small></label>
    <input
      type="text"
      class="form-control no_telp"
      name="no_telp"
      id="no_telp"
      placeholder="08123456789"
      value="<?= $no_telp ?>"
      required
    />
  </div>

  <div class="form-group mb-3 position-relative">
    <label for="tanggal_lahir" class="form-label">Tanggal Lahir <small class="text-danger">*</small></label>
    <input
      type="text"
      name="tanggal_lahir"
      id="tanggal_lahir"
      class="form-control tanggal"
      placeholder="YYYY-MM-DD"
      value="<?= $tanggal_lahir ?>"
      required
    />
  </div>

  <div class="form-group mb-3">
    <label for="alamat" class="form-label">Alamat <small class="text-danger">*</small></label>
    <textarea class="form-control" name="alamat" id="alamat" rows="3" required><?= htmlspecialchars_decode($alamat, ENT_QUOTES) ?></textarea>
  </div>

  <hr>
  <div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">Submit</button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
  </div>
</form>