@extends('layouts.master')

@section('content')

<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>Department Admin <small>Control panel.</small></h3>
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
			All Department Admins
		</li>
	</ul>
</div>
<!-- /breadcrumbs line -->

@include('layouts.notify')
<div class="panel panel-default">

	<div style="margin:5px;" class="alert alert-info">
		If you delete department admin all department tickets , conversations will be assigned to company owner .
	</div>

	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-user4"></i> All Department Admins</h6>
		<div class="table-controls pull-right">
			<a href="/departments/admins/create" class="btn btn-default btn-icon btn-xs tip" title="" data-original-title="Add Department Admin"><i class="icon-plus"></i></a>
		</div>
	</div>
	<div class="datatable-tools">
		<table id="admins_list" class="table">
			<thead>
				<tr>
					<th>Avatar</th>
					<th>Name</th>
					<th>Email</th>
					<th>Mobile No</th>
					<th>Country</th>
					<th>Company</th>
					<th>Department</th>
					<th>Show Avatar</th>
					<th>Activate</th>
					<th>Edit</th>
					<th>Remove from Department</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
			@foreach($admins as $admin)
				<tr>
					<td><img class="img-circle" style="width:80px;" src="{{$admin->avatar}}"/></td>
					<td>{{$admin->name}}</td>
					<td>{{$admin->email}}</td>
					<td>{{$admin->mobile_no}}</td>
					<td>{{$admin->country}}</td>
					<td>{{isset($admin->company)?$admin->company->name:"NONE"}}</td>
					<td>{{isset($admin->department)?$admin->department->name:"NONE"}}</td>
					<td>{{$admin->show_avatar==1?"<label class='label label-info'>Yes</label>":"<label class='label label-warning'>No</label>"}}</td>
					<td>{{$admin->activated==1?"<a class='btn btn-primary' disabled><i class='icon-checkmark4'></i> Activated </button>":"<a href='/departments/admins/activate/".$admin->id."' class='btn btn-primary'><i class='icon-checkmark3'></i>Activate</button>"}}</td>
					<td><a href="/departments/admins/update/{{$admin->id}}" class="btn btn-success btn-sm edit_operator"><i class="icon-pencil4"></i></a></td>
					<td><a href="/departments/admins/remove/{{$admin->id}}" class="btn btn-warning btn-sm"><i class="icon-remove2"></i> Remove </a></td>
					<td><a href="/departments/admins/delete/{{$admin->id}}" class="btn btn-danger btn-sm"><i class="icon-remove2"></i> </a></td>
				</tr>
			@endforeach
			</tbody>

		</table>
	</div>
</div>

@stop