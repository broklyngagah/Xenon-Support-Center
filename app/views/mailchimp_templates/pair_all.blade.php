@extends('layouts.master')

@section('content')

<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>Paired Templates <small>Control panel.</small></h3>
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
			Paired Templates
		</li>
	</ul>
</div>
<!-- /breadcrumbs line -->

@include('layouts.notify')
<div class="panel panel-default">
	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-user4"></i> Paired Templates</h6>
		<div class="table-controls pull-right">
			<a href="/templates/pair/create" class="btn btn-default btn-icon btn-xs tip" title="" data-original-title="Pair View to Template"><i class="icon-plus"></i></a>
		</div>
	</div>
	<div class="datatable-tools">
		<table id="operator_list" class="table">
			<thead>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Email View</th>
                    <th>Template ID</th>
                    <th>Send Mail</th>
                    <th>Preview</th>
                    <th>Delete</th>
				</tr>
			</thead>
			<tbody>
				@foreach($paired_templates as $paired_template)
				<tr>
					<td>{{$paired_template->id}}</td>
					<td>{{$paired_template->name}}</td>
					<td>{{$paired_template->view}}</td>
					<td>{{$paired_template->template_id}}</td>
					<td><a href="/templates/pair/preview/{{$paired_template->id}}?send_mail=true" class="btn btn-info">Send Mail</a></td>
					<td><a href="/templates/pair/preview/{{$paired_template->id}}?send_mail=false" class="btn btn-info">Preview</a></td>
					<td><a href="/templates/pair/delete/{{$paired_template->id}}" class="btn btn-danger">Delete</a></td>
				</tr>
				@endforeach
			</tbody>

		</table>
	</div>
</div>

@stop