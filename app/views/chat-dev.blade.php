<!DOCTYPE html>
<html lang="en">
<head>

    <title>XENON Support Center</title>

    {{HTML::script("http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js")}}
    {{HTML::script("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js")}}
    {{HTML::style("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css")}}
    {{HTML::style("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css")}}

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <link href="http://support.dev/assets/xenon_chat/style.css" rel="stylesheet" type="text/css">
    <script src="http://support.dev/assets/xenon_chat/script.js" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#xenon-chat-widget").XENON_Initialize({company: 1, domain: "http://support.dev"});
        });
    </script><div id="xenon-chat-widget"></div>

<body>

<div class="container">

    
</div>
</body>
</html>