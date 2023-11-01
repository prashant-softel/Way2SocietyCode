<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<title>W2S - Service Request Details</title>
</head>

<?php 
include_once("includes/head_s.php"); 
include_once "classes/include/dbop.class.php";
include_once("classes/servicerequest.class.php");
include_once ("classes/include/fetch_data.php");
include_once ("dbconst.class.php"); 
$dbConn = new dbop();
$dbConnRoot = new dbop(true);
$objFetchData = new FetchData($dbConn);
$objFetchData->GetSocietyDetails($_SESSION['society_id']);
$obj_request = new servicerequest($dbConn);
$LoginIDQuery = "select `member_id` from `login` where `login_id`='".$_SESSION['login_id']."'";
$loginID = $dbConnRoot->select($LoginIDQuery);
//$details = $obj_request->getViewDetails($_REQUEST['rq']);
//
$cnt=0;
$SREmailIDs = array(); 
$strSREMailIDs = "";
if(isset($_REQUEST['rq']))
{
	if($_REQUEST['rq']<>"")
	{
		$details = $obj_request->getViewDetails($_REQUEST['rq'],true);
		
		//for($i=0;$i <= sizeof($edit)-1; $i++)
			//{ 
			$image=$details[0]['img'];
			$image_collection = explode(',', $image);	
			//print_r(sizeof($details));
			for($i=0;$i < sizeof($details); $i++)
			{
				//echo $details[$i]['email'];
				if(isset($details[$i]['email']) && $details[$i]['email'] != "")
				{
					array_push($SREmailIDs , $details[$i]['email']);
				}
			}
			//var_dump($SREmailIDs);
			if(sizeof($details) > 0)
			{
				$strSREMailIDs = implode(";", $SREmailIDs);
			}
			
			$histortdetails = array_reverse($obj_request->getViewDetails1($_REQUEST['rq'],true));
	}
	}
?>
<!--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Service Request Details</title>
</head>-->

<div class="panel panel-info" id="panel" style="margin-top:4%;margin-left:1%; width:76%;">
<div class="panel-heading" id="pageheader" style="font-size:20px">
    Legal Case Details
    </div>
    <br />
<script type="text/javascript" src="lib/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="lib/source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="lib/source/jquery.fancybox.css?v=2.1.5" media="screen" />
<link rel="stylesheet" type="text/css" href="lib/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
<script type="text/javascript" src="lib/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
<script type="text/javascript" src="js/jsServiceRequest_26072018.js"></script>
<script language="javascript" type="application/javascript">
	function go_error()
    {
        setTimeout('hide_error()',10000);	
    }
    function hide_error()
    {
        document.getElementById('error').style.display = 'none';	
    }	
	
	
</script>

<script language="javascript" type="application/javascript">
	function printTable()
	{
		//alert("test23");
	 // document.getElementById('PrintableTable').style.width='80%';
	 
	 //alert("testnew");
	  var divToPrint=document.getElementById('PrintableDiv');
	 document.getElementById('society_name').style.display='block';
	  newWin= window.open("");
	  newWin.document.write('<br><br><center>' + divToPrint.outerHTML + '</center>');
	  newWin.print();
	  newWin.close();
	  //document.getElementById('PrintableTable').style.width='100%';
	   document.getElementById('society_name').style.display='none';
	}
