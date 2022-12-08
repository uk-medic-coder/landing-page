<?php

//error_reporting(E_ALL);  ini_set('display_errors', 1);

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

// Site URL vars now in this:
include __DIR__ . '/../ZZZ_site_vars.php';

function FastFileAppend($fl, $f)
{
	// This method is faster in my tests than file_put_contents for APPENDING to big files
	
	$fp = fopen($fl, 'a');
	fseek($fp,0);
	fwrite($fp, $f);
	fclose($fp);	
}


// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

// Referrer check

$refcheck = 0;
$TrckSITEURL = $siterefname;

$refcheck = 1

if ($refcheck == 1)
{
		$xx = __DIR__ . '/../socialprooftxt/';
		$lasttm = __DIR__ . '/../socialprooftxt/lasttime.anc';
		$yy = $xx.'CRONLOCK.anc';
		$flg = 0;
		
		// see if cron cleanup is running
		
		if(file_exists($yy))
		{
			sleep(2);			//2s
			if(file_exists($yy))
			{
				sleep(3);
				if(file_exists($yy))
				{
					sleep(6);	
					if(file_exists($yy))
					{
						// ERROR CODE HERE						
					}
					else
					{
						$flg = 1;
					}										
				}
				else
				{
					$flg = 1;
				}	
			}
			else
			{
				$flg = 1;
			}	
		}
		else
		{
			$flg = 1;
		}		
		
		// do it?
		
		if($flg==1)
		{
			if(!file_exists($xx))
			{
				mkdir($xx, 0755, true);
			}	
			FastFileAppend($xx.'txt.txt', $_POST['vv'].$CRLF);

			if(file_exists($lasttm))
			{
				unlink($lasttm);
			}

			FastFileAppend($lasttm, $_POST['lstm']);
		}
}
else
{
	echo("success");
}

?>
