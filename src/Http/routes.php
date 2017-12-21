<?php

Route::group([
    'namespace' => 'Seat\Kassie\Calendar\Http\Controllers',
    'middleware' => ['web', 'auth'],
    'prefix' => 'character',
], function() {

    Route::get('/{character_id}/paps', [
        'as' => 'character.view.paps',
        'uses' => 'CharacterController@paps',
        'middleware' => 'characterbouncer:kassie_calendar_paps',
    ]);

});

Route::group([
	'namespace' => 'Seat\Kassie\Calendar\Http\Controllers',
	'middleware' => ['web', 'auth', 'bouncer:calendar.view'],
	'prefix' => 'calendar'
], function () {

    Route::group([
        'namespace' => 'Service',
        'prefix' => 'auth',
    ], function(){

        Route::get('/', [
            'as' => 'calendar.auth',
            'uses' => 'EsiController@redirectToAuth',
        ]);

        Route::get('/callback', [
            'as' => 'calendar.auth.callback',
            'uses' => 'EsiController@authCallback',
        ]);

    });

    Route::group([
        'prefix' => 'ajax'
    ], function(){

        Route::get('/operation/{id}', [
            'as' => 'operation.detail',
            'uses' => 'AjaxController@getDetail'
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
			'middleware' => 'bouncer:calendar.create'
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
		'middleware' => 'bouncer:calendar.setup'
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

		});

	});

	Route::group([
		'prefix' => 'lookup'
	], function() {

		Route::get('characters', 'LookupController@lookupCharacters');
		Route::get('systems', 'LookupController@lookupSystems');
		Route::get('attendees', 'LookupController@lookupAttendees');

	});


});