@extends('layouts.master')

@section('content')

<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>Canned Messages <small>Control panel.</small></h3>
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
			Canned Messages
		</li>
	</ul>
</div>
<!-- /breadcrumbs line -->

@include('layouts.notify')
<div class="panel panel-default">
	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-user4"></i> All Canned Messages</h6>
		<div class="table-controls pull-right">
			<a href="/canned_messages/create" class="btn btn-default btn-icon btn-xs tip" title="" data-original-title="Add Canned Message"><i class="icon-plus"></i></a>
		</div>
	</div>
	<div class="datatable-tools">
		<table id="messages_list" class="table">
			<thead>
				<tr>
					<th>ID</th>
					<th>Message</th>
					<th>Company</th>
					<th>Department</th>
					<th>Operator Name</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
				@foreach($messages as $message)
				<tr>
					<td>{{$message->id}}</td>
					<td>{{$message->message}}</td>
					<td>{{$message->company->name}}</td>
					<td>{{$message->department->name}}</td>
					<td>{{$message->operator->name}}</td>
					<td><a href="/canned_messages/delete/{{$message->id}}" class="btn btn-danger btn-sm"><i class="icon-remove2"></i> Delete </a></td>
				</tr>
				@endforeach
			</tbody>

		</table>
	</div>
</div>

@stop