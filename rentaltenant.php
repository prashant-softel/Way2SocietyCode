<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<title>W2S - View Leave & License</title>
</head>

<?php
include_once("includes/head_s.php");
include_once ("classes/dbconst.class.php");
include_once("classes/initialize.class.php");
include_once("classes/include/dbop.class.php");
include_once("classes/rentaltenant.class.php");
include_once("classes/mem_other_family.class.php");
include_once("classes/unit.class.php");
include_once("classes/view_tenant_profile.class.php") ;

$dbConn = new dbop();
$dbConnRoot = new dbop(true);

$obj_tenant = new rentaltenant($m_dbConn, $m_dbConnRoot,$m_landLordDB, $m_landLordDBRoot);
$obj_initialize = new initialize($m_dbConnRoot);
$obj_mem_other_family = new mem_other_family($dbConn);
$obj_view_tenant_profile = new view_tenant_profile($m_dbConn, $landLordDB);

$show_member_main  = $obj_view_tenant_profile->show_tenant_main();
$PDC_data = $obj_tenant->getPDC();

$unit_details = $obj_mem_other_family->unit_details($_REQUEST['mem_id']);
$society_dets = $obj_mem_other_family->get_society_details($_SESSION['society_id']);
$UnitBlock = $_SESSION["unit_blocked"];
$obj_unit = new unit($dbConn,$dbConnRoot);

$show_wings=$obj_unit->getallwing();
$verifyStatus = $obj_unit->checkVerificationAccess();
$approveStatus = $obj_unit->checkApprovalAccess();
if(isset($_REQUEST['edit']))
{
	if($_REQUEST['edit']<>"")
	{ 
		$details = $obj_tenant->getViewDetails($_REQUEST['edit']);
		$image=$details[0]['img'];
		$document=$details[0]['Document'];
		$imageUrl = "images/noimage.png";	
		if($_SESSION['role'] == ROLE_MEMBER && $details[0]['active']==1 )
		{
			?>
			<script>
				window.location.href = 'Dashboard.php';
			</script>
			<?php
			exit();
		}
	}
}
if(isset($_REQUEST['view']))
{
	if($_REQUEST['view']<>"")
	{ 
		$details = $obj_tenant->getViewDetails($_REQUEST['view']);
		$image=$details[0]['img'];
		$document=$details[0]['Document'];
		$imageUrl = "images/noimage.png";
		if($_SESSION['role'] == ROLE_MEMBER && $details[0]['active']==1 )
		{
			?>
			<script>
				window.location.href = 'Dashboard.php';
			</script>
			<?php
			exit();
		}
	}
}
if($_SESSION['role'] != ROLE_SUPER_ADMIN)
{
	$width = "77%";
}
else
{
	$width = "85%";
}
//echo $width;
?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/pagination.css" >
<script type="text/javascript" src="js/ajax.js"></script>
	<script type="text/javascript" src="js/rentaltenant.js"></script>
    <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
    <script language="JavaScript" type="text/javascript" src="js/validate.js"></script> 
    <script language="javascript" type="application/javascript">
		function getVehicleDetailsTable()
		{
			var role = "<?php echo $_SESSION['role'];?>";
			var sContent = "";
			sContent += "<tr align='center' id='vehicle_table_tr'><td width='15%'><b>&nbsp;&nbsp;Vehicle Type</b></td>";
			if(role != "Super Admin")
			{
				sContent += "<input type='hidden' name='parkingSlot_1' id = 'parkingSlot_1' value=''>";
				sContent += "<input type='hidden' name='parkingSticker_1' id='parkingSticker_1' value=''><input type='hidden' name='parkingType_1' id='parkingType_1' value='0'>";
			}
			else
			{
				sContent += "<td width='20%'><b>Parking Slot No.</b></td><td width='20%'><b>Parking Sticker No.</b></td><td width='15%'><b>Parking Type</b></td>";
			}
	        sContent += "<td width='15%'><b>Registration No.</b></td><td width='20%'><b>Vehicle Owner Name</b></td><td width='12%'><b>Vehicle Make</b></td><td width='12%'><b>Vehicle Model</b></td><td width='15%'><b>Vehicle Colour</b></td></tr>";
			sContent += "<tr align='center'><td align='center' id='vehicleType_td_1'><select name='vehicleType_1' id='vehicleType_1' ";
			if(role != "Super Admin")
			{
				sContent += "style='width:110px;'";
			}
			else
			{
				sContent += "style='width:80px'";
			}
			sContent +="><option value = ''>Please Select</option><option value='2'>Bike</option><option value='4'>Car</option></select></td>";
			if(role != "Super Admin")
			{
				sContent += "<input type='hidden' name='parkingSlot_1' id = 'parkingSlot_1' value=''>";
			}
			else
			{
				sContent += "<td id = 'parkingSlot_td_1'><input type='text' name='parkingSlot_1' id='parkingSlot_1' style='width:80px;'/></td>";
			}
			if(role != "Super Admin")
			{
				sContent += "<input type='hidden' name='parkingSticker_1' id = 'parkingSticker_1' value=''/><input type='hidden' name='parkingType_1' id='parkingType_1' value='0'>";
			}
			else
			{
				sContent += "<td id = 'parkingSticker_td_1'><input type='text' name='parkingSticker_1' id='parkingSticker_1' style='width:80px;'/></td><td id='parkingType_td_1'><select id='parkingType_1' name='parkingType_1' style='width:80px;'></select></td>";
			}
			sContent += "<td id='carRegNo_td_1'><input type='text' name='carRegNo_1' id='carRegNo_1' style='width:100px;'/></td><td id='carOwner_td_1'><input type='text' name='carOwner_1' id='carOwner_1' style='width:130px;'/></td><td id='carMake_td_1'><input type='text' name='carMake_1' id='carMake_1' style='width:90px;'/></td><td id = 'carModel_td_1'><input type='text' name='carModel_1' id='carModel_1' style='width:90px;'/></td><td id = 'carColor_td_1'><input type='text' name='carColor_1' id='carColor_1' style='width:90px;'/></td></tr>";
			sContent +="<tr><td id='addVehicle_button' style='padding-left:1%'><input id='btnAddVehicle' type='button' value='Add' onClick='addNewVehicle()'/></td></tr>";
			document.getElementById("vehicle_table").innerHTML = sContent;
			document.getElementById("addVehicleDetails").style.display = "none";
			document.getElementById("vehiclecount").value = 1;
			//document.getElementById("addVehicle_button").style.display = "table-cell";
		}

function get_wing(society_id)
{
	document.getElementById('error').style.display = '';	
	document.getElementById('error').innerHTML = 'Wait... Fetching wing under this society';	
	remoteCall("ajax/get_wing.php","society_id="+society_id,"res_get_wing");		
}

function res_get_wing()
{
	var res = sResponse;//alert(res)
	
	document.getElementById('error').style.display = 'none';	
	
	var count = res.split('****');
	var pp = count[0].split('###');
	
	document.getElementById('wing_id').options.length = 0;
	var that = document.getElementById('society_id').value;

	for(var i=0;i<count[1];i++) 
	{		
		var kk = pp[i].split('#');
		var wing_id = kk[0];
		var wing = kk[1];
		document.getElementById('wing_id').options[i] = new Option(wing,wing_id);
	}
	document.getElementById('wing_id').options[i] = new Option('All','');
	document.getElementById('wing_id').value = '';
}

$(document).ready(function(){
	var socID = '<?php if($_SESSION['rental_flag'] == 1){echo $_SESSION['society_id'];} else{echo $_SESSION['landLordSocID'];}  ?>' ;
	if(socID) {
		document.getElementById('mapid').value = socID;
	}
});
$(document).ready(function(){
	var wingID = '<?php echo $_SESSION['default_wing_id']; ?>' ;
	if(wingID) {
		document.getElementById('wing_id').value = wingID;
	}
});

	function selectDB(){
		let dbname = document.getElementById('mapid').value;
		console.log(dbname);
		$.ajax({
		url: "process/rentaltenant.process.php",
		type:"POST",
		data: {'selSocID':dbname},
		success: function(data)
		{
			location.reload();
		}
	});
	}

	function clear_unit(wing)
	{
		if(wing=='')
		{
			document.getElementById('unit_no').value = '';	
		}
	}
	function go_error()
    {
		$(document).ready(function()
		{
			$("#error").fadeIn("slow");
		});
        setTimeout('hide_error()',8000);	
    }
    function hide_error()
    {
		$(document).ready(function()
		{
			$("#error").fadeOut("slow");
		});
    }
	
	function for_print()
	{
		document.getElementById('print').style.display = "none";
 		document.getElementById("profile_img").style.height="120px";
		var html = document.getElementById('tenant').innerHTML;
		var print_div = document.getElementById('for_printing');
		print_div.innerHTML = html;
				
		var mywindow = window.open('', 'PRINT', 'height=600,width=800');

	    mywindow.document.write('<html><head><title></title>');
    	mywindow.document.write('</head><body>');
		mywindow.document.write(document.getElementById('head_for_printing').innerHTML);
    	mywindow.document.write(document.getElementById('for_printing').innerHTML);
	    mywindow.document.write('</body></html>');

    	mywindow.document.close(); // necessary for IE >= 10
	    mywindow.focus(); // necessary for IE >= 10*/

    	mywindow.print();
	    mywindow.close();

		document.getElementById('print').style.display = "block";
		document.getElementById("profile_img").style.height="15%";
		return false;
	}
		 
		var isblocked = '<?php echo $UnitBlock ?>';
		if(isblocked==1)
		{
			window.location.href='suspend.php';
		}
		function getFirstMemberName()
		{
			var FName = document.getElementById("t_name").value;
			var MName = document.getElementById("t_mname").value;
			var LName = document.getElementById("t_lname").value;
			document.getElementById("members_1").value = FName+" "+MName+" "+LName;
		}

		$(function()
    	{
			$.datepicker.setDefaults($.datepicker.regional['']);
			$(".basics").datepicker({ 
				dateFormat: "dd-mm-yy", 
				showOn: "both", 
				buttonImage: "images/calendar.gif", 
				buttonImageOnly: true, 
				yearRange : '-2:+5'	
			})
		});
		var datePickerOptions_rec=
		{ 
            dateFormat: "dd-mm-yy", 
            showOn: "both", 
            buttonImage: "images/calendar.gif", 
            changeMonth: true,
            changeYear: true,
            yearRange: '-0:+5',
            buttonImageOnly: true
        };
		$(function()
		{
			$.datepicker.setDefaults($.datepicker.regional['']);
			$(".basics_Dob").datepicker(datePickerOptions)
		});
		var datePickerOptions=
		{ 
            dateFormat: "dd-mm-yy", 
            showOn: "both", 
            buttonImage: "images/calendar.gif", 
            changeMonth: true,
            changeYear: true,
            yearRange: '-33:-10',
			defaultDate: '01-01-1990',
            buttonImageOnly: true
        };
		$(function () 
		{
			
			$("#btnGet").bind("click", function () 
			{
        		var values = "";
        		$("input[name=members]").each(function ()
				{
            		values += $(this).val() + "\n";
        		});
        		//alert(values);
    		});
		});
		function GetDynamicTextBox(value) 
		{
    		return '<td id="members_td_'+FieldCount+'"><input name = "members_'+FieldCount+'" id = "members_'+FieldCount+'" type="text" value = "' + value + '"   style="width:140px;" /></td>&nbsp;<td id="relation_td_'+FieldCount+'"><input name = "relation_'+FieldCount+'" id = "relation_'+FieldCount+'" type="text" value = "' + value + '"  style="width:80px;"  /></td>&nbsp;&nbsp;'+'<td id="mem_dob_td_'+FieldCount+'"><input name = "mem_dob_'+FieldCount+'" id = "mem_dob_'+FieldCount+'"  class="basics_Dob" type="text" value = "' + value + '" size="10"   style="width:80px;" /></td><td id="contact_td_'+FieldCount+'">&nbsp;&nbsp;&nbsp;&nbsp;<input name = "contact_'+FieldCount+'" id = "contact_'+FieldCount+'" type="text" value = "' + value + '"  style="width:80px;"  /></td><td id="email_td_'+FieldCount+'"><input name = "email_'+FieldCount+'" id = "email_'+FieldCount+'" type="text" value = "' + value + '"  style="width:140px;"  />&nbsp;</td><td></td><td></td>';
			//class="dropdown"
		}
		function getTenant(str)
		{ //alert("hi");	
			var iden=new Array();
			iden=str.split("-");		
	//alert(iden[1]);
			if(iden[0]=="delete")
			{
				var conf = confirm("Are you sure , you want to delete it ???");
				if(conf==1)
				{			
					remoteCall("ajax/rentaltenant.ajax.php","&method="+iden[0]+"&TenantId="+iden[1],"loadchanges");
				}
			}
			else
			{
				remoteCall("ajax/rentaltenant.ajax.php","&method="+iden[0]+"&TenantId="+iden[1],"loadchanges");			
			}
		}
