<!DOCTYPE html>
<html lang="en">
<head>

    <title>K15 Support Center</title>

    {{HTML::script("http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js")}}
    {{HTML::script("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js")}}
    {{HTML::style("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css")}}
    {{HTML::style("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css")}}

    {{HTML::style("/assets/k15_chat/style.css")}}
    {{HTML::script("/assets/k15_chat/script.js")}}

    <script type="text/javascript">

        $(document).ready(function () {
            $("#k15-chat-widget").K15_Initialize({company: 1, interval: 5000});
        });

    </script>

<body>

<div class="container">

    <div id="k15-chat-widget"></div>

</div>
</body>
</html>