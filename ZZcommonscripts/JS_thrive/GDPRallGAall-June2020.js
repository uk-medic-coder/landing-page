// dogdpr variable:

// 0 = dont show bar, send all events
// 1 = show bar, but send all events
// 2 = show bar - wait for consent to send all events

var allowDNT = 0;
var sendevnt = 1;	

var nowsendga = 0;        // global also
var sndpvflag = 0;          // needs to be global


// ================================

$(window).on('load', function () {

		$('#cdhighlight').html('<div class="cdhdiv1"><p class="white cdhdivtext">Cookies are used to ensure you get the best experience. By remaining here, we\'ll assume you\'re OK with this.&nbsp;&nbsp;<a href="'+thissiteurl+privacyloc+'" target="_blank">Learn More Here.</a></p></div><div class="cdhdiv2"><div class="cdhbutton" id="cookiecnsntaccept"><p style="text-align: center;color:#000;margin:0;padding:0;font-weight:700;">ACCEPT</p></div></div>');


		// now do initial decisions
		
		if(dogdpr==0)
		{
			SendInitPVGA();
			SendInitPVFB();
        }
		
		if(dogdpr==1)
		{
			if (getCookie("cookiecnsnt")!="yes")
			{
				document.getElementById("cdhighlight").style.bottom = "0";
			}
			SendInitPVGA();
			SendInitPVFB();
		}
		
		if(dogdpr==2)
		{
			if (getCookie("cookiecnsnt")!="yes")
			{
				document.getElementById("cdhighlight").style.bottom = "0";
				sendevnt = 0;
			}
			else
			{
			SendInitPVGA();
			SendInitPVFB();
			}
		}


	// =================================
	// GDPR "ACCEPT" button click
	
	$("#cookiecnsntaccept").click(function(){
		setCookie("cookiecnsnt","yes", 365);				// 1 yr expiration date
		document.getElementById("cdhighlight").style.bottom = "-300px";
		
		sendevnt = 1;
        SendInitPVGA();
        SendInitPVFB();
	});	

});

// ================================
function SendInitPVGA()
{
	if(TestOptInOut() == 1 && TestDoNotTrack() == 0 && GAUAvar!="")	
	{
        // 1st thing to do is to work out is GA.JS working???
        
        sndpvflag = 0;
        
        if(nowsendga==0)
        {
            if(getCookie("sentNowSndGA")=="")
            {
               sendGAPageview(0);
            }
            else
            {
                    nowsendga = getCookie("sentNowSndGA");               // this session only, saves having to retest GA on subsequent pages
                    sndpvflag = 1;
            }              
        }
        else
        {            sndpvflag = 1;                }
        

        // if OK, go ahead
        
        if(sndpvflag==1)
        {   
            if(nowsendga==1)
            {
                sendGAPageview(1);
            }
            else
            {
                sendGAPageviewLazyImg();
            }
        }
    }	
}

// ================================

function SendInitPVFB()
{
	if(TestOptInOut() == 1 && TestDoNotTrack() == 0)	
	{
        var url = [location.protocol, '//', location.host, location.pathname].join('');         // remove QString
        var a = url.slice(-40);				// last portion of URL, Use the .slice() method instead because it is cross browser compatible (see IE).

        if(getCookie("sentInPVFB")!=a)
        {
            // only send if not sent before - for this page
            setCookie("sentInPVFB",a, "");				// 3rd param = "" to set a session cookie coz for this page only

            if(FBpixel!="")
            {
                if(whichFBevent()==1)
                {
                    fbq('init', FBpixel);
                    fbq('track', 'PageView');					
                }
                else
                {
                    //var x = "id="+FBpixel+"&ev=PageView&dl="+GetFullURLNoQS()+"&v=2.8.30&r=stable&ec=0";
                    //SendFBstuff(x);
                
                    var x= "id="+FBpixel+"&ev=PageView&noscript=1";
                    SendFBstuff(x);					
                }
            }
        }
    }	
}

