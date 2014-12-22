@extends('layouts.master')

@section('content')
<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>Pair Template <small>Control panel.</small></h3>
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
			Pair Template
		</li>
	</ul>
</div>
<!-- /breadcrumbs line -->

@include('layouts.notify')

{{Form::open(['url'=>'/templates/pair/create','method'=>'post','files'=>true])}}

<div class="panel panel-default">
	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-user-plus2"></i> Pair Template</h6>
		<div class="table-controls pull-right">
			<input type="submit" value="Save" class="btn btn-info">
		</div>
	</div>
	<div class="panel-body" style="margin:10px;">
		<div class="form-group">
			<div class="row">
				<div class="col-md-6">
					<label>Template Name:</label>
					<select class="form-control" name="view">
						@foreach($email_views as $key=>$value)
							<option value="{{$key}}">{{$value}}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-6">
					<label>ID - Template Name:</label>
					<select class="form-control" name="template_id">
						@foreach($templates as $template)
							<option value="{{$template['id']}}">{{$template['id']." - ".$template['name']}}</option>
						@endforeach
					</select>
				</div>
			</div>
		</div>

		<div class="form-actions text-right">
			<input type="submit" value="Save" class="btn btn-info">
		</div>
	</div>
</div>
{{Form::close()}}

@stop