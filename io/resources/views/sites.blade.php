@extends('layout.app-loggedin')
@section('page_title', 'KEVCO API Services')
@section('page_description', 'KEVCO Administration area')

@section('content')

    <h4 class="header-title mt-0 m-b-30">Sites</h4>

    <div class="table-responsive">
        <form action="" method="post">
            @csrf
            <table class="table mb-0">
                <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th>
                        <div class="form-group row m-b-0">
                            <div class="offset-sm-3 col-sm-9 m-t-15">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    Update Site Configurations
                                </button>
                            </div>
                        </div>
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($sites as $site)
                    <tr>
                        <td><a href="{{$site->url}}" target="_blank">{{$site->url}}</a>
                            <br/>
                            @if($site->active == 0)
                                <span class="badge badge-danger">Inactive</span>
                            @elseif ($site->active == 1)
                                <span class="badge badge-success">Active</span>
                            @endif
                        </td>
                        <td>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Brands</label>
                                <div class="col-sm-6">
                                    @foreach($brands as $key => $brand)
                                        @if(!empty($brand))
                                            <div class="checkbox checkbox-pink">
                                                <input id="brand_{{$site->id}}_{{$brand}}" name="brands[{{$site->id}}][]" value="{{$key}}" type="checkbox"
                                                       {{ isset($site_brands[$site->id]) && in_array(strtolower($brand), $site_brands[$site->id]) ? 'checked="checked"' : '' }}
                                                       data-parsley-multiple="brands{{$site->id}}">
                                                <label for="brand_{{$site->id}}_{{$brand}}"> {{ucwords($brand)}} </label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Categories</label>
                                <div class="col-sm-6">
                                    @foreach($categories as $key => $category)
                                        @if(!empty($category))
                                            <div class="checkbox checkbox-pink">
                                                <input id="categories_{{$site->id}}_{{$category}}" name="categories[{{$site->id}}][]" value="{{$key}}" type="checkbox"
                                                       {{ isset($site_categories[$site->id]) && in_array(strtolower($category), $site_categories[$site->id]) ? 'checked="checked"' : '' }}
                                                       data-parsley-multiple="categories{{$site->id}}">
                                                <label for="categories_{{$site->id}}_{{$category}}"> {{ucwords($category)}} </label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </form>
    </div>

    @push('script-footer')

    @endpush

@endsection
