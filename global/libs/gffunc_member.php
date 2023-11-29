<?php
function findNode($user,$pos=0){
	$obj=SysGetList('tbl_member_packet',array(),"AND rep_user='$user' AND side='$pos'");
	$n=count($obj);
	if($n>0){
		$r=$obj[0];
		$user=$r['username'];
		$user=findNode($user,$pos);
		return $user;
	}else{
		return $user;
	}
}
function isLogin(){
	if(isset($_SESSION['MEMBER_LOGIN']) && $_SESSION['MEMBER_LOGIN']['islogin']){
		/* $user=getInfo('username');
		if(checkExpires($user)===true) return false; */
		return true;
	}
	return false;
}
function isAdmin($gid=1) {
    if(isset($_SESSION['MEMBER_LOGIN']) && $_SESSION['MEMBER_LOGIN']['gid']==$gid)
        return true;
    return false;
}
function getSessionLogin(){
    if(isset($_SESSION['MEMBER_LOGIN'])){
        return $_SESSION['MEMBER_LOGIN'];
    }
    return null;
}
function setSessionLogin($data){
    if(is_array($data)){ $_SESSION['MEMBER_LOGIN']=$data;}
    else {$_SESSION['MEMBER_LOGIN']=null;}
}
function getInfo($field){
	$info=isset($_SESSION['MEMBER_LOGIN'][$field])?$_SESSION['MEMBER_LOGIN'][$field]:'N/a';
	return $info;
}
function setInfo($field,$val){
	if(isset($_SESSION['MEMBER_LOGIN']))$_SESSION['MEMBER_LOGIN'][$field]=$val;
}
function updateInfo($field,$val){
	if(isLogin()) $_SESSION['MEMBER_LOGIN'][$field]=$val;
}
function checkExpires($user){
	// sau 1 phút sẽ check lại hệ thống, nếu thấy user có đăng nhập từ một ip mới, sẽ tiến hành logout user hiện tại
	// get session login
	$now=time();
	if(isset($_SESSION['MEMBER_LOGIN']) && $now-$_SESSION['MEMBER_LOGIN']['action_time']>=ACTION_TIMEOUT){
		$obj=new CLS_MYSQL;
		$sql="SELECT session FROM tbl_member_login WHERE username='$user' AND isactive=1 ORDER BY id DESC";
		$obj->Query($sql);
		if($obj->Num_rows()>0){
			$r=$obj->Fetch_Assoc();
			if($_SESSION['MEMBER_LOGIN']['session']!=$r['session']){
				LogOut($user);
				return true;
			}
		}else{
			die('Check Expire error. Please contact administrator!');
		}
	}
	// check time out login
	if(isset($_SESSION['MEMBER_LOGIN']) && $now-$_SESSION['MEMBER_LOGIN']['action_time']>=MEMBER_TIMEOUT){
		LogOut($user);
	}
	return false;
}
function LogIn($user,$pass){
	$arr=array('status'=>'no','data'=>null);
	if($user==''||$pass=='')	return $arr;
	$pass=hash('sha256', $user).'|'.hash('sha256', $pass);
	$fields=array();
	$obj=new CLS_MYSQL;
	if(MemberCountList(" AND `isactive`=1 AND `username`='$user'")!=1) return $arr;

	$r=MemberGetList($fields," AND `isactive`=1 AND `username`='$user'");
	if($r[0]['password']!=$pass) return $arr;
    $arr= $r[0];
    $arr['data']=1;
	$session=$cdate=time();
	$sql="INSERT INTO tbl_member_login(`username`,`session`,`cdate`) VALUES ('$user','$session','$cdate')";
	$obj->Exec($sql);
	return $arr;
}
function LogOut($user){
	if(isset($_SESSION['MEMBER_LOGIN'])){
		unset($_SESSION['MEMBER_LOGIN']);
		$sql="UPDATE tbl_member_login SET `isactive`=0 WHERE username='$user'";
		$obj=new CLS_MYSQL;
		$obj->Exec($sql);
	}
}
function MemberCountList($where){
	$sql="SELECT COUNT(*) as num FROM tbl_member WHERE 1=1 $where";
	$obj=new CLS_MYSQL;
	$obj->Query($sql);
	$r=$obj->Fetch_Assoc();
	return $r['num']+0;
}
function MemberGetList($fields=array(),$where='',$flag=true){
	if(count($fields)==0){
		$sql="SELECT * FROM tbl_member WHERE 1=1 $where";
	}else{
		$sql="SELECT ";
		foreach($fields as $field){
			$sql.="`$field`,";
		}
		$sql=substr($sql,0,-1)." FROM tbl_member WHERE 1=1 $where ";
	}

	$obj=new CLS_MYSQL;
	$obj->Query($sql);
	if($flag){
		$arr=array();
		while($r=$obj->Fetch_Assoc()){
			$arr[]=$r;
		}
		return $arr;
	}
	return $obj;
}
function MemberAdd($arr){
	if(!is_array($arr) || count($arr)==0) return false;
	$fields=$values='';
	foreach($arr as $key=>$val){
		$fields.="`$key`,";
		$values.="'$val',";
	}
	$sql="INSERT INTO tbl_member(".substr($fields,0,-1).") VALUES(".substr($values,0,-1).")";
	$obj=new CLS_MYSQL;
	$obj->Exec($sql);
}
function MemberEdit($arr){
	$fields='';
	foreach($arr as $key=>$val){
		$fields.="`$key`='$val',";
	}
	$sql="UPDATE tbl_member_login SET ".substr($fields,0,-1)." WHERE username='$user'";
	$obj=new CLS_MYSQL;
	$obj->Exec($sql);
}
function MemberActive($user,$status=0){
	$sql="UPDATE tbl_member SET `isactive`=$status WHERE username='$user'";
	$obj=new CLS_MYSQL;
	$obj->Exec($sql);
}
function MemberChangePass($user,$pass){
	$sql="UPDATE tbl_member SET `password`='$pass' WHERE username='$user'";
	$obj=new CLS_MYSQL;
	$obj->Exec($sql);
}
function getListData($array, $type=''){//$type=1 lấy đại lý cấp
    $arr_user=array();
    if($array->Num_rows()>0){
        ?>
        <table class="table table-main">
            <thead>
            <tr>
                <th class="stt text-center mhide"><strong>STT</strong></th>
                <th ><strong>Tài khoản</strong></th>
                <th class="text-center mhide"><strong>Quyền</strong></th>
                <th class="mhide"><strong>Ngày tạo</strong></th>
                <th class="td-action"><strong>Thao tác</strong></th>
            </tr>
            </thead>
            <tbody>
        <?php
        $stt=0;
            while($r_mem=$array->Fetch_Assoc()){
            $stt++;
            $jdate=date('d-m-Y',$r_mem['cdate']);
            $id=$username=$r_mem['username'];
             $arr_user[]=$username;
            $nhom=$r_mem['isroot']==1? 'Đại lý': 'Sale';
			if($r_mem['rates']!=''){
            $rates=json_decode($r_mem['rates'], true);
            $kichhoat=isset($rates['0,0']['kichhoat'])? $rates['0,0']['kichhoat']:'';
            $giahan=isset($rates['0,0']['giahan'])? $rates['0,0']['giahan']:'';
            $lienket=isset($rates['0,0']['lienket'])? $rates['0,0']['lienket']:'';
           
			}
            ?>
            <tr>
                <td class="stt text-center mhide"><?php echo $stt;?></td>
                <td >
                    <p class="td-text"><b><?php echo $username?></b></p>
                    <ul class="list-inline">
                        <li><?php echo $r_mem['fullname'];?></li>
                        <li>Phone: <a href="tel:<?php echo $r_mem['phone'];?>"><?php echo $r_mem['phone'];?></a></li>
                    </ul>
					<ul class="list-inline">
                        <li>Kích hoạt:<?php echo $kichhoat;?>%</li>
                        <li>Gia hạn:<?php echo $giahan;?>%</li>
                        <li>Liên kết:<?php echo$lienket;?>%</li>
                    </ul>
                </td>
                <td class="text-center mhide"><?php echo $nhom;?></td>
                <td class="txt-cdate mhide"><?php echo $jdate;?></td>
                <td class="text-center">
                    <div class="btn-group btn-more-act">
                        <button type="button" class="btn  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="<?php echo ROOTHOST."members/edit/".$id;?>"  class="dropdown-item" data-id="<?php echo $id;?>"><span class="fa fa-edit"></span> Chỉnh sửa thông tin</a>
                            <a href="<?php echo ROOTHOST."members/config/".$id;?>"  class="dropdown-item" data-id="<?php echo $id;?>"><i class="fa fa-pie-chart" aria-hidden="true"></i> Tỉ lệ thưởng</a>
                            <a href="<?php echo ROOTHOST."members/wallet/".$id;?>"  class="dropdown-item" data-id="<?php echo $id;?>"><i class="fa fa-line-chart" aria-hidden="true"></i> Doanh số</a>
                            <span class="change_pass dropdown-item" dataid="<?php echo $id;?>"><i class="fa fa-unlock-alt" aria-hidden="true"></i> Đổi mật khẩu</span>
                            <a href="#" onclick="callActionMember(this, 3)" class="dropdown-item delete"  data-id="<?php echo $id;?>"><i class="fa fa-trash-o" aria-hidden="true"></i> Xóa</a>
                        </div>
                    </div>
                </td>
            </tr>
            <?php }?>
        </tbody>
    </table>
<?php
}
    if($type==1) return $arr_user;
}?>