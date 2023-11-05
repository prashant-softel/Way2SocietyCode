<?php
include_once ("C:\wamp\www\Alshola\classes\dbconst.class.php");
include_once("C:\wamp\www\Alshola\classes\include\dbop.class.php");

//error_reporting();
$dbConn = new dbop();
$dbConnRoot = new dbop(true);
class tenancy_form 
{
	public $m_dbConn;
	public $m_dbConnRoot;
	// public $landLordDB;
	// public $landLordDBRoot;
	// public $isLandLordDB;
	
	function __construct($dbConn, $dbConnRoot)
	{
		//echo 'Inside const tenant';
		$this->m_dbConn = $dbConn;
		$this->m_dbConnRoot = $dbConnRoot;
		// $this->landLordDB = $landLordDB;
		// $this->landLordDBRoot = $landLordDBRoot;
		// if($_SESSION['landLordDB']){
		// 	$this->isLandLordDB = true;
		// }
	}
     
    function tenancy_landlordDetails(){
        $sql = "select * from landlords where society_id = ".$_SESSION['society_id']."";
        $res = $this->m_dbConn->select($sql);
        return $res;
    }

	function tenancy_tenantDetails(){
		$sql = "SELECT p.name,w.wing,u.unit_no,tm.unit_id,u.flat_configuration,u.area,tm.tenant_name,tm.mobile_no,tm.email,tm.license_no,tm.emirate_no,tm.members,tm.license_authority,tm.property_type FROM tenant_module as tm,unit as u,property_usage as p, wing as w where tm.unit_id = u.unit_id and tm.property_type = p.id and u.wing_id = w.wing_id and tm.status='Y' and tm.tenant_id='".$_GET['id']."' ";
		$res = $this->m_dbConn->select($sql);
		return $res;
	}

	function tenancy_contractDetails(){
		$sql = "select m.name,tm.annual_rent, tm.contract_value, tm.security_deposit, tm.mode_of_payment, tm.create_date, tm.start_date, tm.end_date from tenant_module as tm, mode_of_payment as m where tm.mode_of_payment = m.id and tm.tenant_id='".$_GET['id']."' ";
		$res = $this->m_dbConn->select($sql);
		return $res;
	}
}
?> 