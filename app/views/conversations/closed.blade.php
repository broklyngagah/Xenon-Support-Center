@extends('layouts.master')

@section('content')

<div class="page-header">
	<div class="page-title">
		<h3>Closed Conversations <small>Control panel.</small></h3>
	</div>
</div>

@include('layouts.notify')

<div class="panel panel-default">
	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-envelop"></i> Closed Conversations</h6>
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
					<th>Ended On</th>
					<th>Read</th>
					<th>Delete</th>
				</tr>
				</thead>
				<tbody>
				@foreach($closed_conversations as $closed_conversation)
					<tr>
						<td>{{$closed_conversation->id}}</td>
						<td>{{$closed_conversation->user->name}}</td>
						<td>{{$closed_conversation->user->email}}</td>
						<td>{{isset($closed_conversation->operator)?$closed_conversation->operator->name:"<label class='label label-danger'>NONE</label>"}}</td>
						<td>{{\KodeInfo\Utilities\Utils::prettyDate($closed_conversation->requested_on,true)}}</td>
						<td>{{\KodeInfo\Utilities\Utils::prettyDate($closed_conversation->started_on,true)}}</td>
						<td>{{\KodeInfo\Utilities\Utils::prettyDate($closed_conversation->ended_on,true)}}</td>
						<td><a href="/conversations/read/{{$closed_conversation->thread_id}}" class="btn btn-success btn-sm"> <i class="icon-pencil"></i> Read </a></td>
						<td><a href="/conversations/delete/{{$closed_conversation->thread_id}}" class="btn btn-danger btn-sm"> <i class="icon-remove3"></i> Delete </a></td>
					</tr>
				@endforeach
				</tbody>

			</table>
		</div>
	</div>

</div>

@stop