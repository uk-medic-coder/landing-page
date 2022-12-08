<?php


$CRLF = chr(13).chr(10);

$trackerfilesloc = '/var/www/trackingserverall/html/';

$PHPencryptionkey = '1234567890'; 


// ==========================================

function encryptData($txtin, $IV)
{
	global $PHPencryptionkey;
	
	$a = openssl_encrypt(
		$txtin,
		'AES-256-CBC',        // cipher and mode
		$PHPencryptionkey,
		0,                    // options (not used)
		$IV                   // initialisation vector
	);
	
	return $a;
}

// ==========================================

function decryptData($txtin, $IV)
{
	global $PHPencryptionkey;
	
	$name = openssl_decrypt(
		$txtin,
		'AES-256-CBC',
		$PHPencryptionkey,
		0,
		$IV
	);
	
	return $name;
	
}

// ================================

function FastFileAppend($fl, $f)
{
	$fp = fopen($fl, 'a');
	fseek($fp,0);
	fwrite($fp, $f);
	fclose($fp);	
}

// ================================

function CheckReferrerIsOK($TrckSITEURL)
{
		$refcheck = 0;
		
		if(isset($_SERVER['HTTP_REFERER']))
		{
			$refsite = $_SERVER['HTTP_REFERER'];
		}
		else
		{
			$refsite = '';
		}

	
		$bnm = strpos($refsite, 'https://'.$TrckSITEURL);

		if($bnm === false)			// === needed because of false/0 issue otherwise from strpos
		{
				if(strpos($refsite, 'http://'.$TrckSITEURL) === false)
				{
				}
				else
				{
						if($bnm==0 and $refsite!='')
						{ $refcheck = 1;	}							
				}
		}
		else
		{
			if($bnm==0 and $refsite!='')
			{ $refcheck = 1;	}		
		}
		
		return $refcheck;
}

// ================================

function MailerLiteSendV1($name,$email,$MLdataid,$MLdatacode,$aux1,$aux2,$aux3)
{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, 'app.mailerlite.com/webforms/submit/'.$MLdatacode);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array(
			'fields[name]' => $name,
			'fields[email]' => $email,
			'fields[company]' => $aux1,
			'fields[city]' => $aux2,
			'data-id' => $MLdataid,
			'data-code' => $MLdatacode,
			'ml-submit' => '1'			// Must have this line, else just shows webform!
		  )); 
		$response = curl_exec($ch);
		curl_close($ch);	
		
		return $response;	
}

// ================================

function CheckEmailPass1($email)
{
	$e = substr($email, 0,100);

	if (!filter_var($e, FILTER_VALIDATE_EMAIL))
	{
		$e = ''; 				// not valid
	}	
	
	return $e;
}

// ================================

function CheckTRCM($trcma)
{
	$trcm = htmlspecialchars($trcma, ENT_QUOTES | ENT_HTML5, 'UTF-8');				// stops XSS eg- JS <script>xxxxxx</script> being executed or PHP
	$trcm = substr($trcm,0,30);
	$trcm = preg_replace('/[^a-zA-Z0-9\s-]/', '', $trcm);			// only a-z, numbers and space and hyphen	
	
	return $trcm;
}

// ================================

function StoreGDPRencrEmail($gdprfilename, $emaila, $trcm)
{
    // date/time stamp the encrypted email for GDPR

    global $CRLF, $trackerfilesloc;

    $DataStoreLoc = $trackerfilesloc.'gdprdataall/'.$trcm.'/';

    $email =  htmlspecialchars($emaila, ENT_QUOTES | ENT_HTML5, 'UTF-8');       // stops XSS for storage in my DB
    // Create initialisation vector once for this script
    $iv = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 16)), 0, 16);


		if(!file_exists($DataStoreLoc))
		{
			mkdir($DataStoreLoc, 0755, true);
		}	
		$a = encryptData($email, $iv).','.$iv.','.date("d/m/Y").",".date("H:i").$CRLF;
		FastFileAppend($DataStoreLoc.$gdprfilename, $a);
}

?>