function del_Doc(delDocId, tenantId){
	$.ajax({
		url: "process/rentaltenant.process.php",
		type:"POST",
		data: {'delDocId':delDocId, 'tenantId':tenantId},
		success: function(data)
		{
			location.reload();
		}
	});
}
var ChequeCount=1;
var MaxChequeInputs=12;
var nocheq;
function addNewCheque()
{
	nocheq = document.getElementById('nochq').value;
	console.log(nocheq);
	for( var i =1 ; i<= nocheq;i++)
	{
		ChequeCount = ChequeCount + 1; 
		var sChequeContent = '<tr id="data_'+ChequeCount+'"><td id="sd_td_'+ChequeCount+'">&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="sd_'+ChequeCount+'", id="sd_'+ChequeCount+'" onClick = "selectType('+ChequeCount+');"/></td><td id="mode_td_'+ChequeCount+'"><select name = "mode_'+ChequeCount+'" id = "mode_'+ChequeCount+'" style="width:100px;" onChange= "selectMode(this.value,'+ChequeCount+');"><option id = "Cheque" value = "Cheque">Cheque</option><option id = "Cash" value = "Cash">Cash</option><option id = "Online_Transaction" value = "Online_Transaction">Online Transaction</option></select></td>&nbsp;&nbsp;<td id="bankName_td_'+ChequeCount+'"><input name = "bankName_'+ChequeCount+'" id = "bankName_'+ChequeCount+'" type="text" value = ""   style="width:100px;"/><input name = "pdcId_'+ChequeCount+'" id = "pdcId_'+ChequeCount+'" type="hidden" value = ""/></td>&nbsp;&nbsp;<td id="cheqno_td_'+ChequeCount+'"><input name = "cheqno_'+ChequeCount+'" id = "cheqno_'+ChequeCount+'" type="text" value = ""  style="width:100px;"  /></td>&nbsp;&nbsp;'+'<td id="cheqdate_td_'+ChequeCount+'"><input name = "cheqdate_'+ChequeCount+'" id = "cheqdate_'+ChequeCount+'"  class="basics" type="text" value = "" size="10"   style="width:80px;" /></td><td id="amount_td_'+ChequeCount+'">&nbsp;<input name = "amount_'+ChequeCount+'" id = "amount_'+ChequeCount+'" type="text" value = ""  style="width:70px;" size="30"  /></td><td id="remark_td_'+ChequeCount+'"><input name = "remark_'+ChequeCount+'" id = "remark_'+ChequeCount+'" type="text" value = ""  style="width:100px;"  /> </td><td id="status_td_'+ChequeCount+'"><select name = "status_'+ChequeCount+'" id = "status_'+ChequeCount+'" style="width:100px;"><option id = "" value = "1">Accepted</option><option id = "" value = "2">Deposited</option><option id = "" value = "3">Replaced</option><option id = "" value = "4">Rejected</option><option id = "" value = "5">Cancelled</option></select></td><td id = "delete_td_'+ChequeCount+'"><a id = "delete_'+ChequeCount+'" onClick = "deleteRow('+ChequeCount+')"><img src = "images/del.gif" style = "border: 0"/></a></td><td></td></tr>';
		if(ChequeCount <= MaxChequeInputs) //max file box allowed
		{
			$("#cheq_table").append(sChequeContent);
			$(".basics").datepicker(datePickerOptions_rec);
			document.getElementById('cheqcount').value=ChequeCount;
		}
		else
		{
			alert ("Can't add more than 12 Cheque Details.")
		}
	}
}

function selectMode(id,count)
{
	//alert(id);
	//alert(count);
	// var ChequeCount = 1;
	var mode = document.getElementById('mode_'+count).value;
	// console.log(mode);
	
	if (mode == "Cash"){
		//console.log("bankName_"+ChequeCount);
		document.getElementById("bankName_"+count).disabled = true;
		document.getElementById("bankName_"+count).style.backgroundColor='lightgray';
		document.getElementById("cheqno_"+count).disabled = true;
		document.getElementById("cheqno_"+count).style.backgroundColor='lightgray';
	}
	else if(mode == "Online_Transaction"){
		//console.log("bankName_"+ChequeCount);
		document.getElementById("bankName_"+count).placeholder = "(optional)";
		document.getElementById("bankName_"+count).style.backgroundColor='white';
		document.getElementById("cheqno_"+count).style.backgroundColor='white';
		document.getElementById("cheqno_"+count).disabled = false;
	}
	else{
		document.getElementById("bankName_"+count).disabled = false;
		document.getElementById("bankName_"+count).style.backgroundColor='white';
		document.getElementById("cheqno_"+count).disabled = false;
		document.getElementById("cheqno_"+count).style.backgroundColor='white';
	}	
			// ChequeCount = ChequeCount + 1; 


	// $.ajax({
	// 	url: "ajax/rentaltenant.ajax.php",
	// 	type: 'POST',
	// 	data:{'mode_payment': mode},
	// 	success : function(data){

	// 	}
	// });
}

function deleteRow(count){
	// console.log("clicked");
	document.getElementById("data_"+count).remove();
	
}

function selectType(count){
	var type = document.getElementById('sd_'+count);
	// console.log(type);
		if(type.checked == true){
			// console.log(type);
			document.getElementById("sd_"+count).value = "Security Deposit";
		}else{
			document.getElementById("sd_"+count).value = "Rent";
		}
	}

	// $('#sd_'+count).val('Security Deposit');

var FieldCount=1;
var MaxInputs=10;
function addNewMember()
{
	FieldCount = FieldCount + 1; 
	var sMemberContent = '<tr><td id="members_td_'+FieldCount+'"><input name = "tmemberId_'+FieldCount+'" id = "tmemberId_'+FieldCount+'" type="hidden" value = "" /><input name = "members_'+FieldCount+'" id = "members_'+FieldCount+'" type="text" value = ""   style="width:140px;" /></td>&nbsp;<td id="relation_td_'+FieldCount+'"><input name = "relation_'+FieldCount+'" id = "relation_'+FieldCount+'" type="text" value = ""  style="width:100px;"  /></td>&nbsp;<td id="emirate_td_'+FieldCount+'"><input name = "emirate_'+FieldCount+'" id = "emirate_'+FieldCount+'" type="text" value = ""  style="width:150px;"  /></td>&nbsp;&nbsp;'+'<td id="mem_dob_td_'+FieldCount+'"><input name = "mem_dob_'+FieldCount+'" id = "mem_dob_'+FieldCount+'"  class="basics_Dob" type="text" value = "" size="10"   style="width:100px;" /></td><td id="contact_td_'+FieldCount+'">&nbsp;&nbsp;&nbsp;&nbsp;<input name = "contact_'+FieldCount+'" id = "contact_'+FieldCount+'" type="text" value = ""  style="width:100px;"  /></td><td id="email_td_'+FieldCount+'"><input name = "email_'+FieldCount+'" id = "email_'+FieldCount+'" type="text" value = ""  style="width:130px;"  />&nbsp;</td><td></td><td></td></tr>';
	if(FieldCount <= MaxInputs) //max file box allowed
    {
		
		$("#mem_table").append(sMemberContent);
		$(".basics_Dob").datepicker(datePickerOptions);
		
		document.getElementById('count').value=FieldCount;
	}
	else
	{
		alert ("Can't add more than 10 Members.")
	}
}
var vehicleCount=1;
function addNewVehicle()
{
	var role = "<?php echo $_SESSION['role'];?>";
	vehicleCount = vehicleCount + 1;
	var sContent = "<tr align='center'><td align='center' id='vehicleType_td_"+vehicleCount+"'><select name='vehicleType_"+vehicleCount+"' id='vehicleType_"+vehicleCount+"' ";
	if(role != "Super Admin")
	{
		sContent += "style='width:110px;'";
	}
	else
	{
		sContent += "style='width:80px'";
	}
	sContent +="><option value = ''>Please Select</option><option value='2'>Bike</option><option value='4'>Car</option></select></td>";
    if(role != "Super Admin")
	{
		sContent += "<input type='hidden' name='parkingSlot_"+vehicleCount+"' id = 'parkingSlot_"+vehicleCount+"' value=''>";
	}
	else
	{
				sContent += "<td id = 'parkingSlot_td_"+vehicleCount+"'><input type='text' name='parkingSlot_"+vehicleCount+"' id='parkingSlot_"+vehicleCount+"' style='width:80px;'/></td>";
	}
	if(role != "Super Admin")
	{
		sContent += "<input type='hidden' name='parkingSticker_"+vehicleCount+"' id = 'parkingSticker_"+vehicleCount+"' value=''/><input type='hidden' name='parkingType_"+vehicleCount+"' id='parkingType_"+vehicleCount+"' value='0'>";
	}
	else
	{
				sContent += "<td id = 'parkingSticker_td_"+vehicleCount+"'><input type='text' name='parkingSticker_"+vehicleCount+"' id='parkingSticker_"+vehicleCount+"' style='width:80px;'/></td><td id='parkingType_td_"+vehicleCount+"'><select id='parkingType_"+vehicleCount+"' name='parkingType_"+vehicleCount+"' style='width:80px;'></select></td>";
	}
	sContent += "<td id='carRegNo_td_"+vehicleCount+"'><input type='text' name='carRegNo_"+vehicleCount+"' id='carRegNo_"+vehicleCount+"' style='width:100px;'/></td><td id='carOwner_td_"+vehicleCount+"'><input type='text' name='carOwner_"+vehicleCount+"' id='carOwner_"+vehicleCount+"' style='width:130px;'/></td><td id='carMake_td_"+vehicleCount+"'><input type='text' name='carMake_"+vehicleCount+"' id='carMake_"+vehicleCount+"' style='width:90px;'/></td><td id = 'carModel_td_"+vehicleCount+"'><input type='text' name='carModel_"+vehicleCount+"' id='carModel_"+vehicleCount+"' style='width:90px;'/></td><td id = 'carColor_td_"+vehicleCount+"'><input type='text' name='carColor_"+vehicleCount+"' id='carColor_"+vehicleCount+"' style='width:90px;'/></td></tr>";	
	$("#vehicle_table").append(sContent);
	document.getElementById('vehiclecount').value=vehicleCount;
			document.getElementById("parkingType_"+vehicleCount).innerHTML = "<?php echo $obj_tenant->combobox07("Select `Id`,`ParkingType` from `parking_type` where Status = 'Y' AND IsVisible = '1'", "0");?>";
}

