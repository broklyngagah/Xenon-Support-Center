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
@foreach($online_users as $online)
    <tr>
        <td>{{$online->id}}</td>
        <td>{{$online->user->name}}</td>
        <td>{{$online->user->email}}</td>
        <td>{{isset($online->operator)?$online->operator->name:"<label class='label label-warning'>NONE</label>"}}</td>
        <td>{{\KodeInfo\Utilities\Utils::prettyDate($online->requested_on,true)}}</td>
        <td>{{\KodeInfo\Utilities\Utils::prettyDate($online->started_on,true)}}</td>
        <td>{{$online->locked_by_operator==1?"<label class='label label-warning'>Yes</label>":"<label class='label label-primary'>No</label>"}}</td>

        @if(!isset($online->operator))
            <td><a href="/conversations/accept/{{$online->thread_id}}" class="btn btn-success btn-sm"> <i class="icon-checkmark4"></i> Accept </a></td>
        @endif

        @if(isset($online->operator)&&$online->operator->id==Auth::user()->id)
            <td><a href="/conversations/accept/{{$online->thread_id}}" class="btn btn-success btn-sm"> <i class="icon-checkmark4"></i> Reply </a></td>
        @endif

        @if(isset($online->operator)&&$online->operator->id!=Auth::user()->id)
            <td><a disabled class="btn btn-success btn-sm"> <i class="icon-lock3"></i> Accept </a></td>
        @endif

        <td><a href="/conversations/transfer/{{$online->id}}" class="btn btn-warning btn-sm"> <i class="icon-share3"></i> Transfer </a></td>


        <td><a href="/conversations/close/{{$online->thread_id}}" class="btn btn-danger btn-sm"> <i class="icon-lock3"></i> Close </a></td>
    </tr>
@endforeach
</tbody>
