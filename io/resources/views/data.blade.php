@extends('layout.app-loggedin')
@section('page_title', 'KEVCO API Services')
@section('page_description', 'KEVCO Administration area')

@section('content')

    <h4 class="header-title mt-0 m-b-30">{{ucwords(str_replace("_", " ", $table))}}</h4>

    <div class="table-responsive">
        <table id="datatable" class="table table-bordered">
            <thead>
            <tr>
                @foreach($headers as $header)
                <th>{{$header}}</th>
                @endforeach
                @if($show_link == 1)
                <th></th>
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach($data as $row)
                <tr>
                    @foreach($headers as $header)
                    <td>{{isset($row[$header]) ? $row[$header] : ''}}</td>
                    @endforeach
                    @if($show_link == 1 && isset($row->stock_code))
                            <td><a href="/io/public/data/{{$table}}/{{$row->stock_code}}"><button class="btn btn-icon waves-effect waves-light btn-warning m-b-5"> <i class="fa fa-wrench"></i> </button></a></td>
                    @elseif($show_link == 2)
                            <td><a href="/io/public/data/{{$table}}/{{$row->id}}"><button class="btn btn-icon waves-effect waves-light btn-warning m-b-5"> <i class="fa fa-wrench"></i> </button></a></td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


    @push('script-footer')

        <script type="text/javascript">
            $(document).ready(function () {

                // Default Datatable
                $('#datatable').DataTable();

                //Buttons examples
                var table = $('#datatable-buttons').DataTable({
                    lengthChange: false,
                    buttons: ['copy', 'excel', 'pdf']
                });

                // Key Tables

                $('#key-table').DataTable({
                    keys: true
                });

                // Responsive Datatable
                $('#responsive-datatable').DataTable();

                // Multi Selection Datatable
                $('#selection-datatable').DataTable({
                    select: {
                        style: 'multi'
                    }
                });

                table.buttons().container()
                    .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
            });

        </script>

    @endpush

@endsection
