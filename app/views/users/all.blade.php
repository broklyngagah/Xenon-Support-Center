@extends('layouts.master')

@section('content')

<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>Users <small>Control panel.</small></h3>
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
			Users
		</li>
	</ul>
</div>
<!-- /breadcrumbs line -->

@include('layouts.notify')
<div class="panel panel-default">
	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-user4"></i> All Users</h6>
	</div>
	<div class="datatable-tools">
		<table class="table">
			<thead>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Email</th>
					<th>Type</th>
				</tr>
			</thead>
			<tbody>
				@foreach($users as $user)
				<tr>
					<td>{{$user->id}}</td>
					<td>{{{$user->name}}}</td>
					<td>{{{$user->email}}}</td>
					<td><label class="label label-primary">{{$user->type}}</label></td>
				</tr>
				@endforeach
			</tbody>

		</table>
	</div>
</div>

@stop