<?php

$operational = 1;					// 0 = off dont run ; 1 = run it

$randtime = (14.5 * 60);        // 18.5 mins max in seconds, as cron is 24mins

$nm = 'Someone';				// username

$sex = mt_rand(1,2);			// sex

$type = mt_rand(1, 2);		// type, first column

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if($operational==1)
{
include __DIR__ . '/../ZZZ_site_vars.php';

include $trackerfilesloc.'sitecore/ZZCoreFunctionsInclude.php';

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	$xx = __DIR__ . '/../socialprooftxt/';
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
					// Something wrong! Send error
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
		$c = mt_rand(3,5);
	
		switch($c)
		{
			case 1:
				$cc = './LISTaustralia.anc';
				$ln = 16;
				break;
			case 2:
				$cc = './LISTeurope.anc';
				$ln = 85;
				break;
			case 3:
				$cc = './LISTcanada.anc';
				$ln = 7;
				break;
			case 4:
				$cc = './LISTuk.anc';
				$ln = 44;
				break;
			case 5:
				$cc = './LISTus.anc';
				$ln = 45;
				break;
		}
		
		$ww = mt_rand(1, $ln);			// 1 to $ln inclusive
		$cnt = 1;
		$cf = 0;
		
		
		// Read in file, this seems to be faster than fgets --- not file_get_contents, as this reads whole file into memory as an array!
		

		$fp=fopen($cc, 'r');
		while($cf==0)					// xxd LISTcanada.anc showed 0a to be the linefeed. 0a == ASCII 10.
		{

			$line=stream_get_line($fp,65535, chr(10));
			if($cnt==$ww)
			{
				$cf = 1;
				//echo $line;
				// get tm
				
				date_default_timezone_set('UTC');				// as done in JS client side
				$tm = time() - mt_rand(0, $randtime);				// in seconds - randtime
				
				// store actual row
				
				FastFileAppend($xx.'txt.txt', $type.','.($tm*1000).','.$nm.','.$line.','.$sex.$CRLF);							
			}
			else
			{
				++$cnt;
			}
		}
		fclose($fp);			
	
	// nothing after here	
	}
}
?>
