<?php	
//echo "try";
include_once("../classes/pdc_list.class.php"); 
include_once("../classes/include/dbop.class.php");
include_once("ChequeDetails.class.php");
include_once("utility.class.php");
include_once("dbconst.class.php");
		$dbConn = new dbop();
		$dbConnRoot = new dbop(true);
        $landLordDB = new dbop(false,false,false,false,true);
        $landLordDBRoot = new dbop(false,false,false,false,false,true);
		$ErrorLog='';
		$actionPage="";
		
        $obj_utility= new utility($dbConn, $landLordDB);
		$obj_pdc_list = new pdc_list($dbConn,$dbConnRoot, $landLordDB,$landLordDBRoot);
		// $data = $obj_pdc_list->AddNewValues($_POST['sid']);	
		// $actionPage = $obj_pdc_list->actionPage;
		// $ErrorLog = $obj_pdc_list->errorLog;
		// echo "end";

    if(isset($_POST['chequeData']))
	{	
        $data =$_POST['chequeData'];
        $chequeData = $obj_pdc_list->insertData($data);
	echo $chequeData == true ? "Cheque Deposited Successfully" : "Data are missing Cheque is not deposited";
        $actionPage = $obj_pdc_list->actionPage;
	$ErrorLog = $obj_pdc_list->errorLog;
	return $chequeData;
	}
			
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
	// document.Goback.submit();
</script>
</body>
</html>

