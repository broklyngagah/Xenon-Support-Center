@extends('layouts.master')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-title">
            <h3>Settings
                <small>Control panel.</small>
            </h3>
        </div>
    </div>
    <!-- /page header -->
    <!-- Breadcrumbs line -->
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li>
                <a href="/">Home</a>
            </li>
            <li class="active">
                Settings
            </li>
        </ul>
    </div>
    <!-- /breadcrumbs line -->

    @include('layouts.notify')


    <div class="panel panel-default">
        <div class="panel-body">

            <div class="tabbable">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab-mailgun" data-toggle="tab"><i class="icon-envelop"></i> Mailgun
                        </a></li>
                    <li><a href="#tab-smtp" data-toggle="tab"><i class="icon-envelop"></i> SMTP </a></li>
                    <li><a href="#tab-mailchimp" data-toggle="tab"><i class="icon-envelop"></i> Mailchimp</a></li>
                    <li><a href="#tab-tickets" data-toggle="tab"><i class="icon-ticket"></i> Tickets</a></li>
                </ul>
                <div class="tab-content with-padding">

                    <div class="tab-pane fade in active" id="tab-mailgun">

                        {{Form::open(['url'=>'/settings/mailgun','method'=>'post'])}}

                        <div class="form-group">

                            <div style="margin:5px;" class="alert alert-info">
                                If use mailgun is not selected SMTP settings will be used for sending emails
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label>From Email Address:</label>
                                    <input type="text" class="form-control" placeholder="Enter email address"
                                           name="from_address"
                                           value="{{Input::get('from_address',$settings->mailgun->from_address)}}">
                                </div>
                                <div class="col-md-6">
                                    <label>From Name:</label>
                                    <input type="text" class="form-control" placeholder="Enter name" name="from_name"
                                           value="{{Input::get('from_name',$settings->mailgun->from_name)}}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Reply to Email Address:</label>
                                    <input type="text" class="form-control" placeholder="Enter reply to email address"
                                           name="reply_to"
                                           value="{{Input::get('reply_to',$settings->mailgun->reply_to)}}">
                                </div>
                                <div class="col-md-6">
                                    <label>Domain / Host:</label>
                                    <input type="text" class="form-control" placeholder="Enter domain/smtp host"
                                           name="domain" value="{{Input::get('domain',$settings->mailgun->domain)}}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="mailchimp_div">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Mailgun API Key:</label>
                                    <input type="text" class="form-control" name="api_key"
                                           value="{{Input::old('api_key',$settings->mailgun->api_key)}}"
                                           placeholder="Enter API Key">
                                </div>
                                <div class="col-md-6">
                                    <label>Public API Key:</label>
                                    <input type="text" class="form-control" placeholder="Enter Public API Key"
                                           name="public_api_key"
                                           value="{{Input::get('public_api_key',$settings->mailgun->public_api_key)}}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="checkbox-inline checkbox-info">
                                        <input type="checkbox" class="styled"
                                               name="use_mailgun" {{Input::get('use_mailgun',$settings->mailgun->use_mailgun)==1?"checked":""}}
                                               value="1">
                                        <label>Use Mailgun</label>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions text-right">
                            <input type="submit" value="Save" class="btn btn-success">
                        </div>

                        {{Form::close()}}

                    </div>

                    <div class="tab-pane fade" id="tab-smtp">

                        <div style="margin:5px;" class="alert alert-info">
                            If use mailgun is selected then mailgun will be used for sending emails
                        </div>

                        {{Form::open(['url'=>'/settings/smtp','method'=>'post'])}}

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>From Email Address:</label>
                                    <input type="text" class="form-control" placeholder="Enter email address"
                                           name="from_address"
                                           value="{{Input::get('from_address',$settings->smtp->from_address)}}">
                                </div>
                                <div class="col-md-6">
                                    <label>From Name:</label>
                                    <input type="text" class="form-control" placeholder="Enter name" name="from_name"
                                           value="{{Input::get('from_name',$settings->smtp->from_name)}}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Reply to Email Address:</label>
                                    <input type="text" class="form-control" placeholder="Enter reply to email address"
                                           name="reply_to_address"
                                           value="{{Input::get('reply_to',$settings->smtp->reply_to_address)}}">
                                </div>
                                <div class="col-md-6">
                                    <label>Reply to Name:</label>
                                    <input type="text" class="form-control" placeholder="Enter reply to email address"
                                           name="reply_to_name"
                                           value="{{Input::get('reply_to',$settings->smtp->reply_to_name)}}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>SMTP Username:</label>
                                    <input type="text" class="form-control" placeholder="Enter smtp username"
                                           name="username" value="{{Input::get('username',$settings->smtp->username)}}">
                                </div>
                                <div class="col-md-6">
                                    <label>SMTP Password:</label>
                                    <input type="password" class="form-control" placeholder="Enter smtp password"
                                           name="password" value="{{Input::get('password',$settings->smtp->password)}}">
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Host:</label>
                                    <input type="text" class="form-control" placeholder="Enter SMTP Host" name="host"
                                           value="{{Input::get('host',$settings->smtp->host)}}">
                                </div>
                            </div>
                        </div>

                        <div class="form-actions text-right">
                            <input type="submit" value="Save" class="btn btn-success">
                        </div>

                        {{Form::close()}}

                    </div>

                    <div class="tab-pane fade" id="tab-mailchimp">

                        <div style="margin:5px;" class="alert alert-info">
                            You can design templates using mailchimp template designer and pair them using Pair Email to
                            Template under Mailchimp Templates Menu which will be shown only if use mailchimp templates
                            for email is checked . As a fallback option if pairing is not present we will use predefined
                            mail templates .
                        </div>

                        {{Form::open(['url'=>'/settings/mailchimp','method'=>'post'])}}

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Mailchimp API Key:</label>
                                    <input type="text" class="form-control" name="api_key"
                                           value="{{Input::old('api_key',$settings->mailchimp->api_key)}}"
                                           placeholder="Enter API Key">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="checkbox-inline checkbox-info">
                                        <input name="use_mailchimp" type="checkbox"
                                               class="styled" {{Input::old('use_mailchimp',$settings->mailchimp->use_mailchimp)==1?"checked":""}}
                                               value="1">
                                        <label>Use Mailchimp Templates for Emails</label>
                                    </label>
                                </div>
                            </div>
                        </div>


                        <div class="form-actions text-right">
                            <input type="submit" value="Save" class="btn btn-success">
                        </div>

                        {{Form::close()}}

                    </div>
                    <div class="tab-pane fade" id="tab-tickets">

                        {{Form::open(['url'=>'/settings/tickets','method'=>'post'])}}

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="checkbox-inline checkbox-info">
                                        <input {{$settings->tickets->should_send_email_ticket_reply==1?"checked":""}}
                                                name="should_send_email_ticket_reply" type="checkbox" class="styled"
                                                value="1">
                                        <label>Only send email when customer/operator not online</label>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <label class="checkbox-inline checkbox-info">
                                        <input type="checkbox" class="styled"
                                               name="convert_chat_ticket_no_operators" {{$settings->tickets->convert_chat_ticket_no_operators==1?"checked":""}}
                                               value="1">
                                        <label>Convert chat to ticket if no operator online</label>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions text-right">
                            <input type="submit" value="Save" class="btn btn-success">
                        </div>

                        {{Form::close()}}

                    </div>
                </div>
            </div>

        </div>
    </div>
@stop

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {

            var url = document.location.toString();

            if (url.match('#')) {
                $('.nav-tabs a[href=#' + url.split('#')[1] + ']').tab('show');
            }

            // Change hash for page-reload
            $('.nav-tabs li a').on('shown.bs.tab', function (e) {
                window.location.hash = e.target.hash;
            })
        });
    </script>
@stop