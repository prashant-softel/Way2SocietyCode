<?php if(!isset($_SESSION)){ session_start(); }
include_once("dbconst.class.php");
include_once("utility.class.php");

class CSocietyDetails
{
	
	public $sSocietyName;
	public $sSocietyAddress ;
	public $sSocietyRegNo ;
	public $iSocietyID;
	
	public function __construct($dbConn)
	{
		$this->sSocietyName = "";
	    $this->sSocietyAddress = "";
	    $this->sSocietyRegNo = "";
	    $this->iSocietyID = 0;
	}
} 

class CashSummary
{	
	public $m_dbConn;
	public $objSocietyDetails;
	public $obj_utility;
		
	public function __construct($dbConn)
	{
		$this->m_dbConn = $dbConn;
		$this->objSocietyDetails = new CSocietyDetails($this->m_dbConn);		
		$this->obj_utility = new utility($this->m_dbConn);
	}
	
	function GetSocietyDetails($ReqSocietyID)
	{
		$sqlFetch = "select * from society where society_id=".$ReqSocietyID."";
		$res02 = $this->m_dbConn->select($sqlFetch); 		
		if($res02 <> "")
		{
			foreach($res02 as $row => $v )
			{
				$this->objSocietyDetails->sSocietyName = $res02[$row]['society_name'];
				$this->objSocietyDetails->sSocietyRegNo = $res02[$row]['registration_no'];	
				$this->objSocietyDetails->sSocietyAddress = $res02[$row]['society_add'];
			}
		}
		else
		{
			echo "No Data Found from society database test for society_id=<".$ReqSocietyID.">.";
		}
	}

	function getCashID()
	{
		$sql = 'SELECT `id` FROM `ledger` WHERE `categoryid` = '.CASH_ACCOUNT;
		$res = $this->m_dbConn->select($sql);
		//$result = $this->getPayments($res[0]['id'], $from, $to);
		return $res[0]['id'];
	}
	
	function getTotalReceivedAmount($ledgerID, $from, $to)
	{
		//$sql = 'SELECT `Date`, sum(PaidAmount) as "TotalPaidAmount", sum(ReceivedAmount) as "TotalReceivedAmount" FROM `bankregister` WHERE `LedgerID` = '.$ledgerID. ' AND `Date` BETWEEN "'.$from.'"  AND "'.$to. '"';					
		$sql = "SELECT sum(Amount) as 'total' FROM `paymentdetails` WHERE `PaidTo` = '".$ledgerID."' AND `VoucherDate` BETWEEN '".getDBFormatDate($from)."' AND '".getDBFormatDate($to)."'";
		$result = $this->m_dbConn->select($sql);
		$sql1 = "SELECT sum(Amount) as 'total' FROM `chequeentrydetails` WHERE `BankID` = '".$ledgerID."' AND `VoucherDate` BETWEEN '".getDBFormatDate($from)."' AND '".getDBFormatDate($to)."'";
		$res = $this->m_dbConn->select($sql1);		
		return $result[0]['total'] + $res[0]['total'];	
	}
	
	function getTotalPaidAmount($ledgerID, $from, $to)
	{
		$sql = "SELECT sum(Amount) as 'total' FROM `paymentdetails` WHERE `PayerBank` = '".$ledgerID."' AND `VoucherDate` BETWEEN '".getDBFormatDate($from)."' AND '".getDBFormatDate($to)."'";
		$result = $this->m_dbConn->select($sql);
		$sql1 = "SELECT sum(Amount) as 'total' FROM `chequeentrydetails` WHERE `PaidBy` = '".$ledgerID."' AND `VoucherDate` BETWEEN '".getDBFormatDate($from)."' AND '".getDBFormatDate($to)."'";
		$res = $this->m_dbConn->select($sql1);		
		return $result[0]['total'] + $res[0]['total'];
	}
	
	function getPrevYrOpeningBal($ledgerid,$from)
	{
		/*$sql = 'SELECT `ReceivedAmount` FROM `bankregister` WHERE `Is_Opening_Balance` = 1 AND `LedgerID` = '.$ledgerid;
		$result = $this->m_dbConn->select($sql);
		return $result[0]['ReceivedAmount'];*/
		$result = $this->obj_utility->getOpeningBalance($ledgerid,$from);
		return $result['Total'];
	}
	
	function getOpeningBalance($date, $ledgerid)
	{
		$sql = 'SELECT sum(PaidAmount) as "TPaidAmount", sum(ReceivedAmount) as "TReceivedAmount" FROM `bankregister` WHERE `Date` < "'.getDBFormatDate($date).'" AND `LedgerID` = "'.$ledgerid.'" AND `Is_Opening_Balance` = 0';
		$res = $this->m_dbConn->select($sql);
		return $res;
	}
	
	function getBankName($ledgerID)
	{
		$sql = 'SELECT `ledger_name` FROM `ledger` where `id` = '.$ledgerID;
		//echo $sql;
		$bankname = $this->m_dbConn->select($sql);
		return $bankname[0]['ledger_name']; 	
	}
		
	
	function getPaidDetails($ledgerID, $from, $to)
	{
		$paidDetails = array();
		$sql = "SELECT * FROM `paymentdetails` WHERE `PayerBank` = '".$ledgerID."' AND `VoucherDate` BETWEEN '".getDBFormatDate($from)."' AND '".getDBFormatDate($to)."'";	
		$res = $this->m_dbConn->select($sql);
		
		$ledger = $this->getLedgersArray();		
		for($i = 0; $i < sizeof($res); $i++)
		{
			$arr = array();
			$arr['VoucherDate'] = $res[$i]['VoucherDate'];
			$arr['PaidTo'] = $ledger[$res[$i]['PaidTo']];
			$arr['Amount'] = $res[$i]['Amount'];
			array_push($paidDetails, $arr);
		} 	
		
		$sqlQuery = "SELECT * FROM `chequeentrydetails` WHERE `PaidBy` = '".$ledgerID."' AND `VoucherDate` BETWEEN '".getDBFormatDate($from)."' AND '".getDBFormatDate($to)."'";
		$result = $this->m_dbConn->select($sqlQuery);
		
		for($i = 0; $i < sizeof($result); $i++)
		{ 
			$arr = array();
			$arr['VoucherDate'] = $result[$i]['VoucherDate'];
			$arr['PaidTo'] = $ledger[$result[$i]['BankID']];
			$arr['Amount'] = $result[$i]['Amount'];
			array_push($paidDetails, $arr);
		}
		return $paidDetails;
	}
}
?>