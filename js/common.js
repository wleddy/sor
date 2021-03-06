
// common.js


// the earlyBird end date. Used in various functions
// the date constructor assumes the months are numbered 0-11, days are numbered 1-31
var expires = new Date(2013,(6-1),2) // for 2016, no early bird pricing, so set it in the past
var earlyBirdFormContainID = "#EarlybirdTicketsForm";
var standardFormContainID = "#StandardTicketsForm";
var earlyBirdFormID = "#EarlybirdTicketsFormPP";
var standardFormID = "#StandardTicketsFormPP";
var earlyBirdBuyButtonID = "#earlyBuyButton"
var standardBuyButtonID = "#standardBuyButton"

// set up the date and time strings
function getEventDate(){
	return "Sunday, August 26th, 2018";
}
function getEventTime(){
	return "4:30 PM to 8:30 PM";
}

function getEventLocation(){
    return "The estate of Maggie Ferrari<br>3333 Sunnybank Lane<br>Carmichael, CA";
}

function getEventMapURL(){
    $mapURL = '<a href="https://www.google.com/maps/place/3333+Sunnybank+Ln,+Carmichael,+CA+95608/@38.6245253,-121.3070752,17z/data=!4m2!3m1!1s0x809addcb22c9915b:0x7f0b1b875c6d6485" target="_blank" >(map)</a>';
    //$mapURL = 	'<a href="http://maps.google.com/maps?q=1951+Garden+Highway,+Sacramento,+CA&oe=utf-8&hnear=1951+Garden+Hwy,+Sacramento,+California+95833&gl=us&t=m&z=16" target="_blank">(map)</a>';
    
    return $mapURL;
}

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
		$(".regularPrice").hide();
		$(standardFormContainID).text("");
		$("#villageSponsor").attr("value","TX5CBT3FFPLAJ"); // the PP button code for earlybird price
	} 
	else {
		$(".earlybird").hide();
		$(earlyBirdFormContainID ).text("");
		$("#villageSponsor").attr("value","B96463PRNN5ME"); // the PP button code for Standard price
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
	$(".guestName").hide();
	// disable all the guest name input so extras will not be in the submitted form
	$('.guestName input').attr('disabled', 'disabled');
	
	// then show the needed ones
	if(howMany > 0) {
		for (var i = 0; i <= howMany; i++){
			//alert('#guestName-' + i.toString());
			$('#guestName-' + i.toString()).show();
			$('#guestName-' + i.toString() + ' input').removeAttr('disabled')
		}
	}
	$("#ticketQty").val(howMany+1) //Order Quantity hidden field
}

function submitOrder() {
	// Disable the submit button to avoid double submissions
	var submitButtonObj = $('#orderSubmit');
	$(submitButtonObj).attr('disabled', 'disabled');
	var emailOk = true;
	// Send some emails quietly and then send the user to PayPal to make purchase
	if((IsEmail($('#customer_email').val())) && ($('#customer_first').val() + $('#customer_last').val() !='')) {
	// Use ajax to send an email to SOR staff with the Purchaser and Guest names...
		sendStaffEmail();
	// Use ajax to send an email to the customer...
		sendCustomerEmail();
	} else {
		emailOk = false;
		$(submitButtonObj).removeAttr('disabled');
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
		$(PPButton).trigger('click');
	} //email Ok
	// don't submit our data collection form
	return false;
}

function sendStaffEmail() {
	// send customer information to staff
	var dataOut = $('#customer_form').serialize();
	var data = "";
	$.ajaxSetup({"async" : false});
	//alert(dataOut);
	$.post( "/order/thank-you/ticketsStaffEmail.php",dataOut, function( data ) {
	  //alert( data ); // The returned text. Either "Success" or "Fail"
	});
}

function sendCustomerEmail() {
	// Send instructional email to customer
	var data = $('#customer_form').serialize();
	//alert(data)
	$.ajaxSetup({"async" : false});
	$.post( "/order/thank-you/ticketsCustomerEmail.php",data, function( data ) {
	  //alert( data ); // The returned text. Either "Success" or "Fail"
	});
}