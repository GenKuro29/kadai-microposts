@if (count($users) > 0)
<ul class="midea-list">

@foreach($users as $user)
    <li class="media">
        <div class"media-left">
            <img class="media-object img-rounded" scr="{{ Gravatar::src($user->email, 50) }}" alt="">
        </div>
        <div class ="media-body">
            <dif>
                {{ $user->name }}
            </dif>
            <div>
                <p>{!! link_to_route('users.show', 'View profile', ['id' => $user->id]) !!}</p>
            </div>
            
        </div>
    </li>
@endforeach
</ul>
{!! $users->render() !!}
@endif