<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Diary - AJAX!</title>

  {{-- <link rel="stylesheet" href="js/jquery/colorbox/colorbox.css"> --}}
  <link rel="stylesheet" href="js/jquery/css/blitzer/jquery-ui-1.10.3.custom.min.css">
  <link rel="stylesheet" href="css/styles.css">

  <script src="js/jquery-1.8.3.min.js"></script>
  {{-- <script src="js/jquery.colorbox-min.js"></script> --}}
  <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
  {{-- <script src="js/cookie.js"></script>
  <script src="js/script.js"></script> --}}
</head>
<body>
<div class="quickadd">+</div>

<div id="dtPck"><!-- Calendar widget --></div>
{{ App\ProjectsMenu::display() }}

<div id="diarym">

  <ul class="diaryheadings">
    <li id="li_prvwkbtn"></li>
    <li class="sep"></li>
    @foreach (['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $weekday)
    <li id="{{ $weekday }}"><a>&nbsp;</a></li><li class="sep"></li>
    @endforeach
    <li id="li_nxtwkbtn"></li>
  </ul>

  <div><textarea id="txtInfo1"></textarea></div>

  <div class="upd_btn"><input type="button" id="upd_btn" value="Update &gt;"></div>

  <div id="ins_upd_dt">&nbsp;<!-- Created/Edit dates --></div>

</div>

<script>
$(document).ready(function(){
  $('ul#projectsmenu').css({'background':'#c00'});
  $('ul#projectsmenu li').css('float','none');
  $('ul#projectsmenu li').has('a[href]').hide();
  $('ul#projectsmenu li span').css('cursor','pointer').click(function() {
    $('ul#projectsmenu li').has('a[href]').toggle();
  });

  $( document ).tooltip({
    items: ".datehead, [tooltiptxt], [title]",
    content: function() {
      var element = $(this);
      if ( element.is( "[tooltiptxt]" ) ) {return element.attr( "tooltiptxt" );}
    }
  });

  function getData(mode,date) {
    $.ajax({
      type:'get',
      url:'ajax/getday/' + date,
      dataType: 'json',
      success: function (json){AjaxTest(json, mode);}
    });
  }

  // Initial load via ajax:
  getData('initial','');

  // When a date header is clicked
  function goToday(sel2) {getData('',sel2);}



  function AjaxTest(json, mode) {
    var splitdata=[];$.each(json, function(index, element) {splitdata[index]=element;});

    var dtarr = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']; // JavaScript days of week array.
    for (i=0; i < 7; i++) {
      $('#'+dtarr[i]).html(splitdata[i+13]) ; // Rewrite Day headings (18-24)
    }

    if (splitdata[8] == splitdata[9]) // Today's entry is selected.
    {$('#txtInfo1').removeAttr('class').addClass('today_col');}
    else {$('#txtInfo1').addClass('sel_col');} // Add selected colour to text entry area..

    // Populate textarea
    $('#txtInfo1').val(splitdata[0]);
      
    var st_str = 'Created: ';
    if (splitdata[2] == '01/01/1990 00:00:00') {st_str += 'Not Known';}
    else if (splitdata[2] == '')               {st_str += 'N/A';}
    else                                       {st_str += splitdata[2];}
    st_str += ' | ';
      
    numEdits = (splitdata[4]==1) ? ' [1 Change]' : ' [' + splitdata[4] + ' Changes]' ; // Added: 21-07-2009
    st_str += 'Changed: ';
    if (splitdata[3] == '01/01/1990 00:00:00') {st_str += 'Not Known';}
    else if (splitdata[3] == '')               {st_str += 'N/A';}
    else                                       {st_str += splitdata[3] + numEdits;}
    $("#ins_upd_dt").html(st_str) ;
   
    // 09/05/13 - Are we in the current week?
    var iscurweek      = splitdata[21];
    var iscurweeksplit = iscurweek.split('-');
    // Change Prev week button text and actions
    $("#li_prvwkbtn")
    .html('<button id="btnPrevwk" title3="'+splitdata[11]+'" title4="'+splitdata[10]+'" iscurwk="'+iscurweeksplit[0]+'" title="This day in the Previous Week">&lt;</button>') ;

    if (mode!='initial' && iscurweeksplit[0]==1) {$('#li_prvwkbtn button').addClass('today_col');}
    
    // Change Next week button text and action
    $("#li_nxtwkbtn")
    .html('<button id="btnNextwk" title3="'+splitdata[12]+'" title4="'+splitdata[10]+'" iscurwk="'+iscurweeksplit[2]+'" title="This day Next Week">&gt;</button>') ;
      
    if (mode!='initial' && iscurweeksplit[2]==1) {$('#li_nxtwkbtn button').addClass('today_col');}

    var uk_date_split = splitdata[9].split("-");
    document.title = 'An AJAX Diary: ' + uk_date_split[2] + '-' + uk_date_split[1]
                                       + '-' + uk_date_split[0] + '(' + splitdata[5] + ')';
    focus_txtarea();
      
    // We set this so we can keep track of the selected date
    $('#upd_btn').attr('title2',splitdata[9]);

    if (mode=='initial') {
      //loadGal(Dt2dy);
      //loadRel(Dt2dy);

      $('#dtPck').animate({right: 10, top: 20}, 'slow'); // Calendar
    } // End if.
    loadCal(0, splitdata[9]);
  }









  // Update entry
  $('#upd_btn').click(function(){
    var datetoupdate = $('#upd_btn').attr('title2');        // e.g. 2013-05-08
    $.ajax({
      type:'post',
      url:'ajax/update/' + datetoupdate,
      data:'&info=' + encodeURIComponent($('#txtInfo1').val()) ,
      success: function (d){
        if (d=='Not saved') {alert(d);}
        goToday(datetoupdate);
        showhidechangedstatus('hidden'); // This function is defined below.
        focus_txtarea();
      } // End ajax success.
    });
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

  $('#txtInfo1').keypress(function(event){if(event.which > 0) {showhidechangedstatus('visible');}});
   
  function loadCal(toggle, dateSelected) {
    var dateSelected_split = dateSelected.split("-");
    $.ajax({
      type: 'get',
      url: 'ajax/calendar/' + dateSelected,
      success: function (data){
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
      url: 'ajax/images/' + dateSelected,
      success: function (data){
        $('#gallery').html(data);
        $("a.gallery").colorbox({rel:'gallery'});
      }
    });
  }

  $('#gallery li').live('mouseover', function(){
    $('.delimage').remove();
    $('<span class="delimage" title="'+$(this).attr('title')+'">X</span>').appendTo($(this));
  });

  $('#gallery li').live('mouseout', function(){
    $('.delimage').remove(); 
  });

  $('#gallery .delimage').live('click', function(){
    if (confirm('Are you sure you want to delete this lovely image?')) {
      $.ajax({
        type: 'get',
        url: 'ajax/delimage/' + $(this).attr('title'),
        success: function (data){loadGal($('#upd_btn').attr('title3'));}
      }); // End ajax call.
    } // End if delete image.
  });


  // 23/10/14 - Load Related DIV content
  function loadRel(seldate) {
    $.ajax({
      type: 'GET',
      url: 'ajax/related/' + seldate,
      data: 'load=1',
      success: function (data){$('#related').html(data);}
    }); // End ajax call.
  }

  // Add related to entry.
  $('#sel_relmain').live('change', function(){
    var opt = $(this).val();
    var seldate = $(this).attr('title');
    $.ajax({
      type: 'POST',
      url: 'ajax/related/' + seldate,
      data: 'opt=' + opt + '&upd=1',
      
      success: function (data){if (data!='') {$('#related').html(data);}}
    }); // End ajax call.
  });






  // 26/10/13 - Diary tags

  function loadTags(seldate) {
    $.ajax({
      type: 'POST',
      url: 'ajax/addtag' + seldate,
      data: '&load=1',
      
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
        url: 'ajax/addtag/' + seldate + '/' + tagid,
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
        
        success: function (quickentries){reloadQADiv('up');}
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


  var EntryChangeCounter;
  var EntryChangeCount = 0;

  // Hiding/Showing Changedstatus button - invoked when there is a change in the data entered.
  function showhidechangedstatus(wh) {
    if (wh == "visible") { // Change was detected!
      clearInterval(EntryChangeCounter);
      EntryChangeCounter = setInterval(function(){
        $('#upd_btn').val('Data Changed Update? ' + ++EntryChangeCount);
      }, 1000);
      $('#upd_btn').css({'background':'#c00','color':'#fff'});
    } else {
      clearInterval(EntryChangeCounter);
      EntryChangeCount=0;
      $('#upd_btn').css({'background':'buttonface','color':'#000'}).attr('value','Update!');
    }
  } // End function.

});
</script>

</body>
</html>