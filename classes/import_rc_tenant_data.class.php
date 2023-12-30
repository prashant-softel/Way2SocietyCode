<?php
include_once("include/dbop.class.php");
include_once("utility.class.php");
include_once("dbconst.class.php");
include_once("register.class.php");
include_once("changelog.class.php");//Pending - Verify
include_once("rentaltenant.class.php");
include_once("include/fetch_data.php");

// error_reporting(1);
class import_rc_tenantdata 
{
	public $m_dbConn;
	public $landLordDB;
	public $landLordDBRoot;
	public $obj_utility;
	public $errorfile_name;
	public $errorLog;
	public $actionPage = '../import_rc_tenant_data.php';
	public $bvalidate;
	public $changeLog;
	public $obj_fetch;
	public $obj_tenant;
	

	private $FDCatArray;

	function __construct($dbConnRoot, $dbConn, $landLordDB, $landLordDBRoot)
	{
		$this->m_dbConn = $dbConn;
		$this->dbConnRoot = $dbConnRoot;
		$this->landLordDB = $landLordDB;
		$this->landLordDBRoot = $landLordDBRoot;
		$this->obj_utility = new utility($this->m_dbConn);
		$this->changeLog = new changelog($this->m_dbConn);
		$this->register = new regiser($this->m_dbConn);
		// echo "connection" . $this->landLordDBRoot;
		// var_dump($this->m_dbConn);
		// var_dump($this->landLordDB);
		
		$this->obj_tenant = new rentaltenant($this->m_dbConn, $this->dbConnRoot, $this->landLordDB, $this->landLordDBRoot);
        


		$this->obj_fetch = new FetchData($this->m_dbConn);

		$a = $this->obj_fetch->GetSocietyDetails($_SESSION['society_id']);
			
	}
	

