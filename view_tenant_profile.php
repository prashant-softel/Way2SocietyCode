<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<title>W2S - Tenant Profile View</title>
</head>

<?php include_once("includes/head_s.php");?>
<?php 
include_once "classes/include/dbop.class.php";
include_once "classes/view_tenant_profile.class.php" ;
include_once "classes/view_member_profile.class.php" ;
include_once "classes/dbconst.class.php";
include_once "classes/tenant.class.php" ;
include_once "classes/utility.class.php" ;
include_once "classes/lien.class.php";

$m_dbConnRoot = new dbop(true);
$landLordDB = new dbop(false, false, false, false, true);

$obj_tenant = new tenant($m_dbConn,$m_dbConnRoot, $landLordDB);
$obj_lien=new lien($m_dbConn,$m_dbConnRoot);
$obj_utility = new utility($m_dbConn,$m_dbConnRoot);
$obj_activation = new activation($m_dbConn,$m_dbConnRoot);

//print_r($TenantDetails);
$obj_view_member_profile = new view_member_profile($m_dbConn);
$obj_view_tenant_profile = new view_tenant_profile($m_dbConn, $landLordDB);

$show_member_main 		 = $obj_view_member_profile->show_member_main();
$show_tenant_main = $obj_view_tenant_profile->show_tenant_main();
//echo "name: " .$show_tenant_main[0]['tenant_name'];
$TenantDetails= $obj_tenant->getTenantRecords($show_tenant_main[0]['unit_id']);
//print_r($TenantDetails);
//echo "ID: " .$TenantDetails[0]['tenant_id'];

//$show_sec_deposit=$obj_view_member_profile->showsecurity_deposit(VOUCHER_REFTABLEID,$_GET['id']);

$hasAccess = true;

if($_SESSION['role'] == ROLE_MEMBER && $_SESSION['unit_id'] <> $show_tenant_main[0]['unit_id'])
{
    $hasAccess = false;
}
/*else if($_SESSION['role'] == ROLE_ADMIN_MEMBER)
{
    if($_SESSION['profile'][PROFILE_EDIT_MEMBER] != 1 && $_SESSION['unit_id'] <> $show_member_main[0]['unit'])
    {
        $hasAccess = false;
    }
}*/

if($hasAccess == false)
{
	?>
		<script>
			window.location.href = 'Dashboard.php';

		</script>

	<?php
	exit();
}

//print_r($show_mem_other_family);
$show_tenant_family = $obj_view_tenant_profile->show_tenant_other_family();
$show_cheque_detail = $obj_view_tenant_profile->post_dated_cheque();
$show_tenant_car_parking    = $obj_view_tenant_profile->show_tenant_car_parking();
$show_tenant_bike_parking   = $obj_view_tenant_profile->show_tenant_bike_parking();
$share_certificate_details = $obj_view_member_profile->show_share_certificate_details();
$show_share_certificate = $obj_view_member_profile->show_share_certificate();
$ParkingTypeData = $obj_view_member_profile->get_parking_type();
$show_mem_lien = $obj_lien->getAllLienDetails($show_tenant_main[0]['unit_id']);
//print_r($share_certificate_details);
//echo "ID: " .$show_cheque_detail[0]['tenant_id'];
$UnitBlock = $_SESSION["unit_blocked"];
?>
<head>
<style>
#errorBox
{
    color:hsla(0,100%,50%,1);
    font-weight: bold;
}
.table_format
{
	text-align: center;
    vertical-align: middle;
}
.table_format td, th
{
    text-align: center;
    vertical-align: middle;
}

.table_format_left
{
    text-align: left;
    vertical-align: middle;
}
.table_format_left td, .table_format_left td th
{
    text-align: left;
    vertical-align: middle;
}
</style>
  <script type="text/javascript" src="js/bootstrap-modalmanager.js"></script>
  <script type="text/javascript" src="js/bootstrap-modal.js"></script>
<script language="application/javascript" type="text/javascript" src="js/validate.js"></script> 
<script type="text/javascript" src="js/jsview_member_profile_mem_edit.js"></script>
<script type="application/javascript" language="javascript"></script>
<script type="text/javascript" src="js/OpenDocumentViewer.js">

function go_error()
{
	setTimeout('hide_error()',10000);	
}
function hide_error()
{
	document.getElementById('error').style.display = 'none';	
}
	 
	
	//$( document ).ready(function() {
		 
		  <?php
		if(isset($_GET['edt']))
		{  
			?>
		var isblocked = '<?php echo $UnitBlock ?>';
		if(isblocked==1)
		{
			//alert("We are sorry,but your access has been blocked for this feature . Please contact your Managing Committee for resolution .");
			window.location.href='suspend.php';	
			
			
		}
    
	<?php 
		}
	?>
//});
$(function()
        {
            $.datepicker.setDefaults($.datepicker.regional['']);
            $(".basics").datepicker({ 
            dateFormat: "dd-mm-yy", 
            showOn: "both", 
            buttonImage: "images/calendar.gif", 
            changeMonth: true,
            changeYear: true,
            yearRange: '-100:+0',
            buttonImageOnly: true ,
            defaultDate: '01-01-1980'
        })});

</script>

</head>
<?php if(isset($_REQUEST['up'])){ ?>
<body onLoad="go_error();">
<?php }else{ ?>
<body>
<?php } ?>
<br>
<?php
    if($_SESSION['role'] == ROLE_MEMBER || $_SESSION['role'] == ROLE_ADMIN_MEMBER)
    {
        ?>
        <div class="panel panel-info" id="panel" style="display:none;margin-top:10px;margin-left:3.5%;width:70%">
        <?php
    }
    else
    {
        ?>
        <div class="panel panel-info" id="panel" style="display:none;margin-top:10px;margin-left:3.5%;width:75%">
        <?php
    }
?>
    <div class="panel-heading" id="pageheader">Tenant Profile View</div>
<!--<center><font color="#43729F" size="+1"><b>Profile View</b></font></center>-->
<br><br>
        <br>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document" style="width: 60%;">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel" style="padding:0px">Application to renew parking registration</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -4%;font-size: 40px;"> <span aria-hidden="true">&times;</span> </button>
              </div>
              <div class="modal-body"> </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="close_renew_registration">Close</button>
                <button type="button" class="btn btn-primary" id="submit_renew_registration">Submit</button>
              </div>
            </div>
          </div>
        </div>
<center>

<form method="post" name="memberform" action="process/view_member_profile_tenant_edit.process.php" onSubmit="return validate();">
<input type="hidden" name="id" value="<?php echo $_GET['id'];?>" />
 <a href="#vehicl_div" id="focus_vehicle"></a>
<div>
<center>
<table style="display:none">

<tr>
	<td>
<button type="button" class="btn btn-primary btn-circle" onClick="history.go(-1);" style="float:left;" id="btnBack"><i class="fa  fa-arrow-left"></i></button>
    </td>
</tr>
</table>
<table style="padding-bottom:10px">

<tr>
	<td style="padding:5px">
