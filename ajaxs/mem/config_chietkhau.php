<?php
session_start();
define('incl_path','../../global/libs/');
define('libs_path','../../libs/');
require_once(incl_path.'gfconfig.php');
require_once(incl_path.'gfinit.php');
require_once(incl_path.'gffunc.php');
require_once(incl_path.'gffunc_member.php');
require_once(libs_path.'cls.mysql.php');
require_once(libs_path.'GoogleAuthenticator.php');
if(isLogin()){
    $doanhso_from=isset($_POST['doanhso_from'])? addslashes($_POST['doanhso_from']):'';
    $doanhso_to=isset($_POST['doanhso_to'])? addslashes($_POST['doanhso_to']):'';
    $kichhoat=isset($_POST['kichhoat'])? (int)$_POST['kichhoat']:'';
    $giahan=isset($_POST['giahan'])? (int)$_POST['giahan']:'';
    $lienket=isset($_POST['lienket'])? (int)$_POST['lienket']:'';
    ?>
    <tr class="tr-box">
        <td>
            <span>
                <input type='text' class='form-control number_format'  name='txt_fromtotal[]' placeholder='Doanh số' value='<?php echo $doanhso_from;?>'  />
            </span>
        </td>
        <td>
            <span>
                <input type='text' class='form-control number_format'  name='txt_tototal[]' placeholder='Doanh số' value='<?php echo $doanhso_to;?>'  />
            </span>
        </td>
        <td>
            <span>
                <input type='text' class='form-control' name='txt_active_account1[]' id='txt_active_account1' placeholder='10%' value='<?php echo $kichhoat;?>'  />
            </span>
        </td>
        <td>
            <span>
                  <input type='text' class='form-control' name='txt_change1[]' id='txt_change1' placeholder='5%' value='<?php echo $giahan;?>'/>
            </span>
        </td>
        <td>
            <span>
                 <input type='text' class='form-control' name='txt_link1[]' id='txt_link1' placeholder='5%' value='<?php echo $lienket;?>'/>
            </span>
        </td>
        <td>
            <span class="delete-item"><i class="fa fa-trash-o" aria-hidden="true"></i></span>
        </td>
    </tr>
<?php
}
?>
<script>
    $('.delete-item').click(function(){
        var par=$(this).parent().parent();
        par.remove();
    })
    $(".number_format").keyup(function(){
        var selection = window.getSelection().toString();
        if ( selection !== '' ) {
            return;
        }
        if ( $.inArray( event.keyCode, [38,40,37,39] ) !== -1 ) {
            return;
        }
        var $this = $( this );
        var input = $this.val();
        if(input==0) return 0;
        var input = input.replace(/[\D\s\._\-]+/g, "");
        input = input ? parseInt( input, 10 ) : 0;
        $this.val( function() {
            return ( input === 0 ) ? "" : input.toLocaleString( "en-US" );
        } );
    })
</script>