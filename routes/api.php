<?php

use Illuminate\Http\Request;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group(['prefix' => '', 'middleware' => 'auth:api', 'namespace' => 'Api'], function () {	
	Route::get('get_companies', 'UserController@getCompanies');
	Route::get('report/{report_method}', 'ReportController@index');
    Route::get('user', 'UserController@auth');
    
    Route::apiResource('company', 'CompanyController');

    Route::group(['prefix' => 'company'], function () {

        Route::group(['prefix' => '{company_id}'], function () {

            Route::apiResources([
                'project' => 'ProjectController',
                'project_status' => 'ProjectStatusController',
                'project_type' => 'ProjectTypeController',
                'project_note_status' => 'ProjectNoteStatusController',
                'project_material_status' => 'ProjectMaterialStatusController',
                'trade_status' => 'TradeStatusController',
                'payment_type' => 'PaymentTypeController',
                'deposit_type' => 'DepositTypeController',
                'materials' => 'MaterialController',
                'unit_of_measurement' => 'UnitOfMeasurementController',
                'equipment_status' => 'CompanyEquipmentStatusController',
                'user' => 'UserController',
                'permission' => 'UserPermissionController',
                'upload' => 'FileUploadController',
                'equipment' => 'CompanyEquipmentController',
                'client' => 'ClientController',
            ]);

            Route::group(['prefix' => 'project'], function () {
        
                Route::group(['prefix' => '{project_id}'], function () {

                    Route::apiResources([

                        'phase' => 'ProjectPhaseController',
                        'project_material' => 'ProjectMaterialController',
                        'payment' => 'ProjectPaymentController',
                        'deposit' => 'ProjectDepositController',
                        
                    ]);

                });

            });

            Route::group(['prefix' => 'equipment'], function () {
        
                Route::group(['prefix' => '{equipment_id}'], function () {

                    Route::apiResources([

                        'history' => 'CompanyEquipmentHistoryController',
                        
                    ]);

                });

            });

            Route::group(['prefix' => 'client'], function () {
        
                Route::group(['prefix' => '{client_id}'], function () {

                    Route::apiResources([

                        'trade' => 'TradeController',
                        
                    ]);

                });

            });

        });
        
    });
    
});