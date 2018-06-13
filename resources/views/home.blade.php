@extends('pc.layouts.app_page')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">List post approved</div>

                <div class="panel-body">
                    @foreach ($post as $item)
                        <div class="form-group col-md-8 col-md-offset-2">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Title</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                               <a href="{{route('posts.show.user', ['id' => $item->id])}}">{{ $item->title }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
