<?php
include_once ("dbconst.class.php");
include('include/config_script.php');
include_once("include/dbop.class.php");
include_once("include/display_table.class.php");
//echo "dm";
include_once("activate_user_email.class.php");
//echo "dm2";
include_once("register.class.php");
include_once("../ImageManipulator.php");
include_once("utility.class.php");
include_once("document_maker.class.php");
include_once("latestcount.class.php");
include_once("classes/account_subcategory.class.php");

//error_reporting();
$dbConn = new dbop();
$dbConnRoot = new dbop(true);
class rentaltenant 
{
	public $actionPage = "../rentaltenant.php";
	public $m_dbConn;
	public $m_dbConnRoot;
	public $landLordDB;
	public $landLordDBRoot;
	public $obj_utility;
	public $obj_activation ;
	public $m_bShowTrace;
	public $obj_DocumentMaker;
	public $isLandLordDB;
	public $obj_register;
	public $obj_account_subcategory;

	
	function __construct($dbConn, $dbConnRoot,$landLordDB, $landLordDBRoot = '')
	{
		//echo 'Inside const tenant';
		$this->display_pg=new display_table();
		$this->m_dbConn = $dbConn;
		$this->m_dbConnRoot = $dbConnRoot;
		$this->landLordDB = $landLordDB;
		$this->landLordDBRoot = $landLordDBRoot;
 		$this->m_bShowTrace = 0;
		$this->obj_utility=new utility($this->m_dbConn, $this->m_dbConnRoot);
		$this->obj_DocumentMaker = new doc_templates($this->m_dbConn, $this->m_dbConnRoot);
		$this->obj_activation = new activation_email($dbConn, $dbConnRoot);
		$this->obj_register = new regiser($this->m_dbConn, $this->landLordDB);
		
		// $this->obj_account_subcategory = new account_subcategory($this->m_dbConn);
		if($_SESSION['landLordDB']){
			$this->isLandLordDB = true;
		}
	}
	public function addServiceRequest($srTitle,$srPriority,$srCategory,$unitId,$loginId,$societyId)
	{

		if($this->isLandLordDB){
			$obj_LatestCount = new latestCount($this->landLordDB);
		}else{
			$obj_LatestCount = new latestCount($this->m_dbConn);
		}

		$request_no = $obj_LatestCount->getLatestRequestNo($societyId);
		//echo "request_no : ".$request_no;
		$sqlName = "Select `member_id`,`name` from login where login_id = '".$loginId."';";
		$sqlName_res = $this->m_dbConnRoot->select($sqlName);
		//var_dump($sqlName_res);
		$sql4 = "SELECT u.`unit_no`, mm.`primary_owner_name`, w.`wing`,mm.`mob` FROM `unit` u, `member_main` mm, `wing` w WHERE u.`unit_id` = mm.`unit` AND w.`wing_id` = mm.`wing_id` AND mm.`ownership_status` = '1' AND u.`unit_id` = '".$unitId."'";
		$sql4_res = $this->m_dbConn->select($sql4);
		$summery = "This is Leave & License NOC request.";
		 $sqlsr = "INSERT INTO `service_request` (`request_no`, `society_id`, `reportedby`, `dateofrequest`, `email`, `phone`, `priority`, `category`, `summery`,`img`, `details`, `status`, `unit_id`) VALUES ('".$request_no."', '".$_SESSION['society_id']."', '".$sqlName_res[0]['name']."', '".getDBFormatDate(date('d-m-Y'))."', '".$sqlName_res[0]['name']."', '".$sql4_res[0]['mob']."', '".$srPriority."', '".$srCategory."', '".$srTitle."','','".$summery."', 'Raised', '".$unitId."')";
		$sqlsr_res = $this->m_dbConn->insert($sqlsr);
		return($sqlsr_res);
	}
	public function InsertTenantLedgers($tenant_name,$wing ,$unit_no, $security_deposit)
	{	
		$sql = "select APP_DEFAULT_SOCIETY from appdefault";
		$res = $this->landLordDBRoot->select($sql);
		$landLordSocietyID = $res[0]['APP_DEFAULT_SOCIETY'];

		$Date = $_POST['start_date'];

		if($_SESSION['res_flag'] == 1 || $_SESSION['rental_flag'] == 1){
			$account_category = $_SESSION['default_due_from_tenant'];
		}else{
			$account_category = $_SESSION['default_due_from_member'];
		}

		$startdate = $this->FetchDate($_SESSION['default_year']);
		//Expense group
		$Ledger_Tenant_Array = array();
		if($_SESSION['res_flag'] == 1){
			$tenantName = $unit_no."-".$tenant_name;
			$ledgerName = $tenantName."-Security deposit";
			$sqlI = "select id,ledger_name from ledger where ledger_name IN ('".$tenantName."', '".$ledgerName."')";
			$resI = $this->landLordDB->select($sqlI);
			if($resI <> ''){
				$sqlLegerID = $resI[0]['id'];
				$sd_res = $resI[1]['id'];
				$Ledger_Tenant_Array = array('LedgerID' => $sqlLegerID, 'SecurityID' => $sd_res);
			}else{
				$sql = "select category_id,sd_category_id from landlords where society_id = '".$_SESSION['landLordSocID']."'";
				$res = $this->landLordDBRoot->select($sql);
				$category_id = $res[0]['category_id'];
				$sd_category_id = $res[0]['sd_category_id'];

				$sql1 = "select wing from wing where wing_id = '".$wing."'";
				$res1 = $this->landLordDB->select($sql1);
				$wing_name = $res1[0]["wing"];
				
				$ExpenseGroup = "Expense";
				$categoryexpensebuilding_id = $this->obj_utility->GetCategory_ID("Property Expense", $wing_name, $ExpenseGroup, 1, $this->landLordDB);
				$categoryexpenseFlat_id = $this->obj_utility->GetCategory_ID($wing_name, $unit_no, $ExpenseGroup, 1, $this->landLordDB);

				//income group
				$IncomeGroup = "Income";
				$categoryincomebuilding_id = $this->obj_utility->GetCategory_ID("Rental Income", $wing_name, $IncomeGroup, 1, $this->landLordDB);
				$categoryincomeFlat_id = $this->obj_utility->GetCategory_ID( $wing_name, $unit_no, $IncomeGroup, 1, $this->landLordDB);
				//expense ledger for maintaince
				$ex_sql = "INSERT INTO `ledger`(`society_id`, `categoryid`, `ledger_name`,`expense`, `payment`,`sale`, `receipt`, `opening_balance`,`opening_type`,`opening_date`) VALUES ('".$_SESSION['landLordSocID']."','".$categoryexpenseFlat_id."','".$unit_no."-".$tenant_name."- Maintenance', 1, 1, 1, 1, 0,2,'".getDBFormatDate($startdate)."')";	
				$mai_ex = $this->landLordDB->insert($ex_sql);

				//expense ledger for legal cases
				$legalex_sql = "INSERT INTO `ledger`(`society_id`, `categoryid`, `ledger_name`,`expense`,  `payment`,`sale`, `receipt`, `opening_balance`,`opening_type`,`opening_date`) VALUES ('".$_SESSION['landLordSocID']."','".$categoryexpenseFlat_id."','".$unit_no."-".$tenant_name."- Legal Expenses', 1, 1, 1, 1, 0,2,'".getDBFormatDate($startdate)."')";	
				$le_ex = $this->landLordDB->insert($legalex_sql);

				//Create Income ledgers in Landlord database
				$in_sql = "INSERT INTO `ledger`(`society_id`, `categoryid`, `ledger_name`, `sale`, `show_in_bill`, `opening_balance`,`opening_type`,`opening_date`) VALUES ('".$_SESSION['landLordSocID']."','".$categoryincomeFlat_id."','".$unit_no."-".$tenant_name."-Rent', 1, 1, 0 ,1,'".getDBFormatDate($startdate)."')";	
				$mai_in = $this->landLordDB->insert($in_sql);
			
				//Create Tenant ledger in landlord asset type-due from tenants
				$sqlInsert = "INSERT INTO `ledger`(`society_id`, `categoryid`, `ledger_name`, `sale`, `receipt`, `opening_balance`,`opening_type`,`opening_date`) VALUES ('".$_SESSION['landLordSocID']."', '" . $account_category. "', '".$unit_no."-".$tenant_name."', 1, 1, 0 ,2,'".getDBFormatDate($startdate)."')";	
				$sqlLegerID = $this->landLordDB->insert($sqlInsert);
				$insertAsset = $this->obj_register->SetAssetRegister(getDBFormatDate($Date), $sqlLegerID, 0, 0, TRANSACTION_DEBIT, 0, 1);

				//Create tenant security deposit ledger in landlord liability type
			        $sd_sql = "INSERT INTO `ledger`(`society_id`, `categoryid`, `ledger_name`, `payment`, `receipt`,`show_in_bill`, `opening_balance`,`opening_type`,`opening_date`) VALUES ('".$_SESSION['landLordSocID']."','".$sd_category_id."','".$unit_no."-".$tenant_name."-Security deposit',1, 1, 1,'".$security_deposit."' ,1,'".getDBFormatDate($startdate)."')";	
				$sd_res = $this->landLordDB->insert($sd_sql);
				$insertAsset = $this->obj_register->SetLiabilityRegister(getDBFormatDate($Date), $sd_res, 0, 0, TRANSACTION_CREDIT,$security_deposit, 1);
					
				//create sundry debtor tenant ledger in Parent Company
				$sqlInsert1 = "INSERT INTO `ledger`(`society_id`, `categoryid`, `ledger_name`, `sale`, `receipt`, `opening_balance`,`opening_type`,`opening_date`) VALUES ('".$landLordSocietyID."' , '" . $category_id. "', '".$unit_no."-".$tenant_name."', 1, 1,0 ,1,'".getDBFormatDate($startdate)."')";	
				$sqlLegerID1 = $this->landLordDBRoot->insert($sqlInsert1);
				$Ledger_Tenant_Array = array('LedgerID' => $sqlLegerID, 'SecurityID' => $sd_res);
			}
		  //	return $Ledger_Tenant_Array;
		}else{
			$tenantName = $unit_no."-".$tenant_name;
			$ledgerName = $tenantName."-Security deposit";
			$sqlI = "select id,ledger_name from ledger where ledger_name IN ('".$tenantName."', '".$ledgerName."')";
			$resI = $this->m_dbConn->select($sqlI);
			if($resI <> ''){
				$sqlLegerID = $resI[0]['id'];
				$sd_res = $resI[1]['id'];
				$Ledger_Tenant_Array = array('LedgerID' => $sqlLegerID, 'SecurityID' => $sd_res);
			}else{
				$sql = "select category_id,sd_category_id from landlords where society_id = '".$_SESSION['society_id']."'";
				$res = $this->landLordDBRoot->select($sql);
				$category_id = $res[0]['category_id'];
				$sd_category_id = $res[0]['sd_category_id'];
	
				$sql1 = "select wing from wing where wing_id = '".$wing."'";
				$res1 = $this->m_dbConn->select($sql1);
				$wing_name = $res1[0]["wing"];
	
				$ExpenseGroup = "Expense";
				$categoryexpensebuilding_id = $this->obj_utility->GetCategory_ID("Property Expense", $wing_name, $ExpenseGroup, 1, $this->m_dbConn);
				$categoryexpenseFlat_id = $this->obj_utility->GetCategory_ID($wing_name, $unit_no, $ExpenseGroup, 1, $this->m_dbConn);
	
				//income group
				$IncomeGroup = "Income";
				$categoryincomebuilding_id = $this->obj_utility->GetCategory_ID("Rental Income", $wing_name, $IncomeGroup, 1, $this->m_dbConn);
				$categoryincomeFlat_id = $this->obj_utility->GetCategory_ID( $wing_name, $unit_no, $IncomeGroup, 1, $this->m_dbConn);
				//maintaince expense
				$ex_sql = "INSERT INTO `ledger`(`society_id`, `categoryid`, `ledger_name`,`expense`, `payment`, `sale`, `receipt`, `opening_balance`,`opening_type`,`opening_date`) VALUES ('".$_SESSION['society_id']."','".$categoryexpenseFlat_id."','".$unit_no."-".$tenant_name."- Maintenance', 1, 1,1, 1, 0,2,'".$startdate."')";	
				$mai_ex = $this->m_dbConn->insert($ex_sql);
	
				//legal expense
				$legalex_sql = "INSERT INTO `ledger`(`society_id`, `categoryid`, `ledger_name`,`expense`, `payment`, `sale`, `receipt`, `opening_balance`,`opening_type`,`opening_date`) VALUES ('".$_SESSION['society_id']."','".$categoryexpenseFlat_id."','".$unit_no."-".$tenant_name."- Legal Expenses', 1, 1,1, 1, 0,2,'".getDBFormatDate($startdate)."')";	
				$le_ex = $this->m_dbConn->insert($legalex_sql);
	
				//income expense
				$in_sql = "INSERT INTO `ledger`(`society_id`, `categoryid`, `ledger_name`, `sale`, `show_in_bill`, `opening_balance`,`opening_type`,`opening_date`) VALUES ('".$_SESSION['society_id']."','".$categoryincomeFlat_id."','".$unit_no."-".$tenant_name."-Rent', 1, 1, 0, 1,'".getDBFormatDate($startdate)."')";	
				$mai_in = $this->m_dbConn->insert($in_sql);
	
				//Create Tenant ledger in landlord asset type			
				$sqlInsert = "INSERT INTO `ledger`(`society_id`, `categoryid`, `ledger_name`, `sale`, `receipt`, `opening_balance`,`opening_type`,`opening_date`) VALUES ('".$_SESSION['society_id']."', '" . $account_category. "', '".$unit_no."-".$tenant_name."', 1, 1, 0 ,2,'".getDBFormatDate($startdate)."')";	
				$sqlLegerID = $this->m_dbConn->insert($sqlInsert);
				$insertAsset = $this->obj_register->SetAssetRegister(getDBFormatDate($Date), $sqlLegerID, 0, 0, TRANSACTION_DEBIT, abs($total_opening_balance), 1);
				
				//Create tenant security deposit ledger in landlord liability type
			        $sd_sql = "INSERT INTO `ledger`(`society_id`, `categoryid`, `ledger_name`, `payment`, `receipt`,`show_in_bill`, `opening_balance`,`opening_type`,`opening_date`) VALUES ('".$_SESSION['society_id']."','".$sd_category_id."','".$unit_no."-".$tenant_name."-Security deposit', 1,1,1,'".$security_deposit."' ,1,'".getDBFormatDate($startdate)."')";	
				$sd_res = $this->m_dbConn->insert($sd_sql);
				$insertAsset = $this->obj_register->SetLiabilityRegister(getDBFormatDate($Date), $sd_res, 0, 0, TRANSACTION_CREDIT, abs($security_deposit), 1);
					
				//create sundry debtor tenant ledger in Parent Company
				$sqlInsert1 = "INSERT INTO `ledger`(`society_id`, `categoryid`, `ledger_name`, `sale`, `receipt`, `opening_balance`,`opening_type`,`opening_date`) VALUES ('".$landLordSocietyID."' , '" . $category_id. "', '".$unit_no."-".$tenant_name."', 1, 1,0 ,1,'".getDBFormatDate($startdate)."')";	
				$sqlLegerID1 = $this->landLordDBRoot->insert($sqlInsert1);	
				$Ledger_Tenant_Array = array('LedgerID' => $sqlLegerID, 'SecurityID' => $sd_res);
			}
		}
		return $Ledger_Tenant_Array;
	}

