<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><head>
<title>W2S - Legal Cases</title>
</head>

<?php include_once "ses_set_s.php"; ?>
<?php include_once("includes/head_s.php");
// include_once("RightPanel.php");    
include_once("classes/home_s.class.php");
include_once("classes/dbconst.class.php");
include_once("classes/legalcase.class.php");
include_once( "classes/include/fetch_data.php");
include_once("classes/utility.class.php");
$obj_request = new legalcase($m_dbConn, $m_dbConnRoot, $m_landLordDB);
//$requests = $obj_request->GetUnitNoIfNZero($_REQUEST['id']);
$objfetch=new FetchData($m_dbConn);

$obj=new utility($m_dbConn);
//$obj=new FetchData($m_dbConn);
//echo ($requests);


/*if(isset($_REQUEST['type']) && $_REQUEST['type'] == "resolved")
{
	$requests = $obj_request->getRecords($_REQUEST['cm'],$_REQUEST['type']);
}

else if(isset($_REQUEST['type']) && $_REQUEST['type'] == "assign")
{
	$requests = $obj_request->getRecords($_REQUEST['cm'],$_REQUEST['type']);
}
else if(isset($_REQUEST['type']) && $_REQUEST['type'] =="createdme")
{
	$requests = $obj_request->getRecords($_REQUEST['cm'],$_REQUEST['type']);
}
else
{
	$requests = $obj_request->getRecords($_REQUEST['cm']);
}*/
$requests = $obj_request->getRecords1($_REQUEST['cm']);
$obj_request->getRenovationId();
//$obj_request->getTenantId();
?>

<style>
    .link{display:inline}
    .link {float: left}
	.disabled {
   pointer-events: none;
   cursor: default;
}
</style>
<!--<script type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript" src="js/ajax_new.js"></script>
<script type="text/javascript" src="js/jsServiceRequest.js"></script>
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
-->
<?php 
$width=76;
if($_SESSION['res_flag'] == 1 || $_SESSION['rental_flag'] == 1){
$width=95;
}
else
{
	$width=76;
}
?>
<div class="panel panel-info" style="margin-top:4%;margin-left:1%; width:<?php echo $width?>%">
 
    <div class="panel-heading" style="font-size:20px;text-align:center;">
     Legal Cases
    </div>
    <br />
