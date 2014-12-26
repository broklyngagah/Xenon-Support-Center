@extends('layouts.master')

@section('styles')
{{HTML::style("/assets/plugins/datatables/css/dataTables.bootstrap.css")}}
@stop

@section('content')
<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>Permissions <small>Control panel.</small></h3>
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
			Permissions
		</li>
	</ul>
</div>
<!-- /breadcrumbs line -->

@include('layouts.notify')

<div class="panel panel-default">
	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-key"></i> All Permissions</h6>
		<div class="table-controls pull-right">
			<a href="/permissions/create" class="btn btn-default btn-icon btn-xs tip" title="" data-original-title="Add Permission"><i class="icon-plus"></i></a>
		</div>
	</div>
	<div class="datatable-tools">
		<table id="permissions_list" class="table">
			<thead>
				<tr>
					<th>ID</th>
					<th>Text</th>
					<th>Key</th>
					<th>Edit</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
				@foreach($permissions as $permission)
				<tr>
					<td>{{$permission->id}}</td>
					<td>{{$permission->text}}</td>
					<td>{{$permission->key}}</td>
					<td>
					<button data-id="{{$permission->id}}" data-toggle="modal" data-target="#edit_permission_modal"
					class="btn btn-success btn-sm edit_permission">
						<i class="icon-pencil4"></i> Edit Description
					</button></td>
					<td>
                    	<a href="/permissions/delete/{{$permission->id}}" class="btn btn-warning btn-sm"><i class="icon-remove2"></i> Delete</a>
                    </td>
				</tr>
				@endforeach
			</tbody>
			</tfoot>
		</table>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="edit_permission_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			{{Form::open(['url'=>'/permissions/update','method'=>'post'])}}
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span
					aria-hidden="true">&times;</span><span
					class="sr-only">Close</span>
				</button>
				<h4 class="modal-title">Edit Permission</h4>
			</div>
			<div class="modal-body with-padding">

				<div class="form-group">
					<div class="row">
						<div class="col-sm-8">
							<input id="permission_edit_id" name="id" type="hidden" class="form-control">
							<label>Enter Description</label>
							<textarea id="permission_edit_text" name="text" class="form-control"></textarea>
						</div>
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					Close
				</button>
				<button type="submit" type="button" class="btn btn-primary">
					Save
				</button>
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>

@stop

@section('scripts')
<script type="text/javascript">
	$(document).ready(function() {

		$('.edit_permission').on('click', function() {

			var permission_id = $(this).data('id');

			//Get request to get department with department_id and set that to edit fields
			$.ajax({
				url : "/permissions/get/" + permission_id,
				success : function(permission) {
					$('#permission_edit_id').val(permission.id);
					$('#permission_edit_text').val(permission.text);
				},
				error : function(response) {

				}
			});
		});

	}); 
</script>
@stop