	public function startProcess()
	{
	
		$errorExists = 0;
		$unitId = $_POST['unit_id']; 
		$resUnit = $this->obj_utility->GetUnitDesc($unitId);
		$unitNo = $resUnit[0]["unit_no"];
		if($this->m_bShowTrace)
		{
			echo "unitid:".$unitId."no".$unitNo;
			print_r($resUnit);
		}
		$ResDocTypes = $this->m_dbConn->select("select ID from document_type where doc_type='Lease'");
		//print_r($ResDocTypes);
		$doc_type = "";
		if(isset($ResDocTypes[0]["ID"]))
		{
			$doc_type = $ResDocTypes[0]["ID"];
		}
		else
		{
			echo "<br>Lease Document type not found";
		}
		
		if($_REQUEST['insert']=='Update' && $errorExists==0){

			$profilename = "tenantProfile_".$_REQUEST['unit_id']."_".$_REQUEST['tenant_id']."_".basename($_FILES['profilePhoto']['name']);
			if($_SERVER['HTTP_HOST'] == "localhost" )
			{		
				$uploaddir = "../Uploaded_Documents";			   
			}
			else
			{
				$uploaddir = $_SERVER['DOCUMENT_ROOT']."/Uploaded_Documents";			   
			}
			$uploadfile = $uploaddir ."/". $profilename;	
			//echo "filename : ".$uploadfile."<br/>";
			$profilefileResult = move_uploaded_file($_FILES['profilePhoto']['tmp_name'], $uploadfile);
			// echo "filename : ". $profilename;
			// exit;
			if($profilefileResult){
				$profilesql = "update tenant_module set img = '".$profilename."' where tenant_id = ".$_REQUEST['tenant_id'];
				// echo "profile : ".$profilesql;
				// exit;
				$result = $this->landLordDB->insert($profilesql);
			}

			// Tenant modules data
			$sql1 ="update tenant_module set tenant_name='".$_REQUEST['t_name']."', start_date='".getDBFormatDate($_REQUEST['start_date'])."', end_date ='".getDBFormatDate($_REQUEST['end_date'])."', agent_name ='".$_REQUEST['agent']."', agent_no ='".$_REQUEST['agent_no']."', note = '".$_REQUEST['note']."', security_deposit ='".$_REQUEST['security_deposit']."', annual_rent ='".$_REQUEST['annual_rent']."', contract_value ='".$_REQUEST['contract_value']."', isCompany = '".$_REQUEST['isCompany']."', license_no = '".$_REQUEST['license_no']."',license_authority = '".$_REQUEST['license_authority']."'  where tenant_id = ".$_REQUEST['tenant_id'];
			if($this->isLandLordDB){
				$result = $this->landLordDB->insert($sql1);
			}else{
				$result = $this->m_dbConn->insert($sql1);
			}
			
			//tenant lease data
			for($i = 1; $i <= $_REQUEST['count']; $i++){
				if($_REQUEST['tmemberId_'.$i] && $_REQUEST['tmemberId_'.$i] !='undefined'){

					$sql3 = "update tenant_member set mem_name='".$_REQUEST['members_'.$i]."', relation ='".$_REQUEST['relation_'.$i]."',emirate_no ='".$_REQUEST['emirate_'.$i]."', mem_dob ='".getDBFormatDate($_REQUEST['mem_dob_'.$i])."', contact_no ='".$_REQUEST['contact_'.$i]."', email ='".$_REQUEST['email_'.$i]."' where tmember_id = ".$_REQUEST['tmemberId_'.$i];
					if($this->isLandLordDB){
						$result = $this->landLordDB->insert($sql3);
					}else{
						$result = $this->m_dbConn->insert($sql3);
					}
					
				}else if(empty($_REQUEST['tmemberId_'.$i])){

					$sql3 = "insert into tenant_member (`tenant_id`,`mem_name`,`relation`,`emirate_no`,`mem_dob`,`contact_no`,`email`, `status`) values ('".$_REQUEST['tenant_id']."','".$_REQUEST['members_'.$i]."','".$_REQUEST['relation_'.$i]."','".$_REQUEST['emirate_'.$i]."','".getDBFormatDate($_REQUEST['mem_dob_'.$i])."','".$_REQUEST['contact_'.$i]."','".$_REQUEST['email_'.$i]."','Y')";
					if($this->isLandLordDB){
						$result = $this->landLordDB->insert($sql3);
					}else{
						$result = $this->m_dbConn->insert($sql3);
					}
				}
			}

			// tenant PDC data
			for($i = 1; $i <= $_REQUEST['cheqcount']; $i++){
				$type= $_REQUEST['sd_'.$i] ? $_REQUEST['sd_'.$i] : "Rent";
				if($_REQUEST['pdcId_'.$i] && $_REQUEST['pdcId_'.$i] !='undefined'){
					$sql4 = "update postdated_cheque set bank_name='".$_REQUEST['bankName_'.$i]."', cheque_no ='".$_REQUEST['cheqno_'.$i]."',cheque_date ='".getDBFormatDate($_REQUEST['cheqdate_'.$i])."', amount ='".$_REQUEST['amount_'.$i]."', remark ='".$_REQUEST['remark_'.$i]."', status ='".$_REQUEST['status_'.$i]."', type ='".$type."', mode_of_payment ='".$_REQUEST['mode_'.$i]."' where pdc_id = ".$_REQUEST['pdcId_'.$i];
					if($this->isLandLordDB){
						$result = $this->landLordDB->insert($sql4);
					}else{
						$result = $this->m_dbConn->insert($sql4);
					}
					
				}else if(empty($_REQUEST['pdcId_'.$i]) && $_REQUEST['amount_'.$i]){
					$sql4 = "insert into postdated_cheque (`tenant_id`,`unit_id`,`bank_name`,`cheque_no`,`cheque_date`,`amount`,`remark`, `status`,`type`,`mode_of_payment`) values ('".$_REQUEST['tenant_id']."','".$_REQUEST['unit_no']."','".$_REQUEST['bankName_'.$i]."','".$_REQUEST['cheqno_'.$i]."','".getDBFormatDate($_REQUEST['cheqdate_'.$i])."','".$_REQUEST['amount_'.$i]."','".$_REQUEST['remark_'.$i]."','".$_REQUEST['status_'.$i]."','".$type."','".$_REQUEST['mode_'.$i]."')";
					if($this->isLandLordDB){
						$result = $this->landLordDB->insert($sql4);
					}else{
						$result = $this->m_dbConn->insert($sql4);
					}				}
			}
			//tenant vehicles data 
			$vehicleCount = $_REQUEST['vehiclecount'];
			if($vehicleCount <>'')
			{
				$carsql = "delete from `mem_car_parking` where `member_id`='".$_REQUEST['tenant_id']."' and `car_type` = '1'";
				if($this->isLandLordDB){
					$cardel = $this->landLordDB->delete($carsql);
				}else{
					$cardel = $this->m_dbConn->delete($carsql);
				}
				$bikesql = "delete from `mem_bike_parking` where `member_id`='".$_REQUEST['tenant_id']."' and `bike_type` = '1'";
				if($this->isLandLordDB){
					$bikedel = $this->landLordDB->delete($bikesql);
				}else{
					$bikedel = $this->m_dbConn->delete($bikesql);
				}
				for($i=1;$i <= $vehicleCount;$i++)
				{
					$regNo = $_REQUEST['carRegNo_'.$i];
					$owner = $_POST['carOwner_'.$i];
					$make = $_POST['carMake_'.$i];
					$model = $_POST['carModel_'.$i];
					$vehicleType = $_POST['vehicleType_'.$i];
					$color = $_POST['carColor_'.$i];
					$parkingType = $_POST['parkingType_'.$i];
					$parking_slot = $_POST['parkingSlot_'.$i];
					$parking_sticker = $_POST['parkingSticker_'.$i];
					if($vehicleType == 2)//BIKE
					{
						$insert_queryBike = "insert into mem_bike_parking (`member_id`,`ParkingType`,`parking_slot`,`bike_reg_no`,`bike_owner`,`bike_model`,`bike_make`,`bike_color`,`parking_sticker`,`bike_type`) values ('".$_REQUEST['tenant_id']."','".$parkingType."','".addslashes(trim(ucwords($parking_slot)))."','".addslashes(trim(strtoupper($regNo)))."','".addslashes(trim(ucwords($owner)))."','".addslashes(trim(ucwords($model)))."','".addslashes(trim(ucwords($make)))."','".addslashes(trim(ucwords($color)))."','".addslashes(trim(ucwords($parking_sticker)))."','1')";
						if($this->isLandLordDB){
							$dataBike = $this->landLordDB->insert($insert_queryBike);
						}else{
							$dataBike = $this->m_dbConn->insert($insert_queryBike);
						}
					}
					if($vehicleType == '4')//CAR
					{
						$insert_query="insert into mem_car_parking (`member_id`,`ParkingType`,`parking_slot`,`car_reg_no`,`car_owner`,`car_model`,`car_make`,`car_color`,`parking_sticker`,`car_type`) values ('".$_REQUEST['tenant_id']."','".$parkingType."','".addslashes(trim(ucwords($parking_slot)))."','".addslashes(trim(strtoupper($regNo)))."','".addslashes(trim(ucwords($owner)))."','".addslashes(trim(ucwords($model)))."','".addslashes(trim(ucwords($make)))."','".addslashes(trim(ucwords($color)))."','".addslashes(trim(ucwords($parking_sticker)))."','1')";
						if($this->isLandLordDB){
							$data = $this->landLordDB->insert($insert_query);
						}else{
							$data = $this->m_dbConn->insert($insert_query);
						}
					}				
				}
			}

			//Documents update
			for($i=1; $i<=$_REQUEST['doc_count']; $i++){
				//upload documents
				$fileName = "tenant_".$_REQUEST['unit_no']."_".$_REQUEST['tenant_id']."_".basename($_FILES['userfile'.$i]['name']);
				//echo " fileName: ".$fileName;
				if($_SERVER['HTTP_HOST'] == "localhost" )
				{		
					$uploaddir = "../Uploaded_Documents";			   			   
				}
				else
				{
					$uploaddir = $_SERVER['DOCUMENT_ROOT']."/Uploaded_Documents";			   
				}
				$uploadfile = $uploaddir ."/". $fileName;	

				$fileResult = move_uploaded_file($_FILES['userfile'.$i]['tmp_name'], $uploadfile);
				// echo "<br>fileResult : ".$fileResult;
				// exit;
				if($fileResult)
				{
					$insert_query="insert into `documents` (`Name`, `Unit_Id`,`refID`,`Category`, `Note`,`Document`,`source_table`,`doc_type_id`,`doc_version`,`attachment_gdrive_id`) values ('".$_REQUEST['doc_name_'.$i]."', '" . $_REQUEST['unit_id'] . "','".$_REQUEST['tenant_id']."','0','','".$fileName."','1','".$doc_type."','1','')";
					echo "doc update query :".$insert_query."<br>";
					$data=$this->landLordDB->insert($insert_query);
				}
			}


			$this->actionPage = "../view_tenant_profile.php?scm&id=".$_REQUEST['tenant_id'];
			return "Update";
		}	
				
		if(($_REQUEST['insert']=='Submit' || $_REQUEST['insert']=='Renew') && $errorExists==0)
		{

			$srResult = 0;
			//echo "sizeof(serviceRequestDetails) : ".sizeof($_SESSION['serviceRequestDetails']);
			if($_POST['actionPage2'] == "serviceRequest")
			{
				$srResult = $this->addServiceRequest($_SESSION['serviceRequestDetails']['srTitle'],$_SESSION['serviceRequestDetails']['priority'],$_SESSION['serviceRequestDetails']['category'],$_SESSION['TENANT_REQUEST_ID'],$_SESSION['serviceRequestDetails']['unit_no'],$_SESSION['login_id'],$_SESSION['society_id']);
			}
			$wing = $_POST["wing_id"];
			$wing_name = $_POST["wing_id"];
			$security_deposit = $_POST["sd"];
			$unitLedgerName = $_POST['unit_no'];
			//echo $unitLedgerName;
			$start=getDBFormatDate($_POST['start_date']);
			$end=getDBFormatDate($_POST['end_date']);
			$security_deposit = $_POST['security_deposit'];
			$annual_rent = $_POST['annual_rent'];
			$contract_value = $_POST['contract_value'];
			$iscompany = $_POST['isCompany'];
			$license_no = $_POST['license_no'];
			$license_authority = $_POST['license_authority'];
			$today = date("Y-m-d");    // 2018-01-20
			$total_month = $_POST["Lease_Period"];
			$p_verification = $_POST['pVerified'];
			$leaveAndLicenseAgreement = $_POST['leaveAndLicenseAgreement'];
			$chequeCount = (int)$_POST['cheqcount'];
			$members=$_POST['count'];
			// echo "ID: " .$landLordSocietyID;

			if($_SESSION['res_flag'] == 1)
			{
				foreach(explode(',',$_POST['unit_no']) as $k)
				{
					if($k<>"")
					{
						$rand_no = rand('00000000','99999999');
						$sql00 = "select count(*)as cnt from unit where rand_no='".$rand_no."' and status='Y'";
						$res00 = $this->landLordDB->select($sql00);
						if($res00[0]['cnt']==1)
						{
							$rand_no = rand('00000000','99999999');
						}		
						$sql1 = "select unit_no from unit where unit_id = '".$unitLedgerName."'";
						$res1 = $this->landLordDB->select($sql1);
						$unitNo = $res1[0]["unit_no"];

						$LedgerID = $this->InsertTenantLedgers($_POST['t_name'],$wing ,$unitNo,0);		
						$getmax="select max(sort_order) as cnt from `unit` where society_id='".$_SESSION['landLordSocID']."'";
						$getmaxID = $this->landLordDB->select($getmax);
						$SortOrderID=$getmaxID[0]['cnt'] + 100;
							
						$isBlocked = 0;
						if(isset($_POST['Blocked']))
						{
							$isBlocked = 1; 
						}														

						$sql2= "insert into `tenant_module` (`ledger_id`,`security_id`,`serviceRequestId`,`doc_id`,`wing_id`,`unit_id`,`tenant_name`,tenant_MName,tenant_LName,`mobile_no`,`email`,`dob`,`agent_name`,`agent_no`,`members`,`create_date`,`start_date`,`end_date`, `total_month`, `note`,`ApprovalLevel`,`noofcheque`,`isCompany`,`license_no`,`license_authority`,`annual_rent`,`contract_value`,`security_deposit`) values ('".$LedgerID['LedgerID']."','".$LedgerID['SecurityID']."','".$srResult."','0',".$wing."," . $unitLedgerName . ",'".$_POST['t_name']."','".$_POST['t_mname']."','".$_POST['t_lname']."','".$_POST['contact_1']."','".$_POST['email_1']."','".getDBFormatDate($_POST['mem_dob_1'])."','".$_POST['agent']."','".$_POST['agent_no']."','1','".date('Y-m-d')."','".$start."','".$end."', '".$total_month."', '".$_POST['note']."','".$this->getApprovalLevel()."', '".$chequeCount."','".$iscompany."','".$license_no."','".$license_authority."','".$annual_rent."','".$contract_value."','".$security_deposit."')";
						$result = $this->landLordDB->insert($sql2);

						$sql7 = "insert into `approval_details` (`referenceId`, module_id) values ('".$result."','".TENANT_SOURCE_TABLE_ID."');";
						$sql7_res = $this->landLordDB->insert($sql7);

						if($_POST['verified'] == 1) // If Verified checkbox is check then only update
						{
							$this->updateTenantVerificationStatus($result, $p_verification, $leaveAndLicenseAgreement, $total_month);
						}
						$insert_mapping = "INSERT INTO `mapping`(`society_id`, `unit_id`, `desc`, `code`, `role`, `created_by`, `view`) VALUES ('" . $_SESSION['society_id'] . "', '" . $unitLedgerName . "', '" . $unitLedgerName . "', '" . getRandomUniqueCode() . "', '" . ROLE_MEMBER . "', '" . $_SESSION['login_id'] . "', 'MEMBER')";
						$result_mapping = $this->m_dbConnRoot->insert($insert_mapping);
								
						$this->actionPage="../rentaltenant.php";
					}
				}
				// echo $sql2= "insert into `tenant_module` (`serviceRequestId`,`doc_id`,`unit_id`,`tenant_name`,tenant_MName,tenant_LName,`mobile_no`,`email`,`dob`,`agent_name`,`agent_no`,`members`,`create_date`,`start_date`,`end_date`, `total_month`, `note`,`ApprovalLevel`) values ('".$srResult."','0','".$unitId."','".$_POST['t_name']."','".$_POST['t_mname']."','".$_POST['t_lname']."','".$_POST['contact_1']."','".$_POST['email_1']."','".getDBFormatDate($_POST['mem_dob_1'])."','".$_POST['agent']."','".$_POST['agent_no']."','1','".date('Y-m-d')."','".$start."','".$end."', '".$total_month."', '".$_POST['note']."','".$this->getApprovalLevel()."')";
					// $res2 = $this->m_dbConn->insert($sql2);
					//Inserting renovation details in approval_details table..
					//-------------------------------------------------Code to upload profile image of the tenant--------------------------------------------------//
					$photoFile = $_FILES['profilePhoto'];
					
					//var_dump($photoFile);
					$str = $unitNo ."//Lease//".$start;
					if($this->m_bShowTrace)
					{
						echo "path:".$str;
					}
					$parts = explode("//", $str);
					$fileName = "";
					$doc_id = array();				
					$doc=$_POST['doc_count'];
					
					$finalMemberCount = $members;
					$todaysDate = date('Y-m-d');         		                    
					//----------------------------------------------------G_Drive Code----------------------------------------------------------------------
					//----------------------------------------------------Uncomment below code for gDrive---------------------------------------------------//
					
					/*$resResponse = $this->obj_utility->UploadAttachment($photoFile,  $doc_type, $todaysDate, "Uploaded_Documents", $i, true, $UnitNo);
					echo "<br>Profile Image Result:";
					var_dump($resResponse);
					$sStatus = $resResponse["status"];
					$sMode = $resResponse["mode"];
					$sFileName = $resResponse["response"];
					$sUploadFileName = $resResponse["FileName"];
					if($sMode == "1")
					{
						$random_name = $sFileName;
					} 
					else if($sMode == "2")
					{
						$docGDriveID = $sFileName;
					}
					else
					{
					}
					$sDocVersion = '2';
					if($GdriveDocID != "")
					{
						$sDocVersion = '2';
					}
					$sDocVersion = '2';
					if($GdriveDocID != "")
					{
						$sDocVersion = '2';
					}*/
					$fileName = "tenantProfile_".$unitNo."_".$result."_".basename($photoFile['name']);
					//echo "HTTP POST : ".$_SERVER['HTTP_HOST'];
					if($_SERVER['HTTP_HOST'] == "localhost" )
					{		
						$uploaddir = "../Uploaded_Documents";			   			   
					}
					else
					{
						$uploaddir = $_SERVER['DOCUMENT_ROOT']."/Uploaded_Documents";			   
					}
					$uploadfile = $uploaddir ."/". $fileName;	
					//echo "filename : ".$uploadfile."<br/>";
					$fileResult = move_uploaded_file($photoFile['tmp_name'], $uploadfile);
					//echo "fileResult : ".$fileResult;
					if($fileResult)
					{
						$updateProfileSql = "update tenant_module set `img` = '".$fileName."' where tenant_id = '".$result."';";
						$updateResult = $this->landLordDB->update($updateProfileSql);
					}
// ---------------------------------Security Deposit Cheque-----------------------------------------------------
//--------------------------------------------------Post Dated Cheque-----------------------------------------------------------------------//

					$tenantId = $result;
					//echo "tenant id: " .$tenantId;
					//echo "cheque no: " .$chequeCount;
					for($i = 1; $i <= $chequeCount; $i++)
					{
						$mode = $_POST['mode_'.$i];
						$bank_name = $_POST['bankName_'.$i];
						$cheqno = $_POST['cheqno_'.$i];
						$cheque_date= $_POST['cheqdate_'.$i];
						$amount = $_POST['amount_'.$i];
						$remark = $_POST['remark_'.$i];
						$type = $_POST['sd_'.$i] ? $_POST['sd_'.$i] : "Rent";
						// echo $type;
						if($cheque_date <> ''){
							$insert_query = "insert into postdated_cheque (`tenant_id`,`unit_id`,`bank_name`,`bank_branch`,`cheque_no`,`cheque_date`,`amount`,`remark`,`type`,`status`,`mode_of_payment`) values ('".$tenantId."','".$unitLedgerName."','".$bank_name."','".$bank_branch."','".$cheqno."','".getDBFormatDate($cheque_date)."','".$amount."','".addslashes(trim(ucwords($remark)))."','".$type."','1','".$mode."')";
							$data = $this->landLordDB->insert($insert_query);	
						}
					}

					//echo 
				
					//------------------------------------------------Storing Vehicle details in Database----------------------------------------------------------//
				
					//$RefId=$result;
					$tenantId = $result;
					$vehicleCount = (int)$_POST['vehiclecount'];
					for($i = 1;$i <= $vehicleCount;$i++)
					{
						$regNo = $_POST['carRegNo_'.$i];
						$owner = $_POST['carOwner_'.$i];
						$make = $_POST['carMake_'.$i];
						$model = $_POST['carModel_'.$i];
						$vehicleType = $_POST['vehicleType_'.$i];
						$color = $_POST['carColor_'.$i];
						$parkingType = $_POST['parkingType_'.$i];
						$parking_slot = $_POST['parkingSlot_'.$i];
						$parking_sticker = $_POST['parkingSticker_'.$i];
						
						if($vehicleType == 2)//BIKE
						{
							$sqlBike = "select count(*)as cnt from mem_bike_parking where member_id='".$tenantId."' and bike_reg_no='".addslashes(trim(strtoupper($regNo)))."' and status='Y' and bike_type='1'";
							$resBike = $this->landLordDB->select($sqlBike);
							if($resBike[0]['cnt']==0)
							{
								$insert_queryBike = "insert into mem_bike_parking (`member_id`,`ParkingType`,`parking_slot`,`bike_reg_no`,`bike_owner`,`bike_model`,`bike_make`,`bike_color`,`parking_sticker`,`bike_type`) values ('".$tenantId."','".$parkingType."','".addslashes(trim(ucwords($parking_slot)))."','".addslashes(trim(strtoupper($regNo)))."','".addslashes(trim(ucwords($owner)))."','".addslashes(trim(ucwords($model)))."','".addslashes(trim(ucwords($make)))."','".addslashes(trim(ucwords($color)))."','".addslashes(trim(ucwords($parking_sticker)))."','1')";
								$dataBike = $this->landLordDB->insert($insert_queryBike);
							}
							else
							{
							}
						}
						if($vehicleType == '4')//CAR
						{
							$sqlCar = "select count(*)as cnt from mem_car_parking where member_id='".$tenantId."' and car_reg_no='".$regNo."' and status='Y' and car_type='1'";
							$resCar = $this->landLordDB->select($sqlCar);
							if($resCar[0]['cnt']==0)
							{
								$insert_query="insert into mem_car_parking (`member_id`,`ParkingType`,`parking_slot`,`car_reg_no`,`car_owner`,`car_model`,`car_make`,`car_color`,`parking_sticker`,`car_type`) values ('".$tenantId."','".$parkingType."','".addslashes(trim(ucwords($parking_slot)))."','".addslashes(trim(strtoupper($regNo)))."','".addslashes(trim(ucwords($owner)))."','".addslashes(trim(ucwords($model)))."','".addslashes(trim(ucwords($make)))."','".addslashes(trim(ucwords($color)))."','".addslashes(trim(ucwords($parking_sticker)))."','1')";
								$data = $this->landLordDB->insert($insert_query);
							}
							else
							{
							}
						}
					}
					//---------------------------------------------------Uploading tenant documents-------------------------------------------------------------//
					for($i=1; $i<=$doc; $i++)
					{
						$PostDate = $_POST['start_date'];         		                    
						$docGDriveID = "";
						$random_name = "";
						$doc_name = "";
						$doc_name = $_POST["doc_name_".$i];
						//echo "Doc Name : ".$doc_name;
						
						//---------------------------------------------Saving file on server code-------------------------------------------------------//
						//--------------------------------------------If G Drive code doesn't Work Uncommented Below code-----------------------------------//
						
						$fileName = "tenant_".$unitNo."_".$result."_".basename($_FILES['userfile'.$i]['name']);
						//echo " fileName: ".$fileName;
						if($_SERVER['HTTP_HOST'] == "localhost" )
						{		
							$uploaddir = "../Uploaded_Documents";			   			   
						}
						else
						{
							$uploaddir = $_SERVER['DOCUMENT_ROOT']."/Uploaded_Documents";			   
						}
						$uploadfile = $uploaddir ."/". $fileName;	
						//echo "<br>filename : ".$uploadfile."<br/>";
						$fileResult = move_uploaded_file($_FILES['userfile'.$i]['tmp_name'], $uploadfile);
						//echo "<br>fileResult : ".$fileResult;
						if($fileResult)
						{
							$insert_query="insert into `documents` (`Name`, `Unit_Id`,`refID`,`Category`, `Note`,`Document`,`source_table`,`doc_type_id`,`doc_version`,`attachment_gdrive_id`) values ('".$doc_name."', " . $unitLedgerName . ",'".$result."','0','','".$fileName."','1','".$doc_type."','1','')";
							$data=$this->landLordDB->insert($insert_query);
						}
						
						
						//------------------------------------G Drive Code-------------------------------------------------------------------------//
						
						/*$resResponse = $this->obj_utility->UploadAttachment($_FILES,  $doc_type, $PostDate, "Uploaded_Documents", $i, true, $UnitNo);
						$sStatus = $resResponse["status"];
						$sMode = $resResponse["mode"];
						$sFileName = $resResponse["response"];
						$sUploadFileName = $resResponse["FileName"];
						if($sMode == "1")
						{
							$random_name = $sFileName;
						} 
						else if($sMode == "2")
						{
							$docGDriveID = $sFileName;
						}
						else
						{
						}
						$sDocVersion = '1';
						if($GdriveDocID != "")
						{
							$sDocVersion = '2';
						}
						$insert_query="insert into `documents` (`Name`, `Unit_Id`,`refID`,`Category`, `Note`,`Document`,`source_table`,`doc_type_id`,`doc_version`,`attachment_gdrive_id`) values ('".$doc_name."', '".$unitId."','".$tenantId."','0', '','".$sUploadFileName."','1','2','".$sDocVersion."','".$docGDriveID."')";
						$data=$this->m_dbConn->insert($insert_query);*/
					}
					$chkCreateLogin = 0;
					$CommuEmails = 0;
					for($i=1;$i <= $members;$i++)
					{
						$addmembers= $_POST['members_'.($i)];
						echo $addmembers;
						$addrelation=$_POST['relation_'.($i)];
						$memDOB=getDBFormatDate($_POST['mem_dob_'.($i)]);
						$number=$_POST['contact_'.($i)];
						$email=$_POST['email_'.($i)];
						$emirateNo=$_POST['emirate_'.($i)];
						if($_POST['chkCreateLogin'] == "1")
						{
							$chkCreateLogin = 1;
							//$this->SendActivationEmail();
						}
						if($_POST['other_send_commu_emails'] == "1")
						{
							$CommuEmails = 1;
						}
						if($i > 1)
						{
							$_POST['other_send_commu_emails'] = 0;
						}
						if($addmembers <> '')
						{
							$sqldata="insert into `tenant_member`(`tenant_id`,`mem_name`,`relation`,`emirate_no`,`mem_dob`,`contact_no`,`email`,`send_act_email`,`send_commu_emails`) values('".$result."','".$addmembers."','".$addrelation."','".$emirateNo."','".$memDOB."','".$number."','".$email."','".$chkCreateLogin."','".$CommuEmails."')";						
							$data=$this->landLordDB->insert($sqldata);
						}
						else
						{
							$finalMemberCount--;
						}
						//$up_query="update `tenant_module` set  `members`='".$finalMemberCount."' where tenant_id='".$result."'";
						//$data = $this->m_dbConn->update($up_query);
					}
					if($_POST['actionPage2'] == "serviceRequest")
					{
						$tenantName = $_POST['t_name']." ".$_POST['t_mname']." ".$_POST['t_lname'];
						$this->obj_DocumentMaker->getTenantNOC($unitId,$tenantName,$_SESSION['society_id'],$result);
						$this->actionPage = "../document_maker.php?temp=".$_SESSION['TENANT_REQUEST_ID']."&tId=".$result;
					}
					else
					{
						$this->actionPage = "../view_tenant_profile.php?scm&id=$result&tik_id=time();&m&view";
					}
				$this->landLordDB->commit();
				return "Insert";
			}
//--------------tenant module code for tenant add record----------------------------------------//
			else if($_SESSION['rental_flag'] == 1){
				foreach(explode(',',$_POST['unit_no']) as $k)
				{
					if($k<>"")
					{
						$rand_no = rand('00000000','99999999');
						$sql00 = "select count(*)as cnt from unit where rand_no='".$rand_no."' and status='Y'";
						$res00 = $this->m_dbConn->select($sql00);
						if($res00[0]['cnt']==1)
						{
							$rand_no = rand('00000000','99999999');
						}
						$sql1 = "select unit_no from unit where unit_id = '".$unitLedgerName."'";
						$res1 = $this->m_dbConn->select($sql1);
						$unitNo = $res1[0]["unit_no"];

								//Maintenance Bill
						
						$LedgerID = $this->InsertTenantLedgers($_POST['t_name'],$wing ,$unitNo,0);
					
						
						$getmax="select max(sort_order) as cnt from `unit` where society_id='".$_SESSION['landLordSocID']."'";
						$getmaxID = $this->m_dbConn->select($getmax);
						$SortOrderID=$getmaxID[0]['cnt'] + 100;
							
						$isBlocked = 0;
						if(isset($_POST['Blocked']))
						{
							$isBlocked = 1; 
						}														
						// echo $sql1 = "insert into unit(unit_id,society_id,wing_id,unit_no) values(" . $sqlLegerID . ", ".$_SESSION['society_id']." , ".$wing.", ".$this->m_dbConn->escapeString($unitLedgerName).")";										
						// $res1 = $this->m_dbConn->insert($sql1);
						$sql2= "insert into `tenant_module` (`ledger_id`,`security_id`,`serviceRequestId`,`doc_id`,`wing_id`,`unit_id`,`tenant_name`,tenant_MName,tenant_LName,`mobile_no`,`email`,`dob`,`agent_name`,`agent_no`,`members`,`create_date`,`start_date`,`end_date`, `total_month`, `note`,`ApprovalLevel`,`noofcheque`,`license_no`,`license_authority`,`annual_rent`,`contract_value`,`security_deposit`) values ('".$LedgerID['LedgerID']."','".$LedgerID['SecurityID']."','".$srResult."','0',".$wing."," . $unitLedgerName . ",'".$_POST['t_name']."','".$_POST['t_mname']."','".$_POST['t_lname']."','".$_POST['contact_1']."','".$_POST['email_1']."','".getDBFormatDate($_POST['mem_dob_1'])."','".$_POST['agent']."','".$_POST['agent_no']."','1','".date('Y-m-d')."','".$start."','".$end."', '".$total_month."', '".$_POST['note']."','".$this->getApprovalLevel()."', '".$chequeCount."','".$license_no."','".$license_authority."','".$annual_rent."','".$contract_value."','".$security_deposit."')";
						$result = $this->m_dbConn->insert($sql2);

						$sql7 = "insert into `approval_details` (`referenceId`, module_id) values ('".$result."','".TENANT_SOURCE_TABLE_ID."');";
						$sql7_res = $this->m_dbConn->insert($sql7);
								
						if($_POST['verified'] == 1) // If Verified checkbox is check then only update
						{
							$this->updateTenantVerificationStatus($result, $p_verification, $leaveAndLicenseAgreement, $total_month);
						}

						$insert_mapping = "INSERT INTO `mapping`(`society_id`, `unit_id`, `desc`, `code`, `role`, `created_by`, `view`) VALUES ('" . $_SESSION['society_id'] . "', '" . $unitLedgerName . "', '" . $unitLedgerName . "', '" . getRandomUniqueCode() . "', '" . ROLE_MEMBER . "', '" . $_SESSION['login_id'] . "', 'MEMBER')";
						$result_mapping = $this->m_dbConnRoot->insert($insert_mapping);
								
						$this->actionPage="../rentaltenant.php";
					}
				}
				// echo $sql2= "insert into `tenant_module` (`serviceRequestId`,`doc_id`,`unit_id`,`tenant_name`,tenant_MName,tenant_LName,`mobile_no`,`email`,`dob`,`agent_name`,`agent_no`,`members`,`create_date`,`start_date`,`end_date`, `total_month`, `note`,`ApprovalLevel`) values ('".$srResult."','0','".$unitId."','".$_POST['t_name']."','".$_POST['t_mname']."','".$_POST['t_lname']."','".$_POST['contact_1']."','".$_POST['email_1']."','".getDBFormatDate($_POST['mem_dob_1'])."','".$_POST['agent']."','".$_POST['agent_no']."','1','".date('Y-m-d')."','".$start."','".$end."', '".$total_month."', '".$_POST['note']."','".$this->getApprovalLevel()."')";
					// $res2 = $this->m_dbConn->insert($sql2);
					//Inserting renovation details in approval_details table..
					//-------------------------------------------------Code to upload profile image of the tenant--------------------------------------------------//
					$photoFile = $_FILES['profilePhoto'];
					
					//var_dump($photoFile);
					$str = $unitNo ."//Lease//".$start;
					if($this->m_bShowTrace)
					{
						echo "path:".$str;
					}
					$parts = explode("//", $str);
					$fileName = "";
					$doc_id = array();				
					$doc=$_POST['doc_count'];
					
					$finalMemberCount = $members;
					$todaysDate = date('Y-m-d');         		                    
					//----------------------------------------------------G_Drive Code----------------------------------------------------------------------
					//----------------------------------------------------Uncomment below code for gDrive---------------------------------------------------//
					
					/*$resResponse = $this->obj_utility->UploadAttachment($photoFile,  $doc_type, $todaysDate, "Uploaded_Documents", $i, true, $UnitNo);
					echo "<br>Profile Image Result:";
					var_dump($resResponse);
					$sStatus = $resResponse["status"];
					$sMode = $resResponse["mode"];
					$sFileName = $resResponse["response"];
					$sUploadFileName = $resResponse["FileName"];
					if($sMode == "1")
					{
						$random_name = $sFileName;
					} 
					else if($sMode == "2")
					{
						$docGDriveID = $sFileName;
					}
					else
					{
					}
					$sDocVersion = '2';
					if($GdriveDocID != "")
					{
						$sDocVersion = '2';
					}
					$sDocVersion = '2';
					if($GdriveDocID != "")
					{
						$sDocVersion = '2';
					}*/
					$fileName = "tenantProfile_".$unitNo."_".$result."_".basename($photoFile['name']);
					//echo "HTTP POST : ".$_SERVER['HTTP_HOST'];
					if($_SERVER['HTTP_HOST'] == "localhost" )
					{		
						$uploaddir = "../Uploaded_Documents";			   			   
					}
					else
					{
						$uploaddir = $_SERVER['DOCUMENT_ROOT']."/Uploaded_Documents";			   
					}
					$uploadfile = $uploaddir ."/". $fileName;	
					//echo "filename : ".$uploadfile."<br/>";
					$fileResult = move_uploaded_file($photoFile['tmp_name'], $uploadfile);
					//echo "fileResult : ".$fileResult;
					if($fileResult)
					{
						$updateProfileSql = "update tenant_module set `img` = '".$fileName."' where tenant_id = '".$result."';";
						$updateResult = $this->m_dbConn->update($updateProfileSql);
					}
// ---------------------------------Security Deposit Cheque-----------------------------------------------------
//--------------------------------------------------Post Dated Cheque-----------------------------------------------------------------------//

					$tenantId = $result;
					//echo "tenant id: " .$tenantId;
					//echo "cheque no: " .$chequeCount;
					for($i = 1; $i <= $chequeCount; $i++)
					{
						$mode = $_POST['mode_'.$i];
						// echo $mode;
						$bank_name = $_POST['bankName_'.$i];
						$cheqno = $_POST['cheqno_'.$i];
						$cheque_date= $_POST['cheqdate_'.$i];
						$amount = $_POST['amount_'.$i];
						$remark = $_POST['remark_'.$i];
						$type = $_POST['sd_'.$i] ? $_POST['sd_'.$i] : "Rent";
						// echo $type;
						if($cheque_date <> ''){
							$insert_query = "insert into postdated_cheque (`tenant_id`,`unit_id`,`bank_name`,`bank_branch`,`cheque_no`,`cheque_date`,`amount`,`remark`,`type`,`status`,`mode_of_payment`) values ('".$tenantId."','".$unitLedgerName."','".$bank_name."','".$bank_branch."','".$cheqno."','".getDBFormatDate($cheque_date)."','".$amount."','".addslashes(trim(ucwords($remark)))."','".$type."','1','".$mode."')";
							$data = $this->m_dbConn->insert($insert_query);	
						}
					}

					//echo 
				
					//------------------------------------------------Storing Vehicle details in Database----------------------------------------------------------//
				
					//$RefId=$result;
					$tenantId = $result;
					$vehicleCount = (int)$_POST['vehiclecount'];
					for($i = 1;$i <= $vehicleCount;$i++)
					{
						$regNo = $_POST['carRegNo_'.$i];
						$owner = $_POST['carOwner_'.$i];
						$make = $_POST['carMake_'.$i];
						$model = $_POST['carModel_'.$i];
						$vehicleType = $_POST['vehicleType_'.$i];
						$color = $_POST['carColor_'.$i];
						$parkingType = $_POST['parkingType_'.$i];
						$parking_slot = $_POST['parkingSlot_'.$i];
						$parking_sticker = $_POST['parkingSticker_'.$i];
						
						if($vehicleType == 2)//BIKE
						{
							$sqlBike = "select count(*)as cnt from mem_bike_parking where member_id='".$tenantId."' and bike_reg_no='".addslashes(trim(strtoupper($regNo)))."' and status='Y' and bike_type='1'";
							$resBike = $this->m_dbConn->select($sqlBike);
							if($resBike[0]['cnt']==0)
							{
								$insert_queryBike = "insert into mem_bike_parking (`member_id`,`ParkingType`,`parking_slot`,`bike_reg_no`,`bike_owner`,`bike_model`,`bike_make`,`bike_color`,`parking_sticker`,`bike_type`) values ('".$tenantId."','".$parkingType."','".addslashes(trim(ucwords($parking_slot)))."','".addslashes(trim(strtoupper($regNo)))."','".addslashes(trim(ucwords($owner)))."','".addslashes(trim(ucwords($model)))."','".addslashes(trim(ucwords($make)))."','".addslashes(trim(ucwords($color)))."','".addslashes(trim(ucwords($parking_sticker)))."','1')";
								$dataBike = $this->m_dbConn->insert($insert_queryBike);
							}
							else
							{
							}
						}
						if($vehicleType == '4')//CAR
						{
							$sqlCar = "select count(*)as cnt from mem_car_parking where member_id='".$tenantId."' and car_reg_no='".$regNo."' and status='Y' and car_type='1'";
							$resCar = $this->m_dbConn->select($sqlCar);
							if($resCar[0]['cnt']==0)
							{
								$insert_query="insert into mem_car_parking (`member_id`,`ParkingType`,`parking_slot`,`car_reg_no`,`car_owner`,`car_model`,`car_make`,`car_color`,`parking_sticker`,`car_type`) values ('".$tenantId."','".$parkingType."','".addslashes(trim(ucwords($parking_slot)))."','".addslashes(trim(strtoupper($regNo)))."','".addslashes(trim(ucwords($owner)))."','".addslashes(trim(ucwords($model)))."','".addslashes(trim(ucwords($make)))."','".addslashes(trim(ucwords($color)))."','".addslashes(trim(ucwords($parking_sticker)))."','1')";
								$data = $this->m_dbConn->insert($insert_query);
							}
							else
							{
							}
						}
					}
					//---------------------------------------------------Uploading tenant documents-------------------------------------------------------------//
					for($i=1; $i<=$doc; $i++)
					{
						$PostDate = $_POST['start_date'];         		                    
						$docGDriveID = "";
						$random_name = "";
						$doc_name = "";
						$doc_name = $_POST["doc_name_".$i];
						//echo "Doc Name : ".$doc_name;
						
						//---------------------------------------------Saving file on server code-------------------------------------------------------//
						//--------------------------------------------If G Drive code doesn't Work Uncommented Below code-----------------------------------//
						
						$fileName = "tenant_".$unitNo."_".$result."_".basename($_FILES['userfile'.$i]['name']);
						//echo " fileName: ".$fileName;
						if($_SERVER['HTTP_HOST'] == "localhost" )
						{		
							$uploaddir = "../Uploaded_Documents";			   			   
						}
						else
						{
							$uploaddir = $_SERVER['DOCUMENT_ROOT']."/Uploaded_Documents";			   
						}
						$uploadfile = $uploaddir ."/". $fileName;	
						//echo "<br>filename : ".$uploadfile."<br/>";
						$fileResult = move_uploaded_file($_FILES['userfile'.$i]['tmp_name'], $uploadfile);
						//echo "<br>fileResult : ".$fileResult;
						if($fileResult)
						{
							$insert_query="insert into `documents` (`Name`, `Unit_Id`,`refID`,`Category`, `Note`,`Document`,`source_table`,`doc_type_id`,`doc_version`,`attachment_gdrive_id`) values ('".$doc_name."', " . $unitLedgerName . ",'".$result."','0','','".$fileName."','1','".$doc_type."','1','')";
							$data=$this->m_dbConn->insert($insert_query);
						}
						
						
						//------------------------------------G Drive Code-------------------------------------------------------------------------//
						
						/*$resResponse = $this->obj_utility->UploadAttachment($_FILES,  $doc_type, $PostDate, "Uploaded_Documents", $i, true, $UnitNo);
						$sStatus = $resResponse["status"];
						$sMode = $resResponse["mode"];
						$sFileName = $resResponse["response"];
						$sUploadFileName = $resResponse["FileName"];
						if($sMode == "1")
						{
							$random_name = $sFileName;
						} 
						else if($sMode == "2")
						{
							$docGDriveID = $sFileName;
						}
						else
						{
						}
						$sDocVersion = '1';
						if($GdriveDocID != "")
						{
							$sDocVersion = '2';
						}
						$insert_query="insert into `documents` (`Name`, `Unit_Id`,`refID`,`Category`, `Note`,`Document`,`source_table`,`doc_type_id`,`doc_version`,`attachment_gdrive_id`) values ('".$doc_name."', '".$unitId."','".$tenantId."','0', '','".$sUploadFileName."','1','2','".$sDocVersion."','".$docGDriveID."')";
						$data=$this->m_dbConn->insert($insert_query);*/
					}
					$chkCreateLogin = 0;
					$CommuEmails = 0;
					for($i=1;$i <= $members;$i++)
					{
						$addmembers= $_POST['members_'.($i)];
						echo $addmembers;
						$addrelation=$_POST['relation_'.($i)];
						$memDOB=getDBFormatDate($_POST['mem_dob_'.($i)]);
						$number=$_POST['contact_'.($i)];
						$email=$_POST['email_'.($i)];
						$emirateNo=$_POST['emirate_'.($i)];
						if($_POST['chkCreateLogin'] == "1")
						{
							$chkCreateLogin = 1;
							//$this->SendActivationEmail();
						}
						if($_POST['other_send_commu_emails'] == "1")
						{
							$CommuEmails = 1;
						}
						if($i > 1)
						{
							$_POST['other_send_commu_emails'] = 0;
						}
						if($addmembers <> '')
						{
							$sqldata="insert into `tenant_member`(`tenant_id`,`mem_name`,`relation`,`emirate_no`,`mem_dob`,`contact_no`,`email`,`send_act_email`,`send_commu_emails`) values('".$result."','".$addmembers."','".$addrelation."','".$emirateNo."','".$memDOB."','".$number."','".$email."','".$chkCreateLogin."','".$CommuEmails."')";						
							$data=$this->m_dbConn->insert($sqldata);
						}
						else
						{
							$finalMemberCount--;
						}
						//$up_query="update `tenant_module` set  `members`='".$finalMemberCount."' where tenant_id='".$result."'";
						//$data = $this->m_dbConn->update($up_query);
					}
					if($_POST['actionPage2'] == "serviceRequest")
					{
						$tenantName = $_POST['t_name']." ".$_POST['t_mname']." ".$_POST['t_lname'];
						$this->obj_DocumentMaker->getTenantNOC($unitId,$tenantName,$_SESSION['society_id'],$result);
						$this->actionPage = "../document_maker.php?temp=".$_SESSION['TENANT_REQUEST_ID']."&tId=".$result;
					}
					else
					{
						//$this->actionPage = "../rentaltenant.php";
						$this->actionPage = "../view_tenant_profile.php?scm&id=$result&tik_id=time();&m&view";
					}
				$this->m_dbConn->commit();
				return "Insert";
			}
//--------------------------------------w2s code -------------------------------------------//
			else
			{
				$sql = "select count(*)as cnt from unit where society_id='".$_SESSION['society_id']."' and wing_id='".$wing."' and unit_id ='".$unitLedgerName."' and status='Y'";
				$res = $this->m_dbConn->select($sql);
				if($res[0]['cnt']==0)
				{
					foreach(explode(',',$_POST['unit_no']) as $k)
					{
						
						if($k<>"")
						{
							$sql0 = "select count(*)as cnt from unit where society_id='".$_SESSION['society_id']."' and wing_id='".$wing."' and unit_id='".$unitLedgerName."'";
							$res0 = $this->m_dbConn->select($sql0);
							}
							if($res0[0]['cnt']==0)
							{
								$rand_no = rand('00000000','99999999');
								$sql00 = "select count(*)as cnt from unit where rand_no='".$rand_no."' and status='Y'";
								$res00 = $this->m_dbConn->select($sql00);
								if($res00[0]['cnt']==1)
								{
									$rand_no = rand('00000000','99999999');
								}
								//Maintenance Bill
								$iBillSubTotal = ($_POST['bill_subtotal'] == '') ? 0 : $_POST['bill_subtotal'];
								$iBillInterest = ($_POST['bill_interest'] == '') ? 0 : $_POST['bill_interest'];
								$iPrevPrinciple = ($_POST['principle_balance'] == '') ? 0 : $_POST['principle_balance'];
								$iPrevInterest = ($_POST['interest_balance'] == '') ? 0 : $_POST['interest_balance'];
								//echo "Bill: " .$iBillSubTotal;
								$opening_balance = $iBillSubTotal + $iBillInterest;
								//echo "Bill: " .$opening_balance;
								$total_bill_payable = $opening_balance + $iPrevPrinciple + $iPrevInterest; 

								//Supplimentary Bill
								$iSuppBillSubTotal = ($_POST['supp_bill_subtotal'] == '') ? 0 : $_POST['supp_bill_subtotal'];
								$iSuppBillInterest = ($_POST['supp_bill_interest'] == '') ? 0 : $_POST['supp_bill_interest'];
								$iSuppPrevPrinciple = ($_POST['supp_principle_balance'] == '') ? 0 : $_POST['supp_principle_balance'];
								$iSuppPrevInterest = ($_POST['supp_interest_balance'] == '') ? 0 : $_POST['supp_interest_balance'];
								
								$supp_opening_balance = $iSuppBillSubTotal + $iSuppBillInterest;
								$supp_total_bill_payable = $supp_opening_balance + $iSuppPrevPrinciple + $iSuppPrevInterest; 
								
								$total_opening_balance = $total_bill_payable + $supp_total_bill_payable;
								// echo "Bill: " .$total_opening_balance;										
								$obj_register = new regiser($this->m_dbConn, $this->landLordDB);
								
								//$Date = $this->display_pg->curdate();
								$Date = $start;
								//echo "date: " .$Date;
								$account_category = 0;
								//echo ("ID: " .$account_category);
								if($_SESSION['res_flag'] == 1 && $_SESSION['rental_flag'] == 1){
									$account_category = DUE_FROM_TENANTS;
								}else{
									$account_category = DUE_FROM_MEMBERS;
								}
								if(((float)$total_opening_balance) < 0)
								{
									$sqlInsert = "INSERT INTO `ledger`(`society_id`, `categoryid`, `ledger_name`, `sale`, `receipt`, `opening_balance`,`opening_type`) VALUES ('".$_SESSION['society_id']."', '" . $account_category. "', '".$_POST['t_name']."', 1, 1, '" . abs($total_opening_balance) . "',1)";	
									$sqlLegerID = $this->m_dbConn->insert($sqlInsert);
								
									$insertAsset = $obj_register->SetAssetRegister(getDBFormatDate($Date), $sqlLegerID, 0, 0, TRANSACTION_CREDIT, abs($total_opening_balance), 1);
								}
								else
								{
									$sqlInsert = "INSERT INTO `ledger`(`society_id`, `categoryid`, `ledger_name`, `sale`, `receipt`, `opening_balance`,`opening_type`) VALUES (".$_SESSION['society_id'].", '". $account_category ."', '".$_POST['t_name']."', 1, 1, ". abs($total_opening_balance) .",2)";	
									$sqlLegerID = $this->m_dbConn->insert($sqlInsert);
									
									$insertAsset = $obj_register->SetAssetRegister(getDBFormatDate($Date), $sqlLegerID, 0, 0, TRANSACTION_DEBIT, $total_opening_balance, 1);
									//pending : Add log for above queries
								}
								$getmax="select max(sort_order) as cnt from `unit` where society_id='".$_SESSION['society_id']."'";
								$getmaxID = $this->m_dbConn->select($getmax);
								
								$SortOrderID=$getmaxID[0]['cnt'] + 100;
								
								$isBlocked = 0;
								if(isset($_POST['Blocked']))
								{
									$isBlocked = 1; 
								}			
											
								// echo $sql1 = "insert into unit(unit_id,society_id,wing_id,unit_no) values(" . $sqlLegerID . ", ".$_SESSION['society_id']." , ".$wing.", ".$this->m_dbConn->escapeString($unitLedgerName).")";										
								// $res1 = $this->m_dbConn->insert($sql1);
								$sql2= "insert into `tenant_module` (`serviceRequestId`,`doc_id`,`unit_id`,`tenant_name`,tenant_MName,tenant_LName,`mobile_no`,`email`,`dob`,`agent_name`,`agent_no`,`members`,`create_date`,`start_date`,`end_date`, `total_month`, `note`,`ApprovalLevel`,`noofcheque`) values ('".$srResult."','0'," . $unitLedgerName . ",'".$_POST['t_name']."','".$_POST['t_mname']."','".$_POST['t_lname']."','".$_POST['contact_1']."','".$_POST['email_1']."','".getDBFormatDate($_POST['mem_dob_1'])."','".$_POST['agent']."','".$_POST['agent_no']."','1','".date('Y-m-d')."','".$start."','".$end."', '".$total_month."', '".$_POST['note']."','".$this->getApprovalLevel()."', '".$chequeCount."')";
								$result = $this->m_dbConn->insert($sql2);

								$sql7 = "insert into `approval_details` (`referenceId`, module_id) values ('".$result."','".TENANT_SOURCE_TABLE_ID."');";
								$sql7_res = $this->m_dbConn->insert($sql7);
							

								$sql3="insert into `billdetails`(UnitID, PeriodID, BillSubTotal, BillInterest, PrincipalArrears, InterestArrears,TotalBillPayable, BillRegisterID) values(".$unitLedgerName.",'".$_POST['Period']."','".$iBillSubTotal."','".$iBillInterest."','" . $iPrevPrinciple . "', '" . $iPrevInterest . "', '"  . $total_bill_payable . "',1)";
								$res3 = $this->m_dbConn->insert($sql3);
								
								//echo("dBName : " .$_SESSION['dbname']);
								$sql3="insert into `billdetails`(UnitID, PeriodID, BillSubTotal, BillInterest, PrincipalArrears, InterestArrears,TotalBillPayable, BillRegisterID, BillType) values(".$unitLedgerName.",'".$_POST['Period']."','".$iBillSubTotal."','".$iBillInterest."','" . $iPrevPrinciple . "', '" . $iPrevInterest . "', '"  . $total_bill_payable . "', 1,0)";
								$res3 = $this->m_dbConn->insert($sql3);
								
								
								if($_POST['verified'] == 1) // If Verified checkbox is check then only update
								{
									$this->updateTenantVerificationStatus($result, $p_verification, $leaveAndLicenseAgreement, $total_month);
								}

								if($supp_total_bill_payable !=0)
								{
									$sql4="insert into `billdetails`(UnitID, PeriodID, BillSubTotal, BillInterest, PrincipalArrears, InterestArrears,TotalBillPayable, BillRegisterID, BillType) values(".$unitLedgerName.",'".$_POST['Period']."','".$iSuppBillSubTotal."','".$iSuppBillInterest."','" . $iSuppPrevPrinciple . "', '" . $iSuppPrevInterest . "', '"  . $supp_total_bill_payable . "', 1)";
									$res3 = $this->m_dbConn->insert($sql4);
								}
								$insert_mapping = "INSERT INTO `mapping`(`society_id`, `unit_id`, `desc`, `code`, `role`, `created_by`, `view`) VALUES ('" . $_SESSION['society_id'] . "', '" . $unitLedgerName . "', '" . $unitLedgerName . "', '" . getRandomUniqueCode() . "', '" . ROLE_MEMBER . "', '" . $_SESSION['login_id'] . "', 'MEMBER')";
								$result_mapping = $this->m_dbConnRoot->insert($insert_mapping);
								
								$this->actionPage="../rentaltenant.php";
							}
					}
					// echo $sql2= "insert into `tenant_module` (`serviceRequestId`,`doc_id`,`unit_id`,`tenant_name`,tenant_MName,tenant_LName,`mobile_no`,`email`,`dob`,`agent_name`,`agent_no`,`members`,`create_date`,`start_date`,`end_date`, `total_month`, `note`,`ApprovalLevel`) values ('".$srResult."','0','".$unitId."','".$_POST['t_name']."','".$_POST['t_mname']."','".$_POST['t_lname']."','".$_POST['contact_1']."','".$_POST['email_1']."','".getDBFormatDate($_POST['mem_dob_1'])."','".$_POST['agent']."','".$_POST['agent_no']."','1','".date('Y-m-d')."','".$start."','".$end."', '".$total_month."', '".$_POST['note']."','".$this->getApprovalLevel()."')";
					// $res2 = $this->m_dbConn->insert($sql2);
					//Inserting renovation details in approval_details table..
					//-------------------------------------------------Code to upload profile image of the tenant--------------------------------------------------//
					$photoFile = $_FILES['profilePhoto'];
					
					//var_dump($photoFile);
					$str = $unitNo ."//Lease//".$start;
					if($this->m_bShowTrace)
					{
						echo "path:".$str;
					}
					$parts = explode("//", $str);
					$fileName = "";
					$doc_id = array();				
					$doc=$_POST['doc_count'];
					
					$finalMemberCount = $members;
					$todaysDate = date('Y-m-d');         		                    
					//----------------------------------------------------G_Drive Code----------------------------------------------------------------------
					//----------------------------------------------------Uncomment below code for gDrive---------------------------------------------------//
					
					/*$resResponse = $this->obj_utility->UploadAttachment($photoFile,  $doc_type, $todaysDate, "Uploaded_Documents", $i, true, $UnitNo);
					echo "<br>Profile Image Result:";
					var_dump($resResponse);
					$sStatus = $resResponse["status"];
					$sMode = $resResponse["mode"];
					$sFileName = $resResponse["response"];
					$sUploadFileName = $resResponse["FileName"];
					if($sMode == "1")
					{
						$random_name = $sFileName;
					} 
					else if($sMode == "2")
					{
						$docGDriveID = $sFileName;
					}
					else
					{
					}
					$sDocVersion = '2';
					if($GdriveDocID != "")
					{
						$sDocVersion = '2';
					}
					$sDocVersion = '2';
					if($GdriveDocID != "")
					{
						$sDocVersion = '2';
					}*/
					$fileName = "tenantProfile_".$unitNo."_".$result."_".basename($photoFile['name']);
					//echo "HTTP POST : ".$_SERVER['HTTP_HOST'];
					if($_SERVER['HTTP_HOST'] == "localhost" )
					{		
						$uploaddir = "../Uploaded_Documents";			   			   
					}
					else
					{
						$uploaddir = $_SERVER['DOCUMENT_ROOT']."/Uploaded_Documents";			   
					}
					$uploadfile = $uploaddir ."/". $fileName;	
					//echo "filename : ".$uploadfile."<br/>";
					$fileResult = move_uploaded_file($photoFile['tmp_name'], $uploadfile);
					//echo "fileResult : ".$fileResult;
					if($fileResult)
					{
							$updateProfileSql = "update tenant_module set `img` = '".$fileName."' where tenant_id = '".$result."';";
							$updateResult = $this->m_dbConn->update($updateProfileSql);
					}

					//--------------------------------------------------Post Dated Cheque-----------------------------------------------------------------------//

					$tenantId = $result;
					//echo "tenant id: " .$tenantId;
					//echo "cheque no: " .$chequeCount;
					for($i = 1; $i <= $chequeCount; $i++)
					{
						$bank_name = $_POST['bankName_'.$i];
						$bank_branch = $_POST['branch_'.$i];
						$cheqno = $_POST['cheqno_'.$i];
						$cheque_date= $_POST['cheqdate_'.$i];
						$amount = $_POST['amount_'.$i];
						$remark = $_POST['remark_'.$i];

						$insert_query = "insert into postdated_cheque (`tenant_id`,`unit_id`,`bank_name`,`bank_branch`,`cheque_no`,`cheque_date`,`amount`,`remark`,`status`) values ('".$tenantId."','".$unitLedgerName."','".$bank_name."','".$bank_branch."','".$cheqno."','".getDBFormatDate($cheque_date)."','".$amount."','".addslashes(trim(ucwords($remark)))."','1')";
						$data = $this->m_dbConn->insert($insert_query);
					}

					//echo 
				
					//------------------------------------------------Storing Vehicle details in Database----------------------------------------------------------//
				
					//$RefId=$result;
					$tenantId = $result;
					$vehicleCount = (int)$_POST['vehiclecount'];
					for($i = 1;$i <= $vehicleCount;$i++)
					{
						$regNo = $_POST['carRegNo_'.$i];
						$owner = $_POST['carOwner_'.$i];
						$make = $_POST['carMake_'.$i];
						$model = $_POST['carModel_'.$i];
						$vehicleType = $_POST['vehicleType_'.$i];
						$color = $_POST['carColor_'.$i];
						$parkingType = $_POST['parkingType_'.$i];
						$parking_slot = $_POST['parkingSlot_'.$i];
						$parking_sticker = $_POST['parkingSticker_'.$i];
						
						if($vehicleType == 2)//BIKE
						{
							$sqlBike = "select count(*)as cnt from mem_bike_parking where member_id='".$tenantId."' and bike_reg_no='".addslashes(trim(strtoupper($regNo)))."' and status='Y' and bike_type='1'";
							$resBike = $this->m_dbConn->select($sqlBike);
							if($resBike[0]['cnt']==0)
							{
								$insert_queryBike = "insert into mem_bike_parking (`member_id`,`ParkingType`,`parking_slot`,`bike_reg_no`,`bike_owner`,`bike_model`,`bike_make`,`bike_color`,`parking_sticker`,`bike_type`) values ('".$tenantId."','".$parkingType."','".addslashes(trim(ucwords($parking_slot)))."','".addslashes(trim(strtoupper($regNo)))."','".addslashes(trim(ucwords($owner)))."','".addslashes(trim(ucwords($model)))."','".addslashes(trim(ucwords($make)))."','".addslashes(trim(ucwords($color)))."','".addslashes(trim(ucwords($parking_sticker)))."','1')";
								$dataBike = $this->m_dbConn->insert($insert_queryBike);
							}
							else
							{
							}
						}
						if($vehicleType == '4')//CAR
						{
							$sqlCar = "select count(*)as cnt from mem_car_parking where member_id='".$tenantId."' and car_reg_no='".$regNo."' and status='Y' and car_type='1'";
							$resCar = $this->m_dbConn->select($sqlCar);
							if($resCar[0]['cnt']==0)
							{
								$insert_query="insert into mem_car_parking (`member_id`,`ParkingType`,`parking_slot`,`car_reg_no`,`car_owner`,`car_model`,`car_make`,`car_color`,`parking_sticker`,`car_type`) values ('".$tenantId."','".$parkingType."','".addslashes(trim(ucwords($parking_slot)))."','".addslashes(trim(strtoupper($regNo)))."','".addslashes(trim(ucwords($owner)))."','".addslashes(trim(ucwords($model)))."','".addslashes(trim(ucwords($make)))."','".addslashes(trim(ucwords($color)))."','".addslashes(trim(ucwords($parking_sticker)))."','1')";
								$data = $this->m_dbConn->insert($insert_query);
							}
							else
							{
							}
						}
					}
					//---------------------------------------------------Uploading tenant documents-------------------------------------------------------------//
					for($i=1; $i<=$doc; $i++)
					{
						$PostDate = $_POST['start_date'];         		                    
						$docGDriveID = "";
						$random_name = "";
						$doc_name = "";
						$doc_name = $_POST["doc_name_".$i];
						//echo "Doc Name : ".$doc_name;
						
						//---------------------------------------------Saving file on server code-------------------------------------------------------//
						//--------------------------------------------If G Drive code doesn't Work Uncommented Below code-----------------------------------//
						
						$fileName = "tenant_".$unitNo."_".$result."_".basename($_FILES['userfile'.$i]['name']);
						//echo " fileName: ".$fileName;
						if($_SERVER['HTTP_HOST'] == "localhost" )
						{		
							$uploaddir = "../Uploaded_Documents";			   			   
						}
						else
						{
							$uploaddir = $_SERVER['DOCUMENT_ROOT']."/Uploaded_Documents";			   
						}
						$uploadfile = $uploaddir ."/". $fileName;	
						//echo "<br>filename : ".$uploadfile."<br/>";
						$fileResult = move_uploaded_file($_FILES['userfile'.$i]['tmp_name'], $uploadfile);
						//echo "<br>fileResult : ".$fileResult;
						if($fileResult)
						{
							$insert_query="insert into `documents` (`Name`, `Unit_Id`,`refID`,`Category`, `Note`,`Document`,`source_table`,`doc_type_id`,`doc_version`,`attachment_gdrive_id`) values ('".$doc_name."', " . $unitLedgerName . ",'".$result."','0','','".$fileName."','1','".$doc_type."','1','')";
							$data=$this->m_dbConn->insert($insert_query);
						}
						
						
						//------------------------------------G Drive Code-------------------------------------------------------------------------//
						
						/*$resResponse = $this->obj_utility->UploadAttachment($_FILES,  $doc_type, $PostDate, "Uploaded_Documents", $i, true, $UnitNo);
						$sStatus = $resResponse["status"];
						$sMode = $resResponse["mode"];
						$sFileName = $resResponse["response"];
						$sUploadFileName = $resResponse["FileName"];
						if($sMode == "1")
						{
							$random_name = $sFileName;
						} 
						else if($sMode == "2")
						{
							$docGDriveID = $sFileName;
						}
						else
						{
						}
						$sDocVersion = '1';
						if($GdriveDocID != "")
						{
							$sDocVersion = '2';
						}
						$insert_query="insert into `documents` (`Name`, `Unit_Id`,`refID`,`Category`, `Note`,`Document`,`source_table`,`doc_type_id`,`doc_version`,`attachment_gdrive_id`) values ('".$doc_name."', '".$unitId."','".$tenantId."','0', '','".$sUploadFileName."','1','2','".$sDocVersion."','".$docGDriveID."')";
						$data=$this->m_dbConn->insert($insert_query);*/
					}
					$chkCreateLogin = 0;
					$CommuEmails = 0;
					for($i=1;$i <= $members;$i++)
					{
						$addmembers= $_POST['members_'.($i)];
						echo $addmembers;
						$addrelation=$_POST['relation_'.($i)];
						$memDOB=getDBFormatDate($_POST['mem_dob_'.($i)]);
						$number=$_POST['contact_'.($i)];
						$email=$_POST['email_'.($i)];
						$emirateNo=$_POST['emirate_'.($i)];
						if($_POST['chkCreateLogin'] == "1")
						{
							$chkCreateLogin = 1;
							//$this->SendActivationEmail();
						}
						if($_POST['other_send_commu_emails'] == "1")
						{
							$CommuEmails = 1;
						}
						if($i > 1)
						{
							$_POST['other_send_commu_emails'] = 0;
						}
						if($addmembers <> '')
						{					
							$sqldata="insert into `tenant_member`(`tenant_id`,`mem_name`,`relation`,`emirate_no`,`mem_dob`,`contact_no`,`email`,`send_act_email`,`send_commu_emails`) values('".$result."','".$addmembers."','".$addrelation."','".$emirateNo."','".$memDOB."','".$number."','".$email."','".$chkCreateLogin."','".$CommuEmails."')";						
							$data=$this->m_dbConn->insert($sqldata);
						}
						else
						{
							$finalMemberCount--;
						}
						//$up_query="update `tenant_module` set  `members`='".$finalMemberCount."' where tenant_id='".$result."'";
						//$data = $this->m_dbConn->update($up_query);
					}
					if($_POST['actionPage2'] == "serviceRequest")
					{
						$tenantName = $_POST['t_name']." ".$_POST['t_mname']." ".$_POST['t_lname'];
						$this->obj_DocumentMaker->getTenantNOC($unitId,$tenantName,$_SESSION['society_id'],$result);
						$this->actionPage = "../document_maker.php?temp=".$_SESSION['TENANT_REQUEST_ID']."&tId=".$result;
					}
					else
					{
						$this->actionPage = "../rentaltenant.php";
					}
				$this->m_dbConn->commit();
				return "Insert";
				}
				else
				{
					return "Unit Already exist";
				}
			}
		}
	
	/*-------------------------------------------------Update---------------------------------------------------------------------------------------------------*/

		else if($_REQUEST['insert']=='Update_notusing' && $errorExists==0)
		{
			//echo "<pre>";
			//print_r($_POST);
			//echo "</pre>";
			$start = getDBFormatDate($_POST['start_date']);
			$end = getDBFormatDate($_POST['end_date']);
			$total_month = $_POST["Lease_Period"];
			$Tenant_id = $_POST['tenant_id'];
			$p_verification = $_POST['pVerified'];
			$leaveAndLicenseAgreement = $_POST['leaveAndLicenseAgreement'];
			$members=$_POST['count'];
			//echo "members : ".$members;
			$finalMemberCount = $members;
			$activeStatus = 0;
			if($_POST['tenantAction'] == "ter")
			{
				$activeStatus = 1;
			}
			if(isset($_POST['verified']))
			{
				$activeStatus = 1;
				$this->updateTenantVerificationStatus($Tenant_id, $p_verification, $leaveAndLicenseAgreement, $total_month);
			}
			if(isset($_POST['approved']))
			{
				//$activeStatus = $_POST['approved'];
				$sql1 = "select * from approval_details where referenceId = '".$Tenant_id."' and module_id = '".TENANT_SOURCE_TABLE_ID."';";
				$sql1_res = $this->m_dbConn->select($sql1);
				//var_dump($sql1_res);
				if($sql1_res[0]['firstLevelApprovalStatus'] == 'Y')
				{
					//echo "in if";
					$sql3 = "Select m.`role` from login as l,mapping as m where l.login_id = '".$_SESSION['login_id']."' and l.`current_mapping` = m.`id`";
					$approvedByDesignation = $this->m_dbConnRoot->select($sql3);
					$sql2 = "update approval_details set `secondApprovalById` = '".$_SESSION['login_id']."',`secondApprovalByDesignation`='".$approvedByDesignation[0]['role']."', `secondLevelApprovalStatus` = 'Y' where referenceId = '".$Tenant_id."' and module_id='".TENANT_SOURCE_TABLE_ID."';";
					$sql2_res = $this->m_dbConn->update($sql2);
					$sql7 = "select `serviceRequestId` from tenant_module where tenant_id = '".$Tenant_id."';";
					$sql7_res = $this->m_dbConn->select($sql7);
					$sql8 = "update service_request set `status` = 'Approved' where request_id = '".$sql7_res[0]['serviceRequestId']."';";
					$sql8_res = $this->m_dbConn->update($sql8);
				}
				else
				{
					///echo "in else";
					$sql3 = "Select m.`role` from login as l,mapping as m where l.login_id = '".$_SESSION['login_id']."' and l.`current_mapping` = m.`id`";
					$approvedByDesignation = $this->m_dbConnRoot->select($sql3);
					$sql2 = "update `approval_details` set `firstApprovalById` = '".$_SESSION['login_id']."',`firstApprovalByDesignation`='".$approvedByDesignation[0]['role']."', `firstLevelApprovalStatus` = 'Y' where referenceId = '".$Tenant_id."' and module_id='".TENANT_SOURCE_TABLE_ID."';";
					$sql2_res = $this->m_dbConn->update($sql2);
					$sql7 = "select `serviceRequestId` from tenant_module where tenant_id = '".$Tenant_id."';";
					$sql7_res = $this->m_dbConn->select($sql7);
					$sql8 = "update service_request set `status` = 'Waiting for Approval' where request_id = '".$sql7_res[0]['serviceRequestId']."';";
					$sql8_res = $this->m_dbConn->update($sql8);
				}
			}
			$photoFile = $_FILES['profilePhoto'];
			$resUnit = $this->obj_utility->GetUnitDesc($_POST['unit_id']);
			$unitNo = $resUnit[0]['unit_no'];
			//$resSociety = $this->obj_DocumentMaker->GetGDriveDetails();
			//var_dump($resSociety);
			$memberId = $this->getMemberId($_POST['unit_id']);
			//$sGDrive_W2S_ID = $resSociety["0"]["GDrive_W2S_ID"];
			//echo "Photo Size".sizeof($photoFile);
			if(($photoFile['name']) != "")
			{
				//echo "in if";
				//----------------------------------------------------G_Drive Code----------------------------------------------------------------------
				//----------------------------------------------------Uncomment below code for gDrive---------------------------------------------------//
				
				/*$resResponse = $this->obj_utility->UploadAttachment($photoFile,  $doc_type, $todaysDate, "Uploaded_Documents", $i, true, $UnitNo);
				echo "<br>Profile Image Result:";
				var_dump($resResponse);
				$sStatus = $resResponse["status"];
				$sMode = $resResponse["mode"];
				$sFileName = $resResponse["response"];
				$sUploadFileName = $resResponse["FileName"];
				if($sMode == "1")
				{
					$random_name = $sFileName;
				} 
				else if($sMode == "2")
				{
					$docGDriveID = $sFileName;
				}
				else
				{
				}
				$sDocVersion = '2';
				if($GdriveDocID != "")
				{
					$sDocVersion = '2';
				}
				$sDocVersion = '2';
				if($GdriveDocID != "")
				{
					$sDocVersion = '2';
				}*/
				$fileName = "tenantProfile_".$unitNo."_".$Tenant_id."_".basename($photoFile['name']);
				if($_SERVER['HTTP_HOST'] == "localhost" )
				{		
					$uploaddir = "../Uploaded_Documents";			   			   
				}
				else
				{
					$uploaddir = $_SERVER['DOCUMENT_ROOT']."/Uploaded_Documents";			   
				}
				$uploadfile = $uploaddir ."/". $fileName;	
				//echo "filename : ".$uploadfile."<br/>";
				$fileResult = move_uploaded_file($photoFile['tmp_name'], $uploadfile);
				//echo "fileResult : ".$fileResult;
				if($fileResult)
				{
					//echo "in if2";
					$up_query = "update `tenant_module` set `tenant_name` = '".$_POST['t_name']."',`tenant_MName` = '".$_POST['t_mname']."',`tenant_LName` = '".$_POST['t_lname']."' ,`mobile_no` = '".$_POST['contact_1']."' ,`email` = '".$_POST['email_1']."',`dob` = '".getDBFormatDate($_POST['mem_dob_1'])."',`agent_name` = '".$_POST['agent']."',`agent_no` = '".$_POST['agent_no']."',`members` = '".$_POST['count']."',`create_date` = '".date('Y-m-d')."',`start_date` = '".$start."',`end_date`= '".$end."',`note`='".$_POST['note']."',`img` = '".$fileName."' where tenant_id='".$Tenant_id."'";
					//echo "up_query : ".$up_query;
					$data = $this->m_dbConn->update($up_query);
				}
			}
			else
			{
				//echo "in else";
				$up_query="update `tenant_module` set `tenant_name`='".$_POST['t_name']."',`tenant_MName`= '".$_POST['t_mname']."',`tenant_LName` = '".$_POST['t_lname']."',`mobile_no`= '".$_POST['contact_1']."' ,`email`= '".$_POST['email_1']."',`dob`= '".getDBFormatDate($_POST['mem_dob_1'])."',`agent_name` = '".$_POST['agent']."',`agent_no` = '".$_POST['agent_no']."',`members`= '".$_POST['count']."',`create_date` = '".date('Y-m-d')."',`start_date` = '".$start."',`end_date`= '".$end."',`note` = '".$_POST['note']."' where tenant_id='".$Tenant_id."'";
				//echo "up_query : ".$up_query;
				$data = $this->m_dbConn->update($up_query);
			}
			
			$doc_Count = $_POST['doc_count'];				
			$fileName = "";
			for($i = 1; $i <= $doc_Count; $i++)
			{
				$doc_name = $_POST["doc_name_".$i];
				$fileName = "tenant_".$unitNo."_".$result."_".basename($_FILES['userfile'.$i]['name']);
				if($_SERVER['HTTP_HOST'] == "localhost" )
				{		
					$uploaddir = "../Uploaded_Documents";			   			   
				}
				else
				{
					$uploaddir = $_SERVER['DOCUMENT_ROOT']."/Uploaded_Documents";			   
				}
				$uploadfile = $uploaddir ."/". $fileName;	
				//echo "filename : ".$uploadfile."<br/>";
				$fileResult = move_uploaded_file($_FILES['userfile'.$i]['tmp_name'], $uploadfile);
				//echo "fileResult : ".$fileResult;
				if($fileResult)
				{
					// $insert_query="insert into `documents` (`Name`, `Unit_Id`,`refID`,`Category`, `Note`,`Document`,`source_table`,`doc_type_id`,`doc_version`,`attachment_gdrive_id`) values ('".$doc_name."', '".$
					// ."','".$Tenant_id."','0','','".$fileName."','1','".$doc_type."','1','')";
					$data=$this->m_dbConn->insert($insert_query);
				}
				/*if($_FILES['userfile'.$i]['name'] <> "")
				{
					$PostDate = $_POST['start_date'];         		                    
					$docGDriveID = "";
					$random_name = "";
					$doc_name = $_POST["doc_name_".$i];
					$resResponse = $this->obj_utility->UploadAttachment($_FILES,  $doc_type, $PostDate, "Uploaded_Documents", $i, true, $UnitNo);
					$sStatus = $resResponse["status"];
					$sMode = $resResponse["mode"];
					$sFileName = $resResponse["response"];
					$sUploadFileName = $resResponse["FileName"];
					if($sMode == "1")
					{
						$random_name = $sFileName;
					} 
					else if($sMode == "2")
					{
						$docGDriveID = $sFileName;
					}
					else
					{
					}
					$sDocVersion = '1';
					if($GdriveDocID != "")
					{
						$sDocVersion = '2';
					}
				 	$insert_query="insert into `documents` (`Name`, `Unit_Id`,`refID`, `Category`, `Note`,`Document`,`source_table`,`doc_type_id`,`doc_version`,`attachment_gdrive_id`) values ('".$doc_name."', '".$unit_id."','".$Tenant_id."','0', '','".$sUploadFileName."','1','2','".$sDocVersion."','".$docGDriveID."')";
					$doc_id=$this->m_dbConn->insert($insert_query);
				}*/
			}
			$chkCreateLogin = 0;
			$CommuEmails = 0;  
			if($members <>'')
			{
				$del_member = "delete from `tenant_member` where tenant_id='".$Tenant_id."'";
				$del_list = $this->m_dbConn->delete($del_member);
				for($i=1;$i <= $members;$i++)
				{
					$addmembers= $_POST['members_'.($i)];
					$addrelation=$_POST['relation_'.($i)];
					$Memdob=getDBFormatDate($_POST['mem_dob_'.($i)]);
					$number=$_POST['contact_'.($i)];
					$email=$_POST['email_'.($i)];
					if($_POST['chkCreateLogin'] == "1")
					{
						$chkCreateLogin = 1;
					}
					if($_POST['other_send_commu_emails'] == "1")
					{
						$CommuEmails = 1;
					}
				
					if($i > 1)
					{
						$_POST['other_send_commu_emails'] = 0;
					}
				
					if($addmembers <> '')
					{
						$sqldata="insert into `tenant_member`(`tenant_id`,`mem_name`,`relation`,`mem_dob`,`contact_no`,`email`,`send_act_email`,`send_commu_emails`) values('".$Tenant_id."','".$addmembers."','".$addrelation."','".$Memdob."','".$number."','".$email."','".$chkCreateLogin."','".$CommuEmails."')";						
						$data=$this->m_dbConn->insert($sqldata);
					}
					else
					{
						$finalMemberCount--;
					}
				
				}
				$up_query="update `tenant_module` set  `members`='".$finalMemberCount."' where tenant_id='".$Tenant_id."'";
				$data = $this->m_dbConn->update($up_query);
			}
			$vehicleCount = $_POST['vehiclecount'];
			if($vehicleCount <>'')
			{
				$sql1 = "delete from `mem_car_parking` where `member_id`='".$Tenant_id."' and `car_type` = '1'";
				$sql1_res = $this->m_dbConn->delete($sql1);
				$sql2 = "delete from `mem_bike_parking` where `member_id`='".$Tenant_id."' and `bike_type` = '1'";
				$sql2_res = $this->m_dbConn->delete($sql2);
				for($i=1;$i <= $vehicleCount;$i++)
				{
					$regNo = $_POST['carRegNo_'.$i];
					$owner = $_POST['carOwner_'.$i];
					$make = $_POST['carMake_'.$i];
					$model = $_POST['carModel_'.$i];
					$vehicleType = $_POST['vehicleType_'.$i];
					$color = $_POST['carColor_'.$i];
					$parkingType = $_POST['parkingType_'.$i];
					$parking_slot = $_POST['parkingSlot_'.$i];
					$parking_sticker = $_POST['parkingSticker_'.$i];
					if($vehicleType == 2)//BIKE
					{
						$insert_queryBike = "insert into mem_bike_parking (`member_id`,`ParkingType`,`parking_slot`,`bike_reg_no`,`bike_owner`,`bike_model`,`bike_make`,`bike_color`,`parking_sticker`,`bike_type`) values ('".$Tenant_id."','".$parkingType."','".addslashes(trim(ucwords($parking_slot)))."','".addslashes(trim(strtoupper($regNo)))."','".addslashes(trim(ucwords($owner)))."','".addslashes(trim(ucwords($model)))."','".addslashes(trim(ucwords($make)))."','".addslashes(trim(ucwords($color)))."','".addslashes(trim(ucwords($parking_sticker)))."','1')";
						$dataBike = $this->m_dbConn->insert($insert_queryBike);
					}
					if($vehicleType == '4')//CAR
					{
						$insert_query="insert into mem_car_parking (`member_id`,`ParkingType`,`parking_slot`,`car_reg_no`,`car_owner`,`car_model`,`car_make`,`car_color`,`parking_sticker`,`car_type`) values ('".$Tenant_id."','".$parkingType."','".addslashes(trim(ucwords($parking_slot)))."','".addslashes(trim(strtoupper($regNo)))."','".addslashes(trim(ucwords($owner)))."','".addslashes(trim(ucwords($model)))."','".addslashes(trim(ucwords($make)))."','".addslashes(trim(ucwords($color)))."','".addslashes(trim(ucwords($parking_sticker)))."','1')";
						$data = $this->m_dbConn->insert($insert_query);
					}				
				}
				$up_query="update `tenant_module` set  `members`='".$finalMemberCount."' where tenant_id='".$Tenant_id."'";
				$data = $this->m_dbConn->update($up_query);
			}
			if($_POST['tenantAction'] == "ter")
			{
				if($_POST['actionPage'] == "serviceRequest")
				{
					$this->actionPage = "../rentaltenant.php?prf&mem_id=".$memberId."&sr";
				}
				else
				{
					$this->actionPage = "../view_member_profile.php?id=" . $_POST['mem_id'];
				}
			}
			else if(isset($_POST['verified']) || isset($_POST['approved']))
			{
				$this->actionPage = "../show_rentaltenant.php?TenantList=4";
			}
			else
			{
				$this->actionPage = "../view_member_profile.php?id=" . $_POST['mem_id'];
			}
			return "Update";
		}
		else
		{
			return $errString;
		}
		$_SESSION['serviceRequestDetails'] = "";
	}
	
