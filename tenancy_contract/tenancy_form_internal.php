<?php
include_once ("../classes/dbconst.class.php");
include_once("../classes/initialize.class.php");
include_once("../classes/include/dbop.class.php");
include_once("classes/tenancy_form.class.php");

error_reporting(0);
$dbConn = new dbop();
$dbConnRoot = new dbop(true);

$obj_tenancy_record = new tenancy_form($dbConn, $dbConnRoot);

$tenancy_landlordDetails = $obj_tenancy_record->tenancy_landlordDetails();
$tenancy_tenantDetails = $obj_tenancy_record->tenancy_tenantDetails();
$tenancy_contractDetails = $obj_tenancy_record->tenancy_contractDetails();
//echo $tenancy_tenantDetails[0]['tenant_name'];
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="" xml:lang="">
<head>
<title></title>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
 <br/>
<style type="text/css">

	p {margin: 0; padding: 0;}	.ft10{font-size:13px;font-family:Times;color:#ffffff;}
	.ft11{font-size:11px;font-family:Times;color:#ffffff;}
	.ft12{font-size:7px;font-family:Times;color:#1e3446;}
	.ft13{font-size:10px;font-family:Times;color:#1e3446;}
	.ft14{font-size:11px;font-family:Times;color:#ffffff;}
</style>
</head>
<center>
<?php  
$owner_name = "Prashant Softel Technologies INC Goregoan East";
?>
<body bgcolor="#A0A0A0" vlink="blue" link="blue">
<div id="page1-div" style="position:relative;width:892px;height:1263px;">
<img width="892" height="1263" src="target001_internal.png" alt="background image"/>
<p style="position:absolute;top:825px;left:40px;white-space:nowrap" class="ft10">Contract Information</p>
<p style="position:absolute;top:826px;left:761px;white-space:nowrap" class="ft11"><b>ﺪﻘﻌﻟا تﺎﻣﻮﻠﻌﻣ</b></p>
<p style="position:absolute;top:884px;left:299px;white-space:nowrap" class="ft12">To &#160;ﻰﻟإ</p>
<p style="position:absolute;top:869px;left:40px;white-space:nowrap" class="ft13">Contract&#160;Period &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo getDisplayFormatDate($tenancy_contractDetails[0]['start_date'])?>  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo getDisplayFormatDate($tenancy_contractDetails[0]['end_date'])?></p>
<p style="position:absolute;top:869px;left:462px;white-space:nowrap" class="ft13">Contract&#160;Value &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_contractDetails[0]['contract_value']?></p>
<p style="position:absolute;top:869px;left:375px;white-space:nowrap" class="ft13">ﺪﻘﻌﻟا ةﺮﺘﻓ</p>
<p style="position:absolute;top:886px;left:163px;white-space:nowrap" class="ft12">From&#160;ﻦﻣ</p>
<p style="position:absolute;top:869px;left:798px;white-space:nowrap" class="ft13">ﺪﻘﻌﻟا ﺔﻤﻴﻗ</p>
<p style="position:absolute;top:904px;left:40px;white-space:nowrap" class="ft13">Annual&#160;Rent &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_contractDetails[0]['annual_rent']?></p>
<p style="position:absolute;top:904px;left:462px;white-space:nowrap" class="ft13">Security&#160;Deposit&#160;Amount &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_contractDetails[0]['security_deposit']?></p>
<p style="position:absolute;top:902px;left:356px;white-space:nowrap" class="ft13">يﻮﻨﺴﻟا رﺎﺠﻳﻻا</p>
<p style="position:absolute;top:902px;left:795px;white-space:nowrap" class="ft13">ﻦﻴﻣﺄﺘﻟا ﻎﻠﺒﻣ</p>
<p style="position:absolute;top:938px;left:40px;white-space:nowrap" class="ft13">Mode&#160;of&#160;Payment &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_contractDetails[0]['name']?></p>
<p style="position:absolute;top:937px;left:792px;white-space:nowrap" class="ft13">ﻊﻓﺪﻟا ﺔﻘﻳﺮﻃ</p>
<p style="position:absolute;top:1136px;left:37px;white-space:nowrap" class="ft10">Signatures</p>
<p style="position:absolute;top:1137px;left:793px;white-space:nowrap" class="ft11"><b>تﺎﻌﻴﻗﻮﺘﻟا</b></p>
<p style="position:absolute;top:1229px;left:241px;white-space:nowrap" class="ft13">Date &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo getDisplayFormatDate($tenancy_contractDetails[0]['create_date'])?></p>
<p style="position:absolute;top:1228px;left:377px;white-space:nowrap" class="ft13">ﺦﻳرﺎﺘﻟا</p>
<p style="position:absolute;top:1229px;left:34px;white-space:nowrap" class="ft13">Tenant’s&#160;Signature&#160;ﺮﺟﺄﺘﺴﻤﻟا ﻊﻴﻗﻮﺗ</p>
<p style="position:absolute;top:1229px;left:695px;white-space:nowrap" class="ft13">Date &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo getDisplayFormatDate($tenancy_contractDetails[0]['create_date'])?></p>
<p style="position:absolute;top:1228px;left:831px;white-space:nowrap" class="ft13">ﺦﻳرﺎﺘﻟا</p>
<p style="position:absolute;top:1229px;left:486px;white-space:nowrap" class="ft13">Lessor’s&#160;Signature&#160;ﺮﺟﺆﻤﻟا ﻊﻴﻗﻮﺗ</p>
<p style="position:absolute;top:138px;left:38px;white-space:nowrap" class="ft10">Owner&#160;/&#160;Lessor&#160;Information</p>
<p style="position:absolute;top:138px;left:704px;white-space:nowrap" class="ft11"><b>ﺮﺟﺆﻤﻟا&#160;</b>/<b>ﻚﻟﺎﻤﻟا تﺎﻣﻮﻠﻌﻣ</b></p>
<p style="position:absolute;top:182px;left:38px;white-space:nowrap" class="ft13">Owner’s&#160;Name &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_landlordDetails[0]['name']?></p>
<p style="position:absolute;top:182px;left:798px;white-space:nowrap" class="ft13">ﻚﻟﺎﻤﻟا ﻢﺳا</p>
<p style="position:absolute;top:319px;left:38px;white-space:nowrap" class="ft13">Lessor’s&#160;Email &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_landlordDetails[0]['email']?></p>
<p style="position:absolute;top:319px;left:731px;white-space:nowrap" class="ft13">ﺮﺟﺆﻤﻠﻟ ﻲﻧوﺮﺘﻜﻟﻺﻟا ﺪﻳﺮﺒﻟا</p>
<p style="position:absolute;top:350px;left:38px;white-space:nowrap" class="ft13">Lessor’s&#160;Phone &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_landlordDetails[0]['mobile']?></p>
<p style="position:absolute;top:350px;left:767px;white-space:nowrap" class="ft13">ﺮﺟﺆﻤﻟا ﻒﺗﺎﻫ ﻢﻗر</p>
<p style="position:absolute;top:213px;left:38px;white-space:nowrap" class="ft13">Lessor’s&#160;Name &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_landlordDetails[0]['name']?></p>
<p style="position:absolute;top:213px;left:798px;white-space:nowrap" class="ft13">ﺮﺟﺆﻤﻟا ﻢﺳا</p>
<p style="position:absolute;top:246px;left:38px;white-space:nowrap" class="ft13">Lessor’s&#160;Emirates&#160;ID&#160; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_landlordDetails[0]['emirate_no']?></p>
<p style="position:absolute;top:246px;left:733px;white-space:nowrap" class="ft13">ﺮﺟﺆﻤﻠﻟ ﺔﻴﺗرﺎﻣﻺﻟا ﺔﻳﻮﻬﻟا</p>
<p style="position:absolute;top:278px;left:38px;white-space:nowrap" class="ft13">License&#160;No. &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_landlordDetails[0]['license_no']?></p>
<p style="position:absolute;top:278px;left:461px;white-space:nowrap" class="ft13">Licensing&#160;Authority</p>
<p style="position:absolute;top:278px;left:371px;white-space:nowrap" class="ft13">ﺔﺼﺧﺮﻟا ﻢﻗر</p>
<p style="position:absolute;top:278px;left:779px;white-space:nowrap" class="ft13">ﺺﻴﺧﺮﺘﻟا ﺔﻄﻠﺳ</p>
<p style="position:absolute;top:295px;left:152px;white-space:nowrap" class="ft12">Incase&#160;of&#160;a&#160;Company</p>
<p style="position:absolute;top:294px;left:246px;white-space:nowrap" class="ft12">ﺔﻛﺮﺷ ﺖﻧﺎﻛ لﺎﺣ ﻲﻓ</p>
<p style="position:absolute;top:295px;left:584px;white-space:nowrap" class="ft12">Incase&#160;of&#160;a&#160;Company</p>
<p style="position:absolute;top:294px;left:678px;white-space:nowrap" class="ft12">ﺔﻛﺮﺷ ﺖﻧﺎﻛ لﺎﺣ ﻲﻓ</p>
<p style="position:absolute;top:91px;left:39px;white-space:nowrap" class="ft13">Date &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo getDisplayFormatDate($tenancy_contractDetails[0]['create_date'])?></p>
<p style="position:absolute;top:90px;left:190px;white-space:nowrap" class="ft13">ﺦﻳرﺎﺘﻟا</p>
<p style="position:absolute;top:394px;left:40px;white-space:nowrap" class="ft10">Tenant&#160;Information</p>
<p style="position:absolute;top:394px;left:738px;white-space:nowrap" class="ft11"><b>ﺮﺟﺄﺘﺴﻤﻟا تﺎﻣﻮﻠﻌﻣ</b></p>
<p style="position:absolute;top:430px;left:39px;white-space:nowrap" class="ft13">Tenant’s&#160;Name &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_tenantDetails[0]['tenant_name'] ?></p>
<p style="position:absolute;top:430px;left:785px;white-space:nowrap" class="ft13">ﺮﺟﺄﺘﺴﻤﻟا ﻢﺳا</p>
<p style="position:absolute;top:560px;left:39px;white-space:nowrap" class="ft13">Tenant’s&#160;Phone &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_tenantDetails[0]['mobile_no'] ?></p>
<p style="position:absolute;top:560px;left:329px;white-space:nowrap" class="ft13">ﺮﺟﺄﺘﺴﻤﻟا ﻒﺗﺎﻫ ﻢﻗر</p>
<p style="position:absolute;top:461px;left:39px;white-space:nowrap" class="ft13">Tenant’s&#160;Emirates&#160;ID &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_tenantDetails[0]['emirate_no'] ?></p>
<p style="position:absolute;top:462px;left:722px;white-space:nowrap" class="ft13">ﺮﺟﺄﺘﺴﻤﻠﻟ ﺔﻴﺗارﺎﻣﻹا ﺔﻳﻮﻬﻟا</p>
<p style="position:absolute;top:529px;left:39px;white-space:nowrap" class="ft13">Tenant’s&#160;Email &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_tenantDetails[0]['email'] ?></p>
<p style="position:absolute;top:529px;left:722px;white-space:nowrap" class="ft13">ﺮﺟﺄﺘﺴﻤﻠﻟ ﻲﻧوﺮﺘﻜﻟﻹا ﺪﻳﺮﺒﻟا</p>
<p style="position:absolute;top:491px;left:461px;white-space:nowrap" class="ft13">Licensing&#160;Authority</p>
<p style="position:absolute;top:491px;left:779px;white-space:nowrap" class="ft13">ﺺﻴﺧﺮﺘﻟا ﺔﻄﻠﺳ</p>
<p style="position:absolute;top:491px;left:39px;white-space:nowrap" class="ft13">License&#160;No. &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_tenantDetails[0]['license_no'] ?></p>
<p style="position:absolute;top:491px;left:371px;white-space:nowrap" class="ft13">ﺔﺼﺧﺮﻟا ﻢﻗر</p>
<p style="position:absolute;top:508px;left:152px;white-space:nowrap" class="ft12">Incase&#160;of&#160;a&#160;Company</p>
<p style="position:absolute;top:507px;left:246px;white-space:nowrap" class="ft12">ﺔﻛﺮﺷ ﺖﻧﺎﻛ لﺎﺣ ﻲﻓ</p>
<p style="position:absolute;top:508px;left:584px;white-space:nowrap" class="ft12">Incase&#160;of&#160;a&#160;Company</p>
<p style="position:absolute;top:507px;left:678px;white-space:nowrap" class="ft12">ﺔﻛﺮﺷ ﺖﻧﺎﻛ لﺎﺣ ﻲﻓ</p>
<p style="position:absolute;top:560px;left:461px;white-space:nowrap" class="ft13">Number&#160;&#160;of&#160;Co-Occupants &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_tenantDetails[0]['members'] ?></p>
<p style="position:absolute;top:559px;left:787px;white-space:nowrap" class="ft13">ﻦﻴﻨﻃﺎﻘﻟا دﺪﻋ</p>
<p style="position:absolute;top:683px;left:38px;white-space:nowrap" class="ft13">Plot&#160;No. &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_tenantDetails[0]['unit_no'] ?></p>
<p style="position:absolute;top:684px;left:378px;white-space:nowrap" class="ft13">ضرﻷا ﻢﻗر</p>
<p style="position:absolute;top:683px;left:461px;white-space:nowrap" class="ft13">Makani&#160;No.</p>
<p style="position:absolute;top:684px;left:800px;white-space:nowrap" class="ft13">ﻲﻧﺎﻜﻣ ﻢﻗر</p>
<p style="position:absolute;top:717px;left:461px;white-space:nowrap" class="ft13">Property&#160;No.</p>
<p style="position:absolute;top:718px;left:804px;white-space:nowrap" class="ft13">رﺎﻘﻌﻟا ﻢﻗر</p>
<p style="position:absolute;top:717px;left:38px;white-space:nowrap" class="ft13">Building&#160;Name &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_tenantDetails[0]['wing'] ?></p>
<p style="position:absolute;top:718px;left:371px;white-space:nowrap" class="ft13">ﻰﻨﺒﻤﻟا ﻢﺳا</p>
<p style="position:absolute;top:785px;left:38px;white-space:nowrap" class="ft13">Location</p>
<p style="position:absolute;top:785px;left:395px;white-space:nowrap" class="ft13">ﻊﻗﻮﻤﻟا</p>
<p style="position:absolute;top:787px;left:768px;white-space:nowrap" class="ft13">(</p>
<p style="position:absolute;top:787px;left:792px;white-space:nowrap" class="ft13">)</p>
<p style="position:absolute;top:785px;left:461px;white-space:nowrap" class="ft13">Premises&#160;No.&#160;(DEWA)</p>
<p style="position:absolute;top:785px;left:771px;white-space:nowrap" class="ft13">اﻮﻳد&#160;&#160;ﻰﻨﺒﻤﻟا ﻢﻗر</p>
<p style="position:absolute;top:751px;left:38px;white-space:nowrap" class="ft13">Property&#160;Type &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_tenantDetails[0]['name'] ?></p>
<p style="position:absolute;top:751px;left:375px;white-space:nowrap" class="ft13">ةﺪﺣﻮﻟا عﻮﻧ</p>
<p style="position:absolute;top:751px;left:461px;white-space:nowrap" class="ft13">Property&#160;Area&#160;(s.m) &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_tenantDetails[0]['flat_configuration'] ?></p>
<p style="position:absolute;top:754px;left:762px;white-space:nowrap" class="ft13">.</p>
<p style="position:absolute;top:754px;left:737px;white-space:nowrap" class="ft13">(ﻊﺑﺮﻣ&#160;ﺮﺘﻣ)&#160;رﺎﻘﻌﻟا ﺔﺣﺎﺴﻣ</p>
<p style="position:absolute;top:648px;left:38px;white-space:nowrap" class="ft13">Property&#160;Usage</p>
<p style="position:absolute;top:649px;left:245px;white-space:nowrap" class="ft13">Industrial</p>
<p style="position:absolute;top:648px;left:298px;white-space:nowrap" class="ft13">ﻲﻋﺎﻨﺻ</p>
<p style="position:absolute;top:650px;left:402px;white-space:nowrap" class="ft13">Commercial&#160;يرﺎﺠﺗ</p>
<p style="position:absolute;top:650px;left:574px;white-space:nowrap" class="ft13">Residential&#160;ﻲﻨﻜﺳ</p>
<p style="position:absolute;top:648px;left:780px;white-space:nowrap" class="ft13">رﺎﻘﻌﻟا ماﺪﺨﺘﺳا</p>
<p style="position:absolute;top:610px;left:40px;white-space:nowrap" class="ft10">Property&#160;Information</p>
<p style="position:absolute;top:610px;left:757px;white-space:nowrap" class="ft11"><b>رﺎﻘﻌﻟا تﺎﻣﻮﻠﻌﻣ</b></p>
</div>
</body>
</html>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="" xml:lang="">
<head>
<title></title>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
 <br/>
<style type="text/css">
<!--
	p {margin: 0; padding: 0;}	.ft20{font-size:13px;font-family:Times;color:#ffffff;}
	.ft21{font-size:7px;font-family:Times;color:#1e3446;}
	.ft22{font-size:11px;font-family:Times;color:#ffffff;}
	.ft23{font-size:10px;font-family:Times;color:#1e3446;}
	.ft24{font-size:8px;font-family:Times;color:#1e3446;}
	.ft25{font-size:7px;line-height:16px;font-family:Times;color:#1e3446;}
-->
</style>
</head>
<body bgcolor="#A0A0A0" vlink="blue" link="blue">
<div id="page2-div" style="position:relative;width:892px;height:1263px;">
<img width="892" height="1263" src="target002_internal.png" alt="background image"/>
<p style="position:absolute;top:1068px;left:38px;white-space:nowrap" class="ft20">Signatures</p>
<p style="position:absolute;top:1069px;left:796px;white-space:nowrap" class="ft22"><b>تﺎﻌﻴﻗﻮﺘﻟا</b></p>
<p style="position:absolute;top:1174px;left:239px;white-space:nowrap" class="ft23">Date &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo getDisplayFormatDate($tenancy_contractDetails[0]['create_date'])?></p>
<p style="position:absolute;top:1173px;left:375px;white-space:nowrap" class="ft23">ﺦﻳرﺎﺘﻟا</p>
<p style="position:absolute;top:1174px;left:32px;white-space:nowrap" class="ft23">Tenant’s Signature&#160;ﺮﺟﺄﺘﺴﻤﻟا ﻊﻴﻗﻮﺗ</p>
<p style="position:absolute;top:1174px;left:696px;white-space:nowrap" class="ft23">Date &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo getDisplayFormatDate($tenancy_contractDetails[0]['create_date'])?></p>
<p style="position:absolute;top:1173px;left:832px;white-space:nowrap" class="ft23">ﺦﻳرﺎﺘﻟا</p>
<p style="position:absolute;top:1174px;left:487px;white-space:nowrap" class="ft23">Lessor’s Signature&#160;ﺮﺟﺆﻤﻟا ﻊﻴﻗﻮﺗ</p>
<p style="position:absolute;top:66px;left:37px;white-space:nowrap" class="ft10">Terms and&#160;Conditions</p>
<p style="position:absolute;top:104px;left:35px;white-space:nowrap" class="ft13">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ANNEXURE FORMING PART OF THE TENANCY CONTRACT NO. ASRE/257/N06/207 BETWEEN THE LANDLORD MR. MAYANK KUMAR AMBALAL PATEL&PALLAV S. PATEL,</p>
<p style="position:absolute;top:121px;left:35px;white-space:nowrap" class="ft13">P.B.NO:7350, DUBAI. AND MR. SANGEETH ARAVINDAKSHAN, P.B. NO :12019, DUBAI. IN RESPECT OF APARTMENT NO: 207 IN BUILDING NO: N06, PERSIA CLUSTER,</p>
<p style="position:absolute;top:136px;left:35px;white-space:nowrap" class="ft13">INTERNATIONAL CITY, DUBAI. </p>
<p style="position:absolute;top:170px;left:35px;white-space:nowrap" class="ft13">1.&#160; ELECTRICITY, WATER, SEWERAGE, INTERNET, TELEPHONE AND OTHER UTILITIES DEPOSITS AND BILLS TO BE BORNE BY THE TENANT.</p>
<p style="position:absolute;top:199px;left:35px;white-space:nowrap" class="ft13">2.&#160; REFUNDABLE DEPOSIT TO DEWA TO BE PAID BY THE TENANT. THE TENANT IS REQUIRED TO PAY ADMINISTRATION FEE AND EJARI </p>
<p style="position:absolute;top:215px;left:35px;white-space:nowrap" class="ft13">FEE TO THE MANAGEMENT OFFICE ON EVERY RENEWAL</p>
<p style="position:absolute;top:244px;left:35px;white-space:nowrap" class="ft13">3.&#160; THE TENANT WILL PROIVDE ATLEAST 90 DAYS ADVANCE NOTICE BEFORE THE EXPIRATION DATE OF THE TENANCY AGREEMENT FOR VACATING/RENEWAL OF THE</p>
<p style="position:absolute;top:260px;left:35px;white-space:nowrap" class="ft13"> TENANCY. THE RENEWAL OF THE TENANCY IS AT THE DISCRETION OF THE LANDLORD. IN CASE THE TENANT DOES NOT WANT TO RENEW THE AGREEMENT,</p>
<p style="position:absolute;top:276px;left:35px;white-space:nowrap" class="ft13"> THREE MONTHS PRIOR NOTICE IN WRITING TO BE GIVEN TO THE LANDLORD ELSE THE TENANT IS LIABLE TO PAY THREE MONTHS RENT AS PENALTY. </p>
<p style="position:absolute;top:305px;left:35px;white-space:nowrap" class="ft13">4.&#160; THE LANDLORD WILL PROIVDE TO THE TENANT ATLEAST 90 DAYS WRITTEN ADVANCE NOTICE BEFORE EXPIRATION OF THE TENANCY AGREEMENT FOR ANY RENT </p>
<p style="position:absolute;top:321px;left:35px;white-space:nowrap" class="ft13">INCREASE TO BE OMPOSED FOR THE SUBSEQUENT YEAR.</p>
<p style="position:absolute;top:350px;left:35px;white-space:nowrap" class="ft13">5.&#160; INCASE OF ANY TERMINATION OF THE CONTRACT BEFORE ITS EXPIRY, THE TENANT SHOULD GIVE THREE MONTHS NOTICE IN WRITING AND THREE MONTHS RENT</p>
<p style="position:absolute;top:366px;left:35px;white-space:nowrap" class="ft13">AS PENALTY TO THE LANDLORD.</p>
<p style="position:absolute;top:395px;left:35px;white-space:nowrap" class="ft13">6.&#160; ANY DAMAGE TO ANY OF THE APPLIANCES AND OTHER FIXTURES AND FITTINGS PROVIDED BY THE LANDLORD, SHALL BE REPAIRED BY THE TENANT AND HANDED</p>
<p style="position:absolute;top:411px;left:35px;white-space:nowrap" class="ft13">OVER TO THE OWNER IN GOOD WORKING CONDITION.</p>
<p style="position:absolute;top:440px;left:35px;white-space:nowrap" class="ft13">7.&#160; MAJOR MAINTENANCE OF THE PREMISES WILL BE DONE BY THE LANDLORD AND MINOR MAINTENANCE WILL BE THE RESPONSIBILITY OF THE TENANT.</p>
<p style="position:absolute;top:469px;left:35px;white-space:nowrap" class="ft13">8.&#160; DURING THE TENANCY PERIOD OF THIS AGREEMENT, THE LANDLORD WILL HAVE THE RIGHT TO INSPECT THE PREMISES WITH AN ADVANCE NOTICE OF 3-4 DAYS.</p>
<p style="position:absolute;top:498px;left:35px;white-space:nowrap" class="ft13">9.&#160;ALL KIND OF TAXES IMPOSED BY LOCAL AUTHORITIES WILL BE THE RESPONSIBILITY OF TENANT NOW OR IN THE FUTURE. </p>
<p style="position:absolute;top:527px;left:35px;white-space:nowrap" class="ft13">10.&#160; IN CASE OF ANY POST-DATED CHEQUE GETTING DISHONOURED/ RETURNED OR THE TENANT NOT RENEWING THE CONTRACT ON TIME, THE LANDLORD HAS THE RIGHT </p>
<p style="position:absolute;top:543px;left:35px;white-space:nowrap" class="ft13">TO OCCUPY THE PREMISES WITHOUT ANY PRIOR NOTICE TO THE TENANT.  ALSO, A FINE OF DHS.  5000/-    WILL BE LEVIED ON THE TENANT IF THE CHEQUE GETS DISHONORED.</p>
<p style="position:absolute;top:559px;left:35px;white-space:nowrap" class="ft13">IN THE EVENT ANY CHEQUE ISSUED TOWARDS THE ANNUAL RENT GETS RETURNED, THE TENANT EMPOWERS THE LANDLORD TO VACATE THE TENANT FROM THE PREMISES</p>
<p style="position:absolute;top:575px;left:35px;white-space:nowrap" class="ft13">WITHOUT PRIOR NOTICE.</p>
<p style="position:absolute;top:604px;left:35px;white-space:nowrap" class="ft13">11.&#160; THE TENANT UNDERTAKES TO ARRANGE FOR NECESSARY INSURANCE COVERAGE FOR THE PERSONS OCCUPYING THE PREMISES AND THE ITEMS STORED IN THE LEASED </p>
<p style="position:absolute;top:620px;left:35px;white-space:nowrap" class="ft13">PREMISES INCLUDING BUT NOT LIMITED TO FURNITURE, FIXTURES, MODIFICATIONS, CASH IN HAND ETC. AT TENANT’S OWN COST AND RESPONSIBILITY.  THE LANDLORD SHALL</p>
<p style="position:absolute;top:636px;left:35px;white-space:nowrap" class="ft13"> NOT IN ANY MANNER BE RESPONSIBLE OR LIABLE FOR THE LOSS/DAMAGE/DESTRUCTION OF THE ITEMS OR FOR THE INJURIES/DEATH, IF ANY SUSTAINED TO THE </p>
<p style="position:absolute;top:652px;left:35px;white-space:nowrap" class="ft13">OCCUPANTS DUE TO ANY REASON WHATSOEVER.</p>
<p style="position:absolute;top:681px;left:35px;white-space:nowrap" class="ft13">12.&#160; SECURITY DEPOSIT SHALL BE REFUNDED ONLY AFTER THE TENANT SUBMITS FINAL DISCHARGED DEWA BILL AND AFTER THE LANDLORD/MAINTENANCE DEPARTMENT HAS</p>
<p style="position:absolute;top:697px;left:35px;white-space:nowrap" class="ft13"> GIVEN CLEARANCE OF ANY BREAKAGE IN THE PREMISES.  IN THE EVENT OF ANY BREAKAGE THE TENANT EMPOWERS THE LANDLORD TO DEDUCT AN AMOUNT EQUIVALENT TO </p>
<p style="position:absolute;top:713px;left:35px;white-space:nowrap" class="ft13">THE SAME FROM THE SECURITY DEPOSIT.  IN CASE THE COST OF THE DAMAGED ARTICLE IS MORE THAN THE SECURITY DEPOSIT, THE TENANT AGREES TO PAY THE DIFFERENCE.</p>
<p style="position:absolute;top:742px;left:35px;white-space:nowrap" class="ft13">13.&#160; THE TENANT SHALL NOT MAKE ANY ALTERATIONS TO THE PREMISES WITHOUT THE PRIOR PERMISSION OF THE LANDLORD AND THE RELEVANT AUTHORITIES.  </p>
<p style="position:absolute;top:758px;left:35px;white-space:nowrap" class="ft13">IN CASE OF TEMPORARY ALTERNATIONS WITH PRIOR APPROVAL, THE SAME SHALL BE REMOVED BY THE TENANT ON VACATING THE PREMISES AND THE PREMISES SHALL</p>
<p style="position:absolute;top:774px;left:35px;white-space:nowrap" class="ft13"> BE HANDED OVER TO THE LANDLORD IN ITS ORIGINAL CONDITION.</p>
<p style="position:absolute;top:803px;left:35px;white-space:nowrap" class="ft13">14.&#160; THE PREMISES IS FOR RESIDENCIAL PURPOSE OF FAMILY ONLY AND SHALL NOT BE USED FOR ANY OTHER PURPOSE. THE MAXIMUM PERMISSIBLE PERSONS </p>
<p style="position:absolute;top:819px;left:35px;white-space:nowrap" class="ft13"> FOR ONE BEDROOM SHALL BE THREE NOS. PARTITIONS AND SHARING THE APARTMENT IS NOT ALLOWED.</p>
<p style="position:absolute;top:848px;left:35px;white-space:nowrap" class="ft13">15.&#160; THE LANDLORD SHALL NOT BE RESPONSIBLE IF ANY LOCAL AUTHORITIES IMPOSE ANY RESTRICTION ON BACHELORS OCCUPYING THE APARTMENT. IN SUCH </p>
<p style="position:absolute;top:864px;left:35px;white-space:nowrap" class="ft13">AN EVENT THE TENANT WILL HAVE TO ADHERE TO THE REGULATORY REQUIREMENTS OF THE LOCAL AUTHORITY.</p>

<p style="position:absolute;top:893px;left:35px;white-space:nowrap" class="ft13">16.&#160; THE TENANT UNDERTAKES TO VACATE THE PREMISES UPON EXPIRY OF CONTRACT. OTHERWISE, THE LANDLORD HAS THE RIGHT TO CHARGE ONE MONTH’S </p>
<p style="position:absolute;top:909px;left:35px;white-space:nowrap" class="ft13">RENT FOR ADDITIONAL DAY’S.</p>


<p style="position:absolute;top:1231px;left:495px;white-space:nowrap" class="ft24">support@dubailand.gov.ae</p>
</div>
</body>
</html>
