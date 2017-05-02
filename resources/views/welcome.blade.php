<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
  <meta charset="utf-8">
  <title>Diary - AJAX!</title>
  <!-- Bootstrap -->
  {!! Html::style('css/bootstrap.min.css') !!}

  {{-- <link rel="stylesheet" href="js/jquery/colorbox/colorbox.css"> --}}
  <link rel="stylesheet" href="css/styles.css">

  {!! Html::style('js/jquery/jquery-ui-1.12.1.custom/jquery-ui.css') !!}

  {{-- {!! Html::script('js/jquery/jquery-3.1.1.min.js') !!} --}}
  {!! Html::script('js/jquery/jquery-ui-1.12.1.custom/external/jquery/jquery.js') !!}
  {!! Html::script('js/jquery/jquery-ui-1.12.1.custom/jquery-ui.min.js') !!}
</head>
<body>

  @include('projectmenu')
  <div class="container-fluid">
  <div class="row">

  <div class="col-md-8">

    <ul class="diaryheadings list-inline row equal">
      <li id="li_prvwkbtn">
      @foreach (['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $weekday)
      <li id="{{ $weekday }}"><a>{{ $weekday }}</a>
      @endforeach
      <li id="li_nxtwkbtn">
    </ul>

    <textarea class="today_col form-control" id="txtInfo1"></textarea>

    <input class="btn btn-primary btn-block" type="button" id="upd_btn" value="Update &gt;">

    <div class="alert alert-info text-center" id="ins_upd_dt"></div>

    <fieldset class="whatdidyoudo">
      <legend>What did you do on this fine day?</legend>

      <ul class="current">

      </ul>

      <div class="new">
        <div class="form-group">
        {{ Form::text('timebox','00:00',['class'=>'form-control']) }}
        
        {{ Form::button('am/pm',['name'=>'ampm','class'=>'btn btn-primary']) }}
        </div>
      </div>

    </fieldset>

  </div>
  
  <div class="col-md-4">
    <div id="dtPck">@include('ajax.calendar')</div>
  </div>


  </div>
  </div>

<script>
$(document).ready(function(){
  // Quick add link.
  $('<div class="quickadd">+</div>').prependTo('body');

  // Projects menu.
  $('ul#projectsmenu li.sel').css({'border-bottom':0,'margin':'.2em .5em'});
  $('ul#projectsmenu li').has('a[href]').hide();

  $('ul#projectsmenu li').click(function() {
    $('ul#projectsmenu li').has('a[href]').toggle();
    if ($('ul#projectsmenu li').has('a[href]').is(':hidden')) {
      $('ul#projectsmenu li.sel').css({'border-bottom':0,'margin':'.2em .5em'});
    } else {
      $('ul#projectsmenu li.sel')
      .css({'border-bottom':'1px solid rgba(196,196,196,.7)','margin':0});
    }
  });

  // Tooltips.
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
      url:'getday/' + date,
      dataType: 'json',
      success: function (json){AjaxTest(json, mode);}
    });

    $.ajax({
      type:'get',
      url:'whatdidyoudo/' + date,
      success: function (data){$('.whatdidyoudo .current').html(data);}
    });
  }

  getData('initial',''); // Initial load via ajax:


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

    $('#txtInfo1').val(splitdata[0]); // Populate textarea
      
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

    if (mode=='initial') {$('#dtPck').animate({right: 10, top: 20}, 'slow');} // Calendar

    loadCal(0, splitdata[9]);
  }


  // Update entry
  $('#upd_btn').click(function(){
    var datetoupdate = $('#upd_btn').attr('title2');        // e.g. 2013-05-08
    $.ajax({
      type:'post',
      url:'update/' + datetoupdate,
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
  $('ul.diaryheadings').on('click','a', function(){
    goToday($(this).attr('title3') );
  });

 // What did you do on this fine day functions.

  $('body').on('click','.whatdidyoudo button[name=add]',function(){
    $.ajax({
      type: 'post',
      url: 'whatdidyoudo/' + $('#upd_btn').attr('title2'),
      data:'time=' + $('input[name=timebox]').val() 
      + '&activityid=' + $('select[name=whatdidyoudoa]').val()
      + '&detail=' + $('input[name=whatdidyoudot]').val(),
      success: function (data){
        //alert(data);
        goToday($('#upd_btn').attr('title2'));
        $('div.activitysel').remove();
      }
    });
  });

  $('body').on('click','.whatdidyoudo button[name=cancel]',function(){
    $(this).parent().parent().remove();
  });


  $('button[name=ampm').click(function(){
    var topDigit = $('.timepick span').first().text();
    $('div.timepick:nth-child(1) span').each(function(){
      if ($(this).text()!='•') {
        if(topDigit!='12') {$(this).text( convert12h($(this).text()) );}
        else {$(this).text( convert24h($(this).text()) );}
      }
    });

  });

  $('input[name=timebox]').click(function(){
    // Open timepicker.
    $('div.timepick').remove();

    var str='<div class="cen" style="padding-top:.25em;"><span>12</span></div>';

    str+='<div style="margin-top:-20px;"><span style="margin-left:2.2em;">11</span><span style="position:absolute;right:2.2em;">1</span></div>';
    str+='<div style="margin-top:-8px;"><span style="margin-left:24px;">10</span><span style="position:absolute;right:24px;">2</span></div>';
    
    str+='<div><span style="margin-left:12px;">9</span><span style="position:absolute;left:40%;">&bull;</span><span style="position:absolute;right:12px;">3</span></div>';
    
    str+='<div><span style="margin-left:20px;">8</span><span style="position:absolute;right:20px;">4</span></div>';
    str+='<div><span style="margin-left:2.2em;">7</span><span style="position:absolute;right:2.2em;">5</span></div>';

    str+='<div class="cen" style="margin-top:-15px;"><span>6</span></div>';

    $('<div class="timepick">'+str+'</div>')
    .css({'bottom':$(this).position().top-300})
    .insertBefore($(this).parent())
    ;

    // Get current hour via ajax:
    $.ajax({
    "type":"GET",
    "url":"time/hour",

    "success":function(hour){
      $('div.timepick:nth-child(1) span').each(function(){
        if ($(this).text()!='•') {
          if(hour>12) {
            $(this).text( convert24h($(this).text()) );
            //$('input[type=radio]#pm').attr('checked','checked');
          } else {
            $(this).text( $(this).text() );
            //$('input[type=radio]#am').attr('checked','checked');
          }
          if(hour==$(this).text()) $(this).css('color','yellow');
          
        }
      });
      } // End ajax success function

    }); // End ajax.




  });

  function convert24h(h) {
    var h = parseInt(h,10)
    //alert(h);
    if(h==12) {return 0;}
    return h + 12;
  }

  function convert12h(h) {
    var h = parseInt(h,10)
    if(h==0) {return 12;}
    return h - 12;
  }

  $('body').on('click','.timepick span',function(){
    
    if(!$(this).parent().parent().hasClass('minutes')) {
      // Hours.
      //if($('input[name=am_pm]:checked').val()=='am') $('input[name=timebox]').val($(this).text() + ':00');
      //else 
      $('input[name=timebox]').val( $(this).text() + ':00' );
      $('.timepick').addClass('minutes');

      var cnt=0;
      $('div.timepick:nth-child(1) span').each(function(){
        if ($(this).text()!='•') {
          $(this).text(cnt);
          cnt+=5;
        }
      });

    } else {
      // Minutes.
      var timeSplit=$('input[name=timebox]').val().split(':');
      $('input[name=timebox]').val(timeSplit[0] + ':' + $(this).text());
      $(this).parent().parent().remove();

      // Show the activity and more info fields.
      $.ajax({
        type: 'get',
        url: 'wdyd' ,

        success: function (formfrag){
          // What did you do?
          var str='<h2 class="text-center">What did you do at '+$('input[name=timebox]').val()+'?</h2>';
          str+=formfrag;
          $('<div class="activitysel container">'+str+'</div>')
          //.css({'bottom':$('input[name=timebox]').position().top-300})
          .insertAfter($('input[name=timebox]'));
        }
      });

    }



  });

  // When an item is selected from the activity list, display hint.
  $('body').on('change','select[name=whatdidyoudoa]',function(){
    var $this=$(this);
    $.ajax({
      type: 'get',
      url: 'activityhint/' + $this.val() ,
      success: function (hint){
        $this.parent().next().find('input[name=whatdidyoudot]').val('').attr('placeholder',hint).focus();
      }
    });
  });


  // Calendar functions
  
  $(document).on('click','#tblDate a[title2]', function(){
    var split2 = $(this).attr('title2').split('|');
    goToday(split2[0]);
  });

  $(document).on('click','#goToday', function(){
    var split2 = $(this).attr('title2').split('|');
    goToday(split2[0]);
  });

  $(document).on('click','#goLastMonth', function(){
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

  $(document).on('change','#goToMonth', function(){
    var splittxt = $(this).attr('title2').split('-'); // YYYY-mm-dd
    goToday(splittxt[0] + '-' + parseInt($(this).val(), 10) + '-' + splittxt[2]);
  });

  $(document).on('click','#goThisMonth', function(){
    var splittxt = $(this).attr('title2').split('-'); // YYYY-mm-dd
    var month = parseInt(splittxt[1], 10);
    var year  = parseInt(splittxt[0], 10);
    goToday(year + '-' + month + '-' + splittxt[2]);
  });

  $(document).on('click','#goNextMonth', function(){
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

  $(document).on('click','#goLastYear', function(){
    var splittxt = $(this).attr('title2').split('-'); // YYYY-mm-dd
    var year = parseInt(splittxt[0], 10);
    goToday(--year + '-' + splittxt[1] + '-' + splittxt[2]);
  });

  $(document).on('change','#goToYear', function(){
    var splittxt = $(this).attr('title2').split('-'); // YYYY-mm-dd
    var year = parseInt($(this).val(), 10) ;
    goToday(year + '-' + parseInt(splittxt[1], 10)  + '-' + splittxt[2] );
  });

  $(document).on('click','#goNextYear', function(){
    var splittxt = $(this).attr('title2').split('-'); // YYYY-mm-dd
    var year = parseInt(splittxt[0], 10);
    goToday(++year + '-' + parseInt(splittxt[1], 10) + '-' + splittxt[2]);
  });

  $(document).on('click','#goThisYear', function(){
    var splittxt = $(this).attr('title2').split('-'); // YYYY-mm-dd
    var year = parseInt(splittxt[0], 10);
    goToday(year + '-' + parseInt(splittxt[1], 10) + '-' + splittxt[2]);
  });


  $(document).on('click','#btnPrevwk, #btnNextwk', function(){
    goToday($(this).attr('title3'));
  });

  $('#txtInfo1').change(function(){showhidechangedstatus('visible');});

  $('#txtInfo1').keypress(function(event){if(event.which > 0) {showhidechangedstatus('visible');}});
   
  function loadCal(toggle, dateSelected) {
    var dateSelected_split = dateSelected.split("-");
    $.ajax({
      type: 'get',
      url: 'calendar/' + dateSelected,
      success: function (data){
        if (toggle) {$('#dtPck').html(data).toggle();}
        else {$('#dtPck').hide().html(data).show();}
      }
    });
  } // End loadCal() method.

 



  // Quick add entries

  $('.quickadd').click(function(){
    reloadQADiv('initial');
  });


  // Add to textarea
  $(document).on('click','.quickadddiv li', function(){
    if ($('.quickadddiv .mode span.add').hasClass('highlight')) { // Add
      $('#txtInfo1').val( $('#txtInfo1').val() + $(this).text() ).focus();

    } else if ($('.quickadddiv .mode span.upd').hasClass('highlight')) { // Update
      if ($(this).html().substr(0,2)!='<t') {$(this).html('<textarea>'+$(this).text()+'</textarea> <input type="submit" value="Upd" class="updentry"> <input type="button" value="X">');}

    } else if ($('.quickadddiv .mode span.del').hasClass('highlight')) { // Delete
      if ($(this).html().substr(0,2)!='<i') {$(this).html('<input type="submit" value="Confirm" class="delentry"> <input type="button" value="X">');}

    } else if ($('.quickadddiv .mode span.up').hasClass('highlight')) { // Move up.
      var id   = $(this).attr('class').substr(3);
      $.ajax({
        type: 'GET',
        url: 'quickentries/up/' + id,
        success: function (quickentries){reloadQADiv('up');}
      }); // End ajax call.
    } // End if.
  });

  // 
  $(document).on('click','input[value=X]', function() {
    reloadQADiv('u');
  });


  // Add new entry
  $(document).on('click','.quickadddiv .newentry', function() {
    $.ajax({
      type: 'POST',
      url: 'quickentries/add',
      success: function (quickentries){reloadQADiv('u');}
    }); // End ajax call.
  });

  // Click on a menu item.
  $(document).on('click','.quickadddiv .mode span', function() {
    $('.quickadddiv .mode span').removeClass('highlight');
    $(this).addClass('highlight');
  });

  // Update entry.
  $('body').on('click','.updentry', function() {
    var id   = $(this).parent().attr('class').substr(3);
    var text = $(this).parent().find('textarea').val();
    $.ajax({
      type: 'POST',
      url: 'quickentries/upd/' + id,
      data: 'text=' + text,
      success: function (quickentries){reloadQADiv('u');}
    }); // End ajax call.
  });

  // Delete entry.
  $('body').on('click','.delentry', function() {
    var id   = $(this).parent().attr('class').substr(3);
    $.ajax({
      type: 'POST',
      url: 'quickentries/del/' + id,
      success: function (quickentries){reloadQADiv('d');}
    }); // End ajax call.
  });

  // Reload DIV.
  function reloadQADiv(m) {
    if ($('.quickadddiv').length>0 && m == 'initial') {$('.quickadddiv').remove();$('.quickadd').text('+');}
    else {
      $.ajax({
        type: 'GET',
        url: 'quickentries/' + m,
        success: function (quickentries){
          if ($('.quickadddiv').length>0) {$('.quickadddiv').remove();}
          $('.quickadd').text('-');
          $('<div class="quickadddiv">'+quickentries+'</div>').insertAfter($('.quickadd'));
        }
      }); // End ajax call.
    } // End if.
  } // End function.

  // End quickAdd functions.

  // Focus Entry textarea.
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