	public function updateTenantVerificationStatus($Tenant_id, $p_verification, $leaveAndLicenseAgreement, $total_month)
	{
		$sql1 = "Select m.`role` from login as l,mapping as m where l.login_id = '".$_SESSION['login_id']."' and l.`current_mapping` = m.`id` ";
		$verifiedByDesignation = $this->m_dbConnRoot->select($sql1);
		$sqlApprovalUpdate = "Update approval_details Set `verifiedStatus` = 'Y', verifiedById = '".$_SESSION['login_id']."' and verifiedByDesignation = '".$verifiedByDesignation[0]['role']."' where referenceId = '".$Tenant_id."' and module_id = '".TENANT_SOURCE_TABLE_ID."'";
		$sqlApprovalUpdate_res = $this->m_dbConn->update($sqlApprovalUpdate);
		$updateTenantModule = "Update `tenant_module` set `active` = '1', `p_varification` = '".$p_verification."', `leaveAndLicenseAgreement` = '".$leaveAndLicenseAgreement."', `total_month` = '".$total_month."' where `tenant_id` = '".$Tenant_id."';";
		$update_Res = $this->m_dbConn->update($updateTenantModule);
		$sql1 = "select `serviceRequestId` from tenant_module where tenant_id = '".$Tenant_id."';";
		$sql1_res = $this->m_dbConn->select($sql1);
		$sql3 = "update service_request set `status` = 'Verified' where request_id = '".$sql1_res[0]['serviceRequestId']."';";
		$sql3_res = $this->m_dbConn->update($sql3);
	}
	
