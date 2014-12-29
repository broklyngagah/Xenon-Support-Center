<?php

App::before(function ($request) {

    if (Session::has('client_ip')) {

        //check ip_address in blocking table
        $blocking = Blocking::where('ip_address', Session::get('client_ip'))->first();

        if (!empty($blocking)) {

            if ($blocking->should_block_web_access) {
                return trans('msgs.you_dont_have_website_access');
            }

            $path = $request->path();

            if ($path == "login" && $blocking->should_block_login) {
                return trans('msgs.you_dont_have_login_access');
            }

            if ($path == "customer/tickets" && $blocking->should_block_tickets) {
                return trans('msgs.you_dont_have_tickets_access');
            }

        }

    }

});

Route::filter('has_permission', function ($route, $request, $permission) {

    if (Auth::check()) {

        if (!\KodeInfo\Utilities\Utils::canViewBackend(Auth::user()->id)) {
            Auth::logout();
            Session::flush();
            Session::flash('error_msg', trans('msgs.access_denied_escalate_rights'));
            return Redirect::to('/login');
        }

        if (!Permissions::hasPermission($permission)) {
            $permission_obj = Permissions::where('key', $permission)->pluck('text');
            Session::flash('error_msg', trans('msgs.access_denied_escalate_rights', ['permission_obj' => $permission_obj]));
            return Redirect::to('/dashboard');
        }

    } else {
        Session::flash('error_msg', trans('msgs.please_login_to_continue'));
        return Redirect::to('/login');
    }

});

Route::filter('backend', function () {

    if (Auth::check()) {
        if (!\KodeInfo\Utilities\Utils::canViewBackend(Auth::user()->id)) {
            Auth::logout();
            Session::flush();
            Session::flash('error_msg', trans('msgs.access_denied_escalate_rights'));
            return Redirect::to('/login');
        }
    } else {
        Session::flash('error_msg', trans('msgs.please_login_to_continue'));
        return Redirect::to('/login');
    }

});

Route::filter('department-admin', function () {
    if (Auth::check()) {

        if (!\KodeInfo\Utilities\Utils::isDepartmentAdmin(Auth::user()->id)) {
            Session::flash('error_msg', trans('msgs.access_denied_escalate_rights'));
            return Redirect::to('/dashboard');
        }

    } else {
        Session::flash('error_msg', trans('msgs.please_login_to_continue'));
        return Redirect::to('/login');
    }
});

Route::filter('admin', function () {
    //Someone is loggedin maybe admin / customer
    if (Auth::check()) {

        if (!\KodeInfo\Utilities\Utils::isAdmin(Auth::user()->id)) {
            Session::flash('error_msg', trans('msgs.access_denied_escalate_rights'));
            return Redirect::to('/dashboard');
        }

    } else {
        Session::flash('error_msg', trans('msgs.please_login_to_continue'));
        return Redirect::to('/login');
    }
});

Route::filter('operator', function () {

    if (Auth::check()) {

        if (!\KodeInfo\Utilities\Utils::isOperator(Auth::user()->id)) {
            Session::flash('error_msg', trans('msgs.access_denied_escalate_rights'));
            return Redirect::to('/dashboard');
        }

    } else {
        Session::flash('error_msg', trans('msgs.please_login_to_continue'));
        return Redirect::to('/login');
    }
});

Route::filter('csrf', function () {
    if (Session::token() != Input::get('_token')) {
        throw new Illuminate\Session\TokenMismatchException;
    }
});
