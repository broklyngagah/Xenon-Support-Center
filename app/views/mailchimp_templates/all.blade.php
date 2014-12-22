@extends('layouts.master')

@section('content')

<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>Email Templates <small>Control panel.</small></h3>
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
			Email Templates
		</li>
	</ul>
</div>
<!-- /breadcrumbs line -->

@include('layouts.notify')
<div class="panel panel-default">
	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-user4"></i> Email Templates</h6>
	</div>
	<div class="datatable">
		<table id="operator_list" class="table">
			<thead>
				<tr>
					<th>Template ID</th>
                    <th>Name</th>
                    <th>Layout</th>
                    <th>Preview Image</th>
                    <th>Created on</th>
                    <th>HTML</th>
				</tr>
			</thead>
			<tbody>
				@foreach($templates as $template)
				<tr>
					<td>{{$template['id']}}</td>
					<td>{{$template['name']}}</td>
					<td>{{$template['layout']}}</td>
					<td><a target="_blank" href='{{$template['preview_image']}}'><img style="width:80px;" src='{{$template['preview_image']}}'/></a></td>
					<td>{{\KodeInfo\Utilities\Utils::prettyDate($template['date_created'])}}</td>
					<td><a href="/templates/view/{{$template['id']}}" class="btn btn-success btn-sm"><i class="icon-eye"></i> View HTML</a></td>
				</tr>
				@endforeach
			</tbody>

		</table>
	</div>
</div>

@stop