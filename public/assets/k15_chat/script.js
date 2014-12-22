(function ($) {

    $.fn.K15_Initialize = function (options) {

        var K15 = {
            domain: "http://support.dev",
            user_id: 0,
            thread_id: 0,
            last_message_id: 0,
            location_info: '',
            interval: options.hasOwnProperty("interval") ? options["interval"] : 5000,
            company_id: options.hasOwnProperty("company") ? options["company"] : alert('Company ID not found in options'),

            init: function () {

                //check for blocking and disallow IP from accessing
                $.get("http://ipinfo.io", function (response) {

                    K15.location_info = response;

                    $.ajax({
                        'type': 'GET',
                        'url': K15.domain + '/api/chat/init',
                        'data': {
                            'company': K15.company_id,
                            'ip_address': K15.location_info.ip
                        },
                        'success': function (data) {
                            //get all data and append to k15_chat_widget

                            var response = JSON.parse(data);

                            if (response.blocked) {
                                //errors = your ip have been blocked by admin contact support
                                $('#k15-errors').html(response.errors);
                                $('#k15-errors').show();
                            } else {
                                $('#k15-chat-widget').html(response.data.wrapper);

                                //if(response.in_conversation==1){
                                    K15.check_new_messages();
                                //}

                            }
                        }
                    });
                }, "jsonp");

            },

            send_message: function () {

                if ($('#k15_message').val() == "") {
                    return false; // do nothing
                } else {
                    $.ajax({
                        'type': 'POST',
                        'url': '/api/chat/send_message',
                        'data': {
                            'user_id': K15.user_id,
                            'thread_id': K15.thread_id,
                            'message': $('#k15-message').val()
                        },
                        'success': function (data) {
                            $('#k15-message').val("");
                        }
                    });

                }

                return false;

            },

            start: function () {

                var data = {
                    name: $('#k15-form-name').val(),
                    email: $('#k15-form-email').val(),
                    department: $("#k15-form-department option:selected").val(),
                    message: $("#k15-form-message").val(),
                    company_id: K15.company_id,
                    domain: location.protocol + '//' + location.hostname,
                    page: document.URL,
                    ip: K15.location_info.ip,
                    country: K15.location_info.country,
                    provider: K15.location_info.org
                };

                $.post("/api/chat/start", data, function (data, status) {

                    data = JSON.parse(data);

                    if (data.blocked) {
                        //errors = your ip have been blocked by admin contact support
                        $('#k15-errors').html(data.errors);
                        $('#k15-errors').show();
                        return false;

                    }

                    if (data.result == 0) {
                        $('#k15-errors').html(data.errors);
                        $('#k15-errors').show();
                    } else {
                        if (data.is_online == 1) {
                            K15.thread_id = data.thread_id;
                            K15.user_id = data.user_id;
                            $('#k15-chat-view .chat').html("");
                            K15.check_new_messages();
                        } else {
                            $('#k15-errors').html("");
                            $('#k15-errors').hide();
                            $('#k15-success').html(data.success_msg);
                            $('#k15-success').show();
                        }
                    }

                });

                return false;

            },

            end: function () {
                $.ajax({
                    'type': 'GET',
                    'url': '/api/chat/end',
                    'data': {
                        'thread_id': K15.thread_id
                    },
                    'success': function (data) {
                    }
                });
            },

            check_new_messages: function () {

                $.ajax({
                    'type': 'GET',
                    'url': '/api/chat/check_new_messages',
                    'data': {
                        'user_id': K15.user_id,
                        'thread_id': K15.thread_id,
                        'company_id': K15.company_id,
                        'last_message_id': K15.last_message_id,
                        'page': document.URL
                    },
                    'success': function (data) {

                        var response = JSON.parse(data);

                        if (response.is_online) {
                            K15.change_status(response.is_online);
                        }

                        if (response.in_conversation && !response.conversation_closed) {

                            K15.thread_id = response.thread_id;
                            K15.user_id = response.user_id;
                            K15.last_message_id = response.messages.last_message_id;

                            $('#k15-chat-view .chat').append(response.messages.messages_str);

                            if (response.messages.messages_str.length > 0) {
                                var audio = new Audio('/assets/message.mp3');
                                audio.play();
                            }

                            $('#k15-form-view').hide();
                            $('#k15-chat-view').show();
                            $('#k15-chat-footer').show();
                            $('#k15-footer-view').show();

                            K15.to_bottom();

                        } else {

                            if (response.conversation_closed) {
                                K15.thread_id = 0;
                                K15.user_id = 0;
                                K15.last_message_id = 0;
                                $('#k15-errors').html("");
                                $('#k15-errors').hide();
                                $('#k15-success').html(response.success_msg);
                                $('#k15-success').show();
                            }

                            $('#k15-form-view').show();
                            $('#k15-chat-view').hide();
                            $('#k15-chat-footer').hide();
                            $('#k15-footer-view').hide();

                            var $select = $('#k15-form-department');

                            var selected = $select.find('option:selected').val();

                            $select.find('option').remove();

                            $.each(response.departments, function (key, value) {
                                $select.append('<option value=' + value.id + '>' + value.name + '</option>');
                            });

                            $('#k15-form-department option[value="' + selected + '"]').prop('selected', true);

                        }

                    },
                    'complete': function () {
                        setTimeout(K15.check_new_messages, K15.interval);
                    }
                });

            },

            to_bottom: function () {
                var objDiv = document.getElementById("k15-chat-view");
                objDiv.scrollTop = objDiv.scrollHeight;
            },

            change_status: function (is_online) {
                if (is_online) {
                    $("#k15-widget-title").html("Contact us - Online");
                } else {
                    $("#k15-widget-title").html("Contact us - Offline");
                }
            }
        };

        K15.init();

        $('#k15-message').keypress(function (e) {

            if (e.which == 13) {
                K15.send_message();
            }

        }).focus(function () {

            if ($('#k15-message').val() == "") {
                $('#k15-message').val("");
            }

        }).blur(function () {

            if ($('#k15-message').val() == "") {
                $('#k15-message').val("");
            }

        });

        $(document).on('click','#k15-message-send', function () {
            K15.send_message();
        });

        $(document).on('click','#k15-end', function () {
            K15.end();
        });

        $(document).on('click','#k15-start', function () {
            K15.start();
        });

    };

}(jQuery));
