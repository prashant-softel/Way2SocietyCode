<?php
	session_start();
	include_once ("../classes/ledger_import.class.php");
	include_once ("../classes/include/dbop.class.php");
	require_once ("../classes/CsvOperations.class.php");

	$dbConn = new dbop();
	$dbConnRoot = new dbop(true);
	$validator = "";
    $csv = new CsvOperations();
	$checkBoxIndexes = array();
	$fileData = $_SESSION['file_data'];

	if (isset($_POST["submit"]))
	{
		$tempFileName = $_POST["tmpName"]; 
		$name = $_POST["name"];
		$error = $_POST["error"]; 
		$new_ledger = $_POST['new_ledger'];
		$opening_year = $_POST['opening_year1'];
		if ($error == 0)
		{
			$data = $_POST["data"];

			$checkBoxIndexes = explode(',', $data);

			$checkBoxIndexes = array_filter($checkBoxIndexes, function($value) { return $value !== ''; });
			
			$obj_ledger_import=new ledger_import($dbConnRoot, $dbConn);
			$validator = $obj_ledger_import->UploadDataManually($checkBoxIndexes, $fileData, $new_ledger,$opening_year);
			$actionPage = $obj_ledger_import->actionPage;
			$ErrorLog = $obj_ledger_import->errorLog;
			echo $validator;
		}
		else
		{
			switch ($error)
            {
                case 1:
                       echo '<p> The file is bigger than this PHP installation allows</p>';
                       // $result = '<p> The file is bigger than this PHP installation allows</p>';
                       break;
                case 2:
                       echo '<p> The file is bigger than this form allows</p>';
                       // $result = '<p> The file is bigger than this form allows</p>';
                       break;
                case 3:
                       echo '<p> Only part of the file was uploaded</p>';
                       // $result = '<p> Only part of the file was uploaded</p>';
                       break;
                case 4:
                       echo '<p> No file was uploaded</p>';
                       // $result = '<p> No file was uploaded</p>';
                   break;
            }
			
		}
	}
?>
 <html>
<body>
<form id="Goback" method="post" action="<?php echo $actionPage ?>">
<input type="hidden" name="mm" value="no value">
 </form>
<script>
	window.open("<?php echo $ErrorLog ?>");
	document.getElementById("Goback").submit();
 </script>
 </body>
 </html>