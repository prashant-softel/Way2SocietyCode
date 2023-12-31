<?php
include_once("dbconst.class.php");
include_once("utility.class.php");

class CAdminPanel
{
	public $m_dbConn;
	public $obj_utility;
	public $m_dbConnRoot;
	
	function __construct($dbConn,$dbConnRoot)
	{
		$this->m_dbConn = $dbConn;
		$this->m_dbConnRoot = $dbConnRoot;
		$this->obj_utility = new utility($this->m_dbConn);
		
	}

	public function GetSummary($GroupID)
	{
		if($GroupID==LIABILITY)
		{
			$sqlQuery = "SELECT DISTINCT liability.LedgerID, liability.CategoryID, liability.SubCategoryID FROM liabilityregister as liability JOIN ledger as led ON led.id=liability.LedgerID where led.society_id=".$_SESSION['society_id']." GROUP BY liability.SubCategoryID";
		}
		if($GroupID==ASSET)
		{
			$sqlQuery = "SELECT DISTINCT asset.LedgerID, asset.CategoryID, asset.SubCategoryID FROM assetregister as asset JOIN ledger as led ON led.id=asset.LedgerID where led.society_id=".$_SESSION['society_id']." and ( asset.SubCategoryID != '".BANK_ACCOUNT."' and asset.SubCategoryID != '".CASH_ACCOUNT."')  GROUP BY asset.SubCategoryID";
		}
		if($GroupID==INCOME)
		{
			$sqlQuery = "SELECT DISTINCT income.LedgerID,income.CategoryID, income.SubCategoryID, SUM( income.Debit ) AS debit, SUM( income.Credit ) AS credit FROM incomeregister as income JOIN ledger as led ON led.id =income.LedgerID where led.society_id=".$_SESSION['society_id']."  ";
			
			if($_SESSION['default_year_start_date'] <> 0  && $_SESSION['default_year_end_date'] <> 0)
			{
				$sqlQuery .= "  and Date BETWEEN '".getDBFormatDate($_SESSION['default_year_start_date'])."' AND '".getDBFormatDate($_SESSION['default_year_end_date'])."'";					
			}
			$sqlQuery .= " GROUP BY income.LedgerID ";
		}
		if($GroupID==EXPENSE)
		{
			$sqlQuery = "SELECT  DISTINCT expense.LedgerID,expense.CategoryID, expense.SubCategoryID, expense.Debit AS debit,  expense.Credit AS credit, expense.VoucherID, led.ledger_name FROM expenseregister as expense JOIN ledger as led ON led.id =expense.LedgerID where led.society_id='".$_SESSION['society_id']."' ";
			
			if($_SESSION['default_year_start_date'] <> 0  && $_SESSION['default_year_end_date'] <> 0)
			{
				$sqlQuery .= "  and Date BETWEEN '".getDBFormatDate($_SESSION['default_year_start_date'])."' AND '".getDBFormatDate($_SESSION['default_year_end_date'])."'";					
			}
			$sqlQuery .= " GROUP BY expense.SubCategoryID";
		}
		
		$retData = $this->m_dbConn->select($sqlQuery);
		//return $retData;
		return $this->GetCategoryTransactions($retData , $GroupID);
	}

	public function GetCategoryTransactions($Data , $GroupID)
	{
		
		for($i = 0; $i < sizeof($Data); $i++)
		{
			if($GroupID == LIABILITY)
			{
				$sqlQuery = "SELECT liability.CategoryID, liability.SubCategoryID, SUM( liability.Debit ) AS debit, SUM( liability.Credit ) AS credit FROM `liabilityregister` as liability  where liability.SubCategoryID = '".$Data[$i]['SubCategoryID']."'";
					
				if($_SESSION['default_year_start_date'] <> 0  && $_SESSION['default_year_end_date'] <> 0)
				{
					$sqlQuery .= "  and liability.Date BETWEEN '".getDBFormatDate($_SESSION['default_year_start_date'])."' AND '".getDBFormatDate($_SESSION['default_year_end_date'])."'";					
				}
				$sqlQuery .= " GROUP BY liability.SubCategoryID ";
							
			}
			else if($GroupID == ASSET)
			{
				$sqlQuery = "SELECT assettbl.CategoryID, assettbl.SubCategoryID, SUM( assettbl.Debit ) AS debit, SUM( assettbl.Credit ) AS credit FROM `assetregister` as assettbl  where assettbl.SubCategoryID = '".$Data[$i]['SubCategoryID']."'";
					
				if($_SESSION['default_year_start_date'] <> 0  && $_SESSION['default_year_end_date'] <> 0)
				{
					
					$sqlQuery .= "  and assettbl.Date BETWEEN '".getDBFormatDate($_SESSION['default_year_start_date'])."' AND '".getDBFormatDate($_SESSION['default_year_end_date'])."'";					
				}
				$sqlQuery .= " GROUP BY assettbl.SubCategoryID ";
				
			}
			$retData = $this->m_dbConn->select($sqlQuery);
			
			if($_SESSION['default_year_start_date'] <> 0  && $_SESSION['default_year_end_date'] <> 0)
			{
				$res = $this->obj_utility->getOpeningBalanceOfCategory($Data[$i]['SubCategoryID'] , getDBFormatDate($_SESSION['default_year_start_date']));
				if($res <> "")
				{
					$Data[$i]['debit'] = $retData[0]['debit'] + $res['Debit'];
					$Data[$i]['credit'] = $retData[0]['credit'] + $res['Credit'];
						
				}		
			}
		}
		
		return $Data;
	}










	/*	
	public function GetAssetSummary($NumberOfRecordsRequired)
	{
		$arBankNameAndBalance = "";
		$sqlSelect = "";
//$sqlQuery = "SELECT DISTINCT asset.LedgerID, asset.CategoryID, asset.SubCategoryID, SUM( asset.Debit ) AS debit, SUM( asset.Credit ) AS credit FROM assetregister as asset JOIN ledger as led ON led.id=asset.LedgerID where led.society_id=".$_SESSION['society_id']." GROUP BY asset.LedgerID";
		$sqlQuery = "SELECT DISTINCT asset.LedgerID, asset.CategoryID, asset.SubCategoryID, SUM( asset.Debit ) AS debit, SUM( asset.Credit ) AS credit FROM assetregister as asset JOIN ledger as led ON led.id=asset.LedgerID where led.society_id=".$_SESSION['society_id']." GROUP BY asset.SubCategoryID";
		$retData = $this->m_dbConn->select($sqlQuery);
		//print_r($retData);
		
		return $retData;
	}

	public function GetLiabilitySummary($NumberOfRecordsRequired)
	{
		$arBankNameAndBalance = "";
		$sqlSelect = "";
		//$sqlQuery = "SELECT DISTINCT liability.LedgerID, liability.CategoryID, liability.SubCategoryID, SUM( liability.Debit ) AS debit, SUM( liability.Credit ) AS credit FROM liabilityregister as liability JOIN ledger as led ON led.id=liability.LedgerID where led.society_id=".$_SESSION['society_id']." GROUP BY liability.LedgerID";
		$sqlQuery = "SELECT DISTINCT liability.LedgerID, liability.CategoryID, liability.SubCategoryID, SUM( liability.Debit ) AS debit, SUM( liability.Credit ) AS credit FROM liabilityregister as liability JOIN ledger as led ON led.id=liability.LedgerID where led.society_id=".$_SESSION['society_id']." GROUP BY liability.SubCategoryID";
		$retData = $this->m_dbConn->select($sqlQuery);
		//print_r($retData);
		
		return $retData;
	}

	

	public function GetExpenseSummary($NumberOfRecordsRequired)
	{
		$arBankNameAndBalance = "";
		$sqlSelect = "";
		$sqlQuery = "SELECT DISTINCT expense.LedgerID, SUM( expense.Debit ) AS debit, SUM( expense.Credit ) AS credit, expense.ExpenseHead AS ExpenseHead FROM expenseregister as expense JOIN ledger as led ON led.id =expense.LedgerID where led.society_id=".$_SESSION['society_id']." GROUP BY expense.LedgerID";
		$retData = $this->m_dbConn->select($sqlQuery);
		print_r($retData);
		
		return $retData;
	}
	*/
	public function GetIncomeSummaryDetailed($NumberOfRecordsRequired)
	{
		$arBankNameAndBalance = "";
		$sqlSelect = "";
		$sqlQuery = "SELECT DISTINCT income.LedgerID, SUM( income.Debit ) AS debit, SUM( income.Credit ) AS credit FROM incomeregister as income JOIN ledger as led ON led.id =income.LedgerID where led.society_id=".$_SESSION['society_id']." ";
		if($_SESSION['default_year_start_date'] <> 0  && $_SESSION['default_year_end_date'] <> 0)
		{
			
			$sqlQuery .= "  and income.Date BETWEEN '".getDBFormatDate($_SESSION['default_year_start_date'])."' AND '".getDBFormatDate($_SESSION['default_year_end_date'])."'";					
		}
		$sqlQuery .= "  GROUP BY income.LedgerID";
		
		$retData = $this->m_dbConn->select($sqlQuery);
		//print_r($retData);
		
		return $retData;
	}
	public function GetExpenseSummaryDetailed($NumberOfRecordsRequired)
	{
		$arBankNameAndBalance = "";
		$sqlSelect = "";
		//$sqlQuery = "SELECT  expense.LedgerID, sum(expense.Debit) AS debit,  sum(expense.Credit) AS credit, expense.VoucherID FROM expenseregister as expense JOIN ledger as led ON led.id =expense.LedgerID where led.society_id=".$_SESSION['society_id']." GROUP BY expense.LedgerID";
		$sqlQuery = "SELECT  expense.LedgerID,  sum(expense.Debit) AS debit,  sum(expense.Credit) AS credit, expense.VoucherID, led.ledger_name FROM expenseregister as expense JOIN ledger as led ON led.id =expense.LedgerID where led.society_id='".$_SESSION['society_id']."'";
		if($_SESSION['default_year_start_date'] <> 0  && $_SESSION['default_year_end_date'] <> 0)
		{
			
			$sqlQuery .= "  and expense.Date BETWEEN '".getDBFormatDate($_SESSION['default_year_start_date'])."' AND '".getDBFormatDate($_SESSION['default_year_end_date'])."'";					
		}
		$sqlQuery .= "  GROUP BY expense.LedgerID";
		$retData = $this->m_dbConn->select($sqlQuery);
	
		
		
		return $retData;
	}

