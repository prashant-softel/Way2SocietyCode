<?php if(!isset($_SESSION)){ session_start(); }
//include_once("include/dbop.class.php");
include_once("include/display_table.class.php");
include_once("dbconst.class.php");
include_once("changelog.class.php");
$dbConn = new dbop();
$dbConnRoot = new dbop(true);
$landLordDB = new dbop(false, false, false, false, true);
class view_member_profile_tenant_edit
{
	public $m_dbConn;
	public $landLordDB;
	private $m_sOwnerNames;
	
	
	function __construct($dbConn, $landLordDB)
	{
		$this->m_dbConn = $dbConn;
		$this->display_pg=new display_table($this->m_dbConn);
		$this->landLordDB = $landLordDB;
		$m_sOwnerNames = "";
	}
	
	public function combobox11($query,$id)
	{
	//$str.="<option value=''>Please Select</option>";
	$data = $this->m_dbConn->select($query);
		if(!is_null($data))
		{
			foreach($data as $key => $value)
			{
				$i=0;
				foreach($value as $k => $v)
				{
					if($i==0)
					{
						if($id==$v)
						{
							$sel = 'selected';	
						}
						else
						{
							$sel = '';
						}
						
						$str.="<OPTION VALUE=".$v.' '.$sel.">";
					}
					else
					{
						$str.=$v."</OPTION>";
					}
					$i++;
				}
			}
		}
			return $str;
	}
	
	public function show_tenant_main()
	{
		$sql = "SELECT w.wing,u.unit_no,tm.unit_id,u.flat_configuration,u.area,tm.tenant_name,tm.mobile_no,tm.email FROM tenant_module as tm, unit as u,wing as w where tm.unit_id = u.unit_id and u.wing_id = w.wing_id and tm.status='Y' and tm.tenant_id='".$_GET['id']."' ";
		$res = $this->m_dbConn->select($sql);
		return $res;
	}
	
	public function show_tenant_other_family()
	{
		$sql = "select tmember_id,mem_name,relation,mem_dob,contact_no,tms.email from tenant_member as tms,tenant_module as tenant where tenant.tenant_id='".$_GET['id']."' and tenant.tenant_id= tms.tenant_id and tms.status='Y'";
		//echo $sql;
		$res = $this->m_dbConn->select($sql);
		return $res;
	}
	
	public function show_mem_car_parking()
	{
		$sql = "select * from mem_car_parking as mcp,tenant_module as tenant where tenant.tenant_id='".$_GET['id']."' and tenant.tenant_id=mcp.member_id and mcp.status='Y' ";
		$res = $this->m_dbConn->select($sql);
		return $res;
	}
	
	public function show_mem_bike_parking()
	{
		$sql = "select * from mem_bike_parking as mbp,tenant_module as tenant where tenant.tenant_id='".$_GET['id']."' and tenant.tenant_id=mbp.member_id and mbp.status='Y' ";
		
		$res = $this->m_dbConn->select($sql);
		return $res;
	}
	
