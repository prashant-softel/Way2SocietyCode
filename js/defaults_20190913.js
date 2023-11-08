function get_year()
{
	populateDDListAndTrigger('select#year_id', 'ajax/get_unit.php?getyear', 'year', 'get_period', false);
}

function get_period(year_id)
{
	document.getElementById('error').innerHTML = 'Fetching Period. Please Wait...';
	if(year_id == null)
	{
		populateDDListAndTrigger('select#default_period', 'ajax/get_unit.php?getperiod&year=' + document.getElementById('year_id').value, 'period', 'hide_error', false);
	}
	else
	{
		populateDDListAndTrigger('select#default_period', 'ajax/get_unit.php?getperiod&year=' + year_id, 'period', 'hide_error', false);
	}
}
 
/* function validator */



function disableAllCounter()
{
	$("#VoucherCounter").find("input,button,textarea,select").attr("disabled", "disabled").css("background-color", "#d1d1d1");
	$("#bankledgers").find("input,button,textarea,select").attr("disabled", "disabled").css("background-color", "#d1d1d1");
	$("#CommonCounter").find("input,button,textarea,select").attr("disabled", "disabled").css("background-color", "#d1d1d1");
}


function ShowCommonCounter()
{
	var SizeOfLedger = document.getElementById('LedgerSize').value;
	var IsBankCountChk = document.getElementById('BankCounter').checked;
	var Cnt = 1;
	if(IsBankCountChk == true)
	{
		document.getElementById('bankledgers').style.display = "none";
		document.getElementById('CommonCounter').style.display = "block";
	}
	if(IsBankCountChk == false)
	{
		document.getElementById('bankledgers').style.display = "block";
		document.getElementById('CommonCounter').style.display = "none";
	}
}

function CheckCounterData(YearID)
{
	$.ajax({
				url: 'ajax/defaults.ajax.php',
        		type: 'POST',
        		data: {"YearID": YearID, "method":"CheckExitingData"},
        		success: function(data)
				{	
					ChangeVoucerCounter(YearID,data);
				}
		  })
}