	public function GetBankAccountAndBalance($NumberOfRecordsRequired = 0)
	{
		$arBankNameAndBalance = "";
		$sqlSelect = "";
		//$sqlQuery = "SELECT DISTINCT LedgerID, SUM( ReceivedAmount ) AS receipts, SUM( PaidAmount ) AS payments FROM bankregister GROUP BY LedgerID";
		$sqlQuery = "SELECT DISTINCT bk.LedgerID, SUM( bk.ReceivedAmount ) AS receipts, SUM( bk.PaidAmount ) AS payments FROM bankregister as bk JOIN ledger as led ON led.id = bk.LedgerID where led.society_id='".DEFAULT_SOCIETY."' ";
		//echo $sqlQuery;
		if($_SESSION['default_year_start_date'] <> 0  && $_SESSION['default_year_end_date'] <> 0)
		{
			
			$sqlQuery .= "  and bk.Date <= '".getDBFormatDate($_SESSION['default_year_end_date'])."'";					
		}
		if($_SESSION["login_id"] == "2077")
		{
			include_once("utility.class.php");
			$obj_utility = new utility($this->m_dbConn);
			$resBank = $obj_utility->GetPaymentGatewayBankID();
			//echo "resBank:".$resBank;
			if(isset($resBank))
			{
				$sqlQuery .= " and led.id='".$resBank."'";
			}
		}
		$sqlQuery .= "  GROUP BY bk.LedgerID"; 
		
		if($NumberOfRecordsRequired > 0)
		{
			$sqlQuery .= '  LIMIT 0, ' . $NumberOfRecordsRequired;
		}
		
		$retData = $this->m_dbConn->select($sqlQuery);
		//print_r($retData);
		
		return $retData;
	}
	//  ---------------------------- Expenses report Fourth card ----------------------------------------------------///
	public function GetIncomeSummaryDetailedDashboard()
	{
		$arBankNameAndBalance = "";
		$sqlSelect = "";
		$sqlQuery = "SELECT  income.LedgerID,  sum(income.Debit) AS debit,  sum(income.Credit) AS credit,sum(income.Credit)- sum(income.Debit) as TotalAmt, income.VoucherID, led.ledger_name FROM incomeregister as income JOIN ledger as led ON led.id =income.LedgerID where led.society_id='".$_SESSION['society_id']."'";
		if($_SESSION['default_year_start_date'] <> 0  && $_SESSION['default_year_end_date'] <> 0)
		{
			
			$sqlQuery .= "  and income.Date BETWEEN '".getDBFormatDate($_SESSION['default_year_start_date'])."' AND '".getDBFormatDate($_SESSION['default_year_end_date'])."'";					
		}
		$sqlQuery .= "  GROUP BY income.LedgerID";
		//echo "Sql".$sqlQuery;
		
		$retData = $this->m_dbConn->select($sqlQuery);
		//echo "First";
		//print_r($retData);
		$finalArray = array();
		for($i=0; $i < sizeof($retData) ; $i++)
		{
			if($retData[$i]['TotalAmt'] > 0)
			{
				$DebitAmount=$retData[$i]['TotalAmt'];
				$LedgerName=$retData[$i]['ledger_name'];	
			
				$parentArray = array('DebitAmount' => $DebitAmount, 'LedgerName' => $LedgerName);
				array_push($finalArray , $parentArray);	
			}
				
		}
		
		
	//echo "<pre>";
	//print_r($finalArray);
	//echo "</pre>";
		return $finalArray;
	}
	public function GetCategoryNameFromID($CategoryID)
	{
		$sqlQuery = "SELECT category_name FROM account_category where category_id=".$CategoryID;
		//echo $sqlQuery;
		//echo "<br>";
		$retData = $this->m_dbConn->select($sqlQuery);
		
		return $retData[0]["category_name"];
	}

	public function GetLedgerNameFromID($LedgerID)
	{

		$sqlQuery = "SELECT ledger_name FROM ledger where id=".$LedgerID;
		//echo $sqlQuery;
		//echo "<br>";
		$retData = $this->m_dbConn->select($sqlQuery);
		
		return $retData[0]["ledger_name"];
	}
	
	public function GetTotalIncome()
	{
		$sqlQuery = "SELECT SUM( inc.Debit ) AS payments, SUM( inc.Credit ) AS receipts,monthname( inc.date ) AS date FROM incomeregister as inc JOIN ledger as led ON led.id = inc.LedgerID where led.society_id=".$_SESSION['society_id']." ";
		if($_SESSION['default_year_start_date'] <> 0  && $_SESSION['default_year_end_date'] <> 0)
		{
			
			$sqlQuery .= "  and inc.Date BETWEEN '".getDBFormatDate($_SESSION['default_year_start_date'])."' AND '".getDBFormatDate($_SESSION['default_year_end_date'])."'";					
		}
		$sqlQuery .= "  group by MONTH(inc.date) order by MONTH(inc.date) desc";
		
		//echo $sqlQuery;
		//echo "<br>";
		$retData = $this->m_dbConn->select($sqlQuery);
		
		return $retData;
	}
	
	public function GetTotalExpenses()
	{
		$sqlQuery = "SELECT SUM( exp.Debit ) AS receipts, SUM( exp.Credit ) AS payments,monthname( exp.Date ) AS date FROM expenseregister as exp JOIN ledger as led ON led.id = exp.LedgerID where led.society_id=".$_SESSION['society_id']. " ";
		if($_SESSION['default_year_start_date'] <> 0  && $_SESSION['default_year_end_date'] <> 0)
		{
			
			$sqlQuery .= "  and exp.Date BETWEEN '".getDBFormatDate($_SESSION['default_year_start_date'])."' AND '".getDBFormatDate($_SESSION['default_year_end_date'])."'";					
		}
		$sqlQuery .= "  group by MONTH(exp.date) order by MONTH(exp.date) desc";
		
		//echo $sqlQuery;
		//echo "<br>";
		$retData = $this->m_dbConn->select($sqlQuery);
		
		return $retData;
	}
	
