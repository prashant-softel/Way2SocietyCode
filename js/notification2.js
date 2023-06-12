var bIsSmsValidationDone = false;
var prohibited = ['property', '&','#','$','%','^','&','*','(',')','!','@','_','+'];
var string = JSON.stringify(prohibited)
var newstring = string.replace (/"/g,'      ');

function get_unit(wing_id)
{
	if(wing_id == 0)
	{
		$('select#unit_id').empty();
		$('select#unit_id').append(
			$('<option></option>')
			.val('0')
			.html('All'));
	}
	else
	{
		document.getElementById('error').style.display = '';	
		document.getElementById('error').innerHTML = 'Fetching Units. Please Wait...';	
		populateDDListAndTrigger('select#unit_id', 'ajax/get_unit.php?getunit&wing_id=' + wing_id, 'unit', 'hide_error', true);
	}
}

function get_year()
{
	populateDDListAndTrigger('select#year_id', 'ajax/get_unit.php?getyear', 'year', 'get_period', false);
}

function get_period(year_id)
{
	document.getElementById('error').style.display = '';	
	document.getElementById('error').innerHTML = 'Fetching Period. Please Wait...';	
		
	if(year_id == null)
	{
		populateDDListAndTrigger('select#period_id', 'ajax/ajaxbill_period.php?getperiod&year=' + document.getElementById('year_id').value, 'period', 'periodFetched', false);
	}
	else
	{
		populateDDListAndTrigger('select#period_id', 'ajax/ajaxbill_period.php?getperiod&year=' + year_id, 'period', 'periodFetched', false);
	}
}

function periodFetched()
{
	hide_error();
	var periodID = document.getElementById('period_id').value;
}

function get_notes(periodid)
{
	var societyid = document.getElementById('society_id').value;

	var sURL = "ajax/ajaxgenbill.php";
	var obj = {'getnote':'getnote', 'society':societyid, 'period':periodid};
	remoteCallNew(sURL, obj, 'notefetched');
}

function notefetched()
{
	var sResponse = getResponse(RESPONSETYPE_STRING, true);
	//alert(sResponse);
	document.getElementById('bill_notes').value = sResponse;
}

function sendEmail(unitID)
{
	document.getElementById('status_' + unitID).innerHTML = 'Sending ...';
	
	var periodID = document.getElementById('period_id').value;
	var SentEmailManually = document.getElementById('SentEmailManually').value;
	var BillType = 0;
	if(document.getElementById('Supplementary_Bills').checked)
	{
		BillType = 1;
	}
	
	$.ajax({
			url : "classes/email.class.php",
			type : "POST",
			data: { "unit":unitID, "period":periodID, "SentEmailManually":SentEmailManually, "BT": BillType} ,
			success : function(data)
			{	
				var unitsAry = JSON.parse(data);
				for(var objUnit in unitsAry)
				{
					document.getElementById('status_'+objUnit).innerHTML = unitsAry[objUnit] ;
				}
			},
				
			fail: function()
			{
				
			},
			
			error: function(XMLHttpRequest, textStatus, errorThrown) 
			{
			}
		});
}

function sendEMailAll(UnitsArray)
{
	var periodID = document.getElementById('period_id').value;
	var SentEmailManually = document.getElementById('SentEmailManually').value;
	var BillType = 0;
	if(document.getElementById('Supplementary_Bills').checked)
	{
		BillType = 1;
	}
	
	$.ajax({
			url : "classes/email.class.php",
			type : "POST",
			data: { "unitsArray":JSON.stringify(UnitsArray), "period":periodID, "SentEmailManually":SentEmailManually, "BT": BillType} ,
			success : function(data)
			{	
				var unitsAry = JSON.parse(data);
				for(var objUnit in unitsAry)
				{
					document.getElementById('status_'+objUnit).innerHTML = unitsAry[objUnit] ;
				}
			},
				
			fail: function()
			{
				alert("failed");
			},
			
			error: function(XMLHttpRequest, textStatus, errorThrown) 
			{
			}
		});

}

function sendSMS(unitID)
{
	document.getElementById('status_' + unitID).innerHTML = 'Sending ...';
	var periodID = document.getElementById('period_id').value;
	var UnitsArray = "";
	//alert(unitID);
	UnitsArray = UnitsArray.concat(unitID).split('#');
	var SentSMSManually =  1;
	var BillType = 0;
	if(document.getElementById('Supplementary_Bills').checked)
	{
		BillType = 1;
	}
	$.ajax({
			url : "classes/sms2.class.php",
			type : "POST",
			data: {"period":periodID, "unitsArray":JSON.stringify(UnitsArray),"SentSMSManually":SentSMSManually, "BT": BillType} ,
			success : function(data)
			{
				//alert(data);	
				var unitsAry = JSON.parse(data);
				for(var objUnit in unitsAry)
				{
					//document.getElementById('status_'+objUnit).innerHTML = unitsAry[objUnit] ;				
					//alert("Unit:"+objUnit);
					if(unitsAry[objUnit].trim() == "Empty")
					{
						document.getElementById('status_' + objUnit).innerHTML = 'Invalid Mobile number.';
					}
					else if(unitsAry[objUnit].trim() == "Missing Parameters")
					{
						document.getElementById('status_' + objUnit).innerHTML = 'Error.';
					}
					else
					{					
						document.getElementById('status_' + objUnit).innerHTML = "Sent";
					}
					var sTarget = "sendsms_" + objUnit;
					window.open(unitsAry[objUnit], sTarget, "toolbar=no, scrollbars=yes, resizable=no, top=0, left=0, width=0, height=0");
				}
				//window.open(data, "_blank", "toolbar=no, scrollbars=yes, resizable=yes, top=0, left=0");
				
			},
				
			fail: function()
			{
				
			},
			
			error: function(XMLHttpRequest, textStatus, errorThrown) 
			{
			}
		});
}

function sendGeneralSMS(UnitID)
{
	var MsgBody = document.getElementById('description').value.trim();
	var bMsgContainsInvalidCharacters = bIsSmsContainsInvalidText(MsgBody); 
	
	if(MsgBody == "")
	{
		document.getElementById('error').innerHTML = "Please Enter some message to send.";
		return;	
	}
	else if(MsgBody != "" && bMsgContainsInvalidCharacters == true && bIsSmsValidationDone == false)
	{
		document.getElementById('error').innerHTML = "<img src='images/del.gif' /> " + newstring + "  All these words/characters are not allowed in sms.";
		//$('#error').prepend('<img  src="images/del.gif" />');
		window.scroll(0, 0);
		return;	
	}
	else
	{
		document.getElementById('error').innerHTML = "";
	}
	//document.getElementById('status_' + UnitID).innerHTML = 'Sending ...';	
	var UnitsArray = "";
	//alert(UnitID);
	UnitsArray = UnitsArray.concat(UnitID).split('#');
	var SentSMSManually =  1;
	
	$.ajax({
			url : "classes/generalSms.class.php",
			type : "POST",
			data: { "unitsArray":JSON.stringify(UnitsArray), "msgBody":MsgBody,"SentSMSManually":SentSMSManually} ,
			success : function(data)
			{
				//alert(data);					
				var unitsAry = JSON.parse(data);
				for(var objUnit in unitsAry)
				{
					//document.getElementById('status_'+objUnit).innerHTML = unitsAry[objUnit] ;				
					//alert("Unit:"+objUnit);
					if(unitsAry[objUnit].trim() == "Empty")
					{
						document.getElementById('status_' + objUnit).innerHTML = 'Invalid Mobile number.';
					}
					else if(unitsAry[objUnit].trim() == "Missing Parameters")
					{
						document.getElementById('status_' + objUnit).innerHTML = 'Error.';
					}
					else
					{					
						document.getElementById('status_' + objUnit).innerHTML = "Sent";
					}
				}
					//var sTarget = "sendsms_" + objUnit;
					//window.open(unitsAry[objUnit], sTarget, "toolbar=no, scrollbars=yes, resizable=no, top=0, left=0, width=0, height=0");
				//window.open(data, sTarget, "toolbar=no, scrollbars=yes, resizable=no, top=0, left=0, width=0, height=0");												
			},
				
			fail: function()
			{
				
			},
			
			error: function(XMLHttpRequest, textStatus, errorThrown) 
			{
			}
		});	
}

function SelectAll(chkBox)
{
	//alert("Select All" + chkBox.checked);
	var unitAry = document.getElementById('unit_ary').value.split('#');
	for(var iCnt = 0 ; iCnt < unitAry.length - 1 ; iCnt++)
	{
		document.getElementById('chk_' + unitAry[iCnt]).checked = chkBox.checked;
	}
}

function EMailSentAll()
{
	var unitAry = document.getElementById('unit_ary').value.split('#');
	var unitAry2 = "";
	
	for(var iCnt = 0 ; iCnt < unitAry.length - 1 ; iCnt++)
	{
		if(document.getElementById('chk_' + unitAry[iCnt]).checked == true)
		{
			
			if(unitAry2 != 'undefined')
			{
				unitAry2 = unitAry2.concat(unitAry[iCnt] + "#");
				
				document.getElementById('status_' + unitAry[iCnt]).innerHTML = 'Sending ...';
			}
			else
			{
				unitAry2 =  unitAry[iCnt] + "#";
				
				document.getElementById('status_' + unitAry[iCnt]).innerHTML = 'Sending ...';
			}
		}
	}
	sendEMailAll(unitAry2.split('#'));
}

function SMSSentAll()
{
	var unitAry = document.getElementById('unit_ary').value.split('#');
	var unitAry2 = "";
	for(var iCnt = 0 ; iCnt < unitAry.length - 1 ; iCnt++)
	{
		if(document.getElementById('chk_' + unitAry[iCnt]).checked == true)
		{
			if(unitAry2 != 'undefined')
			{
				unitAry2 = unitAry2.concat(unitAry[iCnt] + "#");
				
				document.getElementById('status_' + unitAry[iCnt]).innerHTML = 'Sending ...';
			}
			else
			{
				unitAry2 =  unitAry[iCnt] + "#";
				
				document.getElementById('status_' + unitAry[iCnt]).innerHTML = 'Sending ...';
			}
		}
	}
	alert(unitAry2);
	sendSMS(unitAry2);
}

function SMSSent()
{
	var response = getResponse(RESPONSETYPE_STRING, true);
	alert(response);
}

function GeneralSMSSentAll()
{	
	var MsgBody = document.getElementById('description').value.trim();
	var bMsgContainsInvalidCharacters = bIsSmsContainsInvalidText(MsgBody); 
	
	if(MsgBody == "")
	{
		document.getElementById('error').innerHTML = "Please Enter some message to send.";
		return;	
	}
	else if(MsgBody != "" && bMsgContainsInvalidCharacters == true)
	{
		document.getElementById('error').innerHTML = "<img src='images/del.gif' />  " + newstring + "  All these words/characters are not allowed in sms.";
		window.scroll(0, 0);
		return;	
	}
	else
	{
		document.getElementById('error').innerHTML = "";
	}
	bIsSmsValidationDone = true;
	var unitAry2 = "";
	var unitAry = document.getElementById('unit_ary').value.split('#');
	for(var iCnt = 0 ; iCnt < unitAry.length - 1 ; iCnt++)
	{
		if(document.getElementById('chk_' + unitAry[iCnt]).checked == true)
		{
			
			if(unitAry2 != 'undefined')
			{
				unitAry2 = unitAry2.concat(unitAry[iCnt] + "#");
				
				document.getElementById('status_' + unitAry[iCnt]).innerHTML = 'Sending ...';
			}
			else
			{
				unitAry2 =  unitAry[iCnt] + "#";
				
				document.getElementById('status_' + unitAry[iCnt]).innerHTML = 'Sending ...';
			}
		}
	}
	
					
	sendGeneralSMS(unitAry2);
	bIsSmsValidationDone = false;
	
}

function bIsSmsContainsInvalidText(smsText)
 {
 
 	  var smsText = smsText.toLowerCase(); 	
	  for (var i = 0; i < prohibited.length; i++) 
	  {
			if(smsText.includes(prohibited[i]) == true)
			{
				return true;	
			}
			
	  }
  return false;
}
