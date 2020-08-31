<?php

Route::group([
    'namespace' => 'Seat\Kassie\Calendar\Http\Controllers',
    'middleware' => ['web', 'auth', 'locale'],
    'prefix' => 'character',
], function() {

    Route::get('/{character}/paps', [
        'as' => 'character.view.paps',
        'uses' => 'CharacterController@paps',
        'middleware' => 'can:character.kassie_calendar_paps,character',
    ]);

});

Route::group([
    'namespace' => 'Seat\Kassie\Calendar\Http\Controllers',
    'middleware' => ['web', 'auth', 'locale'],
    'prefix' => 'corporation',
], function() {

    Route::get('/{corporation}/paps', [
        'as' => 'corporation.view.paps',
        'uses' => 'CorporationController@getPaps',
        'middleware' => 'can:corporation.kassie_calendar_paps,corporation',
    ]);

    Route::get('/{corporation}/paps/json/year', [
        'as' => 'corporation.ajax.paps.year',
        'uses' => 'CorporationController@getYearPapsStats',
        'middleware' => 'can:corporation.kassie_calendar_paps,corporation',
    ]);

    Route::get('/{corporation}/paps/json/stacked', [
        'as' => 'corporation.ajax.paps.stacked',
        'uses' => 'CorporationController@getMonthlyStackedPapsStats',
        'middleware' => 'can:corporation.kassie_calendar_paps,corporation',
    ]);

});

Route::group([
    'namespace' => 'Seat\Kassie\Calendar\Http\Controllers',
    'middleware' => ['web', 'auth', 'locale', 'can:calendar.view'],
    'prefix' => 'calendar'
], function () {

    Route::group([
        'prefix' => 'ajax'
    ], function(){

        Route::get('/operation/{id}', [
            'as' => 'operation.detail',
            'uses' => 'AjaxController@getDetail'
        ])->where('id', '[0-9]+');

        Route::get('/operation/ongoing', [
            'as' => 'operation.ongoing',
            'uses' => 'AjaxController@getOngoing',
        ]);

        Route::get('/operation/incoming', [
            'as' => 'operation.incoming',
            'uses' => 'AjaxController@getIncoming',
        ]);

        Route::get('/operation/faded', [
            'as' => 'operation.faded',
            'uses' => 'AjaxController@getFaded',
        ]);
    });

    Route::group([
        'prefix' => 'operation'
    ], function() {

        Route::get('/', [
            'as' => 'operation.index',
            'uses' => 'OperationController@index'
        ]);

        Route::post('/', [
            'as' => 'operation.store',
            'uses' => 'OperationController@store',
            'middleware' => 'can:calendar.create'
        ]);

        Route::post('update', [
            'as' => 'operation.update',
            'uses' => 'OperationController@update',
        ]);

        Route::post('subscribe', [
            'as' => 'operation.subscribe',
            'uses' => 'OperationController@subscribe'
        ]);

        Route::post('cancel', [
            'as' => 'operation.cancel',
            'uses' => 'OperationController@cancel',
        ]);

        Route::post('activate', [
            'as' => 'operation.activate',
            'uses' => 'OperationController@activate',
        ]);

        Route::post('close', [
            'as' => 'operation.close',
            'uses' => 'OperationController@close'
        ]);

        Route::post('delete', [
            'as' => 'operation.delete',
            'uses' => 'OperationController@delete',
        ]);

        Route::get('{id}', 'OperationController@index');

        Route::get('find/{id}', 'OperationController@find');

        Route::get('/{id}/paps', [
            'as'   => 'operation.paps',
            'uses' => 'OperationController@paps',
        ]);

    });

    Route::group([
        'prefix' => 'setting',
        'middleware' => 'can:calendar.setup'
    ], function() {

        Route::get('/', [
            'as' => 'setting.index',
            'uses' => 'SettingController@index'
        ]);


        Route::group([
            'prefix' => 'slack'
        ], function() {

            Route::post('update', [
                'as' => 'setting.slack.update',
                'uses' => 'SettingController@updateSlack'
            ]);

        });

        Route::group([
            'prefix' => 'tag'
        ], function() {

            Route::post('create', [
            'as' => 'setting.tag.create',
            'uses' => 'TagController@store'
            ]);

            Route::post('delete', [
                'as' => 'setting.tag.delete',
                'uses' => 'TagController@delete'
            ]);

            Route::get('show/{id}', [
                'as' => 'tags.show',
                'uses' => 'TagController@get',
                'middleware' => 'can:calendar.setup',
            ]);

            Route::post('update', [
                'as' => 'setting.tag.update',
                'uses' => 'TagController@store'
            ]);

        });

    });

    Route::group([
        'prefix' => 'lookup'
    ], function() {

        Route::get('characters', 'LookupController@lookupCharacters')->name('calendar.lookups.characters');
        Route::get('systems', 'LookupController@lookupSystems')->name('calendar.lookups.systems');
        Route::get('attendees', 'LookupController@lookupAttendees');
        Route::get('confirmed', 'LookupController@lookupConfirmed');

    });


});
