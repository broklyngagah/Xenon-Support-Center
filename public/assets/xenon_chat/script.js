(function ($) {

    $.fn.XENON_Initialize = function (options) {


        function hasCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return true;
            }
            return false;
        }

        function getCookie(cookie_name) {
            var all_cookies = document.cookie;

            // Get all the cookies pairs in an array
            var cookie_array = all_cookies.split(';');

            // Now take key value pair out of this array
            for (var i = 0; i < cookie_array.length; i++) {
                var name = cookie_array[i].split('=')[0];
                var value = cookie_array[i].split('=')[1];

                if (cookie_name == name) {
                    return value;
                }
            }

            return null;
        }

        function createCookie(name, value, days) {
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                var expires = "; expires=" + date.toGMTString();
            }
            else var expires = "";
            document.cookie = name + "=" + value + expires + "; path=/";
        }

        var XENON = {
            domain: "http://162.243.17.238",
            user_id: 0,
            thread_id: 0,
            token: 0,
            last_message_id: 0,
            location_info: '',
            interval: options.hasOwnProperty("interval") ? options["interval"] : 5000,
            company_id: options.hasOwnProperty("company") ? options["company"] : alert('Company ID not found in options'),

            init: function () {

                //check for blocking and disallow IP from accessing
                $.get("http://ipinfo.io", function (response) {

                    XENON.location_info = response;

                    $.ajax({
                        'type': 'GET',
                        'url': XENON.domain + '/api/chat/init',
                        'data': {
                            'company': XENON.company_id,
                            'ip_address': XENON.location_info.ip,
                            'token' : hasCookie('xenon_chat_box')?getCookie('xenon_chat_box'):0
                        },
                        'success': function (response) {
                            //get all data and append to xenon_chat_widget
                            if (response.blocked) {
                                //errors = your ip have been blocked by admin contact support
                                $('#xenon-errors').html(response.errors);
                                $('#xenon-errors').show();
                            } else {
                                $('#xenon-chat-widget').html(response.data.wrapper);
                                XENON.check_new_messages();
                            }
                        }
                    });
                }, "jsonp");

            },

            send_message: function () {

                if ($('#xenon-message').val() == "") {
                    return false; // do nothing
                } else {

                    var contentType ="application/x-www-form-urlencoded; charset=utf-8";

                    if(window.XDomainRequest)
                        contentType = "text/plain";

                    $.ajax({
                        'url': XENON.domain + '/api/chat/send_message',
                        'data': {
                            'user_id': XENON.user_id,
                            'thread_id': XENON.thread_id,
                            'message': $('#xenon-message').val(),
                            'token' : hasCookie('xenon_chat_box')?getCookie('xenon_chat_box'):0
                        },
                        type:"POST",
                        dataType:"json",
                        contentType:contentType,
                        'success': function (data) {
                            $('#xenon-message').val("");
                        }
                    });


                }

                return false;

            },

            start: function () {

                var data = {
                    name: $('#xenon-form-name').val(),
                    email: $('#xenon-form-email').val(),
                    department: $("#xenon-form-department option:selected").val(),
                    message: $("#xenon-form-message").val(),
                    company_id: XENON.company_id,
                    domain: location.protocol + '//' + location.hostname,
                    page: document.URL,
                    ip: XENON.location_info.ip,
                    country: XENON.location_info.country,
                    provider: XENON.location_info.org,
                    token : hasCookie('xenon_chat_box')?getCookie('xenon_chat_box'):0
                };

                var contentType ="application/x-www-form-urlencoded; charset=utf-8";

                if(window.XDomainRequest)
                    contentType = "text/plain";

                $.ajax({
                    url:XENON.domain + "/api/chat/start",
                    data:data,
                    type:"POST",
                    dataType:"json",
                    contentType:contentType,
                    success:function(data)
                    {
                        if (data.blocked) {
                            //errors = your ip have been blocked by admin contact support
                            $('#xenon-errors').html(data.errors);
                            $('#xenon-errors').show();
                            return false;

                        }

                        if (data.result == 0) {
                            $('#xenon-errors').html(data.errors);
                            $('#xenon-errors').show();
                        } else {
                            if (data.is_online == 1) {
                                XENON.thread_id = data.thread_id;
                                XENON.user_id = data.user_id;
                                XENON.token = data.token;

                                console.log('creating cookieee');
                                console.log(data.token);
                                createCookie('xenon_chat_box','111',1);
                                console.log(getCookie('xenon_chat_box'));

                                $('#xenon-chat-view .chat').html("");
                                XENON.check_new_messages();
                            } else {
                                $('#xenon-errors').html("");
                                $('#xenon-errors').hide();
                                $('#xenon-success').html(data.success_msg);
                                $('#xenon-success').show();
                            }
                        }

                    }
                });

                return false;

            },

            end: function () {
                $.ajax({
                    'type': 'GET',
                    'dataType': 'jsonp',
                    'url': XENON.domain + '/api/chat/end',
                    'data': {
                        'thread_id': XENON.thread_id,
                        'token' : hasCookie('xenon_chat_box')?getCookie('xenon_chat_box'):0
                    },
                    'success': function (data) {
                    }
                });
            },

            check_new_messages: function () {

                $.ajax({
                    'type': 'GET',
                    'url': XENON.domain + '/api/chat/check_new_messages',
                    'async': false,
                    'data': {
                        'user_id': XENON.user_id,
                        'thread_id': XENON.thread_id,
                        'company_id': XENON.company_id,
                        'last_message_id': XENON.last_message_id,
                        'page': document.URL,
                        'token' : hasCookie('xenon_chat_box')?getCookie('xenon_chat_box'):0
                    },
                    'success': function (response) {

                        //var response = JSON.parse(data);

                        if (response.is_online == 1) {
                            XENON.change_status(1);
                        } else {
                            XENON.change_status(0);
                        }

                        if (response.in_conversation && !response.conversation_closed) {

                            XENON.thread_id = response.thread_id;
                            XENON.user_id = response.user_id;
                            XENON.last_message_id = response.messages.last_message_id;

                            $('#xenon-chat-view .chat').append(response.messages.messages_str);

                            if (response.messages.messages_str.length > 0) {
                                var audio = new Audio('/assets/message.mp3');
                                audio.play();
                            }

                            $('#xenon-form-view').hide();
                            $('#xenon-chat-view').show();
                            $('#xenon-chat-footer').show();
                            $('#xenon-footer-view').show();

                            XENON.to_bottom();

                        } else {

                            if (response.conversation_closed) {
                                XENON.thread_id = 0;
                                XENON.user_id = 0;
                                XENON.last_message_id = 0;
                                $('#xenon-errors').html("");
                                $('#xenon-errors').hide();
                                $('#xenon-success').html(response.success_msg);
                                $('#xenon-success').show();
                            }

                            $('#xenon-form-view').show();
                            $('#xenon-chat-view').hide();
                            $('#xenon-chat-footer').hide();
                            $('#xenon-footer-view').hide();

                            var $select = $('#xenon-form-department');

                            var selected = $select.find('option:selected').val();

                            $select.find('option').remove();

                            $.each(response.departments, function (key, value) {
                                $select.append('<option value=' + value.id + '>' + value.name + '</option>');
                            });

                            $('#xenon-form-department option[value="' + selected + '"]').prop('selected', true);

                        }

                    },
                    'complete': function () {
                        setTimeout(XENON.check_new_messages, XENON.interval);
                    }
                });

            },

            to_bottom: function () {
                var objDiv = document.getElementById("xenon-chat-view");
                objDiv.scrollTop = objDiv.scrollHeight;
            },

            change_status: function (is_online) {
                if (is_online == 1) {
                    $("#xenon-widget-title").html("Contact us - Online");
                } else {
                    $("#xenon-widget-title").html("Contact us - Offline");
                }
            }
        };

        XENON.init();

        $('#xenon-message').keypress(function (e) {

            if (e.which == 13) {
                XENON.send_message();
            }

        }).focus(function () {

            if ($('#xenon-message').val() == "") {
                $('#xenon-message').val("");
            }

        }).blur(function () {

            if ($('#xenon-message').val() == "") {
                $('#xenon-message').val("");
            }

        });

        $(document).on('click', '#xenon-message-send', function () {
            XENON.send_message();
        });

        $(document).on('click', '#xenon-end', function () {
            XENON.end();
        });

        $(document).on('click', '#xenon-start', function () {
            XENON.start();
        });

    };

}(jQuery));
