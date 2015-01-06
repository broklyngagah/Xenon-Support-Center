if ("undefined" == typeof jQuery)throw new Error("Xenon Livechat's JavaScript requires jQuery");

(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        // CommonJS
        factory(require('jquery'));
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function ($) {

    var pluses = /\+/g;

    function encode(s) {
        return config.raw ? s : encodeURIComponent(s);
    }

    function decode(s) {
        return config.raw ? s : decodeURIComponent(s);
    }

    function stringifyCookieValue(value) {
        return encode(config.json ? JSON.stringify(value) : String(value));
    }

    function parseCookieValue(s) {
        if (s.indexOf('"') === 0) {
            // This is a quoted cookie as according to RFC2068, unescape...
            s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
        }

        try {
            // Replace server-side written pluses with spaces.
            // If we can't decode the cookie, ignore it, it's unusable.
            // If we can't parse the cookie, ignore it, it's unusable.
            s = decodeURIComponent(s.replace(pluses, ' '));
            return config.json ? JSON.parse(s) : s;
        } catch(e) {}
    }

    function read(s, converter) {
        var value = config.raw ? s : parseCookieValue(s);
        return $.isFunction(converter) ? converter(value) : value;
    }

    var config = $.cookie = function (key, value, options) {

        // Write

        if (arguments.length > 1 && !$.isFunction(value)) {
            options = $.extend({}, config.defaults, options);

            if (typeof options.expires === 'number') {
                var days = options.expires, t = options.expires = new Date();
                t.setTime(+t + days * 864e+5);
            }

            return (document.cookie = [
                encode(key), '=', stringifyCookieValue(value),
                options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
                options.path    ? '; path=' + options.path : '',
                options.domain  ? '; domain=' + options.domain : '',
                options.secure  ? '; secure' : ''
            ].join(''));
        }

        // Read

        var result = key ? undefined : {};

        // To prevent the for loop in the first place assign an empty array
        // in case there are no cookies at all. Also prevents odd result when
        // calling $.cookie().
        var cookies = document.cookie ? document.cookie.split('; ') : [];

        for (var i = 0, l = cookies.length; i < l; i++) {
            var parts = cookies[i].split('=');
            var name = decode(parts.shift());
            var cookie = parts.join('=');

            if (key && key === name) {
                // If second argument (value) is a function it's a converter...
                result = read(cookie, value);
                break;
            }

            // Prevent storing a cookie that we couldn't decode.
            if (!key && (cookie = read(cookie)) !== undefined) {
                result[name] = cookie;
            }
        }

        return result;
    };

    config.defaults = {};

    $.removeCookie = function (key, options) {
        if ($.cookie(key) === undefined) {
            return false;
        }

        // Must not alter options, thus extending a fresh object...
        $.cookie(key, '', $.extend({}, options, { expires: -1 }));
        return !$.cookie(key);
    };

}));

$('#xenon-widget').click(function(){
    ('#xenon-widget').toggle();
});

(function ($) {

    $.fn.XENON_Initialize = function (options) {

        var XENON = {
            domain: options.hasOwnProperty("domain") ? options["domain"] : "http://support.dev",
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
                            'token' : XENON.token
                        },
                        'success': function (response) {
                            //get all data and append to xenon_chat_widget
                            if (response.blocked) {
                                //errors = your ip have been blocked by admin contact support
                                $('#xenon-errors').html(response.errors);
                                $('#xenon-errors').show();
                                $('#xenon-success').html("");
                                $('#xenon-success').show();
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
                            'token' : XENON.token
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
                    token : XENON.token
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

                                $.cookie('xenon_chat_box',data.token);

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
                        'token' : XENON.token
                    },
                    'success': function (data) {
                        $.removeCookie('xenon_chat_box');
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
                        'token' : XENON.token
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
                            XENON.token = response.token;

                            $('#xenon-chat-view .chat').append(response.messages.messages_str);

                            if (response.messages.messages_str.length > 0) {
                                var audio = new Audio(XENON.domain + '/assets/message.mp3');
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
                                XENON.token = 0;
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

            XENON.token = $.cookie('xenon_chat_box');
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
