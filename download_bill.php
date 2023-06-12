<?php
/*
* PHP ZIP - Create a ZIP archive
*/

if(isset($_REQUEST['society_id']) && isset($_REQUEST['period_id']) && isset($_REQUEST['Download']) && isset($_REQUEST['BT']))
{
	include_once ("classes/include/dbop.class.php");
	$dbConn = new dbop();
	
	include_once ("classes/include/fetch_data.php");
	$obj_fetch = new FetchData($dbConn);
	
	include_once("PDFMerger/PDFMerger.php");
	
	$societyDetails = $obj_fetch->GetSocietyDetails($_REQUEST['society_id']);
	
	$baseDir = dirname( dirname(__FILE__) );
	$BillType = $_REQUEST['BT'];
	$dirName =  "maintenance_bills/" . $obj_fetch->objSocietyDetails->sSocietyCode . "/" . $obj_fetch->GetBillFor($_REQUEST["period_id"]) . "/";
	
	$zipFile = $obj_fetch->objSocietyDetails->sSocietyCode . "-" . str_replace(' ', '-', $obj_fetch->GetBillFor($_REQUEST["period_id"])) . "-".$BillType.".zip";
	
	$combine_file = 'All-' . $obj_fetch->objSocietyDetails->sSocietyCode . "-" . str_replace(' ', '-', $obj_fetch->GetBillFor($_REQUEST["period_id"])) . "-".$BillType.".pdf";
		
	if (file_exists($dirName . $zipFile)) {
		try
		{
			unlink($dirName . $zipFile);
		}
		catch(Exception $exp)
		{
			//echo '<br/>Exception : ' . $exp;
		}
    }
	
	if (file_exists($dirName . $combine_file)) 
	{
		try
		{
			unlink($dirName . $combine_file);
		}
		catch(Exception $exp)
		{
			//echo '<br/>Exception : ' . $exp;
		}
    }
	
	$zip = new ZipArchive;
	$pdf = new PDFMerger;
	
	if ($zip->open($dirName . $zipFile,  ZipArchive::CREATE)) 
	{
		$dir = new DirectoryIterator(dirname($dirName . '*.pdf'));
		//print_r($dir);
		/*foreach ($dir as $fileinfo) 
		{
			if (!$fileinfo->isDot()) 
			{
				//echo $dirName.$fileinfo->getFilename();
				//$pdf->addPDF($dirName.$fileinfo->getFilename(), 'all');
				//$zip->addFile($dirName.$fileinfo->getFilename(), $fileinfo->getFilename());
			}
		}*/
		
		try
		{
			$sqlUnit = "select unittbl.`unit_no` from `unit` as unittbl ORDER BY unittbl.`sort_order` ASC";
			$result = $dbConn->select($sqlUnit);
			//print_r($result);
			for($iCnt = 0; $iCnt < sizeof($result); $iCnt++)
			{
				//$unitNo = $result[$iCnt]['unit_no'];
				$specialChars = array('/','.', '*', '%', '&', ',', '(', ')', '"');
				$unitNo = str_replace($specialChars,'', $result[$iCnt]['unit_no']);

				$pdfFileName = 'bill-' . $obj_fetch->objSocietyDetails->sSocietyCode . "-" . $unitNo . '-' . $obj_fetch->GetBillFor($_REQUEST["period_id"]) . "-".$BillType.'.pdf';
				//echo $pdfFileName;
				if(file_exists($dirName.$pdfFileName))
				{
					$zip->addFile($dirName.$pdfFileName, $pdfFileName);
					$pdf->addPDF($dirName.$pdfFileName, 'all');
				}
			}
			
			$pdf->merge('file', $dirName.$combine_file);
		}
		catch(Exception $exp)
		{
			//echo "<br/>Merge Exception : " . $exp;
		}
		$zip->addFile($dirName.$combine_file, $combine_file);		
		$zip->close();
	//ob_start();
		header('Content-disposition: attachment; filename=' . $zipFile);
    		header("Content-Length: " . filesize($dirName.$zipFile));
		header('Content-Type: application/octet-stream');

//header('Content-Type: application/zip');
ob_clean(); 
readfile($dirName.$zipFile);
 //readfile();
		echo 'Archive created!';
	} 
	else 
	{
		echo 'Failed!';
	}
}
?>
