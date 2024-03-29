<ul class="media-list">
@foreach ($microposts as $micropost)
    <?php $user = $micropost->user; ?>
    <li class="media">
        <div class="media-left">
            <img class="media-object img-rounded" src="{{ Gravatar::src($user->email, 50) }}" alt="">
        </div>
        <div class="media-body">
            <div>
                {!! link_to_route('users.show', $user->name, ['id' => $user->id]) !!} <span class="text-muted">posted at {{ $micropost->created_at }}</span>
            </div>
            <div>
                <p>{!! nl2br(e($micropost->content)) !!}</p>
            </div>
            <div class="btn-toolbar">
                  <div class="btn-group">
                    @if (Auth::user()->has_added_favorite($micropost->id))
                        {!! Form::open(['route' => ['user.remove_favorite', $micropost->id], 'method' => 'delete']) !!}
                            {!! Form::submit('★', ['class' => "btn btn-success btn-xs"]) !!}
                        {!! Form::close() !!}
                    @else
                        {!! Form::open(['route' => ['user.add_favorite', $micropost->id]]) !!}
                            {!! Form::submit('☆', ['class' => "btn btn-default btn-xs"]) !!}
                        {!! Form::close() !!}
                    @endif
                </div>
                <div class="btn-group">
                    @if (Auth::user()->id == $micropost->user_id)
                        {!! Form::open(['route' => ['microposts.destroy', $micropost->id], 'method' => 'delete']) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
                        {!! Form::close() !!}
                    @endif
                </div>
            </div>
        </div>
    </li>
@endforeach
</ul>
{!! $microposts->render() !!}