	public function GetTotalAssets()
	{
		//$sqlQuery = "SELECT SUM( asset.Debit ) AS receipts, SUM( asset.Credit ) AS payments FROM assetregister as asset JOIN ledger as led ON led.id = asset.LedgerID where led.society_id=".$_SESSION['society_id'];
		$sqlQuery = "SELECT SUM( asset.Debit ) AS receipts, SUM( asset.Credit ) AS payments,monthname( asset.date ) AS date FROM assetregister as asset JOIN ledger as led ON led.id = asset.LedgerID where led.society_id=".$_SESSION['society_id']." ";
		if($_SESSION['default_year_start_date'] <> 0  && $_SESSION['default_year_end_date'] <> 0)
		{
			
			$sqlQuery .= "  and asset.Date BETWEEN '".getDBFormatDate($_SESSION['default_year_start_date'])."' AND '".getDBFormatDate($_SESSION['default_year_end_date'])."'";					
		}
		$sqlQuery .= "  group by YEAR(asset.date),MONTH(asset.date) order by asset.date desc";
		
		$retData = $this->m_dbConn->select($sqlQuery);
		//print_r($retData);
		return $retData;
	}
	
	public function GetTotalLiabilities()
	{
		//$sqlQuery = "SELECT SUM( lib.Debit ) AS receipts, SUM( lib.Credit ) AS payments FROM liabilityregister as lib JOIN ledger as led ON led.id = lib.LedgerID where led.society_id=".$_SESSION['society_id'];
		$sqlQuery = "SELECT SUM( lib.Debit ) AS receipts, SUM( lib.Credit ) AS payments,monthname( lib.date ) AS date  FROM liabilityregister as lib JOIN ledger as led ON led.id = lib.LedgerID where led.society_id=".$_SESSION['society_id']."  ";
		if($_SESSION['default_year_start_date'] <> 0  && $_SESSION['default_year_end_date'] <> 0)
		{
			
			$sqlQuery .= "  and lib.Date BETWEEN '".getDBFormatDate($_SESSION['default_year_start_date'])."' AND '".getDBFormatDate($_SESSION['default_year_end_date'])."'";					
		}
		$sqlQuery .= "  group by YEAR(lib.date),MONTH(lib.date) order by lib.date desc";
		
		//echo $sqlQuery;
		//echo "<br>";
		//echo date("M", "2015-05-02");
		$retData = $this->m_dbConn->select($sqlQuery);
		
		return $retData;
	}
	
	public function GetTotalLiabilitiesOrAssets($groupID)
	{
		if($groupID == LIABILITY)
		{
			$sqlQuery = "SELECT SUM( lib.Debit ) AS receipts, SUM( lib.Credit ) AS payments,monthname( lib.date ) AS date,(SUM( lib.Credit ) - SUM( lib.Debit )) as BalAmount FROM liabilityregister as lib JOIN ledger as led ON led.id = lib.LedgerID where led.society_id=".$_SESSION['society_id']."  ";
			if($_SESSION['default_year_start_date'] <> 0  && $_SESSION['default_year_end_date'] <> 0)
			{
				
				$sqlQuery .= "  and lib.Date BETWEEN '".getDBFormatDate($_SESSION['default_year_start_date'])."' AND '".getDBFormatDate($_SESSION['default_year_end_date'])."'";					
			}
			$sqlQuery .= "  group by YEAR(lib.date),MONTH(lib.date) order by lib.date desc";		
		}
		else if($groupID == ASSET)
		{
			$sqlQuery = "SELECT SUM( asset.Debit ) AS receipts, SUM( asset.Credit ) AS payments,monthname( asset.date ) AS date,(SUM( asset.Debit ) - SUM( asset.Credit )) as BalAmount FROM assetregister as asset JOIN ledger as led ON led.id = asset.LedgerID where led.society_id=".$_SESSION['society_id']." ";
			if($_SESSION['default_year_start_date'] <> 0  && $_SESSION['default_year_end_date'] <> 0)
			{
				
				$sqlQuery .= "  and asset.Date BETWEEN '".getDBFormatDate($_SESSION['default_year_start_date'])."' AND '".getDBFormatDate($_SESSION['default_year_end_date'])."'";					
			}
			$sqlQuery .= "  group by YEAR(asset.date),MONTH(asset.date) order by asset.date desc";		
			
		}
		$retData = $this->m_dbConn->select($sqlQuery);
		if(count($retData) > 0)
		{
			foreach($retData as $key => $value)
			{
				if($value['date'] == 'April')
			   {
					
					if($groupID == LIABILITY)
					{
						$res = $this->obj_utility->getOpeningBalanceOfCategory(LIABILITY, getDBFormatDate($_SESSION['default_year_start_date']) ,true);
						if($res <> "")
						{
							if($res['OpeningType'] == TRANSACTION_CREDIT)
							{
								$value['BalAmount'] = $value['BalAmount'] + $res['Total'];
							}
							else
							{
								$value['BalAmount'] = $value['BalAmount'] - $res['Total'];
							}
						}
					}
					else if($groupID == ASSET)
					{
						$res = $this->obj_utility->getOpeningBalanceOfCategory(ASSET, getDBFormatDate($_SESSION['default_year_start_date']) ,true);
						if($res <> "")
						{
							if($res['OpeningType'] == TRANSACTION_DEBIT)
							{
								$value['BalAmount'] = $value['BalAmount'] + $res['Total'];
							}
							else
							{
								$value['BalAmount'] = $value['BalAmount'] - $res['Total'];
							}	
						}
						
					}
							
					
			   }		
			}
		}
		else
		{
			$retData = array();
			$BalAmount = 0;
			if($groupID == LIABILITY)
			{
				$res = $this->obj_utility->getOpeningBalanceOfCategory(LIABILITY, getDBFormatDate($_SESSION['default_year_start_date']) ,true);
				
			}
			else if($groupID == ASSET)
			{
				$res = $this->obj_utility->getOpeningBalanceOfCategory(ASSET, getDBFormatDate($_SESSION['default_year_start_date']) ,true);
				
			}
			if($res <> "")
			{
				$BalAmount = $res['Total'];
			}
			array_push($retData , array('date' => 'April' ,'BalAmount' => $BalAmount ));		
			
		}
		return $retData;
			
		
	}
	
	public function GetTotalMemberDues()
	{
		//$sqlQuery = "SELECT SUM( Debit ) AS payments, SUM( Credit ) AS receipts FROM assetregister where SubCategoryID=2";
		$sqlQuery = "SELECT SUM( asset.Debit ) AS receipts, SUM( asset.Credit ) AS payments,monthname( asset.date) as date,(SUM( asset.Debit ) - SUM( asset.Credit )) as BalAmount FROM assetregister as asset  JOIN ledger as led ON led.id = asset.LedgerID where led.society_id = '" . DEFAULT_SOCIETY . "' and  asset.SubCategoryID='".$_SESSION['default_due_from_member']."' ";
		if($_SESSION['default_year_start_date'] <> 0  && $_SESSION['default_year_end_date'] <> 0)
		{
			
			$sqlQuery .= "  and asset.Date BETWEEN '".getDBFormatDate($_SESSION['default_year_start_date'])."' AND '".getDBFormatDate($_SESSION['default_year_end_date'])."'";					
		}
		$sqlQuery .= "  group by YEAR(asset.date),MONTH(asset.date) order by asset.date desc";
		
		//echo "<br>";
		$retData = $this->m_dbConn->select($sqlQuery);
		if(count($retData) > 0)
		{
			foreach($retData as $key => $value)
			{
			   if($value['date'] == 'April')
			   {
					$res = $this->obj_utility->getOpeningBalanceOfCategory(DUE_FROM_MEMBERS, getDBFormatDate($_SESSION['default_year_start_date']));
					if($res <> "")
					{
						if($res['OpeningType'] == TRANSACTION_DEBIT)
						{
							$value['BalAmount'] = $value['BalAmount'] + $res['Total'];
						}
						else
						{
							$value['BalAmount'] = $value['BalAmount'] - $res['Total'];
						}	
					}
				}		
			}
		}
		else
		{
			$retData = array();
			$BalAmount = 0;
			$res = $this->obj_utility->getOpeningBalanceOfCategory(DUE_FROM_MEMBERS, getDBFormatDate($_SESSION['default_year_start_date']));
			
			if($res <> "")
			{
				$BalAmount = $res['Total'];
			}
			array_push($retData , array('date' => 'April' ,'BalAmount' => $BalAmount ));		
			
		}
		
		return $retData;
	}
	
