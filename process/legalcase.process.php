<?php	
	include_once("../classes/include/dbop.class.php");
	include_once("../classes/legalcase.class.php");
	include_once("../classes/dbconst.class.php");	
	include_once("../classes/utility.class.php");	
	$dbConn = new dbop();
	$dbConnRoot = new dbop(true);
	$landLordDB = new dbop(false,false,false,false,true);
	$obj_servicerequest=new legalcase($dbConn,$dbConnRoot,$landLordDB);
	$m_objUtility = new utility($dbConn, $dbConnRoot);
	//$actionPage = "";
	
	if(isset($_POST['selSocID']))
	{	
	
		$DBName = $m_objUtility->getDBName($_POST['selSocID']);		
		$_SESSION['landLordDB'] = $DBName;
		$_SESSION['landLordSocID'] = $_POST['selSocID'];
		if($_SESSION['dbname'] === $_SESSION['landLordDB']){
			$_SESSION['landLordDB'] = '';
		}
		exit;
	}
	if(isset($_REQUEST['vr']))
	{			
		$validator = $obj_servicerequest->insertComments($_REQUEST['vr'], $_POST['emailID'],$_POST['SREmailIDs']);
		$actionPage = "../viewlegalcase.php?rq=".$_REQUEST['vr']."&socid=".$_POST['society_id'];
	}
	else
	{		
		if($_POST['insert'] != 'Update')
		{
			$unitId = $_REQUEST['unit_no'];
			if($_SESSION['role'] !=  ROLE_MEMBER)
			{
				$unitId = $_REQUEST['unit_no2'];
			}
			
			$memberId = $obj_servicerequest->getMemberId($unitId);
		//Vaishali's code
			if($_REQUEST['category'] == $_SESSION['RENOVATION_DOC_ID'] || $_REQUEST['category'] == $_SESSION['ADDRESS_PROOF_ID'] || $_REQUEST['category'] == $_SESSION['TENANT_REQUEST_ID'])
			{
				$serviceRequestDetails = array();
				$serviceRequestDetails['srTitle'] = $_REQUEST['summery'];
				$serviceRequestDetails['email'] = $_REQUEST['email'];
				$serviceRequestDetails['reported_by'] = $_REQUEST['reported_by'];
				$serviceRequestDetails['unit_no'] =$unitId;
				$serviceRequestDetails['phone'] = $_REQUEST['phone'];
				$serviceRequestDetails['priority'] = $_REQUEST['priority'];
				$serviceRequestDetails['category'] = $_REQUEST['category'];
				if($_REQUEST['category'] == $_SESSION['RENOVATION_DOC_ID'])
				{
					$_SESSION['serviceRequestDetails'] = $serviceRequestDetails;
					if($_SESSION['role'] != ROLE_MEMBER)
					{
						$actionPage = "../document_maker.php?View=MEMBER&temp=".$_SESSION['RENOVATION_DOC_ID']."&unitId=".$unitId;
					}
					else
					{
						$actionPage = "../document_maker.php?View=MEMBER&temp=".$_SESSION['RENOVATION_DOC_ID'];
					}
				}
				else if($_REQUEST['category'] == $_SESSION['ADDRESS_PROOF_ID'])
				{
					$_SESSION['serviceRequestDetails'] = $serviceRequestDetails;
					if($_SESSION['role'] != ROLE_MEMBER)
					{
						$actionPage = "../document_maker.php?View=MEMBER&temp=".$_SESSION['ADDRESS_PROOF_ID']."&unitId=".$unitId;
					}
					else
					{
						$actionPage = "../document_maker.php?View=MEMBER&temp=".$_SESSION['ADDRESS_PROOF_ID']."&unitId=".$unitId; 
					}
				}
				else
				{
					$_SESSION['serviceRequestDetails'] = $serviceRequestDetails;
					$actionPage = "../tenant.php?prf&mem_id=".$memberId."&sr";
				}
			}
			
  		}	//end
			
		$validator = $obj_servicerequest->startProcess();
		if($_SESSION['role'] && ($_SESSION['role']==ROLE_ADMIN || $_SESSION['role']==ROLE_SUPER_ADMIN))
		{
			$actionPage = "../legalcase.php?type=open";
		}
		else
		{
			$actionPage = "../legalcase.php?type=createdme";
		}
		
	}


	//echo $actionPage;
	//$actionPage = "../viewrequest.php?rq=".$_REQUEST['vr'];
?>
<html>
<body>
<form name="Goback" method="post" action="<?php echo $actionPage; ?>">

	<?php

	if($validator=="Insert")
	{
	$ShowData="Record Added Successfully";
	}
	else if($validator=="Update")
	{
	$ShowData="Record Updated Successfully";
	}
	else if($validator=="Delete")
	{
	$ShowData="Record Deleted Successfully";
	}
	else
	{
		
	/*	foreach($_POST as $key=>$value)
		{
		echo "<input type=\"hidden\" name=\"$key\" value=\"$value\" />";
		}
		$ShowData=$validator;	*/	
	}
	?>

<input type="hidden" name="ShowData" value="<?php echo $ShowData; ?>">
<input type="hidden" name="mm">
</form>
<script>
	
document.Goback.submit();		
	
</script>
</body>
</html>
