@extends('layouts.master')

@section('content')

<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>Blocking <small>Control panel.</small></h3>
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
			Blocking
		</li>
	</ul>
</div>
<!-- /breadcrumbs line -->

@include('layouts.notify')
<div class="panel panel-default">
	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-user4"></i> All Blocked IP'S</h6>
		<div class="table-controls pull-right">
			<a href="/blocking/create" class="btn btn-default btn-icon btn-xs tip" title="" data-original-title="Add IP"><i class="icon-plus"></i></a>
		</div>
	</div>
	<div class="datatable-tools">
		<table id="messages_list" class="table">
			<thead>
				<tr>
					<th>ID</th>
					<th>Ip Address</th>
					<th>Block Chat</th>
					<th>Block Tickets</th>
					<th>Block Login</th>
					<th>Block Web Access</th>
					<th>Blocked On</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
				@foreach($blocking as $blocked)
				<tr>
					<td>{{$blocked->id}}</td>
					<td>{{$blocked->ip_address}}</td>
					<td>{{$blocked->should_block_chat==1?"<label class='label label-success'>Yes</label>":"<label class='label label-warning'>No</label>"}}</td>
					<td>{{$blocked->should_block_tickets==1?"<label class='label label-success'>Yes</label>":"<label class='label label-warning'>No</label>"}}</td>
					<td>{{$blocked->should_block_login==1?"<label class='label label-success'>Yes</label>":"<label class='label label-warning'>No</label>"}}</td>
					<td>{{$blocked->should_block_web_access==1?"<label class='label label-success'>Yes</label>":"<label class='label label-warning'>No</label>"}}</td>
					<td>{{\KodeInfo\Utilities\Utils::prettyDate($blocked->created_at)}}</td>
					<td><a href="/blocking/delete/{{$blocked->id}}" class="btn btn-danger btn-sm"><i class="icon-remove2"></i> Delete </a></td>
				</tr>
				@endforeach
			</tbody>

		</table>
	</div>
</div>

@stop