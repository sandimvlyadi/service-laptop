<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN NAVIGATION</li>
      <li class="li-dashboard"><a href="<?php echo base_url('dashboard/'); ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
      <li class="li-service"><a href="<?php echo base_url('service/'); ?>"><i class="fa fa-cogs"></i> <span>Service</span></a></li>
      <li class="li-client"><a href="<?php echo base_url('client/'); ?>"><i class="fa fa-users"></i> <span>Client</span></a></li>
      <li class="treeview li-stock">
        <a href="#">
          <i class="fa fa-database"></i> <span>Stock</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="li-stock-laptop"><a href="<?php echo base_url('stock-laptop/'); ?>"><i class="fa fa-circle-o"></i> Laptop</a></li>
          <li class="li-stock-spare-part"><a href="<?php echo base_url('stock-spare-part/'); ?>"><i class="fa fa-circle-o"></i> Spare Part</a></li>
        </ul>
      </li>
      <li class="treeview li-master">
        <a href="#">
          <i class="fa fa-book"></i> <span>Master</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="li-master-kondisi"><a href="<?php echo base_url('master-kondisi/'); ?>"><i class="fa fa-circle-o"></i> Kondisi</a></li>
          <li class="li-master-pengguna"><a href="<?php echo base_url('master-pengguna/'); ?>"><i class="fa fa-circle-o"></i> Pengguna</a></li>
          <li class="li-master-satuan"><a href="<?php echo base_url('master-satuan/'); ?>"><i class="fa fa-circle-o"></i> Satuan</a></li>
          <li class="li-master-spare-part"><a href="<?php echo base_url('master-spare-part/'); ?>"><i class="fa fa-circle-o"></i> Spare Part</a></li>
          <li class="li-master-status"><a href="<?php echo base_url('master-status/'); ?>"><i class="fa fa-circle-o"></i> Status</a></li>
          <li class="li-master-status-kategori"><a href="<?php echo base_url('master-status-kategori/'); ?>"><i class="fa fa-circle-o"></i> Status Kategori</a></li>
          <li class="li-master-type-monitor"><a href="<?php echo base_url('master-type-monitor/'); ?>"><i class="fa fa-circle-o"></i> Type Monitor</a></li>
        </ul>
      </li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
