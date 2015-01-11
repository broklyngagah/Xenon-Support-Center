<?php

View::composer('layouts.master', function($view)
{
    $raw_settings  = Settings::all();

    $settings = new StdClass();

    foreach($raw_settings as $raw_setting){
        $settings->{$raw_setting->key} = json_decode($raw_setting->value);
    }

    $view->with('settings',$settings);
    $view->with('uni_department_id', User::getUniDepartment());
    $view->with('uni_company_id', User::getUniCompany());
});

Route::get('/', 'AuthController@getLogin');

/*
Route::get('/setup', 'DashboardController@getSetup');
Route::post('/setup', 'DashboardController@postSetup');
*/

Route::get('/clean-everything', 'DashboardController@cleanDB');
Route::get('/activities/all', 'DashboardController@allActivities');
Route::get('/users/all', 'DashboardController@allUsers');
Route::get('/profile', 'AuthController@profile');
Route::get('/login', 'AuthController@getLogin');
Route::get('/register', 'AuthController@getRegister');
Route::get('/forgot-password', 'AuthController@getForgotPassword');
Route::get('/reset/{email}/{code}', 'AuthController@getReset');
Route::get('/activate/{user_id}/{activation_code}', 'AuthController@activateUser');
Route::get('/facebook', 'AuthController@signInWithFacebook');
Route::get('/logout', 'AuthController@logout');
Route::get('/change_password', 'AuthController@getChangePassword');
Route::post('/change_password', 'AuthController@postChangePassword');

Route::group(['filter' => 'csrf'], function() {
    Route::post('/login', 'AuthController@postLogin');
    Route::post('/profile', 'AuthController@storeProfile');
    Route::post('/register', 'AuthController@postRegister');
    Route::post('/forgot-password', 'AuthController@postForgotPassword');
    Route::post('/reset/change-password', 'AuthController@postReset');
});

Route::get('/dashboard', array('before' => 'backend', 'as' => 'dashboard', 'uses' => "DashboardController@index"));
Route::get('chat', function(){return View::make('chat');});

//Translation
Route::group(['prefix' => 'translations'], function () {
    Route::get('all', 'TranslationController@all');
    Route::get('view/{id}', 'TranslationController@view');
    Route::get('create', 'TranslationController@create');
    Route::get('delete/{id}', 'TranslationController@delete');

    Route::group(['filter' => 'csrf'], function() {
        Route::post('create', 'TranslationController@create');
    });
});

//Settings
Route::group(['prefix' => 'settings'], function () {
    Route::get('all', 'SettingsController@all');

    Route::group(['filter' => 'csrf'], function() {
       Route::post('mailgun', 'SettingsController@setMailGun');
       Route::post('smtp', 'SettingsController@setSMTP');
       Route::post('mailchimp', 'SettingsController@setMailchimp');
       Route::post('tickets', 'SettingsController@setTickets');
   });
});

//API
Route::group(['prefix' => 'templates'], function () {
    Route::get('all', 'TemplatesController@all');
    Route::get('view/{template_id}', 'TemplatesController@view');
    Route::get('pair/all', 'TemplatesController@getPairAll');
    Route::get('pair/create', 'TemplatesController@createPair');
    Route::get('pair/preview/{pair_id}', 'TemplatesController@previewPair');
    Route::get('pair/delete/{pair_id}', 'TemplatesController@deletePair');

    Route::group(['filter' => 'csrf'], function() {
        Route::post('pair/create', 'TemplatesController@storePair');
    });
});

