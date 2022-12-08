<?php

$operational = 1;					// 0 = off dont run ; 1 = run it


// $runtype:
	// 1 = old cities
	// 2 = newer regions
$runtype = mt_rand(1,2);
//$runtype = 1;

$crontimerun = (45*60);				// 45min cron run --> if that changes change this

$randtime = $crontimerun - (8*60);        // crontime - 8 mins currently --- all in seconds remember (10*60)

$nm = 'Someone';				// username
$sex = mt_rand(1,2);			// sex
$type = mt_rand(1, 2);		// type, first column

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if($operational==1)
{

// Site URL vars now in this:
include __DIR__ . '/../ZZZ_site_vars.php';

// CheckReferrerIsOK()  and FastFileAppend & Gmail error send in here:
include $trackerfilesloc.'sitecore/ZZCoreFunctionsInclude.php';

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	$xx = __DIR__ . '/../socialprooftxt/';
	$yy = $xx.'CRONLOCK.anc';
	$lasttm = $xx.'lasttime.anc';

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
				sleep(4);	
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
		$flg2 = 0;
		
		if(file_exists($lasttm))
		{
			$qq =(int)file_get_contents($lasttm);
			if($qq>0)					// strings etc return 0 for (int) casting
			{
				if($qq<(time() - $crontimerun))
				{
					unlink ($lasttm);
					$flg2 = 1;					
				}
			}
		}
		else
		{
			$flg2 = 1;
		}
		
		
		// proceed
		
		if($flg2==1)
		{
			if($runtype==2)
			{
				$c = mt_rand(1,6);

				switch($c)
				{
					case 1:
						$cc = './RegionsNew/listENG.anc';
						$ln = 1;
						break;
					case 2:
						$cc = './RegionsNew/listWAL.anc';
						$ln = 1;
						break;
					case 3:
						$cc = './RegionsNew/listSCOT.anc';
						$ln = 1;
						break;
					case 4:
						$cc = './RegionsNew/listIRE.anc';
						$ln = 4;
						break;
					case 5:
						$cc = './RegionsNew/listUS.anc';
						$ln = 50;
						break;
					case 6:
						$cc = './RegionsNew/listCAN.anc';
						$ln = 13;
						break;
				}	
			}
			else
			{
				$c = mt_rand(3,5);

				switch($c)
				{
					case 1:
						$cc = './RegionsCities/LISTaustralia.anc';
						$ln = 16;
						break;
					case 2:
						$cc = './RegionsCities/LISTeurope.anc';
						$ln = 85;
						break;
					case 3:
						$cc = './RegionsCities/LISTcanada.anc';
						$ln = 7;
						break;
					case 4:
						$cc = './RegionsCities/LISTuk.anc';
						$ln = 44;
						break;
					case 5:
						$cc = './RegionsCities/LISTus.anc';
						$ln = 45;
						break;
				}
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
					$tm = time() - mt_rand(60, $randtime);				// in seconds - randtime
					
					// store actual row
					
					FastFileAppend($xx.'txt.txt', $type.','.$tm.','.$nm.','.$line.','.$sex.$CRLF);							
				}
				else
				{
					++$cnt;
				}
			}
			fclose($fp);			
		}
	// nothing after here	
	}
}
?>

