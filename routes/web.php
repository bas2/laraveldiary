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

Route::get('ajax/getday/{date}', function($date){
  $date2 = \Carbon\Carbon::parse($date);
  $mon = $date2->startOfWeek();
  $tue = $mon->copy()->addDay();
  $wed = $tue->copy()->addDay();
  $thu = $wed->copy()->addDay();
  $fri = $thu->copy()->addDay();
  $sat = $fri->copy()->addDay();
  $sun = $sat->copy()->addDay();

  $prvwk = $mon->copy()->subDays(7)->format('Y-m-d');
  $nxtwk = $mon->copy()->addDays(7)->format('Y-m-d');
  
  $count=App\Entry::count();
  $entries=App\Entry::where('d',$date)->get(['info']);
  $info=(empty($entries[0]->info)) ? '' : $entries[0]->info ;
  $str = "{$info}-||-Imp-||-1990-01-01-||-Ch-||-Editnum-||-{$count}-||-1-||-22-||-3-||-4-||-5-||-6-||-7-||-otd13-||-imp14-||-15?-||-{$prvwk}-||-{$nxtwk}";

  foreach ([$mon,$tue,$wed,$thu,$fri,$sat,$sun] as $value) {
    $entries=App\Entry::where('d',$value)->get(['info']);
    $info=(empty($entries[0]->info)) ? '' : $entries[0]->info ;
    $str.= "-||-<a class='datehead' tooltiptxt='{$info}' title3='{$value->format('Y-m-d')}' title4='{$value->format('D')}' iscurwk='1'>{$value->format('l jS F')}</a>";
  }

  //$str.="-||-{$tue->format('D d/m/Y')}-||-{$wed->format('D d/m/Y')}-||-{$thu->format('D d/m/Y')}-||-{$fri->format('D d/m/Y')}-||-{$sat->format('D d/m/Y')}-||-{$sun->format('D d/m/Y')}";

  $str.= "-||-Forth25-||-p-||-c-||-n";

  //return view('getday')->with('date', $date)->with('content', $str);
  return $str;
});

Route::get('ajax/calendar', function(){
  return view('ajax.calendar');
});

Route::get('ajax/images', function(){

});

Route::post('ajax/related', function(){

});

Route::post('ajax/update/{date}', function($date){
  $input=Request::all();
  $videos = new \App\Entry;
  $videos->updateOrInsert(['d'=>$date], ['info'=>$input['info']]);
});
