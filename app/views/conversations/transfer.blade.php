@extends('layouts.master')

@section('content')
<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>Transfer <small>Control panel.</small></h3>
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
			Transfer
		</li>
	</ul>
</div>
<!-- /breadcrumbs line -->

@include('layouts.notify')

{{Form::open(['url'=>'/conversations/transfer/'.$online_users->id,'method'=>'post','files'=>true,'class'=>'form-horizontal form-bordered','role'=>'form'])}}

<div class="panel panel-default">
	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-user-plus2"></i> Transfer Conversation</h6>
		<div class="table-controls pull-right">
			<input type="submit" value="Transfer" class="btn btn-info">
		</div>
	</div>
	<div class="panel-body" style="margin:10px;">
		<div class="form-group">
			<div class="row">
				<div class="col-md-6">
					<label>Name:</label>
					<input disabled type="text" class="form-control" name="name" value="{{$customer->name}}">
					<input type="hidden" class="form-control" name="conversation_id" value="{{$online_users->id}}">
				</div>
				<div class="col-md-6">
					<label>Email:</label>
					<input disabled type="text" class="form-control" name="email" value="{{$customer->email}}">
				</div>
			</div>
		</div>


		<div class="form-group">
			<div class="row">
				<div class="col-md-6">
					<label>Company:</label>
					<select id="company" name="company" class="form-control">
						@foreach($companies as $company)
							<option {{Input::old('company',$company_id)==$company->id?"selected":""}} value="{{$company->id}}">{{$company->name}}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-6">
					<label>Department:</label>
					<select id="department" name="department" class="form-control">
						@foreach($departments as $department)
							<option {{Input::old('department',$department_id)==$department->id?"selected":""}} value="{{$department->id}}">{{$department->name}}</option>
						@endforeach
					</select>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="row">
				<div class="col-md-12">
					<label>Operator:</label>
					<select id="operator" name="operator" class="form-control">
						@foreach($operators as $operator)
							<option {{Input::old('operator',$online_users->operator_id)==$operator->id?"selected":""}} value="{{$operator->id}}">{{$operator->name}}</option>
						@endforeach
					</select>
				</div>

			</div>

		</div>

		<div class="form-actions text-right">
			<input type="submit" value="Transfer Conversation" class="btn btn-primary">
		</div>
	</div>
</div>
{{Form::close()}}

@stop

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function(){
			$('#company').on('change', function() {

				var company_id = $(this).find('option:selected').val();

				//Get request to get department with department_id and set that to edit fields
				$.ajax({
					url : "/api/get_company_departments/" + company_id,
					success : function(permissions) {

						var $select = $('#department');

						$select.find('option').remove();

						$.each(permissions, function(key, value) {
							$select.append('<option value=' + value.id + '>' + value.name + '</option>');
						});

						var department_id = $select.find('option:selected').val();

						if(department_id>0&&department_id!=undefined) {
							departmentChanged(department_id);
						}else {
							//Department empty
							$('#operator').find('option').remove();
						}
					},
					error : function(response) {

					}
				});
			});


			$('#department').on('change', function() {

				var department_id = $(this).find('option:selected').val();

				departmentChanged(department_id);
			});

			function departmentChanged(department_id){

				//Get request to get department with department_id and set that to edit fields
				$.ajax({
					url : "/api/get_department_operators/" + department_id,
					success : function(operators) {

						var $select = $('#operator');

						$select.find('option').remove();

						$.each(operators, function(key, value) {
							$select.append('<option value=' + value.id + '>' + value.name + '</option>');
						});

					},
					error : function(response) {

					}
				});
			}
		});
	</script>
@stop
