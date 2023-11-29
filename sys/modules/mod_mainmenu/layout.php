<?php
if($UserLogin->isLogin()){
    $username=$UserLogin->getInfo('username');
    $id_user=$UserLogin->getInfo('id');
	//echo $id_user;
?>
    <header class="main-header">
    <a href="<?php echo ROOTHOST_ADMIN;?>" class="logo">
        <span class="logo-mini"><b>A</b>LT</span>
        <span class="logo-lg"><b>Admin</b></span>
    </a>
    <nav class="navbar navbar-static-top">
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>
    <div class="navbar-custom-menu">
    <ul class="nav navbar-nav">

    <li class="dropdown dropdown-user user user-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
            <i class="fa fa-user"></i>
            <span class="username username-hide-on-mobile">	<?php echo $username;?> </span>
            <i class="fa fa-angle-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-default">
			<li>
                <a href="<?php echo ROOTHOST_ADMIN.'user';?>">
				<i class="fa fa-unlock-alt" aria-hidden="true"></i> Quản lý User</a>
            </li><li>
                <a href="<?php echo ROOTHOST_ADMIN.'user/edit/'.$id_user;?>">
				<i class="fa fa-unlock-alt" aria-hidden="true"></i> Thông tin cá nhân</a>
            </li>
			<li>
                <a href="#" class='change_pass' dataid="<?php echo $username;?>">
				<i class="fa fa-unlock-alt" aria-hidden="true"></i> Đổi mật khẩu
				</a>
            </li>
            
            <li>
                <a href="<?php echo ROOTHOST_ADMIN;?>logout">
                    <i class="fa fa-sign-out" aria-hidden="true"></i> Log Out </a>
            </li>
        </ul>
    </li>
    <!-- END QUICK SIDEBAR TOGGLER -->
    </ul>
    </li>
    <!-- Control Sidebar Toggle Button -->
    </ul>
    </div>
    </nav>
</header>
<?php }?>

