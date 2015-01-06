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

  <form action="/setup" method="POST" role="form">
    <div class="popup-header"><a href="#" class="pull-left"><i class="icon-user-plus"></i></a><span class="text-semibold">Setup</span>
    </div>
    <div class="well">
      <div class="form-group has-feedback">
        <label>Database Host</label>
        <input type="text" class="form-control" name="db_host" placeholder="Database Host" value="{{Input::old("db_host","localhost")}}">
      </div>
      <div class="form-group has-feedback">
        <label>Database Name</label>
        <input type="text" class="form-control" name="db_name" placeholder="Database Name" value="{{Input::old("db_name")}}">
      </div>
      <div class="form-group has-feedback">
        <label>Database User</label>
        <input type="text" class="form-control" name="db_user" placeholder="Database User" value="{{Input::old("db_user")}}">
      </div>
      <div class="form-group has-feedback">
        <label>Database Password</label>
        <input type="text" class="form-control" name="db_password" placeholder="Database Password" value="{{Input::old("db_password")}}">
      </div>
      <div class="row form-actions">
        <div class="col-xs-6">
        </div>
        <div class="col-xs-6">
          <button type="submit" class="btn btn-warning pull-right"><i class="icon-tools"></i> Setup</button>
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
</body>
</html>