<button type="button" class="btn btn-primary btn-circle" onClick="history.go(-1);" style="float:left;" id="btnBack"><i class="fa  fa-arrow-left"></i></button>
    </td>
    <?php
	if($_SESSION['is_year_freeze'] == 0 && ($_SESSION['role']==ROLE_SUPER_ADMIN || $_SESSION['profile'][PROFILE_EDIT_MEMBER] == '1' || $_SESSION['owner_id']==$_GET['id']))
	{
	?>
    
        <?php
		if(!isset($_GET['edt']))
		{  
			?>
            <td style="padding:5px">
				<input type="button"  class="btn btn-primary"  value="Edit Profile"  id="Edit" style="width:100px; height:30px; font-family:'Times New Roman', Times, serif; font-style:normal;"onClick="window.location.href='view_tenant_profile.php?edt&prf&mkm&tik_id=<?php echo time();?>&id=<?php echo $_GET['id'];?>'">
                </td>
			<?php
		}
		else
		{
			?>
            <td style="padding:5px">
				<input type="submit"  class="btn btn-primary"  value="Update Profile"  id="insert" name="update" style="width:100px; height:30px; font-family:'Times New Roman', Times, serif; font-style:normal; background-color: #337ab7;color: #fff; border-color: #2e6da4;">
                </td>
                <td style="padding:5px">
				<input type="button"  class="btn btn-primary"  value="Cancel" style="width:100px; height:30px; font-family:'Times New Roman', Times, serif; font-style:normal;"onClick="window.location.href='view_tenant_profile.php?prf&mkm&tik_id=<?php echo time();?>&id=<?php echo $_GET['id'];?>'">
                </td>
			<?php
		}
	}
	?>
	
	<td style="padding:5px">
	<?php 
	if($_SESSION['is_year_freeze'] == 0 && ($_SESSION['role']==ROLE_SUPER_ADMIN || $_SESSION['profile'][PROFILE_EDIT_MEMBER] == '1' || $_SESSION['owner_id']==$_GET['id']))
	{
	?>
    <input type="button" class="btn btn-primary "  value="Edit Unit Details" style=" height:30px; font-family:'Times New Roman', Times, serif; font-style:normal;" onClick="window.location.href='unit.php?uid=<?php echo $show_tenant_main[0]['unit_id']?>'">
    <?php
	}
	?>
    </td>
    <td style="padding:5px">
    <input type="button" class="btn btn-primary "  value="Total Dues Rs.<?php echo $obj_utility->getDueAmount($show_tenant_main[0]['unit_id'])?>" style=" height:30px; font-family:'Times New Roman', Times, serif; font-style:normal;background-color:#FFFFFF;color:#000;border-color:#FFFFFF;border-top-style:none;border-left-style:none;border-right-style:none;font-weight:bold" onClick="window.open('member_ledger_report.php?&uid=<?php echo $show_tenant_main[0]['unit_id'];?>', '_blank')">
    </td>
    </tr>
    </table>
    </center>
</div>
<div id="errorBox"></div>
<table border="0"> <!-- class="profile_table" -->
<tr>
	<td valign="top" align="center"><font color="red"><?php if(isset($_GET['up'])){echo "<b id=error>Record Updated Successfully</b>";}else{echo '<b id=error></b>';} ?></font></td>
</tr>
<tr><td>

<table border="0" style="width: 100%;">
<tr>
    <td colspan="6" style="font-weight: bold;text-align: center;">
        <i class="fa fa-home" style="font-size: 14px;"></i>&nbsp;<u>TENANT DETAILS</u>&nbsp;<i class="fa fa-home" style="font-size: 14px;"></i><?php if($_SESSION['is_year_freeze'] == 0)
	{?>
    <!--<button type="button" class="btn btn-primary btn-xs" onClick="window.location.href='unit.php?uid=<?php echo $show_tenant_main[0]['unit_id']?>'" style="margin-left:10px"><i class="fa fa-edit fa-large"></i></button>-->
    <!--<a href="mem_other_family_new.php?prf&mkm&mem_id=<?php echo $_GET['id'];?>&tik_id=<?php echo time();?>" style="color:#00F; text-decoration:none;"><b>Add New</b></a></b>-->
    <?php }?>&nbsp;<br/><br/>
    </td>
    
</tr>
<tr>
    <td colspan="6"></td>
</tr>
<?php
		
		if(isset($msg))
		{
			if(isset($_POST['ShowData']))
			{
			?>
				<tr height='30'><td colspan='4' align='center'><font color='red' size='-1'><span id="tenantError" style="display:none"></span><b id='error' style='display:none;'><?php echo $_POST['ShowData']; ?>			</b></font></td></tr>
			<?php
			}
			else
			{
			?>
				<tr height='30'><td colspan='4' align='center'><font color='red' size='-1'><b id='error' style='display:none;'><?php echo $msg; ?></b></font></td></tr>
			<?php
			}	
		}
		else
		{
		?>
			<tr height='30'><td colspan='4' align='center'><font color='red' size='-1'><b id='error' style='display:none;'><?php echo $_POST['ShowData']; ?></b></font></td></tr>
		<?php
		}
		?>
        	<tr>
            <?php 
            $image = $TenantDetails[0]['img'];
            $imageUrl = "Uploaded_Documents/tenantProfile__" .$_GET['id']."_Photo.jpg"; ?>
            	<td style="width: 200px;"><a target="_blank" href="images/noimage.png" id="profileHref"><img <?php 
				if(isset($_REQUEST['edit']) || isset($_REQUEST['edt']) || isset($_REQUEST['view']))
				{ 
					if($image != "") 
					{?> 
                    	src = "<?php echo $imageUrl;?>" 
					<?php 
					}
					else
					{
					?> 
                    	src = "images/noimage.png" 
					<?php 
					}
				}
				else
				{
				?>
                	src = "images/noimage.png"
                <?php
				}
					?> id="profile_img" class="img-square" alt="img" style=" width:70%;height:30%;border: 1px solid #ddd;border-radius: 4px;padding: 5px;"></a>
                </td>

<td colspan="2" width="70%">
    <table width="100%">
    <tr>
        	<td style="text-align:right"></td>
			<td style = "text-align:right"><b>Tenant Name</b></td>
            <td>:</td>
            <td><?php echo $show_tenant_main[0]['tenant_name'];?></td>
        </tr>
		<tr>
        	<td style="text-align:right"></td>
			<td style = "text-align:right"><b>Building Name</b></td>
            <td>:</td>
            <td><?php echo $show_tenant_main[0]['wing'];?></td>
        </tr>
        <tr>
        	<td style="text-align:right"></td>
			<td style = "text-align:right"><b>Flat No</b></td>
            <td>:</td>
            <td><?php echo $show_tenant_main[0]['unit_no'];?></td>
        </tr>
        <tr>
        	<td style="text-align:right"></td>
			<td style = "text-align:right"><b> Property Area</b></td>
            <td>:</td>
            <td><?php echo $show_tenant_main[0]['flat_configuration'];?></td>
        </tr>
        <tr>
        	<td style="text-align:right"></td>
			<td style = "text-align:right"><b>Start Date</b></td>
            <td>:</td>
            <td><?php echo getDisplayFormatDate($TenantDetails[0]['start_date']);?>
            &nbsp; &nbsp;  <b>End Date : </b>
            <?php echo getDisplayFormatDate($TenantDetails[0]['end_date']);?></td>
        </tr>
        <tr>
        	<td style="text-align:right"></td>
			<td style = "text-align:right"><b>Number of Co-Occupants</b></td>
            <td>:</td>
            <td><?php echo $TenantDetails[0]['members'];?></td>
        </tr>
    <table>
