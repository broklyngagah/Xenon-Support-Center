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
            <h3>Messages </h3>
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
                <div class="panel-body" style="height:250px;overflow: auto;">
                    <dl style="margin: 0;">
                        <dt class="text-info">IP Address</dt>
                        <dd>{{{$geo->ip_address}}}</dd>
                        <dt class="text-info">Country</dt>
                        <dd>{{{$geo->country}}}</dd>
                        <dt class="text-info">Currently Viewing</dt>
                        <dd><span id="current_page">{{{$geo->current_page}}}</span></dd>
                        <dt class="text-info">Previous Pages</dt>
                        <span id="previous_pages">
                        @foreach($geo_pages->pages as $page)
                            <dd>{{{$page}}}</dd>
                        @endforeach
                        </span>
                    </dl>
                </div>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading">
                    <h6 class="panel-title"><i class="icon-envelop"></i> Canned Messages</h6>
                </div>
                <div class="panel-body" style="height:385px;overflow: auto;">
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

                    <div class="chat" style="height:300px;overflow: auto;" id="chat_messages">
                        {{$message_str}}
                    </div>
                    <div id="msg"></div>
                    {{Form::open(['url'=>'/conversations/send_message','method'=>'post','files'=>true,'id'=>'reply_submit'])}}
                    @if(!isset($closed_conversation))
                    <textarea id="message_body" class="form-control" rows="3" cols="1"
                                                                               placeholder="Enter your message..."></textarea>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label></label>
                                    <input id="attachment" type="file" name="attachment" class="styled form-control">
                                    <span class="help-block">Accepted formats: rar, zip . Max file size 10Mb</span>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="message-controls">
                        <div class="pull-right">
                            <button  {{isset($closed_conversation)?"disabled='disabled'":""}} id="send_message" type="submit" class="btn btn-success btn-loading"
                                                                                             data-loading-text="<i class='icon-spinner7 spin'></i> Processing">
                                Send
                            </button>

                            <a href="/conversations/close/{{$thread->id}}" {{isset($closed_conversation)?"disabled='disabled'":""}} id="end_chat" type="button" class="btn btn-danger btn-loading"
                               data-loading-text="<i class='icon-spinner7 spin'></i> Processing">
                                End Chat
                            </a>

                            @if(!isset($closed_conversation))
                            <a href="/conversations/transfer/{{$online->id}}" {{isset($closed_conversation)?"disabled='disabled'":""}} id="transfer_chat" type="button" class="btn btn-primary btn-loading"
                               data-loading-text="<i class='icon-spinner7 spin'></i> Processing">
                                Transfer Chat
                            </a>
                            @endif

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
    @if(!isset($closed_conversation))
    <script type="text/javascript">
        $(document).ready(function () {

            CKEDITOR.replace( 'message_body' );

            var thread_id = {{$thread->id}}
            var sender_id = {{$thread->sender_id}}
            var operator_id = {{$thread->operator_id}}
            var last_message_id = {{$last_message_id}};

            $('.canned_messages').click(function () {
                CKEDITOR.instances.message_body.setData($(this).find('p').html());
            });

            var interval = 3000;  // 1000 = 1 second, 3000 = 3 seconds

            function getMessages() {

                $.ajax({
                    'type': 'GET',
                    'url': '/conversations/get_server_messages',
                    'async': false,
                    'data': {
                        'user_id': operator_id,
                        'thread_id': thread_id,
                        'last_message_id': last_message_id
                    },
                    'success': function (data) {
                        var response = JSON.parse(data);

                        if(response.close_conversation){
                            window.location = "/conversations/all";
                        }

                        last_message_id = response.messages.last_message_id;

                        if(response.messages.messages_str.length>0){
                            var audio = new Audio('/assets/message.mp3');
                            audio.play();
                        }

                        $('#chat_messages').append(response.messages.messages_str);
                        $('#current_page').html(response.current_page);

                        var $all_pages = $('#previous_pages');

                        $all_pages.html("");

                        $.each(response.all_pages, function(key, value) {
                            $all_pages.append('<dd>' + value + '</dd>');
                        });

                        toBottom();
                    },
                    'complete':function(){
                        setTimeout(getMessages, interval);
                    }
                });

            }

            function toBottom(){
                var objDiv = document.getElementById("chat_messages");
                objDiv.scrollTop = objDiv.scrollHeight;
            }

            getMessages();

            $('#reply_submit').submit(function () {

                for ( instance in CKEDITOR.instances )
                    CKEDITOR.instances[instance].updateElement();

                var options = {
                    success: showResponse,  // post-submit callback
                    data: {
                        'user_id': operator_id,
                        'thread_id': thread_id,
                        'message': CKEDITOR.instances.message_body.getData()
                    },
                    resetForm: true
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

                $('#uniform-attachment .filename').html("No file selected");

                if(json.result==0){
                    $('#reply_errors').html(json.errors);
                    $('#reply_errors').show();
                }else{
                    CKEDITOR.instances.message_body.setData("");
                    $('#reply_errors').html("");
                    $('#reply_errors').hide();
                }

                return false;

            }
        });
    </script>
    @endif
@stop