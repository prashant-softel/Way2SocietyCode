<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<title>W2S - Manage Masters</title>
</head>

<?php include_once("includes/head_s.php");
//include_once("header.php");


include_once("classes/dbconst.class.php");

include_once("classes/home_s.class.php");
$dbConnRoot =new dbop(true);
$dbConn = new dbop();
$obj_home = new CAdminPanel($dbConn,$dbConnRoot);
$result = $obj_home->GetSecurityDB($_SESSION['society_id']);

if(isset($_GET['ssid'])){if($_GET['ssid']<>$_SESSION['society_id']){?><script>window.location.href = "logout.php";</script><?php }}

include_once("classes/bill_period.class.php");
$obj_bill_period = new bill_period($m_dbConn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$_SESSION['year'] = $_REQUEST['year'];
	$_SESSION['period'] = $_REQUEST['period'];
}

//echo $_SESSION['default_year'].	$_SESSION['default_period'] . $_SESSION['default_interest_on_principle'] . $_SESSION['default_current_asset'] .
			//$_SESSION['default_bank_account'] . $_SESSION['default_due_from_member'] .	$_SESSION['society_id'];
?>
 
<html>
<head>
	<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="js/ajax_new.js"></script>
    <script type="text/javascript" src="js/populateData.js"></script>
    <script type="text/javascript"></script>
    <script language="javascript" type="application/javascript">
	
	function go_error()
    {
        setTimeout('hide_error()',3000);	
    }
	
    function hide_error()
    {
		document.getElementById('error').innerHTML = '';
        document.getElementById('error').style.display = 'none';	
    }
	
	function get_period(year_id)
	{
		if(year_id == null)
		{
			populateDDListAndTrigger('select#period', 'ajax/get_unit.php?getperiod&year=' + document.getElementById('year').value, 'period', 'hide_error', false);
		}
		else
		{
			populateDDListAndTrigger('select#period', 'ajax/get_unit.php?getperiod&year=' + year_id, 'period', 'hide_error', false);
		}
	}	

	</script>
    
    <style>
		#block_head{
			background:#337ab7;
			font-family:Verdana, Geneva, sans-serif;
			color:#FFFFFF;
			font-weight:bold;
			font-size:16px;
			padding:5px;
			width:100%;
			text-align:center;
		}
		#block_data{
			
			font-family:Verdana, Geneva, sans-serif;
			color:#000000;
			font-size:12px;
			text-align:center;
			width:100%;
			padding-top:2px;
			padding-bottom::2px;
			border:none;
		}
		#data_link{
			font-family:Verdana, Geneva, sans-serif;
			text-align:center;
			width:100%;
		}
	</style>
</head>

<?php if(isset($_REQUEST['ShowData']) || isset($_REQUEST['msg']) || isset($_REQUEST['msg1'])){ ?>
<body onLoad="go_error();">
<?php }else{ ?>
<body>
<?php } ?>

<br>
<div id="middle">
<div class="panel panel-default">
<div class="panel-heading" id="pageheader">Manage Masters</div>


<?php if(!isset($_REQUEST['ws'])){ $val ='';?>
<!--
<br>
<center>
<a href="society_view.php?imp" style="color:#00F; text-decoration:none;"><b>Add Unit</b></a>
</center>
-->
<?php }else{ $val = 'onSubmit="return val();"';
?>
<br>
<center>
<a href="wing.php?imp&ssid=<?php echo $_REQUEST['ssid'];?>&s&idd=<?php echo time();?>" style="color:#00F; text-decoration:none;"><b>Back</b></a>
<?php } ?>
<br><br>
<!--<table style="width:100%;">
<tr>
	<td style="width:50%;"><div id="block_head">Import Data</div></td>
    <td style="width:50%;"><div id="block_head">Import Data</div></td>
	
    
</tr>

<tr>
	<td>
    	<div>
        <a href="#" id="data_link"></a><br>
      	<button type="button"  style="border:none;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='import_payments_receipts.php?type=payment'">Import Payment Register</button>
        <button type="button"  style="border:none;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='import_payments_receipts.php?type=receipts'">Import General Receipt Register</button>
        <?php //if($_SESSION['society_client_id'] <> 1 && $_SESSION['society_client_id'] <> 9)
		// {
			?>
        <button type="button"  style="border:none;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='import_opening_balance.php'">Member Opening Balance</button>
            <?php
		 //}
		 ?>
         <?php //if($_SESSION['society_client_id'] == 2 || $_SESSION['society_client_id'] == 8)
		 //{
			?> 
         	<button type="button"  style="border:none;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='import_fixed_deposit.php'">Import Fixed Deposits</button>
            <?php
		 //}
		 ?>
        <!--<a href="rev_import.php" id="data_link">Reverse Import</a><br><br>-->
        <!--<a href="#" id="data_link"></a><br>
        </div>
    </td>
    
