<?php include_once("../classes/list_member.class.php");
  include_once("../classes/include/dbop.class.php");
  include_once("classes/utility.class.php");
   	$dbConn = new dbop();
	$dbConnRoot = new dbop(true);
	$landLordDB = new dbop(false,false,false,false,true);
	$obj_list_member = new list_member($dbConn, $dbConnRoot, $landLordDB);
	$m_objUtility = new utility($m_dbConn,$dbConnRoot);

	if(isset($_POST['selSocID']))
	{	
		$DBName = $m_objUtility->getDBName($_POST['selSocID']);		
		$_SESSION['landLordDB'] = $DBName;
		$_SESSION['landLordSocID'] = $_POST['selSocID'];
		exit;

	}
?>