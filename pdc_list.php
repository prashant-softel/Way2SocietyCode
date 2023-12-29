<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<head>
<title>W2S - List of PDC's </title>
</head>


<?php include_once("includes/head_s.php");
?>
<?php
include_once("classes/list_member.class.php");
include_once("classes/pdc_list.class.php");
include_once("classes/initialize.class.php");

$dbConn = new dbop();
$dbConnRoot = new dbop(true);
$landLordDB = new dbop(false,false,false,false,true);
$landLordDBRoot = new dbop(false,false,false,false,false,true);

$obj_list_member = new list_member($dbConn, $dbConnRoot, $landLordDB ,$landLordDBRoot);
$obj_pdc_list = new pdc_list($dbConn, $dbConnRoot, $landLordDB, $landLordDBRoot);
$obj_initialize = new initialize($dbConnRoot);
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";

// echo "DbName: " .$_SESSION['landLordDB'];
// echo " ID: " .$_SESSION['landLordSocID'];
?>
<link rel="stylesheet" type="text/css" href="css/pagination.css" >
	<link href="css/messagebox.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="js/ajax.js"></script>      
    <script type="text/javascript" src="js/jsViewLedgerDetails.js"></script>      
	<script type="text/javascript" src="js/ajax_new.js"></script> 
	<script language="javascript" type="application/javascript">
function go_error()
{
	setTimeout('hide_error()',10000);	
}
function hide_error()
{
	document.getElementById('error_del').style.display = 'none';	
}
$(document).ready(function(){
	var socID = '<?php echo $_SESSION['landLordSocID']; ?>' ;
	if(socID) {
		document.getElementById('mapid').value = socID;
	}
});

$(function()
{
	$.datepicker.setDefaults($.datepicker.regional['']);
	$(".basics").datepicker({ 
	dateFormat: "dd-mm-yy", 
	showOn: "both", 
	buttonImage: "images/calendar.gif", 
	buttonImageOnly: true,
	minDate: minGlobalCurrentYearStartDate,
	maxDate: maxGlobalCurrentYearEndDate
})});


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

