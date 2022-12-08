
// -------------------------------------------
var maxqns = 0;			// this is read from param-p0, so dont need to set it here
var widthsec = 0;
var curqn = 1;
var currqnid = 2;		// this is just for the LP location check
var ansscore = 0;
var anstxt = [];		// blank array for qn answers
var ansscoretxt = '';
var stopallclks = 0;				// set to 1 to stop all form clicks, for final sending button
anstxt[0] = '';			// coz curqn starts at 1, so make first element blank

var theywant = '';				// string for what they want to manifest
var howgood = 0;


var qnh1 = [];
qnh1[0] = "What Would You Love To Come True?";
qnh1[1] = "Which Of These Applies The Most To You?";
qnh1[2] = "Which Of These Has Had The Most Impact On You?"
qnh1[3] = "How Much Have You Spent On Personal Development?";
qnh1[4] = "Are You...";
qnh1[5] = "Do Subconscious Counter Intentions Mean Anything To You?";
qnh1[6] = "How Do You Feel When Someone Else Has ";
qnh1[7] = "Do You Believe Life is Meant To Be Hard?";
qnh1[8] = "How Much Do You Want To Create Abundance In Your Life?";
var qnh2 = [];


currqnid = 1;


// ==================================
// Converts to sentence case

function toTitleCase(str) {
    return str.replace(
        /\w\S*/g,
        function(txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        }
    );
}

// ==================================

var utmcamp = getQueryVariable('utm_campaign');




$(document).ready(function(){

	$("#startquizbtn").click(function() {
		
		if(currqnid==1)
		{
			$("#quizsec1").fadeOut(200, function() {
				$("#quizsecid2").fadeIn(200, function() {	

					maxqns = $("#quizsecid2").attr('param-p0');

					widthsec = 100/maxqns;

					curqn = 1;
					
					updatequizscreen();	
					ScrollToElement("#topofquizscrollmark", 0);

					// Send SocialProof JSON
					if (DoSocialProof==1 && currqnid==1)
					{
						sendSPData(1,'Someone',Math.floor(Math.random() * 2 + 1));				// type, name, random 1 man 2 woman
					}
					
					// Send GA 
					
					GTSendEvent('click','LP_startquiz',0,0);			// event value = 0 not ''
					FBSendEvent('trackCustom', 'RT_LP_quizstart','');			// case sensitive & must have 3rd param as ''
																														// or JSON, else error!

				});
			});
		}		
	});

	// item clicked, move quiz on
	$(".qnboxclickarea").click(function() {

		if(currqnid==1)
		{			
			var setnum = $(this).attr('param-p0');
			var itemnum = $(this).attr('param-p1');
			
			if(curqn==1)
			{
				theywant = $(this).attr('param-p2');
			}
			if(curqn==2)
			{
				howgood = $(this).attr('param-p1');
			}
			
			
			GTSendEvent('click','LPQnClick'+setnum,0,0);	
			
			
			$("#qn-set-"+curqn).fadeOut(200, function() {
	
				switch(curqn) {
					
						case 1:
								qnh1[6] = qnh1[6] +toTitleCase(theywant) + "?";
								
								// standard stuff now
								anstxt[curqn] = itemnum
								ansscore = ansscore + parseInt(itemnum);				
						break;
						
						default:
							anstxt[curqn] = itemnum
							ansscore = ansscore + parseInt(itemnum);
				}
		
				curqn++;
				
				if(curqn>maxqns)
				{
					updatequizscreen();
					$("#quizanalyzeid1").html('Analysing your responses to 1 - '+maxqns);
					$("#quizmainsection").hide();
					$("#quizanalyze").fadeIn(600, function(){
						setTimeout(function(){
							$("#quizanalyzeid1").hide();
							$("#quizanalyzeid1").html('Calculating ...');
							$("#quizanalyzeid1").fadeIn(400, function(){
								setTimeout(function(){	
									$("#quizanalyze").hide();	
									$('#progbarouter').hide();
								
									if(howgood==0)
									{
										var ttmf = "Your manifesting skills are already good, but there are some improvements that will help you to manifest <b>"+theywant.toUpperCase()+"</b> with greater ease and efficiency.";
										ansscoretxt = "GOOD";
									}
									else
									{
										if(howgood<3)
										{
											var ttmf = "You have had some success with manifesting, but your attempts to manifest <b>"+theywant.toUpperCase()+"</b> will be harder and much slower than they could be.";
											ansscoretxt = "MODERATE";
										}
										else
										{
											var ttmf = "Your desire to manifest <b>"+theywant.toUpperCase()+"</b> is likely to be blocked, and any further attempts without understanding your #1 Manifestion Blocker will probably be wasted time.";
											ansscoretxt = "NEEDING IMPROVEMENT";
										}
									}
									
									$("#fnqzscore").html("<p>"+ttmf+"</p><p>Your #1 Manifestion Blocker is likely to be your having: <span class='red bold'>'COUNTER INTENTIONS'.</span></p>");
									
																		
									$("#postquizanalyze01").fadeIn(600);							
								}, 1000);
							});
						}, 1500);
					});
				}
				else
				{
					updatequizscreen();	
					$("#qn-set-"+curqn).fadeIn(400, function() {
						ScrollToElement("#topofquizscrollmark", 0);
						
					});
				}
			});
		}
	});	
	
// ==================================
// optin for quiz - in jquery area
// ==================================

	function ModalClearFormFocus()
	{
		$("#emailbox").css('border', '1px solid #7eb4ea');	
		$("#gdprdiv").hide();
		$("#notvalidemail").hide();
		
	}

	function BasicValidateEmail(email) 
	{
		// only basic @ test
		var re = /\S+@\S+/;
		return re.test(email);
	}

	// --------------
	
	$("#emailbox").focus(function(){
		ModalClearFormFocus();		
	});

	$('#acmodalgdprbox').change(function(){
		$("#gdprdiv").hide();
	});

	$("#submitbutton").click(function()	
		{		
			if (BasicValidateEmail($("#emailbox").val()))
			{	
				if($('#acmodalgdprbox').is(':checked'))
				{
					if(stopallclks==0)
					{
						if(currqnid==1)			// keep coz if illegal use dont want to email
						{
							
							// OPTIN FORM CONSENT
							// Send all tickboxes to show consent, and make sure store with encrypted email and datetime stamp
							// tb:"0-x"  0 = none dont store, 1-x is number of tickboxes
									// for 1-x:  tb1:"yes", tb2:"no", etc...
									
							// QUIZ RESPONSES
							// these are not stored with email address! GDPR PII, so fully anonmymised
							// qx:"0-x"  0 = none dont store, 1-x is maxqns		
									// 1-x: qr1:"survey1", etc....
									// qs = quizscore
									
							// EMAIL
							// This is logged date/time stamp on server, encrypted of course!		
							// em:"xxxx.com"

							// Set button spinner, needs font awesome
							stopallclks = 1;			// stop further clicks on the form
							
							//$("#acoptinfooter").hide();
							$("#submitbutton").html('SENDING ... <i class="fas fa-spinner fa-spin"></i>');

							// Send GA 
							GTSendEvent('click','LPOptin',0,0);	
							FBSendEvent('trackCustom', 'RT_LP_Optin','');     // case sensitive & must have 3rd param as ''
                                                             // or JSON, else error!

							setCookie("qzwant",theywant,5);			// 5 days to store quiz result --- if change this change on WP side also in:
							setCookie("qzoutcome",ansscoretxt,5);			// 5 days to store quiz result --- if change this change on WP side also in:
						
							var mailrscript = thissiteurl+"/phpmp/RTquizmlr.php";		
							var ajxd = "trcm="+trackcampn+"&em="+ProcessEmailField() + "&tb=0&qx="+maxqns+"&qs="+ansscore+"&qstx="+ansscoretxt+"&tw="+theywant.toUpperCase();

							if(maxqns>0)
							{
								var j;
								
								for (j = 1; j <= maxqns; j++) 
								{ 
									// clear up each anstxt for POST
									// only a-z . + , and -, strip &? as POST query string uses them
									ajxd = ajxd + "&qr"+j+"="+ProcessQuizanswer(anstxt[j]);
									//console.log(j);
								}			
							}					
							
							// Remember GDPR send as POST, not GET
							if(currqnid==1)
							{
								var request = $.ajax({
										url: mailrscript,
										type: "POST",
										data: ajxd
								}).done(function(d)
								{
									window.location="https://manifest-abundance.net";
								});	
							}
							
						}	
					}		
				}
				else
				{
						$("#gdprdiv").show();
				}					
			}
			else
			{
					$("#emailbox").css('border', '1px solid red');
					//$("#emaillabel").css('color', 'red');		
					$("#notvalidemail").show();
			}
		});	

});

