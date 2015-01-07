@extends('layouts.master')

@section('content')

	<!-- Page header -->
	<div class="page-header">
		<div class="page-title">
			<h3>Company Customers <small>Control panel.</small></h3>
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
				Company Customers
			</li>
		</ul>
	</div>
	<!-- /breadcrumbs line -->

	@include('layouts.notify')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h6 class="panel-title"><i class="icon-user4"></i> Company Customers</h6>
			<div class="table-controls pull-right">
				<a href="/customers/create" class="btn btn-default btn-icon btn-xs tip" title="" data-original-title="Add Customer"><i class="icon-plus"></i></a>
			</div>
		</div>
		<div class="datatable-tools">
			<table id="customer_list" class="table">
				<thead>
				<tr>
					<th>Avatar</th>
					<th>Name</th>
					<th>Email</th>
					<th>Company</th>
					<th>Tickets Raised</th>
					<th>Tickets Resolved</th>
					<th>Pending Tickets</th>
					<th>Edit</th>
					<th>Delete</th>
				</tr>
				</thead>
				<tbody>
				@foreach($customers as $customer)
					<tr>
						<td><img class="img-circle" style="width:80px;" src="{{$customer->avatar}}"/></td>
						<td>{{{$customer->name}}}</td>
						<td>{{{$customer->email}}}</td>
						<td>{{{$customer->company->name}}}</td>
						<td><a href="/tickets/customers/{{$customer->id}}/all"><span class="label label-info">{{$customer->all_ticket_count}}</span></a></td>
						<td><a href="/tickets/customers/{{$customer->id}}/{{Tickets::TICKET_RESOLVED}}"><span class="label label-success">{{$customer->resolved_ticket_count}}</span></a></td>
						<td><a href="/tickets/customers/{{$customer->id}}/{{Tickets::TICKET_PENDING}}"><span class="label label-warning">{{$customer->pending_ticket_count}}</span></a></td>
						<td><a href="/customers/update/{{$customer->id}}" class="btn btn-success btn-sm edit_operator"> Edit </a></td>
						<td><a href="/customers/delete/{{$customer->id}}" class="btn btn-danger btn-sm"> Delete </a></td>
					</tr>
				@endforeach
				</tbody>

			</table>
		</div>
	</div>

@stop