<?php
Route::get('home', 'DiaryController@index' );

Route::get('getday/{date?}', 'DiaryController@getDay' );

Route::get('calendar/{date}', 'DiaryController@calendar' );

Route::post('update/{date}', 'DiaryController@entryUpdate' );

Route::get('images', 'DiaryController@images' );
Route::post('related', 'DiaryController@related' );

Route::post('quickentries/add', 'DiaryController@quickEntriesAdd' );
Route::post('quickentries/upd/{id}', 'DiaryController@quickEntriesEdit' );
Route::post('quickentries/del/{id}', 'DiaryController@quickEntriesDelete' );
Route::get('quickentries/{mode}', 'DiaryController@quickEntriesMode' );
Route::get('quickentries/up/{id}', 'DiaryController@quickEntriesUp' );
