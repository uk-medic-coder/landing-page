
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Dont forget to set the CRON SCRIPT to cleanup the txt.txt
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

/*

1. Set vars in index.php for this

2. Ensure cleanupCron.php is set for each domain ... Else txt.txt will get too big

3. And maybe generatefake entries script if wanted

*/

var SPminnottorotate = 10;			// this is the cut off for only going thru the cycle once (ie. below this just go thru once and not repeat)
var SPshowchiplen = 5000;			// 5s for the popup to show
var SPtotalinterval = 10000;			// 9s between popup to popup (total) == 4s rest

var SParray = [];
var SPcurloc = 0;
var SPgiveup = 0;
var SPshownone = 0;
var SPman = 1;
var SPwoman = 1;
var SPtimerID = -99999;
var SPgettxtID = 0;
var SPtxtloadcnt = 0;
var SPshowncnt = 0;
var SPclickclose = 0;
var ipfail = 0;
var gtyp = 0;
var gnm = '';
var gsx = 0;
var ipurl = [];
var spcountry = '';
var spcity = '';

var iploc1 = 'https://ipapi.co/json/';	
ipurl[1] = 'https://api.ipdata.co?api-key=123456';
ipurl[2] = 'https://api.ipdata.co/?api-key=123456';	



// Preload the avatars

var spmanimgs = new Array();
var spwomanimgs = new Array();
for (var i = 1; i < 4; i++) {
	spmanimgs[i] = new Image();
	spmanimgs[i].src = thissiteurl+"/img/spimgs/man"+i+"-min.png";
	spwomanimgs[i] = new Image();
	spwomanimgs[i].src = thissiteurl+"/img/spimgs/woman"+i+"-min.png";
}


// =====================================

$(window).on('load', function () {

 if (DoSocialProof==1)
	{
        $.ajaxSetup({ cache: false });                  // prevent caching of .txt file
        
        SPgettxtID = setInterval(function() { SPloadfile(); }, 4 * 1000); //in secs
		// call now
        
		SPloadfile();
	}
	 
	// =======================
	
	$("#spcloseaction").click(function(){
			closeSPtimer();
			SPclickclose = 1;
	});
});

// =====================================

function SPloadfile()
{
	if (SPtxtloadcnt<5)
	{
		 $.ajax({  
		   url: thissiteurl+'/socialprooftxt/txt.txt',  
          cache: false,                     // stop cached version being used
		   dataType: "text",  
		   success: function(data) {

               clearInterval(SPgettxtID);

               // split data into an array
				SParray = data.split(/[\r\n]+/);				// regex for all CRLF types, linux, windows etc...			
				SPcurloc = SParray.length-1;
				
                SPdisplay();					// coz wait for 7s or so, do it now
				setSPtimer();
			},
			fail: function (jqXHR, status, err) {
					SPtxtloadcnt++;
			}
		});  	
	}
	else
	{
		// SEND ERROR - NOT CURRENTLY DONE
		clearInterval(SPgettxtID);
	}
}

// =====================================

function setSPtimer()
{
	//var c = Math.floor(Math.random() * 11)+7;
	SPtimerID = setInterval(function() { SPdisplay(); }, SPtotalinterval); //in secs
}

function closeSPtimer()
{
	clearInterval(SPtimerID);
	$(".spchip").fadeOut(500);	
}

// =====================================