</script>
<script type="text/javascript" src="lib/jquery-1.7.2.min.js"></script>

	<!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="lib/jquery.mousewheel-3.0.6.pack.js"></script>

	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="source/jquery.fancybox.js?v=2.0.6"></script>
	<link rel="stylesheet" type="text/css" href="source/jquery.fancybox.css?v=2.0.6" media="screen" />

	<!-- Add Button helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-buttons.css?v=1.0.2" />
	<script type="text/javascript" src="source/helpers/jquery.fancybox-buttons.js?v=1.0.2"></script>

	<!-- Add Thumbnail helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-thumbs.css?v=1.0.2" />
	<script type="text/javascript" src="source/helpers/jquery.fancybox-thumbs.js?v=1.0.2"></script>

	<!-- Add Media helper (this is optional) -->
	<script type="text/javascript" src="source/helpers/jquery.fancybox-media.js?v=1.0.0"></script>
    
    <script type="text/javascript">
		$(document).ready(function() {			
		
		//Simple image gallery. Uses default settings
		$('.fancybox').fancybox();
		$('.fancybox1').fancybox();
	
	
		//Button helper. Disable animations, hide close button, change title type and content
		$('.fancybox-buttons').fancybox({
		openEffect  : 'none',
		closeEffect : 'none',

		prevEffect : 'none',
		nextEffect : 'none',

		closeBtn  : false,

		helpers : {
			title : {
				type : 'inside'
			},
			buttons	: {}
		},

			afterLoad : function() {
				this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
			}
		});
		});
	</script>
    <script type="text/javascript">
	$(function()
	{
		$.datepicker.setDefaults($.datepicker.regional['']);
		$(".basics").datepicker({ 
		dateFormat: "dd-mm-yy", 
		showOn: "both", 
		buttonImage: "images/calendar.gif", 
		buttonImageOnly: true 
	})});
</script>
	<style type="text/css">
		.fancybox-custom .fancybox-skin 
		{
			box-shadow: 0 0 50px #222;
		}
		.fancybox-close{
			    position: absolute;
    top: -4px !important;
    right: -4px !important;
    width: 36px;
    height: 36px;
    cursor: pointer;
    z-index: 8040;
			}
	</style>

<body>

<!--<div id="bill_header" style="text-align:center; border-style:solid;">
            <div id="society_name" style="font-weight:bold; font-size:18px;"><?php echo $objFetchData->objSocietyDetails->sSocietyName; ?></div>
            <div id="society_reg" style="font-size:14px;"><?php if($objFetchData->objSocietyDetails->sSocietyRegNo <> "")
				{
					echo "Reg. No. ".$objFetchData->objSocietyDetails->sSocietyRegNo;
					echo " ";
					echo "Dated:".$objFetchData->objSocietyDetails->sSocietyRegDate; 
				}
				?>
            </div>
            <div id="society_address"; style="font-size:14px; border-left:thick;"><?php echo 'Reg. Add:'.$objFetchData->objSocietyDetails->sSocietyAddress; ?></div>
</div>-->
<?php //echo "role".$_SESSION['role'];?>
<center><!--<a href="servicerequest.php">Go Back</a>-->
<div style="padding-left: 15px;padding-bottom: 10px;">
		 <center> <INPUT TYPE="button" id="Print" onClick="printTable()" name="Print" value="Print"   class="btn btn-primary"></center></div>
</center>


