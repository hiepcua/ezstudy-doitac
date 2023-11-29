<?php
require_once("../../../global/libs/gfinit.php");
require_once("../../../global/libs/gfconfig.php");
require_once("../../../global/libs/gffunc.php");
include_once('../../../libs/cls.mysql.php');
include_once('../../libs/cls.albumgallery.php');
$obj=new CLS_MYSQL();
$objGa=new CLS_ALBUMGALLERY();
if(isset($_POST['txtid'])){
    $id=(int)$_POST['txtid'];
    $par_id=(int)$_POST['txtparid'];
    $image=addslashes($_POST['txt_image']);
    $sql="UPDATE tbl_gallery set name='$image' WHERE id='$id'";
    $obj->Query($sql);
    $objGa->getListGallery("WHERE album_id='$par_id'");
}
?>
<script>
    /*del image*/
    $('#respon-img .del-item').click(function(){
        if(confirm("Bạn có muốn chắc xóa ảnh này")){
            var imgId=$(this).attr('value');
            $.post('<?php echo ROOTHOST;?>ajaxs/upload_album/delImage.php',{imgId},function(response_data){
               // $('#respon-img').append(response_data);
				//alert(response_data);
            });
            var parent= $(this).parent();
            parent.remove();
        }
        else return false;
    });
    /*edit name image*/
    $('#respon-img .edit-item').click(function(){
        var id=$(this).attr('value');
        var par_id=$('#par_id').val();
        $.post('<?php echo ROOTHOST;?>ajaxs/upload_album/frm_edit.php',{id, par_id},function(response_data){
            $('#myModal').modal('show');
            $('#myModalLabel').html('Rename');
            $('#data-frm').html(response_data);
        });
    });
</script>

