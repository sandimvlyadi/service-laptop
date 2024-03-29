<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admina | Stock Laptop</title>
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
      <h1>Stock Laptop</h1>
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
                      <th>Merk / Serie</th>
                      <th>Spesifikasi</th>
                      <th>Kondisi</th>
                      <th>Harga</th>
                      <th>Status</th>
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
                  <label>Merk / Serie</label>
                  <input type="text" name="merk_serie" class="form-control" placeholder="Merk / Serie" required></input>
                </div>
                <div class="form-group">
                  <label>Spesifikasi</label>
                  <textarea name="spesifikasi" class="form-control" placeholder="Spesifikasi" rows="5" required></textarea>
                </div>
                <div class="form-group">
                  <label>Kondisi</label>
                  <select name="id_kondisi" class="form-control" style="width: 100%;"></select>
                </div>
                <div class="form-group">
                  <label>Persentase Kondisi</label>
                  <input type="number" name="persentase_kondisi" class="form-control" required>
                </div>
                <div class="form-group">
                  <label>Harga</label>
                  <input type="number" name="harga" class="form-control" required>
                </div>
                <div class="form-group">
                  <label>Status</label>
                  <select name="id_status" class="form-control" style="width: 100%;"></select>
                </div>
                <div class="form-group">
                  <label>Keterangan</label>
                  <textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
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
<script src="<?php echo base_url('assets/admina/js/admina.stock.laptop.js'); ?>"></script>
</body>
</html>