<br>
<br>
<?php 
//print_r($details);
if($details <> "")
	  { ?>
<div width="100%" style="font-size:12px;" id="PrintableDiv"  >
<div id="society_name" style="font-weight:bold; font-size:18px; display:none;"><?php echo $objFetchData->objSocietyDetails->sSocietyName; ?></div>
   
<table width="100%" style="font-size:12px;" id="PrintableTable">

	<tr style="background-color:#bce8f1;font-size:12px;" height="30">
        <th style="width:10%;"><center>Building No.</center></th>
        <th style="width:10%;"><center>Flat No.</center></th>
        <th style="width:20%;"><center>Tenant Name</center></th>
        <th style="width:20%;"><center>Created Date</center></th>
        <th style="width:10%;"><center>Status</center></th>
        <th style="width:20%;"><center>Category</center></th>
         <th style="width:20%;"><center>Outstanding Amount</center></th>
        <th style="width:20%;"><center>Judgment Amount</center></th>

        <!--<th style="width:20%;">Photo</th>-->
    </tr>
    <tr>
    <?php $timestamp = strtotime($details[0]['timestamp']);
	
		//$sql = "SELECT `img` FROM `service_request` WHERE `request_id` = '".$_SESSION['unit_id']."' AND `society_id` = '".$_SESSION['society_id']."'";
		//$result = $this->m_dbConn->select($sql);
		$CategoryDetails = $obj_request->GetCategoryDetails( $details[0]['category']);
		//var_dump($CategoryDetails);
		$MemberName = $obj_request->GetMemberName( $details[0]['category']);
		
		$UnitNo = $obj_request->GetUnitNoIfZero( $_REQUEST['rq']);
		$GotUnitNo = $obj_request->GetUnitNoIfNZero( $_REQUEST['rq']);
		$MemName = $obj_request->getmemDetails($GotUnitNo[0]['unit_id']);
		$latestStatus = $obj_request->getLatestStatus($_REQUEST['rq']);
		if($UnitNo[0]['unit_id'] == 0)
		{
			$show = " - ";
		}
		else
		{			
			$show = $GotUnitNo[0]['unit_no'];
		}
		$totalAmt = 0;
		if($latestStatus[0]['status'] == 'Case Closed')
		{
			$ExpenseAmountSum = $obj_request->getTotalExpense($_REQUEST['rq']);
			$totalAmt = $details[0]['outstanding_rent']+$ExpenseAmountSum;
		}
		//else
		//{
			//$totalAmt = $details[0]['outstanding_rent'];
		//}
	?>
    	
    	<td align="center"><?php echo $_REQUEST['rq'];?></td>
        <td align="center"><?php echo $show;?></td>
        <td align="center"><?php echo $MemName;?></td>
        <td align="center"><?php echo $details[0]['raisedDate'];?></td>
        <td align="center"><?php echo $latestStatus[0]['status'];?></td>
        
        <td align="center"><?php echo $CategoryDetails[0]['category'];?></td>
        <td align="center"><?php echo $details[0]['outstanding_rent'];?></td>
         <td align="center"><?php echo $totalAmt;?></td>
  
    </tr> 
    <tr><td colspan="10"><br /></td></tr>   
    <tr style="background-color:#bce8f1;font-size:14px;"  height="25">
    	<th colspan="10"  align="left"> Title</th>
   	</tr>
    <tr>
    	<td colspan="10" align="left"><span style="margin-left:10px; float: left;margin-top: 5px;"><?php echo nl2br(htmlentities($details[0]['summery'], ENT_QUOTES, 'UTF-8'));?></span></td>
    </tr>
    <tr><td colspan="10"><br /></td></tr> 
        
    <tr style="background-color:#bce8f1;font-size:14px;" height="25">
    	<th colspan="10" align="left">Request Description</th>        
    </tr>
    <tr>
    	<td colspan="10" align="left"><span style="margin-left:10px; float: left; margin-top: 5px;">
        <?php 
			/*if($details[0]['category'] == $_SESSION['RENOVATION_DOC_ID'])
	  		{
				echo $url = $details[0]['details']."<a href='document_maker.php?temp=".$details[0]['category']."&rId=".$details[0]['Id']."&action=view' target='_blank'> Click here to view.</a>";
			}
			else if($details[0]['category'] == $_SESSION['ADDRESS_PROOF_ID'])
	  		{
				echo $url = $details[0]['details']."<a href='document_maker.php?temp=".$details[0]['category']."&aId=".$details[0]['request_id']."' target='_blank'> Click here to view.</a>";
			}
			else if($details[0]['category'] == $_SESSION['TENANT_REQUEST_ID'])
	  		{
				echo $url = $details[0]['details']."<a href='document_maker.php?temp=".$details[0]['category']."&tId=".$details[0]['tenant_id']."' target='_blank'> Click here to view.</a>";
			}
			else
			{*/
				echo $details[0]['details'];
			/*}*/
		?>
        </span></td>
    </tr>
     <tr><td colspan="10"><br /></td></tr> 
    <tr style="background-color:#bce8f1;font-size:14px;" height="25">
    	<th colspan="10" align="left">Attachments</th>        
 <tr><td>
 <table width="500%"><tr><td  align="left">
     <?php 
	// print_r($image_collection);
		for($i=0;$i<sizeof($image_collection);$i++)
		{
			if(strlen($image_collection[$i]) >0 )
			{
		?>
        
		<a href="upload/main/<?php echo $image_collection[$i]?>" class="fancybox"><img  style="    width: 100px;
    height: 70px;" src="upload/main/<?php echo $image_collection[$i]?>" ></a>
      <?php
	   }
		}
	  ?>
       </td></tr></table>
       
      </td>
  <!--  <td align="center"><a href="<?php// echo substr($details[0]['img'],3);?>" class="fancybox"><img src="<?php// echo substr($details[0]['img_thumb'],3);?>" height="100" width="100" /></a></td>-->

    </tr>
    <tr><td colspan="10"><br /></td></tr> 
    <tr style="background-color:#bce8f1;font-size:14px;"  height="25">
    	<th colspan="10" align="left">History</th>        
    </tr>
    <tr>
    
    <table cellspacing="0px" style="width:100%"> 
     <tr style="background-color:#988e8e30;font-size:14px;" height="25">
    	<th align="left" style="padding-left: 9px; width: 35%;" >Details</th>
        <th align="left">Case No.</th>
        <th align="center" style="text-align:center" >Up. Hearing Date</th>
        <th align="center"  style="text-align:center" >Expense Amount</th>
        <th align="left">Status</th>
        <!--<th align="left" style="width: 10%;">Timestamp</th>-->
    </tr>
   
    <?php
	//echo "<pre>";
	//print_r($histortdetails);
	//echo "</pre>";
	for($i = 0; $i < sizeof($histortdetails); $i++)
		{
			//echo $timestamp = strtotime($details[$i]['timestamp']);
			//$timestamp = date('d-m-Y (h:i:s A)',strtotime('+5 hour +30 minutes +1 seconds',strtotime($details[$i]['timestamp'])));
		//if($details[$i]['status']=="Reopen")
		//{
			//$cnt=$cnt+1;
		//}
	?>
   
    <tr>
    	<td align="left"  style = "border-bottom:1px solid #988e8e30;padding-left:10px;padding-top:10px"><?php echo $histortdetails[$i]['comment']; ?></td>
        <td align="left"  style="border-bottom:1px solid #988e8e30;padding-top:10px"><?php echo $histortdetails[$i]['case_no']; ?></td>
        <td align="center" style="border-bottom:1px solid #988e8e30;padding-top:10px" ><?php echo getDisplayFormatDate($histortdetails[$i]['up_hearing_date']); ?></td>
        <td align="center" style=" border-bottom:1px solid #988e8e30;padding-top:10px"><?php echo number_format($histortdetails[$i]['expense_amt'], 2); ?></td>
        <td align="left" style="border-bottom:1px solid #988e8e30;padding-top:10px"><?php echo $histortdetails[$i]['status']; ?></td>
        </tr>
       
    <?php
		}
		
	?>
     </table>
    </tr>
    <tr><td colspan="5"><br /></td></tr> 
</table>
</div>
<br />
<form name="viewrequest" id="viewrequest" method="post" action="process/servicerequest.process.php?vr=<?php echo $_REQUEST['rq'];?>" onSubmit="return val();">
<center>
<table style="border:1px solid #CCC; width:85%;font-size:12px; padding:10px; border-radius: 15px;">
<?php
	if(isset($_POST["ShowData"]))
		{
?>
			<tr height="30"><td colspan="6" align="center"><font color="red" style="size:11px;"><b id="error"><?php echo $_POST["ShowData"]; ?></b></font></td></tr>
<?php   }
		else
		{?>
			<tr height="30"><td colspan="6" align="center"><font color="red" style="size:11px;"><b id="error"></b></font></td></tr>
<?php 	} ?>   
	<tr>
		<th>&nbsp; Changed By</th>
        <td >&nbsp; : &nbsp;</td>
        <td ><?php echo $_SESSION['name'];?></td>
        
        <th >&nbsp; Expense Amount</th>
        <td>&nbsp; : &nbsp;</td>
        <td><input type="text" name="expense_amt" id="expense_amt" value="" onKeyPress="return blockNonNumbers(this, event, true, false);" /></td>
    </tr>
    <tr><td colspan="6"><input type="hidden" name="changedby" id="changedby" value="<?php echo $_SESSION['name'];?>" /></td></tr>
     
    <tr><td colspan="6"><br /></td></tr>
    
    <tr>
    	<th >&nbsp; Status</th>
        <td>&nbsp; : &nbsp;</td>
        <td>
        	<select id="status" name="status">
            	<option value="0"> Please Select </option>
	            <option value="Legal State 1" > Legal State 1 </option>
                <option value="Legal State 2"> Legal State 2 </option>
                <option value="Legal State 3"> Legal State 3 </option>
                <option value="Case Closed"> Case Closed </option>
             </select>
        </td>
        <th >&nbsp; Upcomming Hearing Date</th>
        <td>&nbsp; : &nbsp;</td>
        <td><input type="text" name="upcom_date" id="upcom_date" class="basics" readonly /></td>
    </tr>
    <tr><td colspan="6"><br /></td></tr>
	<tr>
        <th >&nbsp; Case No.</th>
        <td>&nbsp; : &nbsp;</td>
        <td><input type="text" name="case_no" id="case_no" value=""/></td>
    </tr>
    <tr><td colspan="6"><br /></td></tr>
    <tr>       
        <th>&nbsp; Comments</th>
        <td>&nbsp; : &nbsp;</td>
        <td colspan="4"><textarea name="comments" id="comments" rows="6" cols="60" ></textarea>
      </td>
	</tr>
     	<script>
			//CKEDITOR.config.height = 100;
			//CKEDITOR.config.width = 500;
			CKEDITOR.config.extraPlugins = 'justify,table';
			//CKEDITOR.config.extraPlugins = 'table';
			CKEDITOR.replace('comments', {toolbar: [
         						{ name: 'clipboard', items: ['Undo', 'Redo']},{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align'], items: [ 'NumberedList', 'BulletedList','JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
        						{name: 'editing', items: ['Format', 'Bold', 'Italic', 'Underline', 'Strike'] },
								{ name: 'insert', items: [ 'Table' ] },
								{ name: 'insert_2', items: [ 'PageBreak' ] }
   								 ],
								 height:130,
        						 width: 550,
								 uiColor: '#14B8C4'
								 });
		</script>
      
   <tr><td colspan="6"><br /><input type="hidden" id="unit" name="unit" value="<?php echo $details[0]['unit_id'] ?>"></td></tr>
    <tr align="center">
    	<td colspan="6"><input type="submit" name="submit" id="submit" value="Submit Comments"  class="btn btn-primary"/> </td>
    </tr>
    <tr><td colspan="6"><input type="hidden" name="emailID" id="emailID" value="<?php echo $loginID[0]['member_id']; ?>" /></td></tr>
        <tr><td colspan="6"><input type="hidden" name="SREmailIDs" id="SREmailIDs" value="<?php echo $strSREMailIDs; ?>" /></td></tr>
     
    <tr><td colspan="6"><br /></td></tr> 
</table> 
<br>
</center> 
</form>      
<?php }
else { ?>
<div style="font-size:14px; color:#FF0000;"> <p> Service Request <?php echo $_REQUEST['rq']; ?> does not exist. </p></div>
<?php } ?>
</center>
</body>
<!--</html>-->
<?php
if($_SESSION['is_year_freeze'] == 1)
{?>
<script>
	$("#viewrequest").css( 'display', 'none' );
</script>
<?php }?>

</div>

<?php include_once "includes/foot.php"; ?>
        