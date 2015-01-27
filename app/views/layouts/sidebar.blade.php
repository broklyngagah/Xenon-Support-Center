<!-- Sidebar -->
<div class="sidebar collapse">
    <div class="sidebar-content">
        <!-- User dropdown -->
        <div class="user-menu dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img style="width:50%;margin-left:10%;" src="{{Auth::user()->avatar}}" alt="{{{Auth::user()->name}}}">
                <div class="user-info" style="width:100%;">
                    {{{Auth::user()->name}}} <span>{{{Str::limit(Auth::user()->bio,80,'...')}}}</span>
                </div>
            </a>

            <div class="popup dropdown-menu dropdown-menu-right">
                <div class="thumbnail">
                    <div class="thumb"><img alt="" src="{{Auth::user()->avatar}}">

                        <div class="thumb-options">
                            <span><a href="/profile" class="btn btn-icon btn-success"><i class="icon-pencil"></i></a></span>
                        </div>
                    </div>
                    <div class="caption text-center">
                        <h6>{{{Auth::user()->name}}}
                            <small>{{{Auth::user()->bio}}}</small>
                        </h6>
                    </div>
                </div>

            </div>
        </div>
        <!-- /user dropdown -->
        <!-- Main navigation -->
        <ul class="navigation">

            @if(Permissions::hasPermission('departments.create'))
            <li>
                <a class="btn btn-block btn-success" href="/departments/create"> <i class="icon-cash"></i> <span>Create New Department </span>
                </a>
            </li>
            @endif

            @if(\KodeInfo\Utilities\Utils::isBackendUser(Auth::user()->id)))
            <li>
                <a href="/"> <i class="icon-screen2"></i> <span>Dashboard </span> </a>
            </li>
            @endif

            @if(Utils::isAdmin(Auth::user()->id))
            <li {{(isset(Request::segments()[0])&&Request::segments()[0]=='activities')?"class='active'":""}}>
                <a href="/activities/all"> <i class="icon-clipboard"></i> <span>Activities</span> </a>
            </li>
            @endif

            @if(\KodeInfo\Utilities\Utils::isCustomer(Auth::user()->id))
                <li>
                   <a href="/tickets/customer/create"> <i class="icon-ticket"></i> <span>Create New Ticket </span> </a>
                </li>
                <li>
                    <a href="/tickets/customer/all"> <i class="icon-ticket"></i> <span>All Tickets </span> </a>
                </li>
                <li>
                    <a href="/tickets/customer/pending"> <i class="icon-ticket"></i> <span>Pending Tickets </span> </a>
                </li>
                <li>
                    <a href="/tickets/customer/resolved"> <i class="icon-ticket"></i> <span>Resolved Tickets </span> </a>
                </li>
            @endif

            @if(Permissions::hasAnyConversionsPermissions())
            <li {{(isset(Request::segments()[0])&&Request::segments()[0]=='conversations')?"class='active'":""}}>
                <a href="" class="expand"><span>Conversations</span> <i class="icon-envelop"></i></a>
                <ul>
                    @if(Permissions::hasPermission('conversations.accept'))
                    <li>
                        <a href="/conversations/all">Online Conversations</a>
                    </li>
                    @endif

                    @if(Permissions::hasPermission('conversations.closed'))
                    <li>
                        <a href="/conversations/closed">Closed Conversations</a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            @if(Permissions::hasAnyCannedPermissions())
            <li {{(isset(Request::segments()[0])&&Request::segments()[0]=='canned_messages')?"class='active'":""}}>
                <a href="" class="expand"><span>Canned Messages</span> <i class="icon-drawer2"></i></a>
                <ul>
                    @if(Permissions::hasPermission('canned_messages.all')||Permissions::hasPermission('canned_messages.edit'))
                    <li>
                        <a href="/canned_messages/all">All Canned Messages</a>
                    </li>
                    @endif

                    @if(Permissions::hasPermission('canned_messages.create'))
                    <li>
                        <a href="/canned_messages/create">Create Canned Message</a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            @if(Permissions::hasAnyOperatorsPermissions())
            <li {{(isset(Request::segments()[0])&&Request::segments()[0]=='operators')?"class='active'":""}}>
                <a href="" class="expand"><span>Operators</span> <i class="icon-user4"></i></a>
                <ul>
                    @if(Permissions::hasPermission('operators.all'))
                        <li>
                            <a href="/operators/online">Online Operators</a>
                        </li>
                    @endif

                    @if(Permissions::hasPermission('operators.all'))
                        <li>
                            <a href="/operators/all">All Operators</a>
                        </li>
                    @endif

                    @if(Permissions::hasPermission('operators.create'))
                        <li>
                            <a href="/operators/create">Create New Operator</a>
                        </li>
                    @endif
                </ul>
            </li>
            @endif

            @if(Permissions::hasAnyDepartmentsPermissions()||Permissions::hasAnyDepartmentAdminsPermissions())
            <li {{(isset(Request::segments()[0])&&Request::segments()[0]=='departments')?"class='active'":""}}>
                <a href="" class="expand"><span>Departments</span> <i class="icon-users"></i></a>
                <ul>
                    @if(Permissions::hasAnyDepartmentsPermissions())
                    <li>
                        <a href="/departments/all">All Departments</a>
                    </li>
                    @endif
                    @if(Permissions::hasPermission('departments.create'))
                    <li>
                        <a href="/departments/create">Create New Department</a>
                    </li>
                    @endif
                    @if(Permissions::hasPermission('departments_admins.create'))
                    <li>
                        <a href="/departments/admins/create">Create New Department Admin</a>
                    </li>
                    @endif
                    @if(Permissions::hasAnyDepartmentAdminsPermissions())
                    <li>
                        <a href="/departments/admins/all">All Department Admin</a>
                    </li>
                    @endif
                </ul>
            </li>

            @endif

            @if(Permissions::hasAnyMailchimpPermissions())
            <li {{(isset(Request::segments()[0])&&Request::segments()[0]=='templates')?"class='active'":""}}>
                <a href="" class="expand"><span>Mailchimp Templates</span> <i class="icon-profile"></i></a>
                <ul>
                    @if(Permissions::hasPermission('mailchimp.all'))
                    <li>
                        <a href="/templates/all">All Templates</a>
                    </li>
                    @endif
                    @if(Permissions::hasPermission('mailchimp.pair_email')||Permissions::hasPermission('mailchimp.delete'))
                    <li>
                        <a href="/templates/pair/all">Pair Email to Template</a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            @if(\KodeInfo\Utilities\Utils::isAdmin(Auth::user()->id))
            <li {{(isset(Request::segments()[0])&&Request::segments()[0]=='permissions')?"class='active'":""}}>
                <a href="/permissions/all"> <i class="icon-key"></i> <span>Permissions</span> </a>
            </li>
            @endif

            @if(Permissions::hasAnyTicketsPermissions())
            <li {{(isset(Request::segments()[0])&&Request::segments()[0]=='tickets')?"class='active'":""}}>
                <a href="" class="expand"><span>Tickets</span> <i class="icon-ticket"></i></a>
                <ul>
                    @if(Permissions::hasPermission('tickets.create')||Permissions::hasPermission('tickets.edit')||Permissions::hasPermission('tickets.delete'))
                    <li>
                        <a href="/tickets/create">Create Ticket</a>
                    </li>
                    @endif
                    @if(Permissions::hasPermission('tickets.all'))
                    <li>
                        <a href="/tickets/all">All Tickets</a>
                    </li>
                    <li>
                        <a href="/tickets/pending">Pending Tickets</a>
                    </li>
                    <li>
                        <a href="/tickets/resolved">Resolved Tickets</a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            @if(Permissions::hasAnyCustomersPermissions())
            <li {{(isset(Request::segments()[0])&&Request::segments()[0]=='customers')?"class='active'":""}}>
                <a href="/customers/all"> <i class="icon-users2"></i> <span>Customers</span> </a>
            </li>
            @endif

            @if(Permissions::hasAnyCompaniesPermissions())
            <li {{(isset(Request::segments()[0])&&Request::segments()[0]=='companies')?"class='active'":""}}>
                <a href="/companies/all"> <i class="icon-office"></i> <span>Companies</span> </a>
            </li>
            @endif

                @if(Utils::isAdmin(Auth::user()->id))
                    <li {{(isset(Request::segments()[0])&&Request::segments()[0]=='users')?"class='active'":""}}>
                        <a href="/users/all"> <i class="icon-users"></i> <span>Users</span> </a>
                    </li>
                    @endif

            <!--
            <li>
                <a href="" class="expand"><span>Translations</span> <i class="icon-transmission"></i></a>
                <ul>
                    <li>
                        <a href="/translations/all">All Languages</a>
                    </li>
                    <li>
                        <a href="/translations/create_language">New Language</a>
                    </li>
                    <li>
                        <a href="/translations/create">New Translation</a>
                    </li>
                </ul>
            </li>
            -->

            @if(Permissions::hasAnyBlockingPermissions())
            <li {{(isset(Request::segments()[0])&&Request::segments()[0]=='blocking')?"class='active'":""}}>
                <a href="" class="expand"><span>IP Blocking</span> <i class="icon-lock"></i></a>
                <ul>
                    @if(Permissions::hasPermission('blocking.all')||Permissions::hasPermission('blocking.delete'))
                    <li>
                        <a href="/blocking/all">All Blocked IP's</a>
                    </li>
                    @endif
                    @if(Permissions::hasPermission('blocking.block'))
                    <li>
                        <a href="/blocking/create">Block IP</a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            @if(Permissions::hasPermission('settings.all'))
            <li {{(isset(Request::segments()[0])&&Request::segments()[0]=='settings')?"class='active'":""}}>
                <a href="/settings/all"> <i class="icon-cogs"></i> <span>Settings</span> </a>
            </li>
            @endif

        </ul>
        <!-- /main navigation -->
    </div>
</div>
<!-- /sidebar -->