function ChangeVoucerCounter(ChangeYearID,DataExits)
{
	var SizeOfLedger = document.getElementById('LedgerSize').value;
	var SizeOfCashLedger = document.getElementById('SizeOfCashLedger').value;
	if(ChangeYearID != 0 && ChangeYearID != '')
	{
		if(DataExits == 0)
			{	
				document.getElementById('JVstart').value =  1;
				document.getElementById('JVcurrent').value =  1;
				document.getElementById('InvoiceStart').value =  1;
				document.getElementById('InvoiceCurrent').value =  1;
				document.getElementById('DebitNotestart').value =  1;
				document.getElementById('DebitNotecurrent').value =  1;
				document.getElementById('CreditNotestart').value =  1;
				document.getElementById('CreditNotecurrent').value =  1;
				
				for(var i = 0 ; i < SizeOfCashLedger ; i++)
				{
					document.getElementById('CashPaystart'+i).value = 1;
					document.getElementById('CashPaycurrent'+i).value = 1;
					document.getElementById('CashReceivestart'+i).value = 1;
					document.getElementById('CashReceivedcurrent'+i).value = 1;	
				}
				
				for(var k=0 ; k < SizeOfLedger ; k++)
				{	
					document.getElementById('LedgerStartRcpValue'+k).value = 1;
					document.getElementById('LedgerCurrentRcpValue'+k).value = 1;
					document.getElementById('LedgerStartPayValue'+k).value = 1;
	 				document.getElementById('LedgerCurrentPayValue'+k).value = 1;
				}
			}
		else
			{
			$.ajax({
			url: 'ajax/defaults.ajax.php',
        	type: 'POST',
        	data: {"ChangeYearID": ChangeYearID, "method":"FetchCounterDetails"},
        	success: function(data){
				
				var LedgerID = new Array();
				
				var newary = [];
				
				var newary = JSON.parse("["+data+"]");
				
				console.log(newary);
		
				var datalength = newary.length;
				
				for(var i =0 ; i<newary.length ; i++)
				{
					for(var j=0;j<newary[i].length;j++)
	 				{
						if(newary[i][j].VoucherType == document.getElementById('JVVoucherType').value && newary[i][j].LedgerID == 0)
						{
							document.getElementById('JVstart').value =  newary[i][j].StartCounter;
							document.getElementById('JVcurrent').value =  newary[i][j].CurrentCounter;
						}
						if(newary[i][j].VoucherType == document.getElementById('InvoiceVoucherType').value && newary[i][j].LedgerID == 0)
						{
							document.getElementById('InvoiceStart').value =  newary[i][j].StartCounter;
							document.getElementById('InvoiceCurrent').value = newary[i][j].CurrentCounter;
						}
						
						if(newary[i][j].VoucherType == document.getElementById('DebitNoteVoucher').value && newary[i][j].LedgerID == 0)
						{
							document.getElementById('DebitNotestart').value =  newary[i][j].StartCounter;
							document.getElementById('DebitNotecurrent').value = newary[i][j].CurrentCounter;
						}
						
						if(newary[i][j].VoucherType == document.getElementById('CreditNoteVoucher').value && newary[i][j].LedgerID == 0)
						{
							document.getElementById('CreditNotestart').value =  newary[i][j].StartCounter;
							document.getElementById('CreditNotecurrent').value = newary[i][j].CurrentCounter;
						}
						
						if(newary[i][j].LedgerID != 0)
						{
							for(var x = 0 ; x < SizeOfCashLedger; x++)
							{
								var CashLedgerID = document.getElementById('CashLedgerID'+x).value;
								
								if(newary[i][j].VoucherType == document.getElementById('CashPayVoucherType').value && newary[i][j].LedgerID == CashLedgerID)
								{
									document.getElementById('CashPaystart'+x).value =  newary[i][j].StartCounter;
									document.getElementById('CashPaycurrent'+x).value =  newary[i][j].CurrentCounter;
								}
								if(newary[i][j].VoucherType == document.getElementById('CashReceiveVoucherType').value && newary[i][j].LedgerID == CashLedgerID)
								{
									document.getElementById('CashReceivestart'+x).value =  newary[i][j].StartCounter;
									document.getElementById('CashReceivedcurrent'+x).value =  newary[i][j].CurrentCounter;
								}
						     }
							
							for(var k=0 ; k < SizeOfLedger ; k++)
							{
								var LedgerID = document.getElementById('LedgerID'+k).value;
								if(newary[i][j].LedgerID == LedgerID)
								{
									if(newary[i][j].VoucherType == document.getElementById('BankRcpVoucherType').value)
									{
										document.getElementById('LedgerStartRcpValue'+k).value =  newary[i][j].StartCounter;
										document.getElementById('LedgerCurrentRcpValue'+k).value =  newary[i][j].CurrentCounter;
									}
									else if(newary[i][j].VoucherType == document.getElementById('BankPayVoucherType').value)
									{		
										document.getElementById('LedgerStartPayValue'+k).value =  newary[i][j].StartCounter;
										document.getElementById('LedgerCurrentPayValue'+k).value =  newary[i][j].CurrentCounter;
									}
								}
							}					
						}
	 				}	
				}
			}
	     });
	   }				
	}
}

