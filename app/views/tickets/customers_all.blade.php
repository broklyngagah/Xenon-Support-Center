@extends('layouts.master')

@section('content')

<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>Tickets <small>Control panel.</small></h3>
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
			Tickets
		</li>
	</ul>
</div>
<!-- /breadcrumbs line -->

@include('layouts.notify')
<div class="panel panel-default">
	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-user4"></i> All Tickets</h6>
		<div class="table-controls pull-right">
			<a href="/tickets/customer/create" class="btn btn-default btn-icon btn-xs tip" title="" data-original-title="Create Ticket"><i class="icon-plus"></i></a>
		</div>
	</div>
	<div class="datatable-tools">
		<table id="tickets_all" class="table">
			<thead>
			<tr>
				<th>ID</th>
				<th>Company</th>
				<th>Department</th>
				<th>Name</th>
				<th>Email</th>
				<th>Subject</th>
				<th>Priority</th>
				<th>Status</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>
			</thead>
			<tbody>
			@foreach($tickets as $ticket)
				<tr>
					<td>{{$ticket->id}}</td>
					<td>{{isset($ticket->company)?$ticket->company->name:"NONE"}}</td>
					<td>{{isset($ticket->department)?$ticket->department->name:"NONE"}}</td>
					<td>{{isset($ticket->customer)?$ticket->customer->name:"NONE"}}</td>
					<td>{{isset($ticket->customer)?$ticket->customer->email:"NONE"}}</td>
					<td>{{$ticket->subject}}</td>

					@if($ticket->priority==Tickets::PRIORITY_LOW)
						<td><label class="label label-primary">Low</label></td>
					@endif
					@if($ticket->priority==Tickets::PRIORITY_MEDIUM)
						<td><label class="label label-primary">Medium</label></td>
					@endif
					@if($ticket->priority==Tickets::PRIORITY_HIGH)
						<td><label class="label label-warning">High</label></td>
					@endif
					@if($ticket->priority==Tickets::PRIORITY_URGENT)
						<td><label class="label label-danger">Urgent</label></td>
					@endif

					@if($ticket->status==Tickets::TICKET_NEW)
						<td><label class="label label-warning">New</label></td>
					@endif
					@if($ticket->status==Tickets::TICKET_PENDING)
						<td><label class="label label-primary">Pending</label></td>
					@endif
					@if($ticket->status==Tickets::TICKET_RESOLVED)
						<td><label class="label label-success">Resolved</label></td>
					@endif

					<td><a href="/tickets/customer/read/{{$ticket->thread_id}}" class="btn btn-success btn-sm"> <i class="icon-checkmark4"></i> Reply </a></td>
					<td><a href="/tickets/customer/delete/{{$ticket->thread_id}}" class="btn btn-danger btn-sm"> <i class="icon-remove3"></i> Delete </a></td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div>

@stop