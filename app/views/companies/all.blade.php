@extends('layouts.master')

@section('content')

<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>Company <small>Control panel.</small></h3>
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
			All Companies
		</li>
	</ul>
</div>
<!-- /breadcrumbs line -->

@include('layouts.notify')
<div class="panel panel-default">
	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-user4"></i> All Companies</h6>
		<div class="table-controls pull-right">
			<a href="/companies/create" class="btn btn-default btn-icon btn-xs tip" title="" data-original-title="Add Company"><i class="icon-plus"></i></a>
		</div>
	</div>
	<div class="datatable-tools">
		<table id="company_list" class="table">
			<thead>
				<tr>
					<th>ID</th>
					<th>Logo</th>
					<th>Name</th>
					<th>Description</th>
					<th>Domain</th>
					<th>Operators</th>
					<th>Contacts</th>
					<th>Edit</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
				@foreach($companies as $company)
				<tr>
					<td>{{$company->id}}</td>
					<td><img class="img-circle" style="width:80px;" src="{{$company->logo}}"/></td>
					<td>{{$company->name}}</td>
					<td>{{$company->description}}</td>
					<td>{{$company->domain}}</td>
					<td><a class="btn btn-primary" href="/companies/operators/{{$company->id}}">View Operators</a></td>
					<td><a class="btn btn-primary" href="/companies/customers/{{$company->id}}">View Customers</a></td>
					<td><a href="/companies/update/{{$company->id}}" class="btn btn-success btn-sm edit_operator"> <i class="icon-pencil"></i> Edit </a></td>
					<td><a href="/companies/delete/{{$company->id}}" class="btn btn-danger btn-sm"> <i class="icon-remove3"></i> Delete </a></td>
				</tr>
				@endforeach
			</tbody>

		</table>
	</div>
</div>

@stop