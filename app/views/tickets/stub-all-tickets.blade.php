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
@foreach($tickets as $ticket)
    <tr>
        <td>{{$ticket->id}}</td>
        <td>{{{isset($ticket->company)?$ticket->company->name:"NONE"}}}</td>
        <td>{{{isset($ticket->department)?$ticket->department->name:"NONE"}}}</td>
        <td>{{{isset($ticket->customer)?$ticket->customer->name:"NONE"}}}</td>
        <td>{{{isset($ticket->customer)?$ticket->customer->email:"NONE"}}}</td>
        <td>{{{$ticket->subject}}}</td>
        <td>{{{isset($ticket->operator)?$ticket->operator->name:"NONE"}}}</td>

        @if($ticket->priority==Tickets::PRIORITY_LOW)
            <td><label class="label label-primary">Low</label></td>
        @endif
        @if($ticket->priority==Tickets::PRIORITY_MEDIUM)
            <td><label class="label label-primary">Medium</label></td>
        @endif
        @if($ticket->priority==Tickets::PRIORITY_HIGH)
            <td><label class="label label-warning">High</label></td>
        @endif
        @if($ticket->priority==Tickets::PRIORITY_URGENT)
            <td><label class="label label-danger">Urgent</label></td>
        @endif

        @if($ticket->status==Tickets::TICKET_NEW)
            <td><label class="label label-warning">New</label></td>
        @endif
        @if($ticket->status==Tickets::TICKET_PENDING)
            <td><label class="label label-primary">Pending</label></td>
        @endif
        @if($ticket->status==Tickets::TICKET_RESOLVED)
            <td><label class="label label-success">Resolved</label></td>
        @endif

        @if(!isset($ticket->operator))
            <td><a href="/tickets/read/{{$ticket->thread_id}}" class="btn btn-success btn-sm"> <i class="icon-checkmark4"></i> Accept </a></td>
        @endif

        @if(isset($ticket->operator)&&$ticket->operator->id==Auth::user()->id)
            <td><a href="/tickets/read/{{$ticket->thread_id}}" class="btn btn-success btn-sm"> <i class="icon-checkmark4"></i> Reply </a></td>
        @endif

        @if(isset($ticket->operator)&&$ticket->operator->id!=Auth::user()->id)
            <td><a disabled class="btn btn-success btn-sm"> <i class="icon-lock3"></i> Accept </a></td>
        @endif

        <td><a href="/tickets/transfer/{{$ticket->id}}" class="btn btn-warning btn-sm"> <i class="icon-share3"></i> Transfer </a></td>
        <td><a href="/tickets/delete/{{$ticket->thread_id}}" class="btn btn-danger btn-sm"> <i class="icon-remove3"></i> Delete </a></td>
    </tr>
@endforeach
</tbody>