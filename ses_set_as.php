<?php if(!isset($_SESSION)){ session_start(); } ?>
<?php 
	include_once("classes/include/dbop.class.php");
	$m_dbConn = new dbop();	
	$m_landlord = new dbop(false,false,false,false,true);	
?>
<?php
/*if(!isset($_SESSION['admin']) && !isset($_SESSION['sadmin']))
{		
?>
<script>window.location.href = 'login.php?alog';</script>   
<?php
}*/
?>