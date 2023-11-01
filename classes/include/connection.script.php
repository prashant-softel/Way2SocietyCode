<?php
	include('config_script.php');
	include_once('dbop.class.php');
	include_once("classes/utility.class.php");

	//$m_objUtility = new utility($m_dbConn);

	error_reporting(0);

	// if(isset($_REQUEST['update']))
	// {

	// 	$dbName = $_REQUEST['selDB']; 
	// 	$_SESSION['rentalDb'] = $dbName;
	// 	echo "Success";

	// }
	// 	try
	// 	{
	// 		echo $hostname = DB_HOST;
	// 		echo $username =DB_USER;
	// 		echo $password = DB_PASSWORD;
	// 		//$dbPrefix = 'hostmjbt_society';

    //         $dbName = $_REQUEST['selDB'];
	// 		echo "DBName: ".$dbName;
    //         //$query = "select dbname from society where dbname = '".$dbName."'";
	// 	/*	$IsSelectQuery = false;
			
	// 		if($IsSelectQuery == false)
	// 		{
	// 			echo '<br/><br/>Connecting DB : '.$dbName;
	// 		}*/
				
	// 		$mMysqli = mysqli_connect($hostname, $username, $password, $dbName);
	// 		echo $mMysqli;
	// 		if(!$mMysqli)
	// 		{
	// 			if($IsSelectQuery == false)
	// 			{
	// 				echo '<br/>Connection Failed';	
	// 			}
					
	// 		}
	// 		else
	// 		{
	// 			echo '<br/>Connected';	
	// 	    }
    //     }
	// 	catch(Exception $exp)
	// 	{
	// 		echo $exp;
	// 	}
	// else
	// {
	// 	echo 'Missing Parameters';
	// }
	
	// function GetResult($result)
	// {
	// 	$count = 0;
	// 	while($row = $result->fetch_array(MYSQL_ASSOC))
	// 	{
	// 		$data[$count] = $row;
	// 		$count++;
	// 	}
	// 	return $data;
	// }
?>