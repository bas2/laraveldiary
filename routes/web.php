<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('home', function () {
  return view('welcome');
});

Route::get('ajax/getday/{date?}', function($date=null){
  if(is_null($date)) {$date=\Carbon\Carbon::now()->format('Y-m-d');} # Today.
  else {$date=\Carbon\Carbon::parse($date)->format('Y-m-d');}

  $date2 = \Carbon\Carbon::parse($date); # Carbon instance for selected date.
  
  $date3 = $date2->copy()->format('l');
  $date4 = $date2->copy();
  $mon = $date2->startOfWeek();
  $tue = $mon->copy()->addDay();
  $wed = $tue->copy()->addDay();
  $thu = $wed->copy()->addDay();
  $fri = $thu->copy()->addDay();
  $sat = $fri->copy()->addDay();
  $sun = $sat->copy()->addDay();

  $prvwk = $date4->copy()->subDays(7)->format('Y-m-d');
  $nxtwk = $date4->copy()->addDays(7)->format('Y-m-d');

  $curwk = \Carbon\Carbon::now()->startOfWeek()->format('Y-m-d');

  $dateisthisweek=($curwk==$date2->copy()->format('Y-m-d')) ? 1 : 0 ;
  $datesnextisthisweek=($curwk==$nxtwk) ? 1 : 0 ;
  $datesprevisthisweek=($curwk==$prvwk) ? 1 : 0 ;

  $curwk="{$datesprevisthisweek}-{$dateisthisweek}-{$datesnextisthisweek}";

  $count=App\Entry::count();
  $entries=App\Entry::where('d',$date)->get(['info','created_at','updated_at','numedit','important']);
  $info=(empty($entries[0]->info))       ? '' : $entries[0]->info ;
  $crdt=(empty($entries[0]->created_at)) ? '' : $entries[0]->created_at->format('d/m/Y H:i') ;
  $chdt=(empty($entries[0]->updated_at)) ? '' : $entries[0]->updated_at->format('d/m/Y H:i') ;
  $nume=(empty($entries[0]->numedit))    ? '0' : $entries[0]->numedit ;
  $impo=(empty($entries[0]->important))  ? '' : $entries[0]->important ;

  $json=[$info,'Imp',$crdt,$chdt,$nume,$count];

  foreach(['otd13','imp14',\Carbon\Carbon::now()->format('Y-m-d'),$date,$date3,$prvwk,$nxtwk] as $element) {$json[]=$element;}

  $now=\Carbon\Carbon::now()->startOfDay();
  foreach ([$mon,$tue,$wed,$thu,$fri,$sat,$sun] as $value) {
    $entries=App\Entry::where('d',$value)->get(['info']);
    $info=(empty($entries[0]->info)) ? '' : $entries[0]->info ;
    $info=str_replace("'", '&lsquo;', $info); # Replace single quotes so they don't mess tips.
    $class=(empty($info))?' four_col':' three_col'; # No entries versus having entries.
    if ($value->format('Y-m-d')==$date4->format('Y-m-d')) {$class=' sel_col';}
    if ($value->format('Y-m-d')==date('Y-m-d'))           {$class=' today_col';}
    $difference = ($value->diff($now)->days < 1)? 'today': $value->diffForHumans($now);
    $json[]= "<a class='datehead{$class}' tooltiptxt='<p class=date_s>{$value->format('l jS F')} [$difference}</p>{$info}' title3='{$value->format('Y-m-d')}' title4='{$value->format('D')}' iscurwk='1'>{$value->format('l jS F')}</a>";
  }
  foreach(['Forth25',$curwk,'c','n'] as $element) {$json[]=$element;}
  $json=json_encode($json);
  return $json;
});


Route::get('ajax/calendar/{date}', function($date){
  $seldatest=\Carbon\Carbon::parse($date)->startOfMonth();
  $seldateen=\Carbon\Carbon::parse($date)->endOfMonth();

  $firstdayofmonth = $seldatest->format('D');
  $daysinmonth     = $seldatest->daysInMonth;

  $blankcells=0;
  foreach (['Tue'=>1,'Wed'=>2,'Thu'=>3,'Fri'=>4,'Sat'=>5,'Sun'=>6] as $day=>$blankcells1) {
    if($firstdayofmonth==$day) {$blankcells=$blankcells1;}
  }

  $monthentries=App\Entry::whereBetween('d',[$seldatest->format('Y-m-d'),$seldateen->format('Y-m-d')])->get(['d','info']);
  $me2=[];foreach($monthentries as $me3) {
    $d=\Carbon\Carbon::parse($me3->d)->format('j');
    if (!empty($me3->info)) 
    {$me2[$d]=$me3->info;} 
    else {$me2[$d]='';}
  }
  

  return view('ajax.calendar')->with('vals',
    ['daysinmonth'=>$daysinmonth,'monthentries'=>$me2,'blankcells'=>$blankcells,'seldate'=>$seldatest,
    'datesel'=>\Carbon\Carbon::parse($date)->format('Y-m-d')]
  );
});


Route::get('ajax/images', function(){

});

Route::post('ajax/related', function(){

});

Route::get('ajax/quickentries/{mode}', function($mode){

  $mode2 = 'a';
  if ($mode=='u') { // Update mode.
    $mode2 = 'u';
  } else if ($mode=='d') { // Delete mode.
    $mode2 = 'd';
  } // End if.

  $addclass = ($mode2=='a')  ? ' highlight' : '';
  $updclass = ($mode2=='u')  ? ' highlight' : '';
  $delclass = ($mode2=='d')  ? ' highlight' : '';
  $upclass  = ($mode2=='up') ? ' highlight' : '';

  $qentries=\App\Qentry::orderBy('id')->get(['id','text']);

  return view('ajax.quickentries')->with('mode',$mode2)->with('classes',[$addclass,$updclass,$delclass,$upclass])->with('data',$qentries);
});

Route::get('ajax/quickentries/up/{id}', function($id){

});


Route::post('ajax/update/{date}', function($date){
  $input=Request::all();
  $entry=new \App\Entry;
  $info=(empty($input['info'])) ? '' : $input['info'] ;
  $entry->updateOrInsert(['d'=>$date], ['info'=>$info]);
  $entry2=new \App\Entry;$entry->where('d',$date)->increment('numedit');
  return $entry ? 'Saved' : 'Not saved';
});
