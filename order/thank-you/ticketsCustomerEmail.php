<?php
	$customerName = trim($_POST['customer_first']) . " " . trim($_POST['customer_last']);
	$tickCnt = intval($_POST['ticketQty']);
	
	$request = "";
	$request .= "Dear " . $customerName .  ",\n\n";
	$request .= "We're excited that you'll be joining us for Safari on the River on";
	$request .= " July 20th! It will be a fun event supporting a great cause.\n\n";
	$request .= "Your ticket";
	if($tickCnt > 1) {
		$request .= "s";
	}
	$request .= " will be waiting for you at Will Call when the event";
	$request .= " begins at 4:30.\n\n";
	$request .= "(You'll get two confirmation emails from PayPal, one saying that";
	$request .= " your \"item was shipped\" but that just means we've processed your order.)\n\n";
	$request .= "If you have any questions, please contact SafariTeam@AfricaHopeFund.org. Thanks";
	$request .= " again for your support and we look forward to sharing this fun event with you!\n\n";
	
	$tickCnt = intval($_POST['ticketQty']);
	if ($tickCnt > 1) {
		$request .= "Your Guests: \n";
		for ($i=1; $i < $tickCnt; $i++) { 
			$GuestNo = strval($i);
			$guestFirst = 'guestFirst'.$GuestNo;
			$guestLast =  'guestLast'.$GuestNo;
			$request .= "  " . $_POST[$guestFirst] . " " . $_POST[$guestLast] . "\n";
		}
	}
	
	$request = stripslashes($request);
	require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
	// $site array now exists
	$site['from_email'] = 'safariteam@africahopefund.org'; // default in /config.php
	$site['from_name'] = 'Safari On The River'; // default in /config.php
	// Delivery addresses must be arrays...
	$site['to_email'] = array ($_POST['customer_email'],);
	$site['BCC_email'] = array ();
	$site['CC_email'] = array ();
	$site['replyToEmail'] = $site['from_email'];
	$site['replyToName'] = $site['from_name'];
	$site['subject'] = "Safari on the River Ticket Confirmation";
	$site['body'] = $request;
	// handle mail sending
	// Grab the FreakMailer class 
	require_once($site['config_library'].'/MailClass.inc'); 
	
	// instantiate the class 
	$mailer = new FreakMailer(); 
	
	// Set the subject 
	$mailer->Subject = $site['subject']; 
	// Body 
	$mailer->Body = $site['body']; 
	// Setup the from and Reply to
	$mailer->From = $site['from_email']; 
	$mailer->FromName = $site['from_name'];
	if ($site['replyToEmail'] != ''){
		$mailer->AddReplyTo($site['replyToEmail'],$site['replyToName']); 
	}

	// Add To addresses 
	for ($i=1; $i <= count($site['to_email']); $i++) { 
		$mailer->AddAddress($site['to_email'][$i-1]); 
	}
	//$mailer->AddAddress('bill@leddyconsulting.com', 'Bill Leddy'); 
	// add CC addresses
	for ($i=1; $i <= count($site['CC_email']); $i++) { 
		$mailer->AddCC($site['CC_email'][$i-1]); 
	}
	
	// add BCC addresses
	for ($i=1; $i <= count($site['BCC_email']); $i++) { 
		$mailer->AddBCC($site['BCC_email'][$i-1]); 
	}
			//$mailer->AddBCC('bill@leddyconsulting.com', 'Bill Leddy'); 	

	if(trim($_POST['customer_email']) != '') {
		//Actually send the email
		if(!$mailer->Send()) 
		{ 
			echo "Fail";
			// use this to dump the mailer info
			// look for ErrorInfo
			//print_r($mailer);
		} 
		else 
		{ 
			echo "Success";
		} 
	} else {
		echo "No email sent";
	}
	
	// clean up
	$mailer->ClearAddresses(); 
	$mailer->ClearAttachments(); 
	$mailer->ClearCustomHeaders(); 
	$mailer->ClearReplyTos(); 
	
   ?>