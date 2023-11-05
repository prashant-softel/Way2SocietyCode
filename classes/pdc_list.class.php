<?php if(!isset($_SESSION)){ session_start(); }
//include_once("include/dbop.class.php");
include_once("include/display_table.class.php");
include_once("dbconst.class.php");
include_once("voucher.class.php");
include_once("register.class.php");
include_once("utility.class.php");
include_once("ChequeDetails.class.php");
include_once("activate_user_email.class.php");

$dbConn = new dbop();
$dbConnRoot = new dbop(true);
$landLordDB = new dbop(false,false,false,false,true);
class pdc_list
{
	public $actionPage = "../pdc_list.php";
	public $m_dbConn;
	public $m_dbConnRoot;
	public $landLordDB;
	public $obj_ChequeDetails;
	public $obj_utility;
	public $isLandLordDB;

	function __construct($dbConn, $dbConnRoot, $landLordDB)
	{
		$this->m_dbConn = $dbConn;
		$this->m_dbConnRoot = $dbConnRoot;
		$dbopRoot = new dbop(true);
		$this->landLordDB = $landLordDB;
		$this->obj_ChequeDetails=new ChequeDetails($this->m_dbConn);

		$this->m_register = new regiser($dbConn);
		$this->m_voucher = new voucher($dbConn);
		$this->obj_utility = new utility($this->m_dbConn, $dbopRoot);
		$this->display_pg = new display_table($this->m_dbConn);
		$this->obj_activation = new activation_email($this->m_dbConn, $dbopRoot);
		//dbop::__construct();
		if($_SESSION['landLordDB']){
			$this->isLandLordDB = true;
		}
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

    public function chequeDetails()
	{
		//echo "test";
		//$society_id = $_SESSION['landLordSocID'];
		if($this->isLandLordDB){
		$sql1 = "SELECT * FROM society as s,tenant_module as tm,unit as u,wing as w where tm.unit_id=u.unit_id and u.wing_id=w.wing_id and tm.status='Y' ";

			//echo $sql1;
			if(isset($_SESSION['admin']) || isset($_SESSION['sadmin']))
			{
				$sql1 .= " and s.society_id = '".$_SESSION['society_id']."'";
			}
			
			if($_REQUEST['society_id']<>"")
			{
				$sql1 .= " and s.society_id = '".$_REQUEST['society_id']."'";
			}
			if($_REQUEST['wing_id']<>"")
			{
				$sql1 .= " and w.wing_id = '".$_REQUEST['wing_id']."'";
			}
			
			if($_REQUEST['unit_no'] <>"")
			{
				$sql1 .= " and u.unit_no = '".$_REQUEST['unit_no']."'";
			}
			if($_REQUEST['tenant_name']<>"")
			{
				$sql1 .= " and tm.tenant_name like '%".addslashes($_REQUEST['tenant_name'])."%'";
			}
			
			if($_REQUEST['mobile_no'] <>"")
			{
				$sql1 .= " and tm.mobile_no like '%".addslashes($_REQUEST['mobile_no'])."%'";
			}
			
			if($_REQUEST['email'] <>"")
			{
				$sql1 .= " and tm.email like '%".addslashes($_REQUEST['email'])."%'";
			}
			$sql1 .= " order by wing,u.sort_order";
			
			$result = $this->landLordDB->select($sql1);
			
			$this->show_chequeDetails($result);
		}
		else{
			$sql1 = "SELECT * FROM society as s,tenant_module as tm,unit as u,wing as w,postdated_cheque as p where tm.tenant_id = p.tenant_id and tm.unit_id=u.unit_id and u.wing_id=w.wing_id and tm.status='Y' ";

			//echo $sql1;
			if(isset($_SESSION['admin']) || isset($_SESSION['sadmin']))
			{
				$sql1 .= " and s.society_id = '".$_SESSION['society_id']."'";
			}
			
			if($_REQUEST['society_id']<>"")
			{
				$sql1 .= " and s.society_id = '".$_REQUEST['society_id']."'";
			}
			if($_REQUEST['wing_id']<>"")
			{
				$sql1 .= " and w.wing_id = '".$_REQUEST['wing_id']."'";
			}
			
			if($_REQUEST['unit_no'] <>"")
			{
				$sql1 .= " and u.unit_no = '".$_REQUEST['unit_no']."'";
			}
			if($_REQUEST['unit_id'] <>"")
			{
				$sql1 .= " and u.unit_id = '".$_REQUEST['unit_id']."'";
			}
			if($_REQUEST['tenant_id']<>"")
			{
				$sql1 .= " and tm.tenant_id like '%".addslashes($_REQUEST['tenant_id'])."%'";
			}
			if($_REQUEST['tenant_name']<>"")
			{
				$sql1 .= " and tm.tenant_name like '%".addslashes($_REQUEST['tenant_name'])."%'";
			}
			
			if($_REQUEST['bank_name'] <>"")
			{
				$sql1 .= " and p.bank_name like '%".($_REQUEST['bank_name'])."%'";
			}
			if($_REQUEST['bank_branch'] <>"")
			{
				$sql1 .= " and p.bank_branch like '%".($_REQUEST['bank_branch'])."%'";
			}
			
			if($_REQUEST['cheque_no'] <>"")
			{
				$sql1 .= " and p.cheque_no like '%".($_REQUEST['cheque_no'])."%'";
			}
            if($_REQUEST['cheque_date'] <>"")
			{
				$sql1 .= " and p.cheque_date like '%".($_REQUEST['cheque_date'])."%'";
			}
			if($_REQUEST['amount'] <>"")
			{
				$sql1 .= " and p.amount like '%".($_REQUEST['amount'])."%'";
			}
            if($_REQUEST['remark'] <>"")
			{
				echo $sql1 .= " and p.remark like '%".($_REQUEST['remark'])."%'";
			}
			$sql1 .= " order by wing,u.sort_order";
			
			$result = $this->m_dbConn->select($sql1);
			
			$this->show_chequeDetails($result);
		}
	}

    public function show_chequeDetails($res)
	{
		if($res<>"")
		{
			if(!isset($_REQUEST['page']))
			{
				$_REQUEST['page'] = 1;
			}
			$iCounter = 1 + (($_REQUEST['page'] - 1) * 50);
			$UnitArray = $this->getAllUnits();
			
			$EncodeUnitArray;
			$EncodeUrl;
			if(sizeof($UnitArray) > 0)
			{
				$EncodeUnitArray = json_encode($UnitArray);
				$EncodeUrl = urlencode($EncodeUnitArray);
			}
			?>
			<input type="hidden" id="data_arr" name="data_arr" value="" >
        <table id="example" class="display" cellspacing="0" style="width:100%">
		<thead>
        <tr>
            <th style="width:10;text-align:center;">Select All<br/><input type="checkbox" class="chk_select_all" id="chk_all" value="<%=indice%>"/></th>
        	<th width="50">Sr No.</th>
        	<th width="70">Wing</th>
            <th width="60">Unit No.</th>
            <th width="100">Tenant Name</th>
            <th width="100">Bank Name</th>
			<th width="100">Cheque No</th>
        	<th width="100">Cheque Date</th>
            <th width="70">Amount</th>
            <th width="80">Remark</th>
            <!-- <th width="80">Status</th> -->
			<!-- <?php if(IsReadonlyPage() == false && ($_SESSION['role'] == ROLE_SUPER_ADMIN || $_SESSION['role'] == ROLE_ADMIN ||$_SESSION['role'] == ROLE_MANAGER || $_SESSION['role']==ROLE_ACCOUNTANT )){?>
            <th width="50">Edit</th> -->
           
            <?php } ?>
        </tr>
		</thead>
		<tbody>
        <?php 
		foreach($res as $k => $v)
		{ 
			//  echo "ID" .$res[$k]['unit_no'];
			//  echo "ID" .$res[$k]['tenant_id']; 
			?>

        <tr height="25" bgcolor="#BDD8F4" align="center" id="tr_<?php echo $res[$k]['tenant_id']; ?>">
            <td><input type="checkbox" class="chk_select" id="chk_<?php echo $k?>" onClick='depositCheque("<?php echo $res[$k]['tenant_id']?>","<?php echo $res[$k]['wing']?>","<?php echo $res[$k]['unit_id']?>","<?php echo $res[$k]['tenant_name']?>","<?php echo $res[$k]['bank_name']?>","<?php echo $res[$k]['bank_branch']?>","<?php echo $res[$k]['cheque_no']?>","<?php echo getDisplayFormatDate($res[$k]['cheque_date'])?>","<?php echo $res[$k]['amount']?>","<?php echo $res[$k]['remark']?>",this);'/></td>
        	<td align="center"><?php echo $iCounter++;?></td>
        	<td align="center" id = "wing_id"><?php echo $res[$k]['wing'];?></td>
            <td align="center" id = "unit_no"><?php echo $res[$k]['unit_no'];?></td>
            <td align="center" id = "tenant_name"><?php echo $res[$k]['tenant_name'];?></td>
			<td align="center" id = "bank_name"><?php echo $res[$k]['bank_name'];?></td>
        	<td align="center" id = "cheque_no"><?php echo $res[$k]['cheque_no'];?></td>
            <td align="center" id = "cheque_date"><?php echo getDisplayFormatDate($res[$k]['cheque_date']);?></td>
            <td align="center" id = "amount"><?php echo $res[$k]['amount'];?></td>
            <td align="center" id = "remark"><?php echo $res[$k]['remark'];?> </td> 
        </tr>
        <?php }?>
		</tbody>
        </table>
        <?php	
		}
		else
		{
			?>
            <table align="center" border="0">
            <tr>
            	<td><font color="#FF0000" size="2"><b>Please Select a Society.</b></font></td>
            </tr>
            </table>
            <?php	
		}
	}

	function insertData($data){
		$ChequeLeafBook=array();
        $dataid=0;
		$DepositeID=0;

        $banksSQL = "SELECT ledger.id AS BankID, ledger.ledger_name as BankName FROM ledger JOIN bank_master ON ledger.id = bank_master.BankID";
		$banks = $this->m_dbConn->select($banksSQL);
        $bankID= 67;
        $bankNames = array();

        $Counter = $this->obj_utility->GetCounter(VOUCHER_RECEIPT, $bankID,false);
        $vNo = $Counter[0]['CurrentCounter'];

        $systemVoucherNo = $vNo;

        // for ($i = 0; $i < count($banks); $i++)
		// {
		// 	$bankID[$i] = $banks[$i]['BankID'];
		// 	$bankNames[$i] = $banks[$i]['BankName'];
		// }
		// echo "<pre>";
		// print_r($data);
		// echo "</pre>";
		$desc = 'DATA IMPORTED'.date('Y-m-d H:i:sa');
		$queryII = "select `society_creation_yearid` FROM `society` where `society_id` = '".$_SESSION['society_id']."'";
		$resII = $this->m_dbConn->select($queryII);
								  
		$insert_query1="insert into depositgroup (`bankid`,`createby`,`depositedby`,`status`,`desc`,`DepositSlipCreatedYearID`) values ('".$bankID."','".$_SESSION['login_id']."','PDC','0','".$desc."','".$resII[0]['society_creation_yearid']."')";
		$dataid = $this->m_dbConn->insert($insert_query1);
		$DepositeID=$dataid;
		foreach($data as $k => $v)
		{
            $voucherDate = $data[$k]['cheque_date'];
            $cheque_no = $data[$k]['cheque_no'];
            $cheque_date = $data[$k]['cheque_date'];
			echo "Date" .getDBFormatDate($cheque_date);
            $amount = $data[$k]['amount'];
            $tenant_id = $data[$k]['tenant_id'];
            $bank_name = $data[$k]['bank_name'];
            $bank_branch = $data[$k]['bank_branch'];
            $remark = $data[$k]['remark']; 
            $this->obj_ChequeDetails->AddNewValues3($voucherDate,$cheque_date,$cheque_no,$vNo,$systemVoucherNo,1,$amount,$tenant_id,$bankID,$bank_name,$bank_branch,$DepositeID,$remark,2,0,0,0,0,0,false,'',0,0,0);	
		    // $ErrorLog = $obj_pdc_list->errorLog;
            // echo $wing;
        }
	}

}
?>
<script>
	// $(document).on('click', '.chk_select', function() {

