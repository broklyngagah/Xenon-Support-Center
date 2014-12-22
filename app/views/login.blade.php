<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Support Center</title>
<link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="/assets/css/londinium-theme.min.css" rel="stylesheet" type="text/css">
<link href="/assets/css/styles.min.css" rel="stylesheet" type="text/css">
<link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/charts/sparkline.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/uniform.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/select2.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/inputmask.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/autosize.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/inputlimit.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/listbox.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/multiselect.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/validate.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/tags.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/switch.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/uploader/plupload.full.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/uploader/plupload.queue.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/wysihtml5/wysihtml5.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/wysihtml5/toolbar.js"></script>
<script type="text/javascript" src="/assets/js/plugins/interface/daterangepicker.js"></script>
<script type="text/javascript" src="/assets/js/plugins/interface/fancybox.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/interface/moment.js"></script>
<script type="text/javascript" src="/assets/js/plugins/interface/jgrowl.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/interface/datatables.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/interface/colorpicker.js"></script>
<script type="text/javascript" src="/assets/js/plugins/interface/fullcalendar.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/interface/timepicker.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/interface/collapsible.min.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/js/application.js"></script>
</head>
<body class="full-width page-condensed">
<!-- Navbar -->
<div class="navbar navbar-inverse" role="navigation">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-right"><span class="sr-only">Toggle navbar</span><i class="icon-grid3"></i></button>
    <a class="navbar-brand" href="#"><img src="/assets/images/logo.png" alt="Londinium"></a></div>

</div>
<!-- /navbar -->
<!-- Login wrapper -->
<div class="login-wrapper">

    @include('layouts.notify')

  <form action="/login" method="POST" role="form">
    <div class="popup-header"><a href="#" class="pull-left"><i class="icon-user-plus"></i></a><span class="text-semibold">User Login</span>
      <div class="btn-group pull-right"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cogs"></i></a>
        <ul class="dropdown-menu icons-right dropdown-menu-right">
          <li><a href="/forgot-password"><i class="icon-info"></i> Forgot password?</a></li>
          <li><a href="/register"><i class="icon-support"></i> New User </a></li>
        </ul>
      </div>
    </div>
    <div class="well">
      <div class="form-group has-feedback">
        <label>Email</label>
        <input type="text" class="form-control" name="email" placeholder="Email">
        <i class="icon-users form-control-feedback"></i></div>
      <div class="form-group has-feedback">
        <label>Password</label>
        <input type="password" class="form-control" name="password" placeholder="Password">
        <i class="icon-lock form-control-feedback"></i></div>
      <div class="row form-actions">
        <div class="col-xs-6">
          <div class="checkbox checkbox-success">
            <label>
              <input type="checkbox" name="remember_me" class="styled">
              Remember me</label>
          </div>
        </div>
        <div class="col-xs-6">
          <button type="submit" class="btn btn-warning pull-right"><i class="icon-menu2"></i> Sign in</button>
        </div>
      </div>
    </div>
  </form>
</div>
<!-- /login wrapper -->
<!-- Footer -->
<div class="footer clearfix">
  <div class="pull-left">&copy; 2013. Londinium Admin Template by <a href="http://themeforest.net/user/Kopyov">Eugene Kopyov</a></div>
  <div class="pull-right icons-group"> <a href="#"><i class="icon-screen2"></i></a> <a href="#"><i class="icon-balance"></i></a> <a href="#"><i class="icon-cog3"></i></a> </div>
</div>
<!-- /footer -->
<script>
$(document).ready(function($) {

    @if(!Session::has('client_ip'))
    $.get("http://ipinfo.io", function (response) {

      $.ajax({
        'type': 'GET',
        'url': '/api/log_ip',
        'data': {
          'ip_address': response.ip
        },
        'success': function (data) {

        }
      });

    }, "jsonp");
    @endif

});
</script>
</body>
</html>