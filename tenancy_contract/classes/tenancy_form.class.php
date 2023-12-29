<?php
include_once ("../classes/dbconst.class.php");
include_once("../classes/include/dbop.class.php");

//error_reporting(0);
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
			$sql = "SELECT w.wing,u.unit_no,tm.unit_id,u.flat_configuration,u.area,tm.tenant_name,tm.mobile_no,tm.email,tm.license_no,tm.license_authority,tm.note,tm.members,t.emirate_no, p.name,u.property_type,u.location,u.plot_no,u.makani_no,u.premises_no,u.property_no FROM tenant_module as tm,unit as u,property_usage as p, wing as w, tenant_member as t where tm.unit_id = u.unit_id and tm.tenant_id = t.tenant_id and u.property_type = p.id and u.wing_id = w.wing_id and tm.status='Y' and tm.tenant_id='".$_GET['id']."' ";
			$res = $this->m_landLordDB->select($sql);
			return $res;
		}else{
			$sql = "SELECT w.wing,u.unit_no,tm.unit_id,u.flat_configuration,u.area,tm.tenant_name,tm.mobile_no,tm.email,tm.members,tm.license_authority,tm.license_no,tm.note,t.emirate_no,p.name, u.property_type FROM tenant_module as tm,unit as u,property_usage as p, wing as w,tenant_member as t where tm.unit_id = u.unit_id and tm.tenant_id = t.tenant_id and u.property_type = p.id and u.wing_id = w.wing_id and tm.status='Y' and tm.tenant_id='".$_GET['id']."' ";
			$res = $this->m_dbConn->select($sql);
			return $res;
		}
	}

	function tenancy_contractDetails(){
		if($this->isLandLordDB){
			$sql = "SELECT tm.annual_rent, tm.contract_value, tm.security_deposit, tm.create_date, tm.start_date, tm.end_date from tenant_module as tm where tm.tenant_id='".$_GET['id']."' ";
			$res = $this->m_landLordDB->select($sql);
			return $res;
		}else{
			$sql = "SELECT tm.annual_rent, tm.contract_value, tm.security_deposit, tm.create_date, tm.start_date, tm.end_date from tenant_module as tm where tm.tenant_id='".$_GET['id']."' ";
			$res = $this->m_dbConn->select($sql);
			return $res;
		}
	}

	function tenancy_paymentmode(){
		if($_SESSION['res_flag'] == 1){
			$sql = "SELECT mode_of_payment from postdated_cheque where tenant_id = '".$_GET['id']."'";
			$res  = $this->m_landLordDB->select($sql);
			foreach($res as $k => $value){
				$mode[] = $res[$k]['mode_of_payment'];
			}
			$payment = array_unique(array_filter($mode));
			if(sizeof($payment) == 1){
				foreach($payment as $k => $v){
					$data = $payment[$k];
				}
			}else{
				$data = implode(" , ", $payment);
			}
			return $data;
		}else{
			$sql = "SELECT mode_of_payment from postdated_cheque where tenant_id = '".$_GET['id']."'";
			$res  = $this->m_dbConn->select($sql);
			foreach($res as $k => $value){
				$mode[] = $res[$k]['mode_of_payment'];
			}
			$payment = array_unique(array_filter($mode));
			if(sizeof($payment) == 1){
				foreach($payment as $k => $v){
					$data = $payment[$k];
				}
			}else{
				$data = implode(" , ", $payment);
			}
			return $data;
		}
	}
}
?> 