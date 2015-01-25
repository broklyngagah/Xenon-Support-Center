@extends('layouts.master')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-title">
            <h3>Canned Messages
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
                Canned Messages
            </li>
        </ul>
    </div>
    <!-- /breadcrumbs line -->

    @include('layouts.notify')

    {{Form::open(['url'=>'/canned_messages/store','method'=>'post','files'=>true,'class'=>'form-horizontal form-bordered','role'=>'form'])}}

    <!-- Button trigger modal -->

    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><i class="icon-user-plus2"></i> Create New Message</h6>

            <div class="table-controls pull-right">
                <input type="submit" value="Save" class="btn btn-info">
            </div>
        </div>
        <div class="panel-body">


            <div class="form-group">
                <label class="col-sm-2 control-label">Message</label>

                <div class="col-sm-10">
                    <textarea name="message" class="form-control">{{Input::old('message')}}</textarea>
                </div>
            </div>

            @if(\KodeInfo\Utilities\Utils::isDepartmentAdmin(Auth::user()->id))

                <input type="hidden" name="company" value="{{$company->id}}"/>
                <input type="hidden" name="department" value="{{$department->id}}"/>

            @elseif(\KodeInfo\Utilities\Utils::isOperator(Auth::user()->id))

                <input type="hidden" name="company" value="{{$company->id}}"/>
                <input type="hidden" name="department" value="{{$department->id}}"/>
                <input type="hidden" name="operator" value="{{$operator->id}}"/>

            @else

                <div class="form-group">
                    <label class="col-sm-2 control-label">Company</label>

                    <div class="col-sm-10">
                        <select class="form-control" name="company" id="company">
                            @foreach($companies as $company)
                                <option value="{{$company->id}}">{{$company->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Department</label>

                    <div class="col-sm-10">
                        <select class="form-control" name="department" id="department">
                            @foreach($departments as $department)
                                <option value="{{$department->id}}">{{$department->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>



            <div class="form-group">
                <label class="col-sm-2 control-label">Operator ID - Name</label>

                <div class="col-sm-10">
                    <select class="form-control" name="operator" id="operator">
                        @foreach($operators as $operator)
                            <option value="{{$operator->id}}">{{$operator->id." - ".$operator->name}}</option>
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