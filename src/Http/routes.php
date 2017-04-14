<?php

Route::group([
	'namespace' => 'Seat\Kassie\Calendar\Http\Controllers',
	'middleware' => ['web', 'bouncer:calendar.view'],
	'prefix' => 'calendar'
], function () {

	Route::resource('operation', 'OperationController', [
		'only' => ['index', 'store'],
		'names' => [
			'index' => 'operation.index'
		]
	]);

	Route::post('operation/subscribe', [
		'as' => 'operation.subscribe',
		'uses' => 'OperationController@subscribe'
	]);

	Route::post('operation/cancel', [
		'as' => 'operation.cancel',
		'uses' => 'OperationController@cancel'
	]);

	Route::post('operation/activate', [
		'as' => 'operation.activate',
		'uses' => 'OperationController@activate'
	]);

	Route::post('operation/close', [
		'as' => 'operation.close',
		'uses' => 'OperationController@close'
	]);

	Route::post('operation/delete', [
		'as' => 'operation.delete',
		'uses' => 'OperationController@delete'
	]);

	Route::post('operation/update', [
		'as' => 'operation.update',
		'uses' => 'OperationController@update'
	]);

	Route::get('operation/{id}', 'OperationController@index');

	Route::group([
		'middleware' => 'bouncer:calendar.setup'
	], function() {
		Route::get('setting', [
			'as' => 'setting.index',
			'uses' => 'SettingController@index'
		]);
		Route::post('setting/update/slack', [
			'as' => 'setting.update.slack',
			'uses' => 'SettingController@updateSlack'
		]);
		Route::post('setting/tag/create', [
			'as' => 'setting.tag.create',
			'uses' => 'TagController@store'
		]);
		Route::post('setting/tag/delete', [
			'as' => 'setting.tag.delete',
			'uses' => 'TagController@delete'
		]);
	});

	Route::get('operation/find/{id}', 'OperationController@find');

	Route::get('lookup/characters', 'LookupController@lookupCharacters');
	Route::get('lookup/systems', 'LookupController@lookupSystems');
});