@extends('layouts.master')

@section('content')

    <div class="page-header">
        <div class="page-title">
            <h3>Conversations / Chat
                <small>Control panel.</small>
            </h3>
        </div>
    </div>

    @include('layouts.notify')

    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><i class="icon-envelop"></i> Conversations</h6>
        </div>

        <div class="datatable">
            <table id="online_users" class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Operator Name</th>
                    <th>Requested On</th>
                    <th>Started On</th>
                    <th>Locked</th>
                    <th>Accept</th>
                    <th>Close</th>
                </tr>
                </thead>
                <tbody>
                @foreach($online_users as $online)
                    <tr>
                        <td>{{$online->id}}</td>
                        <td>{{$online->user->name}}</td>
                        <td>{{$online->user->email}}</td>
                        <td>{{isset($online->operator)?$online->operator->name:"<label class='label label-warning'>NONE</label>"}}</td>
                        <td>{{\KodeInfo\Utilities\Utils::prettyDate($online->requested_on,true)}}</td>
                        <td>{{\KodeInfo\Utilities\Utils::prettyDate($online->started_on,true)}}</td>
                        <td>{{$online->locked_by_operator==1?"<label class='label label-warning'>Yes</label>":"<label class='label label-primary'>No</label>"}}</td>

                        @if(!isset($online->operator))
                            <td><a href="/conversations/accept/{{$online->thread_id}}" class="btn btn-success btn-sm">
                                    <i class="icon-checkmark4"></i> Accept </a></td>
                        @endif

                        @if(isset($online->operator)&&$online->operator->id==Auth::user()->id)
                            <td><a href="/conversations/accept/{{$online->thread_id}}" class="btn btn-success btn-sm">
                                    <i class="icon-checkmark4"></i> Reply </a></td>
                        @endif

                        @if(isset($online->operator)&&$online->operator->id!=Auth::user()->id)
                            <td><a disabled class="btn btn-success btn-sm"> <i class="icon-lock3"></i> Accept </a></td>
                        @endif

                        <td><a href="/conversations/close/{{$online->thread_id}}" class="btn btn-danger btn-sm"> <i
                                        class="icon-lock3"></i> Close </a></td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>

    </div>

@stop

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {

            var interval = 5000;  // 1000 = 1 second, 3000 = 3 seconds

            function doStartup() {

                $.ajax({
                    type: 'GET',
                    url: '/api/startup_data',
                    success: function (data) {

                        var obj = JSON.parse(data);
                        var table = $('#online_users');

                        table.dataTable().fnDestroy();
                        table.html(obj.online_users);
                        table.dataTable();

                    },
                    complete: function (data) {
                        // Schedule the next
                        setTimeout(doStartup, interval);
                    }
                });

            }

            doStartup();
        });
    </script>
@stop