$(document).ready(function() {
	 $('#example').dataTable(
 {
	"bDestroy": true
}).fnDestroy();
if(localStorage.getItem("client_id") != "" && localStorage.getItem("client_id") != 1)
{
	//alert("hey");
		$('#example').dataTable( 
					
					{
						
						"stateSave": true,
						"stateDuration": 0,
						dom: 'T<"clear">Blfrtip',
						"aLengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All data"] ],
						buttons: 
						[
							{
								extend: 'colvis',
								width:'inherit'/*,
								collectionLayout: 'fixed three-column'*/
							}
							
						],
					 columnDefs: 
					 [
						{
							//targets:[9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25],
							//visible: false
						}
					],
						"oTableTools": 
						{
							"aButtons": 
							[
								{ "sExtends": "copy", "mColumns": "visible" },
								{ "sExtends": "csv", "mColumns": "visible" },
								{ "sExtends": "xls", "mColumns": "visible" },
								{ "sExtends": "pdf", "mColumns": "visible"},
								{ "sExtends": "print", "mColumns": "visible","sMessage": printMessage + " "}
							],
						 "sRowSelect": "multi"
					},
					aaSorting : [],
						
					fnInitComplete: function ( oSettings, json ) {
						//var otb = $(".DTTT_container")
						//alert("fnInitComplete");
						$(".DTTT_container").append($(".dt-button"));
						
						//get sum of amount in column at footer by class name sum
						this.api().columns('.sum').every(function(){
						var column = this;
						var total = 0;
						var sum = column
							.data()
							.reduce(function (a, b) {
								if(a.length == 0)
								{
									a = '0.00';
								} 
								if(b.length == 0)
								{
									b = '0.00';
								}
								var val1 = parseFloat( String(a).replace(/,/g,'') ).toFixed(2);
								var val2 = parseFloat(String(b).replace(/,/g,'') ).toFixed(2);
								total = parseFloat(parseFloat(val1)+parseFloat(val2));
								return  total;
							});
					$(column.footer()).html(format(sum,2));
					});
					
					}
					
				} );	
				//alert("End If");
	}
	else
	{
			$('#example').dataTable( {
						/*dom: 'T<"clear">lfrtip',*/
						"stateSave": true,
						"stateDuration": 0,
						dom: 'T<"clear">Blfrtip',
						"aLengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All data"] ],
						buttons: 
						[
							{
								extend: 'colvis',
								width:'inherit'/*,
								collectionLayout: 'fixed three-column'*/
							}
						],
					 columnDefs: 
					 [
						{
							//targets: 9,
							//visible: false
						}
					],
						"oTableTools": 
						{
							"aButtons": 
							[
								{ "sExtends": "copy", "mColumns": "visible" },
								{ "sExtends": "csv", "mColumns": "visible" },
								{ "sExtends": "xls", "mColumns": "visible" },
								{ "sExtends": "pdf", "mColumns": "visible"},
								{ "sExtends": "print", "mColumns": "visible","sMessage": printMessage + " "}
							],
						 "sRowSelect": "multi"
					},
					aaSorting : [],
						
					fnInitComplete: function ( oSettings, json ) {
						//var otb = $(".DTTT_container")
						//alert("fnInitComplete");
						$(".DTTT_container").append($(".dt-button"));
						
						//get sum of amount in column at footer by class name sum
						this.api().columns('.sum').every(function(){
						var column = this;
						var total = 0;
						var sum = column
							.data()
							.reduce(function (a, b) {
								if(a.length == 0)
								{
									a = '0.00';
								} 
								if(b.length == 0)
								{
									b = '0.00';
								}
								var val1 = parseFloat( String(a).replace(/,/g,'') ).toFixed(2);
								var val2 = parseFloat(String(b).replace(/,/g,'') ).toFixed(2);
								total = parseFloat(parseFloat(val1)+parseFloat(val2));
								return  total;
							});
					$(column.footer()).html(format(sum,2));
					});
					
					}
					
				} );	
			//alert("End");
	}
//alert("End of function");
});	

var Data_arr = [];
function depositCheque(pid,tid,lid,sid,wid,uid,tname,bname,branch,cheq_no,cheq_date,amount,remark,ctype,mode,count){
	// console.log("wid" +wid+"uid" +uid+"tname " +tname);
	var check = document.getElementById('chk_'+count);
	console.log($('#chk_'+count));
	console.log(check);
	if(check.checked == true){
		Data_arr.push({"pdc_id":pid, "tenant_id":tid, "ledger_id":lid, "security_id":sid, "wing_id":wid, "unit_id":uid, "tenant_name":tname, "bank_name":bname,"bank_branch":branch, "cheque_no":cheq_no, "cheque_date":cheq_date, "amount":amount, "remark":remark, "cheque_type":ctype, "mode":mode});
		console.log(Data_arr);
	}
	else{
		for (const [key, value] of Object.entries(Data_arr)) {
			for (const [k, v] of Object.entries(value)) {
				if(k == 'pdc_id' && v == pid){
					Data_arr.splice(key, 1);
				}
			}
		}
	}
	GetButtonAction();
}

function GetButtonAction()
{  
	var freezYear = '<?php echo $_SESSION['is_year_freeze']?>' ;
	if(freezYear == 0)
	{
		if(Data_arr.length > 0)
		{
			document.getElementById("chequeDeposit").disabled=false;
			document.getElementById("chequeDeposit").style.backgroundColor='#337ab7';
		}
		else
		{
			document.getElementById("chequeDeposit").style.backgroundColor='#337ab77a';
			document.getElementById("chequeDeposit").disabled=true;
		}
	}
	else
	{
		document.getElementById("chequeDeposit").style.backgroundColor='#337ab77a';
		document.getElementById("chequeDeposit").disabled=true;
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
		alert(data);
		location.reload();
	}
});	
}


</script>

<?php
	if(isset($_GET['from_date']) && isset($_GET['to_date']) && isset($_GET['tran_type']))
	{
		$from = $_GET['from_date'];
		$to = $_GET['to_date'];
		$tranType = $_GET['tran_type'];
	}
