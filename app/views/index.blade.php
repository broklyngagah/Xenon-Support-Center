@extends('layouts.master')

@section('content')
<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>XENON Support <small>Welcome {{Auth::user()->name}}.</small></h3>
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

<!-- Default info blocks -->
<ul class="info-blocks">
	<li class="bg-primary">
		<div class="top-info">
			<a href="#">Add new post</a><small>post management</small>
		</div>
		<a href="#"><i class="icon-pencil"></i></a><span class="bottom-info bg-danger">12 drafts in progress</span>
	</li>
	<li class="bg-success">
		<div class="top-info">
			<a href="#">Site parameters</a><small>layout settings</small>
		</div>
		<a href="#"><i class="icon-cogs"></i></a><span class="bottom-info bg-primary">No updates</span>
	</li>
	<li class="bg-danger">
		<div class="top-info">
			<a href="#">Site statistics</a><small>visits, users, orders stats</small>
		</div>
		<a href="#"><i class="icon-stats2"></i></a><span class="bottom-info bg-primary">3 new updates</span>
	</li>
	<li class="bg-info">
		<div class="top-info">
			<a href="#">My messages</a><small>messages history</small>
		</div>
		<a href="#"><i class="icon-bubbles3"></i></a><span class="bottom-info bg-primary">24 new messages</span>
	</li>
	<li class="bg-warning">
		<div class="top-info">
			<a href="#">Orders history</a><small>purchases statistics</small>
		</div>
		<a href="#"><i class="icon-cart2"></i></a><span class="bottom-info bg-primary">17 new orders</span>
	</li>
	<li class="bg-primary">
		<div class="top-info">
			<a href="#">Invoices stats</a><small>invoices archive</small>
		</div>
		<a href="#"><i class="icon-coin"></i></a><span class="bottom-info bg-danger">9 new invoices</span>
	</li>
</ul>
<!-- /default info blocks -->


@stop