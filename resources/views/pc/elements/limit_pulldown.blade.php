@php
    $limits = [ 5, 10, 15, 20 ];
    $current = request()->input('limit');
@endphp
<div class="col-sm-4">
    <div class="dataTables_length" id="datatable_length">
        <label>
            <select id="limit-page" name="limit" aria-controls="datatable" class="form-control input-sm">
                <option value="" {{ is_null($current) ? 'selected' : '' }}>...</option>
                @foreach( $limits as $limit)
                    <option value="{{ $limit }}" {{ $limit == $current ? 'selected' : '' }}>{{ $limit }}</option>
                @endforeach
            </select>
        </label>
    </div>
</div>