	public function update_member_profile()
	{
		//echo '1';
		################################################################## Member Main Update ##################################################################
		if($_SESSION['res_flag'] == 1){
			$changeLogArray = array();

			$logSelect = "SELECT * from `tenant_module` WHERE tenant_id = '" . $_POST['id'] . "'";
			$resultLog = $this->landLordDB->select($logSelect);
			//print_r($resultLog);
			$changeLogText['OLD_RECORD']['OWNER'] = explode('/', json_encode($resultLog));

			$logSelect = "SELECT * from `tenant_member` WHERE tenant_id = '" . $_POST['id'] . "'";
			$resultLog = $this->landLordDB->select($logSelect);
			$changeLogText['OLD_RECORD']['OTHER'] = explode('/', json_encode($resultLog));

			$logSelect = "SELECT * from `mem_car_parking` WHERE member_id = '" . $_POST['id'] . "'";
			$resultLog = $this->landLordDB->select($logSelect);
			$changeLogText['OLD_RECORD']['CAR'] = explode('/', json_encode($resultLog));

			$logSelect = "SELECT * from `mem_bike_parking` WHERE member_id = '" . $_POST['id'] . "'";
			$resultLog = $this->landLordDB->select($logSelect);
			$changeLogText['OLD_RECORD']['BIKE'] = explode('/', json_encode($resultLog));

			$changeLogText = str_replace('\"', '', json_encode($changeLogText));

			$objLog = new changeLog($this->landLordDB);
			$iLatestChangeID = $objLog->setLog($changeLogText, $_SESSION['login_id'], 'member', $_POST['id']);

			//$m_sOwnerNames .= addslashes(trim($_POST['primary_owner_name']));

			//$sql = "update member_main set primary_owner_name='".addslashes(trim($_POST['primary_owner_name']))."', resd_no='".addslashes(trim($_POST['resd_no']))."', mob='".addslashes(trim($_POST['mob']))."', alt_mob='".addslashes(trim($_POST['alt_mob']))."', off_no='".addslashes(trim($_POST['off_no']))."', off_add='".addslashes(trim($_POST['off_add']))."', desg='".addslashes(trim($_POST['desg']))."', email='".addslashes(trim($_POST['email']))."', alt_email='".addslashes(trim($_POST['alt_email']))."', dob='".addslashes(trim($_POST['dob']))."', wed_any='".addslashes(trim($_POST['wed_any']))."', blood_group='".addslashes(trim($_POST['bg']))."', eme_rel_name='".addslashes(trim($_POST['eme_rel_name']))."', eme_contact_1='".addslashes(trim($_POST['eme_contact_1']))."', eme_contact_2='".addslashes(trim($_POST['eme_contact_2']))."', profile='".addslashes(trim($_POST['profile']))."', publish_contact='".addslashes(trim($_POST['publish_contact']))."', publish_profile='".addslashes(trim($_POST['publish_profile']))."' where member_id='".$_POST['id']."' ";

			if($_SESSION['role'] == ROLE_SUPER_ADMIN)
			{
				$sql = "update tenant_module set tenant_name='".addslashes(trim($_POST['owner_name']))."', mobile_no='".($_POST['phone_number'])."', email='".addslashes(trim($_POST['email']))."'  where tenant_id='".$_POST['id']."' ";
			}
			else
			{
				$sql = "update tenant_module set tenant_name='".addslashes(trim($_POST['owner_name']))."', mobile_no='".($_POST['phone_number'])."', email='".addslashes(trim($_POST['email']))."' where tenant_id='".$_POST['id']."' ";
				//$sql = "update member_main set resd_no='".addslashes(trim($_POST['resd_no']))."', mob='".addslashes(trim($_POST['mob']))."', alt_mob='".addslashes(trim($_POST['alt_mob']))."', email='".addslashes(trim($_POST['email']))."', alt_email='".addslashes(trim($_POST['alt_email']))."', eme_rel_name='".addslashes(trim($_POST['eme_rel_name']))."', eme_contact_1='".addslashes(trim($_POST['eme_contact_1']))."', eme_contact_2='".addslashes(trim($_POST['eme_contact_2']))."' where member_id='".$_POST['id']."' ";	
			}
			
			//echo $sql;
			
			$res = $this->landLordDB->update($sql);
			
			
			################################################################## Member Main Update ##################################################################
					
			################################################################## Member Other Update ##################################################################
			for($i1=1;$i1<=$_POST['tot_other'];$i1++)
			{
				if($_POST['delete'.$i1] == 1)
				{
					$sql3 = "Update `tenant_member` SET `status` = 'N' WHERE tmember_id ='".$_POST['tmember_id'.$i1]."' and tenant_id='".$_POST['id']."'";
					$res3 = $this->landLordDB->update($sql3);
				}
				else
				{
					$other_publish_contacts = 0;
					if(isset($_POST['other_publish_contact'.$i1]))
					{
						$other_publish_contacts = 1;
					}
					$other_publish_profile = 0;
					if(isset($_POST['other_publish_profile'.$i1]))
					{
						$other_publish_profile = 1;
					}
					$Send_commu_emails = 0;
					if(isset($_POST['Send_commu_emails'.$i1]))
					{
						$Send_commu_emails = 1;
					}
					$sql3 = "update tenant_member set mem_name='".addslashes(trim($_POST['mem_name'.$i1]))."', relation='".addslashes(trim($_POST['relation'.$i1]))."', mem_dob='".getDBFormatDate(addslashes(trim($_POST['mem_dob'.$i1])))."', contact_no='".addslashes(trim($_POST['contact_no'.$i1]))."', email='".addslashes(trim($_POST['email'.$i1]))."', send_commu_emails='".addslashes(trim($Send_commu_emails))."' where tmember_id ='".$_POST['tmember_id'.$i1]."' and tenant_id='".$_POST['id']."'";
					//echo $sql3;
					$res3 = $this->landLordDB->update($sql3);
				
				
				
					/* Publish contact is not updating in profile setting page because below code again reset the value of other_publish_contact
					
					echo '<BR><BR>'.$sql_mem  = "select publish_contact from `member_main` where `member_id` ='".$_POST['id']."'";
					$res_mem = $this->m_dbConn->select($sql_mem);
					var_dump($res_mem);
					if($res_mem[0]['publish_contact'] == "1")
					{
						echo '<BR>'.$sql_upd = "update mem_other_family as other_family set other_family.other_publish_contact='1' where  other_family.member_id='".$_POST['id']."'";
					//echo $sql_upd;
						$res_mem_update = $this->m_dbConn->update($sql_upd);
					}*/
						
				}
			}

			################################################################## Member Other Update ##################################################################
			
			//$sqlOwnerUpdate = "update `member_main` SET owner_name = '" . $m_sOwnerNames . "' WHERE member_id='".$_POST['id']."'";
			//$resOwners = $this->m_dbConn->update($sqlOwnerUpdate);

			################################################################## Member Car Update ##################################################################
			for($i2=1;$i2<=$_POST['tot_car'];$i2++)
			{
				if($_POST['car_delete'.$i2] == 1)
				{
					$sql4 = "UPDATE `mem_car_parking` SET `status` = 'N' where mem_car_parking_id='".$_POST['mem_car_parking_id'.$i2]."' and  member_id='".$_POST['id']."'";
					$res4 = $this->landLordDB->update($sql4);
				}
				else
				{
					$sql4 = "update mem_car_parking set parking_slot='".addslashes(trim($_POST['parking_slot'.$i2]))."',ParkingType='".$_POST['car_parking_type'.$i2]."', car_reg_no='".addslashes(trim($_POST['car_reg_no'.$i2]))."', car_owner='".addslashes(trim($_POST['car_owner'.$i2]))."', car_model='".addslashes(trim($_POST['car_model'.$i2]))."', car_make='".addslashes(trim($_POST['car_make'.$i2]))."',  car_color='".addslashes(trim($_POST['car_color'.$i2]))."',  parking_sticker='".addslashes(trim($_POST['parking_sticker'.$i2]))."' where mem_car_parking_id='".$_POST['mem_car_parking_id'.$i2]."' and  member_id='".$_POST['id']."'";
					$res4 = $this->landLordDB->update($sql4);
				}
			//echo '<br>';
			//echo '6';
			//echo  $sql4;
			}
			################################################################## Member Car Update ##################################################################
			
			
			
			################################################################## Member Bike Update ##################################################################
			for($i3=1;$i3<=$_POST['tot_bike'];$i3++)
			{
				if($_POST['bike_delete'.$i3] == 1)
				{
					$sql5 = "UPDATE `mem_bike_parking` SET `status` = 'N' where mem_bike_parking_id='".$_POST['mem_bike_parking_id'.$i3]."' and  member_id='".$_POST['id']."'";
					$res5 = $this->landLordDB->update($sql5);
				}
				else
				{
					$sql5 = "update mem_bike_parking set parking_slot='".addslashes(trim($_POST['bike_parking_slot'.$i3]))."', ParkingType = '".addslashes(trim($_POST['bike_parking_type'.$i3]))."' , bike_reg_no='".addslashes(trim($_POST['bike_reg_no'.$i3]))."', bike_owner='".addslashes(trim($_POST['bike_owner'.$i3]))."', bike_model='".addslashes(trim($_POST['bike_model'.$i3]))."', bike_make='".addslashes(trim($_POST['bike_make'.$i3]))."', bike_color='".addslashes(trim($_POST['bike_color'.$i3]))."', parking_sticker='".addslashes(trim($_POST['bike_parking_sticker'.$i3]))."' where mem_bike_parking_id='".$_POST['mem_bike_parking_id'.$i3]."' and member_id='".$_POST['id']."'";
					//echo $sql5."<br>";
					$res5 = $this->landLordDB->update($sql5);
				}
			//echo '<br>';
			//echo '7';
			//echo $sql5;
			}
		}else{
			$changeLogArray = array();

			$logSelect = "SELECT * from `tenant_module` WHERE tenant_id = '" . $_POST['id'] . "'";
			$resultLog = $this->m_dbConn->select($logSelect);
			//print_r($resultLog);
			$changeLogText['OLD_RECORD']['OWNER'] = explode('/', json_encode($resultLog));

			$logSelect = "SELECT * from `tenant_member` WHERE tenant_id = '" . $_POST['id'] . "'";
			$resultLog = $this->m_dbConn->select($logSelect);
			$changeLogText['OLD_RECORD']['OTHER'] = explode('/', json_encode($resultLog));

			$logSelect = "SELECT * from `mem_car_parking` WHERE member_id = '" . $_POST['id'] . "'";
			$resultLog = $this->m_dbConn->select($logSelect);
			$changeLogText['OLD_RECORD']['CAR'] = explode('/', json_encode($resultLog));

			$logSelect = "SELECT * from `mem_bike_parking` WHERE member_id = '" . $_POST['id'] . "'";
			$resultLog = $this->m_dbConn->select($logSelect);
			$changeLogText['OLD_RECORD']['BIKE'] = explode('/', json_encode($resultLog));

			$changeLogText = str_replace('\"', '', json_encode($changeLogText));

			$objLog = new changeLog($this->m_dbConn);
			$iLatestChangeID = $objLog->setLog($changeLogText, $_SESSION['login_id'], 'member', $_POST['id']);

			//$m_sOwnerNames .= addslashes(trim($_POST['primary_owner_name']));

			//$sql = "update member_main set primary_owner_name='".addslashes(trim($_POST['primary_owner_name']))."', resd_no='".addslashes(trim($_POST['resd_no']))."', mob='".addslashes(trim($_POST['mob']))."', alt_mob='".addslashes(trim($_POST['alt_mob']))."', off_no='".addslashes(trim($_POST['off_no']))."', off_add='".addslashes(trim($_POST['off_add']))."', desg='".addslashes(trim($_POST['desg']))."', email='".addslashes(trim($_POST['email']))."', alt_email='".addslashes(trim($_POST['alt_email']))."', dob='".addslashes(trim($_POST['dob']))."', wed_any='".addslashes(trim($_POST['wed_any']))."', blood_group='".addslashes(trim($_POST['bg']))."', eme_rel_name='".addslashes(trim($_POST['eme_rel_name']))."', eme_contact_1='".addslashes(trim($_POST['eme_contact_1']))."', eme_contact_2='".addslashes(trim($_POST['eme_contact_2']))."', profile='".addslashes(trim($_POST['profile']))."', publish_contact='".addslashes(trim($_POST['publish_contact']))."', publish_profile='".addslashes(trim($_POST['publish_profile']))."' where member_id='".$_POST['id']."' ";

			if($_SESSION['role'] == ROLE_SUPER_ADMIN)
			{
				$sql = "update tenant_module set tenant_name='".addslashes(trim($_POST['owner_name']))."', mobile_no='".($_POST['phone_number'])."', email='".addslashes(trim($_POST['email']))."'  where tenant_id='".$_POST['id']."' ";
			}
			else
			{
				$sql = "update tenant_module set tenant_name='".addslashes(trim($_POST['owner_name']))."', mobile_no='".($_POST['phone_number'])."', email='".addslashes(trim($_POST['email']))."' where tenant_id='".$_POST['id']."' ";
				//$sql = "update member_main set resd_no='".addslashes(trim($_POST['resd_no']))."', mob='".addslashes(trim($_POST['mob']))."', alt_mob='".addslashes(trim($_POST['alt_mob']))."', email='".addslashes(trim($_POST['email']))."', alt_email='".addslashes(trim($_POST['alt_email']))."', eme_rel_name='".addslashes(trim($_POST['eme_rel_name']))."', eme_contact_1='".addslashes(trim($_POST['eme_contact_1']))."', eme_contact_2='".addslashes(trim($_POST['eme_contact_2']))."' where member_id='".$_POST['id']."' ";	
			}
			
			//echo $sql;
			
			$res = $this->m_dbConn->update($sql);
			
			
			################################################################## Member Main Update ##################################################################
					
			################################################################## Member Other Update ##################################################################
			for($i1=1;$i1<=$_POST['tot_other'];$i1++)
			{
				if($_POST['delete'.$i1] == 1)
				{
					$sql3 = "Update `tenant_member` SET `status` = 'N' WHERE tmember_id ='".$_POST['tmember_id'.$i1]."' and tenant_id='".$_POST['id']."'";
					$res3 = $this->m_dbConn->update($sql3);
				}
				else
				{
					$other_publish_contacts = 0;
					if(isset($_POST['other_publish_contact'.$i1]))
					{
						$other_publish_contacts = 1;
					}
					$other_publish_profile = 0;
					if(isset($_POST['other_publish_profile'.$i1]))
					{
						$other_publish_profile = 1;
					}
					$Send_commu_emails = 0;
					if(isset($_POST['Send_commu_emails'.$i1]))
					{
						$Send_commu_emails = 1;
					}
					$sql3 = "update tenant_member set mem_name='".addslashes(trim($_POST['mem_name'.$i1]))."', relation='".addslashes(trim($_POST['relation'.$i1]))."', mem_dob='".getDBFormatDate(addslashes(trim($_POST['mem_dob'.$i1])))."', contact_no='".addslashes(trim($_POST['contact_no'.$i1]))."', email='".addslashes(trim($_POST['email'.$i1]))."', send_commu_emails='".addslashes(trim($Send_commu_emails))."' where tmember_id ='".$_POST['tmember_id'.$i1]."' and tenant_id='".$_POST['id']."'";
					//echo $sql3;
					$res3 = $this->m_dbConn->update($sql3);
				
				
				
					/* Publish contact is not updating in profile setting page because below code again reset the value of other_publish_contact
					
					echo '<BR><BR>'.$sql_mem  = "select publish_contact from `member_main` where `member_id` ='".$_POST['id']."'";
					$res_mem = $this->m_dbConn->select($sql_mem);
					var_dump($res_mem);
					if($res_mem[0]['publish_contact'] == "1")
					{
						echo '<BR>'.$sql_upd = "update mem_other_family as other_family set other_family.other_publish_contact='1' where  other_family.member_id='".$_POST['id']."'";
					//echo $sql_upd;
						$res_mem_update = $this->m_dbConn->update($sql_upd);
					}*/
						
				}
			}

			################################################################## Member Other Update ##################################################################
			
			//$sqlOwnerUpdate = "update `member_main` SET owner_name = '" . $m_sOwnerNames . "' WHERE member_id='".$_POST['id']."'";
			//$resOwners = $this->m_dbConn->update($sqlOwnerUpdate);

			################################################################## Member Car Update ##################################################################
			for($i2=1;$i2<=$_POST['tot_car'];$i2++)
			{
				if($_POST['car_delete'.$i2] == 1)
				{
					$sql4 = "UPDATE `mem_car_parking` SET `status` = 'N' where mem_car_parking_id='".$_POST['mem_car_parking_id'.$i2]."' and  member_id='".$_POST['id']."'";
					$res4 = $this->m_dbConn->update($sql4);
				}
				else
				{
					$sql4 = "update mem_car_parking set parking_slot='".addslashes(trim($_POST['parking_slot'.$i2]))."',ParkingType='".$_POST['car_parking_type'.$i2]."', car_reg_no='".addslashes(trim($_POST['car_reg_no'.$i2]))."', car_owner='".addslashes(trim($_POST['car_owner'.$i2]))."', car_model='".addslashes(trim($_POST['car_model'.$i2]))."', car_make='".addslashes(trim($_POST['car_make'.$i2]))."',  car_color='".addslashes(trim($_POST['car_color'.$i2]))."',  parking_sticker='".addslashes(trim($_POST['parking_sticker'.$i2]))."' where mem_car_parking_id='".$_POST['mem_car_parking_id'.$i2]."' and  member_id='".$_POST['id']."'";
					$res4 = $this->m_dbConn->update($sql4);
				}
			//echo '<br>';
			//echo '6';
			//echo  $sql4;
			}
			################################################################## Member Car Update ##################################################################
			
			
			
			################################################################## Member Bike Update ##################################################################
			for($i3=1;$i3<=$_POST['tot_bike'];$i3++)
			{
				if($_POST['bike_delete'.$i3] == 1)
				{
					$sql5 = "UPDATE `mem_bike_parking` SET `status` = 'N' where mem_bike_parking_id='".$_POST['mem_bike_parking_id'.$i3]."' and  member_id='".$_POST['id']."'";
					$res5 = $this->m_dbConn->update($sql5);
				}
				else
				{
					$sql5 = "update mem_bike_parking set parking_slot='".addslashes(trim($_POST['bike_parking_slot'.$i3]))."', ParkingType = '".addslashes(trim($_POST['bike_parking_type'.$i3]))."' , bike_reg_no='".addslashes(trim($_POST['bike_reg_no'.$i3]))."', bike_owner='".addslashes(trim($_POST['bike_owner'.$i3]))."', bike_model='".addslashes(trim($_POST['bike_model'.$i3]))."', bike_make='".addslashes(trim($_POST['bike_make'.$i3]))."', bike_color='".addslashes(trim($_POST['bike_color'.$i3]))."', parking_sticker='".addslashes(trim($_POST['bike_parking_sticker'.$i3]))."' where mem_bike_parking_id='".$_POST['mem_bike_parking_id'.$i3]."' and member_id='".$_POST['id']."'";
					//echo $sql5."<br>";
					$res5 = $this->m_dbConn->update($sql5);
				}
			//echo '<br>';
			//echo '7';
			//echo $sql5;
			}
		}
		################################################################## Member Bike Update ##################################################################
		
	}
}
?>