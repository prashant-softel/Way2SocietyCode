<?php
include_once("utility.class.php");
include_once("view_ledger_details.class.php");
include_once("dbconst.class.php");
include_once ("include/fetch_data.php");
include_once('../swift/swift_required.php');
include_once("email.class.php");
class tenant_report
{
	public $m_dbConn;
	public $m_dbConnRoot;
	public $m_landlord;
	public $obj_utility;
	public $obj_fetch;
	public $obj_ledger_details;

	function __construct($dbConn, $dbConnRoot = "",$landlord ="")
	{
		$this->m_dbConn = $dbConn;
		$this->m_dbConnRoot = $dbConnRoot;
		$this->m_landlord = $landlord;
		$this->obj_utility = new utility($this->m_dbConn, $this->m_dbConnRoot);
		$this->obj_ledger_details=new view_ledger_details($this->m_dbConn);
		$this->obj_fetch = new FetchData($this->m_dbConn);
	}
	
	public function show_owner_name($uid,$to = "")
	{ 
		  if($to <> "")
		  {
			$tenantIDS = $this->obj_utility->getTenantIDs($to);	
			//echo "Tenant: " .$tenantIDS;
			$sql="select tenant_id,tenant_name,mobile_no,email,unittbl.unit_no,wingtbl.wing from tenant_module as tenant JOIN `unit` as unittbl on unittbl.unit_id = tenant.unit_id JOIN `wing` as wingtbl on unittbl.wing_id = wingtbl.wing_id where tenant.unit_id ='".$uid."' and  tenant.tenant_id IN (".$tenantIDS.") Group BY tenant.tenant_id  ";
		  }
		  else
		  {
            $tenantIDS = $this->obj_utility->getTenantIDs($to);	
            $sql="select tenant_id,tenant_name,mobile_no,email,unittbl.unit_no,wingtbl.wing from tenant_module as tenant JOIN `unit` as unittbl on unittbl.unit_id=tenant.unit_id JOIN `wing` as wingtbl on unittbl.wing_id=wingtbl.wing_id where tenant.unit_id ='".$uid."' and  tenant.tenant_id IN (".$tenantIDS.")  Group BY tenant.tenant_id  ";
		 }
		  $data=$this->m_dbConn->select($sql);
		  return $data;
	
	  
	}
	public function show_tenant_name($uid,$to = "",$rental=false)
	{ 
	
		  if($to <> "")
		  {
			
			$sql="select tenant_id,tenant_name,mobile_no,email,unittbl.unit_no,wingtbl.wing from tenant_module as tenant JOIN `unit` as unittbl on unittbl.unit_id = tenant.unit_id JOIN `wing` as wingtbl on unittbl.wing_id = wingtbl.wing_id where tenant.tenant_id ='".$uid."'";
		  }
		  else
		  {
           
            $sql="select tenant_id,tenant_name,mobile_no,email,unittbl.unit_no,wingtbl.wing from tenant_module as tenant JOIN `unit` as unittbl on unittbl.unit_id=tenant.unit_id JOIN `wing` as wingtbl on unittbl.wing_id=wingtbl.wing_id where tenant.tenant_id ='".$uid."'";
		 }
		 //echo "sql =>".$sql;
		 if($rental == true)
		 {
			 $data=$this->m_landlord->select($sql);
		 }
		 else
		 {
			$data=$this->m_dbConn->select($sql);
		 }
		//print_r($data);
		  return $data;
	
	  
	}
	