	public function getBillRegisterID($PeriodID, $bCreateIfNotExist = false)
	{
		$iBillRegisterID = 0;
		
		$sqlSelect = "SELECT `ID` from billregister WHERE SocietyID = '" . $_SESSION['society_id'] . "' and PeriodID = '" . $PeriodID . "' ORDER BY ID DESC LIMIT 1";
		$sqlSelectResult = $this->m_dbConn->select($sqlSelect);
		
		if($sqlSelectResult <> '')
		{
			$iBillRegisterID = $sqlSelectResult[0]['ID'];
		}
		else
		{
			if($bCreateIfNotExist == true)
			{
				$aryDate = array();
				$aryDate = $this->obj_utility->getPeriodBeginAndEndDate($PeriodID);
				$sqlInsert = "INSERT INTO `billregister`(`SocietyID`, `PeriodID`, `CreatedBy`, `BillDate`, `DueDate`, `LatestChangeID`, `Notes`) VALUES ('" . $this->m_dbConn->escapeString($_SESSION['society_id']). "', '" . $this->m_dbConn->escapeString( $PeriodID). "', '" . $this->m_dbConn->escapeString($_SESSION['login_id']). "', '" . $this->m_dbConn->escapeString($aryDate['BeginDate']) . "', '" . $this->m_dbConn->escapeString($aryDate['EndDate']) . "', '0', 'Initial Bill')";
				$sqlInsertResult = $this->m_dbConn->insert($sqlInsert);
				$iBillRegisterID = $sqlInsertResult;
			}
		}
		
		return $iBillRegisterID;
	}