function getTotalMonth()
{
	var startDate = document.getElementById('start_date').value;
	var endDate = document.getElementById('end_date').value;	
	
	if(startDate != '' && endDate != '')
	{
		var firstDate = startDate.split('-');
		var secondDate = endDate.split('-');
		
		var year1 = parseInt(firstDate[2]);
		var year2 =parseInt(secondDate[2]);
		
		var month1 = parseInt(firstDate[1]);
		var month2 = parseInt(secondDate[1]);
		
		var monthDiff = (year2 - year1) * 12 + (month2 - month1)+1;
	
		document.getElementById('Lease_Period').value = monthDiff;		
	}
}
function enable_company()
{
	var checkBox = document.getElementById("company");
  	if(checkBox.checked == true)
	{
    	document.getElementById('license_no').style.display='table-row';
		document.getElementById('license_authority').style.display='table-row'; 
		document.getElementById('licence').style.display='table-cell'; 
		document.getElementById('licenceauth').style.display='table-cell';
		
 	} 
	else 
	{
     	document.getElementById('license_no').style.display='none';
		document.getElementById('license_authority').style.display='none';
		document.getElementById('licence').style.display='none';
		document.getElementById('licenceauth').style.display='none';

  	}
}

function updateEndDate()
{
	var startDate = document.getElementById('start_date').value;
	var totalMonth = parseInt(document.getElementById('Lease_Period').value);
	var firstDate = startDate.split('-');
	var year1 = parseInt(firstDate[2]);
	var month1 = parseInt(firstDate[1]) - 1;
	var date1 = parseInt(firstDate[0]);		
	
	var dt = new Date(year1,month1,date1);
	
	var endDate = dt.setMonth(dt.getMonth()+totalMonth); // It's return date in milisecond
	
	var endDate = new Date(endDate);
	
	var year2 = endDate.getFullYear();
	var month2 = endDate.getMonth()+1; // in js jan month start from 1 due to that we addded 1
	var date2 = endDate.getDate();
	
	if (date2 < 10) { 
		date2 = '0' + date2; 
	} 
	if (month2 < 10) { 
		month2 = '0' + month2; 
	}
	
	var updatedEndDate = date2+'-'+month2+'-'+year2;	
	document.getElementById('end_date').value = updatedEndDate;
}

