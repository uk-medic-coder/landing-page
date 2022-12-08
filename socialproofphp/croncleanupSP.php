<?php

//error_reporting(E_ALL);  ini_set('display_errors', 1);

// Site URL vars now in this:
include __DIR__ . '/../ZZZ_site_vars.php';

// CheckReferrerIsOK()  and FastFileAppend in here:
include $trackerfilesloc.'sitecore/ZZCoreFunctionsInclude.php';

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

// Count number of lines

$file= __DIR__ . '/../socialprooftxt/txt.txt';
$cronlock = __DIR__ . '/../socialprooftxt/CRONLOCK.anc';


// Set cronlock

if(!file_exists($cronlock))
{
	FastFileAppend($cronlock, 'a');
}	


// do main

$linecount = 0;

$handle = fopen($file, 'r');
while(!feof($handle)){
  $line = fgets($handle);
  ++$linecount;
}
fclose($handle);

--$linecount;				// coz blank CRLF at end


$allowedlines = 40;

// Take last allowedlines lines and output to a new file
// if less than this do nothing!

if ($linecount>$allowedlines)
{
	$x = 0;
	$tg = $linecount - $allowedlines;
	$nm = __DIR__ . '/../socialprooftxt/tmptt.txt';
	
	$handle = fopen($file, 'r');
	while(!feof($handle)){
	  $line = fgets($handle);
	  ++$x;
	  if($x > $tg)
	  {
		  FastFileAppend($nm, $line);		  }
	}
	fclose($handle);	
	
	// delete orig, and rename tmp
	
	unlink($file);
	rename($nm, $file);
	
  // coz Cron runs under Root, and if not chown future processes cannot append to file 
  chown($file, $chownuser);
	
	
}



// FINALLY delete cronlock file
unlink($cronlock);

?>