//API
Route::group(['prefix' => 'api'], function () {

    Route::group(['prefix' => 'chat'], function () {
        Route::get('init', 'ChatAPIController@init');
        Route::post('start', 'ChatAPIController@start');
        Route::post('send_message', 'ChatAPIController@sendMessage');
        Route::get('end', 'ChatAPIController@end');
        Route::get('check_new_messages', 'ChatAPIController@checkNewMessages');
    });

    Route::get('get_company_operators/company/{company_id}', 'APIController@getCompanyOperators');
    Route::get('get_department_operators/{department_id}', 'APIController@getDepartmentOperators');
    Route::get('get_department_operators_with_admin/{department_id}', 'APIController@getDepartmentOperatorsWithAdmin');
    Route::get('get_company_departments/{company_id}', 'APIController@getCompanyDepartments');
    Route::get('get_company_free_admins/{company_id}', 'APIController@getCompanyFreeDepartmentAdmins');
    Route::get('get_department_permissions/{department_id}', 'APIController@getDepartmentPermissions');
    Route::get('log_ip', 'APIController@logIP');
    Route::get('get_code/{company_id}', 'APIController@getCode');

    Route::get('change_status/{status}', 'APIController@changeStatus');
    Route::get('online_conversations_refresh', 'APIController@conversationsRefresh');
    Route::get('master_refresh', 'APIController@masterRefresh');
    Route::get('tickets_all_refresh', 'APIController@ticketsRefresh');
});

//Conversations
Route::group(['prefix' => 'conversations'], function () {
    Route::get('all', 'ConversationsController@all');
    Route::get('create', 'ConversationsController@create');
    Route::get('close/{thread_id}', 'ConversationsController@closeConversation');
    Route::get('delete/{thread_id}', 'ConversationsController@deleteConversation');
    Route::get('accept/{id}', 'ConversationsController@accept');
    Route::get('read/{id}', 'ConversationsController@read');
    Route::get('get_client_messages', 'ConversationsController@getClientMessages');
    Route::get('get_server_messages', 'ConversationsController@getServerMessages');
    Route::get('closed', 'ConversationsController@closedConversations');
    Route::post('send_message', 'ConversationsController@sendMessage');

    Route::get('transfer/{transfer_id}', 'ConversationsController@transfer');
    Route::post('transfer/{transfer_id}', 'ConversationsController@storeTransfer');
});

//Blocking
Route::group(['prefix' => 'blocking'], function () {
    Route::get('all', 'BlockingController@all');
    Route::get('create', 'BlockingController@create');
    Route::get('delete/{id}', 'BlockingController@delete');

    Route::group(['filter' => 'csrf'], function() {
        Route::post('create', 'BlockingController@store');
    });
});

//Canned messages
Route::group(['prefix' => 'canned_messages'], function () {
    Route::get('all', 'CannedMessagesController@all');
    Route::get('create', 'CannedMessagesController@create');
    Route::get('delete/{message_id}', 'CannedMessagesController@delete');

    Route::group(['filter' => 'csrf'], function() {
        Route::post('store', 'CannedMessagesController@store');
    });
});

//Accounts Routes
Route::group(['prefix' => 'accounts'], function () {
    Route::get('create', 'AccountsController@create');
    Route::get('all', 'AccountsController@all');
    Route::get('delete/{account_id}', 'AccountsController@delete');
    Route::get('update/{account_id}', 'AccountsController@edit');
    Route::get('activate/{account_id}', 'AccountsController@activateAccount');

    Route::group(['filter' => 'csrf'], function() {
        Route::post('create', 'AccountsController@store');
        Route::post('update/{account_id}', 'AccountsController@update');
    });
});

//Operators Routes
Route::group(['prefix' => 'operators'], function () {
    Route::get('create', 'OperatorsController@create');
    Route::get('all', 'OperatorsController@all');
    Route::get('online', 'OperatorsController@online');
    Route::get('delete/{operator_id}', 'OperatorsController@delete');
    Route::get('update/{operator_id}', 'OperatorsController@edit');
    Route::get('activate/{account_id}', 'AccountsController@activate');

    Route::group(['filter' => 'csrf'], function() {
        Route::post('create', 'OperatorsController@store');
        Route::post('update/{operator_id}', 'OperatorsController@update');
    });
});

