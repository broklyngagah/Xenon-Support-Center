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
		{{HTML::script("/assets/js/plugins/charts/sparkline.min.js")}}
		{{HTML::script("/assets/js/plugins/forms/uniform.min.js")}}
		{{HTML::script("/assets/js/plugins/forms/select2.min.js")}}
		{{HTML::script("/assets/js/plugins/forms/inputmask.js")}}
		{{HTML::script("/assets/js/plugins/forms/autosize.js")}}
		{{HTML::script("/assets/js/plugins/forms/inputlimit.min.js")}}
		{{HTML::script("/assets/js/plugins/forms/listbox.js")}}
		{{HTML::script("/assets/js/plugins/forms/multiselect.js")}}
		{{HTML::script("/assets/js/plugins/forms/validate.min.js")}}

		{{HTML::script("/assets/js/plugins/forms/tags.min.js")}}
		{{HTML::script("/assets/js/plugins/forms/switch.min.js")}}
		{{HTML::script("/assets/js/plugins/forms/uploader/plupload.full.min.js")}}
		{{HTML::script("/assets/js/plugins/forms/uploader/plupload.queue.min.js")}}
		{{HTML::script("/assets/js/plugins/forms/wysihtml5/wysihtml5.min.js")}}
		{{HTML::script("/assets/js/plugins/forms/wysihtml5/toolbar.js")}}
		{{HTML::script("/assets/js/plugins/interface/daterangepicker.js")}}
		{{HTML::script("/assets/js/plugins/interface/fancybox.min.js")}}
		{{HTML::script("/assets/js/plugins/interface/moment.js")}}
		{{HTML::script("/assets/js/plugins/interface/jgrowl.min.js")}}
		{{HTML::script("/assets/js/plugins/interface/datatables.min.js")}}
		{{HTML::script("/assets/js/plugins/interface/tabletools.min.js")}}
		{{HTML::script("/assets/js/fnReloadAjax.js")}}

		{{HTML::script("/assets/js/plugins/interface/colorpicker.js")}}
		{{HTML::script("/assets/js/plugins/interface/fullcalendar.min.js")}}
		{{HTML::script("/assets/js/plugins/interface/timepicker.min.js")}}
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

			});
		</script>

		@yield('scripts')

		<i id="ajaxLoading" class="fa fa-spinner fa-spin fa-5x"></i>

	</body>
</html>