	// ($('.chk_select:checked').length == 0) ? $('.multiDeleteDiv').hide(): $('.multiDeleteDiv').show();
	// });

	// $(document).on('click', '.chk_select_all', function() {

	// if ($('.chk_select_all').is(':checked')) {
	// 	//console.log("test");
	// 	$('.chk_select').prop('checked', true);
	// 	let count = $('.chk_select:checked').length;
	// 	//console.log(count);
	// 	$('.multiDeleteDiv').show();
	// } else {
	// 	$('.chk_select').prop('checked', false);
	// 	$('.multiDeleteDiv').hide();
	// }
	// });

	var Data_arr = [];
	function depositCheque(tid,wid,uid,tname,bname,branch,cheq_no,cheq_date,amount,remark){
		// console.log("wid" +wid+"uid" +uid+"tname " +tname);
		if($('.chk_select:checked')){
			Data_arr.push({"tenant_id":tid, "wing_id":wid, "unit_id":uid, "tenant_name":tname, "bank_name":bname,"bank_branch":branch, "cheque_no":cheq_no, "cheque_date":cheq_date, "amount":amount, "remark":remark});
			console.log(Data_arr);
		}
		else{
			var value = ({"tenant_id":tid,"wing_id":wid, "unit_id":uid, "tenant_name":tname, "bank_name":bname, "cheque_no":cheq_no, "cheque_date":cheq_date, "amount":amount, "remark":remark});
			index = Data_arr.findIndex(x => x.Cnt === cnt);
			console.log("id " +index);
		}
	}

	function sendData(){
		// console.log("clicked");
		// let details = JSON.stringify(Data_arr);
		// console.log(details);
		$.ajax({
		url: "process/pdc_list.process.php",
		type:"POST",
		data: {'chequeData': Data_arr},
		success: function(data)
		{
			alert("Success");
		}
	});	
	}

</script>