	public function GetLastBillGenerated()
	{
		$sqlQuery = "SELECT sum(bill.TotalBillPayable) as amount,pd.Type FROM billdetails as bill JOIN period as pd on bill.PeriodID = pd.ID JOIN unit as unittbl on bill.UnitID = unittbl.unit_id where unittbl.society_id = '" . $_SESSION['society_id'] . "' ";
		if($_SESSION['default_year_start_date'] <> 0  && $_SESSION['default_year_end_date'] <> 0)
		{
			
			$sqlQuery .= "  and pd.YearID = '".$_SESSION['default_year']."' ";					
		}
		$sqlQuery .= "  group by pd.Type order by pd.ID desc";
		
		//echo $sqlQuery;
		//echo "<br>";
		$retData = $this->m_dbConn->select($sqlQuery);
		//print_r($retData);
		return $retData;
	}
	
	public function GetNEFTDetails()
	{
		$retAry = array();
		$sqlQuery = "select	count(*) as cnt from neft";
		$result = $this->m_dbConn->select($sqlQuery);
		$retAry['total'] = $result[0]['cnt'];
		
		$sqlQuery = "select	count(*) as cnt from neft where approved = 0";
		$result = $this->m_dbConn->select($sqlQuery);
		$retAry['pending'] = $result[0]['cnt'];
		
		return $retAry;
	}
	
	
	public function GetNoticeDetails()
	{
		$todayDate=date('Y-m-d');
		if($_SESSION['role'] && ($_SESSION['role']==ROLE_ADMIN || $_SESSION['role']==ROLE_SUPER_ADMIN || $_SESSION['role']==ROLE_ADMIN_MEMBER ||  $_SESSION['role']==ROLE_MANAGER))
		{
			$sql="select DISTINCT noticetbl.id, noticetbl.* FROM notices AS noticetbl,display_notices AS displaynoticetbl WHERE noticetbl.status='Y' and noticetbl.id=displaynoticetbl.notice_id  and noticetbl.society_id=".$_SESSION['society_id']." and noticetbl.exp_date >= '".$todayDate."' ORDER BY noticetbl.id DESC LIMIT 3";
			//echo $sql;
			$result=$this->m_dbConn->select($sql);			
		}
		else
		{
			$sql="select DISTINCT noticetbl.id, noticetbl.* FROM notices AS noticetbl,display_notices AS displaynoticetbl WHERE  noticetbl.status='Y' and noticetbl.id=displaynoticetbl.notice_id and  noticetbl.society_id=".$_SESSION['society_id']." and noticetbl.exp_date >= '".$todayDate."' and  displaynoticetbl.unit_id IN (".$_SESSION['unit_id'].",0) ORDER BY noticetbl.id DESC LIMIT 3";
			//echo $sql;
			$result=$this->m_dbConn->select($sql);
			
		}
	return $result;
			
		
		
	}
	
	public function GetPaymentSummary()
	{
		
		$sql="select * from `chequeentrydetails`  where PaidBy=".$_SESSION['unit_id']."  ORDER BY VoucherDate DESC";
		//echo $sql;
		$result=$this->m_dbConn->select($sql);
		return $result;
	}
	
	
	//  ---------------------------- Pending list count  First card ----------------------------------------------------///
	
	public function getCountPendingPovider()
	{
		$sqlCountProvider= "select count(`active`) as PendingProviders from `service_prd_reg` where society_id ='".$_SESSION['society_id']."' and active='0' ";
		$result=$this->m_dbConnRoot->select($sqlCountProvider);	
		return $result;
	}
	
	public function getCountPendingTenant()
	{
		$todayDate= date("Y-m-d");
		$sqlCountTenant= "SELECT count(active) as TenantCount FROM `tenant_module` where active= '0' and end_date >= '".$todayDate."' ";
		$result=$this->m_dbConn->select($sqlCountTenant);
		return $result;
	}
	
	public function getCountPendingTClassified()
	{
		$todayDate= date("Y-m-d");
		$sqlCountClassified= "SELECT count(active) as ClassifiedCount FROM `classified` where active= '0' and exp_date >= '".$todayDate."' and society_id = '".$_SESSION['society_id']."'";
		$result=$this->m_dbConnRoot->select($sqlCountClassified);
		return $result;
	}
	
	//  ---------------------------- Service request list in Second  card ----------------------------------------------------///
	
	
	public function getLegalCasetList()
	{
		$dblistsql = "Select societytbl.dbname from mapping as maptbl JOIN society as societytbl ON maptbl.society_id = societytbl.society_id JOIN dbname as db ON db.society_id = societytbl.society_id WHERE maptbl.login_id = '" . $_SESSION['login_id'] . "' and societytbl.status = 'Y' and maptbl.status = '2' ORDER BY societytbl.society_name ASC ";
		
		$data = $this->m_dbConnRoot->select($dblistsql);
		$dblist = array_column($data, 'dbname');
		//$sqlServiceRequest= "SELECT * FROM `service_request` where status NOT IN('Closed') ORDER BY request_id DESC";
		
		$sqlLegalCase="SELECT * FROM `legal_case`  ORDER BY request_id DESC";
		
		//echo $sqlServiceRequest="SELECT sr.* ,u.unit_no FROM `legal_case` as sr join unit as u on sr.unit_id=u.unit_id where sr.status NOT IN('Closed') AND sr.status NOT IN('Resolved') ORDER BY request_id DESC";
		
		if($_SESSION['res_flag'])
		{
			// $result = [];
			foreach($dblist as $DB) {
				$mysqlicon = mysqli_connect(DB_HOST_SER_REQ, DB_USER_SER_REQ, DB_PASSWORD_SER_REQ, $DB);
				$allresobj = mysqli_query($mysqlicon, $sqlLegalCase);
				$allres[] = mysqli_fetch_all($allresobj, MYSQLI_ASSOC);
				
				mysqli_close($mysqlicon);
			}
			
			foreach ($allres as $subArray) {
				foreach ($subArray as $item) {
					$result[] = $item;
				}
			}
		}
		else{
			$result = $this->m_dbConn->select($sqlLegalCase);
		}
		// print_r($result);
		//$result=$this->m_dbConn->select($sqlLegalCase);
		return $result;
	}
	
		//  ---------------------------- Reminder list in Third card ----------------------------------------------------///
		
	/*public function UpcomingPaymentReminder()
	{
		$sqlServiceRequest= "SELECT * FROM `service_request` where status NOT IN('Closed') ORDER BY request_id DESC";
		$result=$this->m_dbConn->select($sqlServiceRequest);
		return $result;
	}*/
	public function LeaseExpiryReminder()
	{
		$sqlLeaseExpiry= "select u.unit_no,u.unit_id, w.wing from `tenant_module` as t join unit as u on u.unit_id=t.unit_id join wing as w on u.wing_id=w.wing_id where t.end_date >= DATE(now()) and t.end_date <= DATE_ADD(DATE(now()), INTERVAL 1 Month) and t.status='Y'  order by t.tenant_id desc";
		$result=$this->m_dbConn->select($sqlLeaseExpiry);
		//print_r($result);
		return $result;
	}
	public function UpcomingNoticesReminder()
	{
		 $sqlUpcomingNotice= "SELECT * FROM `notices` where (creation_date >= DATE(now()) and creation_date <= post_date) and society_id='".$_SESSION['society_id']."'";
		$result=$this->m_dbConn->select($sqlUpcomingNotice);
		return $result;
	}
	public function FDmaturityReminder()
	{
		$sqlFdMaturity= "select fd.LedgerID,l.ledger_name, ac.group_id from fd_master as fd join `ledger` as l on l.id=fd.LedgerID join `account_category` as ac on ac.category_id=l.categoryid where fd.maturity_date >= DATE(now()) and fd.maturity_date <= DATE_ADD(DATE(now()), INTERVAL 1 Month) and fd.fd_close= '0' and fd.fd_renew='0'";
		$result=$this->m_dbConn->select($sqlFdMaturity);
		return $result;
	}
	