function loadchanges()
{	
	var a		= sResponse.trim();			
	var arr1	= new Array();
	var arr2	= new Array();
	arr1		= a.split("@@@");
	arr2		= arr1[1].split("#");		
	
	var tenant_details = new Array();
	tenant_details = JSON.parse(arr1[1]);
	console.log(tenant_details);
	//alert('test');		
	var role = "<?php echo $_SESSION['role'];?>";
	if(arr1[0] == "edit" || arr1[0] == "renew")
	{	
		if(tenant_details[0]['isCompany'] == 1){
			document.getElementById("company").checked= true;
			enable_company();
			document.getElementById("license_no").value= tenant_details[0]['license_no'];
			document.getElementById("license_authority").value= tenant_details[0]['license_authority'];
		}	
		document.getElementById("mapid").value= <?php echo $_SESSION['society_id']?>;
		document.getElementById("t_name").value= tenant_details[0]['tenant_name'];
		document.getElementById("note").value= tenant_details[0]['note'];
		document.getElementById("wing_id").value= tenant_details[0]['wing_id'];
		document.getElementById("unit_no").value= tenant_details[0]['unit_id'];
		document.getElementById('profileHref').src = tenant_details[0]['img'];
		document.getElementById('profile_img').src = tenant_details[0]['img'];
		document.getElementById('profile_img').value = tenant_details[0]['img'];
		if(arr1[0] == "edit"){
			document.getElementById('start_date').value=tenant_details[0]['start_date'];
		}
		
		if(arr1[0] == "edit"){
			if(bTerminate == false)
			{
				document.getElementById('end_date').value=tenant_details[0]['end_date'];
			}
			else
			{
				$("#end_date").datepicker().datepicker("setDate", new Date());
			}
		}
		//document.getElementById('Lease_Period').value = tenant_details[0]['total_month'];
		
		document.getElementById('agent').value=tenant_details[0]['agent_name'];
		document.getElementById('agent_no').value=tenant_details[0]['agent_no'];
		document.getElementById('security_deposit').value=tenant_details[0]['security_deposit'];
		document.getElementById('annual_rent').value=tenant_details[0]['annual_rent'];
		document.getElementById('contract_value').value=tenant_details[0]['contract_value'];
	
		CKEDITOR.instances['note'].setData(tenant_details[0]['note']);
		
		var memberAry = new Array();
		memberAry = tenant_details[0]['members'];
		//alert( memberAry.length);
		for(var iCnt = 1; iCnt <= memberAry.length-1; iCnt++)
		{			
				
				addNewMember();
		}
		document.getElementById('members_1').value = tenant_details[0]['tenant_name']+" "+tenant_details[0]['tenant_MName']+" "+tenant_details[0]['tenant_LName'];
		document.getElementById('tmemberId_1').value = tenant_details[0]['tmember_id'];
		document.getElementById('emirate_1').value = tenant_details[0]['emirate_no'];
		document.getElementById('mem_dob_1').value = tenant_details[0]['dob'];
		document.getElementById('contact_1').value = tenant_details[0]['mobile_no'];
		document.getElementById('email_1').value = tenant_details[0]['email'];
		document.getElementById('relation_1').value = "self";
		for(var iCnt = 0; iCnt < memberAry.length; iCnt++)
		{
			document.getElementById('tmemberId_'+(iCnt+1)).value = memberAry[iCnt]['tmember_id'];
			document.getElementById('members_'+(iCnt+1)).value = memberAry[iCnt]['mem_name'];
			document.getElementById('relation_'+(iCnt+1)).value = memberAry[iCnt]['relation'];
			document.getElementById('emirate_'+(iCnt+1)).value = memberAry[iCnt]['emirate_no'];
			document.getElementById('mem_dob_'+(iCnt+1)).value = memberAry[iCnt]['mem_dob'];
			document.getElementById('contact_'+(iCnt+1)).value = memberAry[iCnt]['contact_no'];
			document.getElementById('email_'+(iCnt+1)).value = memberAry[iCnt]['email'];
		}

		//--------------------------Post Dated Cheque------------------------------------//
		var chequeAry = new Array();
		if(arr1[0] == "edit"){
			chequeAry = tenant_details[0]['cheques'];
		}
		// console.log(chequeAry);
		for(var iCnt = 1; iCnt <= chequeAry.length-1; iCnt++)
		{			
				addNewCheque();
		}

		for(var iCnt = 0; iCnt < chequeAry.length; iCnt++)
		{
			if(chequeAry[iCnt]['type'] == "Security Deposit"){
				document.getElementById('sd_'+(iCnt+1)).checked = true;
			}
			document.getElementById('sd_'+(iCnt+1)).value = chequeAry[iCnt]['type'];
			document.getElementById('pdcId_'+(iCnt+1)).value = chequeAry[iCnt]['pdc_id'];
			document.getElementById('mode_'+(iCnt+1)).value = chequeAry[iCnt]['mode_of_payment'];
			document.getElementById('bankName_'+(iCnt+1)).value = chequeAry[iCnt]['bank_name'];
			document.getElementById('cheqno_'+(iCnt+1)).value = chequeAry[iCnt]['cheque_no'];
			document.getElementById('cheqdate_'+(iCnt+1)).value = chequeAry[iCnt]['cheque_date'];
			document.getElementById('amount_'+(iCnt+1)).value = chequeAry[iCnt]['amount'];
			document.getElementById('remark_'+(iCnt+1)).value = chequeAry[iCnt]['remark'];
			document.getElementById('status_'+(iCnt+1)).value = chequeAry[iCnt]['status'];
		}
	
		//---------------------------Vehicle Details-----------------------------------------------
				if(tenant_details[0]['vehicleCount'] != "0")
		{
			var carAry = new Array();
			carAry = tenant_details[0]['carDetails'];
			var vCnt = 0;
		
			for(vCnt = 1; vCnt <= tenant_details[0]['vehicleCount']-1; vCnt++)
			{			
				//alert("add");
				addNewVehicle();
			}
			if(carAry)
			{
				for(vCnt =  0; vCnt < carAry.length; vCnt++)
				{
					document.getElementById('vehicleType_'+(vCnt+1)).value = "4";
					if(role == "Super Admin")
					{
									document.getElementById('parkingSlot_'+(vCnt+1)).value = carAry[vCnt]['parking_slot'];
									document.getElementById('parkingSticker_'+(vCnt+1)).value = carAry[vCnt]['parking_sticker'];
						if(carAry[vCnt]['ParkingType'] == "0")
						{	
										document.getElementById('parkingType_'+(vCnt+1)).value = "1";
						}
						else
						{
										document.getElementById('parkingType_'+(vCnt+1)).value = carAry[vCnt]['ParkingType'];
									}
								}
							//alert ('carRegNo_'+(vCnt+1));
								document.getElementById('carRegNo_'+(vCnt+1)).value = carAry[vCnt]['car_reg_no'];
								document.getElementById('carOwner_'+(vCnt+1)).value = carAry[vCnt]['car_owner'];	
								document.getElementById('carMake_'+(vCnt+1)).value = carAry[vCnt]['car_make'];	
								document.getElementById('carModel_'+(vCnt+1)).value = carAry[vCnt]['car_model'];
								document.getElementById('carColor_'+(vCnt+1)).value = carAry[vCnt]['car_color'];	
						}
						vCnt = vCnt + 1;
					}
					else
					{
						vCnt = 1;
					}
			var bikeAry = new Array();
			bikeAry = tenant_details[0]['bikeDetails'];
				
				if(bikeAry != null)
				{
					for(var iCnt = 0; iCnt < bikeAry.length; iCnt++)
					{
					//alert(vCnt);
						document.getElementById('vehicleType_'+(vCnt)).value = "2";
						if(role == "Super Admin")
						{
							if(bikeAry[iCnt]['ParkingType'] == "0")
							{	
								document.getElementById('parkingType_'+(iCnt+1)).value = "Not Specified";
							}
							else
							{
								document.getElementById('parkingType_'+(iCnt+1)).value = bikeAry[iCnt]['ParkingType'];
							}
						}
						document.getElementById('parkingSlot_'+(vCnt)).value = bikeAry[iCnt]['parking_slot'];
						document.getElementById('parkingSticker_'+(vCnt)).value = bikeAry[iCnt]['parking_sticker'];
						document.getElementById('carRegNo_'+(vCnt)).value = bikeAry[iCnt]['bike_reg_no'];
						document.getElementById('carOwner_'+(vCnt)).value = bikeAry[iCnt]['bike_owner'];
						document.getElementById('carMake_'+(vCnt)).value = bikeAry[iCnt]['bike_make'];		
						document.getElementById('carModel_'+(vCnt)).value = bikeAry[iCnt]['bike_model'];
						document.getElementById('carColor_'+(vCnt)).value = bikeAry[iCnt]['bike_color'];	
						vCnt = vCnt + 1;
					}
						
				}
						document.getElementById("addVehicleDetails").style.display = "none";	
		}
		else
		{

			document.getElementById("vehicle_table").innerHTML = "No Records found";	
					document.getElementById("addVehicleDetails").style.display = "table-cell";	
		}
		//-------------------------------------------------Document details-----------------------------
		var DocumentAry = new Array();
		DocumentAry = tenant_details[0]['documents'];
		
		var docTable = "<table>";
		for(var iCnt = 0; iCnt < DocumentAry.length; iCnt++)
		{
			docTable += '<tr>';
			docTable += '<td><a href='+DocumentAry[iCnt]['documentLink']+' target=_blank>' + DocumentAry[iCnt]['Name'] + '</a></td>';
			
			if(tenant_details[0]['active'] == 0)
			{
				docTable += '<td><a href='+DocumentAry[iCnt]['documentLink']+' target=_blank><img id="profileHref" src="'+DocumentAry[iCnt]['documentLink']+'" width="20px"></a></td>';	
				docTable += '<td><a style="color:red" href="#" onclick="del_Doc('+DocumentAry[iCnt]["doc_id"] + ',' +  tenant_details[0]["tenant_id"] + ')" ><img src="images/del.gif" border="0" alt="del" style="cursor:pointer;"></a></td>';	
			}
			docTable += '</tr>';
		}
		docTable += '<table>';
		document.getElementById('doc').innerHTML = docTable;
		document.getElementById("profile_img").src = tenant_details[0]["img"];
		document.getElementById("profileHref").href = tenant_details[0]["img"];
		// document.getElementById("insert").value = "Update";																		
	}
	else if(arr1[0] == "view")
	{		
		
		document.getElementById('data_table').style.border = "1px solid #cccccc";
		
		var t_name = tenant_details[0]['tenant_name'];
		document.getElementById('td_1').innerHTML = t_name;
		document.getElementById('td_n2').innerHTML = tenant_details[0]['tenant_MName'];
		document.getElementById('td_n3').innerHTML = tenant_details[0]['tenant_LName'];
		var start_date = tenant_details[0]['start_date'];
		document.getElementById('td_2').innerHTML = start_date;
		
		if(bTerminate == false)
		{
			var end_date = tenant_details[0]['end_date'];
			document.getElementById('td_3').innerHTML = end_date;
		}
		
		
		document.getElementById('td_10').innerHTML = tenant_details[0]['total_month'];
		
		var agent_name = tenant_details[0]['agent_name'];
		document.getElementById('td_4').innerHTML = agent_name;
		var agent_no = tenant_details[0]['agent_no'];
		document.getElementById('td_5').innerHTML = agent_no;
		
		var active = tenant_details[0]['active'];
		var p_varification = tenant_details[0]['p_varification'];
		var leaveAndLicenseAgreement = tenant_details[0]['leaveAndLicenseAgreement'];
		
		document.getElementById('verified').innerHTML = (active == 1) ? 'Yes' : 'No';
		document.getElementById('pVerified').innerHTML = (p_varification == 1) ? 'Yes' : 'No';
		document.getElementById('leaveAndLicenseAgreement').innerHTML = (leaveAndLicenseAgreement == 1) ? 'Yes' : 'No';
		
		
		document.getElementById('doc_Id').style.display = "none";
		document.getElementById('add_button').style.display = "none";
		document.getElementById('textarea').style.display = "none";
		document.getElementById('to_show_note').innerHTML = tenant_details[0]['note'];
		var memberAry = new Array();
		memberAry = tenant_details[0]['members'];
		for(var iCnt = 1; iCnt <= memberAry.length-1; iCnt++)
		{			
			addNewMember();
		}
		
		document.getElementById('members_td_1').innerHTML = tenant_details[0]['tenant_name']+" "+tenant_details[0]['tenant_MName']+" "+tenant_details[0]['tenant_LName'];
		document.getElementById('mem_dob_td_1').innerHTML = tenant_details[0]['dob'];
		document.getElementById('contact_td_1').innerHTML = tenant_details[0]['mobile_no'];
		document.getElementById('email_td_1').innerHTML = tenant_details[0]['email'];
		document.getElementById('relation_td_1').innerHTML = "Self";
		for(var iCnt = 1; iCnt < memberAry.length; iCnt++)
		{
			document.getElementById('members_td_'+(iCnt+1)).innerHTML = memberAry[iCnt]['mem_name'];
			document.getElementById('relation_td_'+(iCnt+1)).innerHTML = memberAry[iCnt]['relation'];
			document.getElementById('mem_dob_td_'+(iCnt+1)).innerHTML = memberAry[iCnt]['mem_dob'];
			document.getElementById('contact_td_'+(iCnt+1)).innerHTML = memberAry[iCnt]['contact_no'];
			document.getElementById('email_td_'+(iCnt+1)).innerHTML = memberAry[iCnt]['email'];
		}
		document.getElementById('mem_table').style.border = '1px solid #cccccc';
		document.getElementById('mem_table_tr').style.border = '1px solid #cccccc';
		document.getElementById('chkCreateLogin').style.display = "none";
		document.getElementById('other_send_commu_emails').style.display = "none";
		document.getElementById('create_login').style.display = "none";
		document.getElementById('send_emails').style.display = "none";


		//-----------------------------------Post Dated Cheque------------------------------------------------------//

		var chequeAry = new Array();
		chequeAry = tenant_details[0]['cheques'];
		for(var iCnt = 1; iCnt <= chequeAry.length-1; iCnt++)
		{			
			addNewCheque();
		}
		// document.getElementById('mode_td_1').innerHTML = tenant_details[0]['mode_of_payment'];
		// document.getElementById('bankName_td_1').innerHTML = tenant_details[0]['bank_name'];
		// document.getElementById('cheqno_td_1').innerHTML = tenant_details[0]['cheqno'];
		// document.getElementById('cheqdate_td_1').innerHTML = tenant_details[0]['cheqdate'];
		// document.getElementById('amount_td_1').innerHTML = tenant_details[0]['amount'];
		// document.getElementById('remark_td_1').innerHTML = tenant_details[0]['remark'];
		for(var iCnt = 1; iCnt < chequeAry.length; iCnt++)
		{
			document.getElementById('mode_td_'+(iCnt+1)).value = chequeAry[iCnt]['mode_of_payment'];
			document.getElementById('bankName_td_'+(iCnt+1)).innerHTML = chequeAry[iCnt]['bank_name'];
			document.getElementById('cheqno_td_'+(iCnt+1)).innerHTML = chequeAry[iCnt]['cheqno'];
			document.getElementById('cheqdate_td_'+(iCnt+1)).innerHTML = chequeAry[iCnt]['cheqdate'];
			document.getElementById('amount_td_'+(iCnt+1)).innerHTML = chequeAry[iCnt]['amount'];	
			document.getElementById('remark_td_'+(iCnt+1)).innerHTML = chequeAry[iCnt]['remark'];			
		}
		//-------------------------------------------Vehicle Details---------------------------------------------
		if(tenant_details[0]['vehicleCount'] != 0)
		{
			var carAry = new Array();
			carAry = tenant_details[0]['carDetails'];
			var vCnt = 0;
			for(vCnt = 1; vCnt <= tenant_details[0]['vehicleCount']-1; vCnt++)
			{			
				addNewVehicle();
			}
			if(carAry)
			{
				for(vCnt =  0; vCnt < carAry.length; vCnt++)
				{
					document.getElementById('vehicleType_td_'+(vCnt+1)).innerHTML = "Car";
					if(role == "Super Admin")
					{
						document.getElementById('parkingSlot_td_'+(vCnt+1)).innerHTML = carAry[vCnt]['parking_slot'];
						document.getElementById('parkingSticker_td_'+(vCnt+1)).innerHTML = carAry[vCnt]['parking_sticker'];
						if(carAry[vCnt]['ParkingType'] == "0")
						{
							document.getElementById('parkingType_td_'+(vCnt+1)).innerHTML = "Not Specified";
						}
						else
						{
							document.getElementById('parkingType_td_'+(vCnt+1)).innerHTML = carAry[vCnt]['ParkingType'];
						}
					}
					document.getElementById('carRegNo_td_'+(vCnt+1)).innerHTML = carAry[vCnt]['car_reg_no'];
					document.getElementById('carOwner_td_'+(vCnt+1)).innerHTML = carAry[vCnt]['car_owner'];	
					document.getElementById('carMake_td_'+(vCnt+1)).innerHTML = carAry[vCnt]['car_make'];	
					document.getElementById('carModel_td_'+(vCnt+1)).innerHTML = carAry[vCnt]['car_model'];
					document.getElementById('carColor_td_'+(vCnt+1)).innerHTML = carAry[vCnt]['car_color'];	
				}
				if(tenant_details[0]['carDetails'].length == 0)
				{
					vCnt = 1;
				}
				else					
				{
					vCnt = vCnt + 1;
				}
			}
			var bikeAry = new Array();
			bikeAry = tenant_details[0]['bikeDetails'];
			if(tenant_details[0]['bikeDetails'] != null)
			{
				for(var iCnt = 0; iCnt < bikeAry.length; iCnt++)
				{
					document.getElementById('vehicleType_td_'+(vCnt)).innerHTML = "Bike";
					if(role == "Super Admin")
					{
						document.getElementById('parkingSlot_td_'+(vCnt)).innerHTML = bikeAry[iCnt]['parking_slot'];
						document.getElementById('parkingSticker_td_'+(vCnt)).innerHTML = bikeAry[iCnt]['parking_sticker'];
						if(bikeAry[iCnt]['ParkingType'] == "0")
						{
							document.getElementById('parkingType_td_'+(vCnt)).innerHTML = "Not Specified";
						}
						else
						{
							document.getElementById('parkingType_td_'+(vCnt)).innerHTML = bikeAry[iCnt]['ParkingType'];
						}
					}
					document.getElementById('carRegNo_td_'+(vCnt)).innerHTML = bikeAry[iCnt]['bike_reg_no'];
					document.getElementById('carOwner_td_'+(vCnt)).innerHTML = bikeAry[iCnt]['bike_owner'];
					document.getElementById('carMake_td_'+(vCnt)).innerHTML = bikeAry[iCnt]['bike_make'];		
					document.getElementById('carModel_td_'+(vCnt)).innerHTML = bikeAry[iCnt]['bike_model'];
					document.getElementById('carColor_td_'+(vCnt)).innerHTML = bikeAry[iCnt]['bike_color'];	
					vCnt = vCnt + 1;
				}
			}
			document.getElementById('addVehicle_button').style.display = "none";
		}
		else
		{
			document.getElementById("vehicle_table").innerHTML = "No Records found.";
			document.getElementById("addVehicleDetails").style.display = "table-cell";
		}
		document.getElementById("addVehicleDetails").style.display = "none";
		document.getElementById('vehicle_table').style.border = '1px solid #cccccc';
		document.getElementById("profilePhotoSpan").style.display = "none";
		document.getElementById("profilePhoto").style.display = "none";
		document.getElementById("profile_img").src = tenant_details[0]["img"];
		document.getElementById("profileHref").href = tenant_details[0]["img"];
		var DocumentAry = new Array();
		DocumentAry = tenant_details[0]['documents'];
		var docTable = "<table>";

				if(DocumentAry.length > 0)
				{
					for(var iCnt = 0; iCnt < DocumentAry.length; iCnt++)
					{
						docTable += "<tr>";
						docTable += "<td><a href='"+DocumentAry[iCnt]['documentLink']+"' target=_blank>"+(iCnt+1)+". "+ DocumentAry[iCnt]['Name'] + "</a></td><td><a href='#' onclick='del_Doc("+DocumentAry[iCnt]["doc_id"] + "," +  tenant_details[0]["tenant_id"] + ") '></a></td>";
						docTable += "</tr>";
					}
				}
				else
				{
					docTable += "<tr><td colspan='2'>No Documents attached.</td></tr>";
				}
				docTable += "<table>";
				//console.log(docTable);
				document.getElementById('doc').innerHTML = docTable;
				//document.getElementById("insert").value = "Update";
				document.getElementById('insert').style.display = "none";	
				document.getElementById('print').style.display = "block";
			}
			else if(arr1[0] == "delete")
			{			
				window.location.href ="show_tenant.php";
			}
			
		}
		
