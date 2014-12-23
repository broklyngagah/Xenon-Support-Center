@extends('layouts.master')

@section('content')
<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>Change Password <small>Control panel.</small></h3>
	</div>
</div>
<!-- /page header -->
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
	<ul class="breadcrumb">
		<li>
			<a href="/">Home</a>
		</li>
		<li class="active">
			Change Password
		</li>
	</ul>
</div>
<!-- /breadcrumbs line -->

@include('layouts.notify')

{{Form::open(['url'=>'/change_password','method'=>'post','class'=>'form-horizontal form-bordered','role'=>'form'])}}

<!-- Button trigger modal -->

<div class="panel panel-default">
	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-user-plus2"></i> Change Password</h6>
		<div class="table-controls pull-right">
			<input type="submit" value="Save" class="btn btn-info">
		</div>
	</div>
	<div class="panel-body">

		<div class="form-group">
			<label class="col-sm-2 control-label">Current Password</label>
			<div class="col-sm-10">
				<input name="current_password" type="password" class="form-control" >
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label">New Password</label>
			<div class="col-sm-10">
				<input name="password" type="password" class="form-control" >
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label">Confirm New Password</label>
			<div class="col-sm-10">
				<input name="password_confirmation" type="password" class="form-control" >
			</div>
		</div>


		<div class="form-actions text-right">
			<label class="col-sm-2 control-label"></label>
			<input type="submit" value="Save" class="btn btn-info">
		</div>
	</div>
</div>
{{Form::close()}}

@stop

@section('scripts')
{{HTML::style("/assets/plugins/datepicker/css/datepicker3.css")}}
{{HTML::script("/assets/plugins/datepicker/js/bootstrap-datepicker.js")}}
<script type="text/javascript">
	$('#birthday').datepicker({
		format : "dd-mm-yyyy"
	});
</script>
@stop