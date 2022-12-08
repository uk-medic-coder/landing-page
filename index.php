<?php

error_reporting(E_ALL);     ini_set('display_errors', 1);

// ===========================================================

$OutputBuffering = 1;					// default = 1, ON
$OutputPageName = str_replace('.php','.html', basename(__FILE__));            // This is for the HTML output version


if ($OutputBuffering==1)
{
	ob_clean();
	ob_start();
}


// Site URL vars now in this:
include __DIR__ . '/ZZZ_site_vars.php';


// JS/CSS minifier in here
include  __DIR__ . '/ZZcommonscripts/php/functionsall_TTheader.php';


//=======================================
// ALTER THESE
//=======================================

// These are for the social proof DIV text
$SPoutcome1 = 'Took The Abundance Quiz';
$SPoutcome2 = 'Fixed Their #1 Manifestation Killer';
$SPoutcome3 = '';


$trackcampaign = 'RT01';					// for GDPR data store

$privacyloc = $wwwSiteName.'/legals/privacy.php';       // needed to pass to GDPR functions, as it may change site to site


$GA_UA = '';						// google analytics bit
$FBpixel = '';


$DoGDPRBar = 1;	
$DoSocialProof = 1;					// 0 =off

//=======================================


$FontIncluder = 'https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i|Raleway:700,700i|Open+Sans+Condensed:700';
$PreheaderFont = 'font-family: \'Open Sans\', sans-serif;';
$HeadlineFont = 'font-family: \'Raleway\', sans-serif;';
$H2font = 'font-family: \'Open Sans Condensed\', sans-serif; font-weight:700; font-stretch: condensed';
$PageFont = 'font-family: \'Open Sans\', sans-serif;';


// ~~~~~
// QUIZ
// ~~~~~

$Numquizqns = 9;

// For # shares already
$now = time();
$your_date = strtotime("2018-05-15");
$datediff = $now - $your_date;
$dayssincefirstdate = round($datediff / (60 * 60 * 24));
$numbershares = min(($dayssincefirstdate * 7) + 616, 3983);


if(date("Y")=="2018")
{
	$footerdate="2018";
}
else
{
	$footerdate="2018-".date("Y");
}



$PAGETITLE = "Do you have blocks that are holding back your abundance?";
$PAGEDESCRIPTION = "Do you have blocks that are holding back your abundance?";


//===============================================
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script>window.jQuery || document.write('<script src="<?php echo $SiteLPURL; ?>/js/jquery.min.js"><\/script>')</script>

<title><?php echo $PAGETITLE; ?></title>
<meta name="description" content="<?php echo $PAGEDESCRIPTION; ?>" />

<!--no preload for fonts - as firefox not handle it -->
<link href="<?php echo $FontIncluder; ?>" rel="stylesheet">


<?php


	OutputGA();


	// Key vars for JS
	echo('<script>var privacyloc="'.$privacyloc.'";var FBpixel="'.$FBpixel.'";var GAUAvar="'.$GA_UA.'";var DoSocialProof="'.$DoSocialProof.'";var dogdpr="'.$DoGDPRBar.'";var thissiteurl="'.$SiteLPURL.'";var trackcampn="'.$trackcampaign.'";var NGXurl="'.$NGINXproxyURL.'";var SPoutcome1="'.$SPoutcome1.'";var SPoutcome2="'.$SPoutcome2.'";var SPoutcome3="'.$SPoutcome3.'"</script>');


	OutputAntiCJ();

	//=======================================
	$csm = $SiteLPPATH."css/";
	$cssmasterhttploc = '';					// for type2
	$cssinlinefile = "/var/tmp/ZZZinlinetemp.css";			// IF WANT TO REFRESH CSS, JUST DELETE THIS FILE MANUALLY FOR FORCE A NEW RECONSTRUCTION

	$cssfilesarray = array(
										"./ZZcommonscripts/css_thrive/gdprbar.css",
										$csm."LPBase.css",
										$csm."LPDesignStress/LPStressDesign-Main.css",
										$csm."LPDesignStress/LPStressDesign-1050.css",
										$csm."LPDesignStress/LPStressDesign-767.css",
										$csm."LPDesignStress/LPStressDesign-468.css",
										$csm."animations.css",										
										$csm."optin.css",										
										$csm."quiz.css",										
										$csm."socialproof.css",	

									);									
	//=======================================
	$jsm = $SiteLPPATH."js/";
	$jsmasterhttploc = '';			// for type2
	$jsinlinefile = "/var/tmp/ZZZinlinetemp.js";			// IF WANT TO REFRESH JS, JUST DELETE THIS FILE MANUALLY FOR FORCE A NEW RECONSTRUCTION

	$jsfilesarray = array(
										"./ZZcommonscripts/JS_thrive/JScommon.js",
										"./ZZcommonscripts/JS_thrive/JScommonMk2.js",
										"./ZZcommonscripts/JS_thrive/GDPRallGAall-June2020.js",					// contains all GDPR, the popup bar and all GA functions
										$jsm."elementswitch.js",	
															// A script for each site - put on each page to stop JS execution if not on this domain
										#"./ZZcommonscripts/JS_thrive/copyprtction.js",	
										$jsm."stressquizandoptin2018.js",	
										"./ZZcommonscripts/JS_thrive/socialproof-V2.js",
									);
	$jsasyncarray = array (								// Blank if no async, "async" otherwise
										""						
									);
									
