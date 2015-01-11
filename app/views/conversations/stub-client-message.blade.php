@foreach($messages as $message)

    @if(\KodeInfo\Utilities\Utils::isBackendUser($message->sender_id))
        <li class="right clearfix">
        <span class="chat-img pull-right">
            <img src="http://placehold.it/50/FA6F57/fff&text=ME" alt="{{{$message->user->name}}}" class="img-circle"/>
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
            </div>
        </li>
    @else
        <li class="left clearfix">
        <span class="chat-img pull-left">
            <img src="http://placehold.it/50/55C1E7/fff&text=U" alt="{{{$message->user->name}}}" class="img-circle"/>
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
            </div>
        </li>

    @endif

@endforeach