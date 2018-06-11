@section('title', 'Form confirm post')
@extends('pc.layouts.default')
@section('content')
    <div class="">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <a href="/">Home</a> / <a href="{{ route('posts.index') }}" class="current">Posts</a>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="form-group col-md-8 col-md-offset-2">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">User</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                               {{ $post->name }}
                            </div>
                        </div>
                        <div class="form-group col-md-8 col-md-offset-2">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Title</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {{ $post->title }}
                            </div>
                        </div>
                        <div class="form-group col-md-8 col-md-offset-2">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Created_at</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {{ $post->created_at }}
                            </div>
                        </div>
                        <div class="form-group col-md-8 col-md-offset-2">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Updated_at</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {{ $post->updated_at }}
                            </div>
                        </div>
                        <div class="form-group col-md-8 col-md-offset-2">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Content</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {{ $post->content }}
                            </div>
                        </div>
                        <div class="ln_solid col-md-12"></div>
                        <div class="form-group col-md-12 text-center">
                                <a href="{{ route('posts.index') }}" class="buttonFinish  btn btn-default">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection