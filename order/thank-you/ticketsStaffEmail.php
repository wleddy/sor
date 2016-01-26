<?php
	require_once('config.php');
	// web links in the comment means it's probably spam
	$request = "Ticket Order submitted from web site: " . date("F j, Y - g:i a") . "\n\n";
	$customerName = $_POST['customer_first'] . " " . $_POST['customer_last'];
	$request .= "Name: " . $customerName . "\n";
	$request .= "Email: " . $_POST['customer_email'] . "\n";
	
	$comment = trim($_POST{'customer_comment'});
	if ($comment == "") {
		$comment = "{ No Comment }";
	}
	$request .= "Comment: " . $comment . "\n\n";
	
	$request .= "Number of Tickets: " . $_POST['ticketQty'] . "\n";
	$request .= "Guests: \n";
	$tickCnt = intval($_POST['ticketQty']);
	for ($i=1; $i < $tickCnt; $i++) { 
		$GuestNo = strval($i);
		$guestFirst = 'guestFirst'.$GuestNo;
		$guestLast =  'guestLast'.$GuestNo;
		$request .= "  " . $GuestNo . ': ' . $_POST[$guestFirst] . " " . $_POST[$guestLast] . "\n";
	}
	$request = stripslashes($request);
	// $site array now exists
	$site['from_email'] = 'safariteam@africahopefund.org'; // default in /config.php
	$site['from_name'] = 'Safari On The River'; // default in /config.php
	// Delivery addresses must be arrays...
	$site['to_email'] = array ('safariteam@africahopefund.org');
	$site['BCC_email'] = array ('bill@leddyconsulting.com','cvb@fvbe.info');
	$site['CC_email'] = array ();
	$site['replyToEmail'] = $_POST['customer_email'];
	$site['replyToName'] = $customerName;
	$site['subject'] = "[SOR] Ticket Order - ". $customerName;
	$site['body'] = $request;
	
	
	// handle mail sending
	// Grab the FreakMailer class 
	//require_once($site['config_library'].'/MailClass.inc'); 
	
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
		echo "No email address.";
	}

	
	// clean up
	$mailer->ClearAddresses(); 
	$mailer->ClearAttachments(); 
	$mailer->ClearCustomHeaders(); 
	$mailer->ClearReplyTos(); 
	
   ?>