	//  ---------------------------- Member Dues in Fourth card ----------------------------------------------------///
	
	public function MemberDues()
	{
		$sqlFdMaturity= "SELECT * FROM society as s,member_main as mm,unit as u,wing as w where mm.unit=u.unit_id and u.wing_id=w.wing_id and mm.society_id=s.society_id and mm.status='Y' and u.status='Y' and w.status='Y' and mm.ownership_status='1' and s.society_id = '".$_SESSION['society_id']."'";
		$result=$this->m_dbConn->select($sqlFdMaturity);
		
		$finalArray = array();
		for($i= 0;$i< sizeof($result); $i++)
		{
			
			$duesAmount = $this->obj_utility->getDueAmount($result[$i]['unit_id']);
			
			if($duesAmount > 0) 
			{
				$result[$i]['TotalDuesAmount']=$duesAmount;
			
				//array_push($finalArray, array($tempArray,'unit_id'=>$result[$i]['unit_id'], 'MemberName' => $result[$i]['owner_name'], 'Amount' => $result[$i]['TotalDuesAmount']));
				array_push($finalArray, array($tempArray, 'Amount' => $result[$i]['TotalDuesAmount'], 'unit_id'=>$result[$i]['unit_id'], 'MemberName' => $result[$i]['owner_name'], 'UnitNo' =>$result[$i]['unit_no']));
				
			}
		}
		arsort($finalArray);
		return $finalArray;
		
	}
	
	public function TenantDues()
	{
		$sqlFdMaturity= "SELECT * FROM society as s,tenant_module as tm,unit as u,wing as w where tm.unit_id=u.unit_id and u.wing_id=w.wing_id and tm.status='Y' and u.status='Y' and w.status='Y'and s.society_id = '".$_SESSION['society_id']."' and tm.end_date > curdate()";
		$result=$this->m_dbConn->select($sqlFdMaturity);
		
		$finalArray = array();
		for($i= 0;$i< sizeof($result); $i++)
		{
			if($this->isLandLordDB){
				$rental = true;
				$Dues = $this->obj_utility->getDueAmount($result[$i]['ledger_id'],$rental);
				$duesAmount = "0.00";
				if($Dues <> "")
				{
					$duesAmount = $Dues;
				}
			}else{
				$rental = false;
				$Dues = $this->obj_utility->getDueAmount($result[$i]['ledger_id'],$rental);
				$duesAmount = "0.00";
				if($Dues <> "")
				{
					$duesAmount = $Dues;
				}
			}
			
			if($duesAmount > 0) 
			{
				$result[$i]['TotalDuesAmount']=$duesAmount;
			
				//array_push($finalArray, array($tempArray,'unit_id'=>$result[$i]['unit_id'], 'MemberName' => $result[$i]['owner_name'], 'Amount' => $result[$i]['TotalDuesAmount']));
				array_push($finalArray, array($tempArray, 'Amount' => $result[$i]['TotalDuesAmount'], 'unit_id'=>$result[$i]['unit_id'], 'MemberName' => $result[$i]['tenant_name'], 'UnitNo' =>$result[$i]['unit_no'], 'LedgerID' =>$result[$i]['ledger_id'] , 'TenantID' =>$result[$i]['tenant_id']));
				
			}
		}
		arsort($finalArray);
		return $finalArray;
		
	}

	public function getAllUnits()
	{
		$sql="select `unit_id` from `unit` where `society_id` = ".$_SESSION['society_id']." and `status` = 'Y' order by sort_order asc";
		$res=$this->m_dbConn->select($sql);
		$flatten = array();
    	foreach($res as $key)
		{
			$flatten[] = $key['unit_id'];
		}

    	return $flatten;
	}
	//  ---------------------------- Expenses report Fourth card ----------------------------------------------------///
	public function GetExpenseSummaryDetailedDashboard()
	{
		$arBankNameAndBalance = "";
		$sqlSelect = "";
		$sqlQuery = "SELECT  expense.LedgerID,  sum(expense.Debit) AS debit,  sum(expense.Credit) AS credit, expense.VoucherID, led.ledger_name FROM expenseregister as expense JOIN ledger as led ON led.id =expense.LedgerID where led.society_id='".$_SESSION['society_id']."'";
		if($_SESSION['default_year_start_date'] <> 0  && $_SESSION['default_year_end_date'] <> 0)
		{
			
			$sqlQuery .= "  and expense.Date BETWEEN '".getDBFormatDate($_SESSION['default_year_start_date'])."' AND '".getDBFormatDate($_SESSION['default_year_end_date'])."'";					
		}
		$sqlQuery .= "  GROUP BY expense.LedgerID";
		$retData = $this->m_dbConn->select($sqlQuery);
		//echo "First";
		//print_r($retData);
		$finalArray = array();
		for($i=0; $i < sizeof($retData) ; $i++)
		{
			//if($retData[$i]['debit'] > 10000)
			//{
				$DebitAmount=$retData[$i]['debit'];
				$LedgerName=$retData[$i]['ledger_name'];	
			
				$parentArray = array('DebitAmount' => $DebitAmount, 'LedgerName' => $LedgerName);
				array_push($finalArray , $parentArray);	
			//}
				
		}
		
		
	
		return $finalArray;
	}
	
