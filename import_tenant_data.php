
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>W2S - Import Tenant Data</title>
</head>
</html>



<?php if(!isset($_SESSION)){ session_start(); } ?>
<?php 
	include_once("includes/head_s.php");
	include_once("classes/dbconst.class.php");


	
 ?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="css/pagination.css" >
<script type="text/javascript" src="js/validate.js"></script>
<script type="text/javascript" src="js/jquery_min.js"></script>
<script type="text/javascript" src="js/populateData.js"></script>

<script type="text/javascript">

$(document).ready(

	function(){
		$('input:submit').attr('disabled',true);
		$('input:file').change(
		function(){
			if ($(this).val())
			{
				$('input:submit').removeAttr('disabled'); 
			}
			else 
			{
				$('input:submit').attr('disabled',true);
			}
		});
});
    
$(document).ready(

	function(){
		
		document.getElementById('Download').disabled = false;
});

</script>

<script language="javascript" type="text/javascript">
	function go_error()
	{
		setTimeout('hide_error()',10000);	
	}
	function hide_error()
	{
		document.getElementById('error').style.display = 'none';
	}
	    
</script>

</head>

<body onLoad="go_error();">

<form name="display_data" action="display_data.php" method="post" enctype="multipart/form-data" >

<center>
<br> 
<div class="panel panel-info" id="panel" style="display:none">
<div class="panel-heading" id="pageheader">Import Tenant Data</div>
<div id="right_menu">
<table>

<?php

if(isset($_POST["ShowData"]))
{
	?>
    <tr height="30"><td colspan=5 style="text-align:center"><font color="red" style="size:11px;"><b id="error"><?php echo $_POST["ShowData"]; ?></b></font></td></tr>
<?php } ?> 
<strong><div id="show" style="text-align:center; width:100%; color:#FF0000"><?php //echo $show_op; ?></div></strong>

<BR/>
<BR/>

</tr>        
      <tr>
     	 <td>&nbsp;&nbsp;&nbsp;</td>
      </tr>
<tr align="left">
        	<td valign="middle"></td>
			<td>Browse File To Import</td>
            <td>&nbsp; : &nbsp;</td>
			<td id="browse">
				    <input type="file" name="file[]" id="file" />
            </td>
</tr>   
<tr><td colspan="4">&nbsp;</td></tr>
<tr height="50" align="center">
 <td colspan="4" align="center">
 	<input type="hidden" name="flag" value="17">
    <input type="hidden" name="NoteType" value="<?php echo $NoteType;?>">
     <input type="submit" name="Upload" id="Upload" value="Import" disabled on class="btn btn-primary"  accept="application/msexcel"/></td>
</tr>
<input type="hidden" name="sid" value="<?php echo $_SESSION['society_id'];?>">
</table> 


<!-- <span>Please Download this template file and fill the data in appropriate columns.--><a href="samplefile/import_tenant_data.csv" class="btn btn-primary" style="width:200px; height:30px; font-family:'Times New Roman', Times, serif; font-style:normal; color: #fff; background-color: #337ab7; border-color: #2e6da4;" download> Download Sample </a> <br/>

<!-- Download Sample Invoice File-->

</form>
<br>

</center>

</div>

</center>
</div>
</div>
 </form>
<?php include_once "includes/foot.php"; ?>
</body>
