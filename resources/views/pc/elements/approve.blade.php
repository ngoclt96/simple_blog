@if($approve  == 1)
    {!! Form::model($approve, ['url' => route('posts.approve'), 'class' => 'form-horizontal']) !!}
    <button type="submit" class="btn btn-primary btn-xs">Approve</button>
    {!! Form::close() !!}
@endif
@if($approve == 0)
    {!! Form::model($approve, ['url' => route('posts.approve'), 'class' => 'form-horizontal']) !!}
    <button type="submit" class="btn btn-primary btn-xs">None_approve</button>
    {!! Form::close() !!}
@endif