function removeData(count)
{
	document.getElementById("doc_data_"+count).remove();
}		
		
		var DocCount=4;
		<?php if(isset($_REQUEST['edit']))
		{ ?>
				var DocCount=1;
		<?php }?>
		var MaxInputs=10;
		$(function ()
		{
			$("#btnAddDoc").bind("click", function ()
			{
		//	alert("Add");
				if(FieldCount <= MaxInputs) //max file box allowed
                {
					DocCount++; 
					document.getElementById('doc_count').value=DocCount;
				}
	    		var div = $("<tr id = 'doc_data_"+DocCount+"' />");
        		div.html(GetDynamicFileBox(""));
        		$("#doc_Id").append(div);
			});
			$("#btnGet").bind("click", function ()
			{
        		var values = "";
        		$("input[name=upload]").each(function () {
            	values += $(this).val() + "\n";
        	});
        	//alert(values);
    	});
		});
		function GetDynamicFileBox(value)
		{
			return '<td><input name = "doc_name_'+DocCount+'" id = "doc_name_'+DocCount+'" type="text" value = "' + value + '" /></td>'+'<td><input name = "userfile'+DocCount+'" id = "userfile'+DocCount+'" type="file" value = "' + value + '" /></td>'+
					'<td id = "remove_td_'+DocCount+'"><a id = "remove_'+DocCount+'" onClick = "removeData('+DocCount+')"><img src = "images/del.gif" style = "border: 0"/></a></td>'
		}
	</script>
    <style>
		img:hover 
		{
  			box-shadow: 0 0 2px 1px rgba(0, 140, 186, 0.5);
		}
		
	</style>
</head>
<body>


