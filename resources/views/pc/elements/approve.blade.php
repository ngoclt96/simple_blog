@if($item->approve  == 1)
    {!! Form::model($item->approve, ['url' => route('posts.approve'), 'class' => 'form-horizontal']) !!}
    {!! Form::hidden('id', $item->id) !!}
    <button type="submit" class="btn btn-primary btn-xs">Approve</button>
    {!! Form::close() !!}
@endif
@if($item->approve == 0)
    {!! Form::model($item->approve, ['url' => route('posts.approve'), 'class' => 'form-horizontal']) !!}
    {!! Form::hidden('id', $item->id) !!}
    <button type="submit" class="btn btn-primary btn-xs">None_approve</button>
    {!! Form::close() !!}
@endif
