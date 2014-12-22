@extends('layouts.master')

@section('content')

<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>Translations <small>Control panel.</small></h3>
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
			Translations
		</li>
	</ul>
</div>
<!-- /breadcrumbs line -->

@include('layouts.notify')
<div class="panel panel-default">
	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-user4"></i> All Translations</h6>
		<div class="table-controls pull-right">
			<a href="/translations/create" class="btn btn-default btn-icon btn-xs tip" title="" data-original-title="Add Language"><i class="icon-plus"></i></a>
			<a href="/translations/export" class="btn btn-default btn-icon btn-xs tip" title="" data-original-title="Export"><i class="icon-cogs"></i></a>
		</div>
	</div>
	<div class="datatable">
		<table class="table">
			<thead>
				<tr>
					<th>Name</th>
					<th>Code</th>
					<th>Active</th>
					<th>View</th>
				</tr>
			</thead>
			<tbody>
				@foreach($translations as $translation)
				<tr>
					<td><h3>{{$translation->language_name}}</h3></td>
					<td><h3>{{$translation->language_code}}</h3></td>
					<td>{{$translation->active==1?"<label class='label label-success'>Active</label>":"<label class='label label-primary'>Not Active</label>"}}</td>
					<td><a href="/translations/view/{{$translation->id}}" class="btn btn-success btn-sm"><i class="icon-eye"></i> View </a></td>
				</tr>
				@endforeach
			</tbody>

		</table>
	</div>
</div>

@stop