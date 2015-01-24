@extends('layouts.master')

@section('content')

<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>Operators <small>Control panel.</small></h3>
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
			Operators
		</li>
	</ul>
</div>
<!-- /breadcrumbs line -->

@include('layouts.notify')
<div class="panel panel-default">
	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-user4"></i> All Operators</h6>
		<div class="table-controls pull-right">
			<a href="/operators/create" class="btn btn-default btn-icon btn-xs tip" title="" data-original-title="Add Operator"><i class="icon-plus"></i></a>
			<a href="/operators/export" class="btn btn-default btn-icon btn-xs tip" title="" data-original-title="Export"><i class="icon-cogs"></i></a>
		</div>
	</div>
	<div class="datatable-tools">
		<table id="operator_list" class="table">
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
                    <th>Delete</th>
				</tr>
			</thead>
			<tbody>
				@foreach($operators as $operator)
				<tr>
					<td><img class="img-circle" style="width:80px;" src="{{$operator->avatar}}"/></td>
					<td>{{{$operator->name}}}</td>
					<td>{{{$operator->email}}}</td>
					<td>{{{$operator->mobile_no}}}</td>
					<td>{{{$operator->country}}}</td>
					<td>{{{$operator->company->name}}}</td>
					<td>{{{$operator->department->name}}}</td>
					<td>{{$operator->show_avatar==1?"<label class='label label-info'>Yes</label>":"<label class='label label-warning'>No</label>"}}</td>
					<td>{{$operator->activated==1?"<a class='btn btn-primary' disabled><i class='icon-checkmark4'></i>Activated</button>":"<a href='/operators/activate/".$operator->id."' class='btn btn-primary'><i class='icon-checkmark3'></i>Activate</button>"}}</td>
					<td><a href="/operators/update/{{$operator->id}}" class="btn btn-success btn-sm edit_operator"><i class="icon-pencil4"></i> Edit </a></td>
					<td><a href="/operators/delete/{{$operator->id}}" class="btn btn-danger btn-sm"><i class="icon-remove2"></i> Delete </a></td>
				</tr>
				@endforeach
			</tbody>

		</table>
	</div>
</div>

@stop