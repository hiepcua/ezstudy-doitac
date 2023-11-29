<?php $username=getInfo('username');?>
<?php $isroot=getInfo('isroot');?>
<nav class="navitor navbar navbar-inverse navbar-static-top" role="navigation" style="margin-bottom: 0px;">
    <div class="container-fluid">
        <div class="">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo ROOTHOST;?>"><!--<img src="#" class="logo">--> ĐỐI TÁC STUDY</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse menu">
                <ul class="nav navbar-nav">
                    <?php if($isroot==1){?>
                        <li class="root"><a href="<?php echo ROOTHOST;?>daily">Quản lý Đại lý</a></li>
                        <li class="root"><a href="<?php echo ROOTHOST;?>saler">Quản lý Saler</a></li>
                    <?php }?>
                    <li class="root">
                        <a href="#" class="dropdown-toggle">
                            Khách hàng <i class="fa fa-caret-down" aria-hidden="true"></i>
                        </a>
                        <!-- <a href="<?php echo ROOTHOST;?>account">Khách hàng</a> -->
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo ROOTHOST;?>account/hocsinh">Học sinh</a></li>
                            <li><a href="<?php echo ROOTHOST;?>account/chame">Phụ huynh</a></li>
                        </ul>
                    </li>
                    <li><a href="<?php echo ROOTHOST;?>b-wallet">Ví B</a></li>
                    <li><a href="<?php echo ROOTHOST;?>s-wallet">Ví S</a></li>
                    <li><a href="<?php echo ROOTHOST;?>bonus-report">Thống kê doanh số</a></li>
                    <li class="dhide"><a href="<?php echo ROOTHOST;?>members/bonus">Tỉ lệ thưởng</a></li>
                    <li class="dhide"><a href="<?php echo ROOTHOST;?>members/profile">Thông tin cá nhân</a></li>
                    <li class="dhide"><a href="<?php echo ROOTHOST;?>members/changepass"> Đổi mật khẩu</a></li>
                    <li class="dhide"><a href="javascript:void(0);" class='logout' rel="nofollow,noindex">Đăng xuất tài khoản</a></li>
                </ul>
                <div id="in_out" class='text-right pull-right'>
                    <div class="action dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <a href="#" id="nav_registry" ><span class='avatar-small'><i class="fa fa-user fa-2" aria-hidden="true"></i></span> <?php echo $username;?> </a><i class="fa fa-caret-down" aria-hidden="true"></i>
                    </div>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="<?php echo ROOTHOST;?>members/bonus"><i class="fa fa-user" aria-hidden="true"></i> <span>Tỉ lệ thưởng</span></a></li>
                        <li><a href="<?php echo ROOTHOST;?>members/profile"><i class="fa fa-user" aria-hidden="true"></i> <span>Thông tin cá nhân</span></a></li>
                        <li><a href="<?php echo ROOTHOST;?>members/changepass"><i class="fa fa-key"></i> Đổi mật khẩu</a></li>
                        <li><a href="javascript:void(0);" class='logout' rel="nofollow,noindex"><i class="fa fa-power-off"></i> Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>