</td>
</table>
</table>
</td>
</tr>
<table class="table_format">
<tr height="25" valign="bottom" >
    
   
    <td colspan="6" style="font-weight: bold;text-align: center;">
        <br/>
        <i class="fa fa-group" style="font-size: 14px;">&nbsp;</i><b><u>LEASE DETAILS WITH UNIT/FLAT </u></b>&nbsp;<i class="fa fa-group" style="font-size: 14px;"></i>
       <?php if($_SESSION['is_year_freeze'] == 0 && ($_SESSION['role']==ROLE_SUPER_ADMIN || $_SESSION['profile'][PROFILE_APPROVALS_LEASE] == '1' || $_SESSION['owner_id']==$_GET['id']))
	{?>
    <br/>
     
     
      <?php
	 // $leaseEndDate = $TenantDetails[0]['end_date'];
	  //$date = getCurrentTimeStamp();
	  //$currentDate = getDisplayFormatDate($date['Date']);
	  
	  //
	   if($TenantDetails[0]['active'] == "0" && $TenantDetails[0]['tenant_id'] != "")
	 {?>
      <button type="button" class="btn btn-primary btn-xs" value="terminate_lease" onClick="window.location.href='tenant.php?mem_id=<?php echo $_GET['id'];?>&tik_id=<?php echo time();?>&edit=<?php echo $TenantDetails[0]['tenant_id'];?>&ter'" style="float: right; "><!--<i class="fa fa-plus fa-small"></i>-->Terminate Lease</button>
    
    <?php 
	}
	else
	{?>
    
    	<button type="button" class="btn btn-primary btn-xs" value="Add Lessee" onClick="window.location.href='tenant.php?prf&mem_id=<?php echo $_GET['id'];?>&tik_id=<?php echo time();?>'" style="float: right; "><!--<i class="fa fa-plus fa-small"></i>-->Add Lease</button>
 
	<?php 
	}
     
	/*if($TenantDetails[0]['active']==0)
	{?>
		 <button type="button" class="btn btn-primary btn-xs" onClick="window.location.href='tenant.php?prf&mkm&mem_id=<?php echo $_GET['id'];?>&tik_id=<?php echo time();?>'" style="float: right; display:block;">Edit Tenant</button>
		<?php }*/
	}?>
    </td>
