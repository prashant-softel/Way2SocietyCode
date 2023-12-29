<?php	include_once "../classes/view_member_profile_tenant_edit.class.php" ;
		include_once("../classes/include/dbop.class.php");
	  	$dbConn = new dbop();
		$landLordDB = new dbop(false, false, false, false, true);
		$obj_view_member_profile_tenant_edit = new view_member_profile_tenant_edit($dbConn, $landLordDB);
		$update_member_profile = $obj_view_member_profile_tenant_edit->update_member_profile();
?>

<script language="javascript" type="application/javascript">
	window.location.href = '../view_tenant_profile.php?prf&up&id=<?php echo $_POST['id'];?>&idd=<?php echo time();?>';
</script>
