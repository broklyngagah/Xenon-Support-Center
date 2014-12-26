@extends('layouts.master')

@section('content')

<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>Accounts <small>Control panel.</small></h3>
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
			Accounts
		</li>
	</ul>
</div>
<!-- /breadcrumbs line -->

@include('layouts.notify')
<div class="panel panel-default">
	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-user4"></i> All Accounts</h6>
		<div class="table-controls pull-right">
			<a href="/accounts/create" class="btn btn-default btn-icon btn-xs tip" title="" data-original-title="Add Account"><i class="icon-plus"></i></a>
		</div>
	</div>
	<div class="datatable-tools">
		<table id="accounts_list" class="table">
			<thead>
				<tr>
					<th>Avatar</th>
					<th>Name</th>
					<th>Email</th>
					<th>Mobile No</th>
					<th>Country</th>
					<th>Show Avatar</th>
					<th>Activate</th>
					<th>Edit</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
				@foreach($users as $user)
				<tr>
					<td><img class="img-circle" style="width:80px;" src="{{$user->avatar}}"/></td>
					<td>{{$user->name}}</td>
					<td>{{$user->email}}</td>
					<td>{{$user->mobile_no}}</td>
					<td>{{$user->country}}</td>
					<td>{{$user->show_avatar==1?"<label class='label label-info'>Yes</label>":"<label class='label label-warning'>No</label>"}}</td>
					<td>{{$user->activated==1?"<a class='btn btn-primary' disabled><i class='icon-checkmark4'></i>Activated</button>":"<a href='/accounts/activate/".$user->id."' class='btn btn-primary'><i class='icon-checkmark3'></i>Activate</button>"}}</td>
					<td><a href="/accounts/update/{{$user->id}}" class="btn btn-success btn-sm edit_operator"><i class="icon-pencil4"></i> Edit </a></td>
					<td><a href="/accounts/delete/{{$user->id}}" class="btn btn-danger btn-sm"><i class="icon-remove2"></i> Delete </a></td>
				</tr>
				@endforeach
			</tbody>

		</table>
	</div>
</div>

@stop