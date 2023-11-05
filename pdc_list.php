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

$obj_list_member = new list_member($dbConn, $dbConnRoot, $landLordDB);
$obj_pdc_list = new pdc_list($dbConn, $dbConnRoot, $landLordDB);
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

</script>



<?php if(isset($_REQUEST['del'])){ ?>
<body onLoad="go_error();">
<?php }else{ ?>
<body>
<?php } ?>

<!--<center><h2><font color="#43729F"><b><?php //echo $obj_list_member->display_society_name($_SESSION['society_id']);?></b></font></h2>-->
<br><center>
<form type= "post" action= "process/pdc_list.process.php">
	<div class="panel panel-info" id="panel" style="display:none">
	<?php if($_SESSION['res_flag'] == 1 || $_SESSION['rental_flag'] == 1){ ?>
	<div class="panel-heading" id="pageheader">List of PDC's</div>
<?php }?>
<br/>
<button type="button" class="btn btn-primary"  id="chequeDeposit"  onclick= sendData() >Deposit Cheque</button>
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

$str1 = $obj_pdc_list->chequeDetails();

?>
</td>
</tr>
</table>



</center>
</div>
</form>
</center>
<?php include_once "includes/foot.php"; ?>


