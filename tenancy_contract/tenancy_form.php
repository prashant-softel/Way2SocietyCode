<?php
include_once ("../classes/dbconst.class.php");
include_once("../classes/initialize.class.php");
include_once("../classes/include/dbop.class.php");
include_once("classes/tenancy_form.class.php");

error_reporting(0);
$dbConn = new dbop();
$dbConnRoot = new dbop(true);


$obj_tenancy_record = new tenancy_form($dbConn, $dbConnRoot, $landLordDB, $landLordDBRoot);

$tenancy_landlordDetails = $obj_tenancy_record->tenancy_landlordDetails();
$tenancy_tenantDetails = $obj_tenancy_record->tenancy_tenantDetails();
$tenancy_contractDetails = $obj_tenancy_record->tenancy_contractDetails();
$tenancy_paymentmode = $obj_tenancy_record->tenancy_paymentmode();
$note = $tenancy_tenantDetails[0]['note'];

$note1= substr(strip_tags($note),0,120);
$note2= substr(strip_tags($note),120,115);
$note3= substr(strip_tags($note),235,118);
$note4= substr(strip_tags($note),353,118);
$note5= substr(strip_tags($note),471,118);
// echo $note;
$mode = $tenancy_paymentmode;
$property = $tenancy_tenantDetails[0]['property_type'];
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
<body bgcolor="#A0A0A0" vlink="blue" link="blue">
<div id="page1-div" style="position:relative;width:892px;height:1263px;">
<img width="892" height="1263" src="target001.png" alt="background image"/>
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
<p style="position:absolute;top:938px;left:40px;white-space:nowrap" class="ft13">Mode&#160;of&#160;Payment &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $mode ?></p>
<p style="position:absolute;top:937px;left:792px;white-space:nowrap" class="ft13">ﻊﻓﺪﻟا ﺔﻘﻳﺮﻃ</p>
<p style="position:absolute;top:994px;left:532px;white-space:nowrap" class="ft12">.</p>
<p style="position:absolute;top:1054px;left:676px;white-space:nowrap" class="ft12">.</p>
<p style="position:absolute;top:1099px;left:560px;white-space:nowrap" class="ft12">.</p>
<p style="position:absolute;top:1016px;left:848px;white-space:nowrap" class="ft12">&#160;.1</p>
<p style="position:absolute;top:1043px;left:848px;white-space:nowrap" class="ft12">&#160;.2</p>
<p style="position:absolute;top:1098px;left:848px;white-space:nowrap" class="ft12">&#160;.3</p>
<p style="position:absolute;top:976px;left:37px;white-space:nowrap" class="ft10">Terms and&#160;Conditions</p>
<p style="position:absolute;top:977px;left:743px;white-space:nowrap" class="ft11"><b>طوﺮﺸﻟا و مﺎﻜﺣﻷا</b></p>
<p style="position:absolute;top:1010px;left:38px;white-space:nowrap" class="ft12">1.&#160;The&#160;tenant&#160;has&#160;inspected&#160;the&#160;premises&#160;and&#160;agreed&#160;to&#160;lease&#160;the&#160;unit&#160;on&#160;its&#160;current&#160;condition.</p>
<p style="position:absolute;top:1013px;left:533px;white-space:nowrap" class="ft12">ﺔﻴﻟﺎﺤﻟا ﻪﺘﻟﺎﺣ ﻰﻠﻋ رﺎﻘﻌﻟا رﺎﺠﺌﺘﺳإ ﻰﻠﻋ ﻖﻓاوو رﺎﺠﻳﻻا عﻮﺿﻮﻣ ةﺪﺣﻮﻟا ﺮﺟﺄﺘﺴﻤﻟا ﻦﻳﺎﻋ</p>
<p style="position:absolute;top:1031px;left:38px;white-space:nowrap" class="ft12">2.&#160;Tenant&#160;undertakes&#160;to&#160;use&#160;the&#160;premises&#160;for&#160;designated&#160;purpose,&#160;tenant&#160;has&#160;no&#160;rights&#160;to&#160;</p>
<p style="position:absolute;top:1043px;left:38px;white-space:nowrap" class="ft12">transfer&#160;or&#160;relinquish&#160;the&#160;tenancy&#160;contract&#160;either&#160;with&#160;or&#160;to&#160;without&#160;counterpart&#160;to&#160;any&#160;without&#160;</p>
<p style="position:absolute;top:1055px;left:38px;white-space:nowrap" class="ft12">landlord&#160;written&#160;approval.&#160;Also,&#160;tenant&#160;is&#160;not&#160;allowed&#160;to&#160;sublease&#160;the&#160;premises&#160;or&#160;any&#160;part&#160;thereof&#160;</p>
<p style="position:absolute;top:1067px;left:38px;white-space:nowrap" class="ft12">to&#160;third&#160;party&#160;in&#160;whole&#160;or&#160;in&#160;part&#160;unless&#160;it&#160;is&#160;legally&#160;permitted.</p>
<p style="position:absolute;top:1040px;left:467px;white-space:nowrap" class="ft12">ﺪﻘﻋ ﻦﻋ لزﺎﻨﺘﻟا وأ ﻞﻳﻮﺤﺗ ﺮﺟﺄﺘﺴﻤﻠﻟ زﻮﺠﻳ ﻻ و ،ﻪﻟ ﺺﺼﺨﻤﻟا ضﺮﻐﻠﻟ رﻮﺟﺄﻤﻟا لﺎﻤﻌﺘﺳﺎﺑ ﺮﺟﺄﺘﺴﻤﻟا ﺪﻬﻌﺘﻳ</p>
<p style="position:absolute;top:1055px;left:480px;white-space:nowrap" class="ft12">وأ رﻮﺟﺄﻤﻟا ﺮﻴﺟﺄﺗ ﺮﺟﺄﺘﺴﻤﻠﻟ زﻮﺠﻳ ﻻ ﺎﻤﻛ ،ﺎﻴﻄﺧ ﻚﻟﺎﻤﻟا ﺔﻘﻓاﻮﻣ نود ﻞﺑﺎﻘﻣ نود وأ ﻞﺑﺎﻘﻤﺑ ﺮﻴﻐﻠﻟ رﺎﺠﻳﻻا&#160;</p>
<p style="position:absolute;top:1070px;left:678px;white-space:nowrap" class="ft12">ﺎﻧﻮﻧﺎﻗ ﻚﻟﺬﺑ ﺢﻤﺴﻳ ﻢﻟﺎﻣ ﻦﻃﺎﺒﻟا ﻦﻣ ﻪﻨﻣ ءﺰﺟ يأ&#160;</p>
<p style="position:absolute;top:1086px;left:38px;white-space:nowrap" class="ft12">3.&#160;The&#160;tenant&#160;undertakes&#160;not&#160;to&#160;make&#160;any&#160;amendments,&#160;modifications&#160;or&#160;addendums&#160;to&#160;the&#160;</p>
<p style="position:absolute;top:1098px;left:38px;white-space:nowrap" class="ft12">premises&#160;subject&#160;of&#160;the&#160;contract&#160;without&#160;obtaining&#160;the&#160;landlord&#160;written&#160;approval.&#160;Tenant&#160;shall&#160;be&#160;</p>
<p style="position:absolute;top:1110px;left:38px;white-space:nowrap" class="ft12">liable&#160;for&#160;any&#160;damages&#160;or&#160;failure&#160;due&#160;to&#160;that.</p>
<p style="position:absolute;top:1096px;left:479px;white-space:nowrap" class="ft12">ﻚﻟﺎﻤﻟا ﺔﻘﻓاﻮﻣ نود ﺪﻘﻌﻟا عﻮﺿﻮﻣ رﺎﻘﻌﻟا ﻰﻠﻋ تﺎﻓﺎﺿإ وأ تﻼﻳﺪﻌﺗ يأ ءاﺮﺟإ مﺪﻌﺑ ﺮﺟﺄﺘﺴﻤﻟا ﺪﻬﻌﺘﻳ</p>
<p style="position:absolute;top:1111px;left:562px;white-space:nowrap" class="ft12">رﺎﻘﻌﻟﺎﺑ ﻖﺤﻠﻳ ﻒﻠﺗ وأ ﺺﻘﻧ وأ راﺮﺿأ يأ ﻦﻋ ﻻوﺆﺴﻣ ﺮﺟﺄﺘﺴﻤﻟا نﻮﻜﻳ و ،ﺔﻴﻄﺨﻟا&#160;</p>
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
<p style="position:absolute;top:491px;left:461px;white-space:nowrap" class="ft13">Licensing&#160;Authority&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_tenantDetails[0]['license_authority'] ?></p>
<p style="position:absolute;top:491px;left:779px;white-space:nowrap" class="ft13">ﺺﻴﺧﺮﺘﻟا ﺔﻄﻠﺳ</p>
<p style="position:absolute;top:491px;left:39px;white-space:nowrap" class="ft13">License&#160;No. &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_tenantDetails[0]['license_no'] ?></p>
<p style="position:absolute;top:491px;left:371px;white-space:nowrap" class="ft13">ﺔﺼﺧﺮﻟا ﻢﻗر</p>
<p style="position:absolute;top:508px;left:152px;white-space:nowrap" class="ft12">Incase&#160;of&#160;a&#160;Company</p>
<p style="position:absolute;top:507px;left:246px;white-space:nowrap" class="ft12">ﺔﻛﺮﺷ ﺖﻧﺎﻛ لﺎﺣ ﻲﻓ</p>
<p style="position:absolute;top:508px;left:584px;white-space:nowrap" class="ft12">Incase&#160;of&#160;a&#160;Company</p>
<p style="position:absolute;top:507px;left:678px;white-space:nowrap" class="ft12">ﺔﻛﺮﺷ ﺖﻧﺎﻛ لﺎﺣ ﻲﻓ</p>
<p style="position:absolute;top:560px;left:461px;white-space:nowrap" class="ft13">Number&#160;&#160;of&#160;Co-Occupants &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_tenantDetails[0]['members'] ?></p>
<p style="position:absolute;top:559px;left:787px;white-space:nowrap" class="ft13">ﻦﻴﻨﻃﺎﻘﻟا دﺪﻋ</p>
<p style="position:absolute;top:683px;left:38px;white-space:nowrap" class="ft13">Plot&#160;No. &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_tenantDetails[0]['plot_no'] ?></p>
<p style="position:absolute;top:684px;left:378px;white-space:nowrap" class="ft13">ضرﻷا ﻢﻗر</p>
<p style="position:absolute;top:683px;left:461px;white-space:nowrap" class="ft13">Makani&#160;No. &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_tenantDetails[0]['makani_no'] ?></p>
<p style="position:absolute;top:684px;left:800px;white-space:nowrap" class="ft13">ﻲﻧﺎﻜﻣ ﻢﻗر</p>
<p style="position:absolute;top:717px;left:461px;white-space:nowrap" class="ft13">Property&#160;No. &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_tenantDetails[0]['property_no'] ?></p>
<p style="position:absolute;top:718px;left:804px;white-space:nowrap" class="ft13">رﺎﻘﻌﻟا ﻢﻗر</p>
<p style="position:absolute;top:717px;left:38px;white-space:nowrap" class="ft13">Building&#160;Name &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_tenantDetails[0]['wing'] ?></p>
<p style="position:absolute;top:718px;left:371px;white-space:nowrap" class="ft13">ﻰﻨﺒﻤﻟا ﻢﺳا</p>
<p style="position:absolute;top:785px;left:38px;white-space:nowrap" class="ft13">Location &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_tenantDetails[0]['location'] ?></p>
<p style="position:absolute;top:785px;left:395px;white-space:nowrap" class="ft13">ﻊﻗﻮﻤﻟا</p>
<p style="position:absolute;top:787px;left:768px;white-space:nowrap" class="ft13">(</p>
<p style="position:absolute;top:787px;left:792px;white-space:nowrap" class="ft13">)</p>
<p style="position:absolute;top:785px;left:461px;white-space:nowrap" class="ft13">Premises&#160;No.&#160;(DEWA) &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_tenantDetails[0]['premises_no'] ?></p>
<p style="position:absolute;top:785px;left:771px;white-space:nowrap" class="ft13">اﻮﻳد&#160;&#160;ﻰﻨﺒﻤﻟا ﻢﻗر</p>
<p style="position:absolute;top:751px;left:38px;white-space:nowrap" class="ft13">Property&#160;Type &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_tenantDetails[0]['flat_configuration'] ?></p>
<p style="position:absolute;top:751px;left:375px;white-space:nowrap" class="ft13">ةﺪﺣﻮﻟا عﻮﻧ</p>
<p style="position:absolute;top:751px;left:461px;white-space:nowrap" class="ft13">Property&#160;Area&#160;(s.m) &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo $tenancy_tenantDetails[0]['area'] ?></p>
<p style="position:absolute;top:754px;left:762px;white-space:nowrap" class="ft13">.</p>
<p style="position:absolute;top:754px;left:737px;white-space:nowrap" class="ft13">(ﻊﺑﺮﻣ&#160;ﺮﺘﻣ)&#160;رﺎﻘﻌﻟا ﺔﺣﺎﺴﻣ</p>
<p style="position:absolute;top:648px;left:38px;white-space:nowrap" class="ft13">Property&#160;Usage</p>
<!-- radio select property type -->
<?php if($property == 2){ ?>
	<!-- commerical -->
	<p style="position:absolute;top:651px;left:383px;white-space:nowrap" class="ft13"><img src = "../images/radio.png" style="width:13px;"></p>
<?php }elseif($property == 1){ ?>
	<!-- industrial -->
	<p style="position:absolute;top:651px;left:225px;white-space:nowrap" class="ft13"><img src = "../images/radio.png" style="width:13px;"></p>
<?php } else{ ?> 
	<!-- residential -->
	<p style="position:absolute;top:651px;left:554px;white-space:nowrap" class="ft13"><img src = "../images/radio.png" style="width:13px;"></p>
<?php }?>
<!-- end of property selection -->
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

	p {margin: 0; padding: 0;}	.ft20{font-size:13px;font-family:Times;color:#ffffff;}
	.ft21{font-size:7px;font-family:Times;color:#1e3446;}
	.ft22{font-size:11px;font-family:Times;color:#ffffff;}
	.ft23{font-size:10px;font-family:Times;color:#1e3446;}
	.ft24{font-size:8px;font-family:Times;color:#1e3446;}
	.ft25{font-size:7px;line-height:16px;font-family:Times;color:#1e3446;}

</style>
</head>
<body bgcolor="#A0A0A0" vlink="blue" link="blue">
<div id="page2-div" style="position:relative;width:892px;height:1263px;">
<img width="892" height="1263" src="target002.png" alt="background image"/>
<p style="position:absolute;top:804px;left:35px;white-space:nowrap" class="ft20">Additional Terms </p>
<!-- for adding notes in additional term -->
<p style="position:absolute;top:840px;left:71px;white-space:nowrap" class="ft30"><?php echo $note1;?>  </p>
<p style="position:absolute;top:874px;left:71px;white-space:nowrap" class="ft30"><?php echo $note2;?></p>
<p style="position:absolute;top:908px;left:71px;white-space:nowrap" class="ft30"><?php echo $note3;?> </p>
<p style="position:absolute;top:942px;left:71px;white-space:nowrap" class="ft30"><?php echo $note4?></p>
<p style="position:absolute;top:976px;left:71px;white-space:nowrap" class="ft30"><?php echo $note5?> </p>
<!-- notes end -->
<p style="position:absolute;top:712px;left:35px;white-space:nowrap" class="ft20">Attachments for Ejari Registration</p>
<p style="position:absolute;top:558px;left:35px;white-space:nowrap" class="ft20">Know your Rights</p>
<p style="position:absolute;top:592px;left:51px;white-space:nowrap" class="ft21">You may visit Rental Dispute Center website through&#160;www.dubailand.gov.ae&#160;in case of any&#160;</p>
<p style="position:absolute;top:604px;left:51px;white-space:nowrap" class="ft21">rental dispute between parties.</p>
<p style="position:absolute;top:628px;left:51px;white-space:nowrap" class="ft21">Law No 26 of 2007 regulating relationship between landlords and tenants.</p>
<p style="position:absolute;top:652px;left:51px;white-space:nowrap" class="ft21">Law No 33 of 2008 amending law 26 of year 2007.</p>
<p style="position:absolute;top:676px;left:51px;white-space:nowrap" class="ft21">Law No 43 of 2013 determining rent increases for properties.</p>
<p style="position:absolute;top:745px;left:36px;white-space:nowrap" class="ft21">1. Original unified tenancy contract</p>
<p style="position:absolute;top:772px;left:36px;white-space:nowrap" class="ft21">2. Original emirates ID of applicant</p>
<p style="position:absolute;top:593px;left:466px;white-space:nowrap" class="ft21">ءﻮﺸﻧ لﺎﺣ ﻲﻓ &#160;www.dubailand.gov.ae&#160;لﻼﺧ ﻦﻣ ﺔﻳرﺎﺠﻳﻹا تﺎﻋزﺎﻨﻤﻟا ﺾﻓ ﺰﻛﺮﻣ ﻊﻗﻮﻣ ةرﺎﻳز ﻢﻜﻨﻜﻤﻳ</p>
<p style="position:absolute;top:606px;left:736px;white-space:nowrap" class="ft21">.فاﺮﻃﻷا ﻦﻴﺑ يرﺎﺠﻳإ عاﺰﻧ يأ</p>
<p style="position:absolute;top:627px;left:509px;white-space:nowrap" class="ft21">.</p>
<p style="position:absolute;top:627px;left:511px;white-space:nowrap" class="ft21">ﻦﻳﺮﺟﺄﺘﺴﻤﻟاو ﻦﻳﺮﺟﺆﻤﻟا ﻦﻴﺑ ﺔﻗﻼﻌﻟا ﻢﻴﻈﻨﺗ نﺄﺸﺑ&#160;2007&#160;ﺔﻨﺴﻟ&#160;26&#160;ﻢﻗر نﻮﻧﺎﻗ ﻰﻠﻋ عﻼﻃﻹا</p>
<p style="position:absolute;top:650px;left:512px;white-space:nowrap" class="ft21">.2007</p>
<p style="position:absolute;top:649px;left:532px;white-space:nowrap" class="ft21">&#160;مﺎﻌﻟ&#160;26&#160;نﻮﻧﺎﻗ مﺎﻜﺣأ ﺾﻌﺑ ﻞﻳﺪﻌﺘﺑ صﺎﺨﻟا&#160;2008&#160;ﺔﻨﺴﻟ&#160;33&#160;ﻢﻗر نﻮﻧﺎﻗ ﻰﻠﻋ عﻼﻃﻹا</p>
<p style="position:absolute;top:672px;left:580px;white-space:nowrap" class="ft21">.</p>
<p style="position:absolute;top:672px;left:582px;white-space:nowrap" class="ft21">رﺎﺠﻳﻹا لﺪﺑ ةدﺎﻳز ﺪﻳﺪﺤﺗ نﺄﺸﺑ&#160;2013&#160;ﺔﻨﺴﻟ&#160;43&#160;ﻢﻗر نﻮﻧﺎﻗ ﻰﻠﻋ عﻼﻃﻹا</p>
<p style="position:absolute;top:746px;left:728px;white-space:nowrap" class="ft21">ﺪﺣﻮﻤﻟا رﺎﺠﻳﻻا ﺪﻘﻋ ﻦﻋ ﺔﻴﻠﺻأ ﺔﺨﺴﻧ&#160;.1 </p>
<p style="position:absolute;top:773px;left:713px;white-space:nowrap" class="ft21">ﺐﻠﻄﻟا مﺪﻘﻤﻟ ﺔﻴﻠﺻﻷا ﺔﻴﺗارﺎﻣﻹا ﺔﻳﻮﻬﻟا&#160;.2</p>
<p style="position:absolute;top:1006px;left:24px;white-space:nowrap" class="ft25">Note :&#160;You may add addendum to this tenancy contract in case you have additional terms&#160;<br/>while it needs to be signed by all parties.</p>
<p style="position:absolute;top:1010px;left:509px;white-space:nowrap" class="ft21">ﻦﻣ ﻊﻗﻮﻳ نأ ﻰﻠﻋ ،ﺔﻴﻓﺎﺿإ طوﺮﺷ يأ دﻮﺟو لﺎﺣ ﻲﻓ ﺪﻘﻌﻟا اﺬﻫ ﻰﻟإ ﻖﺤﻠﻣ ﺔﻓﺎﺿإ ﻦﻜﻤﻳ&#160;:ﺔﻈﺣﻼﻣ</p>
<p style="position:absolute;top:1025px;left:811px;white-space:nowrap" class="ft21">.ﺪﻗﺎﻌﺘﻟا فاﺮﻃأ&#160;</p>
<p style="position:absolute;top:558px;left:713px;white-space:nowrap" class="ft22"><b>فاﺮﻃﻷا قﻮﻘﺣ ﺔﻓﺮﻌﻤﻟ</b></p>
<p style="position:absolute;top:712px;left:677px;white-space:nowrap" class="ft22"><b>يرﺎﺠﻳإ ﻲﻓ ﻞﻴﺠﺴﺘﻟا تﺎﻘﻓﺮﻣ</b></p>
<p style="position:absolute;top:803px;left:770px;white-space:nowrap" class="ft22"><b>ﺔﻴﻓﺎﺿإ طوﺮﺷ</b></p>
<p style="position:absolute;top:1068px;left:38px;white-space:nowrap" class="ft20">Signatures</p>
<p style="position:absolute;top:1069px;left:796px;white-space:nowrap" class="ft22"><b>تﺎﻌﻴﻗﻮﺘﻟا</b></p>
<p style="position:absolute;top:1174px;left:239px;white-space:nowrap" class="ft23">Date &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo getDisplayFormatDate($tenancy_contractDetails[0]['create_date'])?></p>
<p style="position:absolute;top:1173px;left:375px;white-space:nowrap" class="ft23">ﺦﻳرﺎﺘﻟا</p>
<p style="position:absolute;top:1174px;left:32px;white-space:nowrap" class="ft23">Tenant’s Signature&#160;ﺮﺟﺄﺘﺴﻤﻟا ﻊﻴﻗﻮﺗ</p>
<p style="position:absolute;top:1174px;left:696px;white-space:nowrap" class="ft23">Date &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<?php echo getDisplayFormatDate($tenancy_contractDetails[0]['create_date'])?></p>
<p style="position:absolute;top:1173px;left:832px;white-space:nowrap" class="ft23">ﺦﻳرﺎﺘﻟا</p>
<p style="position:absolute;top:1174px;left:487px;white-space:nowrap" class="ft23">Lessor’s Signature&#160;ﺮﺟﺆﻤﻟا ﻊﻴﻗﻮﺗ</p>
<p style="position:absolute;top:66px;left:35px;white-space:nowrap" class="ft21">4.&#160;The tenant shall be responsible for payment of all electricity, water, cooling and gas charges&#160;</p>
<p style="position:absolute;top:78px;left:35px;white-space:nowrap" class="ft21">resulting of occupying leased unit unless other condition agreed in written.</p>
<p style="position:absolute;top:104px;left:35px;white-space:nowrap" class="ft21">5.&#160;The tenant must pay the rent amount in the manner and dates agreed with the landlord.</p>
<p style="position:absolute;top:129px;left:35px;white-space:nowrap" class="ft21">6.&#160;The tenant fully undertakes to comply with all the regulations and instructions related to the&#160;</p>
<p style="position:absolute;top:142px;left:35px;white-space:nowrap" class="ft21">management of the property and the use of the premises and of common areas such (parking,&#160;</p>
<p style="position:absolute;top:155px;left:35px;white-space:nowrap" class="ft21">swimming pools, gymnasium, etc…).</p>
<p style="position:absolute;top:180px;left:35px;white-space:nowrap" class="ft21">7.&#160;Tenancy contract parties declare all mentioned emails addresses and phone numbers are&#160;</p>
<p style="position:absolute;top:193px;left:35px;white-space:nowrap" class="ft21">correct, all formal and legal notifications will be sent to those addresses in case of dispute&#160;</p>
<p style="position:absolute;top:206px;left:35px;white-space:nowrap" class="ft21">between parties.</p>
<p style="position:absolute;top:231px;left:35px;white-space:nowrap" class="ft21">8.&#160;The landlord undertakes to enable the tenant of the full use of the premises including its&#160;</p>
<p style="position:absolute;top:244px;left:35px;white-space:nowrap" class="ft21">facilities (swimming pool, gym, parking lot, etc) and do the regular maintenance as intended&#160;</p>
<p style="position:absolute;top:257px;left:35px;white-space:nowrap" class="ft21">unless other condition agreed in written, and not to do any act that would detract from the&#160;</p>
<p style="position:absolute;top:270px;left:35px;white-space:nowrap" class="ft21">premises benefit.</p>
<p style="position:absolute;top:295px;left:35px;white-space:nowrap" class="ft21">9. By signing this agreement from the first party, the “Landlord” hereby confirms and undertakes&#160;</p>
<p style="position:absolute;top:308px;left:35px;white-space:nowrap" class="ft21">that he is the current owner of the property or his legal representative under legal power of&#160;</p>
<p style="position:absolute;top:321px;left:35px;white-space:nowrap" class="ft21">attorney duly entitled by the competent authorities.</p>
<p style="position:absolute;top:346px;left:35px;white-space:nowrap" class="ft21">10.&#160;Any disagreement or dispute may arise from execution or interpretation of this contract shall&#160;</p>
<p style="position:absolute;top:359px;left:35px;white-space:nowrap" class="ft21">be settled by the Rental Dispute Center.</p>
<p style="position:absolute;top:384px;left:35px;white-space:nowrap" class="ft21">11.&#160;This contract is subject to all provisions of Law No (26) of 2007 regulating the relation&#160;</p>
<p style="position:absolute;top:397px;left:35px;white-space:nowrap" class="ft21">between landlords and tenants in the emirate of Dubai as amended, and as it will be changed or&#160;</p>
<p style="position:absolute;top:410px;left:35px;white-space:nowrap" class="ft21">amended from time to time, as long with any related legislations and regulations applied in the&#160;</p>
<p style="position:absolute;top:423px;left:35px;white-space:nowrap" class="ft21">emirate of Dubai.</p>
<p style="position:absolute;top:448px;left:35px;white-space:nowrap" class="ft21">12.&#160;Any additional condition will not be considered in case it conflicts with law.</p>
<p style="position:absolute;top:474px;left:35px;white-space:nowrap" class="ft21">13. In case of discrepancy occurs between&#160;Arabic and non&#160;Arabic texts with regards to the&#160;</p>
<p style="position:absolute;top:486px;left:35px;white-space:nowrap" class="ft21">interpretation of this agreement or the scope of its application, the&#160;Arabic text shall prevail.</p>
<p style="position:absolute;top:512px;left:35px;white-space:nowrap" class="ft21">14.&#160;The landlord undertakes to register this tenancy contract on EJARI affiliated to Dubai Land&#160;</p>
<p style="position:absolute;top:525px;left:35px;white-space:nowrap" class="ft21">Department and provide with all required documents.</p>
<p style="position:absolute;top:64px;left:468px;white-space:nowrap" class="ft21">ﻪﻟﺎﻐﺷا ﻦﻋ ﺔﺒﺗﺮﺘﻤﻟا زﺎﻐﻟا و ﺪﻳﺮﺒﺘﻟا و هﺎﻴﻤﻟا و ءﺎﺑﺮﻬﻜﻟا ﺮﻴﺗاﻮﻓ ﺔﻓﺎﻛ داﺪﺳ ﻦﻋ ﻻوﺆﺴﻣ ﺮﺟﺄﺘﺴﻤﻟا نﻮﻜﻳ&#160;.4</p>
<p style="position:absolute;top:79px;left:686px;white-space:nowrap" class="ft21">ﺎﻴﺑﺎﺘﻛ ﻚﻟذ ﺮﻴﻏ ﻰﻠﻋ قﺎﻔﺗﻻا ﻢﺘﻳ ﻢﻟﺎﻣ رﻮﺟﺄﻤﻟا&#160;</p>
<p style="position:absolute;top:112px;left:459px;white-space:nowrap" class="ft21">.ﺎﻬﻴﻠﻋ ﻖﻔﺘﻤﻟا ﺔﻘﻳﺮﻄﻟا و ﺦﻳراﻮﺘﻟا ﻲﻓ ﺪﻘﻌﻟا اﺬﻫ ﻲﻓ ﻪﻴﻠﻋ ﻖﻔﺘﻤﻟا رﺎﺠﻳﻻا ﻎﻠﺒﻣ داﺪﺴﺑ ﺮﺟﺄﺘﺴﻤﻟا ﺪﻬﻌﺘﻳ&#160;.5</p>
<p style="position:absolute;top:142px;left:480px;white-space:nowrap" class="ft21">ﺔﻛﺮﺘﺸﻤﻟا ﻊﻓﺎﻨﻤﻟا و رﻮﺟﺄﻤﻟا ماﺪﺨﺘﺳﺎﺑ ﺔﻘﻠﻌﺘﻤﻟا تﺎﻤﻴﻠﻌﺘﻟا و ﺔﻤﻈﻧﻻﺎﺑ مﺎﺘﻟا ﺪﻴﻘﺘﻟا ﺮﺟﺄﺘﺴﻤﻟا مﺰﺘﻠﻳ&#160;.6</p>
<p style="position:absolute;top:160px;left:641px;white-space:nowrap" class="ft21">.(ﺦﻟا ،ﻲﺤﺼﻟا يدﺎﻨﻟا ،ﺔﺣﺎﺒﺴﻟا ضاﻮﺣأ ،تارﺎﻴﺴﻟا ﻒﻗاﻮﻤﻛ)&#160;</p>
<p style="position:absolute;top:190px;left:453px;white-space:nowrap" class="ft21">ةﺪﻤﺘﻌﻤﻟا ﻲﻫ ﻦﻳوﺎﻨﻌﻟا ﻚﻠﺗ نﻮﻜﺗ و ،هﻼﻋأ ةرﻮﻛﺬﻤﻟا ﻒﺗاﻮﻬﻟا مﺎﻗرأ و ﻦﻳوﺎﻨﻌﻟا ﺔﺤﺼﺑ ﺪﻗﺎﻌﺘﻟا فاﺮﻃأ ﺮﻘﻳ&#160;.7</p>
<p style="position:absolute;top:207px;left:556px;white-space:nowrap" class="ft21">.ﺪﻘﻌﻟا فاﺮﻃأ ﻦﻴﺑ عاﺰﻧ يأ ءﻮﺸﻧ لﺎﺣ ﻲﻓ ﺔﻴﺋﺎﻀﻘﻟا تﺎﻧﻼﻋﻷا و تارﺎﻄﺧﻺﻟ ﺎﻴﻤﺳر&#160;</p>
<p style="position:absolute;top:237px;left:477px;white-space:nowrap" class="ft21">ﻪﺑ ﺔﺻﺎﺨﻟا ﻖﻓاﺮﻤﻟا و ﻪﻠﺟﻷ ﺮﺟﺆﻤﻟا ضﺮﻐﻠﻟ رﺎﻘﻌﻟﺎﺑ مﺎﺘﻟا عﺎﻔﺘﻧﻻا ﻦﻣ ﺮﺟﺄﺘﺴﻤﻟا ﻦﻴﻜﻤﺘﺑ ﺮﺟﺆﻤﻟا ﺪﻬﻌﺘﻳ&#160;.8</p>
<p style="position:absolute;top:253px;left:464px;white-space:nowrap" class="ft21">قﺎﻔﺗﻻا ﻢﺘﻳ ﻢﻟﺎﻣ ﺔﻧﺎﻴﺼﻟا لﺎﻤﻋأ ﻦﻋ ﻻوﺆﺴﻣ نﻮﻜﻳ ﺎﻤﻛ&#160;(ﺦﻟإ&#160;....تارﺎﻴﺳ ﻒﻗاﻮﻣ ،ﻲﺤﺻ يدﺎﻧ ،ﺔﺣﺎﺒﺳ ضﻮﺣ)&#160;</p>
<p style="position:absolute;top:270px;left:664px;white-space:nowrap" class="ft21">.رﺎﻘﻌﻟا ﺔﻌﻔﻨﻣ ﻲﻓ ﻪﻟ ضﺮﻌﺘﻟا مﺪﻋ و ،ﻚﻟذ ﺮﻴﻏ ﻰﻠﻋ&#160;</p>
<p style="position:absolute;top:300px;left:481px;white-space:nowrap" class="ft21">ﻚﻟﺎﻤﻠﻟ ﻲﻧﻮﻧﺎﻘﻟا ﻞﻴﻛﻮﻟا وأ رﺎﻘﻌﻠﻟ ﻲﻟﺎﺤﻟا ﻚﻟﺎﻤﻟا ﻪﻧﺄﺑ ﻪﻨﻣ راﺮﻗإ ﺪﻘﻌﻟا اﺬﻫ ﻰﻠﻋ ﺮﺟﺆﻤﻟا ﻊﻴﻗﻮﺗ ﺮﺒﺘﻌﻳ&#160;.9</p>
<p style="position:absolute;top:317px;left:613px;white-space:nowrap" class="ft21">.ﺔﺼﺘﺨﻤﻟا تﺎﻬﺠﻟا ىﺪﻟ لﻮﺻﻷا ﻖﻓو ﺔﻘﺛﻮﻣ ﺔﻴﻧﻮﻧﺎﻗ ﺔﻟﺎﻛو ﺐﺟﻮﻤﺑ</p>
<p style="position:absolute;top:349px;left:460px;white-space:nowrap" class="ft21">.ﺔﻳرﺎﺠﻳﻹا تﺎﻋزﺎﻨﻤﻟا ﺾﻓ ﺰﻛﺮﻤﻟ ﻪﻴﻓ ﺖﺒﻟا دﻮﻌﻳ ﺪﻘﻌﻟا اﺬﻫ ﺮﻴﺴﻔﺗ وأ ﺬﻴﻔﻨﺗ ﻦﻋ ﺄﺸﻨﻳ ﺪﻗ عاﺰﻧ وأ فﻼﺧ يأ&#160;.10</p>
<p style="position:absolute;top:379px;left:459px;white-space:nowrap" class="ft21">يﺮﺟﺄﺘﺴﻣ و يﺮﺟﺆﻣ ﻦﻴﺑ ﺔﻗﻼﻌﻟا ﻢﻴﻈﻨﺗ نﺄﺸﺑ&#160;2007&#160;ﺔﻨﺴﻟ&#160;(26)&#160;ﻢﻗر نﻮﻧﺎﻘﻟا مﺎﻜﺣأ ﺔﻓﺎﻜﻟ ﺪﻘﻌﻟا اﺬﻫ ﻊﻀﺨﻳ&#160;.11</p>
<p style="position:absolute;top:394px;left:498px;white-space:nowrap" class="ft21">&#160;ﻊﻀﺨﻳ ﺎﻤﻛ ،ﺮﺧﻵ ﺖﻗو ﻦﻣ ﻪﻴﻠﻋ أﺮﻄﻳ ﻞﻳﺪﻌﺗ وأ ﺮﻴﻴﻐﺗ يأ و ﻪﺗﻼﻳﺪﻌﺗ و ،ﻲﺑد ةارﺎﻣإ ﻲﻓ تارﺎﻘﻌﻟا&#160;</p>
<p style="position:absolute;top:412px;left:639px;white-space:nowrap" class="ft21">.ﻲﺑد ﻲﻓ ةﺬﻓﺎﻨﻟا ﺔﻗﻼﻌﻟا تاذ ىﺮﺧﻷا ﺢﺋاﻮﻠﻟا و تﺎﻌﻳﺮﺸﺘﻠﻟ</p>
<p style="position:absolute;top:443px;left:570px;white-space:nowrap" class="ft21">.نﻮﻧﺎﻘﻟا ﻊﻣ ﻪﺿرﺎﻌﺗ لﺎﺣ ﻲﻓ ﺪﻘﻌﻟا اﺬﻫ ﻰﻟإ ﻪﺘﻓﺎﺿإ ﻢﺗ طﺮﺷ يﺄﺑ ﺪﺘﻌﻳ ﻻ&#160;.12</p>
<p style="position:absolute;top:475px;left:458px;white-space:nowrap" class="ft21">.ﻲﺑﺮﻌﻟا ﺺﻨﻟا ﺪﻤﺘﻌﻳ ﻲﺒﻨﺟﻷا ﺺﻨﻟا ﻮﻴﺑﺮﻌﻟا ﺺﻨﻟا ﻦﻴﺑ ﺮﻴﺴﻔﺘﻟا ﻲﻓ فﻼﺘﺧا وأ ضرﺎﻌﺗ يأ ثوﺪﺣ لﺎﺣ ﻲﻓ&#160;.13</p>
<p style="position:absolute;top:505px;left:477px;white-space:nowrap" class="ft21">ﺔﻓﺎﻛ ﺮﻴﻓﻮﺗ و كﻼﻣﻷا و ﻲﺿارﻷا ةﺮﺋاﺪﻟ ﻊﺑﺎﺘﻟا يرﺎﺠﻳإ مﺎﻈﻧ ﻲﻓ رﺎﺠﻳﻻا ﺪﻘﻋ ﻞﻴﺠﺴﺘﺑ ﺮﺟﺆﻤﻟا ﺪﻬﻌﺘﻳ&#160;.14</p>
<p style="position:absolute;top:520px;left:761px;white-space:nowrap" class="ft21">&#160;.ﻚﻟﺬﻟ ﺔﻣزﻼﻟا تاﺪﻨﺘﺴﻤﻟا&#160;</p>
<p style="position:absolute;top:1231px;left:495px;white-space:nowrap" class="ft24">support@dubailand.gov.ae</p>
</div>
</body>
</html>
