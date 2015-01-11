<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Xenon Support System</title>

		{{HTML::style("/assets/css/bootstrap.min.css")}}
		{{HTML::style("/assets/css/xenon-theme.min.css")}}
		{{HTML::style("/assets/css/styles.min.css")}}
		{{HTML::style("/assets/css/icons.min.css")}}
		{{HTML::style("http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic-ext")}}

		{{HTML::script("http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js")}}
		{{HTML::script("http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js")}}
		{{HTML::script("/assets/js/plugins/forms/uniform.min.js")}}
		{{HTML::script("/assets/js/plugins/forms/select2.min.js")}}
		{{HTML::script("/assets/js/plugins/forms/autosize.js")}}
		{{HTML::script("/assets/js/plugins/forms/inputlimit.min.js")}}
		{{HTML::script("/assets/js/plugins/forms/listbox.js")}}
		{{HTML::script("/assets/js/plugins/forms/multiselect.js")}}
		{{HTML::script("/assets/js/plugins/forms/validate.min.js")}}

		{{HTML::script("/assets/js/plugins/forms/tags.min.js")}}
		{{HTML::script("/assets/js/plugins/forms/switch.min.js")}}
		{{HTML::script("/assets/js/plugins/forms/wysihtml5/wysihtml5.min.js")}}
		{{HTML::script("/assets/js/plugins/forms/wysihtml5/toolbar.js")}}
		{{HTML::script("/assets/js/plugins/interface/daterangepicker.js")}}
		{{HTML::script("/assets/js/plugins/interface/fancybox.min.js")}}
		{{HTML::script("/assets/js/plugins/interface/moment.js")}}
		{{HTML::script("/assets/js/plugins/interface/jgrowl.min.js")}}
		{{HTML::script("/assets/js/plugins/interface/datatables.min.js")}}
		{{HTML::script("/assets/js/plugins/interface/tabletools.min.js")}}
		{{HTML::script("/assets/js/fnReloadAjax.js")}}

		{{HTML::style("/assets/js/plugins/jquery-multi-select/css/multi-select.css")}}
		{{HTML::script("/assets/js/plugins/jquery-multi-select/js/jquery.multi-select.js")}}
		{{HTML::style("/assets/js/plugins/datepicker/css/datepicker3.css")}}
		{{HTML::script("/assets/js/plugins/datepicker/js/bootstrap-datepicker.js")}}

		{{HTML::script("/assets/js/plugins/interface/collapsible.min.js")}}
		{{HTML::script("/assets/js/bootstrap.min.js")}}
		{{HTML::script("/assets/js/application.js")}}
		{{HTML::script("/assets/js/custom.js")}}

		<style>
			/* Ajax Stuff */
			#ajaxLoading {
				position: fixed;
				top: 50%;
				left: 50%;
				margin-top: -50px;
				margin-left: -100px;
				color: #43ac6a;
				z-index: 99999999999;
				display: none;
			}
			.no-click {
				pointer-events: none;
				cursor: default;
			}
			/* end Ajax */
		</style>

		@yield('styles')
		<base href="/"/>

	</head>
	<body class="sidebar-wide">

		@include('layouts.header')
		@include('layouts.sidebar')

		<!-- Page container -->
		<div class="page-container">

			<!-- Page content -->
			<div class="page-content">

				@yield('content')

				<!-- Footer -->
				@include('layouts.footer')
				<!-- /footer -->

			</div>
			<!-- /page content -->
		</div>
		<!-- /page container -->

		<script>
			jQuery(document).ready(function($) {

				$(document).bind("ajaxSend", function() {
					$(".disableOnAjax").prop('disabled', true).addClass("no-click");
					$("#ajaxLoading").show();
				}).bind("ajaxComplete", function() {
					$(".disableOnAjax").prop('disabled', false).removeClass("no-click");
					$("#ajaxLoading").hide();
				});

				@if(!Session::has('client_ip'))
				$.get("http://ipinfo.io", function (response) {

					$.ajax({
						'type': 'GET',
						'url': '/api/log_ip',
						'data': {
							'ip_address': response.ip
						},
						'success': function (data) {

						}
					});

				}, "jsonp");
				@endif

				var uni_interval = 5000;  // 1000 = 1 second, 3000 = 3 seconds
				var uni_department_id = {{isset($uni_department_id)?$uni_department_id:0}};
				var uni_company_id = {{isset($uni_company_id)?$uni_company_id:0}};

				var chat_oTable = $('#header_online_users').dataTable({
					"bJQueryUI": false,
					"bAutoWidth": false,
					"bPaginate": false,
					"bSort": false,
					"bFilter" : false,
					"bLengthChange": false,
					"bInfo" : false,
					"aoColumnDefs": [
						{ "bSortable": false, "aTargets": [] }
					],
					"aaSorting": []
				});

				var recent_oTable = $('#header_activities').dataTable({
					"bJQueryUI": false,
					"bAutoWidth": false,
					"bPaginate": false,
					"bSort": false,
					"bFilter" : false,
					"bLengthChange": false,
					"bInfo" : false,
					"aoColumnDefs": [
						{ "bSortable": false, "aTargets": [] }
					],
					"aaSorting": []
				});

				function getMasterRefresh() {

					$.ajax({
						'type': 'GET',
						'url': '/api/master_refresh',
						'async': false,
						'data': {
							'department_id': uni_department_id,
							'company_id': uni_company_id
						},
						'success': function (data) {

							var response = JSON.parse(data);

							console.log(response);

							//Online chat users
							chat_oTable.fnClearTable();

							$.each(response.onlineChats, function(key, value) {
								chat_oTable.fnAddData(value);
							});

							$("#header_online_users_count").html(chat_oTable.fnSettings().fnRecordsTotal());

							@if(Utils::isAdmin(Auth::user()->id))
								//Recent Activities
								recent_oTable.fnClearTable();

								$.each(response.recentActivities, function(key, value) {
									recent_oTable.fnAddData(value);
								});

								$("#header_recent_activities_count").html(recent_oTable.fnSettings().fnRecordsTotal());
							@endif
						},
						'complete':function(){
							setTimeout(getMasterRefresh, uni_interval);
						}
					});

				}

				getMasterRefresh();

			});
		</script>

		@yield('scripts')

		<i id="ajaxLoading" class="fa fa-spinner fa-spin fa-5x"></i>

	</body>
</html>