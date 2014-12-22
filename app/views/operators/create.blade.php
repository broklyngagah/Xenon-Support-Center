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

{{Form::open(['url'=>'/operators/create','method'=>'post','files'=>true,'class'=>'form-horizontal form-bordered','role'=>'form'])}}

<div class="panel panel-default">
	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-user-plus2"></i> Create New Operator</h6>
		<div class="table-controls pull-right">
			<input type="submit" value="Save" class="btn btn-info">
		</div>
	</div>
	<div class="panel-body">
		<div class="form-group">
			<label class="col-sm-2 control-label">Enter Email</label>
			<div class="col-sm-10">
				<input name="email" type="text" class="form-control" value="{{Input::old('email')}}">
			</div>
		</div>
		<div class="form-group">
        	<label class="col-sm-2 control-label">Enter Name</label>
        	<div class="col-sm-10">
        		<input name="name" type="text" class="form-control" value="{{Input::old('name')}}">
        	</div>
        </div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Enter Password</label>
			<div class="col-sm-10">
				<input name="password" type="password" class="form-control" value="{{Input::old('password')}}">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Repeat your Password</label>
			<div class="col-sm-10">
				<input name="password_confirmation" type="password" class="form-control" value="{{Input::old('password_confirmation')}}">
			</div>
		</div>


		<div class="form-group">
        	<label class="col-sm-2 control-label">Birthday</label>
        	<div class="col-sm-10">
        		<input id="birthday" name="birthday" type="text" class="form-control" value="{{Input::old('birthday')}}"/>
        	</div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Bio</label>
            <div class="col-sm-10">
                <textarea name="bio" class="form-control">{{Input::old('bio')}}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Mobile No</label>
            <div class="col-sm-10">
                <input name="mobile_no" class="form-control" value="{{Input::old('mobile_no')}}"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Country</label>
            <div class="col-sm-10">
                <select name="country" class="form-control">
                @foreach($countries as $country)
                    <option {{Input::old("country")==$country->countryName?"selected":""}} value="{{$country->countryName}}">{{$country->countryName}}</option>
                @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Gender</label>
            <div class="col-sm-10">
                <input type="radio" name="gender" value="male" {{Input::old("gender")=="male"?"checked":""}}/> Male
                <input type="radio" name="gender" value="female" {{Input::old("gender")=="female"?"checked":""}}/> Female
            </div>
        </div>

		<div class="form-group">
			<label class="col-sm-2 control-label">Select Operator Avatar</label>
			<div class="col-sm-10">
				<input name="avatar" type="file" class="form-control">
			</div>
		</div>
		<div class="form-group">
        	<label class="col-sm-2 control-label">Select Timezone</label>
        	<div class="col-sm-10">
        		<select class="form-control" name="timezone">
        			@foreach($timezones as $key=>$value)
        			    <option value="{{$key}}">{{$value}}</option>
        			@endforeach
        		</select>
        	</div>
        </div>

		@if(\KodeInfo\Utilities\Utils::isDepartmentAdmin(Auth::user()->id))

			<input type="hidden" name="company" value="{{$company->id}}"/>
			<input type="hidden" name="department" value="{{$department->id}}"/>

		@elseif(\KodeInfo\Utilities\Utils::isOperator(Auth::user()->id))

			<input type="hidden" name="company" value="{{$company->id}}"/>
			<input type="hidden" name="department" value="{{$department->id}}"/>

		@else

			<div class="form-group">
				<label class="col-sm-2 control-label">Select Company</label>
				<div class="col-sm-10">
					<select id="companies" class="form-control" name="company">
						@foreach($companies as $company)
							<option {{Input::old('company_id')==$company->id?"selected":""}} value="{{$company->id}}">{{$company->name}}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Select Department</label>
				<div class="col-sm-10">
					<select id="departments" class="form-control" name="department">
						@foreach($departments as $department)
							<option {{Input::old('department_id')==$department->id?"selected":""}} value="{{$department->id}}">{{$department->name}}</option>
						@endforeach
					</select>
				</div>
			</div>


		@endif

		<div class="form-group">
			<label class="col-sm-2 control-label">Select Permissions</label>
			<div class="col-sm-10">
				<select id="permissions" multiple="multiple" name="permissions[]">
					@foreach($permissions as $permission)
					<option {{Input::old('permissions')==$permission->id?"selected":""}} value="{{$permission->key}}">{{$permission->text}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label"></label>
			<div class="col-sm-10">
				<input type="checkbox" {{Input::old('activated')==1?"checked":""}} value="1" name="activated"/>
				Click here if you want to activate account
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label"></label>
			<div class="col-sm-10">
				<input type="checkbox" {{Input::old('show_avatar')==1?"checked":""}} value="1" name="show_avatar"/>
				Display Avatar in Chat
			</div>
		</div>
		<div class="form-actions text-right">
			<label class="col-sm-2 control-label"></label>
			<input type="submit" value="Save" class="btn btn-info">
		</div>
	</div>
</div>
{{Form::close()}}

@stop

@section('scripts')
{{HTML::style("/assets/plugins/jquery-multi-select/css/multi-select.css")}}
{{HTML::script("/assets/plugins/jquery-multi-select/js/jquery.multi-select.js")}}

{{HTML::style("/assets/plugins/datepicker/css/datepicker3.css")}}
{{HTML::script("/assets/plugins/datepicker/js/bootstrap-datepicker.js")}}
<script type="text/javascript">
	$(document).ready(function() {

        $('#birthday').datepicker({
        		format : "dd-mm-yyyy"
        	});

		$('#permissions').multiSelect();

		$('#companies').on('change', function() {

        	var company_id = $(this).find('option:selected').val();

        	//Get request to get department with department_id and set that to edit fields
        	$.ajax({
        		url : "/api/get_company_departments/" + company_id,
        		success : function(permissions) {

        		    var $select = $('#departments');

        		    $select.find('option').remove();

        		    $.each(permissions, function(key, value) {
        			    $select.append('<option value=' + value.id + '>' + value.name + '</option>');
        		    });

        		    var department_id = $select.find('option:selected').val();

					if(department_id>0&&department_id!=undefined) {
						departmentChanged(department_id);
					}else {
						//Department empty
						$('#permissions').find('option').remove();
						$('#permissions').multiSelect('refresh');
					}
				},
        		error : function(response) {

        		}
        	});
        });


		$('#departments').on('change', function() {

			var department_id = $(this).find('option:selected').val();

            departmentChanged(department_id);
		});

		function departmentChanged(department_id){

			//Get request to get department with department_id and set that to edit fields
			$.ajax({
				url : "/api/get_department_permissions/" + department_id,
				success : function(permissions) {

					var $select = $('#permissions');

					$select.find('option').remove();

					$.each(permissions, function(key, value) {
						$select.append('<option value=' + value.key + '>' + value.text + '</option>');
					});

					$select.multiSelect('refresh');

				},
				error : function(response) {

				}
			});
		}

	}); 
</script>

@stop