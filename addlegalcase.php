<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<title>W2S - Add New Legal Case</title>
</head>

<?php 
	include_once("includes/head_s.php"); 
	include_once("classes/legalcase.class.php");
	include_once("classes/dbconst.class.php");
	$obj_initialize = new initialize($m_dbConnRoot);
	$obj_servicerequest = new legalcase($m_dbConn,$m_dbConnRoot,$m_landLordDB);
	
    if(isset($_REQUEST['edit']))
    {
		if($_REQUEST['edit']<>"")
		{ 
			$details = $obj_request->getViewDetails($_REQUEST['edit']);
			$image=$details[0]['img'];
			$image_collection = explode(',', $image);	
		}
	}
	$MemberDetails = $obj_servicerequest->m_objUtility->GetMemberPersonalDetails($_SESSION["unit_id"]);
	$UnitBlock = $_SESSION["unit_blocked"];
	$MemberUnitNo = $obj_servicerequest->m_objUtility->GetUnitNo($_SESSION["unit_id"]);
	$MemberUnitNoForDD = $obj_servicerequest->m_objUtility->GetUnitNoForDD();
	$LoginDetails = $obj_servicerequest->m_objUtility->GetMyLoginDetails();
    $loginEmailID = $LoginDetails[0]["member_id"];
?>
<script type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript" src="js/ajax_new.js"></script>
<script type="text/javascript" src="js/jsLegalcase.js"></script>
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<script type="text/javascript">
$(function()
{
	$.datepicker.setDefaults($.datepicker.regional['']);
	$(".basics").datepicker({ 
		dateFormat: "dd-mm-yy", 
		showOn: "both", 
		buttonImage: "images/calendar.gif", 
		buttonImageOnly: true 
	})
});
</script>
<script language="javascript" type="application/javascript">
function go_error()
{
	setTimeout('hide_error()',10000);	
}
function getTenantStatus(unitNo)
{
	var result = false;
	var tenantData = [];
	$.ajax
	({
		url : "ajax/ajaxlegalcase.php",
		type : "POST",
		dataType : "json",
		data: {"method" : "checkTenantStatus", "unitId":unitNo},
		success: function(data)
		{
			tenantData = data;
			console.log("data : ",data);
			if(data['tenantStatus'] == "1")
			{
				document.getElementById('error').style.display = "";
				document.getElementById('error').innerHTML = "Active tenant Exits to the selected Unit No. <a href='tenant.php?mem_id="+data['memberId']+"&tik_id=<?php echo time();?>&edit="+data['tenantId']+"&ter&sr' target = '_self'>Click here</a> to terminate exisiting lease.";	
			}
			else
			{
				document.getElementById('error').style.display = "";
				result = true;
			}
		},
		complete: function (data) 
		{
      		return(result); 
     	}
	});
	return (result);
}
function validate()
{
	var email = trim(document.getElementById('email').value);
	var priority = trim(document.getElementById('priority').value);
	var category = trim(document.getElementById('category').value);
	var summery = trim(document.getElementById('summery').value);
	var tenant_id = document.getElementById("tenant_id").value;
	var details = CKEDITOR.instances['details'].getData();
	var role = "<?php echo $_SESSION['role'];?>";
	if(role != "<?php echo ROLE_MEMBER;?>")
	{
		if(tenant_id == "0")
		{
			document.getElementById('error').style.display = "";
			document.getElementById('error').innerHTML = "Please Select Tenant";	
			document.getElementById('unit_no').focus();
			go_error();
			return false;
		}
	}
	if(email == "")
	{
		document.getElementById('error').style.display = "";
		document.getElementById('error').innerHTML = "Please Enter Valid Email ID";	
		document.getElementById('email').focus();
		go_error();
		return false;
	}
	if(priority == 0)
	{
		document.getElementById('error').style.display = "";
		document.getElementById('error').innerHTML = "Please Select Priority";
		document.getElementById('priority').focus();
		go_error();
		return false;
	}
	if(category == 0)
	{
		document.getElementById('error').style.display = "";
		document.getElementById('error').innerHTML = "Please Select Category";
		document.getElementById('category').focus();
		go_error();
		return false;
	}
	if("<?php echo $_SESSION['society_id'];?>" == 288) //For Shree Marigold
	{
		if(category == 21 || category == 22 || category == 23) //For maid, cook and driver
		{				
			var attachmentFile = trim(document.getElementById('img').value);
			if(attachmentFile == "")
			{
				document.getElementById('error').style.display = "";
				document.getElementById('error').innerHTML = "Please attach Aadhar card";
				document.getElementById('category').focus();
				go_error();
				return false;					
			}
		}
	}  
	if(summery == "")
	{
		document.getElementById('error').style.display = "";
		document.getElementById('error').innerHTML = "Please Enter Title of SR";	
		document.getElementById('summery').focus();
		go_error();
		return false;
	}
	if(category != "<?php echo $_SESSION['RENOVATION_DOC_ID'];?>" && category != "<?php echo $_SESSION['TENANT_REQUEST_ID']?>" && category != "<?php echo $_SESSION['ADDRESS_PROOF_ID']?>") <!--Vaishali--> 
	{
		if(details == "")
		{		
			document.getElementById('error').style.display = "";
			document.getElementById('error').innerHTML = "Please Enter Request Details";
			document.getElementById('details').focus();
			go_error();
			return false;	
		}
	}
	$('input[type=submit]').click(function(){
	$(this).attr('disabled', 'disabled');
});
///////////////////////////////////////////////////////////////////////////	
function LTrim( value )
{
	var re = /\s*((\S+\s*)*)/;
	return value.replace(re, "$1");
}
function RTrim( value )
{
	var re = /((\s*\S+)*)\s*/;
	return value.replace(re, "$1");
}
function trim( value )
{
	return LTrim(RTrim(value));
}
///////////////////////////////////////////////////////////////////////////	
}
function hide_error()
{
	document.getElementById('error').style.display = 'none';	
}	
var isblocked = '<?php echo $UnitBlock ?>';
if(isblocked==1)
{
  window.location.href='suspend.php';
}
$(document).ready(function()
{
	var role = "<?php echo $_SESSION['role'];?>";
	var iUnitID = 0;
	if(role == "<?php echo ROLE_MEMBER?>")
	{
		iUnitID = "<?php echo $_SESSION['unit_id'];?>";
		document.getElementById("unit_no").value = iUnitID;
		document.getElementById("tenant_id").value = iUnitID;
	}
	else
	{
		document.getElementById("tenant_id").value = iUnitID;
		document.getElementById("unit_no").value = iUnitID;
	}
});
	
