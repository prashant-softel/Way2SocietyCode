<?php	
//echo "try";
include_once("../classes/billdetails_import.class.php"); 
include_once("../classes/billdetails_tenant_import.class.php"); 
include_once("../classes/include/dbop.class.php");
		$dbConn = new dbop();
		$dbConnRoot = new dbop(true);
		$ErrorLog='';
		$actionPage="";
		$obj_opImport = new billdetails_import($dbConnRoot,$dbConn);
		$obj_teImport = new billdetails_tenant_import($dbConnRoot,$dbConn);
		//$errofile_name = 'import_openingbalance_errorlog_'.date("dmY").'_'.rand().'.html';
		//$errorfile = fopen($errofile_name, "a");
		if($_SESSION['res_flag'] == 1 || $_SESSION['rental_flag'] == 1){
					
			echo "filename". $fileName;
			$validator = $obj_teImport->ImportData1($_SESSION['society_id']);
			echo "print";
		}else{
			$validator = $obj_opImport->ImportData($_SESSION['society_id']);
		}
		//$validator = $obj_opImport->UploadData($_FILES['upload_files']['tmp_name'][0],$errofile_name);
	    
		if($_SESSION['res_flag'] == 1 || $_SESSION['rental_flag'] == 1){
			$actionPage = $obj_teImport->actionPage;
			$ErrorLog = $obj_teImport->errorLog;
		}else{
			$actionPage = $obj_opImport->actionPage;
		    $ErrorLog = $obj_opImport->errorLog;
		}
		
		
		//echo $validator;
		
?>


<html>
<body>
<form name="Goback" method="post" action="<?php echo $actionPage ?>">
<?php 
foreach($_POST as $key=>$value)
{
	echo "<input type=\"hidden\" name=\"$key\" value=\"$value\" />";
}
$ShowData = $validator;
?>
<input type="hidden" name="ShowData" value="<?php echo $ShowData; ?>">

</form>
<script>
	window.open("<?php echo $ErrorLog ?>");
	document.Goback.submit();
</script>
</body>
</html>

