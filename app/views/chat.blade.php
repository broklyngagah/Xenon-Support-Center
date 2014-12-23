<!DOCTYPE html>
<html lang="en">
<head>

    <title>XENON Support Center</title>

    {{HTML::script("http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js")}}
    {{HTML::script("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js")}}
    {{HTML::style("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css")}}
    {{HTML::style("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css")}}

    {{HTML::style("/assets/xenon_chat/style.css")}}
    {{HTML::script("/assets/xenon_chat/script.js")}}

    <script type="text/javascript">

        $(document).ready(function () {
            $("#xenon-chat-widget").XENON_Initialize({company: 1, interval: 5000});
        });

    </script>

<body>

<div class="container">

    <div id="xenon-chat-widget"></div>

</div>
</body>
</html>