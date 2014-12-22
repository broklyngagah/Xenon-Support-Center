@extends('layouts.master')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-title">
            <h3>Departments
                <small>Control panel.</small>
            </h3>
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
                Departments
            </li>
        </ul>
    </div>
    <!-- /breadcrumbs line -->

    @include('layouts.notify')

    {{Form::open(['url'=>'/departments/create','method'=>'post','files'=>true,'class'=>'form-horizontal form-bordered','role'=>'form'])}}

    <!-- Button trigger modal -->

    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><i class="icon-user-plus2"></i> Create New Department</h6>

            <div class="table-controls pull-right">
                <input type="submit" value="Save" class="btn btn-info">
            </div>
        </div>
        <div class="panel-body">

            <div class="form-group">
                <label class="col-sm-2 control-label">Enter Name</label>

                <div class="col-sm-10">
                    <input name="name" type="text" class="form-control" value="{{Input::old('name')}}"
                           placeholder="Enter name">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Select Permissions</label>

                <div class="col-sm-10">
                    <select id="permissions" multiple="multiple" name="permissions[]">
                        @foreach($permissions as $permission)
                            <option value="{{$permission->key}}">{{$permission->text}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if(\KodeInfo\Utilities\Utils::isDepartmentAdmin(Auth::user()->id))

                <input type="hidden" name="company" value="{{$company->id}}"/>

            @elseif(\KodeInfo\Utilities\Utils::isOperator(Auth::user()->id))

                <input type="hidden" name="company" value="{{$company->id}}"/>

            @else

            <div class="form-group">
                <label class="col-sm-2 control-label">Company</label>

                <div class="col-sm-10">
                    <select class="form-control" name="company" id="companies">
                        @foreach($companies as $company)
                            <option value="{{$company->id}}">{{$company->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @endif

            <div class="form-group">
                <label class="col-sm-2 control-label">Department Admin</label>

                <div class="col-sm-10">
                    <select class="form-control" name="department_admin" id="department_admin">
                        <option value="0">NONE</option>
                        @foreach($admins as $admin)
                            <option value="{{$admin->id}}">{{$admin->name}}</option>
                        @endforeach
                    </select>
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
    <script type="text/javascript">
        $(document).ready(function () {

            $('#permissions').multiSelect();

            $('#companies').on('change', function () {

                var company_id = $(this).find('option:selected').val();

                //Get request to get departments with company_id and set that to edit fields
                $.ajax({
                    url: "/api/get_company_free_admins/" + company_id,
                    success: function (departments) {

                        var $select = $('#department_admin');

                        $select.find('option').remove();

                        $.each(departments, function (key, value) {
                            $select.append('<option value=' + value.id + '>' + value.name + '</option>');
                        });
                    },
                    error: function (response) {

                    }
                });
            });
        });
    </script>
@stop