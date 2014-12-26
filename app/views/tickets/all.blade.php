@extends('layouts.master')

@section('content')

    <!-- Page header -->
    <div class="page-header">
        <div class="page-title">
            <h3>Tickets
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
                Tickets
            </li>
        </ul>
    </div>
    <!-- /breadcrumbs line -->

    @include('layouts.notify')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title"><i class="icon-user4"></i> Tickets</h6>

            <div class="table-controls pull-right">
                <a href="/tickets/create" class="btn btn-default btn-icon btn-xs tip" title=""
                   data-original-title="Add Ticket"><i class="icon-plus"></i></a>
            </div>
        </div>
        <table id="tickets_all" class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Company</th>
                <th>Department</th>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Operator</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Transfer</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
@stop

@section('scripts')
    @if(isset($do_refresh)&&$do_refresh==true)
        <script type="text/javascript">
            $(document).ready(function () {

                var interval = 5000;
                var department_id = {{isset($department)?$department->id:0}};
                var company_id = {{isset($company)?$company->id:0}};

                var oTable = $('#tickets_all').dataTable({
                    "bJQueryUI": false,
                    "bAutoWidth": false,
                    "sPaginationType": "full_numbers",
                    "sDom": '<"datatable-header"Tfl><"datatable-scroll"t><"datatable-footer"ip>',
                    "oLanguage": {
                        "sSearch": "<span>Filter:</span> _INPUT_",
                        "sLengthMenu": "<span>Show:</span> _MENU_",
                        "oPaginate": {"sFirst": "First", "sLast": "Last", "sNext": ">", "sPrevious": "<"}
                    },
                    "oTableTools": {
                        "sRowSelect": "single",
                        "sSwfPath": "/assets/copy_csv_xls_pdf.swf",
                        "aButtons": [
                            {
                                "sExtends": "copy",
                                "sButtonText": "Copy",
                                "sButtonClass": "btn"
                            },
                            {
                                "sExtends": "print",
                                "sButtonText": "Print",
                                "sButtonClass": "btn"
                            },
                            {
                                "sExtends": "collection",
                                "sButtonText": "Save <span class='caret'></span>",
                                "sButtonClass": "btn btn-primary",
                                "aButtons": ["csv", "xls", "pdf"]
                            }
                        ]
                    }
                });

                oTable.fnReloadAjax('/api/tickets_all_refresh?department_id=' + department_id + '&company_id=' + company_id);

                setInterval(function () {
                    oTable.fnReloadAjax('/api/tickets_all_refresh?department_id=' + department_id + '&company_id=' + company_id);
                }, interval);
            });
        </script>
    @endif
@stop