// ================================
function sendGAPageview(x)
{

    
    ancana('create', GAUAvar, 'auto');
    ancana('set', 'anonymizeIp', true);				// GDPR safety    

    
    if(x==0)
    {
        setTimeout(function(){ failgacallbck(); }, 3000);
        ancana('send', 'pageview', {'hitCallback': function() { rtgacallbck();   } });    
    }
    else
    {
        ancana('send', 'pageview'); 
        pgloadGAsend();                                     // This function is in the mainpage.js             
    }
}

// ================================
function sendGAPageviewLazyImg()
{
    var q = genGAstringFirst(GAUAvar);

    // this is the actual data bit
    q+="&t=pageview";

    // This must go at the end of the string
    q += genGAstringLast();

    sendGTstuff(q);

    pgloadGAsend();                                     // This function is in the mainpage.js       
}

// ================================
function rtgacallbck()
{
    nowsendga = 1;
    setCookie("sentNowSndGA",1, "");            // set for this session only

    pgloadGAsend();                                     // This function is in the mainpage.js        
}

// ================================
function failgacallbck()
{
    if(nowsendga==0)
    {
        // failed, so set it to 2
        
        nowsendga = 2;
        setCookie("sentNowSndGA",2, "");            // set for this session only

        sendGAPageviewLazyImg();                    // (pgloadGAsend();   done in that function)
    }
}

// ================================

function TestOptInOut()
{
	// returns 1 if opted IN, 0 if OUT
	
	if(getCookie("optstatsga")=="out")
	{
		return 0;
	}	
	else
	{
		return 1;
	}
}

// ================================

function TestDoNotTrack()
{
	// 1 = DNT enabled ; 0 = DNT not set

	if(allowDNT==0)
	{
		return 0;
	}
	else
	{
		if (window.doNotTrack || navigator.doNotTrack || navigator.msDoNotTrack || 'msTrackingProtectionEnabled' in window.external)
		{
			// The browser supports Do Not Track!

			if (window.doNotTrack == "1" || navigator.doNotTrack == "yes" || navigator.doNotTrack == "1" || navigator.msDoNotTrack == "1" || window.external.msTrackingProtectionEnabled()) {

				return 1;
				// Do Not Track is enabled!

			} else {

				return 0;
				// Do Not Track is disabled!

			}

		} else {

			// Do Not Track is not supported
			return 0;
		}	
	}
}

// ===================================

function GTSendEvent(action, label, value, cat)                 // if this format changes, change it in the setTimeout() bit below also...
{
                // Value = integer
    
    if(GAUAvar!="")
    {
        var cc = "";
        if(cat=="" || typeof cat=="undefined")
        {   cc = "(none)";  }
        else
        { cc = cat; }    
        
        
        if(sendevnt == 1)
        {
            if(TestOptInOut() == 1)	
            {
                if(TestDoNotTrack() == 0)
                {				
                    if(getCookie("doinitGA")=="10")
                    {
                        SendInitPVGA();
                        SendInitPVFB();
                        setCookie("doinitGA","0", -30);				// remove cookie
                    }

                    
                    // RETRY in 1sec if nowsendga==0 --- max wait is 3 seconds, coz set by timeout in sendGAPageview(0)
                    
                    if(nowsendga==0)
                    {
                        setTimeout(function(){ GTSendEvent(action, label, value, cat) }, 1000);
                    }
                    else
                    {
                        if(nowsendga==1)
                        {
                            ancana('send', {
                                                hitType: 'event',
                                                eventCategory: cc,
                                                eventAction: action,
                                                eventLabel: label,
                                                eventValue: value
                                            });                        
                        }
                        else
                        {
                            var q = genGAstringFirst(GAUAvar);
                            
                            // this is the actual data bit
                            q+="&t=event";
                            q+="&ea="+action;
                            q+="&ec="+cc;
                            if(label!='')
                            {
                                q+="&el="+label;
                            }
                            if(value!='')
                            {
                                q+="&ev="+value;
                            }
                            

                            // This must go at the end of the string
                            q += genGAstringLast();
                            
                            sendGTstuff(q);
                            
                        }
                    }
                }
            }	
        }
    }
}

// ===================================

function FBSendEvent(p1, p2, p3)
{
    if(FBpixel!="")
    {
        if(sendevnt == 1)
        {
            if(TestOptInOut() == 1)	
            {
                if(TestDoNotTrack() == 0)
                {

                    if(getCookie("doinitGA")=="10")
                    {
                        SendInitPVGA();
                        SendInitPVFB();
                        setCookie("doinitGA","0", -30);				// remove cookie
                    }


                    if(whichFBevent()==1)
                    {	
                        if (p3=='')
                        {
                            fbq(p1,p2);
                        }
                        else
                        {
                            fbq(p1,p2,p3);
                        }
                    }
                    else
                    {
                        if (p3=='')
                        {
                            var str='';
                        }
                        else
                        {
                            var str = "&"+Object.keys(p3).map(function(key){return 'cd'+encodeURIComponent("["+key+"]") + '=' + encodeURIComponent(p3[key]); }).join('&');	
                        }

                        var x= "id="+FBpixel+"&ev="+p2+str+"&noscript=1";
                        //console.log(x);
                        SendFBstuff(x);	
                    }
                }
            }	
        }
    }
}

// ===================================

function whichFBevent()
{
    if(doligaa==0)
    {
        return 1;
    }
    else
    {
        if(typeof window.fbq=="undefined")
        { return 0;  }
        else
        { return 1; }
    }
}

// ===================================

function testURL(urla)
{
		var rr = 0;

		var request = $.ajax({
			url: urla,
			crossDomain: true,				
			type: "POST",
			success: function(){ rr = 1; },
			fail: function(){ rr = 0;   }
		});
		
		return rr;
}

// ===================================

function genGAstringFirst(uu)
{
	var cid;
	
	if(getCookie("anccid")=='')
	{
		var a = Math.floor((Math.random() * 10000000000) + 1);
		var b = Math.floor((Math.random() * 10000000000) + 1);
		cid = a.toString() + "." + b.toString();
		
		setCookie("anccid",cid, (365*2));				// 2 yr expiration date
	}
	else
	{
		cid = getCookie("anccid");
	}
	
	var q = "v=1&tid="+uu+"&cid="+cid+"&aip=1&u=en-gb&de=UTF-8&ds=web&dt="+encodeURIComponent(document.title)+"&dl="+encodeURIComponent(window.location.href);
			
	// &dp="+GetGAPageName()+"   ---> seemed to be removed from collect GA call!
	return q;
}

// ================================

function genGAstringLast()
{
	// z: sed to send a random number in GET requests to ensure browsers and proxies don't cache hits. It should be sent as the final parameter of the request since we've seen some 3rd party internet filtering software add additional parameters to HTTP requests incorrectly. This value is not used in reporting.
			
	var t = "&z="+(Math.floor(Math.random() * 10000000) + 1);		
	return t;
}

// =================================

function sendGTstuff(q)
{
		var request = $.ajax({
			url: NGXurl+'lazyimgga/',
			crossDomain: true,				
			type: "GET",
			data: q
		});
}

// ================================

function SendFBstuff(xx)
{
	var request = $.ajax({
			url: NGXurl+'lazyimgfba/',
			crossDomain: true,				
			type: "GET",
			data: xx
		});
}

// ================================
function GetGAPageName()
{
	// this is:-
					
				// Minus query string
				// https://www.w3schools.com/jsref/tryit.asp?filename=tryjsref_loc_pathname&gh=1   --->   /jsref/tryit.asp	

	return encodeURIComponent(document.location.pathname);
}

// ================================

function GetFullURLNoQS()
{
	// for fb proxy hack
	// full url but no QS
	
	return encodeURIComponent([location.protocol, '//', location.host, location.pathname].join(''));
}

// ================================



