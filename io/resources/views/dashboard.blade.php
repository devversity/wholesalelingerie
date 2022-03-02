@extends('layout.app-loggedin')
@section('page_title', 'KEVCO API Services')
@section('page_description', 'KEVCO Administration area')

@section('content')

    <h4 class="header-title mt-0 m-b-30">API Services</h4>

    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
            <tr>
                <th>Description</th>
                <th>URL</th>
            </tr>
            </thead>
            <tbody>
            @foreach($web_services as $name => $url)
                <tr>
                    <td>{{$name}}</td>
                    <td><a href="{{$url}}" target="_blank">{{$url}}</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    @push('script-footer')

    @endpush

@endsection
