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
			Tickets
		</li>
	</ul>
</div>
<!-- /breadcrumbs line -->

@include('layouts.notify')
<div class="panel panel-default">
	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-user4"></i> All Tickets</h6>
		<div class="table-controls pull-right">
			<a href="/tickets/create" class="btn btn-default btn-icon btn-xs tip" title="" data-original-title="Add Ticket"><i class="icon-plus"></i></a>
			<a href="/tickets/export" class="btn btn-default btn-icon btn-xs tip" title="" data-original-title="Export"><i class="icon-cogs"></i></a>
		</div>
	</div>
	<div class="datatable">
		<table id="tickets_all" class="table">
			{{$tickets_all_str}}
		</table>
	</div>
</div>

@stop

@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){

		var department_id = {{isset($department)?$department->id:0}} ;
		var company_id = {{isset($company)?$company->id:0}} ;

		var interval = 5000;  // 1000 = 1 second, 3000 = 3 seconds

		function doStartup() {

			$.ajax({
				type: 'GET',
				url: '/api/tickets_all_refresh?department_id='+department_id+'&company_id='+company_id,
				success: function (data) {

					var obj = JSON.parse(data);
					var table = $('#tickets_all');

					//table.dataTable().fnDestroy();
					table.html(obj.tickets_all);
					//table.dataTable();

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