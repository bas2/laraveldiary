<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Diary - AJAX!</title>

  <!--<link rel="stylesheet" href="js/jquery/colorbox/colorbox.css">-->
  <link rel="stylesheet" href="js/jquery/css/blitzer/jquery-ui-1.10.3.custom.min.css">
  <link rel="stylesheet" href="css/styles.css">

  <script src="js/jquery-1.8.3.min.js"></script>
  <!--<script src="js/jquery.colorbox-min.js"></script>-->
  <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
  <script src="js/cookie.js"></script>
  <script src="js/script.js"></script>
</head>
<body>

<script>
var dtarr = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']; // JavaScript days of week array:

//var arritems  = [bottmenu', 'diarym', 'dimages', 'rw6', 'selects', 'entrytags', 'related']; // JavaScript arrays holding DIV names

//var arritems2 = [Bottom Menu', 'Diary', 'Images', 'Bottom row', 'Dropdowns', 'Tags', 'Related']; // Friendly names // , 'Top Buttons'

// DECLARE (global) CONSTANTS to pass to our JavaScript. These values are provided dynamically using PHP
var Dt2dy2 = '{{ date("D") }}';   // Day today in three letter format eg Tue obviously representing today which is Tuesday.

</script>

<div id="dtPck"><!-- Calendar widget --></div>
{{ App\ProjectsMenu::display() }}
<div class="cont">
  <div id="diarym">

    <ul class="diaryheadings">
      <li id="li_prvwkbtn"></li>
      @foreach (['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $weekday)
      <li id="{{ $weekday }}"><a>&nbsp;</a></li>
      @endforeach
      <li id="li_nxtwkbtn"></li>
    </ul>

    <div style="height:90%;float:left;width:100%;background:rgba(255,0,0,.3;">
      <textarea id="txtInfo1"></textarea> <div class="quickadd">+</div>
      <!-- End DIV dateinfo -->

      <div id="diaryu">
        <span id="LoadText"><!-- Loading Graphic --></span>
        <span id="statusText2"><!--  --></span>
        <span id="statusText">&nbsp;&nbsp;<!--  --></span>
        <span id="clock">&nbsp;&nbsp;<!-- JavaScript clock --></span>
        <!-- Update button --><input type="button" id="upd_btn" value="Update &gt;">
      </div>
    </div>

  </div>
</div>

<div class="cont">
  <div id="rw6">

    <div id="rw7">
      <input id="row_exist" type="checkbox" checked>
      <label for="row_exist" title="Delete this entry if it exists">Row Exists</label>
      <input id="row_imp" name="row_imp" type="checkbox">
      <label for="row_imp" title="Mark this entry as important">Important</label>
    </div>

    <div id="rw8"><span id="ins_upd_dt">&nbsp;<!-- Created/Edit dates --></span></div>

  </div>
</div>


<script>
$(document).ready(function(){
 
  $( document ).tooltip({
    items: ".datehead, [tooltiptxt], [title]",
    content: function() {
      var element = $( this );
      if ( element.is( "[tooltiptxt]" ) ) {
        return element.attr( "tooltiptxt" );
      }
    }
  });
  
  //showHideItem2(); // Hide Show elements as per cookie values to restore previous state
  //loadtime();      // JavaScript timer

  function getData(date) {
    $.ajax({
      type:'get',
      url:'ajax/getday/' + date,
      dataType: 'json',
      success: function (json){
        AjaxTest(json, 'initial');
      } // End ajax.
    });
  }
 
  // Initial load via ajax:
  getData('');

  // When a date header is clicked
  function goToday(sel2) {getData(sel2);}



  function AjaxTest(json, mode) {
    var splitdata=[];
    $.each(json, function(index, element) {
      splitdata[index]=element;
    });

    // 09/05/13 - Are we in the current week?
    var iscurweek      = splitdata[28];//alert(iscurweek);
    var iscurweeksplit = iscurweek.split('-');

    for (i=0; i < 7; i++) {
      var th_dy = dtarr[i]; // Three-letter day of week Mon-Fri
      if (th_dy == splitdata[17].substring(0,3)) {
        $('#'+th_dy).attr('class','').addClass('sel_col') ;   // Selected day
      } else if ( splitdata[i+6].length==0 && th_dy != splitdata[17].substring(0,3) ) {
        $('#'+th_dy).attr('class','').addClass('four_col');   // No entries since date did not come back
      } else {
        $('#'+th_dy).attr('class','').addClass('three_col');   // Contain entries
      } // End if.
      $('#'+th_dy).html(splitdata[i+20]) ; // Rewrite Day headings (18-24)
    } // End for.

    if (splitdata[15] == splitdata[16]) // Today's entry is selected  || mode=='initial'
    {
      $('#txtInfo1').attr('class','').addClass('today_col');
      $('#'+splitdata[17].substring(0,3)).attr('class','').addClass('today_col');
    }
    else {
      $('#txtInfo1').addClass('sel_col'); // Add selected colour to text entry area.
    } // End if.
    
    // Make sure the tab for today stays noticeable only when current week is displayed
    if (iscurweeksplit[1]==1) {$('#'+Dt2dy2).attr('class','').addClass('today_col');} // End if.


    var DateEntryText  = splitdata[0]; // Actual text for diary entry from database
    var IsImportant    = splitdata[1]; // Marked as important
    var InsertDate     = splitdata[2]; // Date created
    var changedDate    = splitdata[3]; // Date changed
    var numEdits       = splitdata[4]; // Number of edits made to entry. Added on 21-07-2009.
    var TotalEntries   = splitdata[5]; // Total diary entries (to show in document title bar)

    // 6 - 12: Entry text for each day of selected week
    // Highlight days with no entries or no content and is not currently selected. Array items 6-12

    //var onthisdaytext  = splitdata[13]; // Info for on this day dropdown menu
    //var impdatestext   = splitdata[14]; // Important entries dropdown
    // 15 and 16
    var lastdofwk      = splitdata[17]; // Not used for anything anymore

    var prvwkd         = splitdata[18]; // Date on selected day in previous week
    var nxtwkd         = splitdata[19]; // Date on selected day next week

    // 18-24: Text to show in DHTML popups for each day of the selected week

    //var futuredatestxt = splitdata[27];
    


    // Populate textarea
    $('#txtInfo1').val(DateEntryText);
      
    var st_str = 'Created: ';
    if (InsertDate == '01/01/1990 00:00:00') {st_str += 'Not Known';}
    else if (InsertDate == '-- ::')          {st_str += 'N/A';}
    else                                     {st_str += InsertDate;}
    st_str += ' | ';
      
    numEdits = (numEdits==1) ? ' [1 Change]' : ' [' + numEdits + ' Changes]' ; // Added: 21-07-2009
    st_str += 'Changed: ';
    if (changedDate == '01/01/1990 00:00:00') {st_str += 'Not Known';}
    else if (changedDate == '-- ::')          {st_str += 'N/A';}
    else                                      {st_str += changedDate + numEdits;}
    $("#ins_upd_dt").html(st_str) ;
   
    // Check boxes if apply
    if (InsertDate.length > 5)  {$("#row_exist").attr('checked',  'checked') ;}
    else                        {$('#row_exist').removeAttr('checked');}

    if (InsertDate.length == 5) {$("#row_exist").attr('disabled', 'disabled');}
    else                        {$('#row_exist').removeAttr('disabled');}

    if (IsImportant == 1)       {$("#row_imp").attr('checked', 'checked');}
    else                        {$("#row_imp").removeAttr('checked');}
   
    // Display select menus
    //$("#onthisday").html(onthisdaytext) ;  // 13
    //$("#impdates").html(impdatestext) ;   // 14
    //$("#futuredates").html(futuredatestxt) ; // 
   
    // Change Prev week button text and actions
    $("#li_prvwkbtn")
    .html('<button id="btnPrevwk" title3="'+prvwkd+'" title4="'+lastdofwk+'" iscurwk="'+iscurweeksplit[0]+'" title="This day in the Previous Week">&lt;</button>') ;

    if (mode!='initial' && iscurweeksplit[0]==1) {$('#li_prvwkbtn button').addClass('today_col');}
    
    // Change Next week button text and action
    $("#li_nxtwkbtn")
    .html('<button id="btnNextwk" title3="'+nxtwkd+'" title4="'+lastdofwk+'" iscurwk="'+iscurweeksplit[2]+'" title="This day Next Week">&gt;</button>') ;
      
    if (mode!='initial' && iscurweeksplit[2]==1) {$('#li_nxtwkbtn button').addClass('today_col');}

    var uk_date_split = splitdata[16].split("-");
    document.title = 'An AJAX Diary: ' + uk_date_split[2] + '-' + uk_date_split[1]
                                       + '-' + uk_date_split[0] + '(' + TotalEntries + ')';
    focus_txtarea();
    //$('#LoadText').html('');
      
    // We set this so we can keep track of the selected date
    $('#upd_btn').attr('title2',splitdata[16]);
    $('#upload_d');      // 10/05/13 - So we can send date to upload script
    //$('#upd_btn').attr('title4', lastdofwk); // Attach title4 attribute to Update button so we can see selected date.

    if (mode=='initial') {
      //loadGal(Dt2dy);
      //loadRel(Dt2dy);

      // Calendar
      $('#dtPck').animate({right: 10, top: 20}, 'slow');
      loadCal(0, splitdata[16]);
    } // End if.
  }








 
  // Update entry
  $('#upd_btn').click(function(){
    var datetoupdate = $('#upd_btn').attr('title2');              // e.g. 2013-05-08
    //var sel  = $('#upd_btn').attr('title4').substr(0, 3); // e.g. Wed
    $.ajax({
      type:'post',
      url:'ajax/update/' + datetoupdate,
      data:'&info=' + encodeURIComponent($('#txtInfo1').val()) + '&_token={{ csrf_token() }}',
      success: function (d){
        goToday(datetoupdate);
        showhidechangedstatus('hidden'); // This function is defined below.
        focus_txtarea();
        loadCal(0, datetoupdate); // Refresh calendar
      } // End ajax success.
    });
  });
  

  $('#row_exist').click(function(){
    var insl = document.getElementById('ins_upd_dt').innerHTML.length;
    if ($(this).attr('checked')!='checked' && $('#ins_upd_dt').substr(9, 12) != 'N/A')
    {
      if(confirm ('The diary entry for '+$('#upd_btn').attr('title2')+' will be deleted. Continue?') ){del_entry();}
      else{ focus_txtarea(); return false}
    }
  });



  




  // Click on a date heading
  $('.datehead').live('click', function(){
    goToday($(this).attr('title3') );
    loadCal(0, $(this).attr('title3'));
    //loadGal($(this).attr('title3'));
    //loadRel($(this).attr('title3')); // 
    //loadTags($(this).attr('title3')); // 26/10/13
  });

  $('#goToday').live('click', function(){
    var split2 = $(this).attr('title2').split('|');
    goToday(split2[0]);
    loadCal(0, split2[0]);
  });
 
  $('#CalDay').live('click', function(){
    var split2 = $(this).attr('title2').split('|');
    goToday(split2[0]);
    loadCal(0, split2[0]);
    //loadRel(split2[0]); // 
    //loadGal(split2[0]);
  });

  // Calendar functions
// AJAX Calendar functions

$('#goLastMonth').live('click', function(){
  var splittxt = $(this).attr('title2').split('-'); // YYYY-mm-dd
  var month = parseInt(splittxt[1], 10);
  var year  = parseInt(splittxt[0], 10);
  if(month== 1) {
    --year;
    month= 13;
  }
  month--;
  goToday(year + '-' + month + '-' + splittxt[2]);
});

$('#goToMonth').live('change', function(){
  var splittxt = $(this).attr('title2').split('-'); // YYYY-mm-dd
  var month = parseInt($(this).val(), 10) + 1;
  goToday(parseInt(splittxt[2]) + '-' + month + '-01');
});

$('#goThisMonth').live('click', function(){
  var splittxt = $(this).attr('title2').split('-'); // YYYY-mm-dd
  var month = parseInt(splittxt[1], 10);
  var year  = parseInt(splittxt[0], 10);
  goToday(year + '-' + month + '-' + splittxt[2]);
});

  $('#goNextMonth').live('click', function(){
  var splittxt = $(this).attr('title2').split('-'); // YYYY-mm-dd
  var month = parseInt(splittxt[1], 10);
  var year  = parseInt(splittxt[0], 10);
  if(month==12) {
    ++year;
    month=0;
  }
  month++;
  goToday(year + '-' + month + '-' + splittxt[2]);
});

$('#goLastYear').live('click', function(){
  var splittxt = $(this).attr('title2').split('-'); // YYYY-mm-dd
  var year = parseInt(splittxt[0], 10);
  goToday(--year + '-' + splittxt[1] + '-' + splittxt[2]);
});

$('#goToYear').live('change', function(){
  var splittxt = $(this).attr('title2').split('-'); // YYYY-mm-dd
  var year = parseInt($(this).val(), 10) ;
  goToday(year + '-' + parseInt(splittxt[1], 10)  + '-' + splittxt[2] );
});

$('#goNextYear').live('click', function(){
  var splittxt = $(this).attr('title2').split('-'); // YYYY-mm-dd
  var year = parseInt(splittxt[0], 10);
  goToday(++year + '-' + parseInt(splittxt[1], 10) + '-' + splittxt[2]);
});

$('#goThisYear').live('click', function(){
  var splittxt = $(this).attr('title2').split('-'); // YYYY-mm-dd
  var year = parseInt(splittxt[0], 10);
  goToday(year + '-' + parseInt(splittxt[1], 10) + '-' + splittxt[2]);
});





 
  $('#btnPrevwk, #btnNextwk').live('click', function(){
    goToday($(this).attr('title3'));
    loadCal(0, $(this).attr('title3'));
    //loadGal($(this).attr('title3'));
  });
 

 
  $('#txtInfo1').change(function(){showhidechangedstatus('visible');});
  
  $('#txtInfo1').keypress(function(event){
    if (event.which > 0) {showhidechangedstatus('visible');}
  });
 
  // When DIVs are shown or hidden on clicking handle.
  $('.btnShowHide').click(function(){
    var layer = $(this).attr('title2');
    var displ = ($('#'+layer).css('display')=='block') ? 'none' : 'block';
    if (displ=='none') {
      $('#'+layer+'hidebar_btn').text('Show ' + layer.capitalize()).hide().fadeIn('slow')
      .css('background','navy');
    } else { // DIV is visible.
      $('#'+layer+'hidebar_btn').text('Hide ' + layer.capitalize()).hide().fadeIn('slow')
      .css('background','lime');
    } // End if.
    $('#'+layer).toggle('slide');
    setCookie(layer, displ, getExpDate(14,0,0));
  });
 

  // Show / Hide Calendar
  $('#btnShwCal').click(function(){
    loadCal(1);
  });
  
  function loadCal(toggle, dateSelected) {
    var dateSelected_split = dateSelected.split("-");
    $.ajax({
      type: 'get',
      url: 'ajax/calendar/' + dateSelected,
      
      success: function (data){
        //alert(data);
        if (toggle) {$('#dtPck').html(data).toggle();}
        else {$('#dtPck').hide().html(data).show();}
      }
    });
  } // End loadCal() method.
  

  // Gallery functions.
  $('#frmupload').submit(function(e){
    setTimeout(function(){loadGal($('#upd_btn').attr('title3'))}, 3000);
  });

  function loadGal(dateSelected) {
    $.ajax({
      type: 'get',
      url: 'ajax/images',
      data: 'd=' + dateSelected,
      
      success: function (data){
        $('#gallery').html(data);
        $("a.gallery").colorbox({rel:'gallery'});
      }
    });
  }
  
  $('#gallery li').live('mouseover', function(){
    $('.delimage').remove();
    $('<span class="delimage" title="'+$(this).attr('title')+'">X</span>')
    .appendTo($(this));
  });
  
  $('#gallery li').live('mouseout', function(){
    $('.delimage').remove(); 
  });
  
  $('#gallery .delimage').live('click', function(){
    if (confirm('Are you sure you want to delete this lovely image?')) {
      $.ajax({
        type: 'get',
        url: 'ajax/delimage',
        data: 'imgid=' + $(this).attr('title'),
        
        success: function (data){loadGal($('#upd_btn').attr('title3'));}
      }); // End ajax call.
    } // End if delete image.
  });
  

  // 23/10/14 - Load Related DIV content
  function loadRel(seldate) {
    $.ajax({
      type: 'POST',
      url: 'ajax/related',
      data: 'sel=' + seldate + '&load=1',
      
      success: function (data){
        //alert(data);
        $('#related').html(data);
      }
    }); // End ajax call.
  }

  // Add related to entry.
  $('#sel_relmain').live('change', function(){
    var opt = $(this).val();
    var seldate = $(this).attr('title');
    $.ajax({
      type: 'POST',
      url: 'ajax/related',
      data: 'sel=' + seldate + '&opt=' + opt + '&upd=1',
      
      success: function (data){if (data!='') {$('#related').html(data);}}
    }); // End ajax call.
  });





  
  // 26/10/13 - Diary tags
  
  function loadTags(seldate) {
    $.ajax({
      type: 'POST',
      url: 'ajax/addtag',
      data: 'sel=' + seldate + '&load=1',
      
      success: function (data){$('#entrytags').html(data);}
    }); // End ajax call.
  } // End loadTags() function.

  
  // Diary tags.
  $('.diarytag').live('click', function(){ 
    if ($(this).attr('checked')=='checked') {
      var test = document.title.substr(document.title.indexOf(':')+2);
      var splittest = test.split('-');
      var seldate = splittest[2].substr(0,4)+'-'+splittest[1]+'-'+splittest[0];
      var tagid = $(this).attr('id').substr(4);

      $.ajax({
        type: 'POST',
        url: 'ajax/addtag',
        data: 'sel=' + seldate + '&tagid=' + tagid,
        
        success: function (data){}
      }); // End ajax call.
    }
  });




  // Quick add entries
  $('.quickadd').click(function(){
    reloadQADiv('initial');
  });


  // Add to textarea
  $('.quickadddiv li').live('click', function(){
    if ($('.quickadddiv .mode span.add').hasClass('highlight')) {
      // Add
      $('#txtInfo1').val( $('#txtInfo1').val() + $(this).text() ).focus();
    } else if ($('.quickadddiv .mode span.upd').hasClass('highlight')) {
      // Update
      if ($(this).html().substr(0,2)!='<t') {$(this).html('<textarea>'+$(this).text()+'</textarea> <input type="submit" value="Upd" class="updentry"> <input type="button" value="X">');}
    } else if ($('.quickadddiv .mode span.del').hasClass('highlight')) {
      // Delete
      if ($(this).html().substr(0,2)!='<i') {$(this).html('<input type="submit" value="Confirm" class="delentry"> <input type="button" value="X">');}
    } else if ($('.quickadddiv .mode span.up').hasClass('highlight')) {
      // Move up.
      var id   = $(this).attr('class').substr(3);
      //alert(id);
      $.ajax({
        type: 'GET',
        url: 'ajax/quickentries/up/' + id,
        
        success: function (quickentries){
          //alert(quickentries);
          reloadQADiv('up');
        }
      }); // End ajax call.
    } // End if.
  });

  $('input[value=X]').live('click', function() {
    reloadQADiv('u');
  });


  // Add new entry
  $('.quickadddiv .newentry').live('click', function() {
    $.ajax({
      type: 'POST',
      url: 'ajax/quickentries/add',
      //data: 'act=add' ,
      success: function (quickentries){reloadQADiv('u');}
    }); // End ajax call.
  });


  $('.quickadddiv .mode span').live('click', function() {
    $('.quickadddiv .mode span').removeClass('highlight');
    $(this).addClass('highlight');
  });


  $('.updentry').live('click', function() {
    var id   = $(this).parent().attr('class').substr(3);
    var text = $(this).prev().val();
    $.ajax({
      type: 'POST',
      url: 'ajax/quickentries/upd/' + id,
      data: 'text=' + text,
      success: function (quickentries){reloadQADiv('u');}
    }); // End ajax call.
  });

  $('.delentry').live('click', function() {
    var id   = $(this).parent().attr('class').substr(3);
    $.ajax({
      type: 'POST',
      url: 'ajax/quickentries/del/' + id,
      success: function (quickentries){reloadQADiv('d');}
    }); // End ajax call.
  });



  function reloadQADiv(m) {
    if ($('.quickadddiv').length>0 && m == 'initial') {$('.quickadddiv').remove();$('.quickadd').text('+');}
    else {
      $.ajax({
        type: 'GET',
        url: 'ajax/quickentries/' + m,
        success: function (quickentries){
          if ($('.quickadddiv').length>0) {$('.quickadddiv').remove();}
          $('.quickadd').text('-');
          $('<div class="quickadddiv">'+quickentries+'</div>').insertAfter($('.quickadd'));
        }
      }); // End ajax call.
    } // End if.
  } // End function.

function focus_txtarea() {
  // Problem with giving textarea focus if it is in a layer that is not visible
  if ($('#txtInfo1').length>0) {$("#txtInfo1").focus();}
} // End function.



});
</script>

</body>
</html>