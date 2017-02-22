/* Globals */

var EntryChangeCounter;
var EntryChangeCount = 0;


// INIT!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// When page loads - get details for today
//function init() {
//  // This is a way getting the makerequest function to work when diary app loads
//  showHideItem2(); // Hide Show elements as per cookie values to restore previous state
//} // End function


// 20/09/15: Handle hiding 'related' DIV.
//$('.btnHide a').live('click', function(e) {
//  if ( $('.tblRel').is(':visible') ) {$('.tblRel').hide();} 
//  else {$('.tblRel').show();}
//  e.preventDefault();
//});


function entchtimer() {
  EntryChangeCount++;
  $('#upd_btn').val('Data Changed Update? ' + EntryChangeCount);
} // End entchtimer() function.



// Hiding/Showing Changedstatus button - invoked when there is a change in the data entered.
function showhidechangedstatus(wh) {
  var obj = document.getElementById('upd_btn').style;
  if (wh == "visible") { // Change was detected!
    clearInterval(EntryChangeCounter);
    EntryChangeCounter = setInterval("entchtimer()", 1000);
    obj.backgroundColor='#c00';
    obj.color='#fff';
  } else {
    clearInterval(EntryChangeCounter);
    EntryChangeCount=0;
    document.getElementById('upd_btn').value='Update!';
    obj.backgroundColor='buttonface';
    obj.color='#000';
  }
} // End function.


// USED
// Called on page load. Restore previous state:
//function showHideItem2() {
//  var j = 1;
//  for (i in arritems) {showHideItems3(arritems[j-1], j, arritems2[j-1]); j++;}
//} // End function.


// USED
// HANDLE THE HIDING AND SHOWING OF ELEMENTS - called by the previous function
//function showHideItems3(obj, i, objtxt) {
//  var obj3 = document.getElementById(obj+"hidebar_btn");
//  var dowhat = getCookie(obj);
//  if (typeof(dowhat)=='undefined' || dowhat=='block') {
//    eval('document.getElementById(obj).style.display = "block";');
//    obj3.innerHTML = "Hide " + objtxt;
//    obj3.style.backgroundColor = 'lime'; // Indicate visually that DIV is visible.
//  } else {
//    eval('document.getElementById(obj).style.display = "none";'); // Hide!
//    obj3.innerHTML = "Show " + objtxt;
//  } // End if.
//} // End function.