//=======================================

	echo OutputJSFile(1, $jsfilesarray, $CRLF, $jsinlinefile, $jsmasterhttploc, $jsasyncarray);
	echo OutputCSSFile(3,$cssfilesarray, $CRLF, $cssinlinefile, $cssmasterhttploc);
 ?>

<style>
body, input, button, select, option, textarea {
    <?php echo $PageFont; ?>
}
.col-center
{
	float: none;
	margin: 0 auto 0 auto;
}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

<?php
	echo OutputFBPixel($FBpixel);
 ?>

</head>

<body class="docbody" id="mainbodyID">

<div class="pagemainsection">
<script>switchBackdrop()</script>	
	<div id="mainsectionpage01">
		
		<div class="preheader" style="<?php echo $PreheaderFont; ?>" id="preheaddivtext"></div>		
		
		<div class="sectionmainheadline"><h1 id="headdivtext" style="<?php echo $HeadlineFont; ?>"></h1></div>
		
			<div id="topofquizscrollmark"></div>

			<div id="quizsec1">		
		
			<div class="row">
				<div class="col-8 col-m-10 col-s-12 col-center">
					<img class="imgresponsive animatehidden" id="mainfbimg" />	
				</div>
			</div>

			<div class="spacerL">&nbsp;</div>
			
			<?php
			// keep open loops
			?>
			
			<div class="meboxtop">
				<img style="border-radius: 7px;" src="<?php echo $wwwSiteName; ?>/img/mypic.jpg"/>
				<p>From: Michael Simon,<br/>London, United Kingdom<br/><span id="ancjsdatefield"></span></p>	
			</div>			
			
			<p style="clear: both;">Dear Friend,</p>	

			<table class="listtable">
			<tr><td class="tdbullet"><i class="fas fa-arrow-right"></i></td><td>Do you often obsess over where the next "bit" of money is coming from?</td></tr>
			<tr><td class="tdbullet"><i class="fas fa-arrow-right"></i></td><td>Do you envy others for whom love seems to blossom - but for you it seems elusive?</tr>
			<tr><td class="tdbullet"><i class="fas fa-arrow-right"></i></td><td>Do you fantasize about having financial freedom, a perfect relationship - or amazing health?</td></tr>
			<tr><td class="tdbullet"><i class="fas fa-arrow-right"></i></td><td>Have you read The Secret - or any other Law of Attraction teachings - <b>but are not getting what you want?</b></td></tr>
			</table>
			<br/>
			<p><b>If you said "Yes" to any of the above, then...</b></p>
			
			<h2 style="<?php echo $H2font; ?>">This Message Is For You</h2>		
		
			<br/>
			<p>Back in 1952 a scientist found the real answer to <u>manifesting abundance with total ease</u> and <b>mastering the Law Of Attraction.</b></p>
			<p>He bacame a millionaire within 6 short months - and had perfect heath.</p>
			<p>His astonishing ... admittedly bizarre <b>"abundance attraction" technique</b> is NOT about positive thinking, affirmations, visualization, goal setting ... or any other self-development programme you've been into before.</p>
			
			<p>Proof It Works:</p>			
			
			<table class="listtable">
			<tr><td class="tdbullet"><i class="fas fa-check-square trvgreen"></i></td><td>Scientifically verified by Dr. David McCelland of Harvard University, and Dr. Richard Davidson of the State University of New York</td></tr>
			<tr><td class="tdbullet"><i class="fas fa-check-square trvgreen"></i></td><td>Used and endorsed for 20-years by Dr. John L. Kemeny (an associate of Albert Einstein)</tr>
			<tr><td class="tdbullet"><i class="fas fa-check-square trvgreen"></i></td><td>Used by hundreds of the most successful business people to achieve financial freedom, from top Fortune 500 companies such as Exxon, AT&T, Merrill Lynch, TWA, Chemical Bank, and Mutual of New York</td></tr>
			<tr><td class="tdbullet"><img class="listtablegreentick" src="https://info.passgcsemaths.com/hosted/images/a9/d6ef205bd311e4b71c9b2980bbf47a/1414210523_circle-checkmark-32.png"/></td><td>Used by hundreds of the most successful business people to achieve financial freedom, from top Fortune 500 companies such as Exxon, AT&T, Merrill Lynch, TWA, Chemical Bank, and Mutual of New York</td></tr>
			</table>
			
			<div class="spacerM">&nbsp;</div>

			<div id="startquizbtn" class="redbutton redbuttonwidth01 shakeBobble1">
				<span class="showmobileview">TAKE THE QUIZ AND SHOW ME</span>
				<span class="showdesktopview">TAKE THE QUIZ AND SHOW ME</span>
			</div>

		</div>

		<div class="quizsec2 center" id="quizsecid2" style="display: none;" param-p0="<?php echo $Numquizqns; ?>">
	

	<script>switchHeadline()</script>		
		<script>switchImage()</script>			

			<div id="progbarouter">
			  <div id="progbarinner">0%</div>
			</div>

			<div id="quizmainsection">
				
				<div class="quizqnh1 bold" id="quizqnheader1"></div>
				<div class="quizqnh2" id="quizqnheader2"></div>
				<div class="quizqnanswerbox">
						<div id="qn-set-1">
				
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="1" param-p1="1" param-p2="more money">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">More Money</div>		
								</div>
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="1" param-p1="2" param-p2="better health">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">Better Health</div>		
								</div>							
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="1" param-p1="3" param-p2="a loving relationship">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">A Loving Relationship</div>		
								</div>
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="1" param-p1="4" param-p2="widespread abundance">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">All Of The Above!</div>		
								</div>
						</div>
						
						<div id="qn-set-2" style="display: none;">
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="2" param-p1="0">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">I've had lots of success manifesting</div>		
								</div>
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="2" param-p1="1">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">I've had some success but want more</div>		
								</div>
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="2" param-p1="2">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">I've had a little success</div>		
								</div>
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="2" param-p1="3">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">I've had virtually no success</div>		
								</div>
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="2" param-p1="4">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">I'm totally fed up with manifesting</div>		
								</div>	
						</div>

						<div id="qn-set-3" style="display: none;">
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="3" param-p1="1">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">The Secret</div>		
								</div>
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="3" param-p1="2">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">Abraham Hicks</div>		
								</div>
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="3" param-p1="3">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">Louis Hay</div>		
								</div>
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="3" param-p1="4">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">A combination of the above</div>		
								</div>
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="3" param-p1="5">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">None of the above</div>		
								</div>								
						</div>

						<div id="qn-set-4" style="display: none;">
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="4" param-p1="1">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">$50,000+</div>		
								</div>
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="4" param-p1="2">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">$20,000 to $50,000</div>		
								</div>
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="4" param-p1="3">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">$10,000 to $20,000</div>		
								</div>
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="4" param-p1="4">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">$5000 to $10,000</div>		
								</div>
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="4" param-p1="5">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">Under $5000</div>		
								</div>																
						</div>

						<div id="qn-set-5" style="display: none;">
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="5" param-p1="1">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">Male</div>		
								</div>
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="5" param-p1="2">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">Female</div>		
								</div>
						</div>

						<div id="qn-set-6" style="display: none;">
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="6" param-p1="1">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">Yes</div>		
								</div>
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="6" param-p1="2">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">Sounds interesting</div>		
								</div>
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="6" param-p1="3">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">No</div>		
								</div>
						</div>			

						<div id="qn-set-7" style="display: none;">
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="7" param-p1="1">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">Happy for them</div>		
								</div>
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="7" param-p1="2">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">Slightly jealous</div>		
								</div>
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="7" param-p1="3">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">Disappointed - why not me!</div>		
								</div>
						</div>			
						
						<div id="qn-set-8" style="display: none;">
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="8" param-p1="1">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">Yes</div>		
								</div>
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="8" param-p1="2">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">It's challenging at times</div>		
								</div>
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="8" param-p1="3">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">No, it's so easy!</div>		
								</div>
						</div>									
												
						<div id="qn-set-9" style="display: none;">
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="9" param-p1="1">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">Desperately - show me how!</div>		
								</div>
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="9" param-p1="2">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">Sounds interesting</div>		
								</div>
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="9" param-p1="3">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">A little</div>		
								</div>
								<div class="qnboxclickclass qnboxclickarea qnboxtext" param-p0="9" param-p1="4">
											<div class="qnboxtextarea01"><img class="quizradioimg" src="<?php echo $SiteLPURL; ?>/img/radio.png"/></div>
										<div class="qnboxtextarea02">I'm not bothered</div>		
								</div>
						</div>			

				</div>			
		</div>				
		<!-- end quizmainsection -->
		<div id="quizanalyze" style="display: none;">
				<div id="quizanalyzeid1" class="quizqnh1" style="<?php echo $H2font; ?>"></div>
				<div class="row">
					<div class="col-4 col-m-6 col-s-12 col-center">
						<img class="imgresponsive" src="<?php echo $SiteLPURL; ?>/img/loading.gif"/>	
					</div>
				</div>
		</div>


		<div id="postquizanalyze01" style="display: none;">
			<div style="<?php echo $H2font; ?>; font-size: 23px; margin: 30px 0">Your Personalised Results Are As Follows:</div>
			<p id="fnqzscore"></p>
			
			<br/>
			<p><b>Learn how to Fix your "Counter Intention" blockers...</b></p>
			<p>Which email address should I send this to?</p>
			<br/>
			<div class="nomodaloptinfrm">
				<p class="nomargin" id="emaillabel" class="bold">Your Email:-</p>
				<input type="email" placeholder="Your best email here" name="psw" required id="emailbox">

				<div id="notvalidemail" class="modalsmalltext alignleft" style="font-weight: 700;display: none; color: red; margin: -15px 0 20px 30px">Please enter a valid email</div>	
	
				<p><input type="checkbox" id="acmodalgdprbox" /><label for="acmodalgdprbox" class="modalsmalltext"><span class="ui"></span>GDPR: I'm happy for you to email me, and to send periodic emails on this topic only. Full details in the <a href="<?php echo $privacyloc; ?>" target="_blank">Privacy Policy</a></label></p>

				<div id="gdprdiv" class="modalsmalltext alignleft" style="font-weight: 700;display: none; color: red; margin: -15px 0 20px 30px">I need your permission to email you.<br/>Tap the tick box above if you agree.</div>		

				<div id="quizsndbtnanim" class="shakeSide1">
					<div id="submitbutton" class="redbutton redbuttonwidth01">
						<span class="showmobileview">PROCEED TO YOUR MANIFESTING FIX...</span>
						<span class="showdesktopview">PROCEED TO YOUR MANIFESTING FIX...</span>
					</div>
				</div>
				
				<div class="modalsmalltext center acmodalspacer1"><img src="<?php echo $SiteLPURL; ?>/img/lock.ico"/>&nbsp;&nbsp;&nbsp;<b>100% Secure - I Never Share Your Email. I take your privacy seriously.</b></div>

			
			</div>
		</div>
		
	</div>
	<!-- end quizsec2 -->	
				
	<div class="pagefooter">
			<div class="footertext">&copy <?php echo $BaseSiteName; ?>, <?php echo $footerdate; ?></div>
			<div class="footertext2" id="fbfootertext"><br/>This site is not a part of the Facebook website or Facebook, Inc. Additionally, this site is not endorsed by Facebook in any way. FACEBOOK is a trademark of Facebook, Inc. This site is also not a part of Google or Google, Inc. This site is not endorsed by Google / YouTube in any way.</div>
	</div>

