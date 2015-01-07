@foreach($messages as $message)

    @if(\KodeInfo\Utilities\Utils::isBackendUser($message->sender_id))
        <div class="message reversed">
            <strong style="margin-right:55px;">{{{$message->user->name}}}</strong>
            <a class="message-img" href="#"><img src="{{$message->user->avatar}}" alt="{{{$message->user->name}}}"></a>
            <div class="message-body">{{{$message->message}}}
                <span class="attribution">{{\KodeInfo\Utilities\Utils::prettyDate($message->created_at)}}</span>
            </div>
        </div>
    @else
        <div class="message">
            <strong style="margin-left:55px;">{{{$message->user->name}}}</strong>
            <a class="message-img" href="#"><img src="{{$message->user->avatar}}" alt="{{{$message->user->name}}}"></a>
            <div class="message-body">{{{$message->message}}}
                <span class="attribution">{{\KodeInfo\Utilities\Utils::prettyDate($message->created_at)}}</span>
            </div>
        </div>
    @endif

@endforeach