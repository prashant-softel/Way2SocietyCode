<?php include_once("../classes/tenant.class.php");
  include_once("../classes/include/dbop.class.php");
  include_once("classes/utility.class.php");
   	$dbConn = new dbop();
	$dbConnRoot = new dbop(true);
	$landLordDB = new dbop(false,false,false,false,true);
	$landLordDBRoot = new dbop(false,false,false,false,false,true);
	$obj_tenant = new tenant($dbConn, $dbConnRoot, $landLordDB, $landLordDBRoot);
	$m_objUtility = new utility($m_dbConn,$dbConnRoot);

	if(isset($_POST['selSocID']))
	{	
		$DBName = $m_objUtility->getDBName($_POST['selSocID']);		
		$_SESSION['landLordDB'] = $DBName;
		$_SESSION['landLordSocID'] = $_POST['selSocID'];
		exit;

	}

	if(isset($_POST['selLandlord']))
	{	
        //echo "call";
        $landlord = $_POST['selLandlord'];
        //echo "ID" .$landlord;
		$_SESSION['default_Sundry_debetor'] = $landlord;
		exit;

	}
	if (isset($_POST['wing_id'])) 
	{
		$wing_id = $_POST['wing_id'];
		// echo"wingid: ".$wing_id;
	    echo "ID: ".$wing_id;
		$_SESSION['default_wing_id'] = $wing_id;
        exit;
		
	}

	$validator = $obj_tenant->startProcess();
	 // echo $validator;
?>

<html>
<body>
<font color="#FF0000" size="+2">Please Wait...</font>

<form name="Goback" method="post" action="<?php echo $obj_tenant->actionPage; ?>">
	<?php
	if($validator=="Insert")
	{
		$ShowData = "Record Added Successfully";
	}
	else if($validator=="Update")
	{
		$ShowData = "Record Updated Successfully";
	}
	else if($validator=="Delete")
	{
		$ShowData = "Record Deleted Successfully";
	}
	else
	{
		foreach($_POST as $key=>$value)
		{
			echo "<input type=\"hidden\" name=\"$key\" value=\"$value\" />";
		}
		$ShowData = $validator;
	}
	?>

<input type="hidden" name="ShowData" value="<?php echo $ShowData; ?>">
</form>

<script>
document.Goback.submit();
</script>

</body>
</html>