</tr>
<!--<tr align="left">
<td> Number of Tenant Entries : <?php // echo $TenantDetails[0]['counttotal']?></td></tr>
--><tr>
	<td colspan="6">
    <?php if( $TenantDetails[0]['tenant_id'] =='')
	 {?>
     <table border="0" >
     <tr height="30" bgcolor="#E8E8E8">
      <th width="280">Name on the Lease Document</th>
                        <th width="150">Start Date</th>
                        <th width="150">End Date</th>
                      <!--  <th width="100">Mobile</th>
                        <th width="180">E-Mail</th>-->
                        <th width="150">Member(s)</th>
               <!-- <th width="80">Document</th>-->
                        </tr>
                        <tr><td><br></td></tr>
                        <tr><td colspan="6" style="color: red;font-size: 11px; font-weight: bold;">No Active Lessee Record Found</td></tr>
                        
     <?php }
	 else{?>
    	<table border="0">
        <tr height="30" bgcolor="#E8E8E8">
                        <th width="280">Name on the Lease Document</th>
                        <th width="100">Start Date</th>
                        <th width="100">End Date</th>
                       <!-- <th width="100">Mobile</th>
                        <th width="180">E-Mail</th>-->
                        <th width="100">Member(s)</th>
                      <!--  <th width="80">Document</th>-->
                        <?php
                        if(!isset($_GET['edt']))
                        {
                            ?>
                                <th width="100">View</th>
                            <?php
                        }
                        else
                        {
                            ?>
                                <th width="100">Delete</th>
                            <?php
                        }
                        if($TenantDetails[0]['active']==0)
						{?>
                        <th width="100">Edit</th>
                        <?php 
						}
						else
						{?>
						<th width="100" style="display:none">Edit</th>
						<?php }
						if($TenantDetails[0]['active']==0)
						{?>
                        <th width="100"  style="display:none" >Status</th>
                        <?php }
						else{?>
                        <th width="100">Status</th>
                        <?php }?>	
        </tr>
        
        <tr height="25" bgcolor="#BDD8F4">
        <td><a onClick="window.location.href='tenant.php?mem_id=<?php echo $_GET['id']; ?>&tik_id=<?php echo time(); ?>&view=<?php echo $TenantDetails[0]['tenant_id']; ?>'"><?php echo $TenantDetails[0]['tenant_name'] ?></a></td>
        <td><?php echo getDisplayFormatDate($TenantDetails[0]['start_date']);?></td>
        <td><?php echo getDisplayFormatDate($TenantDetails[0]['end_date']);?></td>
         <!--<td><?php// echo $TenantDetails[0]['mobile_no']?></td>
        <td><?php //echo $TenantDetails[0]['email']?></td>-->
        <td><?php echo $TenantDetails[0]['members']?></td>
      	<!--<td>  <?php //echo "<a href='http://localhost/onedrive/sujit/beta_aws/Uploaded_Documents/".$TenantDetails[0]['Document']. "'download>"?> <?php // "<img src='images/download1.ico'  width='20'>";?></a></td>-->
       <!--<td> <?php //echo  " <a href='http://localhost/onedrive/sujit/beta_aws/Uploaded_Documents/".$TenantDetails[0]['Document']. "'download>"?>Download </a></td>-->
       
        <td style="text-transform: capitalize;"><a id="mem_" onClick="memexpandDetails(this);">More</a></td>
       <?php
       if($TenantDetails[0]['active']==0)
	   {?><!--need this line -->
		   <td><a href="tenant.php?mem_id=<?php echo $_GET['id'];?>&tik_id=<?php echo time();?>&edit=<?php echo $TenantDetails[0]['tenant_id'];?>"><img src="images/edit.gif" /></a></td>
		<?php }
	      else
		  {?>
       <td><span>Active</span>
       <a href="tenant.php?mem_id=<?php echo $_GET['id'];?>?edit=<?php echo $TenantDetails[0]['tenant_id'];?>"><img src="images/edit.gif"  style="display:none;"/></a></td>
        <?php }?>
        
                   
        </tr>
         <?php
        if(!isset($_GET['edt']))
        {
            ?>
                <tr id="memdetail_" style="display: none;">
            <?php
        }
        else
        {
            ?>  
                <tr id="memdetail_">
            <?php
        }
    ?>
       <!-- <tr align="left" id="memdetail_" style="display: none;">-->
        <td  valign="left" colspan="8">
        <table class="table_format_left" style="width: 100%; float:left">
        <tr><td width="200%">
        					<table width="100%"  style="background-color:#f9f9f9;">
                         <tr height="25" align="left"  style="background-color:#f9f9f9;">
                         <th width="80px"style="text-align:left" >Agent Name :</th><th style="text-transform: capitalize;text-align:left; width:180px;"><?php echo $TenantDetails[0]['agent_name']?></th>
                         <th width="80px"style="text-align:left" >Contact No :</th><th><?php echo $TenantDetails[0]['agent_no']?></th>
                         </tr>
                         </table>
                         </td></tr>
                          <tr><td>
                         <table  width="100%"  >
                         <tr style="background-color:#bce8f1;font-size:14px;"  height="25">
    	<th colspan="8" align="center" style="text-align:center;">Lessee Occupying in the Flat </th>
        <th style="background-color:#FFF; width:50px;"></th>
      <th style="width:150px; text-align:center;" align="center">Document </th>

                            <tr height="25" align="left" style="background-color:#f9f9f9;">
                            <td  colspan="8"><table><tr>
                                <th  width="180" style="text-align:left" >Name</th>
                        		<th width="110" style="text-align:left" >Relation</th>
                                <th width="110" style="text-align:left" >Emirate No</th>
                                <th width="100"style="text-align:left" >Date Of Birth</th>
                                <th width="100"style="text-align:left" >Contact No</th>
                                <th width="100"style="text-align:left" >Email Address</th>
                                <th style="width:50px;">Send Communcation Emails ?</th>
                        </tr>
                       
                        <?php 
						 $mem_List=$TenantDetails[0]['Allmembers'];
						// $doc_List=$TenantDetails[0]['Alldocuments'];
						for($i=0;$i<sizeof($mem_List);$i++)
						{  //for($j=1;$j<=sizeof($mem_List);$j++)
							//{
							$member=$mem_List[$i]['mem_name'];
							$Relation=$mem_List[$i]['relation'];
							$MemberDob=getDisplayFormatDate($mem_List[$i]['mem_dob']);
							$number=$mem_List[$i]['contact_no'];
							$email=$mem_List[$i]['email'];
							$emirate=$mem_List[$i]['emirate_no'];
							?>
                            <tr align="left">
                             <td style="text-transform: capitalize;"><?php //echo $j?><?php echo $member?></td>
                            <td style="text-transform: capitalize;"><?php echo $Relation?></td>
                             <td style=""><?php echo $emirate?></td>
                            <td><?php echo $MemberDob?></td>
                             <td><?php echo $number?></td>
                              <td><?php echo $email?></td>
                              <td style="text-align:center"> <input type="checkbox" name="mem_Send_commu_emails<?php echo $ii1;?>" id="mem_Send_commu_emails<?php echo $ii1;?>" value="1" <?php if($mem_List[$i]['send_commu_emails']==1){echo 'checked';}else{echo '';}?> <?php  echo 'disabled'; ?> <?php if($_SESSION['role'] == ROLE_ADMIN) { echo 'onclick="return false;"';} ?>></td>

                              </tr>
                             
						<?php }?>
                        </table></td> 
						 <td style="width:50px; background-color:#FFF"></td>
                         <td colspan="2"><table  width="150px">
						<?php $doc_List=$TenantDetails[0]['Alldocuments'];
						for($i=0;$i<sizeof($doc_List);$i++)
						{ 
							$docName=$doc_List[$i]['Name'];
							$doc_Link=$doc_List[$i]['Document'];
                            $doc_version=$doc_List[$i]['doc_version'];

                            $gdrive_id = $doc_List[$i]['attachment_gdrive_id'];
                            
                            $doc_id=$doc_List[$i]['doc_id'];
							?>
                            <tr align="center">
                            <td style="text-transform: capitalize; text-align:center;"><span style="text-align:center"><a href="<?php echo $doc_List[$i]['documentLink'];?>" target="_blank" style="cursor: pointer;text-decoration:none;"><?php echo $docName?></a></span></td>
                            <?php if($TenantDetails[0]['active'] == 0){ ?>
                            <td><input style="color: red" onClick="delete_doc(this.id)" type="button" id="<?php echo $doc_id;?>"  name="<?php echo $doc_id;?>"  value="<?php echo "X";?>"></td>
                            <?php }?>
                           </tr>
						<?php } //}?>
                        </table></td>
                       
                      <!--<table width="20%"> 
                        <tr height="25">
                         <th width="85" >Alternate no.</th></tr>
					<tr>  <td><?php //echo $TenantDetails[0]['alter_no']?></td></tr>
                       </table>
                    --></table></td></tr>
                   
                        <!--<?php 
						 //$doc_List=$TenantDetails[0]['Alldocuments'];
						//$j=20;
						//for($i=0;$i<sizeof($doc_List);$i++)
						//{ 
							//$docName=$doc_List[$i]['Name'];
							//$doc_Link=$doc_List[$i]['Document'];
							?>
                            <tr align="left">
                             <td style="text-transform: capitalize;"><a href="Uploaded_Documents/<?php// echo $doc_Link?> " target="_blank"><?php //echo $docName?></a></td>
                            
                           </tr>
						<?php //} //}?>
                    
                    </table>-->
                        </table><?php }?>
                        </td></tr>
                        <tr><td><br></td></tr>
                        
                  <?php if( $TenantDetails[0]['Count']==0 && $TenantDetails[0]['tenant_id']<>'')
	 			{?><tr>
			 <td colspan="6"> <span style="float: left;margin-left: 3px;"><a href="tenant_list.php?u_id=<?php echo $show_tenant_main[0]['unit_id']?>" target="_blank"><b> Number of Previous Lease Entries : <?php  echo $TenantDetails[0]['Count']?></b></a></span></td></tr>
             <?php 
			 }
			  else if( $TenantDetails[0]['Count']==0)
	 			{?><tr>
			 <td colspan="6"> <span style="float: left;margin-left: 3px;"><b> Number of Previous Lease Entries : <?php  echo $TenantDetails[0]['Count']?></b></span></td></tr>
             <?php 
			 }
			 else
			 {
				 //var_dump($TenantDetails);?>
          <tr><td  colspan="6"><span style="float: left;margin-left: 3px;"><a href="tenant_list.php?u_id=<?php echo $show_tenant_main[0]['unit_id']?>" target="_blank"><b> Number of Previous Lease Entries : <?php  echo $TenantDetails[0]['Count']?></b></a></span></td></tr>
             <?php }?>
             
    </table>
    
    
   
<tr><td><br/><br></td></tr>
<tr height="25" valign="bottom">
    <td colspan="6" style="font-weight: bold;text-align: center;"><i class="fa fa-car" style="font-size: 14px;">&nbsp;</i><b><u id="vehicl_div">VEHICLE DETAILS</u></b>&nbsp;<i class="fa fa-car" style="font-size: 14px;"></i>
       <?php if($_SESSION['is_year_freeze'] == 0 && ($_SESSION['role']==ROLE_SUPER_ADMIN || $_SESSION['profile'][PROFILE_EDIT_MEMBER] == '1' || $_SESSION['owner_id']==$_GET['id']))
	{?>
    <br/>
    <button type="button"  class="btn btn-primary btn-xs" onClick="window.location.href='mem_vehicle_new.php?prf&mkm&ten_id=<?php echo $_GET['id'];?>&tik_id=<?php echo time();?>'" style="float: right;">Add Vehicle<!--<i class="fa fa-plus fa-small">--></i></button>
    <!--<a href="mem_vehicle_new.php?prf&mkm&mem_id=<?php //echo $_GET['id'];?>&tik_id=<?php //echo time();?>" style="color:#00F; text-decoration:none;"><b>Add New</b></a></b>-->
    <?php }?>
    </td>
</tr>

