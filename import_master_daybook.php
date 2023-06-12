
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<head>
<title>W2S - Import Tally Master</title>
</head>

<?php if(!isset($_SESSION)){ session_start(); } ?>
<?php
	include_once("includes/head_s.php");
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
			if('<?php echo $_SESSION['is_year_freeze'] ?>' == 0)
			{
				if ($(this).val())
				{
					$('input:submit').removeAttr('disabled'); 
				}
				else 
				{
					$('input:submit').attr('disabled',true);
				}
			}
			else
			{
				$('input:submit').attr('disabled',true);
			}
		});
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
        <div class="panel-heading" id="pageheader">Import Tally Master</div>
<div id="right_menu">
<table>

<?php

if(isset($_POST["ShowData"]))
{
	?>
    <tr height="30"><td colspan=5 style="text-align:center"><font color="red" style="size:11px;"><b id="error"><?php echo $_POST["ShowData"]; ?></b></font></td></tr>
<?php } ?> 
<strong><div id="show" style="text-align:center; width:100%; color:#FF0000"><?php //echo $show_op; ?></div></strong>
<!--<tr height="50" align="center"><td>&nbsp;</td><th colspan="3" align="center"><table align="center"><tr height="25"><th bgcolor="#CCCCCC" width="180">For Society Admin Login</th></tr></table></th></tr>-->
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
				    <input type="file" name="file[]" id="file" multiple/>
            </td>
</tr>
 <tr>
 <td valign="middle"></td>
<td>
  <input type="radio" id="category" name="data" value="category" checked>
  <label for="category">Import Category Data</label>
</td>
</tr>
<tr>
<td valign="middle"></td>
<td>
  <input type="radio" id="ledger" name="data" value="ledger">
  <label for="ledger">Import Ledger Data</label>
</td>
</tr>
<tr><td colspan="4">&nbsp;</td></tr>
<tr height="50" align="center">
 <td colspan="4" align="center">
 	<input type="hidden" name="flag" value="9">
     <input type="submit" name="Upload" id="Import" value="Import" disabled on class="btn btn-primary" ss/></td>
</tr>
<input type="hidden" name="sid" value="<?php echo $_SESSION['society_id'];?>">
</table> 
	<span style="color:red;">Date should be in YYYY-MM-DD format</span><br>
  <!--  <span>Share Certificate Data Sample File : <a href="samplefile/Invoice_import.csv" download>Click here to Download</a> </span>-->
</center>
</div>
</div>
    </form>
<?php include_once "includes/foot.php"; ?>
</body>
</html>