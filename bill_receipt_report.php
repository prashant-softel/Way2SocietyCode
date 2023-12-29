<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<title>W2S - Bill Reciept Report</title>
</head>


<?php include_once("includes/head_s.php");
include_once ("classes/dbconst.class.php"); 
include_once("classes/bill_receipt_report.class.php");
include_once("classes/initialize.class.php");
$obj_brr = new bill_receipt_report($m_dbConn,$landLordDB);
$obj_initialize = new initialize($m_dbConnRoot);
include_once("classes/wing.class.php");
//$obj_wing = new wing($m_dbConn);

?>
 
<html>
<head>
	<style>
		#report_table
		{
			border: solid 1px black !important;
			text-align:center;
			border-collapse:collapse;
		}
		#report_table th
		{
			text-align:center;
			border: solid 1px black !important;
			vertical-align:middle;
		}
		#report_table td
		{
			border: solid 1px black !important;
			vertical-align:middle;
		}
	</style>
	<title>Bill - Receipt Report</title>
	<link rel="stylesheet" type="text/css" href="css/pagination.css" >
	<link href="css/messagebox.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="js/ajax_new.js"></script>
    <script type="text/javascript" src="js/populateData.js"></script>
	<script type="text/javascript" src="js/jsbillmaster.js"></script>
    <script type="text/javascript" src="js/validate.js"></script>
    <script lang="javascript" src="js/FileSaver.js"></script>
	<script lang="javascript" src="js/xlsx.full.min.js"></script>
    <script language="javascript" type="application/javascript">
	
	function go_error()
    {
		hideLoader();
        setTimeout('hide_error()',3000);	
    }
	
    function hide_error()
    {
		document.getElementById('error').innerHTML = '';
        document.getElementById('error').style.display = 'none';	
    }
	
	function ExportToExcel()
	{
		var fileName = document.getElementById('fileName').innerHTML;
		ExportExcel('show_table', fileName); // this fucntion is written under FileSaver.js 
		//window.open('data:application/vnd.ms-excel,' + encodeURIComponent( $("#show_table").html()));
	}
	
	function Export_Print()
	{		
		//var html = document.getElementById('show_table').innerHTML;
		//var print_div = document.getElementById('for_printing');
		//print_div.innerHTML = html;
				
		var mywindow = window.open('', 'PRINT', 'height=600,width=800');

	    mywindow.document.write('<html><head><title>Bill - Receipt Report</title>');
    	mywindow.document.write('</head><body>');
		//mywindow.document.write(document.getElementById('head_for_printing').innerHTML);
    	mywindow.document.write(document.getElementById('show_table').innerHTML);
	    mywindow.document.write('</body></html>');

    	mywindow.document.close(); // necessary for IE >= 10
	    mywindow.focus(); // necessary for IE >= 10

    	mywindow.print();
	    mywindow.close();
		
		return false;
		/*var originalContents = document.body.innerHTML;
		var printContents = document.getElementById('show_table').innerHTML;
		
		document.body.innerHTML = printContents;
		window.print();
	
		document.body.innerHTML= originalContents;*/
	}
	
	function Export_PDF()
	{
		var originalContents = document.body.innerHTML;
		//$('#mainDiv').find('.btn-report').remove();
		
		//$('#mainDiv').find('.no-print').remove();
		//$("td").find('a').contents().unwrap();
		var sData = document.getElementById('show_table').innerHTML;
	
		var sHeader = '<html><head>';
		sHeader += '<title>Bill - Receipt Report</title>';	
		sHeader +=	'</head><body><center>   ';
		
		var sFooter =  '</center></body></html>';
		
		sData = sHeader + sData + sFooter;
		document.body.innerHTML= originalContents;
		document.getElementById("data").value =sData; 
		document.getElementById("myForm").submit();
	}

	$(document).ready(function(){
	var socID = '<?php echo $_SESSION['landLordSocID']; ?>' ;
	if(socID) {
		document.getElementById('mapid').value = socID;
	}
//console.log("demo3");
});


function selectDB(){
		let dbname = document.getElementById('mapid').value;
		console.log(dbname);
		$.ajax({
		url: "process/list_member.process.php",
		type:"POST",
		data: {'selSocID':dbname},
		success: function(data)
		{
			location.reload();
		}
	});
	}

	</script>
    <style>
		input{box-shadow:none;}
	</style>
</head>

