<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admina | Dashboard</title>
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
        Dashboard
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-12">
        
          <div id="tableStockLaptop" class="row col-md-6">
            <div class="col-xs-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3>Data Stock Laptop</h3>
                </div>
                <div class="box-body">
                  <div class="row">
                    <div class="col-xs-12">
                      <table id="dataTableLaptop" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                          <th>No.</th>
                          <th>Merk</th>
                          <th>Spesifikasi</th>
                          <th>Kondisi</th>
                          <th>Harga</th>
                          <th>Status</th>
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

          <div id="tableStockSparePart" class="row col-md-6">
            <div class="col-xs-12">
              <div class="box box-primary">
                <div class="box-header">
                  <h3>Data Stock Spare Part</h3>
                </div>
                <div class="box-body">
                  <div class="row">
                    <div class="col-xs-12">
                      <table id="dataTableSparePart" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                          <th>No.</th>
                          <th>Nama Spare Part</th>
                          <th>Total Stock</th>
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
<script src="<?php echo base_url('assets/admina/js/admina.dashboard.js'); ?>"></script>
</body>
</html>
