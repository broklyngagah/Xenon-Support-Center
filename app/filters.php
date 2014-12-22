<?php

App::before(function ($request) {

    if (Session::has('client_ip')) {

        //check ip_address in blocking table
        $blocking = Blocking::where('ip_address', Session::get('client_ip'))->first();

        if (!empty($blocking)) {

            if ($blocking->should_block_web_access) {
                return "You dont have website access . Contact Support";
            }

            $path = $request->path();

            if ($path == "login"&&$blocking->should_block_login) {
                return "You dont have login access . Contact Support";
            }

            if ($path == "customer/tickets"&&$blocking->should_block_tickets) {
                return "You dont have tickets access . Contact Support";
            }

        }

    }

});

Route::filter('has_permission', function ($route, $request, $permission) {

    if (Auth::check()) {

        if (!\KodeInfo\Utilities\Utils::isBackendUser(Auth::user()->id)) {
            //Not a backend user then fuckoff
            Auth::logout();
            Session::flush();
            Session::flash('error_msg', 'Access has been denied. Please contact an administrator to escalate your rights.');
            return Redirect::to('/login');
        }

        if (!Permissions::hasPermission($permission)) {
            Session::flash('error_msg', 'Access has been denied. Please contact an administrator to escalate your rights.');
            return Redirect::to('/dashboard');
        }

    } else {
        Session::flash('error_msg', 'Please Login to continue');
        return Redirect::to('/login');
    }

});

Route::filter('backend', function () {

    if (Auth::check()) {
        if (!\KodeInfo\Utilities\Utils::isBackendUser(Auth::user()->id)) {
            Auth::logout();
            Session::flush();
            Session::flash('error_msg', 'Access has been denied. Please contact an administrator to escalate your rights.');
            return Redirect::to('/login');
        }
    } else {
        Session::flash('error_msg', 'Please Login to continue');
        return Redirect::to('/login');
    }

});

Route::filter('department-admin', function () {
    if (Auth::check()) {

        if (!\KodeInfo\Utilities\Utils::isDepartmentAdmin(Auth::user()->id)) {
            Session::flash('error_msg', 'Access has been denied. Please contact an administrator to escalate your rights.');
            return Redirect::to('/dashboard');
        }

    } else {
        Session::flash('error_msg', 'Please Login to continue');
        return Redirect::to('/login');
    }
});

Route::filter('admin', function () {
    //Someone is loggedin maybe admin / customer
    if (Auth::check()) {

        if (!\KodeInfo\Utilities\Utils::isAdmin(Auth::user()->id)) {
            Session::flash('error_msg', 'Access has been denied. Please contact an administrator to escalate your rights.');
            return Redirect::to('/dashboard');
        }

    } else {
        Session::flash('error_msg', 'Please Login to continue');
        return Redirect::to('/login');
    }
});

Route::filter('operator', function () {

    if (Auth::check()) {

        if (!\KodeInfo\Utilities\Utils::isOperator(Auth::user()->id)) {
            Session::flash('error_msg', 'Access has been denied. Please contact an administrator to escalate your rights.');
            return Redirect::to('/dashboard');
        }

    } else {
        Session::flash('error_msg', 'Please Login to continue');
        return Redirect::to('/login');
    }
});

Route::filter('csrf', function () {
    if (Session::token() != Input::get('_token')) {
        throw new Illuminate\Session\TokenMismatchException;
    }
});