<tr>
	<td colspan="6">
    	<table border="0">
        <tr height="30" bgcolor="#E8E8E8">
            <th width="120">Car Owner</th>
            <th width="150">Car Registration No.</th>
            <th width="85">Parking Slot No.</th>
            <th width="85">Parking Sticker No.</th>
            <th width="80">Parking Type</th>
            <th width="80">Car Make</th>
            <th width="80">Car Model</th>
            <th width="80">Car Colour</th>
             <?php
			  if (!isset($_GET['edt']) && $_SESSION['society_id'] == 288) { ?>
				<th width="50">Renewal</th>
				<?php
				}
                if(isset($_GET['edt']))
                {
					if($_SESSION['role'] == ROLE_ADMIN || $_SESSION['role'] == ROLE_SUPER_ADMIN)
					{
                    ?>
                        <th width="50">Delete</th>
                    <?php
					}
                }
            ?>
        </tr>
        <?php
		if($show_tenant_car_parking<>"")
        {
            $ii2 = 1;
        foreach($show_tenant_car_parking as $k3 => $v3)
        {
        ?>       
            <input type="hidden" name="mem_car_parking_id<?php echo $ii2;?>" value="<?php echo $show_tenant_car_parking[$k3]['mem_car_parking_id'];?>" />
            <tr height="25" bgcolor="#BDD8F4">
            <td align="center">
                <input type="text" name="car_owner<?php echo $ii2;?>" id="car_owner" value="<?php echo $show_tenant_car_parking[$k3]['car_owner'];?>" style="width:150px;" class = "field_input" <?php if($_SESSION['role'] != ROLE_SUPER_ADMIN && $_SESSION['role'] != ROLE_ADMIN) { echo 'readonly';} ?> />
            </td>
            <td align="center">
                <input type="text" name="car_reg_no<?php echo $ii2;?>" id="car_reg_no" value="<?php echo $show_tenant_car_parking[$k3]['car_reg_no'];?>" style="width:120px;" class = "field_input" <?php if($_SESSION['role'] != ROLE_SUPER_ADMIN && $_SESSION['role'] != ROLE_ADMIN) { echo 'readonly';} ?> />
            </td>
            <td align="center">
                <input type="text" name="parking_slot<?php echo $ii2;?>" id="parking_slot<?php echo $ii2;?>" value="<?php echo $show_tenant_car_parking[$k3]['parking_slot'];?>" style="width:70px;" size="13" class = "field_input" <?php if($_SESSION['role'] != ROLE_SUPER_ADMIN && $_SESSION['role'] != ROLE_ADMIN) { echo 'readonly';} ?> />
            </td>
            <td align="center">
                <input type="text" name="parking_sticker<?php echo $ii2;?>" id="parking_sticker<?php echo $ii2;?>" value="<?php echo $show_tenant_car_parking[$k3]['parking_sticker'];?>" style="width:70px;" size="13" class = "field_input" <?php if($_SESSION['role'] != ROLE_SUPER_ADMIN && $_SESSION['role'] != ROLE_ADMIN) { echo 'readonly';} ?> />
            </td>
             <td align="center">
             	
				<?php
				if(isset($_GET['edt']))
				{
				 	if($_SESSION['role'] == ROLE_SUPER_ADMIN || $_SESSION['role'] == ROLE_ADMIN)
					{
						
				?>
                		<select name="car_parking_type<?php echo $ii2;?>" id="parking_type<?php echo $ii2;?>" style="width:70px;" class="field_select">
				<?php
						echo $obj_view_member_profile->ComboboxWithDefaultSelect("Select `Id`,`ParkingType` from `parking_type` where Status = 'Y'",$show_tenant_car_parking[$k3]['ParkingType']);
				?>
                		</select>    
                <?php
					} 
					else
					{ 
				?> 
                		<select name="car_parking_type<?php echo $ii2;?>" id="parking_type<?php echo $ii2;?>" style="width:70px;" class="field_select" disabled>
                <?php
						echo $obj_view_member_profile->ComboboxWithDefaultSelect("Select `Id`,`ParkingType` from `parking_type` where Status = 'Y'",$show_tenant_car_parking[$k3]['ParkingType']); ?></select>
                <?php
					}
				}
				else
				{
					?> 
                		<select name="car_parking_type<?php echo $ii2;?>" id="parking_type<?php echo $ii2;?>" style="width:70px;" class="field_select" disabled>
                <?php
						echo $obj_view_member_profile->ComboboxWithDefaultSelect("Select `Id`,`ParkingType` from `parking_type` where Status = 'Y'",$show_tenant_car_parking[$k3]['ParkingType']); ?> </select>
                <?php
				}
				?>
            </td>
            <td align="center">
                <input type="text" name="car_make<?php echo $ii2;?>" id="car_make" value="<?php echo $show_tenant_car_parking[$k3]['car_make'];?>" style="width:80px;" class = "field_input" <?php if($_SESSION['role'] != ROLE_SUPER_ADMIN && $_SESSION['role'] != ROLE_ADMIN) { echo 'readonly';} ?> />
            </td>
            <td align="center">
                <input type="text" name="car_model<?php echo $ii2;?>" id="car_model" value="<?php echo $show_tenant_car_parking[$k3]['car_model'];?>" style="width:80px;" class = "field_input" <?php if($_SESSION['role'] != ROLE_SUPER_ADMIN && $_SESSION['role'] != ROLE_ADMIN) { echo 'readonly';} ?> />
            </td>
            <td align="center">
                <input type="text" name="car_color<?php echo $ii2;?>" id="car_color" value="<?php echo $show_tenant_car_parking[$k3]['car_color'];?>" style="width:80px;" class = "field_input" <?php if($_SESSION['role'] != ROLE_SUPER_ADMIN && $_SESSION['role'] != ROLE_ADMIN) { echo 'readonly';} ?> />
            </td>
              <?php
				  if (!isset($_GET['edt']) && $_SESSION['society_id'] == 288 && $show_tenant_car_parking[$k3]['Renew_Registration'] == 0) // 288 is shree mari gold society
				  { ?>
				<td align="center"><button type="button" class="btn btn-primary" id="renew_registration" name="renew_registration" title="Renew parking registration" onclick='showRenewModal(<?php echo json_encode($show_tenant_car_parking[$k3],JSON_HEX_APOS); ?>,<?php echo VEHICLE_CAR; ?>);'><i class="fa fa-undo"></i></button></td>
			  <?php }
					else if(!isset($_GET['edt']) && $_SESSION['society_id'] == 288 && $show_tenant_car_parking[$k3]['Renew_Registration'] == 1)
					{?>
						<td align="center">submitted</td>
					<?php
					}
					?>
            <td>
                 <?php
                    if(isset($_GET['edt']))
                    {
						if($_SESSION['role'] == ROLE_ADMIN || $_SESSION['role'] == ROLE_SUPER_ADMIN)
						{
                        ?>
                            <input type="checkbox" name="car_delete<?php echo $ii2; ?>" id="car_delete<?php echo $ii2; ?>" value="1">
                        <?php
                    	}
					}
                ?>
            </td>
        </tr>
        <?php
            $ii2++;
        }
        }
        else
        {
            ?>
            <tr height="25"><td colspan="8" align="center"><font color="#FF0000"><b>No Records Found<!--  by admin --></b></font></td></tr>
            <?php   
        }
        ?>
        <input type="hidden" name="tot_car" value="<?php echo $ii2-1;?>" />
        </table>
    </td>
</tr>