<div id="middle">
	<div class="panel panel-info" id="panel" style="display:block; margin-top:6%;width:<?php echo $width;?>;">
    	<?php 
		$tenantAction = "";
		$actionPage2 = "memberProfile";			
		if(isset($_REQUEST['edit']))
	  	{
		?>
   			<div class="panel-heading" id="pageheader">Edit Leave & License</div>
      	<?php 
			if(isset($_REQUEST['ter']))
			{
				$tenantAction = "ter";
				if(isset($_REQUEST['sr']))
				{
					$actionPage2 = "serviceRequest";
				}
			}
			else
			{
				$tenantAction = "edit";
			}
		}
		else if(isset($_REQUEST['view']))
	  	{
		?>
      		<div class="panel-heading" id="pageheader">View Leave & License</div>
        <?php
			$tenantAction = "view";
	  	}
	  	else if(isset($_REQUEST['ter']))
	  	{
		?>
			<div class="panel-heading" id="pageheader">Terminate Lease</div>
		<?php  
			
		}
	  	else
		{
		?>
        	<div class="panel-heading" id="pageheader">Add Tenant</div>
			
        <?php
			$tenantAction = "add";
			if(isset($_REQUEST['sr']))
			{
				$tenantResult = $obj_tenant->checkTenantStatus($_SESSION['serviceRequestDetails']['unit_no']);
				//var_dump($tenantResult);
				if($tenantResult['tenantStatus'] == 1)
				{
					
					echo "<div style='text-align:center'><br/><font color='red'><span style='text-align:center'>Active tenant Exits to the selected Unit No. <a href='tenant.php?mem_id=".$tenantResult['memberId']."&tik_id=".time()."&edit=".$tenantResult['tenantId']."&ter&sr' target = '_self'>Click here</a> to terminate exisiting lease.</span></font><br></div>";
				?>
                <script>
					$( document ).ready(function()
					{
						document.getElementById("insert").style.display = "none";
					});
				</script>
				<?php
                }
				else
				{
					
				}
				$actionPage2 = "serviceRequest";
			}
        }
	?>
	<br>
		<button type="button" class="btn btn-primary btn-circle" onClick="history.go(-1);" style="float:left;margin-left:10%" id="btnBack"><i class="fa  fa-arrow-left"></i></button>
		<center>
		<?php 
		if(isset($_SESSION['role']) && $_SESSION['role']==ROLE_MEMBER)
		{
		?>
			<input type="button" class="btn btn-primary" onClick="window.location.href='view_member_profile.php?prf&id=<?php echo $_GET['mem_id'];?>'"  style="float:right;margin-right:40%" value="Go to profile view">
		<?php
        }
		else
		{
		?>
			<input type="button" class="btn btn-primary" onClick="window.location.href='view_member_profile.php?scm&id=<?php echo $_GET['mem_id'];?>&tik_id=<?php echo time();?>&m'" style="float:right;margin-right:40%" value="Go to profile view">
<?php 
		} 
		?>
		</center>
		<br>
        <center>
		<?php if(isset($_REQUEST['ter']))
		{
		?>
			<p style="font-size:12px; color:red; font-weight:bold;padding-top:5%">Please update Lease end date to new date on which you want to terminate the lease.</p>
		<?php 
		}?>
		</center>
		<?php 
		if(isset($_POST['ShowData']) || isset($_REQUEST['msg']))
		{ 
		?>
			<body onLoad="go_error();">
		<?php 
		}
		$star = "<font color='#FF0000'>*</font>";
		if(isset($_REQUEST['msg']))
		{
			$msg = "Sorry !!! You can't delete it. ( Dependency )";
		}
		else if(isset($_REQUEST['msg1']))
		{
			$msg = "Deleted Successfully.";
		}
		else
		{
		}
		?>
		<form name="tenant" id="tenant" method="post" action="process/rentaltenant.process.php" enctype="multipart/form-data"  onSubmit="return val();">
		<input type="hidden" name="form_error"  id="form_error" value="<?php echo $_REQUEST["form_error"]; ?>" />
		<input type="hidden" name="ssid" value="<?php echo $_GET['ssid'];?>">
		<input type="hidden" name="wwid" value="<?php echo $_GET['wwid'];?>">
		<center>
		<br/>
		<br/>
		<table align='center' id="data_table" width="90%" style="border:0.5;">
		<input type = "hidden" name="tenantAction" id = "tenantAction" value = "<?php echo $tenantAction;?>"/>
        <input type = "hidden" name="actionPage2" id = "actionPage2" value = "<?php echo $actionPage2;?>"/>
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
            	<td style="text-align:center;width: 200px;"><a target="_blank" href="images/noimage.png" ><img name="profileHref" id="profileHref" <?php 
				if(isset($_REQUEST['edit']) || isset($_REQUEST['view']))
				{ 
					if($show_member_main[0]['img'] != "") 
					{?> 
                    	src = "<?php echo $show_member_main[0]['img'];?>" 
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
					?> id="profile_img" class="img-square" alt="img" style=" width:50%;height:15%;border: 1px solid #ddd;border-radius: 4px;padding: 5px;"></a>
                <br/>
                <br/><span style="text-align:left" id="profilePhotoSpan"><b>&nbsp;Upload Profile:</b></span><input type="file" accept="image/*" id="profilePhoto" name="profilePhoto" multiple/>
                </td>
                <td colspan="2" width="70%">
                	<table width="100%">
					<tr>
        				<td style="text-align:right"></td>
						<td style = "text-align:right"><?php echo $star;?>&nbsp;<b>Landlords</b></td>
						<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
						<td>
						<select id="mapid" name="mapid" style="width:142px;" onChange= "selectDB(this.value);" value="<?php echo $_REQUEST['mapid']; ?>"<?php if($_SESSION['role'] == ROLE_SUPER_ADMIN && $_SESSION['res_flag'] == 1) { }else{echo 'disabled';} ?>>
                     		 <?php echo $mapList = $obj_initialize->combobox("Select societytbl.society_id, concat_ws(' - ', societytbl.society_id,societytbl.society_name) from mapping as maptbl JOIN society as societytbl ON maptbl.society_id = societytbl.society_id JOIN dbname as db ON db.society_id = societytbl.society_id WHERE maptbl.login_id = '" . $_SESSION['login_id'] . "' and societytbl.rental_flag = 1 and societytbl.status = 'Y' and maptbl.status = '2' ORDER BY societytbl.society_name ASC ", $_SESSION['current_mapping']);?>		
							 <input type="hidden" name="mode" value="set" />
						</select>
            			</td>
					</tr>
                	<tr>
        				<td style="text-align:right"></td>
						<td style = "text-align:right"><?php echo $star;?>&nbsp;<b>Building</b></td>
						<td>&nbsp;&nbsp; : &nbsp;&nbsp;</td>
						<td>
                			<select name="wing_id" id="wing_id" style="width:142px;" onChange=" fetchUnits(this.value); " value="<?php echo $_REQUEST['wing_id'];?>"<?php if($_SESSION['role'] == ROLE_SUPER_ADMIN) { }else{echo 'disabled';} ?> >
							<?php echo $combo_wing =  $obj_tenant->getTenantWing( $_SESSION['unit_id']); ?>
							</select>
            			</td>
					</tr>
					<tr>
						<td style="text-align:right"></td>
						<td style="text-align:right"><?php echo $star;?>&nbsp;<b>Unit No. ( Flat No )</b></td>
            			<td>&nbsp; : &nbsp;</td>
						<td>
                			<select name="unit_no" id="unit_no" style="width:142px;" onChange="checkUnits();" value="<?php echo $_REQUEST['unit_no'];?>"<?php if($_SESSION['role'] == ROLE_SUPER_ADMIN) { }else{echo 'disabled';} ?> >
							<?php $dbName = $_SESSION['rentalDb']?>
							<?php echo $combo_unit = $obj_tenant->getTenantUnit( $_SESSION['unit_id'], $_SESSION['default_wing_id']) ?>
							</select>
            			</td>
					</tr>
					<tr>
						<td style="text-align:right"></td>
						<td style="text-align:right"><?php echo $star;?>&nbsp;<b> Name of the Tenant</b></td>
            			<td style="text-align:left">&nbsp; : &nbsp;</td>
						<td style="text-align:left" id="td_1"><input type="text" name="t_name" id="t_name"  value="<?php echo $_GET['edit'] ? $show_member_main[0]['tenant_name'] : '';?>"/>
						<input type="hidden" name="profile_img" id="profile_img"   value=""/></td>
					</tr>
        			<!--<tr>
        				<td style="text-align:right"></td>
						<td style="text-align:right"><b>Middle Name of Lessee</b></td>
            			<td style="text-align:left">&nbsp; : &nbsp;</td>
						<td style="text-align:left" id="td_n2"><input type="text" name="t_mname" id="t_mname"/></td>
					</tr>
        			<tr >
        				<td style="text-align:right"></td>
						<td style="text-align:right"><?php echo $star;?>&nbsp;<b>Last Name of Lessee</b></td>
            			<td style="text-align:left">&nbsp; : &nbsp;</td>
						<td style="text-align:left" id="td_n3"><input type="text" name="t_lname" id="t_lname" onBlur="getFirstMemberName()"/></td>
					</tr>-->
      	  			<tr>
        				<td style="text-align:right"></td>
						<td style="text-align:right"><?php echo $star;?>&nbsp;<b>Lease Start Date</b></td>
            			<td style="text-align:left">&nbsp; : &nbsp;</td>
						<td style="text-align:left" id="td_2"><input type="text" name="start_date" id="start_date" class="basics" onChange="getTotalMonth();vacantUnit();" size="10"   style="width:80px;" /></td>
					</tr>
					<tr>
        				<td style="text-align:right"></td>
        				<td style="text-align:right"><?php echo $star;?>&nbsp;<b>Lease End Date</b></td>
            			<td style="text-align:left">&nbsp; : &nbsp;</td>
						<td style="text-align:left" id="td_3"><input type="text" name="end_date" id="end_date" class="basics" onChange="getTotalMonth();" size="10"   style="width:80px;" /></td>
					</tr>
                    <!--<tr>
        				<td style="text-align:right"></td>
        				<td style="text-align:right"><?php //echo $star;?>&nbsp;<b>Number of Month</b></td>
            			<td style="text-align:left">&nbsp; : &nbsp;</td>
						<td style="text-align:left" id="td_10"><input type="text" name="Lease_Period" id="Lease_Period" onChange="updateEndDate();" size="10"   style="width:80px;" /></td>
					</tr>-->
        			<tr  align="right">
        				<td style="text-align:right"><?php //echo $star;?></td>
						<td style="text-align:right"><b>Agent Name</b></td>
             			<td style="text-align:left">&nbsp; : &nbsp;</td>
						<td style="text-align:left" id="td_4"><input type="text" name="agent" id="agent" value="<?php echo $_GET['edit'] ? $show_member_main[0]['agent_name'] : '';?>"/></td>
					</tr>
					<tr  align="right">
        				<td style="text-align:right"><?php //echo $star;?></td>
						<td style="text-align:right"><b>Agent  Contact No (If applicable)</b></td>
             			<td style="text-align:left">&nbsp; : &nbsp;</td>
						<td style="text-align:left" id="td_5"><input type="text" name="agent_no" id="agent_no"  onBlur="extractNumber(this,0,true);" onKeyUp="extractNumber(this,0,true);" onKeyPress="return blockNonNumbers(this, event, true, true)" size="30"  value="<?php echo $_GET['edit'] ? $show_member_main[0]['agent_no'] : '';?>"/></td>
					</tr>
        			<?php
	    			if($verifyStatus && $_REQUEST['action'] == "verify" || (!isset($_REQUEST['view']) && !isset($_REQUEST['edit']) && !isset($_REQUEST['approve']) && $_SESSION['role'] <> ROLE_MEMBER && $_SESSION['role'] <> ROLE_ADMIN_MEMBER))
	   				{
					?>
        			<tr  align="left" id = "verifyTr">
                    	<td style="text-align:right"><?php //echo $star;?></td>
						<td style="text-align:right"><b>Verified by manager</b></td>
             			<td style="text-align:left">&nbsp; : &nbsp;</td>
						<td style="text-align:left" id="td_6"><input type="checkbox" name="verified" id="verified" value="1" ></td>
					</tr>
					<?php 
					}
					
					if(isset($_REQUEST['view']) && $_REQUEST['view'] <> '')
					{ ?>
					
                    	<tr  align="left" id = "verifyTr">
                    	<td style="text-align:right"><?php //echo $star;?></td>
						<td style="text-align:right"><b>Verified by manager</b></td>
             			<td style="text-align:left">&nbsp; : &nbsp;</td>
						<td style="text-align:left" id="td_6"><label name="verified" id="verified"></label></td>
					</tr>
                    <!--<tr  align="left" id = "pVerifyTr">
                    	<td style="text-align:right"><?php //echo $star;?></td>
						<td style="text-align:right"><b>Police Verification Submitted</b></td>
             			<td style="text-align:left">&nbsp; : &nbsp;</td>
						<td style="text-align:left" id="td_8"><label name="pVerified" id="pVerified"></label></td>
					</tr>
                     <tr  align="left" id = "leaveAndLicenseAgreementTr">
                    	<td style="text-align:right"><?php //echo $star;?></td>
						<td style="text-align:right"><b>Leave and License Agreement Submitted</b></td>
             			<td style="text-align:left">&nbsp; : &nbsp;</td>
						<td style="text-align:left" id="td_8"><label name="leaveAndLicenseAgreement" id="leaveAndLicenseAgreement"></label></td>
					</tr>-->
                    <?php }
					
					if($approveStatus && $_REQUEST['action'] == "approve")
	   				{
					?>
        			<tr  align="left" id = "approveTr">
                    	<td style="text-align:right"><?php //echo $star;?></td>
						<td style="text-align:right"><b>Approved by manager</b></td>
             			<td style="text-align:left">&nbsp; : &nbsp;</td>
						<td style="text-align:left" id="td_7"><input type="checkbox" name="approved" id="approved" value="1" ></td>
					</tr>
					<?php 
					}
					?>
					
					<tr  align="left" id = "Company">
                    	<td style="text-align:right"><?php //echo $star;?></td>
						<td style="text-align:right"><b>Is it Company?</b></td>
             			<td style="text-align:left">&nbsp; : &nbsp;</td>
						<td style="text-align:left" id="td_9"><input type="checkbox" name="isCompany" id="company" value="1" onclick="enable_company()"></td>
					</tr>
					<tr align="left">
					    <td style="text-align:right"><?php //echo $star;?></td>
						<td style="text-align:right;display:none;" id="licence"><b>License No</b></td>
						<td style="text-align:left">&nbsp; : &nbsp;</td>
						<td><input type="text" style="text-align:left;display:none;" name="license_no" id="license_no" placeholder="License No" /></td>
			            
			       </tr>
			      <tr align="left">
				        <td style="text-align:right"><?php //echo $star;?></td>
				        <td id="licenceauth" style="text-align:right;display:none;" name="licenceauth"><b>&nbsp;&nbsp;License Authority</b></td>
			            <td style="text-align:left">&nbsp; : &nbsp;</td>
						<td><input type="text"  name="license_authority" style="display:none;text-align:left;" id="license_authority" placeholder="License Authority" /></td>
			         
					</tr>
                   </table>
                </td>
            </tr>
			
			<tr><td colspan="6"><hr></td></tr>
            <tr align="left" >
            <table width="100%"><tr>
			<tr>
				<td><?php echo $star ?><b>Security Deposit</b>&nbsp;:&nbsp;
					&nbsp;&nbsp;<input type="text" name="security_deposit" id="security_deposit" style="width: 100px;">
				</td>
				<td ><?php echo $star ?><b>Annual Rent</b>&nbsp;:&nbsp;
					&nbsp;&nbsp;<input type="text" name="annual_rent" id="annual_rent" style="width: 100px;">
				</td>
				<td ><?php echo $star ?><b>Contract Value</b>&nbsp;:&nbsp;
					&nbsp;&nbsp;<input type="text" name="contract_value" id="contract_value" style="width: 100px;">
				</td>
			</tr>
            </tr>
<!-- -----------------Security Deposits-------------------------- -->
<tr><td colspan="6"><hr></td></tr>
    <tr align="left">
        <td valign="left" style="fontsize:35px;"><b>PAYMENTS RECORDS</b></td>
            <td></td>
		</tr>
    
            <tr align="left" id = "cheque">
			<td colspan="8">
			<input type="hidden" name="chequecount" id="chequecount" value="">
            <table id="cheq_table" style="margin-top:-10px;" width="100%"><tr align="left" id="mem_table_tr"><td width="6%"><b>&nbsp;&nbsp;FOR &nbsp;&nbsp;SD</b></td><td width="12%"><b>&nbsp;&nbsp;Mode Of Payment</b></td>
            <td width="12%"><b>&nbsp;&nbsp;Bank Name</b></td><td width="14%"><b>Cheque No/Trnx No</b></td><td width="14%"><b>Date<br/>(DD-MM-YYYY)</b></td>
          	<td width="10%">&nbsp;&nbsp;<b><?php //echo $star;?>&nbsp;&nbsp;Amount</b></td>
            <td width="12%"><b><?php //echo $star;?>&nbsp;&nbsp;Remark</b></td>
            <td width="14%"><b><?php //echo $star;?>&nbsp;&nbsp;Status</b></td>
           <!-- <td id="create_login">Create Login</td>
            <td id="send_emails">Send E-Mails ?</td>-->
            </tr>
            <tr align="left" id = "data_1">
			<td align="left" id="sd_td_1">&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="sd_1" id="sd_1" value = "1" onClick="selectType(1)"/></td>
				<td align = "left" id="mode_td_1" >
		
				<select name = "mode_1" id = "mode_1" style="width:100px;" onChange= "selectMode(this.value,1);" >
					<option id="Cheque" value = "Cheque">Cheque</option>
					<option id="Cash" value = "Cash">Cash</option>
					<option id="Online_Transaction" value = "Online_Transaction">Online Transaction</option>
				</select></td>
            <td align="left" id="bankName_td_1"><input type="text" name="bankName_1" id="bankName_1" style="width:100px;" /><input type="hidden" name="pdcId_1" id="pdcId_1" /></td>
            <td id="cheqno_td_1"><input type="text" name="cheqno_1" id="cheqno_1"  style="width:100px;" value = "" /></td>
            <td id="cheqdate_td_1"><input type="text" name="cheqdate_1" id="cheqdate_1" class="basics" size="10" style="width:80px;" /></td>
            <td id="amount_td_1"><input type="text" name="amount_1" id="amount_1"  style="width:70px;"  onBlur="extractNumber(this,0,true);" onKeyUp="extractNumber(this,0,true);" onKeyPress="return blockNonNumbers(this, event, true, true)" size="30" /> </td>
            <td id="remark_td_1"><input type="text" name="remark_1" id="remark_1" style="width:100px;" /></td>  
            <td id="status_td_1">
				<select name = "status_1" id = "status_1" style="width:100px;">
					<option id="" value = "1">Accepted</option>
					<option id="" value = "2">Deposited</option>
					<option id="" value = "3">Replaced</option>
					<option id="" value = "4">Rejected</option>
					<option id="" value = "5">Cancelled</option>
				</select>
			</td>  
			<td></td>          
			<!--<td><input type="checkbox"  name="chkCreateLogin" id="chkCreateLogin" value="1" /></td>
			<td><input type="checkbox" name="other_send_commu_emails" id="other_send_commu_emails" value="1" /></td>-->
		</tr>
			<input type="hidden" name="cheqcount" id="cheqcount" value="1">
            <!--<tr><td   valign="left"><div id="TextBoxContainer" >-->
    <!--Textboxes will be added here -->
<!--</div></td></tr>-->
<br />
<br />
</table>
<tr align="left">
	<td valign="left"><b>Add More Cheques</b>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<select id = "nochq" name="nochq" style= "width: 55px">
		<option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option><option>11</option><option>12</option>
		</select>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input id="btnAdd" type="button" value="Add" onclick="addNewCheque()"/>
	</td>
	</tr>

        </td></tr>

		<tr><td colspan="6"><hr></td></tr>
    <tr  align="left">
        <td valign="left"><b>Lessee occupying the unit</b></td>
			<td</td>
            <td></td></tr>
            <tr align="left" >
			<td colspan="8">
            <table id="mem_table" style="margin-top:-10px;" width="100%"><tr align="left" id="mem_table_tr"><td width="20%"><b>&nbsp;&nbsp;Name</b></td>
            <td width="20%"><b>Relation</b></td><td width="20%"><b>Emirate Id</b></td><td width="20%"><b>Date Of Birth<br/>(DD-MM-YYYY)</b></td>
          	<td width="20%">&nbsp;&nbsp;&nbsp;&nbsp;<b><?php //echo $star;?>&nbsp;&nbsp;Contact No.</b></td>
            <td width="20%"><b><?php //echo $star;?>&nbsp;&nbsp;Email Address</b></td>
           <!-- <td id="create_login">Create Login</td>
            <td id="send_emails">Send E-Mails ?</td>-->
            </tr>
            <tr align="left">
            <td align="left" id="members_td_1"><input type="hidden" name="tmemberId_1" id="tmemberId_1" /><input type="text" name="members_1" id="members_1" style="width:140px;" /></td>
            <td id="relation_td_1"><input type="text" name="relation_1" id="relation_1"  style="width:100px;" value = "Self" /></td>
            <td id="emirate_td_1"><input type="text" name="emirate_1" id="emirate_1"  style="width:150px;" value = "" /></td>
            <td id="mem_dob_td_1"><input type="text" name="mem_dob_1" id="mem_dob_1"   class="basics_Dob" size="10" style="width:100px;" /></td>
            <td id="contact_td_1">&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="contact_1" id="contact_1"  style="width:100px;"  onBlur="extractNumber(this,0,true);" onKeyUp="extractNumber(this,0,true);" onKeyPress="return blockNonNumbers(this, event, true, true)" size="30" /></td>
            <td id="email_td_1"><input type="text" name="email_1" id="email_1" style="width:130px;" /></td>            
			<!--<td><input type="checkbox"  name="chkCreateLogin" id="chkCreateLogin" value="1" /></td>
			<td><input type="checkbox" name="other_send_commu_emails" id="other_send_commu_emails" value="1" /></td>-->
		</tr>
            </tr>
            <!--<tr><td   valign="left"><div id="TextBoxContainer" >-->
    <!--Textboxes will be added here -->
    <input type="hidden" name="count" id="count" value="1">
<!--</div></td></tr>-->
<br />
<br />
</table>
<tr>
<td id="add_button"><input id="btnAdd" type="button" value="Add" onclick="addNewMember()"/></td>
</tr>

  		<tr><td colspan="6"><hr></td></tr>
 		<tr  align="left" id = "vehicle_main_tr">
        	<td id="vehicleBtnTd" style="text-align:left"><b>Vehicle Details</b></td>
			<td></td>
            <td style="text-align:right"><input type="button" name="addVehicleDetails" id="addVehicleDetails" value="Add Vehicle Details" onClick="getVehicleDetailsTable()" style="display:none"></td>
        </tr>
      	<tr align="left" >
			<td colspan="8">
            <input type="hidden" name="vehiclecount" id="vehiclecount" value="1">
            <table id="vehicle_table" style="margin-top:-10px;" width="100%" >
            	<tr align="center" id="vehicle_table_tr">
                	<td width="15%"><b>&nbsp;&nbsp;Vehicle Type</b></td>
                    <?php
						if($_SESSION['role'] != ROLE_SUPER_ADMIN)
						{
						?>
							<input type="hidden" name="parkingSlot_1" id="parkingSlot_1" value="">
						<?php
						}
						else
						{
						?>
							<td width="20%"><b>Parking Slot No.</b></td>
			        
					<?php
						}
						if($_SESSION['role'] != ROLE_SUPER_ADMIN)
						{
						?>
							<input type="hidden" name="parkingSticker_1" id="parkingSticker_1" value="">
                            <input type="hidden" name="parkingType_1" id="parkingType_1" value="0">
						<?php
						}
						else
						{
						?>
							<td width="20%"><b>Parking Sticker No.</b></td>
                            <td width="15%"><b>Parking Type</b></td>
			            <?php
						}
            			?>
                        <td width="15%"><b>Registration No.</b></td>
          				<td width="20%"><b>Vehicle Owner Name</b></td>
            			<td width="12%"><b>Vehicle Make</b></td>
            			<td width="12%"><b>Vehicle Model</b></td>
                        <td width="15%"><b>Vehicle Colour</b></td>
            </tr>
           
            <!--<tr><td   valign="left"><div id="TextBoxContainer" >-->
    		<!--Textboxes will be added here -->
    		<tr align="center">
           		<td align="center" id="vehicleType_td_1">
            		<select name="vehicleType_1" id="vehicleType_1" <?php if($_SESSION['role'] != ROLE_SUPER_ADMIN) {?>style="width:110px;" <?php }else{?> style = "width:80px"<?php }?>>
						<option value = "">Please Select</option>
                    	<option value="2">Bike</option>
						<option value="4">Car</option>
					</select>
            	</td>
            	<?php
				if($_SESSION['role'] != ROLE_SUPER_ADMIN)
				{
				?>
				<?php
				}
				else
				{
				?>
					<td id = "parkingSlot_td_1"><input type="text" name="parkingSlot_1" id="parkingSlot_1" style="width:80px;" /></td>
				<?php
				}
				?>
				<?php
				if($_SESSION['role'] != ROLE_SUPER_ADMIN)
				{
				}
				else
				{
				?>
					<td id = "parkingSticker_td_1"><input type="text" name="parkingSticker_1" id="parkingSticker_1" style="width:80px;" /></td>
                    <td id="parkingType_td_1">
                	<select id="parkingType_1" name="parkingType_1" style="width:80px;">
                	<?php
						echo $obj_tenant->combobox07("Select `Id`,`ParkingType` from `parking_type` where Status = 'Y' AND IsVisible = '1'", "0");
                    ?>
                	</select>
                </td>
				<?php
				}
				?>
            	
           		<td id="carRegNo_td_1"><input type="text" name="carRegNo_1" id="carRegNo_1" style="width:100px;"/></td>
            	<td id="carOwner_td_1"><input type="text" name="carOwner_1" id="carOwner_1" style="width:130px;" /></td>
            	<td id="carMake_td_1"><input type="text" name="carMake_1" id="carMake_1" style="width:90px;" /></td>            
				<td id = "carModel_td_1"><input type="text" name="carModel_1" id="carModel_1" style="width:90px;"/></td>
				<td id = "carColor_td_1"><input type="text" name="carColor_1" id="carColor_1" style="width:80px;"/></td>
		</tr>
   
<!--</div></td></tr>-->
<br />
            
            </table>
			<tr>
            <td id="addVehicle_button" style="padding-left:1%"><input id="btnAddVehicle" type="button" value="Add" onClick="addNewVehicle()"/></td>
     		</tr>
            
        </td></tr>
		<tr><td colspan="6"><hr></td></tr>
        	<tr  align="left">
        		<td style="text-align:left;"><b>Lease Documents</b></td>
				<td style="text-align:left"></td>
            	<td></td>
            </tr>
            <tr></tr>
            <tr align="left"><td colspan="4"><div id="doc" style="margin-left: 50px;font-weight: bold;text-transform: capitalize;"></div></td></tr>
            <tr align="left">
			<td colspan="6">
            <table id="doc_Id">
            <tr align="left">
            <td><b>Enter document name</b></td>
            <td><b>&nbsp;&nbsp;Select file to upload</b></td>
			<td></td>

			<td></td>
            </tr>
            <?php if(!isset($_REQUEST['edit']))
			{?>
            <tr align="left">
            	<td><input type="text" id="doc_name_1" name="doc_name_1" placeholder="Emirate Front ID"></td>
            	<td align="left"><input type="file" name="userfile1" id="userfile1"/></td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
            </tr>
			<tr align="left">
            	<td><input type="text" id="doc_name_2" name="doc_name_2" placeholder="Emirate Back ID"></td>
            	<td align="left"><input type="file" name="userfile2" id="userfile2"/></td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr align="left">
            <td><input type="text" id="doc_name_3" name="doc_name_3" placeholder="Ejari Document" ></td>
            <td align="left"><input type="file" name="userfile3" id="userfile3"/></td>
            </tr>
			<tr align="left">
            <td><input type="text" id="doc_name_4" name="doc_name_4" placeholder="Passport" ></td>
            <td align="left"><input type="file" name="userfile4" id="userfile4"/></td>
        	<!--<td><div id="doc" style="margin-left: 73px;font-weight: bold;text-transform: capitalize;"></div></td>-->
            </tr>
            <!--<tr><td   valign="middle"><div id="FileContainer" >-->
            <input type="hidden" name="doc_count" id="doc_count" value="4">

            <?php }
			else
			{ ?>
                <tr align="left">
                <td><input type="text" id="doc_name_1" name="doc_name_1"></td>
                <td align="left"><input type="file" name="userfile1" id="userfile1"/></td>
                <td><input id="btnAddDoc" type="button" value="Add More" /></td><!--<td><div id="doc" style="margin-left: 73px;font-weight: bold;text-transform: capitalize;"></div></td>-->
                </tr>
                <!--<tr><td   valign="middle"><div id="FileContainer" >-->
                <input type="hidden" name="doc_count" id="doc_count" value="1">	
                
            <?php }?>
            <!--</div>-->
            <!--</td></tr>-->

            </table>
			<tr>
				<td><input id="btnAddDoc" type="button" value="Add More" /></td>
			</tr>
            </td>
		</tr>

		<table hidden = "hidden" align="center" style="width:100%">
		<tr class="UnitFields"><td colspan="4"><br /><br /></td></tr>
		<tr height="50" align="center"  class="UnitFields"><td>&nbsp;</td><th colspan="3" align="center"><table align="center"><tr height="25"><th bgcolor="#CCCCCC"  style="padding-top: 6px;"width="180">Particulars For Bill </th></tr></table></th></tr>
        <tr align="left" class="UnitFields">    
			
		<?php if($IsGST == 1){?>
        <tr align="left" class="UnitFields">
        	<td valign="middle"><?php //echo $star;?></td>
			<td>GST No Exemption</td>
            <td>&nbsp; : &nbsp;</td>
			<td><input type="checkbox" value="1" id="GSTNoExp" name="GSTNoExp" <?php if(/*$_SESSION['role'] != ROLE_SUPER_ADMIN*/$_SESSION['profile'][PROFILE_EDIT_MEMBER] == 1 && $_SESSION['profile'][PROFILE_MANAGE_MASTER] == 1) { }else{echo 'disabled';} ?>></td>
            <td></td>
		</tr>
        <?php }?>
		<tr align="left" class="UnitFields">
        	<td valign="middle"><?php //echo $star;?></td>
			<td>Bill type</td>
            <td>&nbsp; : &nbsp;</td>
			<td>Maintenance Bill</td>
			<td>Supplimentary Bill</td>
		</tr>
		
		<tr align="left" class="UnitFields">
        	<td valign="middle"><?php //echo $star;?></td>
			<td>Opening Principle Balance</td>
            <td>&nbsp; : &nbsp;</td>
			<td><input   type="text"   <?php  echo $bIsCurrentYearAndCreationYrMatch == false ?  'readonly  style="background-color:#CCC;"' : ''; ?>  name="bill_subtotal" id="bill_subtotal"  value="<?php echo $_REQUEST['bill_subtotal'];?>" <?php if(/*$_SESSION['role'] != ROLE_SUPER_ADMIN*/$_SESSION['profile'][PROFILE_EDIT_MEMBER] == 1 && $_SESSION['profile'][PROFILE_MANAGE_MASTER] == 1) { }else{echo 'disabled';} ?> /></td>
			<td><input   type="text"   <?php  echo $bIsCurrentYearAndCreationYrMatch == false ?  'readonly  style="background-color:#CCC;"' : ''; ?>  name="supp_bill_subtotal" id="supp_bill_subtotal"  value="<?php echo $_REQUEST['supp_bill_subtotal'];?>" <?php if(/*$_SESSION['role'] != ROLE_SUPER_ADMIN*/$_SESSION['profile'][PROFILE_EDIT_MEMBER] == 1 && $_SESSION['profile'][PROFILE_MANAGE_MASTER] == 1) { }else{echo 'disabled';} ?> /></td>
		</tr>
        
        <tr align="left" class="UnitFields">
        	<td valign="middle"><?php //echo $star;?></td>
			<td>Opening Interest Balance</td>
            <td>&nbsp; : &nbsp;</td>
			<td><input type="text" <?php  echo $bIsCurrentYearAndCreationYrMatch == false ?   'readonly  style="background-color:#CCC;"' : ''; ?>  name="bill_interest" id="bill_interest" value="<?php echo $_REQUEST['bill_tax'];?>" <?php if(/*$_SESSION['role'] != ROLE_SUPER_ADMIN*/$_SESSION['profile'][PROFILE_EDIT_MEMBER] == 1 && $_SESSION['profile'][PROFILE_MANAGE_MASTER] == 1) { }else{echo 'disabled';} ?>/></td>
			<td><input type="text" <?php  echo $bIsCurrentYearAndCreationYrMatch == false ?   'readonly  style="background-color:#CCC;"' : ''; ?>  name="supp_bill_interest" id="supp_bill_interest" value="<?php echo $_REQUEST['supp_bill_tax'];?>" <?php if(/*$_SESSION['role'] != ROLE_SUPER_ADMIN*/$_SESSION['profile'][PROFILE_EDIT_MEMBER] == 1 && $_SESSION['profile'][PROFILE_MANAGE_MASTER] == 1) { }else{echo 'disabled';} ?>/></td>
		</tr>
        
        <tr align="left" class="UnitFields">
        	<td valign="middle"><?php //echo $star;?></td>
			<td>Previous Principle Balance</td>
            <td>&nbsp; : &nbsp;</td>
			<td><input type="text" <?php  echo $bIsCurrentYearAndCreationYrMatch == false ?  'readonly  style="background-color:#CCC;"': ''; ?>  name="principle_balance" id="principle_balance" value="<?php echo $_REQUEST['principle_balance'];?>" <?php if(/*$_SESSION['role'] != ROLE_SUPER_ADMIN*/$_SESSION['profile'][PROFILE_EDIT_MEMBER] == 1 && $_SESSION['profile'][PROFILE_MANAGE_MASTER] == 1) { }else{echo 'disabled';} ?>/></td>
			<td><input type="text" <?php  echo $bIsCurrentYearAndCreationYrMatch == false ?  'readonly  style="background-color:#CCC;"': ''; ?>  name="supp_principle_balance" id="supp_principle_balance" value="<?php echo $_REQUEST['supp_principle_balance'];?>" <?php if(/*$_SESSION['role'] != ROLE_SUPER_ADMIN*/$_SESSION['profile'][PROFILE_EDIT_MEMBER] == 1 && $_SESSION['profile'][PROFILE_MANAGE_MASTER] == 1) { }else{echo 'disabled';} ?>/></td>
		</tr>
        
        <tr align="left" class="UnitFields">
        	<td valign="middle"><?php //echo $star;?></td>
			<td>Previous Interest Balance</td>
            <td>&nbsp; : &nbsp;</td>
			<td><input type="text" <?php  echo $bIsCurrentYearAndCreationYrMatch == false ?  'readonly  style="background-color:#CCC;"' : ''; ?> name="interest_balance" id="interest_balance" value="<?php echo $_REQUEST['interest_balance'];?>" <?php if(/*$_SESSION['role'] != ROLE_SUPER_ADMIN*/$_SESSION['profile'][PROFILE_EDIT_MEMBER] == 1 && $_SESSION['profile'][PROFILE_MANAGE_MASTER] == 1) { }else{echo 'disabled';} ?>/></td>
			<td><input type="text" <?php  echo $bIsCurrentYearAndCreationYrMatch == false ?  'readonly  style="background-color:#CCC;"' : ''; ?> name="supp_interest_balance" id="supp_interest_balance" value="<?php echo $_REQUEST['supp_interest_balance'];?>" <?php if(/*$_SESSION['role'] != ROLE_SUPER_ADMIN*/$_SESSION['profile'][PROFILE_EDIT_MEMBER] == 1 && $_SESSION['profile'][PROFILE_MANAGE_MASTER] == 1) { }else{echo 'disabled';} ?>/></td>
		</tr>
        
       <?php if(isset($_GET['ssid'])){?>
        <!--<tr align="left" height="40" valign="bottom"><td></td><td colspan="3"><font style="font-size:11px; color:#F00;"><u>Note</u> : You can add multiple unit no.(Flat No.) with comma (,) separated.<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Eg. 1001,1002,1003,1004</font></td></tr>-->
        
        <?php }?>
    
        <tr><td colspan="4">&nbsp;</td></tr>
		
        <tr>
			<td colspan="4" align="center">
           		<input type="hidden" name="id" id="id">
				<input type="hidden" name="uid" id="uid" value="<?php echo $_REQUEST['uid'];?>">
                 <input type="hidden" name="mode" id="mode"   value="<?php echo $_REQUEST['mode'];?>" />
                 <input type="hidden" name="member_id" id="member_id" value="<?php echo $_REQUEST['member_id'];?>" />
              
            </td> 
		</tr>
        <tr>
	</table>
 	<tr><td><br/></td></tr> 
<tr align="left">
    	<td valign="middle"><b>Note &nbsp;:&nbsp;</b></td>
    	<td style="text-align:left;"></td>
   		<td></td>
        <td></td>
    </tr>
    <tr>
    	<td id="to_show_note" colspan="3" style="padding-left:3%"></td>
    </tr>
    <tr><td colspan="4" id="textarea"><textarea name="note" id="note" rows="5" cols="50"></textarea></td></tr>
       	<script>			
			CKEDITOR.config.extraPlugins = 'justify';
			CKEDITOR.replace('note', {toolbar: [
         						{ name: 'clipboard', items: ['Undo', 'Redo']},{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align'], items: [ 'NumberedList', 'BulletedList','JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
        						{name: 'editing', items: ['Format', 'Bold', 'Italic', 'Underline', 'Strike'] }
   								 ],
								 height: 100,
        						 width: 740,
								 uiColor: '#14B8C4'
								 });
		</script>
        
        <tr><td><input type="hidden" name="tenant_id" id="tenant_id" value=<?php echo $_GET['edit']?$_GET['edit']:$_GET['renew'] ?>></td></tr>
         <tr><td><input type="hidden" name="mem_id" id="mem_id" value="<?php  echo $_GET['mem_id']?>"></td></tr>
         <tr><td><input type="hidden" name="unit_id" id="unit_id" value="<?php  echo $unit_details['unit_id']?>"></td></tr>
        <tr><td><input type="hidden" id="doc_id" name="doc_id" value="<?php echo $details[0]['doc_id']?>"></td></tr>
        <tr><td><input type="hidden" value="<?php echo getRandomUniqueCode(); ?>" name="Code" id=="Code" /></td></tr>
		<br/><br/>
		<tr>
		<td colspan="4" align="center">
            <!--<input type="hidden" name="id" id="id">-->
			<?php if($_GET['edit']){ ?>
				<input type="submit" name="insert" id="insert" value="Update" class="btn btn-primary" style="color:#FFF; width:100px;background-color:#337ab7;" >
			<?php }else if($_GET['renew']){ ?>
				<input type="submit" name="insert" id="insert" value="Renew" class="btn btn-primary" style="color:#FFF; width:100px;background-color:#337ab7;" >
			<?php }else{ ?>
				<input type="submit" name="insert" id="insert" value="Submit" class="btn btn-primary" style="color:#FFF; width:100px;background-color:#337ab7;" >
			<?php } ?>
            <input type="button" name="print" id="print" value="Print" class="btn btn-primary" style="color:#FFF; width:100px;background-color:#337ab7; display:none" onClick="for_print();" >
            </td>
								</tr>
        
         <tr><td><br><br></td></tr>
</table>
<div id="head_for_printing" style="display:none"><center><table><tr><td style="text-align:center"><?php echo $society_dets[0]['society_name']; ?></td></tr><tr><td style="text-align:center"><?php echo $society_dets[0]['society_add']; ?></td></tr></table></center></div>
<div id="for_printing" style="display:none"></div>
</form>
</center>

<table align="center">
<tr>
<td>
<?php
/*echo "<br>";
$str1 = $obj_tenant->pgnation();
echo "<br>";
echo $str = $obj_tenant->display1($str1);
echo "<br>";
$str1 = $obj_tenant->pgnation();
echo "<br>";*/
?>
</td>
</tr>
</table>
</div>
</div>
</body>
</html>
<?php
	if(isset($_REQUEST['edit']) && $_REQUEST['edit'] <> '')
	{
		?>
			<script>
				getTenant('edit-' + <?php echo $_REQUEST['edit'];?>);				
			</script>
		<?php
	}
	
	if(isset($_REQUEST['deleteid']) && $_REQUEST['deleteid'] <> '')
	{
		?>
			<script>
				getTenant('delete-' + <?php echo $_REQUEST['deleteid'];?>);				
			</script>
		<?php
	}
	
	if(isset($_REQUEST['view']) && $_REQUEST['view'] <> '')
	{
		?>
        	<script>
				getTenant('view-' + <?php echo $_REQUEST['view']; ?> );
			</script>
        <?php
	}
	if(isset($_REQUEST['renew']) && $_REQUEST['renew'] <> '')
	{
		?>
        	<script>
				getTenant('renew-' + <?php echo $_REQUEST['renew']; ?> );
			</script>
        <?php
	}
?>

<?php
	if(isset($_REQUEST['ter']))
	{
		?>
		<script>
			bTerminate = true;
		</script>
		<?php
	}
?>
<script>
    
	function fetchUnits() {
		// console.log("hi");
        var wing_id = document.getElementById('wing_id').value;
		// console.log(wing_id);

		$.ajax({
		url: "process/rentaltenant.process.php",
		type:"POST",
		data: {'wing_id': wing_id},
		success: function(data)
		{
			// console.log(data)
			location.reload();
		}
	});
        
    }
var unit_id;
	function checkUnits(){
		unit_id = document.getElementById('unit_no').value;
		console.log(unit_id);
	}
	
	function vacantUnit(){
		var date = document.getElementById('start_date').value;
		console.log(date);
		console.log(unit_id);
		$.ajax({
			url: "ajax/rentaltenant.ajax.php",
			type:"POST",
			data: {'validate_date':'', 'date': date, 'unit_id': unit_id},
			success: function(data)
			{
				data == false ?'' : alert(data);
			}
		});
	}
   
</script>




<?php include_once "includes/foot.php"; ?>