// ==================================
// end jquery
// ==================================

function ProcessEmailField()
{
	var a = $("#emailbox").val().substring(0,100);
	var b = a.replace('&', '');
	return b;
}

function ProcessQuizanswer(q)
{
	var regExpr = /[^a-zA-Z0-9-.+, ]/g;		
	var a = q.substring(0,200);
	var b = a.replace(regExpr, '');
	return b;
}

function processtextbox(a)
{
	var b = a.toLowerCase();
	var c = b.replace(/[^a-zA-Z0-9]+/g, "-");
	return c;
}

function updatequizscreen()
{
  var elem = document.getElementById("progbarinner");   
  if(curqn==1)
  {
	  elem.style.width = '0px'; 
	  elem.innerHTML = '0%';	  
  }
  else
  {
	  var width = (curqn-2) * widthsec;
	  var id = setInterval(frame, 10);
	  function frame()
	  {
		if (width >= ((curqn-1)*widthsec)) {
		  clearInterval(id);
		} else {
		  width++; 
		  elem.style.width = width + '%'; 
		  elem.innerHTML = Math.floor(width)  + '% completed';
		}
	  }
	}	
	
	$("#quizqnheader1").html("QUESTION "+curqn+" of "+maxqns);		
	$("#quizqnheader2").html(qnh1[curqn-1]);	
}


function ScrollToElement (id,behave)
{
		var gg="html, body";

		if(behave==0)	// to top of element
		{
			$(gg).animate({ scrollTop: $(id).offset().top }, 320);			
		}
		else		// scroll to center of viewport
		{
			$(gg).animate({scrollTop: $(id).offset().top - ( $(window).height() - $(id).outerHeight(true) ) / 2}, 320);			}
}

