@php
    $edit = null;
    $delete = null;
    $show = null;
    if (isset($item['edit']) && isset($item['delete']) && isset($item['show'])) {
        $edit = $item['edit'];
        $delete = $item['delete'];
        $show = $item['show'];
        $key = $item['arg']['0'];
        $val = $item['arg']['1'];
    } else {
        $module = request()->segment(2);
        $edit = route($module. '.edit', $item->id);
        $delete = route($module . '.delete');
        $show = route($module . '.show', $item->id);
        $key = 'id';
        $val = $item->id;
    }
@endphp
<a href="{{ $edit }}" class="btn btn-primary btn-xs">Edit</a>
<button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target=".bs-example-modal-sm1-{{ $val }}">Delete</button>
<button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target=".bs-example-modal-sm-{{ $val }}">Show</button>
<div class="modal fade bs-example-modal-sm1-{{ $val }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel2">@lang('delete_confirm')?</h4>
            </div>
            <div class="modal-body">
                <h4>@lang('Are you sure want to delete this record all your data will be lost')?</h4>
            </div>
            <div class="modal-footer">
                {!! Form::model(null, ['url' => $delete, 'method' => 'POST']) !!}
                {!! Form::hidden($key, $val) !!}
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Ok</button>
                {!! Form::close() !!}
            </div>

        </div>
    </div>
</div>
<div class="modal fade bs-example-modal-sm-{{ $val }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel2">@lang('Show')?</h4>
            </div>
            <div class="modal-body">
                <h4>@lang('Are you want to see the post')?</h4>
            </div>
            <div class="modal-footer">
                {!! Form::model(null, ['url' => $show, 'method' => 'GET']) !!}
                {!! Form::hidden($key, $val) !!}
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Ok</button>
                {!! Form::close() !!}
            </div>

        </div>
    </div>
</div>