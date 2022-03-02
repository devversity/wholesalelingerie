@extends('layout.app-loggedin')
@section('page_title', 'KEVCO API Services')
@section('page_description', 'KEVCO Administration area')

@section('content')

    <div class="card-box">
        <div class="dropdown pull-right">
            <a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown" aria-expanded="false">
                <i class="mdi mdi-dots-vertical"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <!-- item-->
                <a href="/io/public/migrate/products?sku={{$data->stock_code}}&cmd=Y&return=/data/parent_product/{{$data->stock_code}}?migrated=Y" class="dropdown-item">Mark for Migration</a>
                <a href="/io/public/migrate/products?sku={{$data->stock_code}}" target="_blank" class="dropdown-item">Migrate now (New Window)</a>
            </div>
        </div>

        <h4 class="header-title mt-0 m-b-30">{{$data->stock_code}}</h4>

        @if(!empty($_GET['migrated']))
            <div class="col-md-12">
                <div class="card m-b-20 text-white bg-success text-xs-center">
                    <div class="card-body">
                        <blockquote class="card-bodyquote">
                          <p>Product has been scheduled to be picked up for migration to Magento database.</p>
                        </blockquote>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">

            <div class="col-xl-12 mt-md-3">

                <ul class="nav nav-tabs nav-justified">
                    <li class="nav-item">
                        <a href="#home2" data-toggle="tab" aria-expanded="false" class="nav-link active">
                            General
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#profile2" data-toggle="tab" aria-expanded="true" class="nav-link">
                            SKU's
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#messages2" data-toggle="tab" aria-expanded="false" class="nav-link">
                            Images
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#settings2" data-toggle="tab" aria-expanded="false" class="nav-link">
                            Stock Levels
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade show active" id="home2">
                        @foreach ($parent_fields as $field)
                        <dl class="row">
                        <dt class="col-sm-3">{{ucwords(str_replace("_", " ", $field))}}</dt>
                        <dd class="col-sm-9">@php echo $data->{$field} @endphp</dd>
                        </dl>
                        @endforeach
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="profile2">
                        @if(count($data->skus) > 0)

                            <div class="table-responsive">
                                <table id="datatable1" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        @foreach($child_fields as $header)
                                            <th>{{$header}}</th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data->skus as $row)
                                        <tr>
                                            @foreach($child_fields as $header)
                                                <td>{{isset($row[$header]) ? $row[$header] : ''}}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                        @else
                        <p>No child SKU's found.</p>
                        @endif
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="messages2">
                        <div class="table-responsive">
                            <table id="datatable2" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Stock Code</th>
                                    <th>Image Name</th>
                                    <th>File Name</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($data->parent_images) > 0)
                                    @foreach($data->parent_images as $image)
                                    <tr>
                                        <td>{{$image->stock_code}} (Parent)</td>
                                        <td>
                                            {{$image->image_name}}
                                            @if(!empty($image->image_name) && trim($image->image_name) != "-")
                                                <br/>
                                                <img src="/pub/media/catalog/product/khaos/{{str_replace("P:\Khaos\Images", "", $image->image_name)}}" title="image" style="max-width:200px"/>
                                            @endif
                                        </td>
                                        <td>
                                            {{$image->file_name}}
                                            @if(!empty($image->file_name) && trim($image->file_name) != "-")
                                                <br/>
                                                <img src="/pub/media/catalog/product/khaos/{{ str_replace("P:\Khaos\Images", "", $image->file_name) }}" title="image" style="max-width:200px"/>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                @if(count($data->skus) > 0)
                                    @foreach($data->skus as $sku)
                                        @if (count($sku->images) > 0)
                                            @foreach ($sku->images as $image)
                                                <tr>
                                                    <td>{{$image->stock_code}}</td>
                                                    <td>
                                                        {{$image->image_name}}
                                                        @if(!empty($image->image_name))
                                                            <br/>
                                                            <img src="/pub/media/catalog/product/khaos/{{str_replace("P:\Khaos\Images", "", $image->image_name)}}" title="image" style="max-width:200px"/>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{$image->file_name}}
                                                        @if(!empty($image->file_name))
                                                            <br/>
                                                            <img src="/pub/media/catalog/product/khaos/{{ str_replace("P:\Khaos\Images", "", $image->file_name) }}" title="image" style="max-width:200px"/>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif

                                    </tbody>
                                </table>
                            </div>

                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="settings2">
                        @if(count($data->skus) > 0)

                            <div class="table-responsive">
                                <table id="datatable3" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        @foreach($stock_fields as $header)
                                            <th>{{$header}}</th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($data->parent_stock_levels))
                                        @foreach($data->parent_stock_levels as $stock_level)
                                            <tr>
                                                @foreach($stock_fields as $header)
                                                    <td>{{isset($stock_level[$header]) ? $stock_level[$header] : ''}}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    @endif

                                    @foreach($data->skus as $row)
                                        @if(count($row->stock_levels))
                                            @foreach($row->stock_levels as $stock_level)
                                                <tr>
                                                    @foreach($stock_fields as $header)
                                                        <td>{{isset($stock_level[$header]) ? $stock_level[$header] : ''}}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                        @else
                            <p>No child SKU's found.</p>
                        @endif
                    </div>
                </div>
            </div><!-- end col -->

        </div>
        <!-- end row -->

    </div>


    @push('script-footer')

        <script type="text/javascript">
            $(document).ready(function () {

                // Default Datatable
                $('#datatable1').DataTable();
                $('#datatable2').DataTable();
                $('#datatable3').DataTable();

            });
        </script>

    @endpush

@endsection