	public function show_due_details($uid,$from = "",$to ="",$rental=false)
	{	  	
		$starting3Month = array('04','05','06','07','08','09');  
	 // $sql = "select assettbl.Date, ledgertbl.ledger_name as Particular, Debit, Credit,VoucherID,VoucherTypeID from `assetregister` as assettbl JOIN `ledger` as ledgertbl on assettbl.LedgerID=ledgertbl.id where assettbl.LedgerID='".$uid."' and assettbl.Is_Opening_Balance= 0";
	//$sql="select assettbl.Date, ledgertbl.ledger_name as Particular,bdetail.BillSubTotal,bdetail.AdjustmentCredit,bdetail.BillInterest,assettbl.Debit, assettbl.Credit,bdetail.PrincipalArrears, bdetail.InterestArrears,assettbl.VoucherID,assettbl.VoucherTypeID,v.ID,v.RefNo from `assetregister` as assettbl JOIN `ledger` as ledgertbl on assettbl.LedgerID=ledgertbl.id join voucher as v on v.ID=assettbl.VoucherID join billdetails as bdetail on v.RefNo=bdetail.ID where assettbl.LedgerID='".$uid."' and assettbl.Is_Opening_Balance= 0";
	$sql ="select assettbl.Date, ledgertbl.ledger_name as Particular,bdetail.BillSubTotal,bdetail.AdjustmentCredit,
	      bdetail.CGST,bdetail.SGST, s.CGST as invoice_cgst, s.SGST as invoice_sgst, s.Ledger_round_off as invoice_round_off_amount,  bdetail.BillInterest,assettbl.Debit, assettbl.Credit,bdetail.PrincipalArrears, bdetail.InterestArrears,assettbl.VoucherID,assettbl.VoucherTypeID,v.ID,v.RefNo from `assetregister` as assettbl JOIN `ledger` as ledgertbl on assettbl.LedgerID=ledgertbl.id  left join voucher as v on v.ID=assettbl.VoucherID left join billdetails as bdetail on v.RefNo=bdetail.ID left join sale_invoice as s ON v.RefNo = s.ID where assettbl.LedgerID='".$uid."' and assettbl.Is_Opening_Balance= 0";
	  	if($from <> "" && $to <> "")
		{
		   $sql .= "  and assettbl.Date BETWEEN '".$from."' AND '".$to."'";		
		}
		else if($_SESSION['default_year_start_date'] <> 0  && $_SESSION['default_year_end_date'] <> 0)
	   {
		  
		   $from           = $_SESSION['default_year_start_date'];
		   $to             = $_SESSION['default_year_end_date'];
		   
		   $sql .= "  and assettbl.Date BETWEEN '".getDBFormatDate($from)."' AND '".getDBFormatDate($to)."'";					
	   }
	   $sql .= " ORDER BY assettbl.Date";
	  // echo  $sql;
	   if($rental == true)
		 {
			 $data=$this->m_landlord->select($sql);
		 }
		 else
		 {
			$data=$this->m_dbConn->select($sql);
		 }
	   //$data = $this->m_dbConn->select($sql);
	   return $data;
	}
	
	public function getParticularDetails($uid,$voucherdate)
	{
		$aryParent = $this->obj_utility->getParentOfLedger($uid);
		$res45 = $this->details($aryParent['group'],$uid,$voucherdate);	
		if($res45[0]['VoucherID'] == 0)
		{
			 return 'Opening Balanace';
		}
		else
		{
			$res46 = $this->details2($uid,$res45[0]['VoucherID'],$res45[0]['VoucherTypeID']);
			return $res46;
		 }
	}
	
