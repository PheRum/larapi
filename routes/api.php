<?php

/**
 * MailChimp Lists
 */
Route::group(['prefix' => 'list'], function () {
    Route::get('/', 'MailChimpListController@index')->name('list.index');
    Route::post('/', 'MailChimpListController@store')->name('list.store');

    Route::get('{list_id}', 'MailChimpListController@show')->name('list.show');

    Route::patch('{list_id}', 'MailChimpListController@update')->name('list.update');
    Route::delete('{list_id}', 'MailChimpListController@destroy')->name('list.destroy');

    /**
     * MailChimp Lists Members
     */
    Route::group(['prefix' => '{list_id}/member'], function () {
        Route::get('/', 'MailChimpListMemberController@index')->name('list.member.index');
        Route::post('/', 'MailChimpListMemberController@store')->name('list.member.store');

        Route::get('{hash}', 'MailChimpListMemberController@show')->name('list.member.show');

        Route::patch('{hash}', 'MailChimpListMemberController@update')->name('list.member.update');
        Route::delete('{hash}', 'MailChimpListMemberController@destroy')->name('list.member.destroy');
    });
});
