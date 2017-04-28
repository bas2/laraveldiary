<?php
Route::get('home', 'DiaryController@index' );

Route::get('getday/{date?}', 'DiaryController@getDay' );

Route::get('calendar/{date}', 'DiaryController@calendar' );

Route::post('update/{date}', 'DiaryController@entryUpdate' );

Route::post('whatdidyoudo/{date}', 'DiaryController@postWhatdidyoudo');
Route::get('whatdidyoudo/{date?}',  'DiaryController@getWhatdidyoudo');

Route::get('time/hour',function(){
  return date('H');
});
// Get form fragment.
Route::get('wdyd',function(){
  $activities=[];foreach(\App\Activity::orderBy('detail')->get() as $activity){$activities[$activity->id]=$activity->detail;}
  return view('ajax.wdydo')->with('activities',$activities);
});

Route::get('activityhint/{id}',function($id){
  return \App\Activity::where('id',$id)->get(['hint'])[0]->hint;
});

Route::get('images', 'DiaryController@images' );
Route::post('related', 'DiaryController@related' );

Route::post('quickentries/add', 'DiaryController@quickEntriesAdd' );
Route::post('quickentries/upd/{id}', 'DiaryController@quickEntriesEdit' );
Route::post('quickentries/del/{id}', 'DiaryController@quickEntriesDelete' );
Route::get('quickentries/{mode}', 'DiaryController@quickEntriesMode' );
Route::get('quickentries/up/{id}', 'DiaryController@quickEntriesUp' );
