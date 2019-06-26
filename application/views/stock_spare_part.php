<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admina | Stock Spare Part</title>
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
      <h1>Stock Spare Part</h1>
    </section>

    <!-- Main content -->
    <section class="content">

      <div id="table" class="row">
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-body">
              <div class="row" style="padding-bottom: 10px;">
                <div class="col-xs-12">
                  <button name="btn_back" class="btn btn-xs btn-default btn-flat" style="display: none;"><i class="fa fa-chevron-left"></i> Kembali</button> <button name="btn_add" class="btn btn-xs btn-primary btn-flat pull-right" style="display: none;"><i class="fa fa-plus"></i> Tambah Data</button>
                </div>
              </div>

              <div class="row table-main">
                <div class="col-xs-12">
                  <table id="dataTable" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>No.</th>
                      <th>Nama Spare Part</th>
                      <th>Total Stock</th>
                      <th style="min-width: 75px;">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="row table-detail" style="display: none;">
                <div class="col-xs-12">
                  <table id="dataTableDetail" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>No.</th>
                      <th>Nama Spare Part</th>
                      <th>Merk / Serie</th>
                      <th>Spesifikasi</th>
                      <th>Harga Beli</th>
                      <th>Harga Jual</th>
                      <th>Stock</th>
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
                <input type="hidden" name="id_spare_part" value="0">
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
                  <label>Harga Beli</label>
                  <input type="number" name="harga_beli" class="form-control" placeholder="Harga Beli" required></input>
                </div>
                <div class="form-group">
                  <label>Harga Jual</label>
                  <input type="number" name="harga_jual" class="form-control" placeholder="Harga Jual" required></input>
                </div>
                <div class="form-group">
                  <label>Jumlah Stock</label>
                  <input type="number" name="jml_stock" class="form-control" placeholder="Jumlah Stock" required></input>
                </div>
                <div class="form-group">
                  <label>Status</label>
                  <select name="id_status" class="form-control" style="width: 100%;"></select>
                </div>
                <div class="form-group fieldKapasitas">
                  <label>Kapasitas</label>
                  <input type="number" name="kapasitas" class="form-control" placeholder="Kapasitas"></input>
                </div>
                <div class="form-group fieldSatuan">
                  <label>Satuan</label>
                  <select name="id_satuan" class="form-control" style="width: 100%;"></select>
                </div>
                <div class="form-group fieldTypeMonitor">
                  <label>Type Monitor</label>
                  <select name="id_type_monitor" class="form-control" style="width: 100%;"></select>
                </div>
                <div class="form-group fieldBatreFor">
                  <label>Batre For</label>
                  <input type="text" name="batre_for" class="form-control" placeholder="Batre For">
                </div>
                <div class="form-group fieldAdaptorFor">
                  <label>Adaptor For</label>
                  <input type="text" name="adaptor_for" class="form-control" placeholder="Adaptor For">
                </div>
                <div class="form-group fieldChassingFor">
                  <label>Chassing For</label>
                  <input type="text" name="chassing_for" class="form-control" placeholder="Chassing For">
                </div>
                <div class="form-group fieldKeyboardFor">
                  <label>Keyboard For</label>
                  <input type="text" name="keyboard_for" class="form-control" placeholder="Keyboard For">
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
<script src="<?php echo base_url('assets/admina/js/admina.stock.spare.part.js'); ?>"></script>
</body>
</html>