function UpdateCounter()
{
	var obj = [];
	var arDataToSubmit = [];
	var bankCountChk = 0;
	
	var defaultYear = document.getElementById('default_year').value;
	var JVStart = document.getElementById('JVstart').value;
	var JVCurent = document.getElementById('JVcurrent').value;
	var JVVoucherType = document.getElementById('JVVoucherType').value;
	
    obj = {'VoucherType' : JVVoucherType , 'LedgerID': 0 , 'StartCnt' : JVStart , 'CurrentCnt' : JVCurent};
	arDataToSubmit.push(obj);
	
	var InvoiceStart = document.getElementById('InvoiceStart').value;
	var InvoiceCurrent = document.getElementById('InvoiceCurrent').value;
	var InvoiceVoucherType = document.getElementById('InvoiceVoucherType').value;
	
    obj = {'VoucherType' : InvoiceVoucherType , 'LedgerID': 0 , 'StartCnt' : InvoiceStart , 'CurrentCnt' : InvoiceCurrent};
	arDataToSubmit.push(obj);
	
	var DebitNotestart = document.getElementById('DebitNotestart').value;
	var DebitNotecurrent = document.getElementById('DebitNotecurrent').value;
	var DebitNoteVoucher = document.getElementById('DebitNoteVoucher').value;
	
    obj = {'VoucherType' : DebitNoteVoucher , 'LedgerID': 0 , 'StartCnt' : DebitNotestart , 'CurrentCnt' : DebitNotecurrent};
	arDataToSubmit.push(obj);
	
	var CreditNotestart = document.getElementById('CreditNotestart').value;
	var CreditNotecurrent = document.getElementById('CreditNotecurrent').value;
	var CreditNoteVoucher = document.getElementById('CreditNoteVoucher').value;
	
    obj = {'VoucherType' : CreditNoteVoucher , 'LedgerID': 0 , 'StartCnt' : CreditNotestart , 'CurrentCnt' : CreditNotecurrent};
	arDataToSubmit.push(obj);
	
	
	var SizeOfCashLedger = document.getElementById('SizeOfCashLedger').value;
	
	for(var i = 0 ; i < SizeOfCashLedger ; i++)
	{
		var CashLedgerID = document.getElementById('CashLedgerID'+i).value;
		var CashPaystart = document.getElementById('CashPaystart'+i).value;
		var CashPaycurrent = document.getElementById('CashPaycurrent'+i).value;
		var CashPayVoucherType = document.getElementById('CashPayVoucherType').value;
		
		obj = {'VoucherType' : CashPayVoucherType , 'LedgerID': CashLedgerID , 'StartCnt' : CashPaystart , 'CurrentCnt' : CashPaycurrent};
		arDataToSubmit.push(obj);
		
		var CashReceivestart = document.getElementById('CashReceivestart'+i).value;
		var CashReceivedcurrent = document.getElementById('CashReceivedcurrent'+i).value;
		var CashReceiveVoucherType = document.getElementById('CashReceiveVoucherType').value;
		
		obj = {'VoucherType' : CashReceiveVoucherType , 'LedgerID': CashLedgerID, 'StartCnt' : CashReceivestart , 'CurrentCnt' : CashReceivedcurrent};
		arDataToSubmit.push(obj);	
	
	}
	
	
	var SizeOfLedger = document.getElementById('LedgerSize').value;
	var BankRcpVoucherType = document.getElementById('BankRcpVoucherType').value;
	var BankPayVoucherType = document.getElementById('BankPayVoucherType').value;
	var IsbankCounterChk = document.getElementById('BankCounter').checked;
	
	if(IsbankCounterChk == false)
	{
		for(var i=0 ; i < SizeOfLedger ; i++)
		{
			var Lid = document.getElementById('LedgerID'+i).value;
			var LRcpStart = document.getElementById('LedgerStartRcpValue'+i).value;
			var LRcpCurrent = document.getElementById('LedgerCurrentRcpValue'+i).value;
			
			var LPayStart = document.getElementById('LedgerStartPayValue'+i).value;
			var LPayCurrent = document.getElementById('LedgerCurrentPayValue'+i).value;
				
			obj = {'VoucherType' : BankRcpVoucherType , 'LedgerID': Lid , 'StartCnt' : LRcpStart , 'CurrentCnt' : LRcpCurrent};
			arDataToSubmit.push(obj);
			
			obj = {'VoucherType' : BankPayVoucherType , 'LedgerID': Lid , 'StartCnt' : LPayStart , 'CurrentCnt' : LPayCurrent};
			arDataToSubmit.push(obj);		
		}
	}
	else
	{
		bankCountChk = 1;
		
		var SingleVcrCntRcptSrt = document.getElementById('SingleVcrCntRcptSrt').value;
		var SingleVcrCntRcptCrt = document.getElementById('SingleVcrCntRcptCrt').value;
		var SingleBnkRcptVoucher = document.getElementById('SingleBnkRcptVoucher').value;
		
		obj = {'VoucherType' : SingleBnkRcptVoucher , 'LedgerID': 0 , 'StartCnt' : SingleVcrCntRcptSrt , 'CurrentCnt' : SingleVcrCntRcptCrt};
		arDataToSubmit.push(obj);
		
		var SingleVcrCntPaySrt = document.getElementById('SingleVcrCntPaySrt').value;
		var SingleVcrCntPayCrt = document.getElementById('SingleVcrCntPayCrt').value;
		var SingleBnkPayVoucher = document.getElementById('SingleBnkPayVoucher').value;
		
		obj = {'VoucherType' : SingleBnkPayVoucher , 'LedgerID': 0 , 'StartCnt' : SingleVcrCntPaySrt , 'CurrentCnt' : SingleVcrCntPayCrt};
		arDataToSubmit.push(obj);	
	}		
	
		var sURL = "ajax/defaults.ajax.php";
		var obj = {"method":"updateCounter", 'defaultYear':defaultYear, 'IsbankCounterChk' : bankCountChk,'Counterdata' : JSON.stringify(arDataToSubmit)};
		
		//remoteCallNew(sURL, obj, 'defaultsApplied');
		remoteCallNew(sURL, obj, "ResponseOnCounter");
	
}

