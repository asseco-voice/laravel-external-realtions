<?php

use Asseco\RemoteRelations\App\Http\Controllers\RemoteRelationController;
use Asseco\RemoteRelations\App\Http\Controllers\RemoteRelationServicesController;
use Asseco\RemoteRelations\App\Http\Controllers\ResolvedRemoteRelationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('api')
    ->middleware('api')
    ->group(function () {
        Route::get('remote-relation-services', [RemoteRelationServicesController::class, 'index'])
            ->name('remote-relation-services.index');

        Route::get('remote-relations/{remote_relation}/resolved', [ResolvedRemoteRelationController::class, 'show'])
            ->name('remote-relations.resolve');

        Route::post('remote-relations/many', [RemoteRelationController::class, 'storeMany'])
            ->name('remote-relations.store-many');

        Route::apiResource('remote-relations', RemoteRelationController::class);
    });
