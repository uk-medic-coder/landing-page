
// switches elements according to what qs says

var swbck = '';
var swheader = '';
var swfbad = '';
var imgdir = thissiteurl+"/img/";

var imagearr = ["bckok/sky2.jpg","bckok/sky2.jpg","matching/img00.png"];

var defPreH1 = '<u>ATTENTION</u>:&nbsp;&nbsp;&nbsp;If You Want To Manifest Abundance...';
var defPreH2 = 'xxx';

var defH1 = 'ARE YOU BLOCKING YOUR ABUNDANCE FROM MANIFESTING?<div class="headdivtxtline2">"SCIENCE REVEALS ANSWER..."</div>';	
var defH2 = 'DO YOU HAVE BLOCKS THAT ARE STOPPING YOUR ABUNDANCE FROM MANIFESTING?<div class="headdivtxtline2">"Science Reveals Answer..."</div>';	

var defFBad = imgdir+imagearr[2];

// Now preload the images

var pgimgs = new Array();
for (var i = 0; i < imagearr.length; i++) {
	pgimgs[i] = new Image();
	pgimgs[i].src = imgdir+imagearr[i];
	//alert(imgdir+imagearr[i]);
}



// Look at QS vars

//utm_campaign = txtXY -----   X = headline (0-9) ; Y = background (0-9)  txt=  campaign, eg-3wd  
//utm_source=fb (or other --- if FB, shows FB notice at the bottom of the page)
//utm_content = fb image to match (01, 02,10 etc...)


var qs = getQueryVariable('utm_campaign');
if(qs!=false)
{
	var swbck = qs.slice(-1);
	var swheader = qs.slice(-2,-1);
}

qs = getQueryVariable('utm_content');
if(qs!=false)
{
	var swfbad = qs;
}


// ========================
// ========================

function switchBackdrop()
{
	var df = 0;
	
	if(swbck!='')
	{
		switch(swbck)
		{
			case "0":
				df = 1;
				break;
			default:
				df = 1;
				break;
		}
	}
	else
	{
		// set defaults
		df = 1;
	}
	// **********************
	// Done this way coz IE and safari didnt do background images otherwise!!!!
	// **********************
	if(df==99)
	{
		var element = document.getElementsByTagName("BODY")[0];
		element.classList.add("RTblueback");				
	}

	if(df==1)
	{
		document.body.style.backgroundImage = "url('"+imgdir+imagearr[0]+"')";
		document.body.style.repeat = 'no-repeat';
		document.body.style.backgroundSize = 'cover';
		document.body.style.backgroundAttachment = 'fixed';		
	}
	
}

// ========================

function switchHeadline()
{

	if(swheader!='')
	{
		switch(swheader)
		{
			case "1":
				document.getElementById("headdivtext").innerHTML = defH1;
				document.getElementById("preheaddivtext").innerHTML = defPreH1;				
				break;
			case "2":
				document.getElementById("headdivtext").innerHTML = defH2;
				document.getElementById("preheaddivtext").innerHTML = defPreH1;				
				break;
			default:
				document.getElementById("headdivtext").innerHTML = defH1;
				document.getElementById("preheaddivtext").innerHTML = defPreH1;	
				break;
		}
	}
	else
	{
		// set defaults
		document.getElementById("headdivtext").innerHTML = defH1;
		document.getElementById("preheaddivtext").innerHTML = defPreH1;			
	}
}

// ========================

function switchImage()
{

	if(swfbad!='')
	{
		document.getElementById("mainfbimg").src = imgdir+"matching/img"+swfbad+".png";
	}
	else
	{
		// set defaults
		document.getElementById("mainfbimg").src = defFBad;
	}
}