<br>
<div id="middle">
<div class="panel panel-info" id="panel" style="display:none">
        <div class="panel-heading" id="pageheader">Member - Receipt Report</div>        

<?php
$period_id = "0";
$bill_type = "0";
foreach($_POST as $key => $value)
{
	if(isset($_POST['period_id']))
	{
		$period_id = $_POST['period_id'];
	}
	if(isset($_POST['bill_method']))
	{
		$bill_type = $_POST['bill_method'];
	}
} ?>

<center>
<form  action="common_pdf.php" method="post" id="myForm" class="no-print">
	 <input type="hidden" name="data"  id="data"/>
     <input type="hidden" id="landscape" name="landscape" value="1">
</form>
<form name="unit" id="unit" method="post" action="process/bill_receipt_report.process.php" <?php echo $val;?> >
	<?php
		$star = "<font color='#FF0000'>*</font>";
		/*if(isset($_REQUEST['msg']))
		{
			$msg = "Sorry !!! You can't delete it. ( Dependency )";
		}
		else if(isset($_REQUEST['msg1']))
		{
			$msg = "Record Deleted Successfully.";
		}
		else
		{
			//$msg = '';	
		}*/
	?>
    <table align='center' style="margin-top: 30px;">
		<tr height="30"><td colspan="4" align="center"><font color="red" style="size:11px;"><b id="error"><?php //echo $_REQUEST["ShowData"]; ?></b></font></td></tr>	        
        <input type="hidden" name="society_id" id="society_id" value="<?php echo $_SESSION['society_id'];?>">
		<?php if($_SESSION['res_flag'] == 1){ ?>
				<tr align="left">
					<td valign="middle"></td>
					<td>LandLords</td>
					<td>&nbsp; : &nbsp;</td>
					<td>
					<select id="mapid" name="mapid" style="width:142px;" onChange= "selectDB(this.value);" value="<?php echo $_REQUEST['mapid']; ?>">
						<?php  echo $mapList = $obj_initialize->combobox("Select societytbl.society_id, concat_ws(' - ',societytbl.society_id, societytbl.society_name) from mapping as maptbl JOIN society as societytbl ON maptbl.society_id = societytbl.society_id JOIN dbname as db ON db.society_id = societytbl.society_id WHERE maptbl.login_id = '" . $_SESSION['login_id'] . "' and societytbl.rental_flag = 1 and societytbl.status = 'Y' and maptbl.status = '2' ORDER BY societytbl.society_name ASC ", $_SESSION['current_mapping']);?>		
						<input type="hidden" name="mode" value="set" />
					</select>
					</td>
				</tr>
			<?php } ?>
		<tr align="left">
        	<td valign="middle"><?php //echo $star;?></td>
			<td>Wing</td>
            <td>&nbsp; : &nbsp;</td>
			<td>
                <select name="wing_id" id="wing_id" style="width:142px;">
                	<?php 
					if($_SESSION['res_flag'] == 1){
						echo $combo_wing = $obj_brr->combobox("SELECT `wing_id`,`wing` FROM `wing` WHERE `society_id` = '".$_SESSION['landLordSocID']."'",$_POST['wing_id'],"All");
					}else{
						echo $combo_wing = $obj_brr->combobox("SELECT `wing_id`,`wing` FROM `wing` WHERE `society_id` = '".$_SESSION['society_id']."'",$_POST['wing_id'],"All");
					}?>
				</select>
            </td>
		</tr>
              
        <tr align="left">
        	<td valign="middle"><?php if(isset($_GET['ws'])){echo $star;}?></td>
			<td>Bill Year </td>
            <td>&nbsp; : &nbsp;</td>
			<td>
            	<select name="year_id" id="year_id" style="width:142px;" onChange="get_period(this.value, <?php echo DEFAULT_PERIOD; ?>, 'period_id');">
                	<?php echo $combo_state = $obj_brr->combobox("select YearID,YearDescription from year where status='Y' and YearID = '" . $_SESSION['default_year'] . "' ORDER BY YearID DESC", DEFAULT_YEAR,"",""); ?>
				</select>
            </td>
		</tr>        
        <tr align="left">
        	<td valign="middle"><?php if(isset($_GET['ws'])){echo $star;}?></td>
			<td>Bill For </td>
            <td>&nbsp; : &nbsp;</td>
			<td>
               <select name="period_id" id="period_id" style="width:142px;">
                	<?php echo $combo_state = $obj_brr->combobox("select ID, Type from period  where  status='Y' and YearID = '" . DEFAULT_YEAR . "'","0","Please Select"); ?>  
                </select>
            </td>
		</tr>
        
        
        <tr align="left"  <?php //if ($_SESSION['society_id'] <> '32' && $_SESSION['society_id'] == '59'){ echo 'style="visibility:hidden;"'; } ?>>
        	<td valign="middle"></td>
			<td>Bill Type</td>
            <td>&nbsp; : &nbsp;</td>
			<td><select name="bill_method" id="bill_method" style="width:142px;" >
            		<OPTION VALUE="<?php echo BILL_TYPE_REGULAR; ?>">Regular Bill</OPTION>
                    <OPTION VALUE="<?php echo BILL_TYPE_SUPPLEMENTARY; ?>">Supplementary Bill</OPTION>
                </select>
            </td>
		</tr>
	
		<tr><td colspan="4">&nbsp;</td></tr>
				
        <tr>
			<td colspan="4" align="center">
			<?php if($_SESSION['res_flag'] == 1){  
			if($_SESSION['landLordDB'] <> ''){?>
				<input type="submit" name="insert" id="insert" value="Fetch Details" style="width:100px; background-color:#286090; color:#fff" class="btn btn-primary" />
			<?php }else{ ?>
				<input type="submit" name="insert" id="insert" disabled value="Fetch Details" style="width:100px; background-color:#286090; color:#fff" class="btn btn-primary" />
			<?php } 
			}else{ ?>
				<input type="submit" name="insert" id="insert" value="Fetch Details" style="width:100px; background-color:#286090; color:#fff" class="btn btn-primary" />
			<?php } ?>
            </td>
		</tr>
