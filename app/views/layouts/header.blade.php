<!-- Navbar -->
<div class="navbar navbar-inverse" role="navigation">

	<div class="navbar-header">
		<a class="navbar-brand" href="#"><img src="/assets/images/logo.png" style="width:160px;" alt="Xenon"></a><a class="sidebar-toggle"><i class="icon-menu2"></i></a>
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-icons">
			<span class="sr-only">Toggle navbar</span><i class="icon-grid3"></i>
		</button>
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar">
			<span class="sr-only">Toggle navigation</span><i class="icon-paragraph-justify2"></i>
		</button>
	</div>

	<ul class="nav navbar-nav navbar-right collapse" id="navbar-icons">
		<div class="nav navbar-nav navbar-left collapse" id="navbar-text">
			@if(Auth::user()->is_online==0)
				<p class="navbar-text"><i class="icon-user"></i> You are offline <a href="/api/change_status/1" class="navbar-link">go online</a></p>
			@else
				<p class="navbar-text"><i class="icon-user"></i> You are online <a href="/api/change_status/0" class="navbar-link">go offline</a></p>
			@endif
		</div>

		@if(Permissions::hasPermission('tickets.create'))
		<li class="dropdown">
			<a href="/tickets/create" ><i class="icon-ticket"></i> New Ticket</a>
		</li>
		@endif

		@if(Permissions::hasPermission('operators.create'))
		<li class="dropdown">
			<a href="/operators/create" ><i class="icon-user4"></i> New Operator</a>
		</li>
		@endif

		@if(Permissions::hasPermission('departments.create'))
		<li class="dropdown">
			<a href="/departments/create" ><i class="icon-users"></i> New Department</a>
		</li>
		@endif

		<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="icon-envelop"></i><span id="header_online_users_count" class="label label-info">6</span></a>
			<div class="popup dropdown-menu dropdown-menu-right">
				<div class="popup-header"><span>Online Chats</span><a href="/conversations/all" class="pull-right"><i class="icon-new-tab"></i></a></div>
				<table id="header_online_users" class="table table-hover">
					<thead>
					<tr>
						<th>Name</th>
						<th>Email</th>
						<th class="text-center">Action</th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td><span class="status status-success item-before"></span> Imran Iqbal</td>
						<td><span class="text-smaller text-semibold">shellprog@gmail.com</span></td>
						<td class="text-center"><a href="" class="btn btn-success">Accept</a></td>
					</tr>
					<tr>
						<td><span class="status status-success item-before"></span> Imran Iqbal</td>
						<td><span class="text-smaller text-semibold">shellprog@gmail.com</span></td>
						<td class="text-center"><a href="" class="btn btn-success">Accept</a></td>
					</tr>
					</tbody>
				</table>
			</div>
		</li>

		<li class="user dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown"><img src="{{Auth::user()->avatar}}" alt=""><span>{{{Auth::user()->name}}}</span><i class="caret"></i></a>
			<ul class="dropdown-menu dropdown-menu-right icons-right">
				<li>
					<a href="/profile"><i class="icon-user"></i> Profile</a>
				</li>
				<li>
					<a href="/change_password"><i class="icon-lock"></i> Change Password</a>
				</li>
				@if(Auth::user()->is_online==0)
					<li>
						<a href="/api/change_status"><i class="icon-bubble4"></i> Go Online</a>
					</li>
				@else
					<li>
						<a href="/api/change_status"><i class="icon-bubble4"></i> Go Offline</a>
					</li>
				@endif
				@if(Permissions::hasPermission('settings.all'))
				<li>
					<a href="/settings/all"><i class="icon-cog"></i> Settings</a>
				</li>
				@endif
				<li>
					<a href="/logout"><i class="icon-exit"></i> Logout</a>
				</li>
			</ul>
		</li>
	</ul>
</div>
<!-- /navbar -->