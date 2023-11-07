<?php
include_once ("../classes/dbconst.class.php");
include_once("../classes/include/dbop.class.php");

//error_reporting();
$dbConn = new dbop();
$dbConnRoot = new dbop(true);
$landLordDB = new dbop(false,false,false,false,true);
$landLordDBRoot = new dbop(false,false,false,false,false,true);
class tenancy_form 
{
	public $m_dbConn;
	public $m_dbConnRoot;
	public $landLordDB;
	public $landLordDBRoot;
	public $isLandLordDB;
	
	function __construct($dbConn, $dbConnRoot, $landLordDB, $landLordDBRoot)
	{
		//echo 'Inside const tenant';
		$this->m_dbConn = $dbConn;
		$this->m_dbConnRoot = $dbConnRoot;
		$this->m_landLordDB = $landLordDB;
		$this->m_landLordDBRoot = $landLordDBRoot;
		if($_SESSION['landLordDB']){
			$this->isLandLordDB = true;
		}
	}
     
    function tenancy_landlordDetails(){
		if($this->isLandLordDB){
		$sql = "select * from landlords where society_id = ".$_SESSION['landLordSocID']."";
       	 	$res = $this->m_landLordDB->select($sql);
        	return $res;
		}
		else{
			$sql = "select * from landlords where society_id = ".$_SESSION['society_id']."";
        	$res = $this->m_dbConn->select($sql);
        	return $res;
		}
    }

	function tenancy_tenantDetails(){
		if($this->isLandLordDB){
			// $sql = "Select w.wing,u.unit_no,tm.unit_id,u.flat_configuration,u.area,tm.tenant_name,tm.mobile_no,tm.email,tm.property_type,tm.license_no,tm.emirate_no,tm.members,tm.license_authority, pm.name from wing as w, property_usage as pm, tenant_module as tm JOIN unit as u ON tm.unit_id = u.unit_id JOIN property_usage as pm ON u.property_type = pm.id JOIN wing as w ON u.wing_id = w.wing_id where tm.status = 'Y' and tm.tenant_id = '".$_GET['id']."' ";
			$sql = "SELECT w.wing,u.unit_no,tm.unit_id,u.flat_configuration,u.area,tm.tenant_name,tm.mobile_no,tm.email,tm.property_type,tm.license_no,tm.emirate_no,tm.members,tm.license_authority, p.name FROM tenant_module as tm,unit as u,property_usage as p, wing as w where tm.unit_id = u.unit_id and u.property_type = p.p_id and u.wing_id = w.wing_id and tm.status='Y' and tm.tenant_id='".$_GET['id']."' ";
			$res = $this->m_landLordDB->select($sql);
			return $res;
		}else{
			$sql = "SELECT w.wing,u.unit_no,tm.unit_id,u.flat_configuration,u.area,tm.tenant_name,tm.mobile_no,tm.email,tm.members,tm.license_authority,tm.property_type,tm.license_no,tm.emirate_no,p.name FROM tenant_module as tm,unit as u,property_usage as p, wing as w where tm.unit_id = u.unit_id and u.property_type = p.id and u.wing_id = w.wing_id and tm.status='Y' and tm.tenant_id='".$_GET['id']."' ";
			$res = $this->m_dbConn->select($sql);
			return $res;
		}
	}

	function tenancy_contractDetails(){
		if($this->isLandLordDB){
			$sql = "select tm.annual_rent, tm.contract_value, tm.security_deposit, tm.mode_of_payment, tm.create_date, tm.start_date, tm.end_date, m.name from tenant_module as tm, mode_of_payment as m where tm.mode_of_payment = m.id and tm.tenant_id='".$_GET['id']."' ";
			$res = $this->m_landLordDB->select($sql);
			return $res;
		}else{
			$sql = "select tm.annual_rent, tm.contract_value, tm.security_deposit, tm.mode_of_payment, tm.create_date, tm.start_date, tm.end_date, m.name from tenant_module as tm, mode_of_payment as m where tm.mode_of_payment = m.id and tm.tenant_id='".$_GET['id']."' ";
			$res = $this->m_dbConn->select($sql);
			return $res;
		}
	}
}
?> 