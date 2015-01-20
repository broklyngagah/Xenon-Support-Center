<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Xenon Support Center</title>
<link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="/assets/css/xenon-theme.min.css" rel="stylesheet" type="text/css">
<link href="/assets/css/styles.min.css" rel="stylesheet" type="text/css">
<link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/uniform.min.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
</head>
<body class="full-width page-condensed">
<!-- Navbar -->
<div class="navbar navbar-inverse" role="navigation">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-right"><span class="sr-only">Toggle navbar</span><i class="icon-grid3"></i></button>
    <a class="navbar-brand" href="#"><img style="width:170px;" src="/assets/images/logo.png" alt="Xenon"></a></div>

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

      <div class="row">
        <div class="col-xs-12">
            <a href="/forgot-password">Forgot Password ?</a>
        </div>
      </div>

    </div>
  </form>
</div>
<!-- /login wrapper -->
<!-- Footer -->
<div class="footer clearfix">
  <div class="pull-left">&copy; 2015. Xenon Support Center </div>
  <div class="pull-right icons-group"> <a href="#"><i class="icon-screen2"></i></a> <a href="#"><i class="icon-balance"></i></a> <a href="#"><i class="icon-cog3"></i></a> </div>
</div>
<!-- /footer -->
<script>
    var userip;
</script>
@if(!Session::has('client_ip'))
  <script type="text/javascript" src="http://l2.io/ip.js?var=userip"></script>
@endif
<script>
$(document).ready(function($) {

  @if(!Session::has('client_ip'))
  $.ajax({
    'type': 'GET',
    'url': '/api/log_ip',
    'data': {
      'ip_address': userip
    },
    'success': function (data) {

    }
  });
  @endif

});
</script>
</body>
</html>