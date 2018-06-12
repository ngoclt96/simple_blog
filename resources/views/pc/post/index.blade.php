@section('title', 'List posts')
@extends('pc.layouts.default')
@section('content')
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>
                    Home >
                    <small>Posts List</small>
                </h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Posts List</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div id="datatable-responsive_wrapper"
                             class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <div class="row">
                                @if(session()->has('success'))
                                    <div class="alert alert-success">
                                        {{ session()->get('success') }}
                                        <button class="close" data-dismiss="alert">×</button>
                                    </div>
                                @elseif(session()->has('warning'))
                                    <div class="alert alert-danger">
                                        {{ session()->get('warning') }}
                                        <button class="close" data-dismiss="alert">×</button>
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                @include('pc.elements.limit_pulldown')
                                <div class="col-sm-8">
                                    <a class="btn btn-info pull-right btn-sm" aria-label="Left Align"
                                       href="{{ route('posts.form') }}">New Post</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable" class="table table-striped table-bordered dataTable no-footer"
                                           role="grid" aria-describedby="datatable_info">
                                        <thead>
                                        @include("pc.elements.table_heading", ['heading' => $searchField, 'checkall' => true])
                                        </thead>
                                        <tbody>
                                        @if(!$pages->count())
                                            <tr align="center">
                                                <td colspan="11">{{ "No data avaiable in table" }}</td>
                                            </tr>
                                        @else
                                            @foreach ($pages as $post)
                                                <tr role="row" class="even">
                                                    <td class="text-center"><input type="checkbox"
                                                                                   class="form-control check-el"
                                                                                   name="{{ $post->id }}"></td>
                                                    @foreach($searchField as $key => $value)
                                                        @php $name = $post->$key @endphp

                                                        <td class="text-center">
                                                            @if($key == "approve")
                                                            @include('pc.elements.approve', ['item' => $post])
                                                            @else

                                                            {{ $name }}

                                                            @endif
                                                        </td>
                                                    @endforeach
                                                    <td class="text-center">
                                                        @include('pc.elements.action', ['item' => $post])
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                @include('pc.elements.pagination', ['pages' => $pages])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection