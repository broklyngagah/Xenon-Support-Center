@extends('layouts.master')

@section('content')

<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>Activities <small>Control panel.</small></h3>
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
			Activities
		</li>
	</ul>
</div>
<!-- /breadcrumbs line -->

@include('layouts.notify')
<div class="panel panel-default">
	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-user4"></i> All Activities</h6>
	</div>
	<div class="datatable-tools">
		<table class="table">
			<thead>
				<tr>
					<th>ID</th>
					<th>Message</th>
					<th>Done on</th>
				</tr>
			</thead>
			<tbody>
				@foreach($activities as $activity)
				<tr>
					<td>{{$activity->id}}</td>
					<td>{{{$activity->message}}}</td>
					<td>{{\KodeInfo\Utilities\Utils::prettyDate($activity->created_at)}}</td>
				</tr>
				@endforeach
			</tbody>

		</table>
	</div>
</div>

@stop