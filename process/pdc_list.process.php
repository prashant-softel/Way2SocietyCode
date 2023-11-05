<?php	
//echo "try";
include_once("../classes/pdc_list.class.php"); 
include_once("../classes/include/dbop.class.php");
include_once("ChequeDetails.class.php");
include_once("utility.class.php");
include_once("dbconst.class.php");
		$dbConn = new dbop();
		$dbConnRoot = new dbop(true);
		$ErrorLog='';
		$actionPage="";
		
        $obj_utility= new utility($dbConn);
		$obj_pdc_list = new pdc_list($dbConn);
		// $data = $obj_pdc_list->AddNewValues($_POST['sid']);	
		// $actionPage = $obj_pdc_list->actionPage;
		// $ErrorLog = $obj_pdc_list->errorLog;
		// echo "end";

    if(isset($_POST['chequeData']))
	{	
		// echo"<pre>";
        // print_r($_POST['chequeData']);
        // echo "</pre>";
        // exit;
        $data =$_POST['chequeData'];
        $chequeData = $obj_pdc_list->insertData($data);
        $actionPage = $obj_pdc_list->actionPage;
		$ErrorLog = $obj_pdc_list->errorLog;

        // $ChequeLeafBook=array();
        // $data=0;
		// $DepositeID=0;

        // $banksSQL = "SELECT ledger.id AS BankID, ledger.ledger_name as BankName FROM ledger JOIN bank_master ON ledger.id = bank_master.BankID";
		// $banks = $this->m_dbConn->select($banksSQL);
        // $bankID= array();
        // $bankNames = array();

        // $Counter = $this->obj_utility->GetCounter(VOUCHER_RECEIPT, $BankID,false);
        // $vNo = $Counter[0]['CurrentCounter'];

        // $systemVoucherNo = $vNo;

        // for ($i = 0; $i < count($banks); $i++)
		// {
		// 	$bankID[$i] = $banks[$i]['BankID'];
		// 	$bankNames[$i] = $banks[$i]['BankName'];
		// }

        // foreach($data as $k => $v)
		// {
        //     $voucherDate = $data[$k]['cheque_data'];
        //     $cheque_no = $data[$k]['cheque_no'];
        //     $cheque_date = $data[$k]['cheque_data'];
        //     $amount = $data[$k]['amount'];
        //     $tenant_id = $data[$k]['tenant_id'];
        //     $bank_name = $data[$k]['bank_name'];
        //     $bank_branch = $data[$k]['bank_branch'];
        //     $remark = $data[$k]['remark'];

        //     $desc = 'DATA IMPORTED'.date('Y-m-d H:i:sa');
		// 	$queryII = "select `society_creation_yearid` FROM `society` where `society_id` = '".$_SESSION['society_id']."'";
		// 	$resII = $this->m_dbConn->select($queryII);
								  	
		// 	$insert_query1="insert into depositgroup (`bankid`,`createby`,`depositedby`,`status`,`desc`,`DepositSlipCreatedYearID`) values ('".$BankID."','".$_SESSION['login_id']."','PDC','0','".$desc."','".$resII[0]['society_creation_yearid']."')";
		// 	$data = $this->m_dbConn->insert($insert_query1);
        //     $DepositeID=$data; 
            
        //     $data = $obj_ChequeDetails->AddNewValues($voucherDate,$cheque_date,$cheque_no,$vNo,$systemVoucherNo,1,$amount,$tenant_id,$BankID,$bank_name,$bank_branch,$DepositeID,$remark,2,0,0,0,0,0,false,'',0,0,0);	
        //     // echo $wing;
        // }
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