	public function SendActivationEmail()
	{
		if($_POST['chkCreateLogin'] == "1")
			{
				//echo "chk".$_POST['chkCreateLogin'];
				//die();
				$role = ROLE_MEMBER;
				$unit_id  = $_POST["unit_id"];
				$code  = $_POST["Code"];
				$society_id = $_SESSION['society_id'];
				$NewUserEmailID = $_REQUEST['email_1'];
				$DisplayName = $_POST['members_1'];
				//echo "unit:".$unit_id  ." code:".	$code ." email:".$NewUserEmailID ." name:".$DisplayName ;
				
				$ActivationStatus = $this->obj_activation->AddMappingAndSendActivationEmail($role, $unit_id, $society_id, $code, $NewUserEmailID, $DisplayName);
				//echo "status:".$ActivationStatus;
				
				if($ActivationStatus != "Success")
				{				
					return "Unable to Send Activation Email.";
				}
				//die();
			}
	}
	public function display1($rsas)
	{
		$thheader = array('t_name','mob','alt_mob','email','members','start_date','end_date','p_varification','upload','note');
		$this->display_pg->edit		= "gettenant";
		$this->display_pg->th		= $thheader;
		$this->display_pg->mainpg	= "rentaltenant.php";

		$res = $this->display_pg->display_new($rsas);
		return $res;
	}
	public function pgnation()
	{
		$sql1 = "select id,`t_name`,`mob`,`alt_mob`,`email`,`members`,`start_date`,`end_date`,`p_varification`,`upload`,`note` from  where status='Y'";
		$cntr = "select count(status) as cnt from  where status='Y'";

		$this->display_pg->sql1		= $sql1;
		$this->display_pg->cntr1	= $cntr;
		$this->display_pg->mainpg	= "rentaltenant.php";

		$limit	= "50";
		$page	= $_REQUEST['page'];
		$extra	= "";

		$res	= $this->display_pg->pagination($cntr,$mainpg,$sql1,$limit,$page,$extra);
		return $res;
	}
	public function selecting($Tenant_Id)
	{
		//$sql = "select t.tenant_name,t.mobile_no,t.alter_no,t.email,t.start_date,t.end_date,t.p_varification,t.note, from `tenant_module` as t LEFT JOIN `Documents` as d on t.doc_id=d.doc_id where t.tenant_id='".$Tenant_Id."'"; 
		//$sql = "select  tenant_name,dob, mobile_no, alter_no, email, start_date, end_date, note  from `tenant_module` where tenant_id='".$Tenant_Id."'"; 
		//$sql = "select  tenant_id,unit_id, tenant_name,start_date, end_date,  agent_name, agent_no, note  from `tenant_module` where tenant_id='".$Tenant_Id."'"; 
		$sql = "select t.tenant_id,t.unit_id,t.wing_id,t.isCompany,t.license_no,t.license_authority, u.unit_no, t.tenant_name,t.tenant_MName,t.tenant_LName,t.start_date, t.end_date, t.total_month, t.`agent_name`, t.agent_no, t.note,t.`dob`,t.`mobile_no`,t.`email`, t.`img`, t.`active`, t.`p_varification`, t.`leaveAndLicenseAgreement`,`annual_rent`,`contract_value`,`security_deposit` from `tenant_module` t, `unit` u where tenant_id='".$Tenant_Id."' and t.unit_id = u.unit_id";
		
		if($_SESSION['res_flag'] == 1){
			$result=$this->landLordDB->select($sql);
		}else{
			$result=$this->m_dbConn->select($sql);
		}

		/*if($_SERVER['HTTP_HOST'] == "localhost" )
		{		
			$uploaddir = $_SERVER['DOCUMENT_ROOT']."beta_aws_master/Uploaded_Documents";			   
		}
		else
		{
			$uploaddir = $_SERVER['DOCUMENT_ROOT']."Uploaded_Documents";			   
		}*/
		$uploaddir = "Uploaded_Documents";
		$result[0]['img'] = $uploaddir."/".$result[0]['img'];
		$arrayTenant = array();
		if($result<>'')
		{
			
			$result[0]['start_date'] = getDisplayFormatDate($result[0]['start_date']);
			$result[0]['end_date'] = getDisplayFormatDate($result[0]['end_date']);
			$result[0]['dob'] = getDisplayFormatDate($result[0]['dob']);
			$sqldata="select `tmember_id`,`tenant_id`,`mem_name`,`relation`,`mem_dob`,`contact_no`,`email`,`send_act_email`,`send_commu_emails`,`emirate_no` from `tenant_member` where `tenant_id`='".$Tenant_Id."'";						
			
			if($_SESSION['res_flag'] == 1){
				$res1=$this->landLordDB->select($sqldata);
			}else{
				$res1=$this->m_dbConn->select($sqldata);
			}
			
			$result[0]['members'] = array();
			for($i=0;$i<sizeof($res1);$i++)
			{
				$res1[$i]['mem_dob'] = getDisplayFormatDate($res1[$i]['mem_dob']);
				array_push($result[0]['members'], $res1[$i]);
			}
			// Post Dated Cheques
			$chequedata="select `pdc_id`,`tenant_id`,`unit_id`,`bank_name`,`bank_branch`,`cheque_no`,`cheque_date`,`amount`,`remark`,`status`,`noofcheque`,`type`,`mode_of_payment` from `postdated_cheque` where `tenant_id`='".$Tenant_Id."'";
									
			if($_SESSION['res_flag'] == 1){
				$rescheque=$this->landLordDB->select($chequedata);
			}else{
				$rescheque=$this->m_dbConn->select($chequedata);
			}
			
			$result[0]['cheques'] = array();
			for($i=0;$i<sizeof($rescheque);$i++)
			{
				$rescheque[$i]['cheque_date'] = getDisplayFormatDate($rescheque[$i]['cheque_date']);
				array_push($result[0]['cheques'], $rescheque[$i]);
			}
			
			$result[0]['documents']=array();
			//$doc_id=$result[0]['doc_id'];
			$unit=$result[0]['unit_id'];
			$sqlDoc="select * from `documents` where `refId`='".$Tenant_Id."' and (status='Y' or status='') ";
			if($_SESSION['res_flag'] == 1){
				$res2=$this->landLordDB->select($sqlDoc);
			}else{
				$res2=$this->m_dbConn->select($sqlDoc);
			}
			for($j=0;$j<sizeof($res2);$j++)
			{
				$doc_version=$res2[$j]['doc_version'];
				$URL = "";
	            $gdrive_id = $res2[$j]['attachment_gdrive_id'];
	            if($doc_version == "1")
	            {
	            	$URL = "Uploaded_Documents/". $res2[$j]["Document"];
	            }
	            else if($doc_version == "2")
	            {
	                if($gdrive_id == "" || $gdrive_id == "-")
	                {
	                 	$URL = "Uploaded_Documents/". $res2[$j]["Document"];
	               	}
	                else
	                {
	                 	$URL = "https://drive.google.com/file/d/". $gdrive_id."/view";
	                }
	        	}
				$res2[$j]['documentLink'] = $URL;
			}
			//var_dump($res2);
			for($i=0;$i<sizeof($res2);$i++)
			{
				array_push($result[0]['documents'], $res2[$i]);
			}
			$sqlCar="select * from `mem_car_parking` where `car_type`='1' and member_id = '".$Tenant_Id."' and status='Y'";
			if($_SESSION['res_flag'] == 1){
				$sqlCar_res = $this->landLordDB->select($sqlCar);
			}else{
				$sqlCar_res = $this->m_dbConn->select($sqlCar);
			}
			//var_dump($sqlCar_res);
			$result[0]['carDetails'] = $sqlCar_res;
			$sqlBike="select * from `mem_bike_parking` where `bike_type`='1' and member_id = '".$Tenant_Id."' and status='Y'";
			if($_SESSION['res_flag'] == 1){
				$sqlBike_res = $this->landLordDB->select($sqlBike);
			}else{
				$sqlBike_res = $this->m_dbConn->select($sqlBike);
			}
			$result[0]['bikeDetails'] = $sqlBike_res;
			if(sizeof($sqlBike_res) == 0 && sizeof($sqlCar_res) == 0)
			{
				$result[0]['vehicleCount'] = 0;
			}
			else
			{
				$result[0]['vehicleCount'] = sizeof($sqlBike_res)+sizeof($sqlCar_res);
			}
		}
		return $result;
	}
	public function deleting($Tenant_id)
	{
		$sql = "update `tenant_module` set status='N' where tenant_id='".$Tenant_id."'";
		$res = $this->m_dbConn->update($sql);
	}
	
	
														/*--------------------------------------------  Show tenant list------------------------------------------------------*/
														
