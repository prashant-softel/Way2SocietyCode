<?php 
include_once("../classes/include/dbop.class.php");
include_once("../classes/dbconst.class.php");
include_once("../classes/ContributionLedgerDetailed.class.php");
include_once("../classes/genbill.class.php");
$dbConn = new dbop();
$landLordDB = new dbop(false,false,false,false,true);
$obj_ContributionLedgerDetailed = new ContributionLedgerDetailed($dbConn,$landLordDB);
$obj_genbill = new genbill($dbConn, $landLordDB);


if($_REQUEST["method"] == 'fetch')
{

	$finalArray = array();
	$societyID = $_REQUEST['societyID'];
	$unitIDArray = json_decode(str_replace('\\', '', $_REQUEST['unitIDArray']), true);
	if($_SESSION['res_flag'] == 1){
		// echo "id" .$_SESSION['landLordSocID'];
		$sqlBillcycle = "select `society_name` from `society` where `society_id` = '".$_SESSION['landLordSocID']."' " ;
		$resBillcycle = $landLordDB->select($sqlBillcycle);
	}else{
		// echo "id" .$societyID;
		$sqlBillcycle = "select `society_name` from `society` where `society_id` = '".$societyID."' " ;
		$resBillcycle = $dbConn->select($sqlBillcycle);
	}
	// echo "name: ".$resBillcycle[0]['society_name'];
	echo "<div style='display:none;' id='societyname'><center><h1><font>"  .$resBillcycle[0]['society_name']. "</font></h1></center></div>";	
	echo "<div><center><font>BILL SUMMARY WITH BIFURCATION</font></center></div>";	
	$unitdata = implode(" , ", $unitIDArray);
	// echo $unitdata;

	$sqlLedger = "select ledger_id from tenant_module where unit_id in( ".$unitdata.") and end_date >= curdate()";
	if($_SESSION['res_flag'] == 1){
		$resLedger = $landLordDB->select($sqlLedger);
	}else{	
		$resLedger = $dbConn->select($sqlLedger);
	}
	// echo "<pre>";
	// print_r($resLedger);
	// echo "</pre>";
	$ledgerid = array_column($resLedger, 'ledger_id');
	// echo "<pre>";
	// print_r($ledgerid);
	// echo "</pre>";
		for($j = 0;$j < sizeof($ledgerid); $j++)
		{
			// echo $ledgerid[$j];
			// exit;
			if($_SESSION['res_flag'] == 1){
				$finalArray = $obj_ContributionLedgerDetailed->getCollection_res($_SESSION['landLordSocID'],$ledgerid[$j]);
			}else if($_SESSION['rental_flag'] == 1){
				$finalArray = $obj_ContributionLedgerDetailed->getCollection_res($societyID,$ledgerid[$j]);
			}else{
				$finalArray = $obj_ContributionLedgerDetailed->getCollection($societyID,$unitIDArray[$i]);
			}
	
			if($_REQUEST['ignore_zero'] == 'true')
			{
				$finalArray = $obj_genbill->unsetZeroKeysFromArray($finalArray , $obj_ContributionLedgerDetailed->checkZero);
			}
			
			if(sizeof($finalArray) > 0 )
			{
				$obj_ContributionLedgerDetailed->displayResults1($finalArray);
				//array_push($finalArray,$tempArray);
			}
		}
		
	
	//$finalArray = $obj_ContributionLedgerDetailed->getCollection($societyID,$unitIDArray[$i]);
	
	
	
	// print_r($finalArray);
	//$obj_ContributionLedgerDetailed->displayResults1($finalArray);
	
}

?>