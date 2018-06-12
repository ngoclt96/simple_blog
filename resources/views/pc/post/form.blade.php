@section('title', 'Form post')
@extends('pc.layouts.default')
@section('content')
    <div class="">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <a href="/">Home</a> > <a href="{{ route('posts.index') }}" class="current"> Post</a>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        {!! Form::model($post, ['url' => route('posts.confirm'), 'class' => 'form-horizontal']) !!}
                        {!! Form::hidden('id', $post->id) !!}

                        <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Title<span class="textred">(*)</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::text('title', null, ['class' => 'form-control col-md-7 col-xs-12', 'required' => false, 'value' => old('title')]) !!}
                                <span class="error"> {{ $errors->first('title') ?? '' }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Content<span class="textred">(*)</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::textarea('content', null, ['class' => 'form-control col-md-7 col-xs-12', 'required' => false, 'value' => old('content')]) !!}
                                <span class="error"> {{ $errors->first('content') ?? '' }}</span>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="form-group text-center">
                            <a href="{{ route('posts.index') }}" class="buttonFinish  btn btn-default">Back</a>
                            <button type="submit" class="buttonPrevious  btn btn-primary">Submit</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection