@if (Auth::user()->is_saving_fav($micropost->id))
        {!! Form::open(['route' => ['unsave.favorite', $micropost->id], 'method' => 'delete','style'=>'display:inline-block']) !!}
            {!! Form::submit('★Favorite', ['class' => 'btn btn-primary btn-xs']) !!}
        {!! Form::close() !!}
@else
        {!! Form::open(['route' => ['save.favorite', $micropost->id],'style'=>'display:inline-block']) !!}
            {!! Form::submit('☆Unfavorite', ['class' => 'btn btn-success btn-xs']) !!}
        {!! Form::close() !!}
@endif