<!-- mainsectionpage01 ends -->
	</div>

	<!-- ================================================== -->
</div>




<!-- GDconsent bar on each page -->
<div id="cdhighlight"><!--blank, filled in by JS--></div>

<!-- SP -->
<div class="spchip" style="display: none;">
	<div class="spdivmain">
		<span class="spclose" id="spcloseaction" title="Close">&times;</span>
		<div class="spdiv1"><img id="spimg" src="" class="spchipmainimg" /></div>
		<div class="spdiv2">
			<div class="row sptbl">
				<div class="col-12 bold" id="sptxtrow1" ></div>
				<div class="col-12" id="sptxtrow2" ></div>
				<div class="col-8 col-s-12 sptxtlastrow" id="sptxtrow3" ></div>
				<div class="col-4 col-s-12 sptxtlastrow" ><img class="spimgtick" src="<?php echo $wwwSiteName; ?>/img/verify.jpg"/><span class="spverify">Verified Live</span></div>
			</div>	
		</div>	
	</div>
</div>
 
<script>
function nth(n){return["st","nd","rd"][((n+90)%100-10)%10-1]||"th"}
var d = new Date();
var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
var mlist = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
document.getElementById("ancjsdatefield").innerHTML = days[d.getDay()]+", "+d.getDate()+nth(d.getDate())+" "+mlist[d.getMonth()]+", "+d.getFullYear();
</script>

<script>
$( document ).ready(function() {
	$('#mainfbimg').addClass('animateslowfadein');	
});	

window.onload = function() {
	setTimeout(function(){ $('#mainfbimg').addClass('animated tada');	 }, 200);
};
</script>

</body>
</html>


<?php
	// ================================================== 
	// Nothing after this
	// ================================================== 

	if ($OutputBuffering==1)
	{	
		$bf = ob_get_contents();
		ob_end_clean();
		ob_clean(); //Clean (erase) the output buffer

		echo $bf;
		
		$fln = $Sitevarpath.'/'.$OutputPageName;
		if(file_exists($fln))
		{
			unlink($fln);
		}
		
		$a = file_put_contents($fln, '<!-- html version -->'.$CRLF.$bf);
	}
?>