//Tickets Routes
Route::group(['prefix' => 'tickets'], function () {
    Route::get('create', 'TicketsController@create');
    Route::get('all', 'TicketsController@all');
    Route::get('pending', 'TicketsController@pending');
    Route::get('resolved', 'TicketsController@resolved');
    Route::get('delete/{ticket_id}', 'TicketsController@delete');
    Route::get('read/{ticket_id}', 'TicketsController@read');
    Route::get('get_ticket_messages', 'TicketsController@getTicketMessages');
    Route::get('transfer/{ticket_id}', 'TicketsController@transfer');

    Route::get('customers/{customer_id}/{status}', 'TicketsController@getStatusTickets');

    //Operators Routes
    Route::group(['prefix' => 'customer'], function () {
        Route::get('create', 'CustomersTicketsController@create');
        Route::get('all', 'CustomersTicketsController@all');
        Route::get('read/{ticket_id}', 'CustomersTicketsController@read');
        Route::get('pending', 'CustomersTicketsController@pending');
        Route::get('resolved', 'CustomersTicketsController@resolved');
        Route::get('delete/{operator_id}', 'CustomersTicketsController@delete');
        Route::get('update/{operator_id}', 'CustomersTicketsController@edit');

        Route::group(['filter' => 'csrf'], function() {
            Route::post('create', 'CustomersTicketsController@store');
            Route::post('update', 'CustomersTicketsController@update');
        });
    });

    Route::group(['filter' => 'csrf'], function() {
        Route::post('create', 'TicketsController@store');
        Route::post('update', 'TicketsController@update');
        Route::post('transfer/{ticket_id}', 'TicketsController@storeTransfer');
    });
});

//Companies Routes
Route::group(['prefix' => 'companies'], function () {
    Route::get('create', 'CompaniesController@create');
    Route::get('all', 'CompaniesController@all');
    Route::get('update/{company_id}', 'CompaniesController@edit');
    Route::get('delete/{company_id}', 'CompaniesController@delete');
    Route::get('operators/{company_id}', 'CompaniesController@getOperators');
    Route::get('customers/{company_id}', 'CompaniesController@getCustomers');

    Route::group(['filter' => 'csrf'], function() {
        Route::post('create', 'CompaniesController@store');
        Route::post('update/{company_id}', 'CompaniesController@update');
        Route::post('operators/add', 'CompaniesController@addOperator');
    });
});

//Customers Routes
Route::group(['prefix' => 'customers'], function () {
    Route::get('create', 'CustomersController@create');
    Route::get('all', 'CustomersController@all');
    Route::get('delete/{customer_id}', 'CustomersController@delete');
    Route::get('update/{customer_id}', 'CustomersController@edit');

    Route::group(['filter' => 'csrf'], function() {
        Route::post('create', 'CustomersController@store');
        Route::post('update/{customer_id}', 'CustomersController@update');
    });
});

//Departments Routes
Route::group(['prefix' => 'departments'], function () {
    Route::get('/create', 'DepartmentsController@create');
    Route::get('/update/{id}', 'DepartmentsController@edit');
    Route::get('/get/{department_id}', 'DepartmentsController@get');
    Route::get('/all', 'DepartmentsController@all');
    Route::get('/delete/{group_id}', 'DepartmentsController@delete');

    Route::group(['filter' => 'csrf'], function() {
        Route::post('/create', 'DepartmentsController@store');
        Route::post('/update/{id}', 'DepartmentsController@update');
    });

    Route::group(['prefix' => 'admins'], function () {
        Route::get('/create', 'DepartmentAdminsController@create');
        Route::get('/update/{id}', 'DepartmentAdminsController@edit');
        Route::get('/all', 'DepartmentAdminsController@all');
        Route::get('/delete/{admin_id}', 'DepartmentAdminsController@delete');
        Route::get('/remove/{admin_id}', 'DepartmentAdminsController@remove');
        Route::get('activate/{account_id}', 'DepartmentAdminsController@activate');

        Route::group(['filter' => 'csrf'], function() {
            Route::post('/create', 'DepartmentAdminsController@store');
            Route::post('/update/{id}', 'DepartmentAdminsController@update');
        });

    });

});


//Permissions Routes
Route::group(['prefix' => 'permissions'], function () {

    Route::get('/get/{id}', 'PermissionsController@getPermission');
    Route::get('/create', 'PermissionsController@create');
    Route::get('/all', 'PermissionsController@all');
    Route::get('/delete/{id}', 'PermissionsController@delete');

    Route::group(['filter' => 'csrf'], function() {
        Route::post('/store', 'PermissionsController@store');
        Route::post('/update', 'PermissionsController@update');
    });

});