function SPdisplay()
{
	// 1st check has GDPR bar gone?
	
	var prceed = 1;			// set to zero and do GDPR check if want to wait for GDPR bar to disappear
	//var prceed = getCookie("cookiecnsnt")=="yes" || dogdpr==0;
	
	
	var i = 0;
	var flg = 0;
	
	if (DoSocialProof==1)
	{
		if (prceed==1)
		{
			// Find a non blank row - if cant find one, give up for this session as something gone wrong
			
			if(SPgiveup==0)
			{	
				SPcurloc--;
				if(SPcurloc < 0)
				{
					SPcurloc = SParray.length-1;
					
					if(SPshowncnt<SPminnottorotate)
					{
						flg = 1;
						SPgiveup = 1;
					}
					else
					{
						SPshowncnt = 0;
					}
				}		
				
				while (flg==0) 
				{
					if(SParray[SPcurloc] != undefined && SParray[SPcurloc].length>8)
					{
						flg = 1;
					}
					else
					{
						SPcurloc--;
						if(SPcurloc < 0)
						{
							if(SPshownone==0)
							{
								flg = 1;
								SPgiveup = 1;
							}
							else
							{
								SPcurloc = SParray.length-1;
							}						
						}
					}
				}
			}
			
			// OK to proceed?
			
			if(SPgiveup==0)
			{
				SPshownone = 1;
				
				SPshowncnt++;
				
				// do sp box text img etc...
				
				var ps = SParray[SPcurloc].split(",");
				var r1 = '';
				var r2 = '';
				var r3 = '';
				
				// do row 1
				if(ps[2]=='')
				{ r1 = 'Someone'; }
				else
				{	r1 = ps[2];	}
				
				r1+=' from ';

				var cmma='';

				if(ps[3]=='' && ps[4]=='')
				{
					r1 += 'Unknown';
				}
				else
				{
					if(ps[3]!='')
					{	
						r1+=ps[3];
						cmma=', ';
					}

					if(ps[4]!='')
					{	r1+=cmma+ps[4];	}
				}

				switch(ps[0]) {
				case "1":
					r2 = SPoutcome1;
				break;
				case "2":
					r2 = SPoutcome2;
				break;
				case "3":
					r2 = SPoutcome3;
				break;
				}

				switch(ps[5]) {
				case "1":
					$("#spimg").attr("src",thissiteurl+"/img/spimgs/man"+SPman+"-min.png");
					SPman++;
					if (SPman>3)
					{	SPman = 1;	}
				break;
				case "2":
					$("#spimg").attr("src",thissiteurl+"/img/spimgs/woman"+SPwoman+"-min.png");
					SPwoman++;
					if (SPwoman>3)
					{	SPwoman = 1;	}
				break;
				}			
				
				// date
				// Always use UTC
				
				var d = MyUTCDateTime()/1000;
				var dds = (d - ps[1]);				// in seconds now

				if(dds<60)
				{ r3 = dds + ' seconds';	}
				
				if(dds>=60 && dds < 120)
				{ r3 = Math.floor(dds/60) + ' minute';	}				
					
				if(dds>=120 && dds < 3600)
				{ r3 = Math.floor(dds/60)  + ' minutes';	}	
				
				if(dds>=3600 && dds < 7200)
				{ r3 = Math.floor(dds/3600)  + ' hour';	}				
					
				if(dds>=7200 && dds < 86400)
				{ r3 = Math.floor(dds/3600) + ' hours';	}			
				
				if(dds>=86400 && dds < 172800)
				{ r3 = Math.floor(dds/86400) + ' day';	}				
					
				if(dds>=172800 && dds < 2592000)
				{ r3 = Math.floor(dds/86400) + ' days';	}										

				if(dds>=2592000)
				{ r3 = '1 month or so';	}							
				
	

				// Show now
				$("#sptxtrow1").html(r1);
				$("#sptxtrow2").html(r2);
				$("#sptxtrow3").html(r3+" ago");			
				
				$(".spchip").show();
				
				setTimeout(function()
				{ 
					$(".spchip").fadeOut(400);
				}, SPshowchiplen);
			}	
			// Nothing after here
		}
	}
}

// =====================================

function MyUTCDateTime()
{
	var date = new Date(); 
	var d =  Date.UTC(date.getUTCFullYear(), date.getUTCMonth(), date.getUTCDate(),date.getUTCHours(), date.getUTCMinutes(), date.getUTCSeconds());
	
	return d;
}
	
// =====================================

function ReplaceCommaBrackets(s)
{
	var regex = new RegExp(',', 'g');				// cross compatible way to replace comma with hyphen
	var d = s.replace(regex, '-');
	
	// Now remove stuff in between () brackets, as some JSON data returned has it in, eg- Toronto (Old toronto)
	
	d = d.replace(/ *\([^)]*\) */g, "");
	
    var rs = d.split(" ");
    
    if(rs.length>3)
    {
    	// 3 words max
    	var res = rs[0]+" "+rs[1]+" "+rs[2];    
    }
    else
    {
    	var res = d;
    }	
	

	return res.trim();
}