<?php
if($_SESSION['is_year_freeze'] == 0 && $_SESSION['rental_flag'] ==0 )
{?>
   <center><button type="button" class="btn btn-primary" onClick="window.location.href='addlegalcase.php'">Create New Legal Case</button></center>
<?php }?>   
    <!--<span class="link"><a href="addservicerequest.php">Create New Service Request</a></span> -->
    <br />
    <div class="panel-body">                        
        <div class="table-responsive">
                    <!-- Nav tabs -->
        <!--<ul class="nav nav-tabs" role="tablist">
         <?php if($_SESSION['role'] && ($_SESSION['role'] <> ROLE_ADMIN && $_SESSION['role'] <> ROLE_SUPER_ADMIN ))
			{?>
        <li <?php echo (isset($_REQUEST['type']) && $_REQUEST['type'] == "assign" && $_REQUEST['type'] <>"resolved" && $_REQUEST['type'] <> "createdme" && $_REQUEST['type'] <> "open") ? 'class="active"' : ""; ?>> 
            	<a href="#assign" role="tab" data-toggle="tab" onClick="window.location.href='servicerequest.php?type=assign'">Assigned To Me</a>
    		</li>
            <?php } ?>
            
            <li <?php echo (isset($_REQUEST['type']) && $_REQUEST['type'] <> "resolved" && $_REQUEST['type'] <> "assign" && $_REQUEST['type'] <> "open"  && $_REQUEST['type'] == "createdme" ) ? 'class="active"' : ""; ?>> 
            	<a href="#createdme" role="tab" data-toggle="tab" onClick="window.location.href='servicerequest.php?type=createdme'">Created by me</a>
    		</li>
            
            <?php if($_SESSION['role'] && ($_SESSION['role']==ROLE_ADMIN || $_SESSION['role']==ROLE_SUPER_ADMIN || $_SESSION['role']==ROLE_ADMIN_MEMBER))
			{?>
            <li <?php echo (isset($_REQUEST['type']) && $_REQUEST['type'] <> "resolved" && $_REQUEST['type'] <> "assign" && $_REQUEST['type'] <> "createdme" && $_REQUEST['type'] == "open" ) ? 'class="active"' : ""; ?>> 
            	<a href="#home" role="tab" data-toggle="tab" onClick="window.location.href='servicerequest.php?type=open'">All Open</a>
    		</li>
            <?php }?>
            
            <li <?php echo (isset($_REQUEST['type']) && $_REQUEST['type'] == "resolved") ? 'class="active"' : ""; ?>>
            	<a href="#profile" role="tab" data-toggle="tab" onClick="window.location.href='servicerequest.php?type=resolved'">Resolved </a>
    		</li>
        </ul>-->
		<br/>
            <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th style="text-align:center">Case Id.</th>
                        <th style="text-align:center">Landlord Name.</th>
                        <th style="text-align:center">Tenant Name</th>
                        <th style="text-align:center">Case Assigned To</th>
                        <th style="text-align:center">Created Date</th>
                        <th style="text-align:center">Next Due Date</th>
                        <th style="text-align:center">Category</th>
                        <th style="text-align:center">Outstanding Amount</th>  
                        <th style="text-align:center">Expense Amount</th>                        
                        <th style="text-align:center">Status</th> 
                        <!--<th >Edit</th>
                        <th>Delete</th>-->                                                                      
                    </tr>
                </thead>
                <tbody>
                	<?php 
						$prevRequestNo = "";
						//echo "<pre>";
						//print_r($requests);
						//echo "</pre>";
						for($i = 0; $i < sizeof($requests); $i++)
						{
							$cnt=0;
							$count=0;
                            $landlord_name =  $m_dbConnRoot->select("SELECT `society_name` FROM `society` WHERE `society_id` = '".$requests[$i]['society_id']."'")[0]['society_name'];
							 $unitNo=$objfetch->GetUnitNumber($requests[$i]['unit_id']);
							 $memID=$obj->GetMemberIDNew($requests[$i]['unit_id']);
							 $buildingNo = $obj_request->getBuildingNo($requests[$i]['unit_id']);
							$CategoryDetails = $obj_request->GetCategoryDetails( $requests[$i]['category']);
							//if($prevRequestNo != $requests[$i]['request_no'])
							//{
								
								$prevRequestNo = $requests[$i]['request_no'];
					?>
                    <tr>
                     <td style="text-align:center"><a href="viewlegalcase.php?rq=<?php echo $requests[$i]['request_id'];?>&socid=<?php echo $requests[$i]['society_id'];?>" target="_blank"><?php echo $i+1;?></a></td>
                        <td style="text-align:center"><?php echo $landlord_name ;?></td>
                        
                          <?php
						  $details = $obj_request->getViewDetails($requests[$i]['request_id'],true);
	
						$latestStatus = $obj_request->getLatestStatus($requests[$i]['request_id'],$requests[$i]['society_id']);
						$totalAmt=0;
						
						$ExpenseAmountSum = $obj_request->getTotalExpense($requests[$i]['request_id'],$requests[$i]['society_id']);
						$totalAmt = $ExpenseAmountSum;
						
	
	?>	
                        <td style="text-align:center"><?php echo $requests[$i]['tenant_name'];?> </td>
                        <td style="text-align:center"><?php echo $requests[$i]['caseAssignedTo'];?> </td>
                        <td style="text-align:center"><?php echo getDisplayFormatDate($requests[$i]['dateofrequest']);?></td>
                        <td style="text-align:center"><?php echo  getDisplayFormatDate($latestStatus[0]['up_hearing_date']);?></td>
                        <td style="text-align:center"><?php echo $CategoryDetails[0]['category'];?></td>
                        <!--<td><a href="viewrequest.php?rq=<?php echo $requests[$i]['request_id'];?>" target="_blank"><?php echo $requests[$i]['summery'];?></td>-->
                        <td style="text-align:center"><a href="viewlegalcase.php?rq=<?php echo $requests[$i]['request_id'];?>&socid=<?php echo $requests[$i]['society_id'];?>" target="_blank"><?php echo $requests[$i]['outstanding_rent'];?></a></td> 
                        <td style="text-align:center"><?php echo $totalAmt;?></td> 
                        <td style="text-align:center"><?php echo $latestStatus[0]['status'];?></td> 
                     </tr>
                    <?php
					}
					?>
                </tbody>
               
            </table>
        </div>
        
   
      </div>
</div>
<?php /*?><?php
if(isset($_REQUEST['rq']) && $_REQUEST['rq'] <> '')
	{
		?>
			<script>
				getService('delete-' + <?php echo $_REQUEST['rq'];?>);				
			</script>
		<?php
	}
?><?php */?>

<?php include_once "includes/foot.php"; ?>