function getExpDate(days, hours, minutes) {
 var expDate = new Date();
 if (typeof days == "number" && typeof hours == "number" && typeof minutes == "number") {
  expDate.setDate(expDate.getDate() + parseInt(days));
  expDate.setHours(expDate.getHours() + parseInt(hours));
  expDate.setMinutes(expDate.getMinutes() + parseInt(minutes));
  return expDate.toUTCString();
 }
}

// utility function called by getCookie()
function getCookieVal(offset) {
 var endstr = document.cookie.indexOf (";", offset);
 if (endstr == -1) {
  endstr = document.cookie.length;
 }
 return decodeURI(document.cookie.substring(offset, endstr));
}

// primary function to retrieve cookie by name
function getCookie(name) {
 var arg = name + "=";
 var alen = arg.length;
 var clen = document.cookie.length;
 var i=0;
 while (i < clen) {
  var j = i + alen;
  if (document.cookie.substring(i, j) == arg) {
   return getCookieVal(j);
  }
  i = document.cookie.indexOf(" ", i) + 1;
  if (i == 0) break;
 }
}

// store cookie value with optional details as needed
function setCookie(name, value, expires, path, domain, secure) {
 //alert('');
 document.cookie = name + "=" + encodeURI(value) + 
 ((expires) ?  "; expires=" + expires : "") + 
 ((path) ?  "; path=" + path : "") + 
 ((domain) ?  "; domain=" + domain : "") + 
 ((secure) ?  "; secure" : "");
}

// remove the cookie by setting an ancient expiration date
function deleteCookie(name, path, domain) {
 if (getCookie(name)) {
  document.cookie = name + "=" + ((path) ? "; path=" + path : "") + ((domain) ? "; domain=" + domain : "") + "; expires=Thu, 01-Jan-70 00:00:01 GMT";
 }
}