?>

<?php if(isset($_REQUEST['del'])){ ?>
<body onLoad="go_error();">
<?php }else{ ?>
<body>
<?php } ?>

<!--<center><h2><font color="#43729F"><b><?php //echo $obj_list_member->display_society_name($_SESSION['society_id']);?></b></font></h2>-->
<br><center>
<form type="POST" action="pdc_list.php">
	<div class="panel panel-info" id="panel" style="display:none">
	<?php if($_SESSION['res_flag'] == 1 || $_SESSION['rental_flag'] == 1){ ?>
	<div class="panel-heading" id="pageheader">List of Payment Detail's</div>
<?php }?>
<br />
<table style="width:100%; border:1px solid black; background-color:transparent; ">
	<tr> <td colspan="2"><br/> </td></tr>
	<tr>
	<td> Select a Landlord: 
	<select id="mapid" name="mapid" style="width:180px;" onChange= "selectDB(this.value);" value="<?php echo $_REQUEST['mapid']; ?>"<?php if($_SESSION['role'] == ROLE_SUPER_ADMIN && $_SESSION['res_flag'] == 1) { }else{echo 'disabled';} ?>>
	<?php echo $mapList = $obj_initialize->combobox("Select societytbl.society_id, concat_ws(' - ', societytbl.society_id,societytbl.society_name) from mapping as maptbl JOIN society as societytbl ON maptbl.society_id = societytbl.society_id JOIN dbname as db ON db.society_id = societytbl.society_id WHERE maptbl.login_id = '" . $_SESSION['login_id'] . "' and societytbl.rental_flag = 1 and societytbl.status = 'Y' and maptbl.status = '2' ORDER BY societytbl.society_name ASC ", $_SESSION['current_mapping']);?>		
	<input type="hidden" name="mode" value="set" />
	</select>
	</td>
	<td> From :&nbsp; &nbsp;                   
	<input type="text" name="from_date" id="from_date"  class="basics" size="10" style="width:80px;" value = "<?php echo getDisplayFormatDate($from)?>"/></td>
	&nbsp; &nbsp;
	<td> To :&nbsp; &nbsp;                  
	<input type="text" name="to_date" id="to_date"  class="basics" size="10" style="width:80px;" value="<?php echo getDisplayFormatDate($to);?>"/></td>
	&nbsp; &nbsp;
	<td> Transaction Type : &nbsp; &nbsp;
			<select name="tran_type" id="tran_type" style="width:80px;">
			<option value="0">All</option>
			<option value="1">Accepted</option>
			<option value="2">Deposited</option>
			<option value="3">Replaced</option>
			<option value="4">Cancelled</option>
		</select>
	</td>
	<td><input type = "submit" name="fetch" value="Fetch"/></td>
	</tr>
	<tr> <td colspan="2"><br/> </td></tr>
	<?php
	// echo "date: " .$from;
	?>
</table>
</form>
<form type= "post" action= "process/pdc_list.process.php">
	<br/>
<button type="button" class="btn btn-primary"  disabled id="chequeDeposit"  onclick= sendData() >Deposit Cheque</button>
<center>
	<br>
<!--<a href="unit.php?imp&ssid=<?php echo $_SESSION['society_id'];?>&idd=<?php echo time();?>"><input type="button" value="Add Unit"></a>-->

<table align="center" border="0" style="width:100%">
<tr>
	<td valign="top" align="center"><font color="red"><?php if(isset($_GET['del'])){echo "<b id=error_del>Record deleted Successfully</b>";}else{echo '<b id=error_del></b>';} ?></font></td>
</tr>

<tr>
<td>
	<?php
	if($_REQUEST['from_date']){
		$str1 = $obj_pdc_list->chequeDetails($from,$to,$tranType);
	}else{
		$str1 = $obj_pdc_list->chequeDetails(null,null,null);
	}
	?>
</td>
</tr>
</table>

</center>
</div>
</form>
</center>
<?php include_once "includes/foot.php"; ?>