function ResponseOnCounter()
{
	var sResponse = getResponse(RESPONSETYPE_STRING, true);
	document.getElementById('error').innerHTML = sResponse;
	window.location.href = "voucherCounter.php";
}
function ApplyValues()
{

	
	document.getElementById('error').style.display = 'block';
	document.getElementById('error').innerHTML = 'Saving defaults. Please wait...';

	var defaultYear = document.getElementById('default_year').value;
	var defaultPeriod = 0;//document.getElementById('default_period').value;
	var interestOnPrinciple = document.getElementById('default_interest_on_principle').value;
	if(interestOnPrinciple  == "0" || interestOnPrinciple  == "")
	{
		window.alert("Please select Interest On Principle Due option");
			return false;
	}
	var penaltyToMember = document.getElementById('default_penalty_to_member').value;
	if(penaltyToMember == "0" || penaltyToMember == "")
	{
		window.alert("Please select Cheque Return Penalty option");
		return false;
	}
	var bankCharges = document.getElementById('default_bank_charges').value;
	if(bankCharges == "0" || bankCharges == "")
	{
		window.alert(" Please selectBank Charges option");
		return false;
	}
	var tdsPayable = document.getElementById('default_tds_payable').value;
	if(tdsPayable  == "0" || tdsPayable  == "")
	{
		window.alert("Please select TDS Payable option");
		return false;
	}
	var tdsReceivable = document.getElementById('default_tds_receivable').value;
	
	var imposeFine = document.getElementById('default_impose_fine').value;
	if(imposeFine == "0" || imposeFine == "")
	{
		window.alert("Please select Impose Fine option");
		return false;
	}
	var currentAsset = document.getElementById('default_current_asset').value;
	if(currentAsset  == "0" || currentAsset  == "")
	{
		window.alert("Please select Current Asset option");
		return false;
	}
	var fixedAsset = document.getElementById('default_fixed_asset').value;
	if(fixedAsset   == "0" || fixedAsset  == "")
	{
		window.alert("Please select Fixed Asset option");
		return false;
	}
	var bankAccount = document.getElementById('default_bank_account').value;
	if(bankAccount  == "0" || bankAccount  == "")
	{
		window.alert("Please select Bank Account option");
		return false;
	}
	var cashAccount = document.getElementById('default_cash_account').value;	
	if(cashAccount  == "0" || cashAccount  == "")
	{
		window.alert("Please select Cash Account option");
		return false;
	}
	var dueFromMember = document.getElementById('default_due_from_member').value;
	if(dueFromMember  == "0" || dueFromMember  == "")
	{
		window.alert("Please select Dues From Members option");
		return false;
	}
	
	var contributionfrommember = document.getElementById('default_contribution_from_member').value;
	if(contributionfrommember  == "0" || contributionfrommember  == "")
	{
		window.alert("Please select Contribution From Members option");
		return false;
	}
	var Sundrydebtor = document.getElementById('default_Sundry_debtor').value;
	var defaultIncomeExpenditureAccount = document.getElementById('default_income_expenditure_account').value;	
	if( defaultIncomeExpenditureAccount  == "0" || defaultIncomeExpenditureAccount  == "")
	{
		window.alert("Please select Income & Expenditure A/C option");
		return false;
	}		
	var defaultSociety = document.getElementById('default_society').value;
	var defaultAdjustmentCredit = document.getElementById('default_adjustment_credit').value;
	if(defaultAdjustmentCredit  == "0" || defaultAdjustmentCredit  == "")
	{
		window.alert("Please select Adjustment Credit option");
		return false;
	}
	var defaultSuspenseAccount = document.getElementById('default_suspense_account').value;
	if(defaultSuspenseAccount  == "0" || defaultSuspenseAccount  == "")
	{
		window.alert("Please select Suspense A/C option");
		return false;
	}
	var dueFromTenant = document.getElementById('default_due_from_tenant').value;
	var defaultLedgerRoundOff = document.getElementById('default_ledger_round_off').value;
	var igstServiceTax = document.getElementById('igst_service_tax').value;
	var cgstServiceTax = document.getElementById('cgst_service_tax').value;
	var sgstServiceTax = document.getElementById('sgst_service_tax').value;
	var cessServiceTax = document.getElementById('cess_service_tax').value;
	var sgstInput = document.getElementById('sgst_input').value;
	var cgstInput = document.getElementById('cgst_input').value;
	var igstInput = document.getElementById('igst_input').value;
	var Sundrycreditor = document.getElementById('default_Sundry_creditor').value;
	var defaultSinkingFund  =  document.getElementById('default_sinking_fund').value;
	var defaultInvestmentRegister =  document.getElementById('default_investment_register').value;
	var default_sd_category  =  document.getElementById('default_sd_category').value;
	var default_bank_id =  document.getElementById('default_bank_id').value;

	
	if(defaultSociety == 0 /*|| !EmailValidation*/)
	{
		if(defaultSociety == 0)
		{
			document.getElementById('error').innerHTML = 'Please select a Society';
		}
	}
	else
	{
		var sURL = "ajax/defaults.ajax.php";
		var obj = {'update':'', 'defaultYear':defaultYear, 
					'defaultPeriod':defaultPeriod, 
					'interestOnPrinciple':interestOnPrinciple,
					'penaltyToMember':penaltyToMember,
					'bankCharges':bankCharges,
					'tdsPayable':tdsPayable,
					'imposeFine':imposeFine,          // impose fine
					'currentAsset':currentAsset, 
					'fixedAsset':fixedAsset,
					'dueFromMember':dueFromMember,
					'contributionfrommember' : contributionfrommember,
					'Sundrydebtor':Sundrydebtor, 
					'bankAccount':bankAccount, 
					'cashAccount':cashAccount, 
					'societyid' : defaultSociety,
					'defaultIncomeExpenditureAccount' : defaultIncomeExpenditureAccount, 
					'defaultAdjustmentCredit' : defaultAdjustmentCredit,
					'defaultSuspenseAccount' : defaultSuspenseAccount,
					'defaultLedgerRoundOff' : defaultLedgerRoundOff,
					'igstServiceTax' : igstServiceTax,
					'cgstServiceTax' : cgstServiceTax,
					'sgstServiceTax' : sgstServiceTax,
					'cessServiceTax' : cessServiceTax,
					'sgstInput' : sgstInput,
					'cgstInput' : cgstInput,
					'igstInput' : igstInput,
					'tdsReceivable':tdsReceivable,
					'Sundrycreditor':Sundrycreditor, 
					'defaultSinkingFund'    : defaultSinkingFund,
					'defaultInvestmentRegister'    : defaultInvestmentRegister,
					'dueFromTenant': dueFromTenant,
					'default_sd_category': default_sd_category,
					'default_bank_id': default_bank_id
					};
					
		remoteCallNew(sURL, obj, 'defaultsApplied');
	}
}
 


function defaultsApplied()
{
	var sResponse = getResponse(RESPONSETYPE_STRING, true);
	document.getElementById('error').innerHTML = sResponse;
	window.location.href = "defaults.php";
}
/*
function ValidateEmail(isFrmApply)
{
	var result = false;
	var EmailID;
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	
	if(document.getElementById('defaultEmailID').value == "")
	{
		return false;	
	}
	else
	{
		EmailID = document.getElementById('defaultEmailID').value;
		result = emailReg.test(EmailID);	
	}
	
	if(result)
	{
		return result;		
	}
	else
	{
		if(!isFrmApply)
		{
			alert("Email Id Invalid.");
		}
		return result;	
	}
	
}*/