	public function getRecords()
	{
		 $sql =" select t.*,d.* from `tenant_module` as t LEFT JOIN `documents` as d on t.doc_id=d.doc_id where t.status='Y'";
		$result=$this->m_dbConn->select($sql);
		//echo $sql;
		return $result;
	}
	public function getTenantDocuments($UnitID = 0)
	{
		
		//$UnitID = $_SESSION["unit_id"];
		 $sql =" select t.*,d.* from `tenant_module` as t LEFT JOIN `documents` as d on t.tenant_id=d.refID where t.status='Y' and d.source_table=1";
		 //echo "unitid:".$UnitID ;
		 if($SESSION['role']!=ROLE_ADMIN && $_SESSION['role']!=ROLE_SUPER_ADMIN && $_SESSION['role']!=ROLE_ADMIN_MEMBER)
		 {
		 	$sql .= " and t.unit_id='".$UnitID."'";
		 }
		$result=$this->m_dbConn->select($sql);
		//echo $sql;
		return $result;
	}
	public function getTenantDocumentsNew($UnitID = 0)
	{
		
		//$UnitID = $_SESSION["unit_id"];
		 $sql =" select t.* from `tenant_module` as t where t.status='Y'";
		 //echo "unitid:".$UnitID ;
		// if($SESSION['role']!=ROLE_ADMIN && $_SESSION['role']!=ROLE_SUPER_ADMIN && $_SESSION['role']!=ROLE_ADMIN_MEMBER)
		 if($UnitID != 0)
		 {
		 	$sql .= " and t.unit_id='".$UnitID."'";
		 }
		$result=$this->m_dbConn->select($sql);
		//echo $sql;
		return $result;
	}
	
