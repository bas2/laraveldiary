<?php
Route::get('home', 'DiaryController@index' );

Route::get('ajax/getday/{date?}', 'DiaryController@getDay' );

Route::get('ajax/calendar/{date}', 'DiaryController@calendar' );

Route::post('ajax/update/{date}', 'DiaryController@entryUpdate' );

Route::get('ajax/images', 'DiaryController@images' );
Route::post('ajax/related', 'DiaryController@related' );

Route::post('ajax/quickentries/add', 'DiaryController@quickEntriesAdd' );
Route::post('ajax/quickentries/upd/{id}', 'DiaryController@quickEntriesEdit' );
Route::post('ajax/quickentries/del/{id}', 'DiaryController@quickEntriesDelete' );
Route::get('ajax/quickentries/{mode}', 'DiaryController@quickEntriesMode' );
Route::get('ajax/quickentries/up/{id}', 'DiaryController@quickEntriesUp' );
