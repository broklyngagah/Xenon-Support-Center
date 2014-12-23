@extends('layouts.master')

@section('content')
<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>Tickets <small>Control panel.</small></h3>
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
			Create new Ticket
		</li>
	</ul>
</div>
<!-- /breadcrumbs line -->

@include('layouts.notify')

{{Form::open(['url'=>'/tickets/create','method'=>'post','files'=>true,'class'=>'form-horizontal form-bordered','role'=>'form'])}}

<div class="panel panel-default">
	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-user-plus2"></i> Create new Ticket</h6>
		<div class="table-controls pull-right">
			<input type="submit" value="Save" class="btn btn-info">
		</div>
	</div>
	<div class="panel-body" style="margin:10px;">
		<div class="form-group">
			<div class="row">
				<div class="col-md-6">
					<label>Name:</label>
					<input type="text" class="form-control" name="name" value="{{Input::old('name')}}" placeholder="Enter name">
				</div>
				<div class="col-md-6">
					<label>Email:</label>
					<input type="text" class="form-control" placeholder="your@email.com" name="email" value="{{Input::get('email')}}">
				</div>
			</div>
		</div>

		<input type="hidden" name="ip" id="ip" value=""/>
		<input type="hidden" name="country" id="country" value=""/>
		<input type="hidden" name="provider" id="provider" value=""/>

		<div class="form-group">
			<div class="row">
				<div class="col-md-6">
					<label>Priority:</label>
					<select name="priority" data-placeholder="Choose your system..." class="select-full" tabindex="2">
						<option {{Input::old('priority')==Tickets::PRIORITY_LOW ? "selected":""}} value="{{Tickets::PRIORITY_LOW}}">Low</option>
						<option {{Input::old('priority')==Tickets::PRIORITY_MEDIUM ? "selected":""}} value="{{Tickets::PRIORITY_MEDIUM}}">Medium</option>
						<option {{Input::old('priority')==Tickets::PRIORITY_HIGH ? "selected":""}} value="{{Tickets::PRIORITY_HIGH}}">High</option>
						<option {{Input::old('priority')==Tickets::PRIORITY_URGENT ? "selected":""}} value="{{Tickets::PRIORITY_URGENT}}">Urgent</option>
					</select>
				</div>
				<div class="col-md-6">
					<label>Upload files:</label>
					<input type="file" name="attachment" class="styled form-control" id="report-screenshot">
					<span class="help-block">Accepted formats: rar, zip . Max file size 10Mb</span>
				</div>
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
			<div class="row">
				<div class="col-md-6">
					<label>Company:</label>
					<select id="company" name="company" data-placeholder="Choose your company..." class="form-control" tabindex="2">
						@foreach($companies as $company)
							<option {{Input::old('company')==$company->id?"selected":""}} value="{{$company->id}}">{{$company->name}}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-6">
					<label>Department:</label>
					<select data-placeholder="Choose issue type..." class="form-control" name="department" id="department" tabindex="2">
						@foreach($departments as $department)
							<option value="{{$department->id}}">{{$department->name}}</option>
						@endforeach
					</select>
				</div>
			</div>
		</div>

		@endif

		<div class="form-group">
			<label>Subject:</label>
			<input name="subject" placeholder="Subject." type="text" class="elastic form-control"/>
		</div>
		<div class="form-group">
			<label>Additional information:</label>
			<textarea rows="5" cols="5" name="description" placeholder="Add info about your ticket." class="elastic form-control editor"></textarea>
		</div>
		<div class="form-actions text-right">
			<input type="reset" value="Reset" class="btn btn-danger">
			<input type="submit" value="Submit ticket" class="btn btn-primary">
		</div>
	</div>
</div>
{{Form::close()}}

@stop

@section('scripts')
<script type="text/javascript">

	$.get("http://ipinfo.io", function(response) {

		location_info = response;
		$("#ip").val(location_info.ip);
		$("#country").val(location_info.country);
		$("#provider").val(location_info.org);

	}, "jsonp");

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



			},
			error : function(response) {

			}
		});
	});
</script>
@stop