	public function details2($lid, $vid, $vtype = 0, $debit = 0, $credit = 0,  $byORto = "",$IsInvoice,$rental=false)
	{	
	//echo "<br>LID=>".$lid;	
	//echo "<br>VID=>".$vid;	
	//echo "<br>Vtype=>".$vtype;	
	//echo "<br>Debit=>".$debit;	
	//echo "<br>Credit=>".$credit;	
	//echo "<br>By or to=>".$byORto;
	//echo "<br>is Invoice=>".$IsInvoice;
	//echo "<br>rental=>".$rental;	
		$sql1 = "select `desc` from `vouchertype` where id='".$vtype."'";	
		 if($rental == true)
		 {
			 $data1=$this->m_landlord->select($sql1);
		 }
		 else
		 {
			$data1=$this->m_dbConn->select($sql1);
		 }
		//$data1 = $this->m_dbConn->select($sql1);
		 $voucher = $data1[0]['desc'];		
		 $sql2 = "select RefNo,RefTableID,VoucherNo from `voucher` where id='".$vid."' ";
		// echo "Rental ::".$rental;
		 if($rental == true)
		 {
			 //echo "Inside ";
			 $data2=$this->m_landlord->select($sql2);
		 }
		 else
		 {
			 //echo "else";
			$data2 =$this->m_dbConn->select($sql2);
		 }
		
		$RefNo = $data2[0]['RefNo'];
		$RefTableID = $data2[0]['RefTableID'];
		$VoucherNo = $data2[0]['VoucherNo'];	
		
		if($byORto <> "" && $voucher == "Journal Voucher" || $RefTableID == TABLE_CREDIT_DEBIT_NOTE)
		{
			//echo "Inside IF";	
		 $sql3 = "select ledgertbl.id,`ledger_name`,vouchertbl.Note,vouchertbl.RefTableID,vouchertbl.RefNo,vouchertbl.VoucherNo,vouchertbl.".$byORto." as 'To' from `voucher` as vouchertbl JOIN `ledger` as ledgertbl on vouchertbl.".$byORto."=ledgertbl.id where vouchertbl.RefNo='".$RefNo."' and vouchertbl.RefTableID='".$RefTableID."' and vouchertbl.VoucherNo='".$VoucherNo."'";		
		}
		else
		{
			//echo "Inside else";
		 	$sql3 = "select ledgertbl.id,`ledger_name`,vouchertbl.Note,vouchertbl.RefTableID,vouchertbl.RefNo,vouchertbl.VoucherNo,vouchertbl.To as 'To' from `voucher` as vouchertbl JOIN `ledger` as ledgertbl on vouchertbl.To=ledgertbl.id where vouchertbl.RefNo='".$RefNo."' and vouchertbl.RefTableID='".$RefTableID."' and vouchertbl.VoucherNo='".$VoucherNo."'";		
		}
		
		//echo $sql3;
		 if($rental == true)
		 {
			 $data3=$this->m_landlord->select($sql3);
		 }
		 else
		 {
			$data3 = $this->m_dbConn->select($sql3);
		 }
		//$data3 = $this->m_dbConn->select($sql3);		
		$data3[0]['voucher_name'] = $voucher;
		if($data3[0]['RefTableID'] == TABLE_SALESINVOICE)
		{
			//*** Setting  Billtype 2 for sale invoice data
			$data3[0]['BillType'] = 2;
		}
		else
		{
			$data3[0]['BillType'] = 1;	
		}
					
		if($voucher == 'Sales Voucher')
		{
			$sqlBill = "SELECT `BillNumber`,`PeriodID`,`BillType` FROM `billdetails` where `ID`= '".$data3[0]['RefNo']."' ";
			if($rental == true)
		 	{
			 	$billresult=$this->m_landlord->select($sqlBill);
		 	}
		 	else
		 	{
				$billresult = $this->m_dbConn->select($sqlBill);
		 	}
			//$billresult = $this->m_dbConn->select($sqlBill);
			$data3[0]['BillNumber'] = $billresult[0]['BillNumber'];	
			$data3[0]['PeriodID'] = $billresult[0]['PeriodID'];	
			$data3[0]['BillType'] = $billresult[0]['BillType'];	
		}		
		$status = "";
		
		if($voucher == "Payment Voucher")
		{ 
			$PaymentDetailsSql = "SELECT pd.PayerBank, pd.ChqLeafID,pd.ChequeNumber, chklb.CustomLeaf FROM paymentdetails as pd JOIN chequeleafbook as chklb ON pd.ChqLeafID = chklb.id  WHERE pd.id = '".$RefNo."'";
			if($rental == true)
		 	{
			 	$PaymentResult=$this->m_landlord->select($PaymentDetailsSql);
		 	}
		 	else
		 	{
				$PaymentResult = $this->m_dbConn->select($PaymentDetailsSql);
		 	}
			//$PaymentResult = $this->m_dbConn->select($PaymentDetailsSql);
			$data3[0]['PayerBank'] = $PaymentResult[0]['PayerBank'];	
			$data3[0]['ChqLeafID'] = $PaymentResult[0]['ChqLeafID'];
			$data3[0]['CustomLeaf'] = $PaymentResult[0]['CustomLeaf'];
			$data3[0]['ChequeNumber'] = $PaymentResult[0]['ChequeNumber'];
		}
		
		if($RefNo <> "")
		{
			if($RefTableID <> TABLE_CREDIT_DEBIT_NOTE && $RefTableID <> TABLE_SALESINVOICE)
			{
			$category = $this->obj_utility->getParentOfLedger($data3[0]['To']);	
			$CategoryID = $category['category'];

			if($RefTableID == TABLE_PAYMENT_DETAILS) {
				$sqlCheque="SELECT pmtdetail.ChequeNumber, pmtdetail.PaidTo, pmtdetail.PayerBank, pmtdetail.Bill_Type as BillType FROM `paymentdetails` as pmtdetail where pmtdetail.id='".$data3[0]['RefNo']."'";
				if($rental == true)
		 		{
			 		$Chequedetails=$this->m_landlord->select($sqlCheque);
		 		}
		 		else
		 		{
					$Chequedetails = $this->m_dbConn->select($sqlCheque);
		 		}
				//$Chequedetails = $this->m_dbConn->select($sqlCheque);
			}
			
			if($Chequedetails <> "")
			{
				//Check whether its return entry of receipt
				$sqlChkEntry = "SELECT cheque.BillType, bank.return, bank.ReceivedAmount FROM chequeentrydetails as cheque JOIN (SELECT b2.ChkDetailID, b2.`return`, b2.`ReceivedAmount` FROM `bankregister` as b1 INNER JOIN `bankregister` as b2 ON b1.Ref = b2.id where b1.Ref != 0 and b1.chkDetailID = '".$data3[0]['RefNo']."' and b1.voucherTypeID IN('".VOUCHER_PAYMENT."', '".VOUCHER_CONTRA."')) as bank ON cheque.ID = bank.ChkDetailID";
				if($rental == true)
		 		{
			 		$ChequeEntrydetails=$this->m_landlord->select($sqlChkEntry);
		 		}
		 		else
		 		{
					$ChequeEntrydetails = $this->m_dbConn->select($sqlChkEntry);
		 		}
				//$ChequeEntrydetails = $this->m_dbConn->select($sqlChkEntry);
				
				$sqlBank="SELECT * FROM `ledger` where id='".$Chequedetails[0]['PayerBank']."'";
				//$BankDetail = $this->m_dbConn->select($sqlBank);
				if($rental == true)
		 		{
			 		$BankDetail=$this->m_landlord->select($sqlBank);
		 		}
		 		else
		 		{
					$BankDetail = $this->m_dbConn->select($sqlBank);
		 		}
				// It is return entry of receipt then it's bill type will same as receipt
				if(!empty($ChequeEntrydetails)){
					$data3[0]['Return']   = $ChequeEntrydetails[0]['return'];
					$data3[0]['BillType'] = $ChequeEntrydetails[0]['BillType'];	
				}
				else{
					// It is not return entry and present in only in payment then bill type will same as payment	
					$data3[0]['BillType'] = $Chequedetails[0]['BillType']; 
				}
				$data3[0]['ChequeNumber'] = $Chequedetails[0]['ChequeNumber'];
				$data3[0]['PayerBank'] 	  = $BankDetail[0]['ledger_name'];
				$data3[0]['BankID'] 	  = $Chequedetails[0]['PayerBank'];	
				
			}
			
				if($credit > 0)
				{
					$bankRegQuery = 'SELECT `Reconcile`, `Return`, `ChkDetailID`,`DepositGrp` FROM `bankregister` WHERE `ChkDetailID` = "'.$RefNo.'" AND `ReceivedAmount` > 0';
					if($rental == true)
		 			{
			 			$res=$this->m_landlord->select($bankRegQuery);
		 			}
		 			else
		 			{
						$res = $this->m_dbConn->select($bankRegQuery);
		 			}
					//$res = $this->m_dbConn->select($bankRegQuery);
					$data3[0]['DepositGrp'] = $res[0]['DepositGrp'];
				}
				elseif($debit > 0)
				{
					$bankRegQuery = 'SELECT `Reconcile`, `Return`, `ChkDetailID`,`DepositGrp` FROM `bankregister` WHERE `ChkDetailID` = "'.$RefNo.'" AND `PaidAmount` > 0';
					//$res = $this->m_dbConn->select($bankRegQuery);
					if($rental == true)
		 			{
			 			$res=$this->m_landlord->select($bankRegQuery);
		 			}
		 			else
		 			{
						$res = $this->m_dbConn->select($bankRegQuery);
		 			}
					$data3[0]['DepositGrp'] = $res[0]['DepositGrp'];
				}	
				
				
				
				if($res[0]['Reconcile'] > 0)
				{
					$status = "<span style='color:green'>Cleared</span>";
				}
				elseif($res[0]['Return'] > 0)
				{
					$status = "<span style='color:red'>Rejected</span>";	
				}
				else
				{
					$status = "<span style='color:blue'>Received</span>";	
				}
			//}
			}
			if($RefTableID <> "")
			{											
				if($RefTableID == 2)
				{	
					$sql = "SELECT cheque_details.`ChequeNumber`, cheque_details.`PayerBank`, cheque_details.`PayerChequeBranch`,cheque_details.`BillType`,period.`ID` as PeriodID FROM `chequeentrydetails` as cheque_details join period on cheque_details.ChequeDate between period.BeginingDate and period.EndingDate WHERE cheque_details.`ID` = '".$res[0]['ChkDetailID']."'";				
					//echo $sql = "SELECT `ChequeNumber`, `PayerBank`, `PayerChequeBranch`,`BillType` FROM `chequeentrydetails` WHERE `ID` = '".$res[0]['ChkDetailID']."'";									
					//$result = $this->m_dbConn->select($sql);
					if($rental == true)
		 			{
			 			$result=$this->m_landlord->select($sql);
		 			}
		 			else
		 			{
						$result = $this->m_dbConn->select($sql);
		 			}					
					$data3[0]['ChequeNumber'] = $result[0]['ChequeNumber'];
					$data3[0]['PayerBank'] = $result[0]['PayerBank'];
					$data3[0]['PayerChequeBranch'] =  $result[0]['PayerChequeBranch'];
					$data3[0]['BillType'] = $result[0]['BillType'];	
					$data3[0]['PeriodID'] = $result[0]['PeriodID'];
					$data3[0]['BankID'] = $result[0]['PayerBank'];
					
				}														
			}
			
			if($voucher=="Sales Voucher")
			{
				$sqlQuery = 'SELECT `PeriodID`,`BillType` FROM `billdetails` WHERE `ID` = '.$RefNo;
				//$res = $this->m_dbConn->select($sqlQuery);
				if($rental == true)
				{
					$res=$this->m_landlord->select($sqlQuery);
				}
				else
				{
					$res = $this->m_dbConn->select($sqlQuery);
				}
				if($res <> "")
				{
					$sqlPeriod = "Select periodtbl.type, yeartbl.YearDescription from period as periodtbl JOIN year as yeartbl ON periodtbl.YearID = yeartbl.YearID where periodtbl.ID = '" . $res[0]['PeriodID'] . "'";
				
					//$sqlResult = $this->m_dbConn->select($sqlPeriod);
					if($rental == true)
					{
						$sqlResult=$this->m_landlord->select($sqlPeriod);
					}
					else
					{
						$sqlResult = $this->m_dbConn->select($sqlPeriod);
					}
					$data3[0]['billFor'] =  $sqlResult[0]['type'] . " "  . $sqlResult[0]['YearDescription'];
					$data3[0]['BillType'] = $res[0]['BillType'];



					$sqlBillRegister = "SELECT  `BillFor_Message` FROM `billregister` where `PeriodID`='".$res[0]['PeriodID']."' AND `BillType`='".$res[0]['BillType']."' ";
					//echo $sqlBillRegister;
					if($rental == true)
					{
						$BillRegResult=$this->m_landlord->select($sqlBillRegister);
					}
					else
					{
						$BillRegResult = $this->m_dbConn->select($sqlBillRegister);
					}
					//$BillRegResult = $this->m_dbConn->select($sqlBillRegister);
					//print_r($BillRegResult);
					//echo "Msg:".$BillRegResult[0]["BillFor_Message"];
					$BillMsg =$BillRegResult[0]["BillFor_Message"];
					if($BillMsg != "")
					{
						$data3[0]['billFor'] =  $BillMsg;
					}
				}
			}
		}
		
		//var_dump($data3);
		if($data3[0]['RefTableID'] == TABLE_CREDIT_DEBIT_NOTE)
		{
			$data3[0]['RefNo'] = $RefNo;
			$BillTypeQuery = "SELECT BillType FROM credit_debit_note WHERE ID = '".$RefNo."'";
			if($rental == true)
			{
				$BillTypeResult=$this->m_landlord->select($BillTypeQuery);
			}
			else
			{
				$BillTypeResult =  $this->m_dbConn->select($BillTypeQuery);
			}
			//$BillTypeResult =  $this->m_dbConn->select($BillTypeQuery);
			$data3[0]['BillType'] = $BillTypeResult[0]['BillType'];
			
			if($byORto == 'By')
			{
				$data3[0]['VoucherType'] = VOUCHER_CREDIT_NOTE;
				$data3[0]['voucher_name'] = 'Credit Note';	
			}
			else
			{
				$data3[0]['VoucherType'] = VOUCHER_DEBIT_NOTE;
				$data3[0]['voucher_name'] = 'Debit Note';
			}
			$data3[0]['DepositGrp'] = 0;
		}
		
		$data3[0]['Status'] = $status;
		return $data3;
			
	}
	
