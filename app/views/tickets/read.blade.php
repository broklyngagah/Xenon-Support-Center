@extends('layouts.master')

@section('styles')
    <style type="text/css">

        .canned_messages.chat-member p {
            padding: 4px;
        }

        .canned_messages:hover {
            background-color: rgba(0, 0, 0, .03);
            cursor: pointer;
        }

    </style>
@stop

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-title">
            <h3>Ticket Messages </h3>
        </div>
    </div>

    @include('layouts.notify')

    <!-- /page header -->
    <!-- Breadcrumbs line -->
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li>
                <a href="/dashboard">Home</a>
            </li>
            <li class="active">
                Messages
            </li>
        </ul>
    </div>
    <!-- /breadcrumbs line -->

    <div class="row">
        <div class="col-lg-3">

            <div class="panel panel-info">
                <div class="panel-heading">
                    <h6 class="panel-title"><i class="icon-info"></i> Info panel</h6>
                </div>
                <div class="panel-body" style="height:342px;overflow: auto;">


                    <dl style="margin: 0;">
                        <dt class="text-info">Name</dt>
                        <dd>{{{$ticket->customer->name}}}</dd>
                        <dt class="text-info">Email</dt>
                        <dd>{{{$ticket->customer->email}}}</dd>
                        <dt class="text-info">Country</dt>
                        <dd id="info_country">{{{$geo->country}}}</dd>
                        <dt class="text-info">IP Address</dt>
                        <dd id="info_ip">{{{$geo->ip_address}}}</dd>

                        <dt class="text-info">Priority</dt>
                        @if($ticket->priority==Tickets::PRIORITY_LOW)
                            <dd><label class="label label-primary">Low</label></dd>
                        @endif
                        @if($ticket->priority==Tickets::PRIORITY_MEDIUM)
                            <dd><label class="label label-primary">Medium</label></dd>
                        @endif
                        @if($ticket->priority==Tickets::PRIORITY_HIGH)
                            <dd><label class="label label-warning">High</label></dd>
                        @endif
                        @if($ticket->priority==Tickets::PRIORITY_URGENT)
                            <dd><label class="label label-danger">Urgent</label></dd>
                        @endif

                        <dt class="text-info">Status</dt>
                        @if($ticket->status==Tickets::TICKET_NEW)
                            <dd class="info_status"><label class="label label-warning">New</label></dd>
                        @endif
                        @if($ticket->status==Tickets::TICKET_PENDING)
                            <dd class="info_status"><label class="label label-primary">Pending</label></dd>
                        @endif
                        @if($ticket->status==Tickets::TICKET_RESOLVED)
                            <dd class="info_status"><label class="label label-success">Resolved</label></dd>
                        @endif

                    </dl>

                    <select class="form-control pull-right" name="auto_scroll" id="auto_scroll">
                        <option value="1">Auto Scroll</option>
                        <option value="2">No Auto Scroll</option>
                    </select>
                </div>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading">
                    <h6 class="panel-title"><i class="icon-envelop"></i> Canned Messages</h6>
                </div>
                <div class="panel-body" style="height:460px;overflow: auto;">
                    <ul class="message-list">
                        @foreach($canned_messages as $message)
                            <li data-id="{{$message->id}}" class="canned_messages">
                                <div class="clearfix">
                                    <div class="chat-member">
                                        <p>{{{$message->message}}}</p>
                                    </div>
                                </div>

                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>

        <div class="col-lg-9" style="margin-bottom: 20px;">

            <div class="panel panel-info">
                <div class="panel-heading">
                    <h6 class="panel-title"><i class="icon-bubbles"></i> Messages</h6>
                </div>
                <div class="panel-body">
                    <input type="hidden" id="thread_id" value="{{$thread->id}}"/>
                    <input type="hidden" id="sender_id" value="{{$thread->sender_id}}"/>
                    <input type="hidden" id="operator_id" value="{{$thread->operator_id}}"/>
                    <h4>Subject : {{{$ticket->subject}}}</h4>

                    <div class="chat" style="height:300px;overflow: auto;" id="chat_messages">
                        {{$message_str}}
                    </div>
                    <div id="msg"></div>

                    {{Form::open(['url'=>'/tickets/update','method'=>'post','files'=>true,'id'=>'reply_submit'])}}

                    <input type="hidden" name="thread_id" value="{{$thread->id}}"/>
                    <input type="hidden" name="user_id"
                           value="{{\KodeInfo\Utilities\Utils::isBackendUser($thread->operator_id)?$thread->operator_id:$thread->sender_id}}"/>

                    <div style="display: none;" id="reply_errors" style="margin:5px;" class="alert alert-danger">

                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Message:</label>
                                <textarea id="message_body" name="message" class="form-control" rows="3" cols="1"
                                          placeholder="Enter your message..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Status:</label><span class="info_status"></span>
                                <select id="status" class="form-control" name="status">
                                    <option {{$ticket->status==Tickets::TICKET_NEW?"selected":""}} value="{{Tickets::TICKET_NEW}}">
                                        New
                                    </option>
                                    <option {{$ticket->status==Tickets::TICKET_PENDING?"selected":""}}
                                            value="{{Tickets::TICKET_PENDING}}">Pending
                                    </option>
                                    <option {{$ticket->status==Tickets::TICKET_RESOLVED?"selected":""}}
                                            value="{{Tickets::TICKET_RESOLVED}}">Resolved
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Upload files:</label>
                                <input id="attachment" type="file" name="attachment" class="styled form-control">
                                <span class="help-block">Accepted formats: rar, zip . Max file size 10Mb</span>
                            </div>
                        </div>
                    </div>

                    <div class="message-controls">
                        <div class="pull-right">
                            <button type="submit" class="btn btn-success btn-loading">
                                Reply
                            </button>
                        </div>
                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>

    </div>