</tr>
</table>-->

<table style="width:100%;">
<tr>
	<td style="width:50%;"><div id="block_head">Manage Accounting Items</div></td>
	<td style="width:50%;"><div id="block_head">Manage Society and Members</div></td>
</tr>
<tr>
	<td><div id="block_data">
<!--<button type="button"  style="border:none" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='group.php'">Manage Account Groups</button>-->
        <button type="button"  style="border:none" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href=' account_category.php'">Manage Categories</button>
        <button type="button"  style="border:none" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='ledger.php'">Manage Ledgers</button>
        <button type="button"  style="border:none" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='BankDetails.php'">Manage Banks</button>
        <button type="button"  style="border:none" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='FixedDeposit.php'">Manage Fixed Deposits</button>
        <?php if($_SESSION['society_id'] == 149 || $_SESSION['society_id'] == 59) 
				{	?>
        <button type="button"  style="border:none" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='FixedAssetMgmt.php'">Manage Fixed Assets</button>
        <?php } if($_SESSION['role'] == ROLE_SUPER_ADMIN){ ?>
        <button type="button"  style="border:none" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='voucherCounter.php'">Manage Voucher Counter </button>
       <?php }?>
        </div>
    </td>
    <td>
    	<div id="block_data">
        <button type="button"  style="border:none" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='society.php?id=<?php echo $_SESSION['society_id'];?>&show&imp'">Manage Society</button>
       
        <button type="button"  style="border:none" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='wing.php'">Manage Wings</button>
        
        <button type="button"  style="border:none" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='unit.php'">Manage Units and Members</button>
        
        <button type="button"  style="border:none" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='commitee.php'">Manage Committee</button>
        
        
       <?php if($_SESSION['society_id'] ==59 || $_SESSION['society_id'] == 149 || $_SESSION["society_id"] == '156')
				{?>
        <button type="button"  style="border:none" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='momGroup.php'">Manage Resident Groups</button>
        <?php }?>
		<button type="button"  style="border:none" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='module.php'">Manage Module Access</button>
        		<?php if($_SESSION['society_id'] ==59 || $_SESSION['society_id'] == 149)
				{?>
                <button type="button"  style="border:none" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='document_manager.php?type=submitting'">Manage Nomination Form</button>
                <?php 
				}
				?>
         <div style="width: 55%; float:left" >        
        	<a href="#" id="data_link"></a><br>
      		<button type="button"  style="border:none; width: 85%;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.open('viewParkingType.php', '_blank')" >Manage Parking Type</button>
        </div>
        <div class="vl" style="border-left: 3px solid #80808069;height: 40px;width: 1%;float: left;margin-top: 25px;     margin-left: -25px;"></div>
      	<div style="width: 40%; float:left">
        	<a href="#" id="data_link"></a><br>
      		<button type="button"  style="border:none; width: 110%;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.open('updateParkingType.php', '_blank')" >Manage Parking</button>
        </div>
	    </div>
    </td>
</tr>
</table>
<table style="width:100%;">
<tr>
	<td style="width:50%;"><div id="block_head">Manage Documents</div></td>
	<td style="width:50%;"><div id="block_head">Manage Billing</div></td>
</tr>
<tr>
	<td>
    	<div id="block_data">
      	<button type="button"  style="border:none;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='cat.php'">Service Provider Category</button>
	    <button type="button"  style="border:none;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='desg.php'">Designations</button>
        <button type="button"  style="border:none;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='servicerequest_master.php'">Service Request Category</button>
        <a href="#" id="data_link"></a>        
        <button type="button"  style="border:none;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='serviceRequestSetting.php'">Service Request Setting</button>
        <a href="#" id="data_link"></a>
        </div>
    </td>
    <td>
    	<div id="block_data">
        <?php if($_SESSION['res_flag'] == 1 || $_SESSION['rental_flag'] == 1)
		{ ?>
        <button type="button"  style="border:none;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='billmaster_res.php'">Manage Bill (Tariff) Master</button>
	    <?php }
		else
		{?>
		<button type="button"  style="border:none;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='billmaster.php'">Manage Bill (Tariff) Master</button>
		<?php }?>
        <button type="button"  style="border:none;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='bill_year.php'">Manage Financial Year</button>
       	<button type="button"  style="border:none;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='bill_period.php'">Manage Bill Periods</button>
       
        <button type="button"  style="border:none;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='viewSMSQuota.php'">Manage SMS</button>
	   
        </div>
    </td>
