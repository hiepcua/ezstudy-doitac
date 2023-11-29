<?php
require_once("../../../global/libs/gfinit.php");
require_once("../../../global/libs/gfconfig.php");
require_once("../../../global/libs/gffunc.php");
include_once('../../libs/cls.mysql.php');
include_once('../../libs/cls.gallery.php');
$obj=new CLS_MYSQL();
$objGa=new CLS_GALLERY();
$id=isset($_POST['imgId'])? $_POST['imgId']: '';
$sql="SELECT link FROM `tbl_gallery` WHERE `id`='$id'";
$obj->Query($sql);
$obj->Num_rows();
$row=$obj->Fetch_Assoc();

$link=ROOTDOCUMENT.PATH_GALLERY_REVIEW.$row['link'];
if(is_file($link)){
    unlink($link);
}
$objGa->DeleteGallery($id);
unset($obj);
unset($objGa);
?>
<script>
    /*del image*/
    $('#respon-img .del-item').click(function(){
        if(confirm("Bạn có muốn chắc xóa ảnh này")){
            var imgId=$(this).attr('value');
            var par_id=$('#par_id').val();
            $.post('<?php echo ROOTHOST_ADMIN;?>ajaxs/upload_album/delImage.php',{imgId, par_id},function(response_data){
            });
            var parent= $(this).parent();
            parent.remove();
        }
        else return false;
    });
    $('#file-img').val("");

    /*edit name image*/
    $('.edit-item').click(function(){
        var id=$(this).attr('value');
        var par_id=$('#par_id').val();
        $.post('<?php echo ROOTHOST_ADMIN;?>ajaxs/upload_album/frm_edit.php',{id, par_id},function(response_data){
            $('#myModal').modal('show');
            $('#myModalLabel').html('Đổi tên ảnh');
            $('#data-frm').html(response_data);
        });
    });

</script>