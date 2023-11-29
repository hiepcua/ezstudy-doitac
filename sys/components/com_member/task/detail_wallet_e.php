<?php
global $_UNIT;
if(isLogin()){
    $this_user=getInfo('username');
    $obj=SysGetList('tbl_member_wallet',array()," AND username='$this_user'");
    $wallet=isset($obj[0]['wallet'])?$obj[0]['wallet']:'';
    $B_Balance=getWalletBalance('tbl_wallet_b',$username);
    $cur_page=isset($_POST['txtCurnpage'])? (int)$_POST['txtCurnpage']:1;
    ?>
    <div class="card">
        <div class="card-body">
            <div class="wallet-title">
                <div class="row">
                    <div class="col-md-4">
                        <div class="media">
                            <h3 class="title">E WALLET</h3>
                            <p><span class="number">3456đ</span></p>
                        </div>
                    </div>
                    <div class="col-md-8 align-self-center">
                        <div class="text-lg-center">
                            <div class="row">
                                <div class="col-md-3">
                                    <div>
                                        <p class="text-muted text-truncate mb-2">Tổng nạp</p>
                                        <h5 class="mb-0"> <?php echo number_format($B_Balance,2);?></h5>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div>
                                        <p class="text-muted text-truncate mb-2">Đã chuyển</p>
                                        <h5 class="mb-0">40</h5>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div>
                                        <p class="text-muted text-truncate mb-2">Đã kích hoạt</p>
                                        <h5 class="mb-0">18</h5>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div>
                                        <p class="text-muted text-truncate mb-2">Còn lại</p>
                                        <h5 class="mb-0">18</h5>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <a class="btn btn-success" href="<?php echo ROOTHOST."send";?>"><i class="fa fa-retweet" aria-hidden="true"></i> Chuyển điểm</a>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h3 class='title'>Lịch sử giao dịch</h3>
            <?php
            $strwhere=" AND username ='$this_user'";
            $total_items=SysCount('tbl_wallet_b',$strwhere);
            $start=($cur_page-1)*MAX_ROWS;
            $strwhere.=" ORDER BY id DESC LIMIT ".$start.",".MAX_ROWS;
            $array=SysGetList('tbl_wallet_b', array(),$strwhere, false);
            getListWallet($array);
            ?>
            <div class="box-pagination">
                <?php echo paging($total_items,MAX_ROWS,$cur_page); ?>
            </div>
        </div>
    </div>
<?php }else{
    header('location:'.ROOTHOST.'login');
}?>