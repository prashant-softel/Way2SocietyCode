<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><head>
<title>W2S - Service Request</title>
</head>

<?php include_once "ses_set_s.php"; ?>
<?php include_once("includes/head_s.php");
// include_once("RightPanel.php");    
include_once("classes/home_s.class.php");
include_once("classes/dbconst.class.php");
include_once("classes/servicerequest.class.php");
include_once( "include/fetch_data.php");
include_once("include/utility.class.php");
$obj_request = new servicerequest($m_dbConn);
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
<div class="panel panel-info" style="margin-top:4%;margin-left:1%; width:76%">
 
    <div class="panel-heading" style="font-size:20px;text-align:center;">
     Legal Cases
    </div>
    <br />
<?php
if($_SESSION['is_year_freeze'] == 0)
{?>
   <center><button type="button" class="btn btn-primary" onClick="window.location.href='addservicerequest.php'">Create New Legal Case</button></center>
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
                        <th>Building No.</th>
                        <th>Flat No.</th>
                        <th>Tenant Name</th>
                        <th>Created Date</th>
                        <th>Next Due Date</th>
                        <th>Category</th>
                        <th>Outstanding Amount</th>  
                        <th>Total Judgment Amount</th>                        
                        <th>Status</th> 
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
							 $unitNo=$objfetch->GetUnitNumber($requests[$i]['unit_id']);
							 $memID=$obj->GetMemberIDNew($requests[$i]['unit_id']);
							
							$CategoryDetails = $obj_request->GetCategoryDetails( $requests[$i]['category']);
							if($prevRequestNo != $requests[$i]['request_no'])
							{
								//$status = $obj_request->getUpdatedStatus($requests[$i]['request_no']);
								$prevRequestNo = $requests[$i]['request_no'];
					?>
                    <tr>
                        <td style="text-align:center"><a href="viewrequest.php?rq=<?php echo $requests[$i]['request_id'];?>" target="_blank"><?php echo $requests[$i]['request_id'];?></a></td>
                        
                          <?php
						  $details = $obj_request->getViewDetails($requests[$i]['request_id'],true);
	
						$latestStatus = $obj_request->getLatestStatus($requests[$i]['request_id']);
						$totalAmt=0;
						if($latestStatus[0]['status'] == 'Case Closed')
						{
							$ExpenseAmountSum = $obj_request->getTotalExpense($requests[$i]['request_id']);
							$totalAmt = $details[0]['outstanding_rent']+$ExpenseAmountSum;
						}
						else
						{
							$totalAmt = 0;
						}
	
	?>	
    					<td><a href="view_member_profile.php?scm&id=<?php echo $memID[0]['member_id'];?>&tik_id=<?php echo time();?>&m&view" target="_blank" ><?php echo $unitNo;?></a></td>
                        <td><a href="tenant.php?mem_id=<?php echo $memID[0]['member_id'];?>&tik_id=<?php echo time();?>&edit=5" target="_blank" ><?php echo  $memID[0]['owner_name'];?></a></td>
                        <td><?php echo getDisplayFormatDate($requests[$i]['dateofrequest']);?></td>
                        <td><?php echo  getDisplayFormatDate($latestStatus[0]['up_hearing_date']);?></td>
                        <td><?php echo $CategoryDetails[0]['category'];?></td>
                        <!--<td><a href="viewrequest.php?rq=<?php echo $requests[$i]['request_id'];?>" target="_blank"><?php echo $requests[$i]['summery'];?></td>-->
                        <td><a href="viewrequest.php?rq=<?php echo $requests[$i]['request_id'];?>" target="_blank"><?php echo $requests[$i]['outstanding_rent'];?></a></td> 
                        <td><?php echo $totalAmt;?></td> 
                        <td><?php echo $latestStatus[0]['status'];?></td> 
                        
                        
                        
                       <!-- <td><?php// echo $status;?></td> -->
                         
                      <!--  <td align="center">  <a href="addnotice.php?id=<?php //echo $display_notices[$key]['id'];?>"><img src="images/view.jpg"  width="20"/></a> </td>-->
                          </tr>
                    <?php
							}
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