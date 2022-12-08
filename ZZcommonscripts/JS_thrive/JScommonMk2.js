function GetQSParam(param)
{  
    var url = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');  
    for (var i = 0; i < url.length; i++) {  
        var urlparam = url[i].split('=');  
        if (urlparam[0] == param) {  
            return urlparam[1];  
        }  
    }  
} 

// ---------------------------------------

function getQueryVariable(variable)
{
       var query = window.location.search.substring(1);
       var vars = query.split("&");
       for (var i=0;i<vars.length;i++) {
               var pair = vars[i].split("=");
               if(pair[0] == variable){return pair[1];}
       }
       return(false);
}


function GetFullQueryString()
{
    var qs = window.location.search;
    return qs;
}

// ---------------------------------------

function BasicValidateEmail(email) 
{
    // only basic @ test
    var re = /\S+@\S+/;
    return re.test(email);
}
	
// ---------------------------------------

