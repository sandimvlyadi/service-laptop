<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admina | Teknisi</title>
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
      <h1>Teknisi</h1>
    </section>

    <!-- Main content -->
    <section class="content">

      <div id="table" class="row">
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-body">
              <div class="row" style="padding-bottom: 10px;">
                <div class="col-xs-12">
                  <button name="btn_add" class="btn btn-xs btn-primary btn-flat pull-right"><i class="fa fa-plus"></i> Tambah Data</button>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <table id="dataTable" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>No.</th>
                      <th>Display Name</th>
                      <th>Kontak</th>
                      <th style="min-width: 75px;">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div id="form" class="row" style="display: none;">
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 id="formTitle">Tambah Data</h3>
            </div>
            <div class="box-body">
              <form id="formData">
                <input id="csrf" type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="" />
                <div class="form-group">
                  <label>Username</label>
                  <input type="text" name="username" class="form-control" placeholder="Username" required></input>
                </div>
                <div class="form-group">
                  <label>Display Name</label>
                  <input type="text" name="display_name" class="form-control" placeholder="Display Name" required></input>
                </div>
                <div class="form-group">
                  <label>Email</label>
                  <input type="email" name="email" class="form-control" placeholder="Email"></input>
                </div>
                <div class="form-group">
                  <label>Kontak</label>
                  <input type="text" name="kontak" class="form-control" placeholder="Kontak"></input>
                </div>
                <div class="form-group">
                  <label>Alamat</label>
                  <textarea name="alamat" class="form-control" placeholder="Alamat"></textarea>
                </div>
              </form>
            </div>
            <div class="box-footer">
              <div class="row pull-right">
                <div class="col-xs-12">
                  <button id="0" name="btn_save" class="btn btn-xs btn-success btn-flat"><i class="fa fa-check"></i> Simpan</button>
                  <button name="btn_cancel" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-times"></i> Batal</button>
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
<script src="<?php echo base_url('assets/admina/js/admina.teknisi.js'); ?>"></script>
</body>
</html>