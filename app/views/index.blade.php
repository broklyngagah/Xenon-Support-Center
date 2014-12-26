@extends('layouts.master')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-title">
            <h3>XENON Support
                <small>Welcome {{Auth::user()->name}}.</small>
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
                Dashboard
            </li>
        </ul>
    </div>
    <!-- /breadcrumbs line -->

    @include('layouts.notify')

    @if(\KodeInfo\Utilities\Utils::isAdmin(Auth::user()->id))
        <!-- Default info blocks -->
        <div class="block">
            <h5>Tickets</h5>
            <ul class="statistics list-justified">
                <li>
                    <div class="statistics-info">
                        <a href="#" title="" class="bg-success"><i
                                    class="icon-user-plus"></i></a><strong>{{$tickets_past_hr}}</strong>
                    </div>
                    <div class="progress progress-micro">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100"
                             aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                    </div>
                    <span>Past hour</span>
                </li>
                <li>
                    <div class="statistics-info">
                        <a href="#" title="" class="bg-warning"><i
                                    class="icon-user-plus"></i></a><strong>{{$tickets_today}}</strong>
                    </div>
                    <div class="progress progress-micro">
                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="100"
                             aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                    </div>
                    <span>Today</span>
                </li>
                <li>
                    <div class="statistics-info">
                        <a href="#" title="" class="bg-info"><i
                                    class="icon-user-plus"></i></a><strong>{{$tickets_this_week}}</strong>
                    </div>
                    <div class="progress progress-micro">
                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="100"
                             aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                    </div>
                    <span>Week</span>
                </li>
                <li>
                    <div class="statistics-info">
                        <a href="#" title="" class="bg-danger"><i
                                    class="icon-user-plus"></i></a><strong>{{$tickets_this_month}}</strong>
                    </div>
                    <div class="progress progress-micro">
                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="100"
                             aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                    </div>
                    <span>Month</span>
                </li>
                <li>
                    <div class="statistics-info">
                        <a href="#" title="" class="bg-primary"><i
                                    class="icon-user-plus"></i></a><strong>{{$tickets_total}}</strong>
                    </div>
                    <div class="progress progress-micro">
                        <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="100"
                             aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                    </div>
                    <span>Total tickets</span>
                </li>
            </ul>
        </div>
        <!-- /default info blocks -->

        @foreach($department_stats as $stats)
            <h4 style="text-decoration: underline;">{{$stats->name}}</h4>
            @foreach($stats->departments as $department)
                <div class="block">
                    <h5>{{$department->name}}</h5>
                    <ul class="statistics list-justified">
                        <li>
                            <div class="statistics-info">
                                <a href="#" title="" class="bg-success"><i
                                            class="icon-user-plus"></i></a><strong>{{$department->all_tickets}}</strong>
                            </div>
                            <div class="progress progress-micro">
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100"
                                     aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                            </div>
                            <span>All Tickets</span>
                        </li>
                        <li>
                            <div class="statistics-info">
                                <a href="#" title="" class="bg-warning"><i
                                            class="icon-user-plus"></i></a><strong>{{$department->pending_tickets}}</strong>
                            </div>
                            <div class="progress progress-micro">
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="100"
                                     aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                            </div>
                            <span>Pending Tickets</span>
                        </li>
                        <li>
                            <div class="statistics-info">
                                <a href="#" title="" class="bg-info"><i
                                            class="icon-user-plus"></i></a><strong>{{$department->resolved_tickets}}</strong>
                            </div>
                            <div class="progress progress-micro">
                                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="100"
                                     aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                            </div>
                            <span>Resolved Tickets</span>
                        </li>
                        <li>
                            <div class="statistics-info">
                                <a href="#" title="" class="bg-danger"><i
                                            class="icon-user-plus"></i></a><strong>{{$department->operators_online}}</strong>
                            </div>
                            <div class="progress progress-micro">
                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="100"
                                     aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                            </div>
                            <span>Operators online</span>
                        </li>
                        <li>
                            <div class="statistics-info">
                                <a href="#" title="" class="bg-primary"><i
                                            class="icon-user-plus"></i></a><strong>{{$department->operators_offline}}</strong>
                            </div>
                            <div class="progress progress-micro">
                                <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="100"
                                     aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                            </div>
                            <span>Operators offline</span>
                        </li>
                    </ul>
                </div>
                <hr>
            @endforeach
        @endforeach
    @endif

    @if(\KodeInfo\Utilities\Utils::isDepartmentAdmin(Auth::user()->id)||\KodeInfo\Utilities\Utils::isOperator(Auth::user()->id))

        <div class="block">
            <h5>Tickets</h5>
            <ul class="statistics list-justified">
                <li>
                    <div class="statistics-info">
                        <a href="#" title="" class="bg-success"><i
                                    class="icon-user-plus"></i></a><strong>{{$tickets_past_hr}}</strong>
                    </div>
                    <div class="progress progress-micro">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100"
                             aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                    </div>
                    <span>Past hour</span>
                </li>
                <li>
                    <div class="statistics-info">
                        <a href="#" title="" class="bg-warning"><i
                                    class="icon-user-plus"></i></a><strong>{{$tickets_today}}</strong>
                    </div>
                    <div class="progress progress-micro">
                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="100"
                             aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                    </div>
                    <span>Today</span>
                </li>
                <li>
                    <div class="statistics-info">
                        <a href="#" title="" class="bg-info"><i
                                    class="icon-user-plus"></i></a><strong>{{$tickets_this_week}}</strong>
                    </div>
                    <div class="progress progress-micro">
                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="100"
                             aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                    </div>
                    <span>Week</span>
                </li>
                <li>
                    <div class="statistics-info">
                        <a href="#" title="" class="bg-danger"><i
                                    class="icon-user-plus"></i></a><strong>{{$tickets_this_month}}</strong>
                    </div>
                    <div class="progress progress-micro">
                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="100"
                             aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                    </div>
                    <span>Month</span>
                </li>
                <li>
                    <div class="statistics-info">
                        <a href="#" title="" class="bg-primary"><i
                                    class="icon-user-plus"></i></a><strong>{{$tickets_total}}</strong>
                    </div>
                    <div class="progress progress-micro">
                        <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="100"
                             aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                    </div>
                    <span>Total tickets</span>
                </li>
            </ul>
        </div>
        <div class="block">
            <h5>{{$department_stats->name}}</h5>
            <ul class="statistics list-justified">
                <li>
                    <div class="statistics-info">
                        <a href="#" title="" class="bg-success"><i
                                    class="icon-user-plus"></i></a><strong>{{$department_stats->all_tickets}}</strong>
                    </div>
                    <div class="progress progress-micro">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100"
                             aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                    </div>
                    <span>All Tickets</span>
                </li>
                <li>
                    <div class="statistics-info">
                        <a href="#" title="" class="bg-warning"><i
                                    class="icon-user-plus"></i></a><strong>{{$department_stats->pending_tickets}}</strong>
                    </div>
                    <div class="progress progress-micro">
                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="100"
                             aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                    </div>
                    <span>Pending Tickets</span>
                </li>
                <li>
                    <div class="statistics-info">
                        <a href="#" title="" class="bg-info"><i
                                    class="icon-user-plus"></i></a><strong>{{$department_stats->resolved_tickets}}</strong>
                    </div>
                    <div class="progress progress-micro">
                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="100"
                             aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                    </div>
                    <span>Resolved Tickets</span>
                </li>
                <li>
                    <div class="statistics-info">
                        <a href="#" title="" class="bg-danger"><i
                                    class="icon-user-plus"></i></a><strong>{{$department_stats->operators_online}}</strong>
                    </div>
                    <div class="progress progress-micro">
                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="100"
                             aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                    </div>
                    <span>Operators online</span>
                </li>
                <li>
                    <div class="statistics-info">
                        <a href="#" title="" class="bg-primary"><i
                                    class="icon-user-plus"></i></a><strong>{{$department_stats->operators_offline}}</strong>
                    </div>
                    <div class="progress progress-micro">
                        <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="100"
                             aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                    </div>
                    <span>Operators offline</span>
                </li>
            </ul>
        </div>
        <hr>
    @endif

@stop