function AcceptTnC(value)
{
	if(value == true)
	{
		document.getElementById("insert").disabled = false; 
	}
	else
	{
		document.getElementById("insert").disabled = true; 
	}
}
	
function goToRenovationRequest(value)
{
	
	document.getElementById("tnc_accept").style.display = "none";
	document.getElementById("Maid_TnC").style.display = "none";
	if("<?php echo $_SESSION['society_id'];?>" == 288) //For Shree Marigold
	{
		if(value == 21 || value == 22 || value == 23) //For maid, cook and driver
		{				
			document.getElementById("Maid_TnC").style.display = "contents";
			document.getElementById("insert").disabled = true; 
			document.getElementById("tnc_accept").style.display = "inline";
			document.getElementById("tnc_accept").disabled = false;
			var val = CKEDITOR.instances['details'].getData();					
			if(!(val.length > 0))
			{ 				
				var msgText = 'Note : Please read and accept terms and conditions before you submit the service request in following format.<BR><BR>-----------------------------------------<BR><BR>I want to request you to approve entry of my following service provider. <BR><BR>Name of the person (service provider) : <br /> <br /> Aadhar Card Number :<br /> Please attach softcopy of Aadhar card<br /> <br /> Timing of visit, or 12/24 hrs : <br /> <br /> Date of start of visit :  <br /> <br /> Note : <BR>';						
				CKEDITOR.instances['details'].setData(msgText);	
			}
		}
	}


	if(value == "<?php echo $_SESSION['RENOVATION_DOC_ID'];?>" || value == "<?php echo $_SESSION['TENANT_REQUEST_ID'];?>" || value == "<?php echo $_SESSION['ADDRESS_PROOF_ID'];?>") <!--Vaishali--> 
	{
		document.getElementById("priority").value = "Medium";
		document.getElementById("detailsTr").style.display = "none";
		document.getElementById("insert").value = "Next";
		document.getElementById("uploadTd1").style.display = "none";
		document.getElementById("uploadTd2").style.display = "none";
		document.getElementById("uploadTd3").style.display = "none";
		document.getElementById("uploadTd4").style.display = "none";
		document.getElementById("uploadTd5").style.display = "none";
	}
	else
	{
		document.getElementById("detailsTr").style.display = "table-row";	
		document.getElementById("insert").value = "Submit";
		document.getElementById("uploadTd1").style.display = "table-cell";
		document.getElementById("uploadTd2").style.display = "table-cell";
		document.getElementById("uploadTd3").style.display = "table-cell";
		document.getElementById("uploadTd4").style.display = "table-cell";
		document.getElementById("uploadTd5").style.display = "table-cell";
	}
}
</script>
<script>
$(document).ready(function() { 
	var socId = '<?php echo $_SESSION['landLordSocID']; ?>';
	if(socId) {
		document.getElementById("socid").value = socId;
	}
});

function selectSociety() {
	let id=document.getElementById('socid').value;
	$.ajax({
		url: "process/legalcase.process.php",
		type:"post",
		data: {'selSocID':id},
		success: function(data)
		{
			location.reload();
		}
	});
}


</script>

<?php if(isset($_POST["ShowData"]))
{?>
	<body onLoad="go_error();">
<?php 
} ?>
<br><br>
<div class="panel panel-info" id="panel" style="width:76%;display:block;margin-left: 1%;">
<div class="panel-heading" style="font-size:20px;text-align:center;">
     Create New Legal Case
</div>
<br />
<?php 
if($_SESSION['role'] && ($_SESSION['role']==ROLE_ADMIN || $_SESSION['role']==ROLE_SUPER_ADMIN))
{
	$Url = "legalcase.php?type=open";
}
else
{
	$Url = "legalcase.php?type=createdme";
} ?>
<center>
    <button type="button" class="btn btn-primary" onClick="window.location.href='<?php echo $Url;?>'">Go Back</button>
	<?php 
 if($_SESSION['res_flag']) 
 { ?>
	<h2 style="padding: 0;">Select A Landlord to Create Legal case</h2>
	<select id="socid" name="socid" style="width:auto; height:auto;" onchange="selectSociety()">
	<?php  echo $mapList = $obj_initialize->combobox("Select societytbl.society_id, concat_ws(' - ', societytbl.society_name, maptbl.desc) from mapping as maptbl JOIN society as societytbl ON maptbl.society_id = societytbl.society_id JOIN dbname as db ON db.society_id = societytbl.society_id WHERE maptbl.login_id = '" . $_SESSION['login_id'] . "' and societytbl.status = 'Y' and maptbl.status = '2' and societytbl.society_id != ".$_SESSION['society_id']." ORDER BY societytbl.society_name ASC ", $_SESSION['current_mapping']);

	?>			
</select>
<br /><br />
<?php } ?>
</center>
<br>
<center>
<form name="addrequest" id="addrequest" method="post" action="process/legalcase.process.php" enctype="multipart/form-data" onSubmit="return validate(); ">
<?php $star = "<font color='#FF0000'>*&nbsp;</font>";?>
<table align='center'>
 <input type="hidden" id="request_no" name="request_no" value="">
	<?php
		if(isset($_POST["ShowData"]))
		{
	?>
			<tr height="30"><td colspan="4" align="center"><font color="red" style="size:11px;"><b id="error"><?php echo $_POST["ShowData"]; ?></b></font></td></tr>
	<?php }
		else
		{?>
    		<tr height="30"><td colspan="4" align="center"><font color="red" style="size:11px;"><b id="error"></b></font></td></tr>
          <?php 
		 } ?>               
               
    	<tr align="left">
        	<td valign="middle"><?php //echo $star;?></td>
        	<th><b>Created By </b></th>
        	<td>&nbsp; : &nbsp;</td>
        	<td><input type="text" name="reported_by" id="reported_by" value="<?php echo $_SESSION["name"];?>"</td>
        	<td>&nbsp; &nbsp; &nbsp;</td>
        	<td valign="middle"><?php //echo $star;?></td>
        	<th><b>Created for Tenant</b></th>
        	<td>&nbsp; : &nbsp;</td>
        	<td>
        		<?php 
				if($_SESSION['landLordDB']){
					$t_data = $obj_servicerequest->getTenants($_SESSION['unit_id']);
				 }
				?>
        		<input type = "hidden" id = "unit_no" name = "unit_no" value = "0"/> 
        		<select id="tenant_id" name="tenant_id" value="" onchange="loadTanant(this.value);"> 
        		<?php 
				$options = "<option value='0'>Select Tenant</option>";
				for($i=0;$i < sizeof($t_data); $i++)
				{
					$options .="<option value='".$t_data[$i]['tenantValue']."'>".$t_data[$i]['name']."</option>";
				}
				echo $options;
				?>
            	</select>
            </td>
		</tr>
    
    	<tr>
        	<td colspan="4">
        		<input type="hidden" name="reportedby" id="reportedby" value="<?php echo $_SESSION['name'];?>"> <input type="hidden" name="landLordSocID" id="landLordSocID" value="<?php echo $_SESSION['landLordSocID'];?>">
            </td>
        </tr>
	    <tr><td><br></td></tr>
    	<tr align="left">
        	<td valign="middle"><?php echo $star;?></td>
        	<th><b>Email </b></th>
        	<td>&nbsp; : &nbsp;</td>
        	<?php 
        	if($MemberDetails[0]['email']<>'')
			{?>
      			<td>  <input type="text" name="email" value="<?php echo $MemberDetails[0]['email'];?>" id="email" /></td>
      <?php }
	  		else
	  		{?>
       			<td><input type="text" name="email" value="<?php echo $loginEmailID;?>" id="email" /></td>
      <?php }?>
      		<td>&nbsp; &nbsp; &nbsp;</td>
      		<td valign="middle"><?php //echo $star;?></td>
        	<th><b>Phone </b></th>
        	<td>&nbsp; : &nbsp;</td>
        	<td><input type="text" name="phone" value="<?php if($MemberDetails[0]['mob'] == "") { echo "0"; } else { echo $MemberDetails[0]['mob'];}?>" id="phone" onKeyPress="return blockNonNumbers(this, event, true, true);"/></td>
		</tr>   
    	<tr><td><br></td></tr>
    	<tr align="left">
        	<td valign="middle"><?php echo $star;?></td>
        	<th><b>Priority</b></th>
        	<td>&nbsp; : &nbsp;</td>
        	<td>
        		<select id="priority" name="priority">
            		<option value="0"> Please Select </option>
                	<option value="Low">Low </option>
               	 	<option value="Medium" selected>Medium </option>
                	<option value="High">High </option>
            	</select>
        	</td>
        	<td>&nbsp; &nbsp; &nbsp;</td>
        	<td valign="middle"><?php echo $star;?></td>
        	<th><b>Category</b></th>
        	<td>&nbsp; : &nbsp;</td>
        	<td>
        		<select id="category" name="category" value=""  onchange="selectCategory();"> 
            		<?php echo $combo_category = $obj_servicerequest->combobox("SELECT `id`, `category` FROM `legalcase_category` WHERE `status` = 'Y'", 0); ?>
            	</select>
       	 	</td>
		</tr>
    	<tr><td><br></td></tr>
     	<tr align="left">
        	<td valign="middle"><?php //echo $star;?></td>
        	<th><b>Case Number</b></th>
        	<td>&nbsp; : &nbsp;</td>
        	<td><input type="text" name="case_no" id="case_no" value=""></td>
        	<td>&nbsp; &nbsp; &nbsp;</td>
        	<td valign="middle"><?php echo $star;?></td>
        	<th><b>Outstanding Rent</b></th>
        	<td>&nbsp; : &nbsp;</td>
        	<td>
        		<input type="text" name="outstanding_amt" id="outstanding_amt" onKeyPress="return blockNonNumbers(this, event, true, false);" value=""/>
       	 	</td>
		</tr>
    	<tr><td><br></td></tr>
     	<tr align="left">
        	<td valign="middle"><?php //echo $star;?></td>
        	<th><b>Open On</b></th>
        	<td>&nbsp; : &nbsp;</td>
        	<td>
        		<input type="text" name="open_on" id="open_on" class="basics" value="<?php echo date('d-m-Y');?>" readonly />
        	</td>
        	<td>&nbsp; &nbsp; &nbsp;</td>
        	<td valign="middle"><?php //echo $star;?></td>
        	<th><b>Case Opening Date</b></th>
        	<td>&nbsp; : &nbsp;</td>
        	<td>
        		<input type="text" name="open_date" id="open_date" class="basics" readonly />
        	</td>
		</tr>
    	<tr><td><br></td></tr>
  
    	<tr id="upload"> 
        	<td valign="top"><?php echo $star;?></td>
        	<td><b>Title</b></td>   
        	<td>&nbsp; : &nbsp;</td>
        	<td><textarea name="summery" id="summery" rows="2" cols="50" style="max-width:10;"></textarea></td>
      		<td id = "uploadTd1">&nbsp; &nbsp; &nbsp;</td>
       		<td valign="left" id = "uploadTd2"></td>
        	<td id = "uploadTd3"><b>Upload Image</b></td>
        	<td id = "uploadTd4">&nbsp; : &nbsp;</td>
        	<td id = "uploadTd5"><input  style=" width: 200px;" name="img[]" id="img" type="file" accept=".jpg, .png, .jpeg, .gif" multiple /></td>
       		<?php 
			for($i=0;$i<sizeof($image_collection);$i++)
			{ 
				if(strlen($image_collection[$i]) >0 )
				{ ?>
        
					<a href="upload/main/<?php echo $image_collection[$i];?>"><img  style="width:50px; height:35px;" src="upload/main/<?php echo $image_collection[$i]?>"></a><a href="javascript:void(0);" onClick="del_photo('<?php echo $image_collection[$i];?>',<?php echo $_REQUEST['edit']?>);"><img style="width:15px;margin-top:-30px; margin-left: -10px;" src="images/del.gif" /></a>
      <?php
	 	 	 }
		}
		?>
       </tr>
       <tr><td><br></td></tr>
       
    	<tr align="left" id="Maid_TnC" style="display:none">
    		<td valign="left"><?php echo $star;?></td>
    		<td style="text-align:left;"><b>Terms and Conditions</b></td>
   			<td>&nbsp; : &nbsp;</td>
        	<td><input type="checkbox" name="tnc_accept" id="tnc_accept" value="1" onChange="AcceptTnC(this.checked);">&nbsp;&nbsp;<b>I accept  <a href='docs\maid_tnc.pdf' target = '_blank'>terms and conditions</a></b></td>
        
    	</tr>

    	<tr><td>&nbsp; : &nbsp;</td></tr>
    	<tr align="left" id="detailsTr">
    		<td valign="left"><?php echo $star;?></td>
    		<td style="text-align:left;"><b>Request Details</b></td>
   			<td>&nbsp; : &nbsp;</td>
        	<td colspan="6"><textarea name="details" id="details" rows="5" cols="50"></textarea></td>
    	</tr>
       	<script>			
			CKEDITOR.config.extraPlugins = 'justify';
			CKEDITOR.replace('details', {toolbar: [
         						{ name: 'clipboard', items: ['Undo', 'Redo']},{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align'], items: [ 'NumberedList', 'BulletedList','JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
        						{name: 'editing', items: ['Format', 'Bold', 'Italic', 'Underline', 'Strike'] }
   								 ],
								 height: 250,
        						 width: 630,
								 uiColor: '#14B8C4'
								 });
		</script>
        <tr>
            <td colspan="4">
            	<input type="hidden" name="request_id" id="request_id" value="<?php //echo $_SESSION['name'];?>"> 
            </td>
        </tr>
    	<tr>
        	<td colspan="4">
            &nbsp;<input type="hidden" name="memberemail" id="memberemail" /><input type="hidden" name="email1" id="email1" /> 		</td>
        </tr>    
    	<tr><td><br></td></tr>
  
    <tr>
		<td colspan="10" align="center">
        	<input type="submit" name="insert" id="insert" class="btn btn-primary" value="Submit" style="width: 150px; height: 30px; background-color: #337ab7; color:#FFF"; >
       </td>
    </tr>
    <tr><td><br></td></tr>
</table> 
</form>
</center>
</div>
</body>
<?php
if(isset($_REQUEST['edit']) && $_REQUEST['edit'] <> '')
{
?>
	<script>
        getService('edit-' + <?php echo $_REQUEST['edit'];?>);				
    </script>
<?php
}
if(isset($_REQUEST['deleteid']) && $_REQUEST['deleteid'] <> '')
{
?>
	<script>
        getService('delete-' + <?php echo $_REQUEST['deleteid'];?>);				
    </script>
<?php
}
?>
<script>
var value = jQuery("#category :selected").text();
//alert(value);
var text = $("#category option:selected").text();
//alert(text);
function loadTanant(id)
{
	const myArray = id.split("_");
	console.log(myArray);
    var tenantId = myArray[0];
	$.ajax
	({
		url : "ajax/ajaxlegalcase.php",
		type : "POST",
		dataType : "json",
		data: {"method" : "loadtenantData", "tenantId":tenantId},
		success: function(data)
		{
			console.log("data : ",data);
			document.getElementById('email').value=data[0]['email'];
			document.getElementById('phone').value=data[0]['mobile_no'];
		},
		complete: function (data) 
		{
      		return(result); 
     	}
	});
}
function selectCategory()
{
	var SelTenat = jQuery("#tenant_id :selected").text();
	var SelCategory = jQuery("#category :selected").text();
	//alert(SelTenat);
	//alert(SelCategory);
	
	document.getElementById("summery").value=SelTenat+' '+SelCategory+' Legal Case ';
}	
</script>
<?php include_once "includes/foot.php"; ?>