	//  ---------------------------- Reciept report Fifth card ----------------------------------------------------///
/*	public function getRecieptReportCurrentPeriod($wing_id=0)
	{
		
		$todayDate= date("Y-m-d");
		
		$sql1 = "SELECT * FROM `period` WHERE `YearID` = '".$_SESSION['default_year']."'";
		$sql1_res = $this->m_dbConn->select($sql1);
		
		$flag = 0;
		for($i = 0; $i < sizeof($sql1_res); $i++)
		{
			$return = $this->obj_utility->getIsDateInRange($todayDate,$sql1_res[$i]['BeginingDate'],$sql1_res[$i]['EndingDate']);
			if($return == true)
			{
				$current_period_id = $sql1_res[$i]['ID'];
				$flag = 1;
			}			
		}
		
		if($flag == 0)
		{
			 $sql2 = "SELECT MAX(`ID`) AS `ID` FROM `period` WHERE `YearID` = '".$_SESSION['default_year']."'";
			$sql2_res = $this->m_dbConn->select($sql2);
			$current_period_id = $sql2_res[0]['ID'];
		}
		
		//echo "current period: ".$current_period_id;
		
		if($wing_id == 0)
		{
			$sql01 = "SELECT `unit_id` FROM `unit` WHERE `status` = 'Y' ORDER BY `sort_order`";
			$sql11 = $this->m_dbConn->select($sql01);
		}
		else
		{
			$sql01 = "SELECT `unit_id` FROM `unit` WHERE `status` = 'Y' AND `wing_id` = '".$wing_id."' ORDER BY `sort_order`";
			$sql11 = $this->m_dbConn->select($sql01);
		}
		
		$sql07 = "SELECT `society_name` FROM `society` WHERE `society_id` = '".$_SESSION['society_id']."'";
		$sql77 = $this->m_dbConn->select($sql07);
		
		$sql08 = "SELECT `BeginingDate`, `EndingDate` FROM `period` WHERE `ID` = '".$current_period_id."'";
		$sql88 = $this->m_dbConn->select($sql08);
		
		if($wing_id == 0)
		{
			$wing = "All";			
		}
		else
		{
			$sql09 = "SELECT `wing` FROM `wing` WHERE `wing_id` = '".$wing_id."' AND `society_id` = '".$_SESSION['society_id']."'";
			$sql99 = $this->m_dbConn->select($sql09);
			$wing = $sql99[0]['wing'];
		}
		
			$sql03 = "SELECT `BillDate`, `DueDate` FROM `billregister` WHERE `PeriodID` = '".$current_period_id."' AND `BillType` = '0'";
			$sql33 = $this->m_dbConn->select($sql03);
		
			$current_bill_date = $sql33[0]['BillDate'];
			//echo "<br>";
		
			$sql12 = "SELECT `ID` FROM `period` WHERE `PrevPeriodID` = '".$current_period_id."'";
			$sql12_res = $this->m_dbConn->select($sql12);
		
			$sql13 = "SELECT `BillDate`, `DueDate` FROM `billregister` WHERE `PeriodID` = '".$current_period_id."' AND `BillType` = '0'";
		$sql13_res = $this->m_dbConn->select($sql13);
		
		
		$total_BillAmount = 0;
		$total_BillArrears = 0;
		$total_AmountDue = 0;
		$total_Amount = 0;
		$total_ReturnedCheques_Amount = 0;
		$finalArray= array();
		for($i = 0; $i < sizeof($sql11); $i++)
		{
			$total_arrears = 0;
			$sql05 = "SELECT * FROM `billdetails` WHERE `UnitID` = '".$sql11[$i]['unit_id']."' AND `PeriodID` = '".$selectPeriodID[0]['PrevPeriodID']."' AND `BillType` = '0'";
			$sql55 = $this->m_dbConn->select($sql05);
		
			$sql03 = "SELECT `BillDate`, `DueDate` FROM `billregister` WHERE `PeriodID` = '".$current_period_id."' AND `BillType` = '0'";
			$sql33 = $this->m_dbConn->select($sql03);
		
			$sql04 = "SELECT * FROM `chequeentrydetails` WHERE `PaidBy` = '".$sql11[$i]['unit_id']."' AND `BillType` = '0' AND `VoucherDate` BETWEEN '".$sql33[0]['BillDate']."' AND '".$sql33[0]['DueDate']."'";
			$sql44 = $this->m_dbConn->select($sql04);
			
			$total_arrears = $sql44[0]['PrincipalArrears'] + $sql44[0]['InterestArrears'];
		
			$sql06 = "SELECT u.`unit_no`, mm.`owner_name` FROM `unit` u, `member_main` mm WHERE u.`unit_id`=mm.`unit` AND u.`unit_id` = '".$sql11[$i]['unit_id']."'";
			$sql66 = $this->m_dbConn->select($sql06);		
		
		
			
			$total_BillAmount = $total_BillAmount + $sql55[0]['CurrentBillAmount'];
			$total_BillArrears = $total_BillArrears + $total_arrears;
			$total_AmountDue = $total_AmountDue + $sql55[0]['TotalBillPayable'];
			
			if($sql44[0]['IsReturn'] == 1)
			{
				
				$total_ReturnedCheques_Amount = $total_ReturnedCheques_Amount + $sql44[0]['Amount'];
			}
			else if($sql44[0]['IsReturn'] == 0)
			{
				$total_Amount = $total_Amount + $sql44[0]['Amount'];
			}
			
			
			
			if(sizeof($sql44) > 1)
			{
				for($j = 1; $j < sizeof($sql44); $j++)
				{
					
					
					if($sql44[$j]['IsReturn'] == 1)
					{
						
						$total_ReturnedCheques_Amount = $total_ReturnedCheques_Amount + $sql44[0]['Amount'];
					}
					else if($sql44[$j]['IsReturn'] == 0)
					{
						
						$total_Amount = $total_Amount + $sql44[$j]['Amount'];
					}
					
					
				}
			}
			$parentArray = array('TotalBillAmount' => $total_BillAmount, 'TotalRecievedAmount' => $total_Amount, 'TotalRejectedAmount' => $total_ReturnedCheques_Amount);
			
		}
		
		array_push($finalArray,$parentArray);
		print_r($finalArray);
		return $finalArray;		
	}
	*/
	/*------------------------------- Count Active Users ------------------------------------*/
	public function getActiveUsers()
	{
		
		$sql1 = "Select societytbl.society_name, maptbl.id, maptbl.login_id, maptbl.desc, maptbl.code, maptbl.role, maptbl.status, maptbl.unit_id ,maptbl.view from mapping as maptbl JOIN society as societytbl ON maptbl.society_id = societytbl.society_id and maptbl.society_id = '" . $_SESSION['society_id'] . "' order by maptbl.sort_order";
		$result = $this->m_dbConnRoot->select($sql1);
		return $result;
	}
	public function getUnitCount()
	{
		$FinalArray =array();
		$selectUnit= "Select * from unit where society_id='".$_SESSION['society_id']."'";
		$resultUnit = $this->m_dbConn->select($selectUnit);
		$resultUnit[0]['TotalUnit']= sizeof($resultUnit);
		$FinalArray =array();
		$count = 0;
		for($i = 0; $i < sizeof($resultUnit); $i++)
		{
			 $sql1 = "Select societytbl.society_name, maptbl.id, maptbl.login_id, maptbl.desc, maptbl.code, maptbl.role, maptbl.status, maptbl.unit_id ,maptbl.view from mapping as maptbl JOIN society as societytbl ON maptbl.society_id = societytbl.society_id and maptbl.society_id = '" . $_SESSION['society_id'] . "' and maptbl.unit_id='".$resultUnit[$i]['unit_id']."'  and maptbl.status= '2' order by maptbl.sort_order";
			
			$result = $this->m_dbConnRoot->select($sql1);
		
			if($result <> "")
			{
				//var_dump($result);
				$count++ ;
			}

		
		}
		$resultUnit[0]['ActiveUnit']=$count;
		$FinalArray = array('ActiveUnit' =>$resultUnit[0]['ActiveUnit'], 'TotalUnit' => $resultUnit[0]['TotalUnit']);
		return $FinalArray;
	}
	
	
	public function getMemberInfo($login_id)
	{
		$sql = "Select * from login where login_id = '" . $login_id . "'";
		$result = $this->m_dbConnRoot->select($sql);

		if($login_id > 0 && $result == '')
		{
			$result[0]['name'] = '<font color="red">NO LOGIN NAME FOUND</font>';
			$result[0]['member_id'] = '<font color="red">NO LOGIN EMAIL FOUND</font>';
		}
		
		return $result;
	}
	
	public function getMemberProfile($unit_id)
	{
		$sql = "Select * from member_main where society_id = '" . $_SESSION['society_id'] . "' and unit = '" . $unit_id . "'";
		$result = $this->m_dbConn->select($sql);
		return $result;
	}
	
	public function SMSCounter()
	{
		$sql1 = "SELECT count(`SentSMSReminderDate`) as `Total` FROM `notification` WHERE `SentSMSReminderDate` NOT IN('0000-00-00 00:00:00')";		
		$sql1_result = $this->m_dbConn->select($sql1);
		
		$sql2 = "SELECT count(`SentBillSMSDate`) as `Total` FROM `notification` where `SentBillSMSDate` NOT IN('0000-00-00 00:00:00')";		
		$sql2_result = $this->m_dbConn->select($sql2);

		$total = $sql1_result[0]['Total'] + $sql2_result[0]['Total'];	
		
		return $total;	
	}
	
	public function purchageCounter()
	{
		
		$sql1 = "SELECT * FROM `sms_allotment` where SocietyId='".$_SESSION['society_id']."' and Payment_Received=1";		
		$sql1_result = $this->m_dbConnRoot->select($sql1);
		$totalPurchgeSMS = 0;
		for($i=0;$i< sizeof($sql1_result);$i++)
		{
			$totalPurchgeSMS += $sql1_result[$i]['SMSAllotted'];
		}
		
		return $totalPurchgeSMS;	
	}
	public function getServiceRequestCount()
	{
		//$finalarray=array();
		$sql1 = "SELECT count(m1.status) as Raised FROM service_request m1 LEFT JOIN service_request m2 ON (m1.request_no = m2.request_no AND m1.request_id < m2.request_id) WHERE  m2.request_id IS NULL  and m1.`visibility`='1' and m1.status='Raised'";
		$result1 = $this->m_dbConn->select($sql1);
		
		$sql2="SELECT count(m1.status) as Process FROM service_request m1 LEFT JOIN service_request m2 ON (m1.request_no = m2.request_no AND m1.request_id < m2.request_id) WHERE  m2.request_id IS NULL  and m1.`visibility`='1' and m1.status NOT IN('Raised') and m1.status NOT IN ('Closed')";
		
		$result2 = $this->m_dbConn->select($sql2);
		
		$sql3 ="SELECT count(m1.status) as Closed FROM service_request m1 LEFT JOIN service_request m2 ON (m1.request_no = m2.request_no AND m1.request_id < m2.request_id) WHERE m2.request_id IS NULL and m1.`visibility`='1' and m1.status = 'Resolved' and m1.status ='Closed'";
	
	$result3 = $this->m_dbConn->select($sql3);
	$finalarray = array('Raised' => $result1[0]['Raised'], 'Process' => $result2[0]['Process'], 'Closed' => $result3[0]['Closed']);
	
	return $finalarray;
	}
	
	
	/*------------------------------------------------------Fourth card -----------------------------*/
	