<tr>
	<td colspan="6">
    	<table border="0">
        <tr height="30" bgcolor="#E8E8E8">
            <th width="120">Bike Owner</th>
            <th width="150">Bike Registration No.</th>
            <th width="85">Parking Slot No.</th>
            <th width="85">Parking Sticker No.</th>
            <th width="80">Parking Type</th>
            <th width="80">Bike Make</th>
            <th width="80">Bike Model</th>
            <th width="80">Bike Colour</th>
            <?php
				if (!isset($_GET['edt']) && $_SESSION['society_id'] == 288) // 288 is shree mari gold society
 			  { ?>
				<th width="50">Renewal</th>
				<?php
				}
				
                if(isset($_GET['edt']))
                {
					if($_SESSION['role'] == ROLE_ADMIN || $_SESSION['role'] == ROLE_SUPER_ADMIN)
					{
                    ?>
                        <th width="50">Delete</th>
                    <?php
					}
                }
            ?>
        </tr>    
        <?php
		if($show_tenant_bike_parking<>"")
        {
            $ii3 = 1;
        foreach($show_tenant_bike_parking as $k4 => $v4)
        {
        ?> 
        <input type="hidden" name="mem_bike_parking_id<?php echo $ii3;?>" value="<?php echo $show_tenant_bike_parking[$k4]['mem_bike_parking_id'];?>" />      
        <tr height="25" bgcolor="#BDD8F4">
           	<td align="center">
                <input type="text" name="bike_owner<?php echo $ii3;?>" id="bike_owner" value="<?php echo $show_tenant_bike_parking[$k4]['bike_owner'];?>" style="width:150px;" class="field_input" <?php if($_SESSION['role'] != ROLE_SUPER_ADMIN && $_SESSION['role'] != ROLE_ADMIN) { echo 'readonly';} ?> />
            </td>
            <td align="center">
                <input type="text" name="bike_reg_no<?php echo $ii3;?>" id="bike_reg_no" value="<?php echo $show_tenant_bike_parking[$k4]['bike_reg_no'];?>" style="width:120px;" class="field_input" <?php if($_SESSION['role'] != ROLE_SUPER_ADMIN && $_SESSION['role'] != ROLE_ADMIN) { echo 'readonly';} ?> />
            </td>
            <td align="center">
                <input type="text" name="bike_parking_slot<?php echo $ii3;?>" id="bike_parking_slot" value="<?php echo $show_tenant_bike_parking[$k4]['parking_slot'];?>" style="width:70px;" size="13" class="field_input" <?php if($_SESSION['role'] != ROLE_SUPER_ADMIN && $_SESSION['role'] != ROLE_ADMIN) { echo 'readonly';} ?> />
            </td>
            <td align="center">
                <input type="text" name="bike_parking_sticker<?php echo $ii3;?>" id="bike_parking_sticker<?php echo $ii3;?>" value="<?php echo $show_tenant_bike_parking[$k4]['parking_sticker'];?>" style="width:70px;" size="13" class = "field_input" <?php if($_SESSION['role'] != ROLE_SUPER_ADMIN && $_SESSION['role'] != ROLE_ADMIN) { echo 'readonly';} ?> />
            </td>
            <td align="center">
 	<?php
              if(isset($_GET['edt']))
				{
				 	if($_SESSION['role'] == ROLE_SUPER_ADMIN || $_SESSION['role'] == ROLE_ADMIN)
					{
						
				?>
                		<select name="bike_parking_type<?php echo $ii3;?>" id="parking_type<?php echo $ii3;?>" style="width:70px;" class="field_select">
				<?php
						echo $obj_view_member_profile->ComboboxWithDefaultSelect("Select `Id`,`ParkingType` from `parking_type` where Status = 'Y'",$show_tenant_bike_parking[$k4]['ParkingType']);
				?>
                		</select>    
                <?php
					} 
					else
					{ 
				?> 
                		<select name="bike_parking_type<?php echo $ii3;?>" id="parking_type<?php echo $ii3;?>" style="width:70px;" class="field_select" disabled>
                <?php
						echo $obj_view_member_profile->ComboboxWithDefaultSelect("Select `Id`,`ParkingType` from `parking_type` where Status = 'Y'",$show_tenant_bike_parking[$k4]['ParkingType']); ?></select>
                <?php
					}
				}
				else
				{
					?> 
                		<select name="bike_parking_type<?php echo $ii3;?>" id="parking_type<?php echo $ii3;?>" style="width:70px;" class="field_select" disabled>
                <?php
						echo $obj_view_member_profile->ComboboxWithDefaultSelect("Select `Id`,`ParkingType` from `parking_type` where Status = 'Y'",$show_tenant_bike_parking[$k4]['ParkingType']); ?> </select>
                <?php
				}
				?>
            </td>
            <td align="center">
            <input type="text" name="bike_make<?php echo $ii3;?>" id="bike_make" value="<?php echo $show_tenant_bike_parking[$k4]['bike_make'];?>" style="width:80px;" class="field_input" <?php if($_SESSION['role'] != ROLE_SUPER_ADMIN && $_SESSION['role'] != ROLE_ADMIN) { echo 'readonly';} ?> />
            </td>
            <td align="center">
            <input type="text" name="bike_model<?php echo $ii3;?>" id="bike_model" value="<?php echo $show_tenant_bike_parking[$k4]['bike_model'];?>" style="width:80px;" class="field_input" <?php if($_SESSION['role'] != ROLE_SUPER_ADMIN && $_SESSION['role'] != ROLE_ADMIN) { echo 'readonly';} ?> />
            </td>
            <td align="center">
            <input type="text" name="bike_color<?php echo $ii3;?>" id="bike_color" value="<?php echo $show_tenant_bike_parking[$k4]['bike_color'];?>" style="width:80px;" class="field_input" <?php if($_SESSION['role'] != ROLE_SUPER_ADMIN && $_SESSION['role'] != ROLE_ADMIN) { echo 'readonly';} ?> />
            </td>
            <?php
			  if (!isset($_GET['edt']) && $_SESSION['society_id'] == 288  && $show_tenant_bike_parking[$k4]['Renew_Registration'] == 0) // 288 is shree mari gold society
			  { ?>
			<td align="center"><button type="button" class="btn btn-primary" id="renew_registration" name="renew_registration" title="Renew parking registration" onclick='showRenewModal(<?php echo json_encode($show_tenant_bike_parking[$k4],JSON_HEX_APOS); ?>,<?php echo VEHICLE_BIKE; ?>);'><i class="fa fa-undo"></i></button></td>
		  <?php }
				 else if(!isset($_GET['edt']) && $_SESSION['society_id'] == 288  && $show_tenant_bike_parking[$k4]['Renew_Registration'] == 1)
				{?>
					<td align="center">submitted</td>
				<?php
				}
			  ?>
            <td>
                 <?php
                    if(isset($_GET['edt']))
                    {
						if($_SESSION['role'] == ROLE_ADMIN || $_SESSION['role'] == ROLE_SUPER_ADMIN)
						{
                        ?>
                            <input type="checkbox" name="bike_delete<?php echo $ii3; ?>" id="bike_delete<?php echo $ii3; ?>" value="1">
                        <?php
						}
                    }
                ?>
            </td>
        </tr>
        <?php
            $ii3++;
        }
        }
        else
        {
            ?>
            <tr height="25"><td colspan="8" align="center"><font color="#FF0000"><b>No Records Found<!--  by admin --></b></font></td></tr>
            <?php   
        }
        ?>
        <input type="hidden" name="tot_bike" value="<?php echo $ii3-1;?>" />
        </table>
    </td>
</tr>

