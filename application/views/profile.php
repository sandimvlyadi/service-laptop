<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admina | Profile</title>
  <?php $this->load->view('script-head'); ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <?php $this->load->view('header'); ?>

  <!-- =============================================== -->

  <?php $this->load->view('sidebar'); ?>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Profile
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <input id="csrf" type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="" />
        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3>Data Pengguna</h3>
            </div>
            <div class="box-body">
              <form id="formDataPengguna">
                <div class="row">
                  <div class="col-xs-4 col-xs-offset-4">
                    <img src="<?php if($data['display_picture'] != '') { echo base_url('uploads/img/' . $data['display_picture']); } else { echo base_url('assets/dist/img/user.png'); } ?>" class="img-responsive display-picture">
                    <p><i class="fa fa-camera"></i> Ubah</p>
                    <input type="file" name="file_display_picture" style="display: none;" accept="image/*">
                  </div>
                </div>
                <div class="form-group">
                  <label>Username</label>
                  <input type="text" name="username" class="form-control" placeholder="Username" value="<?php echo $data['username']; ?>" required></input>
                </div>
                <div class="form-group">
                  <label>Display Name</label>
                  <input type="text" name="display_name" class="form-control" placeholder="Display Name" value="<?php echo $data['display_name']; ?>" required></input>
                </div>
                <div class="form-group">
                  <label>Email</label>
                  <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo $data['email']; ?>"></input>
                </div>
                <div class="form-group">
                  <label>Kontak</label>
                  <input type="text" name="kontak" class="form-control" placeholder="Kontak" value="<?php echo $data['kontak']; ?>"></input>
                </div>
                <div class="form-group">
                  <label>Alamat</label>
                  <textarea name="alamat" class="form-control" placeholder="Alamat"><?php echo $data['alamat']; ?></textarea>
                </div>
              </form>
            </div>
            <div class="box-footer">
              <div class="row pull-right">
                <div class="col-xs-12">
                  <button name="btn_save_data" class="btn btn-xs btn-success btn-flat"><i class="fa fa-check"></i> Simpan</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3>Ganti Password</h3>
            </div>
            <div class="box-body">
              <form id="formDataPassword">
                <div class="form-group">
                  <label>Password Lama</label>
                  <input type="password" name="password_lama" class="form-control" placeholder="Password Lama" required></input>
                </div>
                <div class="form-group">
                  <label>Password Baru</label>
                  <input type="password" name="password_baru" class="form-control" placeholder="Password Baru" required></input>
                </div>
                <div class="form-group">
                  <label>Ulangi Password Baru</label>
                  <input type="password" name="password_ulang" class="form-control" placeholder="Ulangi Password" required></input>
                </div>
              </form>
            </div>
            <div class="box-footer">
              <div class="row pull-right">
                <div class="col-xs-12">
                  <button name="btn_save_password" class="btn btn-xs btn-success btn-flat"><i class="fa fa-check"></i> Simpan</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php $this->load->view('footer'); ?>

</div>
<!-- ./wrapper -->

<?php $this->load->view('script-foot'); ?>
<script src="<?php echo base_url('assets/admina/js/admina.profile.js'); ?>"></script>
</body>
</html>