	public function UploadData1($fileName,$fileData, $bvalidate)
	{
		
		$Foldername = $this->obj_fetch->objSocietyDetails->sSocietyCode;

		if (!file_exists('../logs/import_log/'.$Foldername)) 
		{
			mkdir('../logs/import_log/'.$Foldername, 0777, true);
		}

		$a = 'import_rc_tenant_data_errorlog_'.date("d.m.Y").'_'.rand().'.html';
		$b = '../logs/import_log/'.$Foldername;

		$c = 'logs/import_log/'.$Foldername;
		
		$this->errorfile_name = $b.'/'.$a;
		$errorfile = fopen($this->errorfile_name, "a");

		if($bvalidate == true)
		{
			$this->errorfile_name = $c.'/'.$a;
		}else
		{
			$this->errorfile_name = $b.'/'.$a;
		}

		$this->errorLog = $this->errorfile_name;
   
    

		$errormsg="[Importing Tenant Data]";
		$isImportSuccess = true;
		$this->obj_utility->logGenerator($errorfile,'start',$errormsg);
		// $bvalidate = true;

		$array = array();
		$Success = 0;
		$rowCount = 0;
		$m_TraceDebugInfo = "";
		$noErrorInFIle = array();

		

		foreach($fileData as $row)
		{
			$isImportSuccess = true;

			if($row[0] || $row[1] <> '')
			{
				$rowCount++;
				if($rowCount == 1)//Header
				{
					
					
					$UnitNoCol = array_search('APT_NO',$row, true);
					$TenantFname = array_search('TenantFname',$row, true);
					$TenantMname = array_search('TenantMname',$row, true);
					$TenantLname = array_search('TenantLname',$row, true);
					$DOB = array_search(DOB,$row, true);
					// $Email = array_search(Email,$row, true);
					$ContactNumber = array_search('ContactNumber',$row, true);
					$Agentname = array_search('Agentname',$row, true);	
					$AgentContactNo = array_search('AgentContactNo',$row, true);
					$StartDate = array_search('StartDate',$row, true);
					$CreateDate = array_search('createDate',$row, true);
					$EndDate = array_search('EndDate',$row, true);
					$TenantType = array_search('TenantType',$row, true);	
					$Address = array_search('Address',$row, true);
					$Pincode = array_search('Pincode',$row, true);
					$City = array_search('City',$row, true);
					$wing = array_search('BLDG_NAME',$row, true);
					$Security_deposit = array_search('SecurityDeposit',$row, true);
					$annual_rent = array_search('Annual_rent',$row, true);
					$mode_of_payment = array_search('REMARKS',$row, true);
					$mobile = array_search('MOBILE',$row, true);
					$mobile_1 = array_search('MOBILE_1',$row, true);
					$Email = array_search('EMAIL',$row, true);
					$email_1 = array_search('EMAIL_1',$row, true);
					$contract_value = array_search('Contract_value',$row, true);
					$Licence_no = array_search('Licence_no',$row, true);
					$Licence_authority = array_search('Licence_authority',$row, true);
					$Emerait_id = array_search('Emirates_id ',$row, true);
					$no_of_occupants = array_search('No_of_Occupants',$row, true);
					$property_usage = array_search('Property_usage',$row, true);
                                        $sdOpeningBalance = array_search('SD_openingBalance',$row,true);
					

					
						$ErrorPrintHead = false;
						
						
						if($ErrorPrintHead == true)
						{
							array_push($noErrorInFIle,$ErrorPrintHead);
							$this->obj_utility->logGenerator($errorfile,$rowCount,$m_TraceDebugInfo,"E");	
						}
						//die();
						
						/*if(!isset($UnitNoCol) || !isset($TenantFname) || !isset($TenantMname) || !isset($DOB)||!isset($Email) || !isset($ContactNumber) || !isset($Agentname) || !isset($AgentContactNo) || !isset($StartDate) || !isset($EndDate) || !isset($TenantType) || !isset($Address) || !isset($Pincode) || !isset($City) || !isset($NoOfMember) )
						{
								$result = '<p>Required Column Names Not Found. Cant Proceed Further......</p>';
								$errormsg=" Column names does not match";
								$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"E");
								return $result;
								
						}*/

					
				}
				else
				{

					/*if($rowCount == 2)
					{
						continue;	
					}*/
					
					//print_r($row[$Address]);
					//echo $row[$TenantFname];
					/*if($row[$UnitNoCol] == "")
					{
						echo "ashwini";
					}
					else
					{
						echo "rokade";
					}
					die();*/
						$unit_no = $row[$UnitNoCol];
						$errormsg = '';

						//getting wing id
						$getwing = "select `wing_id` from `wing` where `wing` = '".$row[$wing]."' ";
						$wingid = $this->m_dbConn->select($getwing);

						
						$unitidquery = "select `unit_id`, `unit_no` from unit where `unit_no` = '".$unit_no."' and `wing_id`='".$wingid[0]['wing_id']."' ";
						$unitid = $this->m_dbConn->select($unitidquery);
						
						// echo $unitidquery;
						// exit;
						// print_r($unitid);
						//die();
						$wingid = $wingid[0]['wing_id'];
						if($unitid[0]['unit_id'] == '')
						{
							$errormsg="Unit No Missing Or Please mention correct Unit No.  :<br/>";
							$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"E");
							$isImportSuccess = false;
						}else
						{
							$UnitnoCol = $unitid[0]['unit_id'];
						}
						
						$Tenantfname  = trim($row[$TenantFname]);
						$Tenantmname = trim($row[$TenantMname]);
						$Tenantlname = rtrim($row[$TenantLname]);
						$tenantname = $Tenantfname." ".$Tenantmname." ".$Tenantlname;
						$tenantName = trim($tenantname);
						$sd_openingBal = $row[$sdOpeningBalance];
                        $Ledgerid = $this->obj_tenant->InsertTenantLedgers($tenantName,$wingid,$unit_no,$sd_openingBal);
						// echo $UnitnoCol;
						// die();

						// if($row[$TenantFname] == '')
						// {
						// 	$errormsg="Tenant First Name Missing   :<br/>";
						// 	$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"E");
						// 	$isImportSuccess = false;
							
						// }else
						// {
						// 	$Tenantfname  = $row[$TenantFname];
						// }
						
						// if($row[$TenantMname] == '')
						// {
						// 	$errormsg="Tenant Middle Name Missing    :<br/>";
						// 	$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"E");
						// 	$isImportSuccess = false;
							
						// }else
						// {
						// 	$Tenantmname = $row[$TenantMname];
						// }

						// if($row[$TenantLname] == '')
						// {
						// 	$errormsg="Tenant Last Name Missing   :<br/>";
						// 	$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"E");
						// 	$isImportSuccess = false;
							
						// }else
						// {
						// 	$Tenantlname = $row[$TenantLname];
						// }

						// if($row[$DOB] == '')
						// {
						// 	$errormsg="DOB Missing :<br/>";
						// 	$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"E");
						// 	$isImportSuccess = false;

						// }else
						// {
						// 	$date = explode('-', $row[$DOB]);
						// 	if(strlen($date[0]) < 3 && strlen($date[2]) < 3)
						// 	{
						// 		$errormsg = "The Date format should be 'dd-mm-yyyy' ";
						// 		$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"E");
						// 		$isImportSuccess = false;

						// 	}else
						// 	{
						// 		$dob = getDBFormatDate($row[$DOB]);
						// 	}

						// 	// $dateofdeposite = $row[$DateofDeposite];
						// }


						/*$datefomr = $row[$DOB];
						$datecheck = $this->obj_utility->dateFormat($datefomr)
						if($datecheck == '')
						{
							$errormsg="DOB Missing   :<br/>";
							$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"E");
							$isImportSuccess = false;
						}
						else
						{
							$dob = $datecheck;
						}
						*/
						// if($row[$ContactNumber] == '')
						// {
						// 	$errormsg="Contact Number Missing   :<br/>";
						// 	$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"E");
						// 	$isImportSuccess = false;
						// }else
						// {
						// 	$Contactnumber = $row[$ContactNumber];
						// }

						// if($row[$Agentname] == '')
						// {
						// 	$errormsg="Agent name Missing  :<br/>";
						// 	$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"E");
						// 	$isImportSuccess = false;
						// }else
						// {
						// 	$agentname = $row[$Agentname];
						// }

						// if($row[$AgentContactNo] == '')
						// {
						// 	$errormsg="Agent Contact Number Missing   :<br/>";
						// 	$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"E");
						// 	$isImportSuccess = false;
						// }else
						// {
						// 	$agentContactNo = $row[$AgentContactNo];
						// }
						// if($row[$DOB] == '')
						// {
						// 	$errormsg="DOB Missing :<br/>";
						// 	$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"E");
						// 	$isImportSuccess = false;

						// }else
						// {
						// 	$date = explode('-', $row[$DOB]);
						// 	if(strlen($date[0]) < 3 && strlen($date[2]) < 3)
						// 	{
						// 		$errormsg = "The Date format should be 'dd-mm-yyyy' ";
						// 		$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"E");
						// 		$isImportSuccess = false;

						// 	}else
						// 	{
						// 		$dob = getDBFormatDate($row[$DOB]);
						// 	}

						// 	// $dateofdeposite = $row[$DateofDeposite];
						// }
						$email = $row[$Email];
                        // if($row[$Email] == '')
						// {
						// 	$errormsg="Email ID Missing   :<br/>";
						// 	$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"E");
						// 	$isImportSuccess = false;
							
						// }else
						// {
						// 	$email = $row[$Email];
						// 	//echo "Email: " .$email;
						// }
						$email1 = $row[$email_1];
						// if($row[$email_1] == '')
						// {
						// 	$errormsg="Email ID 1 Missing   :<br/>";
						// 	$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"E");
						// 	$isImportSuccess = false;
							
						// }else
						// {
						// 	$email1 = $row[$email_1];
						// 	// echo "test: ".$email1;
						// }

						$Mobile = $row[$mobile];
						$mobile1 = $row[$mobile_1];
						$securitydeposit = $row[$Security_deposit];
						// if($row[$mobile] == '')
						// {
						// 	$errormsg="Mobile no Missing   :<br/>";
						// 	$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"E");
						// 	$isImportSuccess = false;
							
						// }else
						// {
						// 	$Mobile = $row[$mobile];
						// 	// echo "m : " .$Mobile;
						// }
						// if($row[$mobile_1] == '')
						// {
						// 	$errormsg="Mobile no 1 Missing   :<br/>";
						// 	$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"E");
						// 	$isImportSuccess = false;
							
						// }else
						// {
						// 	$mobile1 = $row[$mobile_1];
						// }
						$enddate = $row[$EndDate];
						$startdate = $row[$StartDate];
						$Annualrent = $row[$annual_rent];
						$contractvalue = $row[$contract_value];
					    $licenceno = $row[$Licence_no];
					    $licenceauthority = $row[$Licence_authority];
					    $emeraitid = $row[$Emerait_id];
					    $noofoccupants = $row[$no_of_occupants];
						$propertyUsage = $row[$property_usage];

						// if($row[$StartDate] == '')
						// {
						// 	$errormsg="Start Date Missing   :<br/>";
						// 	$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"E");
						// 	$isImportSuccess = false;
							
						// }else
						// {
						// 	$startdate = $row[$StartDate];
						// }
						// if($row[$EndDate] == '')
						// {
						// 	$errormsg="End Date Missing:<br/>";
						// 	$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"E");
						// 	$isImportSuccess = false;
							
						// }else
						// {
						// 	$enddate = $row[$EndDate];
						// 	// echo "enddate".$enddate;
						// } 
                        // if($row[$Security_deposit] == '')
						// {
						// 	$errormsg = "security deposit missing: <br/>";
						// 	$isImportSuccess = false;
							
						// }else{
						// 	$securitydeposit = $row[$Security_deposit];
						// }

						// if($row[$annual_rent] == '')
						// {
						// 	$errormsg = "Annual rent missing: <br/>";
						// 	$isImportSuccess = false;
							
						// }else{
						// 	$Annualrent = $row[$annual_rent];
						// }

						// if($row[$mode_of_payment] == '')
						// {
						// 	$errormsg = "mode payment missing: <br/>";
						// 	$isImportSuccess = false;
							
						// }else{
						// 	$modeofpayment = $row[$mode_of_payment];
						// }
						$modeofpayment = $row[$mode_of_payment];
                        $Tenanttype = $row[$TenantType];

						// if($row[$TenantType] == '')
						// {
						// 	$errormsg="Tenant Type Missing  :<br/>";
						// 	$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"E");
						// 	$isImportSuccess = false;
							
						// }
						// else
						// {
						// 	$Tenanttype = $row[$TenantType];
						// }
						// if($row[$note] == '')
						// {
						// 	$errormsg = "Note missing: <br/>";
						// 	$isImportSuccess = false;
						// }else{
						// 	$Note = $row[$note];
						// }
						// echo "Email: " . $Email . ", Email_1: " . $email_1 . ", Mobile: " . $mobile . ", Mobile_1: " . $mobile_1 . "<br>";

						// $address = $row[$Address];
						// $pincode = $row[$Pincode];
						// $city = $row[$City];
						$NoOfmember= $row[$NoOfMember];

						/*echo $UnitnoCol;
						echo $Tenantfname;
						echo $Tenantmname;
						echo $Tenantlname;
						echo $dob;
						echo $email;
						echo $Contactnumber;
						echo $agentname;
						echo $agentContactNo;
						echo $Startdate;
						echo $Enddate;
						echo $Tenanttype;
						print_r($Address);
						print_r($Pincode);
						print_r($City);
						print_r($NoOfMember);*/

						//die();
						//echo $isImportSuccess;
						
						if($isImportSuccess == false)
						{

				            $errormsg = "Data not Inserted";
							$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"I");
							
						}
						//elseif($UnitnoCol <> '' && $Tenantfname <> '' && $Tenantmname <> '' && $Tenantlname <> '' && $dob <> '' && $email <> '' && $Contactnumber <> '' && $agentname <> '' && $agentContactNo <> '' && $Startdate <> '' && $Enddate <> '' && $Tenanttype <> '')
					
						else
						{
                            $existingTenantQuery = "SELECT * FROM `tenant_module` WHERE `unit_id` = '".$UnitnoCol."' AND `tenant_name` = '".$Tenantfname."' AND `tenant_MName` = '".$Tenantmname."' AND `Tenant_LName` = '".$Tenantlname."' AND `start_date` = '".getDBFormatDate($startdate)."'";
                            $existingTenant = $this->m_dbConn->select($existingTenantQuery);

							if ($existingTenant) {
								// Tenant exists, update the existing record
								$updateQuery = "UPDATE `tenant_module` SET  `end_date` = '".getDBFormatDate($enddate)."', `security_deposit` = '".$securitydeposit."', `annual_rent` = '".$Annualrent."', `mode_of_payment` = '".$modeofpayment."', `mobile_no` = '".$Mobile."', `mobile_1` = '".$mobile1."', `email` = '".$email."', `email_1` = '".$email1."', `contract_value` = '".$contractvalue."', `license_no` = '".$licenceno ."', `license_authority` = '".$licenceauthority."', `emirate_no` = '".$emeraitid."', `members`='".$noofoccupants."', `property_type`='".$propertyUsage."' WHERE `tenant_id` = '".$existingTenant[0]['tenant_id']."'";
					
								$this->m_dbConn->update($updateQuery);
					
								echo $errormsg = "Tenant Data updated successfully.";
								$this->obj_utility->logGenerator($errorfile, $rowCount, $errormsg, "I");
					
							} 
							else
							{
						  	 $sql_insert = "insert into `tenant_module`(`ledger_id`,`security_id`,`unit_id`,`wing_id`,`tenant_name`,`start_date`,`end_date`,`security_deposit`,`annual_rent`, `mode_of_payment`,`mobile_no`,`mobile_1`,`email`,`email_1`,`contract_value`,`license_no`,`license_authority`,`emirate_no`,`members`,`property_type`) values ('".$Ledgerid['LedgerID']."','".$Ledgerid['SecurityID']."','".$UnitnoCol."','".$wingid."','".$Tenantfname." ".$Tenantmname." ".$Tenantlname."','". getDBFormatDate($startdate)."','". getDBFormatDate($enddate)."', '".$securitydeposit."', '".$Annualrent."' , '".$modeofpayment."', '".$Mobile."', '".$mobile1."','".$email."', '".$email1."','".$contractvalue."','".$licenceno."','".$licenceauthority."','".$emeraitid."','".$noofoccupants."','".$propertyUsage."')";
							
							$sql_insert_done = $this->m_dbConn->insert($sql_insert);

							// echo $sql_insert_done;
							// die();
							// echo $tenantidModule = "select tenant_id from tenant_module where `unit_id`='".$UnitnoCol."'";
							// $id = $this->m_dbConn->select($tenantidModule);
							// //$ashwini = $id['tenant_id'];

							// print_r($id);
							//die();
							
								$sql_insert1 = "insert into `tenant_member`(`tenant_id`,`mem_name`,`relation`,`mem_dob`,`email`,`contact_no`) values ('".$sql_insert_done."','".$Tenantfname." " .$Tenantmname." ".$Tenantlname."','".self."','". getDBFormatDate($dob)."','".$email."','".$Mobile."')";

								$sql_insert_member = $this->m_dbConn->insert($sql_insert1);
							//die();

							if($sql_insert_member <> "" && $sql_insert_done <> "")
							{
								$errormsg = "Tenant Data inserted successfully.";
								$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"I");
                            
								// echo"<pre>";
								// print_r($incert);
								// echo "</pre>";
							}
						
						  }
						}
						
					 
				}
				
			}
		}
		
	}
}
