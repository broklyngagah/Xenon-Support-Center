@extends('layouts.master')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-title">
            <h3>Companies
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
                Companies
            </li>
        </ul>
    </div>
    <!-- /breadcrumbs line -->

    @include('layouts.notify')

    {{Form::open(['url'=>'/companies/update/'.$company->id,'method'=>'post','files'=>true,'class'=>'form-horizontal form-bordered','role'=>'form'])}}

    <!-- Button trigger modal -->

    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><i class="icon-user-plus2"></i> Update Company</h6>

            <div class="table-controls pull-right">
                <input type="submit" value="Save" class="btn btn-info">
            </div>
        </div>
        <div class="panel-body">

            <div class="form-group">
                <label class="col-sm-2 control-label">Company Name</label>

                <div class="col-sm-10">
                    <input name="id" type="hidden" class="form-control" value="{{$company->id}}">
                    <input name="name" type="text" class="form-control" value="{{Input::old('name',$company->name)}}">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Description</label>

                <div class="col-sm-10">
                    <textarea name="description"
                              class="form-control">{{Input::old('description',$company->description)}}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Company Domain Name</label>

                <div class="col-sm-10">
                    <input name="domain" type="text" class="form-control" placeholder="http://kodeinfo.com"
                           value="{{Input::old('domain',$company->domain)}}">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Change Company Logo</label>

                <div class="col-sm-10">
                    <p><img class="img-circle" style="width:80px;" src="{{$company->logo}}"/>
                    </p>
                    <input name="logo" type="file" class="form-control">
                    <input name="old_logo" type="hidden" value="{{$company->logo}}">
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