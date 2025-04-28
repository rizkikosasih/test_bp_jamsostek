<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Test BP Jamsostek</title>
  <?php
    require_once('helpers/global.php');
    require('templates/css.php');
  ?>
  <link rel="shortcut icon" href="<?= base_url('assets/favicon/favicon.ico') ?>" type="image/x-icon">
</head>
<body>
  <div class="container my-5">
    <h1>Data User</h1>

    <div class="card">
      <div class="card-header">
        <button class="btn btn-primary btn-sm open-modal" data-url="<?= base_url('ajax/add_user.php') ?>">
          <i class="fas fa-plus"></i> Tambah
        </button>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table
            class="table table-striped table-bordered datatables"
            id="user"
            data-url="<?= base_url('ajax/get_data_user.php') ?>"
          >
            <thead class="text-center">
              <th>Nama</th>
              <th>Tanggal Lahir</th>
              <th>Alamat</th>
              <th>Email</th>
              <th>No Telp</th>
              <th><i class="fas fa-cogs"></i></th>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <?php view('modal') ?>

  <?php require('templates/js.php') ?>
</body>
</html>