	function getRecieptReportCurrentPeriod($wing_id=0)
	{
		
		$todayDate= date("Y-m-d");
		
		$sql1 = "SELECT * FROM `period` WHERE `YearID` = '".$_SESSION['default_year']."'";
		$sql1_res = $this->m_dbConn->select($sql1);
		
		$flag = 0;
		for($i = 0; $i < sizeof($sql1_res); $i++)
		{
			$return = $this->obj_utility->getIsDateInRange($todayDate,$sql1_res[$i]['BeginingDate'],$sql1_res[$i]['EndingDate']);
			if($return == true)
			{
				 $current_period_id = $sql1_res[$i]['ID'];
				 $Previous_period_id = $sql1_res[$i]['PrevPeriodID'];
				$flag = 1;
			}			
		}
		
		if($flag == 0)
		{
			//$sql2 = "SELECT MAX(`ID`) AS `ID` FROM `period` WHERE `YearID` = '".$_SESSION['default_year']."'";
		   	$sql2 = "SELECT MAX(`ID`) AS `ID`, Max(`PrevPeriodID`) as `PrevPeriodID` FROM `period` WHERE `YearID` = '".$_SESSION['default_year']."'";
			$sql2_res = $this->m_dbConn->select($sql2);
			$current_period_id = $sql2_res[0]['ID'];
			$Previous_period_id  = $sql2_res[0]['PrevPeriodID'];
		}
		
		
		
		
		if($wing_id == 0)
		{
			$sql01 = "SELECT `unit_id` FROM `unit` WHERE `status` = 'Y' ORDER BY `sort_order`";
			$sql11 = $this->m_dbConn->select($sql01);
		}
		else
		{
			$sql01 = "SELECT `unit_id` FROM `unit` WHERE `status` = 'Y' AND `wing_id` = '".$wing_id."' ORDER BY `sort_order`";
			$sql11 = $this->m_dbConn->select($sql01);
		}
		
		$sql07 = "SELECT `society_name` FROM `society` WHERE `society_id` = '".$_SESSION['society_id']."'";
		$sql77 = $this->m_dbConn->select($sql07);
		
		$sql08 = "SELECT `BeginingDate`, `EndingDate` FROM `period` WHERE `ID` = '".$current_period_id."'";
		$sql88 = $this->m_dbConn->select($sql08);
		
		if($wing_id == 0)
		{
			$wing = "All";			
		}
		else
		{
			$sql09 = "SELECT `wing` FROM `wing` WHERE `wing_id` = '".$wing_id."' AND `society_id` = '".$_SESSION['society_id']."'";
			$sql99 = $this->m_dbConn->select($sql09);
			$wing = $sql99[0]['wing'];
		}
		
		$sql03 = "SELECT `BillDate`, `DueDate` FROM `billregister` WHERE `PeriodID` = '".$Previous_period_id."' AND `BillType` = '0'";
		$sql33 = $this->m_dbConn->select($sql03);
		//echo "<br>";
		$current_bill_date = $sql33[0]['BillDate'];
		//echo "<br>";
		
		$sql12 = "SELECT `ID` FROM `period` WHERE `PrevPeriodID` = '".$current_period_id."'";
		$sql12_res = $this->m_dbConn->select($sql12);
		
		$sql13 = "SELECT `BillDate`, `DueDate` FROM `billregister` WHERE `PeriodID` = '".$sql12_res[0]['ID']."' AND `BillType` = '0'";
		$sql13_res = $this->m_dbConn->select($sql13);
		//echo "<br>";
		if($sql13_res <> "")
		{
			$next_period_bill_date = $sql13_res[0]['BillDate'];
		}
		else
		{
			$next_period_bill_date = date("Y-m-d");
		}
		
	//	$period_string = "Member Receipt Report for period ".getDisplayFormatDate($current_bill_date)." to ".getDisplayFormatDate($next_period_bill_date)." for ".$wing." wing(s).";
		
		
		
		$total_BillAmount = 0;
		$total_BillArrears = 0;
		$total_AmountDue = 0;
		$total_Amount = 0;
		$total_ReturnedCheques_Amount = 0;	
		$finalArray= array();	
		
		for($i = 0; $i < sizeof($sql11); $i++)
		{
			$total_arrears = 0;
			$dues = 0;
			
			 $sql05 = "SELECT * FROM `billdetails` WHERE `UnitID` = '".$sql11[$i]['unit_id']."' AND `PeriodID` = '".$Previous_period_id."' AND `BillType` = '0'";
		
			$sql55 = $this->m_dbConn->select($sql05);			
		
			$sql04 = "SELECT * FROM `chequeentrydetails` WHERE `PaidBy` = '".$sql11[$i]['unit_id']."' AND `BillType` = '0' AND `VoucherDate` BETWEEN '".$current_bill_date."' AND '".$next_period_bill_date."'";
			$sql44 = $this->m_dbConn->select($sql04);
			
			$total_arrears = $sql55[0]['PrincipalArrears'] + $sql55[0]['InterestArrears'];
		
			$sql06 = "SELECT u.`unit_no`, mm.`owner_name` FROM `unit` u, `member_main` mm WHERE u.`unit_id`=mm.`unit` AND u.`unit_id` = '".$sql11[$i]['unit_id']."'";
			$sql66 = $this->m_dbConn->select($sql06);		
		
		
			
			$total_BillAmount = $total_BillAmount + $sql55[0]['CurrentBillAmount'];
			$total_BillArrears = $total_BillArrears + $total_arrears;
			$total_AmountDue = $total_AmountDue + $sql55[0]['TotalBillPayable'];
			
			if($sql44[0]['IsReturn'] == 1)
			{
				$total_ReturnedCheques_Amount = $total_ReturnedCheques_Amount + $sql44[0]['Amount'];
			}
			else if($sql44[0]['IsReturn'] == 0)
			{
				
				$sql55[0]['TotalBillPayable'] = $sql55[0]['TotalBillPayable'] - $sql44[0]['Amount'];
				$total_Amount = $total_Amount + $sql44[0]['Amount'];
			}
			
		
			
			if(sizeof($sql44) > 1)
			{
				for($j = 1; $j < sizeof($sql44); $j++)
				{
					
					
					if($sql44[$j]['IsReturn'] == 1)
					{
						
						$total_ReturnedCheques_Amount = $total_ReturnedCheques_Amount + $sql44[0]['Amount'];
					}
					else if($sql44[$j]['IsReturn'] == 0)
					{
						
						$sql55[0]['TotalBillPayable'] = $sql55[0]['TotalBillPayable'] - $sql44[$j]['Amount'];
						$total_Amount = $total_Amount + $sql44[$j]['Amount'];
					}
					
					
				}
			}
			$parentArray = array('TotalBillAmount' => $total_BillAmount, 'TotalRecievedAmount' => $total_Amount, 'TotalRejectedAmount' => $total_ReturnedCheques_Amount, "PeriodID"=>$current_period_id);
		}
		
		
		
		array_push($finalArray,$parentArray);
		//print_r($finalArray);
		return $finalArray;		
	}
	
/*   --------------------   member Dashboard ---------------------------*/
	function BillSummary()
	{
		 $sql = "Select billreg.DueDate as DueDate, period.Type as Month, yr.YearDescription as Year,bill.UnitID as BillUnitID,bill.PeriodID as BillPeriodID,bill.CurrentBillAmount,bill.TotalBillPayable from billdetails as bill JOIN period as period ON bill.PeriodID = period.id JOIN year as yr ON yr.YearID=period.YearID JOIN billregister as billreg ON bill.PeriodID = billreg.PeriodID where bill.UnitID='". $_SESSION["unit_id"] . "' group by bill.PeriodID DESC limit 0,3";
		$result = $this->m_dbConn->select($sql);	
		
		return $result;
	}
	function PaymentSummary()
	{
		 $sql = "select ChequeDate,Amount,PaidBy,ChequeNumber,DepositID from chequeentrydetails where PaidBy='" . $_SESSION["unit_id"] . "' order by ChequeDate DESC limit 0,3";
		$result = $this->m_dbConn->select($sql);	
		
		return $result;
	}
	
