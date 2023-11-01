<?php	include_once("../classes/tenant_other_family.class.php");
		include_once("../classes/include/dbop.class.php");
	  	$dbConn = new dbop();
		$landLordDB = new dbop(false, false, false, false, true);
		$obj_tenant_other_family=new tenant_other_family($dbConn, $landLordDB);
		$validator = $obj_tenant_other_family->startProcess();
?>
<html>
<body>
<form name="Goback" method="post" action="<?php echo $obj_tenant_other_family->actionPage; ?>">

	<?php

	if($validator=="Insert")
	{
	$ShowData="Record Added Successfully";
	}
	else if($validator=="Update")
	{
	$ShowData="Record Updated Successfully";
	}
	else if($validator=="Delete")
	{
	$ShowData="Record Deleted Successfully";
	}
	else
	{
		foreach($_POST as $key=>$value)
		{
		echo "<input type=\"hidden\" name=\"$key\" value=\"$value\" />";
		}
		$ShowData=$validator;
	}
	?>

<input type="hidden" name="ShowData" value="<?php echo $ShowData; ?>">
<input type="hidden" name="scm">
</form>
<script>
	document.Goback.submit();
</script>
</body>
</html>