<table class="table_format">
<tr height="25" valign="bottom" >
    
   
    <td colspan="6" style="font-weight: bold;text-align: center;">
        <br/>
        <i class="fa fa-group" style="font-size: 14px;">&nbsp;</i><b><u>POST DATED CHEQUE DETAILS</u></b>&nbsp;<i class="fa fa-group" style="font-size: 14px;"></i>
    </td>
</tr>
<!--<tr align="left">
<td> Number of Tenant Entries : <?php // echo $TenantDetails[0]['counttotal']?></td></tr>
--><tr>
	<td colspan="6">
    <?php if( $TenantDetails[0]['tenant_id'] =='')
	 {?>
     <table border="0" >
     <tr height="30" bgcolor="#E8E8E8">
      <th width="150">Bank Name</th>
                        <th width="150">Cheque No</th>
                        <th width="150">Cheque Date</th>
                      <!--  <th width="100">Mobile</th>
                        <th width="180">E-Mail</th>-->
                        <th width="150">Amount</th>
                        <th width="100">Remark</th>
               <!-- <th width="80">Document</th>-->
                        </tr>
                        <tr><td><br></td></tr>
                        <tr><td colspan="6" style="color: red;font-size: 11px; font-weight: bold;">No Active Lessee Record Found</td></tr>
                        
     <?php }
	 else{?>
    	<table border="0">
        <tr height="30" bgcolor="#E8E8E8">
                        <th width="200">Bank Name</th>
                        <th width="200">Cheque No</th>
                        <th width="100">Cheque Date</th>
                       <!-- <th width="100">Mobile</th>
                        <th width="180">E-Mail</th>-->
                        <th width="100">Amount</th>
                        <th width="100">Remark</th>
                      <!--  <th width="80">Document</th>-->	
        </tr>
        
        <tr height="25" bgcolor="#BDD8F4">
        <td><?php echo $show_cheque_detail[0]['bank_name']; ?></td>
        <td><?php echo $show_cheque_detail[0]['cheque_no'];?></td>
        <td><?php echo getDisplayFormatDate($show_cheque_detail[0]['cheque_date']);?></td>
        <td><?php echo $show_cheque_detail[0]['amount']?></td>
        <td><?php echo $show_cheque_detail[0]['remark']?></td>
     </tr>
    </table><?php 
}?>

</tr></td>
<table class="table_format" style="width: 100%;">
<tr height="25" valign="bottom">
    <td colspan="6" style="font-weight: bold;text-align: center;">
        <br/><i class="fa fa-medkit" style="font-size: 14px;"></i>&nbsp;<u><b>EMERGENCY CONTACT DETAILS</b></u>&nbsp;<i class="fa fa-medkit" style="font-size: 14px;">&nbsp;</i>
    </td>
</tr>
<tr height="25" valign="center" align="left">
    <td style="text-align: left;" width="100px"><b>Relatives Name</b></td>
    <td>:</td>
    <td align="left" style="text-align: left;">
    <input type="text" name="eme_rel_name" id="eme_rel_name" class="field_input" value="<?php echo $show_tenant_main[0]['tenant_name'];?>" style="width:180px;"/>
    </td>
</tr>

<tr align="left">
    <td style="text-align: left;"><b>Contact No.</b></td>
    <td>:</td>
    <td style="text-align: left;">
    <input type="text" name="eme_contact_1" id="eme_contact_1" class="field_input" onBlur="extractNumber(this,0,true);" onKeyUp="extractNumber(this,0,true);" onKeyPress="return blockNonNumbers(this, event, true, true)" value="<?php echo $show_tenant_main[0]['mobile_no'];?>" style="width:120px;"/>
    </td>
</tr>
</table>
<br>
<br>
<?php if($_SESSION['is_year_freeze'] == 0 &&($_SESSION['role']==ROLE_SUPER_ADMIN || $_SESSION['profile'][PROFILE_EDIT_MEMBER] == '1' ))
{?>
		<center>
            <?php
                if(!isset($_GET['edt']))
                {  
                    ?>
                        <input type="button"  class="btn btn-primary"  value="Edit Profile"  id="Edit" style="width:100px; height:30px; font-family:'Times New Roman', Times, serif; font-style:normal;"onClick="window.location.href='view_tenant_profile.php?edt&prf&mkm&tik_id=<?php echo time();?>&id=<?php echo $_GET['id'];?>'">
                    <?php
                }
                else
                {
                    ?>
                        <input type="submit"  class="btn btn-primary"  value="Update Profile"  id="insert" name="update" style="width:100px; height:30px; font-family:'Times New Roman', Times, serif; font-style:normal; background-color: #337ab7;color: #fff; border-color: #2e6da4;">
                        <input type="button"  class="btn btn-primary"  value="Cancel" style="width:100px; height:30px; font-family:'Times New Roman', Times, serif; font-style:normal;"onClick="window.location.href='view_tenant_profile.php?prf&mkm&tik_id=<?php echo time();?>&id=<?php echo $_GET['id'];?>'">
                    <?php
                }
            ?>
        </center>
<?php } ?>
</td></tr>

<tr>
<td><input type="hidden" name="test" id="test"/></td>
</tr>

</table>
</form>
<center>
<?php
    if(!isset($_GET['edt']))
    {
        ?>
            <script>
                $('.field_input').replaceWith(function(){
                    return '<span class='+this.className+'>'+this.value+'</span>'
                });
                $('.field_select').replaceWith(function(){
                    return '<span class='+this.className+'>' + this.options[this.selectedIndex].text + '</span>'
                });
                $('.field_date').replaceWith(function(){
                    return '<span class="">'+this.value+'</span>'
                });
            </script>
        <?php
    }