	function TaskSummary()
	{
		 $sql = "SELECT id,Title,Priority,DueDate,PercentCompleted FROM `tasklist` where status IN(1,2,3)";
		 $result = $this->m_dbConn->select($sql);	
		
		return $result;
	}
	
	function GetSecurityDB($societyID)
	{
		$sql = "Select `society_id`,`dbname`,`security_dbname` from `society` where `society_id` = '" . $societyID. "' ";
		$result = $this->m_dbConnRoot->select($sql);
		
		return $result;
	}
	function getCountOfPendingRenovationRequest()
	{
		$sql = "Select COUNT(*) as renovationCount from renovation_details as rr, service_request as sr, unit as u, member_main as mm, approval_details as ad where ad.`verifiedStatus` = 'N' and rr.`status` = 'Y' and u.`unit_id` = rr.`unit_id` and mm.`unit`= u.`unit_id` and rr.`status` = 'Y' and sr.`request_id` = rr.`request_id` and ad.`referenceId` = rr.`Id` and ad.`module_id` = '".RENOVATION_SOURCE_TABLE_ID."'";
		$sql_res = $this->m_dbConn->select($sql);
		return($sql_res[0]['renovationCount']);
	}
	function getCountOfPendingAddressProofRequest()
	{
		//Select COUNT(*) as addressProofCount from addressproof_noc as ap, service_request as sr, unit as u, approval_details as ad where ad.`verifiedStatus` = 'N' and ap.`status` = 'Y' and u.`unit_id` = ap.`unit_id` and sr.`request_id` = ap.`service_request_id` and ad.`referenceId` = ap.`id` and ad.`module_id` = '2'
		$sql = "Select COUNT(*) as addressProofCount from addressproof_noc as ap, service_request as sr, unit as u, approval_details as ad where ad.`verifiedStatus` = 'N' and ap.`status` = 'Y' and u.`unit_id` = ap.`unit_id` and sr.`request_id` = ap.`service_request_id` and ad.`referenceId` = ap.`id` and ad.`module_id` = '".ADDRESSPROOF_SOURCE_TABLE_ID."'";
		$sql_res = $this->m_dbConn->select($sql);
		return($sql_res[0]['addressProofCount']);
	}

	public function FetchUnitName($IsOutsider)
	{
		$str = '';
		if($_SESSION['res_flag'] == 1){
			$defaultText = 'Please Select To View Member';
		}else{
			$defaultText = 'Please Select To View Tenant';
		}
		if($IsOutsider == 0)
		{
			if($_SESSION['res_flag'] == 1){
				$query = "select mm.member_id, CONCAT(CONCAT(u.unit_no,'-'), mm.owner_name) AS 'unit_no' from unit AS u JOIN `member_main` AS mm ON u.unit_id = mm.unit where u.status = 'Y' and u.society_id = '" . $_SESSION['society_id'] . "' and mm.ownership_status=1 ORDER BY u.sort_order ";
			}else{
				$query = "select tm.tenant_id, CONCAT(CONCAT(u.unit_no,'-'), tm.tenant_name) AS 'unit_no' from unit AS u JOIN `tenant_module` AS tm ON u.unit_id = tm.unit_id where u.status = 'Y' and u.society_id = '" . $_SESSION['society_id'] . "' and tm.status='Y' ORDER BY u.sort_order ";
			}
		}
		if($_SESSION['res_flag'] == 1){
			$str .= "<option value='' >Please Select To View Member</option>";
		}else{
			$str .= "<option value='' >Please Select To View Tenant</option>";
		}
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
						if($_SESSION['res_flag'] == 1){
							$str.='<OPTION VALUE="view_member_profile.php?scm&id='.$v.'&tik_id='.time().'&m&view"
						 	>';
						}else{
							$str.='<OPTION VALUE="view_tenant_profile.php?scm&id='.$v.'&tik_id='.time().'&m&view"
							>';
						}
					}
					else
					{
						$str.=$v.'</OPTION>';
					}
					$i++;
				}
			}
		}
		return $str;
	}


	public function FetchLedger()
	{
		$str = '';
		$defaultText = 'Please Select To View Ledger';
		if($IsOutsider == 0)
		{
			
			$query="select g.id as gid, ledgertable.`id`,concat(ledgertable.ledger_name, '  [ ', categorytbl.category_name,' ]') as Ledger from `ledger` as ledgertable  Join `account_category` as categorytbl on categorytbl.category_id=ledgertable.categoryid join `group` as g on categorytbl.group_id = g.id  where society_id= '".$_SESSION['society_id']."'";
		}
			
		$str .= "<option value='' >Please Select To View Ledger</option>";
		
		
		$data = $this->m_dbConn->select($query);

		if(!is_null($data))
		{
			foreach($data as $key => $value)
			{
				
			 $str.='<OPTION VALUE="view_ledger_details.php?lid='.$value['id'].'&gid='.$value['gid'].'">'.$value['Ledger'].'</OPTION>';

				
			}
		}
		return $str;
	}
	
	public function GetPCDReports()
	{
		$dblistsql = "Select societytbl.dbname from mapping as maptbl JOIN society as societytbl ON maptbl.society_id = societytbl.society_id JOIN dbname as db ON db.society_id = societytbl.society_id WHERE maptbl.login_id = '" . $_SESSION['login_id'] . "' and societytbl.status = 'Y' and maptbl.status = '2' ORDER BY societytbl.society_name ASC ";
		$data = $this->m_dbConnRoot->select($dblistsql);
		$dblist = array_column($data, 'dbname');
		
$sql1 = "SELECT count(cheque_date) as no_of_cheques , cheque_date, SUM(amount) as amount FROM `postdated_cheque` where cheque_date >= NOW() group by cheque_date order by cheque_date asc";

if($_SESSION['res_flag'])
		{
			// $result = [];
			foreach($dblist as $DB) {
				$mysqlicon = mysqli_connect(DB_HOST_SER_REQ, DB_USER_SER_REQ, DB_PASSWORD_SER_REQ, $DB);
				$allresobj = mysqli_query($mysqlicon, $sql1);
				$allres[] = mysqli_fetch_all($allresobj, MYSQLI_ASSOC);
				
				mysqli_close($mysqlicon);
			}
			
			foreach ($allres as $subArray) {
				foreach ($subArray as $item) {
					$result[] = $item;
				}
			}
		}
		else{
			$result = $this->m_dbConn->select($sql1);
		}
		//$result = $this->m_dbConn->select($sql1);
		return $result;	
	}
	
	function GetFlatDetails($societyId)
	{
		$finalArray= array();	
		$sql="SELECT Count(unit_id) as no_of_flat FROM `unit` where society_id= '".$societyId."' and status='Y'";
		$result = $this->m_dbConn->select($sql);
		
		$sql1="SELECT count(tenant_id) as no_of_occupied FROM `tenant_module` where end_date >= NOW()";
		$result1 = $this->m_dbConn->select($sql1);
		$nonCooupied = $result[0]['no_of_flat']-$result1[0]['no_of_occupied'];
		$finalArray = array('TotalFlat' => $result[0]['no_of_flat'], 'TotalOccupied' => $result1[0]['no_of_occupied'],'TotalnonOccupied' =>$nonCooupied);
		return $finalArray;
		
	} 
}

?>