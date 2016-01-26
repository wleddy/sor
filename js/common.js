
// common.js

// share the $
var $j = jQuery;

// the earlyBird end date. Used in various functions
// the date constructor assumes the months are numbered 0-11, days are numbered 1-31
var expires = new Date(2016,8,18) // June 2nd 
var earlyBirdFormContainID = "#EarlybirdTicketsForm";
var standardFormContainID = "#StandardTicketsForm";
var earlyBirdFormID = "#EarlybirdTicketsFormPP";
var standardFormID = "#StandardTicketsFormPP";
var earlyBirdBuyButtonID = "#earlyBuyButton"
var standardBuyButtonID = "#standardBuyButton"

function isEarly() {
	// hide the out of date ticket form
	var now = new Date();
	//alert( expires+ " > " + now)
	return (expires > now) 
}

function doReady(){
	// hide the out of date ticket form
	//alert(isEarly())
	if (isEarly()) 
	{
		$j(".regularPrice").hide();
		$j(standardFormContainID).text("");
		$j("#villageSponsor").attr("value","TX5CBT3FFPLAJ"); // the PP button code for earlybird price
	} 
	else {
		$j(".earlybird").hide();
		$j(earlyBirdFormContainID ).text("");
		$j("#villageSponsor").attr("value","B96463PRNN5ME"); // the PP button code for Standard price
	}
	//alert("ready");
}

// validate an email address
function IsEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

// for order.htm form
function orderQtySelect(qty) {
	displayGuestInput(qty.selectedIndex); // zero indexed
}

function displayGuestInput(howMany) {
	// hide all the guest fields
	$j(".guestName").hide();
	// disable all the guest name input so extras will not be in the submitted form
	$j('.guestName input').attr('disabled', 'disabled');
	
	// then show the needed ones
	if(howMany > 0) {
		for (var i = 0; i <= howMany; i++){
			//alert('#guestName-' + i.toString());
			$j('#guestName-' + i.toString()).show();
			$j('#guestName-' + i.toString() + ' input').removeAttr('disabled')
		}
	}
	$j("#ticketQty").val(howMany+1) //Order Quantity hidden field
}

function submitOrder() {
	// Disable the submit button to avoid double submissions
	var submitButtonObj = $j('#orderSubmit');
	$j(submitButtonObj).attr('disabled', 'disabled');
	var emailOk = true;
	// Send some emails quietly and then send the user to PayPal to make purchase
	if((IsEmail($j('#customer_email').val())) && ($j('#customer_first').val() + $j('#customer_last').val() !='')) {
	// Use ajax to send an email to SOR staff with the Purchaser and Guest names...
		sendStaffEmail();
	// Use ajax to send an email to the customer...
		sendCustomerEmail();
	} else {
		emailOk = false;
		$j(submitButtonObj).removeAttr('disabled');
		alert("Your name and email address are required.\nThanks!");		
	}
	if(emailOk) {
		// pick the correct form to use so customer is billed correctly
		var PPButton = '';
		if (isEarly()) 
		{
			PPButton = earlyBirdBuyButtonID
		} 
		else {
			PPButton = standardBuyButtonID
		}
		// submit the form by simulating a click on the submit button
		//alert(PPButton);
		$j(PPButton).trigger('click');
	} //email Ok
	// don't submit our data collection form
	return false;
}

function sendStaffEmail() {
	// send customer information to staff
	var dataOut = $j('#customer_form').serialize();
	var data = "";
	$j.ajaxSetup({"async" : false});
	//alert(dataOut);
	$.post( "/order/thank-you/ticketsStaffEmail.php",dataOut, function( data ) {
	  //alert( data ); // The returned text. Either "Success" or "Fail"
	});
}

function sendCustomerEmail() {
	// Send instructional email to customer
	var data = $j('#customer_form').serialize();
	//alert(data)
	$j.ajaxSetup({"async" : false});
	$.post( "/order/thank-you/ticketsCustomerEmail.php",data, function( data ) {
	  //alert( data ); // The returned text. Either "Success" or "Fail"
	});
}