													/*-----------------------------------------------show image and document from edit ---------------------------------*/
	public function getViewDetails($TenantId)
	{
		 $sql="select t.tenant_id,t.doc_id,t.img,t.active,d.Document from `tenant_module` as t LEFT JOIN `documents` as d on d.refID=t.tenant_id where t.tenant_id='".$TenantId."' and d.status='Y'";
		$result=$this->m_dbConn->select($sql);
		return $result;
	}
	
												/*-------------------------------------------------------------- Menber profile function page--------------------------------------------------------*/
	public function getTenantRecords($unit_id)
	{	
		if($_SESSION['res_flag'] == 1){
			$sql ="select * from `tenant_module` as tm JOIN unit as u on tm.unit_id = u.unit_id where tm.unit_id ='".$unit_id."' and tm.tenant_id = '".$_REQUEST['id']."' and tm.status='Y'";
		//
		// $sql ="select *, Count(t.unit_id) as counttotal from `tenant_module` as t LEFT JOIN `Documents` as d on t.doc_id=d.doc_id where t.status='Y' and t.`unit_id`='".$unit_id."' and t.end_date >= now()";
		//$sql =" select* from `tenant_module` as t LEFT JOIN `Documents` as d on t.doc_id=d.doc_id where t.status='Y' and t.`unit_id`='".$unit_id."'";
		 $result=$this->landLordDB->select($sql);
		 $Tenant_Id = $result[0]['tenant_id'];
		 //echo "ID: " .$Tenant_Id;
		if($Tenant_Id<>'')
			{
				$sqldata="select `tenant_id`,`mem_name`,`relation`,`mem_dob` ,`contact_no`,`email`,`send_act_email`,`send_commu_emails`,`emirate_no` from `tenant_member` where `tenant_id`='".$Tenant_Id."'";						
				$res1=$this->landLordDB->select($sqldata);
				$result[0]['Allmembers']=$res1;
			}
			if($Tenant_Id<>'')
			{
				$sqldoc="select * from documents where refID='".$Tenant_Id."' and status in('Y','') and unit_id='".$unit_id."'";
			//echo "sql:".$sqldoc;	
				$res2=$this->landLordDB->select($sqldoc);
				for($j=0;$j<sizeof($res2);$j++)
				{
					$doc_version=$res2[$j]['doc_version'];
					$URL = "";
	            	$gdrive_id = $res2[$j]['attachment_gdrive_id'];
	            	if($doc_version == "1")
	            	{
	            		$URL = "Uploaded_Documents/". $res2[$j]["Document"];
	            	}
	            	else if($doc_version == "2")
	            	{
	                	if($gdrive_id == "" || $gdrive_id == "-")
	                	{
	                 		$URL = "Uploaded_Documents/". $res2[$j]["Document"];
	               		}
	                	else
	                	{
	                 		$URL = "https://drive.google.com/file/d/". $gdrive_id."/view";
	                	}
	        		}
					$res2[$j]['documentLink'] = $URL;
				}
				if(sizeof($res2) > 0)
				{
					$result[0]['Alldocuments']=$res2;
				}
				else
				{
					$result[0]['Alldocuments'] = "No documents attached";
				}
			}
			
			$sqlCount ="select  Count(unit_id) as counttotal from `tenant_module`  where status='Y' and `unit_id`='".$unit_id."'" ;
			$res3=$this->landLordDB->select($sqlCount);
			$result[0]['Count']=$res3[0]['counttotal'];
			if($Tenant_Id<>'')
			{
				$result[0]['Count'] = $result[0]['Count'] - 1;
			}
			if($this->m_bShowTrace)
			{
				echo "<pre>";
				print_r($result);
				echo "</pre>";
			}
		return $result;
		}else{
			$sql ="select * from `tenant_module` as tm JOIN unit as u on tm.unit_id = u.unit_id where tm.unit_id ='".$unit_id."' and tm.tenant_id = '".$_REQUEST['id']."' and tm.status='Y'";
			//
			// $sql ="select *, Count(t.unit_id) as counttotal from `tenant_module` as t LEFT JOIN `Documents` as d on t.doc_id=d.doc_id where t.status='Y' and t.`unit_id`='".$unit_id."' and t.end_date >= now()";
			//$sql =" select* from `tenant_module` as t LEFT JOIN `Documents` as d on t.doc_id=d.doc_id where t.status='Y' and t.`unit_id`='".$unit_id."'";
			$result=$this->m_dbConn->select($sql);
			$Tenant_Id = $result[0]['tenant_id'];
			//echo "ID: " .$Tenant_Id;
			if($Tenant_Id<>'')
				{
					$sqldata="select `tenant_id`,`mem_name`,`relation`,`mem_dob` ,`contact_no`,`email`,`send_act_email`,`send_commu_emails`,`emirate_no` from `tenant_member` where `tenant_id`='".$Tenant_Id."'";						
					$res1=$this->m_dbConn->select($sqldata);
					$result[0]['Allmembers']=$res1;
				}
				if($Tenant_Id<>'')
				{
					$sqldoc="select * from documents where refID='".$Tenant_Id."' and status in('Y','') and unit_id='".$unit_id."'";
				//echo "sql:".$sqldoc;	
					$res2=$this->m_dbConn->select($sqldoc);
					for($j=0;$j<sizeof($res2);$j++)
					{
						$doc_version=$res2[$j]['doc_version'];
						$URL = "";
						$gdrive_id = $res2[$j]['attachment_gdrive_id'];
						if($doc_version == "1")
						{
							$URL = "Uploaded_Documents/". $res2[$j]["Document"];
						}
						else if($doc_version == "2")
						{
							if($gdrive_id == "" || $gdrive_id == "-")
							{
								$URL = "Uploaded_Documents/". $res2[$j]["Document"];
							}
							else
							{
								$URL = "https://drive.google.com/file/d/". $gdrive_id."/view";
							}
						}
						$res2[$j]['documentLink'] = $URL;
					}
					if(sizeof($res2) > 0)
					{
						$result[0]['Alldocuments']=$res2;
					}
					else
					{
						$result[0]['Alldocuments'] = "No documents attached";
					}
				}
				
				$sqlCount ="select  Count(unit_id) as counttotal from `tenant_module`  where status='Y' and `unit_id`='".$unit_id."'" ;
				$res3=$this->m_dbConn->select($sqlCount);
				$result[0]['Count']=$res3[0]['counttotal'];
				if($Tenant_Id<>'')
				{
					$result[0]['Count'] = $result[0]['Count'] - 1;
				}
				if($this->m_bShowTrace)
				{
					echo "<pre>";
					print_r($result);
					echo "</pre>";
				}
			return $result;
		}
	}
													
