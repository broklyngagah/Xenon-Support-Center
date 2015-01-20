@foreach($messages as $message)

    @if(\KodeInfo\Utilities\Utils::isBackendUser($message->sender_id))
        <li class="right clearfix">
        <span class="chat-img pull-right">
            @if($message->user->show_avatar)
                <img src="{{$message->user->avatar}}" style="width:50px;" alt="{{{$message->user->name}}}" class="img-circle"/>
            @else
                <img src="http://placehold.it/50/FA6F57/fff&text=ME" alt="{{{$message->user->name}}}" class="img-circle"/>
            @endif
        </span>

            <div class="chat-body clearfix">
                <div class="header">
                    <small class=" text-muted">
                        <span class="glyphicon glyphicon-time"></span>
                        {{\KodeInfo\Utilities\Utils::prettyDate($message->created_at)}}
                    </small>
                    <strong class="pull-right primary-font">{{{$message->user->name}}}</strong>
                </div>
                <p>{{$message->message}}</p>
                @if(isset($message->attachment)&&$message->attachment->has_attachment)
                    <p></p>
                    <div class="well with-padding block-inner">Attachment : <a class="btn btn-primary" target="_blank" href="{{URL::to('/').$message->attachment->attachment_path}}">View attachment</a>
                        <p></p><small>Scan attachment before opening</small></div>
                @endif
            </div>
        </li>
    @else
        <li class="left clearfix">
        <span class="chat-img pull-left">

            @if($message->user->show_avatar)
                <img src="{{$message->user->avatar}}" style="width:50px;" alt="{{{$message->user->name}}}" class="img-circle"/>
            @else
                <img src="http://placehold.it/50/55C1E7/fff&text=U" alt="{{{$message->user->name}}}" class="img-circle"/>
            @endif
        </span>

            <div class="chat-body clearfix">
                <div class="header">
                    <strong class="primary-font">{{{$message->user->name}}}</strong>
                    <small class="pull-right text-muted">
                        <span class="glyphicon glyphicon-time"></span>
                        {{\KodeInfo\Utilities\Utils::prettyDate($message->created_at)}}
                    </small>
                </div>
                <p>{{$message->message}}</p>
                @if(isset($message->attachment)&&$message->attachment->has_attachment)
                    <p></p>
                    <div class="well with-padding block-inner">Attachment : <a class="btn btn-primary" target="_blank" href="{{URL::to('/').$message->attachment->attachment_path}}">View attachment</a>
                        <p></p><small>Scan attachment before opening</small></div>
                @endif
            </div>
        </li>

    @endif

@endforeach