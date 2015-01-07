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
			Department Admin
		</li>
	</ul>
</div>
<!-- /breadcrumbs line -->

@include('layouts.notify')

{{Form::open(['url'=>'/departments/admins/update/'.$user->id,'method'=>'post','files'=>true,'class'=>'form-horizontal form-bordered','role'=>'form'])}}

<!-- Button trigger modal -->

<div class="panel panel-default">
	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-user-plus2"></i> Edit Department Admin</h6>
		<div class="table-controls pull-right">
			<input type="submit" value="Save" class="btn btn-info">
		</div>
	</div>
	<div class="panel-body">

		<div class="form-group">
			<label class="col-sm-2 control-label">Enter Email</label>
			<div class="col-sm-10">
				<input name="email" type="text" class="form-control" disabled value="{{{Input::old('email',$user->email)}}}">
				<input name="user_id" type="hidden" value="{{$user->id}}">
			</div>
		</div>

	    <div class="form-group">
    		<label class="col-sm-2 control-label">Enter Name</label>
    		<div class="col-sm-10">
    			<input name="name" type="text" class="form-control" value="{{{Input::old('name',$user->name)}}}">
    		</div>
    	</div>


		<div class="form-group">
			<label class="col-sm-2 control-label">Select Avatar</label>
			<div class="col-sm-10">
				<input name="avatar" type="file" class="form-control">
				<input name="old_avatar" type="hidden" value="{{$user->avatar}}">
			</div>

		</div>

		<div class="form-group">
        	<label class="col-sm-2 control-label"></label>
        	<div class="col-sm-10">
        		<p><img class="img-circle" style="width:80px;" src="{{$user->avatar}}"/></p>
        	</div>
        </div>


		<div class="form-group">
        	<label class="col-sm-2 control-label">Birthday</label>
        	<div class="col-sm-10">
        		<input id="birthday" name="birthday" type="text" class="form-control" value="{{{Input::old('birthday',date('d-m-Y',strtotime($user->birthday)))}}}"/>
        	</div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Bio</label>
            <div class="col-sm-10">
                <textarea name="bio" class="form-control">{{{Input::old('bio',$user->bio)}}}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Mobile No</label>
            <div class="col-sm-10">
                <input name="mobile_no" class="form-control" value="{{{Input::old('mobile_no',$user->mobile_no)}}}"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Country</label>
            <div class="col-sm-10">
                <select name="country" class="form-control">
                @foreach($countries as $country)
                    <option {{Input::old("country",$user->country)==$country->countryName?"selected":""}} value="{{{$country->countryName}}}">{{{$country->countryName}}}</option>
                @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Gender</label>
            <div class="col-sm-10">
                <input type="radio" name="gender" value="male" {{Input::old("gender",$user->gender)=="male"?"checked":""}}/> Male
                <input type="radio" name="gender" value="female" {{Input::old("gender",$user->gender)=="female"?"checked":""}}/> Female
            </div>
        </div>

		@if(\KodeInfo\Utilities\Utils::isDepartmentAdmin(Auth::user()->id))

			<input type="hidden" name="company" value="{{$company_id}}"/>
			<input type="hidden" name="department" value="{{$department_id}}"/>

		@elseif(\KodeInfo\Utilities\Utils::isOperator(Auth::user()->id))

			<input type="hidden" name="company" value="{{$company_id}}"/>
			<input type="hidden" name="department" value="{{$department_id}}"/>

		@else

			<div class="form-group">
				<label class="col-sm-2 control-label">Company</label>
				<div class="col-sm-10">
					<select id="companies" class="form-control" name="company">
						@foreach($companies as $company)
							<option {{Input::old("company",$user->company->id)==$company->id?"selected":""}} value="{{$company->id}}">{{{$company->name}}}</option>
						@endforeach
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">Select Department</label>
				<div class="col-sm-10">
					<select id="departments" class="form-control" name="department">
						<option value="0">NONE</option>
						@foreach($departments as $department)
							<option {{Input::old('department',$department_id)==$department->id?"selected":""}} value="{{$department->id}}">{{{$department->name}}}</option>
						@endforeach
					</select>
				</div>
			</div>

		@endif


		<div class="form-actions text-right">
			<label class="col-sm-2 control-label"></label>
			<input type="submit" value="Save" class="btn btn-info">
		</div>
	</div>
</div>
{{Form::close()}}

@stop

@section('scripts')
<script type="text/javascript">
	$('#birthday').datepicker({
		format : "dd-mm-yyyy"
	});

	$('#companies').on('change', function() {

		var company_id = $(this).find('option:selected').val();

		//Get request to get departments with company_id and set that to edit fields
		$.ajax({
			url : "/api/get_company_departments/" + company_id,
			success : function(departments) {

				var $select = $('#departments');

				$select.find('option').remove();

				$.each(departments, function(key, value) {
					$select.append('<option value=' + value.id + '>' + value.name + '</option>');
				});

				var department_id = $select.find('option:selected').val();

			},
			error : function(response) {

			}
		});
	});

</script>
@stop