	public function getInvoiceNumbers($ID)
	{
		//*** For Invoice bill fetching Invoice Number and sub total 
		$UnitIDquery = "Select Inv_Number,InvSubTotal from sale_invoice where id = '".$ID."' order by Inv_date";
		$result = $this->m_dbConn->select($UnitIDquery);
		return $result; 
	}
	
	
	public function details($gid,$lid,$voucherdate)
	{
		  if($gid == 1)
		  {
			$sql = "select ledgertbl.id,Date,ledgertbl.ledger_name as Particular, Debit, Credit,VoucherID,VoucherTypeID,Is_Opening_Balance from `liabilityregister` as liabilitytbl JOIN `ledger` as ledgertbl on liabilitytbl.LedgerID=ledgertbl.id where liabilitytbl.LedgerID='".$lid."' and Date='".$voucherdate."' ORDER BY Date ASC";
			$data = $this->m_dbConn->select($sql);
		  }
		  else if($gid == 2)
		  {
			
			$categoryid = $this->obj_utility->getParentOfLedger($lid);
			if($categoryid['category'] == BANK_ACCOUNT)
			{ 
				$sql = "select ledgertbl.id,Date,ledgertbl.ledger_name as Particular,PaidAmount as Debit,ReceivedAmount as  Credit,VoucherID,VoucherTypeID,Is_Opening_Balance from `bankregister` as banktbl JOIN `ledger` as ledgertbl on banktbl.LedgerID=ledgertbl.id  where banktbl.LedgerID='".$lid."' and Date='".$voucherdate."' ORDER BY Date ASC";		  
			}
			else
			{
			   $sql = "select ledgertbl.id,Date,ledgertbl.ledger_name as Particular, Debit, Credit,VoucherID,VoucherTypeID,Is_Opening_Balance	 from `assetregister` as assettbl JOIN `ledger` as ledgertbl on assettbl.LedgerID=ledgertbl.id  where assettbl.LedgerID='".$lid."'  and Date='".$voucherdate."' ORDER BY Date ASC";
			}
			
			
			$data = $this->m_dbConn->select($sql);
		 }
	  	 else if($gid == 3)
		 {
			$sql = "select ledgertbl.id,Date, ledgertbl.ledger_name as Particular, Debit, Credit,VoucherID,VoucherTypeID from `incomeregister` as incometbl JOIN `ledger` as ledgertbl on incometbl.LedgerID=ledgertbl.id where incometbl.LedgerID='".$lid."' and Date='".$voucherdate."' ORDER BY Date ASC";
			$data = $this->m_dbConn->select($sql);
		 }
		 else if($gid == 4)
		 {
			$sql = "select ledgertbl.id,Date,ledgertbl.ledger_name as Particular, Debit, Credit,VoucherID,VoucherTypeID from `expenseregister` as expensetbl JOIN `ledger` as ledgertbl on expensetbl.LedgerID=ledgertbl.id where expensetbl.LedgerID='".$lid."' and Date='".$voucherdate."' ORDER BY Date ASC";
			$data = $this->m_dbConn->select($sql);
		 }
	  	return $data;
	}	
	
