<?php
$user = $this->session->userdata('userSession');
?>
<div class="navbar-custom-menu">
  <ul class="nav navbar-nav">
    <!-- User Account: style can be found in dropdown.less -->
    <li class="dropdown user user-menu">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <img src="<?php echo base_url('assets/dist/img/user.png'); ?>" class="user-image" alt="User Image">
        <span class="hidden-xs"><?php echo $user['displayName']; ?></span>
      </a>
      <ul class="dropdown-menu">
        <!-- User image -->
        <li class="user-header">
          <img src="<?php echo base_url('assets/dist/img/user.png'); ?>" class="img-circle" alt="User Image">

          <p>
            <?php echo $user['level']; ?>
          </p>
        </li>

        <!-- Menu Footer-->
        <li class="user-footer">
          <div class="pull-left">
            <a href="<?php echo base_url('profile/'); ?>" class="btn btn-default btn-flat">Profile</a>
          </div>
          <div class="pull-right">
            <a href="<?php echo base_url('dashboard/logout/'); ?>" class="btn btn-default btn-flat">Sign out</a>
          </div>
        </li>
      </ul>
    </li>
  </ul>
</div>
