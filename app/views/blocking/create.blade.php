@extends('layouts.master')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-title">
            <h3>Block New IP <small>Control panel.</small></h3>
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
                Block New IP
            </li>
        </ul>
    </div>
    <!-- /breadcrumbs line -->

    @include('layouts.notify')

    {{Form::open(['url'=>'/blocking/create','method'=>'post','files'=>true])}}

    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><i class="icon-user-plus2"></i> Block New IP</h6>
            <div class="table-controls pull-right">
                <input type="submit" value="Save" class="btn btn-info">
            </div>
        </div>
        <div class="panel-body">

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <label>IP Address:</label>
                        <input type="text" class="form-control" name="ip_address" value="{{Input::old('ip_address')}}" placeholder="Enter IP Address">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label class="checkbox-inline checkbox-info">
                            <input type="checkbox" class="styled"
                                   name="should_block_chat" {{Input::get('should_block_chat')==1?"checked":""}} checked="checked"
                                   value="1">
                            <label>Should Block Chat</label>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="checkbox-inline checkbox-info">
                            <input type="checkbox" class="styled"
                                   name="should_block_tickets" {{Input::get('should_block_tickets')==1?"checked":""}} checked="checked"
                                   value="1">
                            <label>Should Block Tickets</label>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label class="checkbox-inline checkbox-info">
                            <input type="checkbox" class="styled"
                                   name="should_block_login" {{Input::get('should_block_login')==1?"checked":""}} checked="checked"
                                   value="1">
                            <label>Should Block Login</label>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="checkbox-inline checkbox-info">
                            <input type="checkbox" class="styled"
                                   name="should_block_web_access" {{Input::get('should_block_web_access')==1?"checked":""}} checked="checked"
                                   value="1">
                            <label>Should Block Web Access</label>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-actions text-right">
                <input type="reset" value="Reset" class="btn btn-danger">
                <input type="submit" value="Save" class="btn btn-primary">
            </div>
        </div>
    </div>
    {{Form::close()}}

@stop