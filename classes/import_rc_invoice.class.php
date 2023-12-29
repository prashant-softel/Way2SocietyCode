<?php
include_once("include/dbop.class.php");
include_once("utility.class.php");
include_once("dbconst.class.php");
include_once("changelog.class.php");//Pending - Verify
include_once("genbill.class.php");
include_once("include/fetch_data.php");

class invoice_rc_import 
{
	public $m_dbConn;
	public $obj_utility;
	public $errorfile_name;
	public $errorLog;
	public $actionPage = '../import_rc_invoice.php';
	public $changeLog;
	public $obj_genbill;
	public $obj_fetch;
	private $InvoiceNumberArray = array();

	function __construct($dbConnRoot, $dbConn)
	{
		$this->m_dbConn = $dbConn;
		$this->obj_utility = new utility($this->m_dbConn);
		$this->m_objLog = new changeLog($this->m_dbConn);
		$this->obj_genbill = new genbill($this->m_dbConn);

		$this->obj_fetch = new FetchData($this->m_dbConn);

		$a = $this->obj_fetch->GetSocietyDetails($_SESSION['society_id']);

	}
	

	public function UploadData($fileName,$fileData)
	{
        // echo "data";
       
		$Foldername = $this->obj_fetch->objSocietyDetails->sSocietyCode;
        
		if (!file_exists('../logs/import_log/'.$Foldername)) 
		{
			mkdir('../logs/import_log/'.$Foldername, 0777, true);
		}

		$this->errorfile_name = '../logs/import_log/'.$Foldername.'/import_invoice_errorlog_'.date("d.m.Y").'_'.rand().'.html';
		$this->errorLog = $this->errorfile_name;
		$errorfile = fopen($this->errorfile_name, "a");
		$errormsg="[Importing Invoice Data]";
		$isImportSuccess = false;
		$this->obj_utility->logGenerator($errorfile,'start',$errormsg);
		
		$invoice_no="Select Inv_Number from sale_invoice";
		$InvoiceResult=$this->m_dbConn->select($invoice_no);
    
		// $this->InvoiceNumberArray = array_column($InvoiceResult, 'Inv_Number');
		$Success = 0; 
		foreach($fileData as $row)
			{
				if($row <> '')
				{
						$rowCount++;
                       
						if($rowCount == 1)//Header
						{
							// $ledger_id = array();
							$UnitNoIndex = array_search(UnitNo,$row,true);
							$InvoiceDateIndex = array_search(InvoiceDate,$row,true);
							$InvoiceNoIndex=array_search(InvoiceNo,$row,true);
                                                        $tenant_nameIndex = array_search(TenantName,$row,true);
							$invoice_rentIndex = array_search(RentAmount,$row,true);
							$invoice_sdIndex = array_search(SecurityDeposit,$row,true);
                                                      
							// $j = 0;
							// $ledger_no=0;
							// $cnt = 0;
							//LedgerNames - Dynamic
							// for($i=3;$i<sizeof($row)-3;$i++)
							// {
							// 	$ledger[$j] = $row[$i];
							// 	$ledger_no[$j]=array_search($ledger[$j],$row,true);
							// 	$query_id="Select id from ledger where ledger_name='".$ledgername."'";
							// 	//pending optimization
							// 	$LedgerResult=$this->m_dbConn->select($query_id);
							// 	$ledger_id[$cnt] = $LedgerResult[0]['id'];
							// 	echo "Ledger ID".$ledger_id[$cnt];
							// 	$cnt++;
							// 	$j++;
							// }
                            
							// $CGSTIndex=array_search(CGST,$row,true);
							// $SGSTIndex=array_search(SGST,$row,true);
							$NoteIndex = array_search(Note,$row,true);
							
							if(!isset($UnitNoIndex) || !isset($InvoiceDateIndex) || !isset($InvoiceNoIndex) || !isset($NoteIndex))
							{
								$result = '<p>Required Column Names Not Found. Cant Proceed Further......</p>';
								$errormsg=" Column names does not match";
								$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"E");
								return $result;
								
							}
						}
					 	else	//from line_no 2 onwards
					   	{ 	
							$tenantName = trim($row[$tenant_nameIndex]); 
							$Unit_No = $row[$UnitNoIndex];
							if(!empty($Unit_No))
							{
								$unit_no = $Unit_No;
							}
							else
							{
								$errormsg .= "<br>Unit_No  Not provided";
							}
							$Amount = $row[$invoice_rentIndex];
							$Invoice_No = $row[$InvoiceNoIndex];
                                                        $ledgername_rent = $unit_no."-".$tenantName."-Rent";
                                                        $ledgername_tenant = $unit_no."-".$tenantName;
							$ledgername_sd = $unit_no."-".$tenantName."-Security deposit";
							//echo "amount." .$Amount;
							$sd = $row[$invoice_sdIndex];
							echo "sd." .$sd;
							$sdTotal=$sd;
							$rentTotal=$Amount;
							// $totalamount = $sdTotal + $rentTotal;

							$j = 0;
							$ledger_no=0;
							//$leger_array = new ();
							$leger_array = array();
							$ammount_array = array();
							$ledgerTotal=0;	
							$cnt = 0;
							//LedgerNames - Dynamic 
							//get all the ledgers in array 
							for($i=3;$i<sizeof($row)-3;$i++)
							{
								$ledger[$j] = $row[$i];
								//get the amount using amountindex and sdindex
								$ledger_no[$j]=array_search($ledger[$j],$row,true);
								echo $query = "SELECT id from ledger where ledger_name = '".$ledgername_tenant."'";//
								$res = $this->m_dbConn->select($query);
								$ledgerid= $res[0]['id'];
								echo "ledger1". $ledgerid;

	
							        $query1 = "SELECT id from ledger where ledger_name = '".$ledgername_rent."'";
								$res1 = $this->m_dbConn->select($query1);
								$ledgeridrent= $res1[0]['id'];
								echo "ledger2". $ledgeridrent;
								echo "total". $sdTotal;
								if($sdTotal <> '')
								{
									
											$query1 = "SELECT id from ledger where ledger_name = '".$ledgername_sd."'";
											$res1 = $this->m_dbConn->select($query1);
											$ledgeridsd= $res1[0]['id'];
											echo "ledger3". $ledgeridsd;

											//echo "Ledger ID".$ledger_id[$cnt];
							    }
								array_push($leger_array,$ledgerid,$ledgeridrent,$ledgeridsd);
								array_push($ammount_array,$ledgerTotal,$rentTotal,$sdTotal);
								$cnt++;
								$j++;
                           

						    }
							
							for($i=5;$i<sizeof($row)-5;$i++)
							{
								$ammount_array[$j] = $row[$i];
								$j++;
							}
							print_r($ammount_array);		
							$associativeArray = array();
							$Counter = 0;
							for($i = 0 ; $i < count($leger_array); $i++)
							{
                                                                 //echo "debug";
                                                                //print_r($ammount_array);
                                
								if($ammount_array[$i] <> '' && $ammount_array[$i] <> 0)
								{
                                                                    // echo "debug1";
                                                                   //echo "lid:".$ledgerTotal;
									 $ledgerTotal = $ledgerTotal + $ammount_array[$i];
									if(!empty($leger_array[$i]))
									{
                                       

										$associativeArray[$Counter]['Head'] = $leger_array[$i];
										$associativeArray[$Counter]['Amt'] = $ammount_array[$i];
										$Counter++;
                                        
									}
									else
									{
										$errormsg .= "<br>Ledger Name ".$ledger[$i]." Not Exits For Row".$rowCount;
									}
								}
									
							}
							// print_r($leger_array);
							// print_r($ammount_array);
                            // echo "ledger". $ledgeridrent;
                           

							$errormsg = '';
                        	
								if(in_array($Invoice_No,$this->InvoiceNumberArray))
								{
									$Warningmsg = "<br>Invoice Number Exists";
									$this->obj_utility->logGenerator($errorfile,$rowCount,$Warningmsg,"W");
								}
							
							
							$Invoice_Date = $row[$InvoiceDateIndex];
							$InvoiceDate=$Invoice_Date;
							$Note=$row[$NoteIndex];
							
							
							
										
							
							
							
							var_dump($associativeArray);
							// die();
							
							// if($ledgerTotal == 0)
							// {
							// 	$errormsg .= "<br>Amount is not set for any ledger";		
							// }
							
							
							$FinalValue = array();
							// Associative Array of Ledger Total, CGST , SGST and Total
							$FinalValue['Subtotal'] = $ledgerTotal;
							$finalTotal=$ledgerTotal;
							$RoundOffAmtForfinalTotal = $this->obj_utility->getRoundValue2($finalTotal);
							$RoundOffAmt = $RoundOffAmtForfinalTotal - $ledgerTotal;
							$FinalValue['RoundOffAmt'] = $RoundOffAmt;
							$FinalValue['Total'] = $RoundOffAmtForfinalTotal;
                                                        $finalasset=0;
						
							if(!empty($errormsg))
							{
                              
                               
								$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"E");
                                
                                
								// continue;
                                                               // exit;
							}
                           
                            
							if((isset($ledgerid) && isset($InvoiceDate) && isset($Invoice_No) && isset($FinalValue)))
							{	
                                                                // echo "id.".$ledgerid;
                                
							       	//echo 'amount:';
								print_r($Finalsd);
								
								//$Result1 = $this->obj_genbill->SetSalesInvoiceVoucher_WithImport($ledgerid,$InvoiceDate,$associativeArray,$Note,$Invoice_No,0,true,$FinalValue,false,$ledgeridrent);
								$Result1 = $this->obj_genbill->SetSalesInvoiceVoucher_WithImport($ledgerid,$InvoiceDate,$associativeArray,$Note,$Invoice_No,0,true,$FinalValue,false);
							    
								
                               
                                                                //print_r($Result);
                                
								if($Result == 1 || $Result1 == 1)//Successful
								{   //Value In Errormsg
								    
									$Success++;
									$errormsg ="Invoice Data Imported : Unit ID : &lt;".$ledgerid." &gt; Date : &lt;".$InvoiceDate." &gt; Invoice No : &lt;".$Invoice_No." &gt; <br>";
									
									$erormsg=0;
									for($i = 0 ; $i < count($ledger_id); $i++)
									{
										if($ledger_amt[$i] <> '' && $ledger_amt[$i] <> 0)
										{
											$errormsg .= $ledger[$i];
											$errormsg .= " : ";
											$errormsg .= $ledger_amt[$i];
											$errormsg .= " &nbsp;&nbsp;&nbsp; ";
										}
									}
									$errormsg =  $errormsg."<br>CGST : '".$CGST."' <br> SGST : '".$SGST."' <br> Note : '".$Note."'";
									
									$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"I");
								}
							}
						}
					}
				}
				$totalCount = $rowCount - 1;
				$Failed = $totalCount - $Success;
				$errormsg = "<br>Total Number of Rows : ".$totalCount;
				$errormsg .= "<br>Number of Rows Successfully Imported :".$Success;
				$errormsg .= "<br>Number of Rows Not Imported : ".$Failed;
				$this->obj_utility->logGenerator($errorfile,$rowCount,$errormsg,"I");
			}
	
	public function isNumeric($Numeric)
	{
		$bResult = true;
		 if (!preg_match('/^[0-9]*$/', $Numeric))
		
		{
			$bResult = false;
		}
		return $bResult;
	}
	
	public function validateDate($InvoiceDate)
	{
	if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$InvoiceDate))
	{
    return true;
	}
	else 
	{
    return false;
	}
	}
}//class
						?>