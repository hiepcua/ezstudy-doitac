<div id="menus" class="toolbars">
    <form id="frm_menu" name="frm_menu" method="post" action="" >         
        <label>
            <input type="hidden" name="txtorders" id="txtorders" />
            <input type="hidden" name="txtids" id="txtids" />
            <input type="hidden" name="txtaction" id="txtaction" />
        </label>
        <ul>
            <?php
            $viewtype=isset($viewtype)? $viewtype:'';
            if(isset($_GET["viewtype"]) && $_GET["viewtype"]=='list'){
                $com=isset($_GET["com"])? $_GET["com"]:'';
                ?>
                <li>
                    <?php
                    echo '<a class="btn btn-success" href="'.ROOTHOST.COMS.'/addnew" title="Thêm mới">
                    <i class="fa fa-plus" aria-hidden="true"></i><span class="hiden-label"> Thêm mới</span>
                    </a>';
                    ?>
                </li>
            <?php }else{?>
                <li><a class="btn btn-primary" href="#" onclick="dosubmitAction('frm_action','save');" title="Lưu"><i class="fa fa-floppy-o" aria-hidden="true"></i><span class="hiden-label"> Lưu</span></a></li>
                <li><a class="btn btn-warning" href="<?php echo ROOTHOST.COMS;?>" title="Đóng"><i class="fa fa-close" aria-hidden="true"></i><span class="hiden-label"> Đóng</span></a></li>

            <?php } ?>
        </ul>
    </form>
</div>