	public function getTenantRecords1($unit_id)
	{	
	$sql ="select * from `tenant_module` as tm JOIN unit as u on tm.unit_id = u.unit_id where tm.unit_id ='".$unit_id."' and tm.status='Y'";
		//
		// $sql ="select *, Count(t.unit_id) as counttotal from `tenant_module` as t LEFT JOIN `Documents` as d on t.doc_id=d.doc_id where t.status='Y' and t.`unit_id`='".$unit_id."' and t.end_date >= now()";
		//$sql =" select* from `tenant_module` as t LEFT JOIN `Documents` as d on t.doc_id=d.doc_id where t.status='Y' and t.`unit_id`='".$unit_id."'";
		 $result=$this->m_dbConn->select($sql);
		 $Tenant_Id=$result[0]['tenant_id'];
			if($Tenant_Id<>'')
			{
				$sqldata="select `tenant_id`,`mem_name`,`relation`,`mem_dob` ,`contact_no`,`email`,`send_act_email`,`send_commu_emails` from `tenant_member` where `tenant_id`='".$Tenant_Id."'";						
				$res1=$this->m_dbConn->select($sqldata);
				$result[0]['Allmembers']=$res1;
			}
			if($Tenant_Id<>'')
			{
				$sqldoc="select * from documents where refID='".$Tenant_Id."' and status in('Y','') and unit_id='".$unit_id."'";
			//echo "sql:".$sqldoc;	
				$res2=$this->m_dbConn->select($sqldoc);
				for($j=0;$j<sizeof($res2);$j++)
				{
					$doc_version=$res2[$j]['doc_version'];
					$URL = "";
	            	$gdrive_id = $res2[$j]['attachment_gdrive_id'];
	            	if($doc_version == "1")
	            	{
	            		$URL = "Uploaded_Documents/". $res2[$j]["Document"];
	            	}
	            	else if($doc_version == "2")
	            	{
	                	if($gdrive_id == "" || $gdrive_id == "-")
	                	{
	                 		$URL = "Uploaded_Documents/". $res2[$j]["Document"];
	               		}
	                	else
	                	{
	                 		$URL = "https://drive.google.com/file/d/". $gdrive_id."/view";
	                	}
	        		}
					$res2[$j]['documentLink'] = $URL;
				}
				if(sizeof($res2) > 0)
				{
					$result[0]['Alldocuments']=$res2;
				}
				else
				{
					$result[0]['Alldocuments'] = "No documents attached";
				}
			}
			
			$sqlCount ="select  Count(unit_id) as counttotal from `tenant_module`  where status='Y' and `unit_id`='".$unit_id."'" ;
			$res3=$this->m_dbConn->select($sqlCount);
			$result[0]['Count']=$res3[0]['counttotal'];
			if($Tenant_Id<>'')
			{
				$result[0]['Count'] = $result[0]['Count'] - 1;
			}
			if($this->m_bShowTrace)
			{
				echo "<pre>";
				print_r($result);
				echo "</pre>";
			}
		return $result;
	}												
													/*-------------------------------------------------------------- Member List  form Unit --------------------------------------------------------*/
			public function MemberList($unit)
			{
			//	$sql =" select* from `tenant_module` as t LEFT JOIN `Documents` as d on t.doc_id=d.doc_id where t.status='Y' and t.unit_id='".$unit."'";
				$sql =" select *, u.unit_no from `tenant_module` as t LEFT JOIN `documents` as d on t.doc_id=d.doc_id join unit as u on t.unit_id=u.unit_id where t.status='Y' and t.unit_id='".$unit."'";
				$result=$this->m_dbConn->select($sql);
				return $result;
				
			}
			
			public function getViewDetailsUser($TenantId)
			{
			$data=$this->selecting($TenantId);
			
				return $data;	
		}
		
		/*-------------------------------------------------------------Notificatino alert-------------------------------------------------*/
		
	public function TenantAlert()
	{
		if($_SESSION['role'] && ($_SESSION['role']==ROLE_ADMIN || $_SESSION['role']==ROLE_SUPER_ADMIN || $_SESSION['role']==ROLE_ADMIN_MEMBER))
		{
		//$sql="select * ,u.unit_no from `tenant_module` as t join unit as u on u.unit_id=t.unit_id where t.end_date >= TIMESTAMPADD( DAY , -30, NOW( )+ INTERVAL 5 HOUR + INTERVAL 30 MINUTE) t.status='Y' and t.active='1' order by t.tenant_id desc";
		//$sql="select *,u.unit_no from `tenant_module` as t join unit as u on u.unit_id=t.unit_id where t.end_date >= DATE(now()) and t.end_date <= DATE_ADD(DATE(now()), INTERVAL 1 Month) and t.status='Y' and t.active='1' order by t.tenant_id desc";
		$sql="select *,u.unit_no, w.wing from `tenant_module` as t join unit as u on u.unit_id=t.unit_id join wing as w on u.wing_id=w.wing_id where t.end_date >= DATE(now()) and t.end_date <= DATE_ADD(DATE(now()), INTERVAL 1 Month) and t.status='Y' and t.active='1' order by t.tenant_id desc";
		
		}
		else
		{
			$sql="select *,u.unit_no, w.wing from `tenant_module` as t join unit as u on u.unit_id=t.unit_id join wing as w on u.wing_id=w.wing_id where t.unit_id='".$_SESSION['unit_id']."' and  t.end_date >= DATE(now()) and t.end_date <= DATE_ADD(DATE(now()), INTERVAL 1 Month) and t.status='Y' and t.active='1' order by t.tenant_id desc";
		}
		$result=$this->m_dbConn->select($sql);
		return $result;
	}

	public function getTenantWing($id)
	{
		$query = "select wing_id,wing from wing as w JOIN society as s ON w.society_id = s.society_id";
		if($_SESSION['res_flag'] == 1){
			$isLandLordDB = true;
		}
		return $this->combobox($query, $id, null,$isLandLordDB);
						
	}

	public function getTenantUnit($id, $wing_id)
	{
		echo $query = "select unit_id,unit_no from unit as u JOIN wing as w ON u.wing_id = w.wing_id where u.status = 'Y' and u.wing_id = '".$wing_id."'";
		
		if($_SESSION['landLordDB']){
			$isLandLordDB = true;
		}
		return $this->combobox($query, $id, $wing_id, $isLandLordDB);
						
	}

	public function getPDC()
	{
		$query = "select * from postdated_cheque where tenant_id = '".$_GET['id']."'";

		$data = $this->m_dbConn->select($query);
		
		return $data;						
	}
	public function delDocument($docId)
	{
		
		$query = "delete from documents where doc_id = '".$docId."'";
		return $this->landLordDB->select($query);
		
	}

	public function combobox($query, $id, $wing_id="",$dbselected= false)
	{
		$str.="<option value='0'>Please Select</option>";
		if($dbselected){
			$data = $this->landLordDB->select($query);
		}else{
			$data = $this->m_dbConn->select($query);
		}
		if(!is_null($data))
		{
			foreach($data as $key => $value)
			{
				$i=0;
				foreach($value as $k => $v)
				{
					if($i==0)
					{
						if($id==$v)
						{
							$sel = 'selected';	
						}
						else
						{
							$sel = '';
						}
						
						$str.="<OPTION VALUE=".$v.' '.$sel.">";
					}
					else
					{
						$str.=$v."</OPTION>";
					}
					$i++;
				}
			}
		}
		return $str;
	}

	public function combobox07($query,$id)
	{
			$str.="<option value='1'>Please Select</option>";
			$data = $this->m_dbConn->select($query);
				if(!is_null($data))
				{
					foreach($data as $key => $value)
					{
						$i=0;
						foreach($value as $k => $v)
						{
							if($i==0)
							{
								if($id==$v)
								{
									$sel = 'selected';	
								}
								else
								{
									$sel = '';
								}
								
								$str.="<OPTION VALUE=".$v.' '.$sel.">";
							}
							else
							{
								$str.=$v."</OPTION>";
							}
							$i++;
						}
					}
				}
					return $str;
	}
	public function checkTenantStatus($unitId)
	{
		$result = array();
		$sql1 = "select * from `tenant_module` where status='Y' and `unit_id`='".$unitId."' and end_date >= CURDATE()";
		$sql1_res = $this->m_dbConn->select($sql1);
		//var_dump($sql1_res);
		$memberId = $this->getMemberId($unitId);
		if(sizeof($sql1_res) > 0)
		{
			$result['tenantStatus'] = 1;//Active Tenant Exits
			$result['memberId'] = $memberId;
			$result['tenantId'] = $sql1_res[0]['tenant_id'];
		}
		else
		{
			$result['tenantStatus'] = 0;//No Active Tenant Exits
			$result['memberId'] = $memberId;
			$result['tenantId'] = 0;
		}
		//var_dump($result);
		return ($result);
	}
	public function getMemberId($unitId)
	{
		$sql1 = "SELECT `member_id` FROM `member_main` where unit = '".$unitId."' and ownership_status = '1' and status = 'Y'";
		$sql1_res = $this->m_dbConn->select($sql1);
		return ($sql1_res[0]['member_id']);
	}
	public function getApprovalLevel()
	{
		$sql1 = "Select `Value` from appdefault_new where `Property` = 'LevelOfApprovalForTenantRequest' and module_id = '3';";
		$sql1_res = $this->m_dbConn->select($sql1);
		return ($sql1_res[0]['Value']);
	}

	public function getOpeningBalanceDate()
	{
		$currentYear = $_SESSION['default_year'];
		
		//$sql = "Select periodtbl.BeginingDate from period as periodtbl JOIN society as societytbl ON societytbl.bill_cycle = periodtbl.Billing_cycle where YearID = '" . $currentYear . "' ORDER BY periodtbl.ID ASC";
		
		//$result = $this->m_dbConn->select($sql);
		$OpeningBalanceDate = $this->obj_utility->GetDateByOffset($_SESSION['default_year_start_date'] , -1);
		return $OpeningBalanceDate;
		//return $result[0]['BeginingDate'];
	}

	public function FetchDate($default_year)
	{
		$sql = "select DATE_FORMAT(BeginingDate, '%d-%m-%Y') as BeginingDate from `year` where `YearID`='".$default_year."' ";
		$res = $this->m_dbConn->select($sql);
		return  $this->obj_utility->GetDateByOffset($res[0]['BeginingDate'],-1);
	}

	public function check_unit($date,$unit_id){
		if($_SESSION['res_flag'] == 1){
			$dateDB = getDBFormatDate($date); 
			$sql = "SELECT u.unit_no, tm.tenant_name, tm.end_date from tenant_module as tm, unit as u where tm.unit_id = u.unit_id and tm.unit_id = '".$unit_id."' and end_date >= '".$dateDB."'";
			$res = $this->landLordDB->select($sql); 
			$end_date = getDisplayFormatDate($res[0]['end_date']);
			if($res<>''){
				$msg = "Cannot rent out the flat ".$res[0]['unit_no']." from selected date ".$date.". Since the flat is rented out by ".$res[0]['tenant_name']." till ".$end_date.". If tenant has vacated the flat please update end date by clicking Terminate lease";
				return $msg;
			}
		}else{
			$dateDB = getDBFormatDate($date); 
			$sql = "SELECT u.unit_no, tm.tenant_name, tm.end_date from tenant_module as tm, unit as u where tm.unit_id = u.unit_id and tm.unit_id = '".$unit_id."' and end_date >= '".$dateDB."'";
			$res = $this->m_dbConn->select($sql); 
			$end_date = getDisplayFormatDate($res[0]['end_date']);
			if($res<>''){
				$msg = "Cannot rent out the flat ".$res[0]['unit_no']." from selected date ".$date.". Since the flat is rented out by ".$res[0]['tenant_name']." till ".$end_date.". If tenant has vacated the flat please update end date by clicking Terminate lease";
				return $msg;
			}
		}
	}
}
?> 