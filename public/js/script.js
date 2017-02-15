/* Globals */

var EntryChangeCounter;
var EntryChangeCount = 0;


/*
FUNCTIONS IN THIS FILE:
=======================

init()


gotoday(serverPage)

showHideCal()

showDatePicker()
getTime()

displayEventsDIV(firstload)
addeventfrm()
addevent()
editeventfrm(eventid)
editevent(eventid)
closeEventEditFrm()



createtime()
loadTime2(hoursss)
changeCol()
showhgoLastYear(dt, month, year)
idechangedstatus(wh)
isShowHideStatusbuttonVisible()

goLastMonth(dt, month, year)
goNextMonth(dt, month, year)
goNextYear(dt, month, year)
goToMonth(dt, month, year)
goToYear(dt, month, year)
goToDay(dt, month, year)

showHideItem(obj, objtxt)
showHideItem2()
showHideItems3(obj, i, objtxt)

ShowHideBox(objnum)
ShowBox(obj)
HideBox(obj)

*/
String.prototype.capitalize = function() {
  return this.replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
};


// INIT!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// When page loads - get details for today
function init() {
  // This is a way getting the makerequest function to work when diary app loads
  showHideItem2(); // Hide Show elements as per cookie values to restore previous state
  loadtime();      // JavaScript timer

} // End function





// Calendar functions
// AJAX Calendar functions

$('#goLastMonth').live('click', function(){
  var splittxt = $(this).attr('title2').split('|');
  var month = parseInt(splittxt[1], 10);
  var year  = parseInt(splittxt[2], 10);
  if(month== 1) {
    --year;
    month= 13;
  }
  month--;
  $.ajax({
    type:'get',
    url:'_inc/func_showcal.php',
    data:'dt=' + splittxt[0] + '&m=' + month + '&y=' + year,
  
    success: function (data){
      //alert('dt=' + splittxt[0] + '&m=' + month + '&y=' + parseInt(splittxt[2]));
      $('#dtPck').html(data).fadeIn();
    }
  });
});

$('#goToMonth').live('change', function(){
  var splittxt = $(this).attr('title2').split('|');
  //alert(splittxt);
  var month = parseInt($(this).val(), 10) + 1;
  //alert(month);
  $.ajax({
    type:'get',
    url:'_inc/func_showcal.php',
    data:'dt=' + splittxt[0] + '&m=' + month + '&y=' + parseInt(splittxt[2]),
    
    success: function (data){
      //alert('dt=' + splittxt[0] + '&m=' + month + '&y=' + splittxt[2] );
      $('#dtPck').html(data).fadeIn();
    }
  });
});

$('#goNextMonth').live('click', function(){
  var splittxt = $(this).attr('title2').split('|');
  //alert(splittxt);
  var month = parseInt(splittxt[1], 10);
  var year  = parseInt(splittxt[2], 10);
  if(month== 12) {
    ++year;
    month= 0;
  }
  month++;
  //alert(splittxt[2]);
  $.ajax({
    type:'get',
    url:'_inc/func_showcal.php',
    data:'dt=' + splittxt[0] + '&m=' + month + '&y=' + year,
    
    success: function (data){
      $('#dtPck').html(data).fadeIn();
    }
  });
});

$('#goThisMonth').live('click', function(){
  var splittxt = $(this).attr('title2').split('|');
  var month = parseInt(splittxt[1], 10);
  var year  = parseInt(splittxt[2], 10);
  $.ajax({
    type:'get',
    url:'_inc/func_showcal.php',
    data:'dt=' + splittxt[0] + '&m=' + month + '&y=' + year,
    
    success: function (data){
      $('#dtPck').html(data).fadeIn();
    }
  });
});

$('#goLastYear').live('click', function(){
  var splittxt = $(this).attr('title2').split('|');
  //alert(splittxt);
  var year = parseInt(splittxt[2], 10);
  //year--;
  $.ajax({
    type:'get',
    url:'_inc/func_showcal.php',
    data:'dt=' + splittxt[0] + '&m=' + splittxt[1] + '&y=' + --year,
    
    success: function (data){
      $('#dtPck').html(data).fadeIn();
    }
  });
});

$('#goToYear').live('change', function(){
  var splittxt = $(this).attr('title2').split('|');
  var year = parseInt($(this).val(), 10) ;
  //alert(month);
  $.ajax({
    type:'get',
    url:'_inc/func_showcal.php',
    data:'dt=' + splittxt[0] + '&m=' + parseInt(splittxt[1], 10) + '&y=' + year,
    
    success: function (data){
      $('#dtPck').html(data).fadeIn();
    }
  });
});

$('#goNextYear').live('click', function(){
  var splittxt = $(this).attr('title2').split('|');
  //alert(splittxt);
  var year = parseInt(splittxt[2], 10);

  //alert(splittxt[2]);
  $.ajax({
    type:'get',
    url:'_inc/func_showcal.php',
    data:'dt=' + splittxt[0] + '&m=' + parseInt(splittxt[1], 10) + '&y=' + ++year,
    
    success: function (data){
      $('#dtPck').html(data).fadeIn();
    }
  });
});