</table>
</form>
<br><br>

<?php
if($_POST['wing_id'] <> '')
{
	?>
    	<input type="button" id="Excel" name="Excel" value="Export To Excel" onClick="ExportToExcel();" class="btn btn-primary"/>
		<input type="button" id="PrintTable" name="PrintTable" value="Print" onClick="Export_Print();" class="btn btn-primary"/>
        <input type="button" id="PDF" name="PDF" value="Export to PDF" onClick="Export_PDF();" class="btn btn-primary" />
	<?php
}
?>
<br><br>
<div id="show_table" >
<?php
if($_POST['wing_id'] <> '')
{
	if($_SESSION['res_flag'] == 1 || $_SESSION['rental_flag'] == 1){
		echo $obj_brr->get_report_res($_POST['wing_id'],$_POST['year_id'],$period_id,$bill_type); 
	}else{
		echo $obj_brr->get_report($_POST['wing_id'],$_POST['year_id'],$period_id,$bill_type); 
	}
}
else if($_REQUEST['Dashboard'] == 1)
{
	if($_SESSION['res_flag'] == 1 || $_SESSION['rental_flag'] == 1){
		echo $obj_brr->get_report_res(0,$_SESSION['default_year'],$_REQUEST['period_id'],0);
	}else{
		echo $obj_brr->get_report(0,$_SESSION['default_year'],$_REQUEST['period_id'],0);
	}
}
?>
</div>
<br><br>
<?php
if($_POST['wing_id'] <> '')
{
	?>
    	<input type="button" id="Excel" name="Excel" value="Export To Excel" onClick="ExportToExcel();" class="btn btn-primary"/>
		<input type="button" id="PrintTable" name="PrintTable" value="Print" onClick="Export_Print();" class="btn btn-primary"/>
        <input type="button" id="PDF" name="PDF" value="Export to PDF" onClick="Export_PDF();" class="btn btn-primary" />
	<?php
}
?>
<br /><br/>
</center>
</div>
</div>
<script>
	get_period(document.getElementById('year_id').value, "<?php echo $obj_brr->getCurrentPeriod(); ?>", 'period_id');
	$( document ).ready(function() {
		<?php if($period_id != 0 && $period_id != "")
		{
			?>
		    document.getElementById('period_id').value = '<?php echo $period_id; ?>';
		<?php
		}
		if($bill_type != "0" && $bill_type != "")
		{
			?>
			document.getElementById('bill_method').value = '<?php echo $bill_type; ?>';
		<?php
		}
		?>
});
</script>
<?php include_once "includes/foot.php"; ?>