	public function sendEmail()
	{
		if(isset($_REQUEST['unitID']))
		{
			try
			{
				$unitID = $_REQUEST['unitID'];
				
				$unitNo = 'All';
				
				$memberDetails = $this->obj_fetch->GetMemberDetails($unitID);
				if($unitID <> 0)
				{
					$unitNo = $this->obj_fetch->GetUnitNumber($unitID);
				}
	
				$mailSubject = 'Memeber Ledger Report For All Unit';
				$mailBody = 'Attached Memeber Ledger Report For All Unit';
				
				if($_REQUEST['emailMessage'] <> '')
				{
					$mailBody = $_REQUEST['emailMessage'];
				}
				if($_REQUEST['emailSubjectHead'] <> '')
				{
					$mailSubject = $_REQUEST['emailSubjectHead'];
				}
				
				//$societyDetails = $this->obj_fetch->GetSocietyDetails($this->obj_fetch->GetSocietyID($unitID));
				$societyDetails = $this->obj_fetch->GetSocietyDetails($_SESSION['society_id']);
				$societyCCEmailID = $this->obj_fetch->objSocietyDetails->sSocietyCC_Email;
				
				$mailToEmail = $this->obj_fetch->objMemeberDetails->sEmail;
				//$mailToEmail = "societyaccounts@pgsl.in";
				
				/*if($mailToEmail == '')
				{
					echo 'Email ID Missing';
					return 'Email ID Missing';
					exit();
				}*/
				
				if($_REQUEST['emailID'] <> '')
				{
					$mailToEmail = $_REQUEST['emailID'];
				}
				
				$mailToName = $this->obj_fetch->objMemeberDetails->sMemberName;
				
				$baseDir = dirname( dirname(__FILE__) );
				 
				 $specialChars = array('/','.', '*', '%', '&', ',', '(', ')', '"');
				$unitNo = str_replace($specialChars,'',$unitNo);
			
				$fileName =  $baseDir . "/Reports/" . $this->obj_fetch->objSocietyDetails->sSocietyCode . "/MemberLedgerReport-" . $this->obj_fetch->objSocietyDetails->sSocietyCode . '-' . $unitNo .'.pdf';
				
				if(!file_exists($fileName))
				{
					return 'Report does not exist.';
					exit();
				}
				
				$EMailIDToUse = $this->obj_utility->GetEmailIDToUse(false, 0, 0, 0, 0, 0, $_SESSION['society_id']);
				
				//print_r($EMailIDToUse);
				// AWS Config
				$AWS_Config = CommanEmailConfig();
				//print_r($AWS_Config);
				 $transport = Swift_SmtpTransport::newInstance($AWS_Config[0]['Endpoint'],$AWS_Config[0]['Port'] , $AWS_Config[0]['Security'])
				  ->setUsername($AWS_Config[0]['Username'])
				  ->setPassword($AWS_Config[0]['Password']);	 
				//$EMailID = $EMailIDToUse['email'];
				//$Password = $EMailIDToUse['password'];

				// Create the mail transport configuration
				//$transport = Swift_SmtpTransport::newInstance('103.50.162.146',587)
					 // ->setUsername($EMailID)
					 // ->setSourceIp('0.0.0.0')
					//  ->setPassword($Password) ;	 
				
					  
				// Create the message
				$message = Swift_Message::newInstance();
				
				if($mailToEmail != "")
				 {
					 $arEmailDs = explode(";", $mailToEmail);
					 $arIDs = array();
					 
					 foreach($arEmailDs as $sKey => $sCurEMailID)
					 {
					 	if($sCurEMailID <> '')
					 	{
						 	array_push($arIDs, $sCurEMailID);
					 	}
					 }
					 $message->setTo($arIDs);
				 }

				/*$message->setTo(array(
				  $mailToEmail => $mailToName
				 ));*/
				 
				// $societyEmail = "societyaccounts@pgsl.in";
				 
				 $societyName = $this->obj_fetch->objSocietyDetails->sSocietyName;
				 $sSocietyEmail = $this->obj_fetch->objSocietyDetails->sSocietyEmail;
				 
				 if($_REQUEST['CC'] != "")
				 {
					 $arEmailDs = explode(";", $_REQUEST['CC']);
					 $arIDs = array();
					 
					 foreach($arEmailDs as $sKey => $sCurEMailID)
					 {
						 array_push($arIDs, $sCurEMailID);
					 }
					 $message->SetCc($arIDs);
				 }
				
				//send email to society cc email
				 if($societyCCEmailID  <> "")
				 {
						$message->SetCc(array($societyCCEmailID => $societyName));
				 }
				 
				 //send email to society email
				  if($sSocietyEmail  <> "")
				 {
						$message->setReplyTo(array($sSocietyEmail => $societyName));
				 }
				 
				$message->setSubject($mailSubject);
				$message->setBody($mailBody);
				$message->setFrom("no-reply@way2society.com", $this->obj_fetch->objSocietyDetails->sSocietyName);
				 
				$message->attach(Swift_Attachment::fromPath($fileName));
				// Send the email
				$mailer = Swift_Mailer::newInstance($transport);
		
				$result = $mailer->send($message);
			
				if($result > 0)
				{
					echo 'Success';
					return 'Success';
				}
				else
				{
					echo 'Failed';
					return 'Failed';
				}
			}
			catch(Exception $exp)
			{
					echo $exp;
			}
			
	}
	else
	{
		//echo 'Missing Parameters';
		return 'Missing Parameters';
	}
		
	}
	
	public function getAllUnits()
	{
		$sql="select `tenant_id` from `tenant_module` where `status` = 'Y' and end_date >= curdate() order by tenant_id asc";
		if($_SESSION['res_flag'] == 1){
			$res=$this->m_landlord->select($sql);
		}else{
			$res=$this->m_dbConn->select($sql);
		}
		$flatten = array();
    	foreach($res as $key)
		{
			$flatten[] = $key['tenant_id'];
		}

    	return $flatten;
	}

	public function getAllLedegrID()
	{
		$sql="select `ledger_id` from `tenant_module` where `status` = 'Y' and end_date >= curdate() order by tenant_id asc";
		if($_SESSION['res_flag'] == 1){
			$res=$this->m_landlord->select($sql);
		}else{
			$res=$this->m_dbConn->select($sql);
		}
		// print_r($res);
		$flatten = array();
    	foreach($res as $key)
		{
			$flatten[] = $key['ledger_id'];
		}

    	return $flatten;
	}
	
	
}
?> 