@stop

@section('scripts')

    {{HTML::script("/assets/js/plugins/ckeditor/ckeditor.js")}}
    <script src="http://malsup.github.com/jquery.form.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {

            CKEDITOR.replace( 'message_body' );

            var thread_id = {{$thread->id}}
            var user_id = {{\KodeInfo\Utilities\Utils::isBackendUser($thread->operator_id)?$thread->operator_id:$thread->sender_id}}
            var last_message_id = {{$last_message_id}};

            $('.canned_messages').on('click', function () {
                CKEDITOR.instances.message_body.setData($(this).find('p').html());
            });

            $('#reply_submit').submit(function () {

                for ( instance in CKEDITOR.instances )
                    CKEDITOR.instances[instance].updateElement();

                var options = {
                    success: showResponse,  // post-submit callback
                    resetForm: false
                };

                // inside event callbacks 'this' is the DOM element so we first
                // wrap it in a jQuery object and then invoke ajaxSubmit
                $(this).ajaxSubmit(options);

                // !!! Important !!!
                // always return false to prevent standard browser submit and page navigation
                return false;
            });

            function showResponse(responseText, statusText, xhr, $form) {

                var json = JSON.parse(responseText);

                $('#reply_submit').not('select').trigger('reset');
                $('#uniform-attachment .filename').html("No file selected");

                if(json.result==0){
                    $('#reply_errors').html(json.errors);
                    $('#reply_errors').show();
                }else{
                    $('#reply_errors').html("");
                    $('#reply_errors').hide();
                }

            }


            var interval = 3000;  // 1000 = 1 second, 3000 = 3 seconds

            function getMessages() {

                $.ajax({
                    'type': 'GET',
                    'url': '/tickets/get_ticket_messages',
                    'data': {
                        'user_id': user_id,
                        'thread_id': thread_id,
                        'last_message_id': last_message_id
                    },
                    'success': function (data) {
                        var response = JSON.parse(data);

                        last_message_id = response.messages.last_message_id;

                        if (response.messages.messages_str.length > 0) {
                            var audio = new Audio('/assets/message.mp3');
                            audio.play();
                        }

                        $('#chat_messages').append(response.messages.messages_str);

                        $('#info_country').html(response.geo.country);
                        $('#info_ip').html(response.geo.ip_address);

                        //$('#status option[value="'+response.ticket.status+'"]').prop('selected', true);

                        if(response.ticket.status==1){
                            $('.info_status').html('<label class="label label-warning">New</label>');
                        }

                        if(response.ticket.status==2){
                            $('.info_status').html('<label class="label label-primary">Pending</label>');
                        }

                        if(response.ticket.status==3){
                            $('.info_status').html('<label class="label label-success">Resolved</label>');
                        }

                        if($('#auto_scroll option:checked').val()==1)
                            toBottom();
                    },
                    'complete': function () {
                        setTimeout(getMessages, interval);
                    }
                });

            }

            function toBottom() {
                var objDiv = document.getElementById("chat_messages");
                objDiv.scrollTop = objDiv.scrollHeight;
            }

            getMessages();

        });
    </script>

@stop