// =====================================

function sendSPData(typ,nm,sx)
{
	gtyp = typ;
	gnm = nm;
	gsx = sx;
	
	if(getCookie('sputy')=='')
	{
		getIP01(1); 			// 1st attempts
	}
	else
	{
		// already stored in cookies, dont need to resend
		ajaxSPdata(ReplaceCommaBrackets(getCookie('sputy')), ReplaceCommaBrackets(getCookie('spcnty')));
	}
}

// =====================================

function getIP01(vl)
{
    // DO CITY ON 1st ATTEMPT
        // IF CITY DOES NOT WORK, 'REGION' IS DONE ON 2ND ATTEMPT
    
    
	$.getJSON(iploc1, function(data)
	{
	}).done(function(data) 
	{
		if(data.hasOwnProperty('city') && data.hasOwnProperty('country_name'))
		{
			spcity = data.city;
			spcountry = data.country_name;
			
			ajaxSPdata(ReplaceCommaBrackets(spcity), ReplaceCommaBrackets(spcountry));
		}
		else
		{
			if(vl==2)
			{
				sendGError(2);	
				// try next one
				getIP02(1);
			}
			else
			{
				setTimeout(function(){  getIP01(vl+1);  }, 2000);					
			}	
		}
	}).fail(function(jqXhr, textStatus, error)
	{
			if(vl==2)
			{
				sendGError(2);
				// try next one
				getIP02(1);
			}
			else
			{
				setTimeout(function(){  getIP01(vl+1);  }, 2000);					
			}
	});			
}

// =====================================

function getIP02(vl)
{
	$.getJSON(ipurl[vl], function(data)
	{
	}).done(function(data) 
	{
		// coz sometimes only country!
		// and this is last chance
		
		if(data.hasOwnProperty('country_name'))
		{
			spcountry = data.country_name;
			
			if(data.hasOwnProperty('region'))
			{
				spcity = data.region;
				ajaxSPdata(ReplaceCommaBrackets(spcity), ReplaceCommaBrackets(spcountry));
			}
			else
			{
				if(vl==1)
				{
					// try again
					setTimeout(function(){  getIP02(vl+1);  }, 2000);	
				}
				else
				{
					// just use this
					spcity = '';				// keep blank and just use country name
					ajaxSPdata(ReplaceCommaBrackets(spcity), ReplaceCommaBrackets(spcountry));					
				}
			}
		}
		else
		{
			if(vl==2)
			{
				sendGError(3);	
			}
			else
			{
				setTimeout(function(){  getIP02(vl+1);  }, 2000);					
			}	
		}
	}).fail(function(jqXhr, textStatus, error)
	{
			if(vl==2)
			{
				sendGError(3);
			}
			else
			{
				setTimeout(function(){  getIP02(vl+1);  }, 2000);					
			}
	});			
}

// =====================================

function ajaxSPdata(city, cnty)
{
	var adjnm = ReplaceCommaBrackets(gnm);
	var er = 0;
	
	
	if(adjnm=='Someone' || adjnm=='')
	{
		// ignore if someone from no IP
		if(city=='' || cnty=='')
		{
			er = 1;
		}
		
		if(city.toLowerCase()=='unknown' || cnty.toLowerCase()=='unknown')
		{
			er = 1;
		}
	}
	
	// proceed
	
	if(er==0)
	{
		// store details in cookie for future use
		
		setCookie('sputy', city, '');				// session cookies
		setCookie('spcnty', cnty, '');

		var tt = (MyUTCDateTime()/1000).toString().trim();				// millisecs to secs since epoch
		var f = 'lstm='+tt+'&vv='+gtyp+","+tt+","+adjnm+","+city+","+cnty+","+gsx;

		var request = $.ajax({
			url: thissiteurl+"/socialproofphp/storeSP.php",
			type: "POST",
			data: f
		});
	}
	
}

// =====================================






