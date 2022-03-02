@extends('layout.app-loggedin')
@section('page_title', 'KEVCO API Services')
@section('page_description', 'KEVCO Administration area')

@section('content')

    <h4 class="header-title mt-0 m-b-30">Stock Code</h4>
    <div class="table-responsive">
        <form action="" method="get">
            @csrf
            <table class="table mb-0">
                <tbody>
                <tr>
                    <td>
                        <div class="form-group row">
                            <label for="stock_code" class="col-sm-4 col-form-label">Parent Stock Code</label>
                            <div class="col-sm-7">
                                <input type="text" name="stock_code"
                                       value="{{isset($_GET['stock_code']) ? $_GET['stock_code'] : ''}}" required=""
                                       parsley-type="url" class="form-control" id="stock_code" placeholder="Stock Code">
                            </div>
                        </div>
                    </td>
                    <td>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">
                            Get Khaos Sync / Migrate Links
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
        @if(!empty($_GET['stock_code']))
            <table class="table mb-0">
                <tbody>
                <tr>
                    <td>
                        <div class="form-group row">
                            https://www.wholesalelingerie.co.uk/io/public/request/GetStockList/5?stock_code={{$_GET['stock_code']}}
                        </div>
                    </td>
                    <td>
                        <a href="https://www.wholesalelingerie.co.uk/io/public/request/GetStockList/5?stock_code={{$_GET['stock_code']}}" target="_blank">
                        <button class="btn btn-primary waves-effect waves-light">
                            RUN
                        </button>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group row">
                            http://www.wholesalelingerie.co.uk/io/public/migrate/products?sku={{$_GET['stock_code']}}
                        </div>
                    </td>
                    <td>
                        <a href="http://www.wholesalelingerie.co.uk/io/public/migrate/products?sku={{$_GET['stock_code']}}" target="_blank">
                        <button class="btn btn-primary waves-effect waves-light">
                            RUN
                        </button>
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
        @endif
    </div>
@endsection