?>
<script>
 				function showRenewModal(data, vehicle_type) {
                
				const VEHICLE_CAR = 4;
                const VEHICLE_BIKE = 2;
                
				var ParkingData = '<?php echo json_encode($ParkingTypeData);?>';
                if(ParkingData != null)
				{
					ParkingData = JSON.parse("["+ParkingData+"]");	
				} 
				
				var unitNo = $('#Unit_no').html();
                var id = 0;
				
				$('.modal-body').empty();
                
				var expireDate = new Date('2023-02-22');
				expireDate.setDate(expireDate.getDate() + 1);
				var today = new Date();
				var removeSubmitBtn = false;
				if(expireDate < today)
				{
					removeSubmitBtn = true;
					var table = "<table width ='90%'>";
					table += "<tr><td style='font-size:20px;color:red;'><label>The last date to renew parking registration was 22<sup>th</sup> Feb. Please contact to society manager.</label></td></tr>";
					table += "</table>";
				
				}
				else
				{
				
				var table = "<table width ='90%'>";
                table += "<tr><td><b>Unit No : </b></td><td>" + unitNo + "</td></tr>";
				table += "<tr><td><b>Member Name : </b></td><td>" + ((data.owner_name != "") ? data.owner_name : '---') + "</td></tr>";
                table += "<tr><td><b>Parking Type : </b></td><td>" + ((ParkingData[0][(data.ParkingType)] !="") ? ParkingData[0][(data.ParkingType)] : '---') + "</td></tr>";
                table += "<tr><td><b>Parking Slot : </b></td><td>" + ((data.parking_slot != "") ? data.parking_slot : '---') + "</td></tr>";
                table += "<tr><td><b>Parking Sticker : </b></td><td>" + ((data.parking_sticker != "") ? data.parking_sticker : '---') + "</td></tr>";
				
				
                if(vehicle_type == VEHICLE_CAR) {
				  
				  id = data.mem_car_parking_id;	
				  table += "<tr><td><b>Car Owner : </b></td><td>" + ((data.car_owner != "") ? data.car_owner : '---') + "</td></tr>";
                  table += "<tr><td><b>Car Registration No : </b></td><td>" + ((data.car_reg_no != "") ? data.car_reg_no : '---') + "</td></tr>";
                  table += "<tr><td><b>Car Make : </b></td><td>" + ((data.car_make != "") ? data.car_make : '---') + "</td></tr>";
                  table += "<tr><td><b>Car Model : </b></td><td>" + ((data.car_model != "") ? data.car_model : '---') + "</td></tr>";
                  table += "<tr><td><b>Car Colour : </b></td><td>" + ((data.car_color != "") ? data.car_color : '---') + "</td></tr>";
                } 
				else if (vehicle_type == VEHICLE_BIKE) {
					
				  id = data.mem_bike_parking_id;	
                  table += "<tr><td><b>Bike Owner : </b></td><td>" + ((data.bike_owner != "") ? data.bike_owner : '---') + "</td></tr>";
				  table += "<tr><td><b>Bike Registration No : </b></td><td>" + ((data.bike_reg_no != "") ? data.bike_reg_no : '---') + "</td></tr>";
                  table += "<tr><td><b>Bike Make : </b></td><td>" + ((data.bike_make != "") ? data.bike_make : '---') + "</td></tr>";
                  table += "<tr><td><b>Bike Model : </b></td><td>" + ((data.bike_model != "") ? data.bike_model : '---') + "</td></tr>";
                  table += "<tr><td><b>Bike Colour : </b></td><td>" + ((data.bike_color != "") ? data.bike_color : '---') + "</td></tr>";
                }
				
				table += "<tr><td><b>Note : </b> </td><td width='70%'>If you have purchased a new car please add your new vehicle on profile page then submit your renew registartion. <a href='https://docs.google.com/gview?url=https://way2society.com/docs/OpenParking.docx&embedded=true' target='_blank'>T &amp; C Apply</a></td></tr>";
				table +="<tr><td></td><td></td></tr><tr><td></td><td></td></tr><tr><td></td><td></td></tr>";
				table +="<tr><td></td><td> Thanking You.</td></tr>";
				table +="<tr></tr><tr></tr><tr></tr>";
				table += "</table>";
				table += "<br>";
				table +="<div style='font-size:15px;'><b>Please read and then accept term and conditions mentioned as below.</b></div>";
				table += "<br>";
				table += "<div>";
				table += "<iframe style='height:200px;width:100%;' class='embed-responsive-item' src='https://docs.google.com/gview?url=https://way2society.com/docs/OpenParking.docx&embedded=true' allowfullscreen></iframe>";
				table += "</div>";
				table += "<br><br>";
				table += "<div><input type='checkbox' name='accept_term' id='accept_term' style='margin: 0px 15px 0px 2px;' onchange='enableOrDisableBtn();'><label for='accept_term'>Accept Term and Condition</label></div>";
				
				}
				
				$('.modal-body').append(table);
				$('#submit_renew_registration').show();
				
				if(removeSubmitBtn == true)
				{
					$('#submit_renew_registration').remove();
				}
				else
				{
					$('#submit_renew_registration').attr('onClick','submit_renew_registration('+id+','+vehicle_type+')').attr('disabled',true);	
				}
				
				
                $('#exampleModal').modal('toggle');
              }


			  function enableOrDisableBtn()
			  {
				 	if($('input[id="accept_term"]').prop("checked") == true){
						$('#submit_renew_registration').removeAttr('disabled');
					}
					else if($('input[id="accept_term"]').prop("checked") == false){
						$('#submit_renew_registration').attr('disabled',true);
					}
			  }
			  

			  function submit_renew_registration(Id, vehicle_type)
			  {
				  $('#exampleModal').modal('hide');
				  $('.modal-body').empty();
				  $.ajax({
					  url : "classes/view_tenant_profile.class.php",
					  type : "POST",
					  cache : false,
					  data : {"method":"submit_renew_registration","id":Id,"vehicle_type":vehicle_type},
					  success : function(result){
						  	
							var result = JSON.parse(result);
						  	$('.modal-body').append(result.msg);
							$('#close_renew_registration').attr('onClick','submit_renew_registration(location.reload())');
							$('#submit_renew_registration').hide();
						  	$('#exampleModal').modal('toggle');
						  }
						})
			  }
			  

function expandDetails(obj)
{
    var id = obj.id.split('_')[1]; 
    document.getElementById("exp_" + id).innerHTML = "Less";
    document.getElementById("exp_" + id).onclick = function(){ collapseDetails(this); } ;
    document.getElementById("extra_" + id).style.display = "table-row"; 
}
function collapseDetails(obj)
{
    var id = obj.id.split('_')[1]; 
    document.getElementById("exp_" + id).innerHTML = "More";
    document.getElementById("exp_" + id).onclick = function(){ expandDetails(this); } ;
    document.getElementById("extra_" + id).style.display = "none"; 
}
function memexpandDetails(obj)
{
    var mem = obj.id.split('_')[1]; 
    document.getElementById("mem_" + mem).innerHTML = "Less";
    document.getElementById("mem_" + mem).onclick = function(){ memcollapseDetails(this); } ;
    document.getElementById("memdetail_" + mem).style.display = "table-row"; 
}
function memcollapseDetails(obj)
{
    var mem = obj.id.split('_')[1]; 
    document.getElementById("mem_" + mem).innerHTML = "More";
    document.getElementById("mem_" + mem).onclick = function(){  memexpandDetails(this); } ;
    document.getElementById("memdetail_" + mem).style.display = "none"; 
}
function SendActEmail(role,unit_id,society_id,code,email,name)
{

	$.ajax({
		url : "ajax/ajax_email.php",
		type : "POST",
		data: {"mode" : "email","role" : role,"unit_id" : unit_id,"society_id" : society_id,"code" : code,"email" : email,"name" : name} ,
		success : function(data)
		{	
			
			if(data != '') 
			{
				var sIndex = data.indexOf("Success");
				if(parseInt(sIndex) > 0)
				{
					alert("Email Send Successfully");
				}
				else
				{
					alert("Error while sending Email. Please retry.");
				}

			}
			else
			{
			}
		}
	});	
}
function delete_doc(DocumentID)
{
    if(confirm("Are you sure you want to delete this attachment ?"))
    {
        $.ajax({
        url : "ajax/documents.ajax.php",
        type : "POST",
        data: {"method" : "delete","ID" : DocumentID} ,
        success : function(data)
        {   
           // alert(data);
           var sData = data.trim();
            if(sData == "1") 
            {
                alert("Document deleted Successfully");
                window.location.reload();
            }
            else
            {
                alert("Document not deleted");
            }
        }
        /*fail: function()
        {
            alert("Failed ! unable to delete selected document");
        },
        
        error: function(XMLHttpRequest, textStatus, errorThrown) 
        {
            alert("Unexpected error while deleting selected document");
        }*/
    }); 
    }
}
</script>
<?php 
		if(isset($_REQUEST['renew']))
			{ ?>
				<script>
				document.getElementById('focus_vehicle').click();
				</script>
				
			<?php }
include_once "includes/foot.php"; ?>
