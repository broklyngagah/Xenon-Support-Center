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
                <th>Transfer</th>
                <th>Close</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

@stop

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {

            var interval = 5000;  // 1000 = 1 second, 3000 = 3 seconds
            var department_id = {{isset($department)?$department->id:0}};
            var company_id = {{isset($company)?$company->id:0}};

            var oTable = $('#online_users').dataTable({
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

            oTable.fnReloadAjax('/api/online_conversations_refresh?department_id=' + department_id + '&company_id=' + company_id);

            setInterval(function () {
                oTable.fnReloadAjax('/api/online_conversations_refresh?department_id=' + department_id + '&company_id=' + company_id);
            }, interval);

        });
    </script>
@stop