</tr>
</table>

<table style="width:100%;">
<tr>
	<td style="width:50%;"><div id="block_head">Manage Units</div></td>
    <td style="width:50%;"><div id="block_head">Groups And Gallery</div></td>
</tr>
<tr>
	<td>
    	<div id="block_data">        
        <button type="button"  style="border:none;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='add_sharecertificate.php'">Add/Update Share Certificate</button>
      	<button type="button"  style="border:none;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='unit_sorting.php'">Sort Units/Update Wing</button>
            <button type="button"  style="border:none;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='ChangeLog.php'">Change Log History</button>
        <a href="#" id="data_link"></a><br>
        </div>
    </td>
    <td>
    	<div id="block_data">        
        <button type="button"  style="border:none;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='gallery_group.php'">Manage Society Groups</button>
         <a href="#" id="data_link"></a>
          </div>
    	<div id="block_data">        
        <button type="button"  style="border:none;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='gallery_upload.php'">Create Album/Upload images</button>
         <a href="#" id="data_link"></a><br>
        </div>
    </td>
</tr>
</table>
<!--<table style="width:100%;">
<tr>
	<td><div id="block_head">Groups And Gallery</div></td>
    <tr>
	<td>
    	<div id="block_data">        
        <button type="button"  style="border:none;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='gallery_group.php'">Manage Groups</button>
         <a href="#" id="data_link"></a><br>
        </div>
    </td>
</tr>

<tr>
	<td>
    	<div id="block_data">        
        <button type="button"  style="border:none;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='gallery_upload.php'">Create Album/Upload images</button>
         <a href="#" id="data_link"></a><br>
        </div>
    </td>
</tr>
</tr>
</table>
-->
<!--<table style="width:100%;">
<tr>
	<td><div id="block_head">Miscellaneous</div></td>
</tr>
<tr>
	<td>
    	<div id="block_data">        
        <a href="#" id="data_link"></a><br>
      	<button type="button"  style="border:none;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.location.href='defaults.php'">Set Defaults</button>
                <a href="#" id="data_link"></a><br>
        </div>
    </td>
</tr>
</table>
-->
<table style="width:100%;">
<tr>

	<td style="width:50%;"><div id="block_head">Validation</div></td>
     <?php if($result[0]['security_dbname'] <> '')
	 {?>
	
    <td style="width:50%;"><div id="block_head">Security Manager Setting</div></td>
    	<?php } ?>
	



    <td style="width:50%;"></td>
</tr>
<tr>

	<td>
    <div id="block_data">
    	<div style="width: 55%; float:left" >        
        <a href="#" id="data_link"></a><br>
      	<button type="button"  style="border:none; width: 85%;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.open('billDetailsValidation.php', '_blank')" >Bill Detail Validation</button>
        <a href="#" id="data_link"></a>
        
        <a href="#" id="data_link"></a>
      	<button type="button"  style="border:none; width: 85%;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.open('RegistersValidation.php', '_blank')" >All Registers Validation</button>
        <a href="#" id="data_link"></a>
        <button type="button"  style="border:none; width: 85%;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.open('voucherValidation.php', '_blank')" >All Voucher Validation</button>
        
        </div>
        <div class="vl" style="border-left: 3px solid #80808069;height: 80px;width: 1%;float: left;margin-top: 25px;     margin-left: -25px;"></div>
      
        <div style="width: 40%; float:left">
         <a href="#" id="data_link"></a><br>
      	<button type="button"  style="border:none; width: 110%;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.open('repaireinvoice.php?&Rvoucher=1', '_blank')" >Validate GST Invoices</button>
        <a href="#" id="data_link"></a>
        
         <a href="#" id="data_link"></a>
         <?php if($_SESSION['login_id']==4){?>
        <button type="button"  style="border:none; width: 110%;" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.open('repaireinvoice.php?&Rvoucher=0', '_blank')" >Repair GST Invoices</button>
        <?php }?>
        <a href="#" id="data_link"></a>
        </div>
        </div>
        
    </td>
    <?php if($result[0]['security_dbname'] <> '')
	 {?>
    <td>
		<a href="#" id="data_link"></a><br>
    <button type="button"  style="border:none" class="btn btn-outline btn-primary btn-lg btn-block" onClick="window.open('OTP_Setting.php', '_blank')">Visitor OTP Settings</button>

		</td>
        <?php } ?>
</tr>
	
</table>
</center>
</div>
</div>
<?php include_once "includes/foot.php"; ?>