$('#goThisYear').live('click', function(){
  var splittxt = $(this).attr('title2').split('|');
  //alert(splittxt);
  var year = parseInt(splittxt[2], 10);
  $.ajax({
    type:'get',
    url:'_inc/func_showcal.php',
    data:'dt=' + splittxt[0] + '&m=' + parseInt(splittxt[1], 10) + '&y=' + year,
    
    success: function (data){
      $('#dtPck').html(data).fadeIn();
    }
  });
});




function calNav(act, dt, month, year) {

  switch (act) {

    case "goLastMonth":
      // If the month is January, decrement the year
      if(month== 1) {
        --year;
        month= 13;
      }
      urlstr = dt;
      month = month - 1;
      break;

    case "goNextMonth":
      // If the month is December, increment the year
      if(month== 12) {
        ++year;
        month= 0;
      }
      urlstr = dt;
      month = month + 1;
      break;

    case "goLastYear":
      urlstr = dt;
      year = year - 1;
      break;

    case "goNextYear":
      urlstr = dt;
      year = year + 1;
      break;

    case "goToMonth":
      urlstr = dt;
      break;

    case "goToYear":
      urlstr = dt;
      break;
    // 
    case "goToDay":
      var dt_split = dt.split("-");
      urlstr = dt_split[0]+'-'+dt_split[1]+'-'+dt_split[2];
      break;
  } // End switch.

  $.ajax({
    type:'get',
    url:'_inc/func_showcal.php',
    //context:'#editrow',
    data:'dt=' + urlstr + '&m=' + month + '&y=' + year,
    
    success: function (data){
      $('#dtPck').html(data).fadeIn();
    }
  });
}

$('.closebtn').live('click', function(){
  $('#dtPck').toggle('slow');
});


// 20/09/15: Handle hiding 'related' DIV.
$('.btnHide a').live('click', function(e) {
  //$('.tblRef').css('display','block');
  //alert($('.tblRel').is(':visible'));
  if ( $('.tblRel').is(':visible') ) {
    $('.tblRel').hide();
  } else {
    $('.tblRel').show();
  } // End if.
  e.preventDefault();
});


function focus_txtarea() {
  // Problem with giving textarea focus if it is in a layer that is not visible
  //alert(document.getElementById('diarym').style.display);
  //if (document.getElementById('diarym').style.display == "block") {document.getElementById("txtInfo1").focus();}
} // End function.

//var shwDiv = true;
//function hideShowDIV() {
//}


// Show current time - will call every second to create a digital clock effect
function createtime() {
  var time    = new Date();
  var hours   = time.getHours();
  var minutes = time.getMinutes()
  var seconds = time.getSeconds();
  if (minutes<=9) {minutes="0"+minutes;}
  if (seconds<=9) {seconds="0"+seconds;}
  var ctime=""+hours+":"+minutes+":"+seconds+""+"";
  if (document.all) {document.all.clock.innerHTML=ctime;}
  else if (document.getElementById) {document.getElementById("clock").innerHTML=ctime;}
  else {document.write(ctime);}
 
  if (ctime == '00:00:00') {location.reload();} // Reload page at midnight!
} // End createtime() function.
if (!document.all&&!document.getElementById) {createtime();}


function loadtime() {
  if (document.all||document.getElementById) {setInterval("createtime()",1000);}
} // End loadtime() function.


function entchtimer() {
  EntryChangeCount++;
  $('#upd_btn').val('Data Changed Update? ' + EntryChangeCount);
  //console.log('Data Changed Update? ' + EntryChangeCount);
} // End entchtimer() function.



// Hiding/Showing Changedstatus button - invoked when there is a change in the data entered.
function showhidechangedstatus(wh) {
  // wh is either visible (data has changed) or hidden (has not)
  //eval("document.getElementById(\'changedState\').style.visibility=\'"+wh+"\';");
  // Changed: Now we use a single button whose caption and style will change to indicate if entry text has changed
  //alert('');
  var obj = document.getElementById('upd_btn').style;
  if (wh == "visible") { // Change was detected!
    //alert('');
    clearInterval(EntryChangeCounter);
    //EntryChangeCount=0;
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
function showHideItem2() {
  var j = 1;
  for (i in arritems) {
    showHideItems3(arritems[j-1], j, arritems2[j-1]); j++;
  } // End for.
} // End function.



// USED
// HANDLE THE HIDING AND SHOWING OF ELEMENTS - called by the previous function
function showHideItems3(obj, i, objtxt) {
  //alert('');
  //var obj2 = eval('document.getElementById(obj).style.display;');
  //var obj3 = eval('document.getElementById(obj+"hidebar_btn");');
  var obj3 = document.getElementById(obj+"hidebar_btn");
  var dowhat = getCookie(obj);
  if (typeof(dowhat)=='undefined' || dowhat=='block') {
    eval('document.getElementById(obj).style.display = "block";');
    obj3.innerHTML = "Hide " + objtxt;
    obj3.style.backgroundColor = 'lime'; // Indicate visually that DIV is visible.
    //obj3.className = 'updsuccess'; // ??
  } else {
    eval('document.getElementById(obj).style.display = "none";'); // Hide!
    obj3.innerHTML = "Show " + objtxt;
  } // End if.
} // End function.
