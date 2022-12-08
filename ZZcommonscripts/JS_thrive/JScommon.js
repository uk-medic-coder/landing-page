// Common elements to all JS files


var actrckloc = "";

var thisTID = '';
var tmstarted;

// https://github.com/js-cookie/js-cookie
// https://github.com/js-cookie/js-cookie/blob/latest/src/js.cookie.js

/*!
 * JavaScript Cookie v2.2.0
 * https://github.com/js-cookie/js-cookie
 *
 * Copyright 2006, 2015 Klaus Hartl & Fagner Brack
 * Released under the MIT license
 */
;(function (factory) {
	var registeredInModuleLoader = false;
	if (typeof define === 'function' && define.amd) {
		define(factory);
		registeredInModuleLoader = true;
	}
	if (typeof exports === 'object') {
		module.exports = factory();
		registeredInModuleLoader = true;
	}
	if (!registeredInModuleLoader) {
		var OldCookies = window.Cookies;
		var api = window.Cookies = factory();
		api.noConflict = function () {
			window.Cookies = OldCookies;
			return api;
		};
	}
}(function () {
	function extend () {
		var i = 0;
		var result = {};
		for (; i < arguments.length; i++) {
			var attributes = arguments[ i ];
			for (var key in attributes) {
				result[key] = attributes[key];
			}
		}
		return result;
	}

	function init (converter) {
		function api (key, value, attributes) {
			var result;
			if (typeof document === 'undefined') {
				return;
			}

			// Write

			if (arguments.length > 1) {
				attributes = extend({
					path: '/'
				}, api.defaults, attributes);

				if (typeof attributes.expires === 'number') {
					var expires = new Date();
					expires.setMilliseconds(expires.getMilliseconds() + attributes.expires * 864e+5);
					attributes.expires = expires;
				}

				// We're using "expires" because "max-age" is not supported by IE
				attributes.expires = attributes.expires ? attributes.expires.toUTCString() : '';

				try {
					result = JSON.stringify(value);
					if (/^[\{\[]/.test(result)) {
						value = result;
					}
				} catch (e) {}

				if (!converter.write) {
					value = encodeURIComponent(String(value))
						.replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g, decodeURIComponent);
				} else {
					value = converter.write(value, key);
				}

				key = encodeURIComponent(String(key));
				key = key.replace(/%(23|24|26|2B|5E|60|7C)/g, decodeURIComponent);
				key = key.replace(/[\(\)]/g, escape);

				var stringifiedAttributes = '';

				for (var attributeName in attributes) {
					if (!attributes[attributeName]) {
						continue;
					}
					stringifiedAttributes += '; ' + attributeName;
					if (attributes[attributeName] === true) {
						continue;
					}
					stringifiedAttributes += '=' + attributes[attributeName];
				}
				return (document.cookie = key + '=' + value + stringifiedAttributes);
			}

			// Read

			if (!key) {
				result = {};
			}

			// To prevent the for loop in the first place assign an empty array
			// in case there are no cookies at all. Also prevents odd result when
			// calling "get()"
			var cookies = document.cookie ? document.cookie.split('; ') : [];
			var rdecode = /(%[0-9A-Z]{2})+/g;
			var i = 0;

			for (; i < cookies.length; i++) {
				var parts = cookies[i].split('=');
				var cookie = parts.slice(1).join('=');

				if (!this.json && cookie.charAt(0) === '"') {
					cookie = cookie.slice(1, -1);
				}

				try {
					var name = parts[0].replace(rdecode, decodeURIComponent);
					cookie = converter.read ?
						converter.read(cookie, name) : converter(cookie, name) ||
						cookie.replace(rdecode, decodeURIComponent);

					if (this.json) {
						try {
							cookie = JSON.parse(cookie);
						} catch (e) {}
					}

					if (key === name) {
						result = cookie;
						break;
					}

					if (!key) {
						result[name] = cookie;
					}
				} catch (e) {}
			}

			return result;
		}

		api.set = api;
		api.get = function (key) {
			return api.call(api, key);
		};
		api.getJSON = function () {
			return api.apply({
				json: true
			}, [].slice.call(arguments));
		};
		api.defaults = {};

		api.remove = function (key, attributes) {
			api(key, '', extend(attributes, {
				expires: -1
			}));
		};

		api.withConverter = init;

		return api;
	}

	return init(function () {});
}));

// ===============================
// ===============================

function setCookie(cname,cvalue,exdays)
{
	// exdays:  '' to set a session cookie, >1 to set in days, minus figure to remove
	if (exdays=='')
	{
		Cookies.set(cname, cvalue);
	}
	else
	{
		if(exdays<0)
		{
				Cookies.remove(cname);
		}
		else
		{
			Cookies.set(cname, cvalue, { expires: exdays });
		}
	}
}

// ===============================
function getCookie(cname)
{
	var aa = Cookies.get(cname);
	if(aa==undefined)
	{		return '';	}
	else
	{		return aa;	}
}
// ===============================
function GetTimeElapsedFromInitial()
{
	// uses a cookie for time elapsed from initial visit
	
	var sttm = 0;
	var secs = 0;
	
	if(getCookie("trckstrttmvisit",0)=="")
	{
			var d = new Date();
			sttm = Math.round(d.getTime() / 1000);			
			setCookie("trckstrttmvisit", sttm, 0);
			secs = 0;						// as 1st visit, sets the cookie
	}
	else
	{
			var d = new Date();
			var edt = Math.round(d.getTime() / 1000);			
			sttm = getCookie("trckstrttmvisit",0);
			secs = Math.round(edt - sttm);
	}	

	return secs;
}
// ===============================
function getbasesiteURL()
{
	var url = window.location.href
	var arr = url.split("/");
	var result = arr[0] + "//" + arr[2];	
	return result;
}

// ===============================

function GetThisPageSlug()
{
	return window.location.pathname;	
}
// ===============================
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
// ===============================
function createTID()
{
	var d = new Date();
	var n = d.getTime();
	var chars = "ABCDEFGHIJKLMNOPQRSTUVWXTZ";
	var string_length = 3;
	var randomstring = '';
	for (var i=0; i<string_length; i++) {
	var rnum = Math.floor(Math.random() * chars.length);
			randomstring += chars.substring(rnum,rnum+1);
	}
	return randomstring+n;
}
// ===============================
function getTID()
{
var tt = "";
if(thisTID=="")
{
	if(getCookie("trckactid",0)=="")
	{
			tt = "noTID";
	}
	else
	{
			tt = getCookie("trckactid",0);
	}	
}
else
{
		tt = thisTID;
}	
return tt;
}
// ===============================
function detectIE() {
    var ua = window.navigator.userAgent;
    var msie = ua.indexOf('MSIE ');
    if (msie > 0) {
        return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
    }
    var trident = ua.indexOf('Trident/');
    if (trident > 0) {
        var rv = ua.indexOf('rv:');
        return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
    }
    var edge = ua.indexOf('Edge/');
    if (edge > 0) {
       return parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
    }
    return false;
}
// ===============================
// ===============================

