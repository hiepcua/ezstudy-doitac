<?php
if(isset($_FILES['fileImg']) && $_FILES['fileImg']['name'][0]!=NULL):
    require_once("../../../global/libs/gfinit.php");
	require_once("../../../global/libs/gfconfig.php");
	require_once("../../../global/libs/gffunc.php");
    include_once('../../../libs/cls.mysql.php');
    include_once('../../libs/cls.albumgallery.php');
    include_once('../../extensions/cls.upload.php');
    $album_id=addslashes($_POST['album_id']);
    $name_image=addslashes($_POST['txt_image']);
    $name=$_FILES['fileImg']['name'][0];
    $tmp_name=$_FILES['fileImg']['tmp_name'][0];
    $size=$_FILES['fileImg']['size'][0];
    $fileType=$_FILES['fileImg']['type'][0];
    $path=UPLOAD_PATH.PATH_GALLERY;

    $obj=new CLS_MYSQL();
    $objUpload=new CLS_UPLOAD();

    if($objUpload->UploadFiles('fileImg', $path)):
        $objGa=new CLS_ALBUMGALLERY();
        $objGa->GAlbumID=$album_id;
        $objGa->Name=$name_image;
        // $objGa->GLink=$name;
        $objGa->GLink=ROOTHOST.PATH_GALLERY_REVIEW.$name;
        $id='';
        $sql=" INSERT INTO `tbl_gallery`(`album_id`,`name`,`link`,`isactive`) VALUES";
        $sql.="('".$objGa->GAlbumID."','".$objGa->Name."','".$objGa->GLink."','".$objGa->GisActive."')";
        if($obj->Exec($sql)==true){
            $objGa->getListGallery("WHERE album_id='$album_id'", PATH_GALLERY);
            }
        else{
            echo "Có lỗi xảy ra, vui lòng thử lại!";
        }
    endif;
endif;
unset($obj);
unset($objGa);
?>

<script>
    /*del image*/
    $('#respon-img .del-item').click(function(){
        if(confirm("Bạn có muốn chắc xóa ảnh này")){
            var id=$(this).attr('value');
            $.post('<?php echo ROOTHOST_ADMIN;?>ajaxs/upload_album/delImage.php',{id},function(response_data){
            });
            var parent= $(this).parent();
            parent.remove();
        }
        else return false;
    });
    $('#file-img').val("");
    $('#txt_image').val("");
    /*edit name image*/
    $('.edit-item').click(function(){
        var id=$(this).attr('value');
        var par_id=$('#par_id').val();
        $.post('<?php echo ROOTHOST_ADMIN;?>ajaxs/upload_album/frm_edit.php',{id, par_id},function(response_data){
            $('#myModal').modal('show');
            $('#myModalLabel').html('Rename');
            $('#data-frm').html(response_data);
        });
    });

</script>

