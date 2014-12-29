@extends('layouts.master')

@section('content')

<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>Company <small>Control panel.</small></h3>
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
			All Companies
		</li>
	</ul>
</div>
<!-- /breadcrumbs line -->

@include('layouts.notify')
<div class="panel panel-default">

	<div style="margin:5px;" class="alert alert-info">
		If you delete company then all company departments , operators , customers , conversations and tickets will be deleted
	</div>

	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-user4"></i> All Companies</h6>
		<div class="table-controls pull-right">
			<a href="/companies/create" class="btn btn-default btn-icon btn-xs tip" title="" data-original-title="Add Company"><i class="icon-plus"></i></a>
		</div>
	</div>
	<div class="datatable-tools">
		<table id="company_list" class="table">
			<thead>
				<tr>
					<th>ID</th>
					<th>Logo</th>
					<th>Name</th>
					<th>Description</th>
					<th>Domain</th>
					<th>Operators</th>
					<th>Contacts</th>
					<th>Show Livechat Code</th>
					<th>Edit</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
				@foreach($companies as $company)
				<tr>
					<td>{{$company->id}}</td>
					<td><img class="img-circle" style="width:80px;" src="{{$company->logo}}"/></td>
					<td>{{$company->name}}</td>
					<td>{{$company->description}}</td>
					<td>{{$company->domain}}</td>
					<td><a class="btn btn-primary" href="/companies/operators/{{$company->id}}">View Operators</a></td>
					<td><a class="btn btn-primary" href="/companies/customers/{{$company->id}}">View Customers</a></td>
					<td><button class="btn btn-info" data-toggle="modal" data-id="{{$company->id}}" id="code_modal" role="button" href="#livechat_modal" id="livechat_code">Show Code</button></td>
					<td><a href="/companies/update/{{$company->id}}" class="btn btn-success btn-sm edit_operator"> <i class="icon-pencil"></i> Edit </a></td>
					<td><a href="/companies/delete/{{$company->id}}" class="btn btn-danger btn-sm"> <i class="icon-remove3"></i> Delete </a></td>
				</tr>
				@endforeach
			</tbody>

		</table>
	</div>
</div>

<div id="livechat_modal" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Copy and Paste</h4>
			</div>
			<div class="modal-body with-padding">
				<p>Copy and paste below code . Livechat will only work on <strong><span id="livechat_domain"></span></strong></p>
				<p><code id="livechat_code">&lt;link href=&quot;{{URL::to('/')}}/assets/xenon_chat/style.css&quot; rel=&quot;stylesheet&quot; type=&quot;text/css&quot;&gt;<br/>&lt;script src=&quot;{{URL::to('/')}}/assets/xenon_chat/script.js&quot; type=&quot;text/javascript&quot;&gt;&lt;/script&gt;<br/><br/>&lt;script type=&quot;text/javascript&quot;&gt;<br/><br/>$(document).ready(function () {<br/>$(&quot;#xenon-chat-widget&quot;).XENON_Initialize({company: 1, domain: &quot;{{URL::to('/')}}&quot;});<br/>});<br/><br/>&lt;/script&gt;</code></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
			</div>
		</div>
	</div>
</div>

@stop

@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){

		$('#code_modal').on('click',function(){

			$.ajax({
				'type': 'GET',
				'url': '/api/get_code/'+$(this).data('id'),
				'success': function (data) {

					var response = JSON.parse(data);

					console.log(response.domain);
					$('#livechat_code').html(response.code);
					$('#livechat_domain').html(response.domain);
				}
			});

		});
	});
</script>
@stop