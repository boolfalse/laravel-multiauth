@extends('admin.layouts.app')

@push('custom_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/datatables.min.css') }}"/>
    @include('admin.components.switcher2')
@endpush

@section('content')

    <table id="yajra_datatable" class="table table-bordered">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Image</th>
            <th>Status</th>

            <th>Options</th>
        </tr>
        </thead>
    </table>

    @include('admin.components.modal-delete')

@endsection

@push('custom_scripts')
    <script src="{{ asset('js/admin/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript">
        function delete_action(item_id){
            $('#item_id').val(item_id);
        }
        function initDataTable() {
            var YajraDataTable = $('#yajra_datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('admin.users.ajax') }}",
                "columns":[
                    {
                        "data": "name",
                        "name": "name",
                    },
                    {
                        "data": "email",
                        "name": "email",
                    },
                    {
                        "data": "image",
                        "name": "image",
                    },
                    {
                        "data": "status",
                        "name": "status",
                    },
                    {
                        "data": "",
                        "name": ""
                    },
                ],
                "autoWidth": false,
                // "drawCallback": function(settings, json) {
                //     // callback function that is called every time DataTables performs a draw
                //     var items_array = settings.json.data;
                //     for (var i = 0; i < items_array.length; i++) {
                //         initSwitcher(items_array[i].status, items_array[i].id, false);
                //     }
                // },
                'columnDefs': [
                    { // options
                        'targets': -1,
                        'defaultContent': '-',
                        'searchable': false,
                        'orderable': false,
                        'width': '10%',
                        'className': 'dt-body-center',
                        'render': function (data, type, full_row, meta){
                            return '<div style="display: block">' +
                                '<a href="{{ url('/admin/users/show') }}/' + full_row.id + '" class="btn btn-primary btn-xs">Show</a> ' +
                                '<button onclick="delete_action(' + full_row.id + ')" type="button" class="delete_action btn btn-danger btn-xs" data-toggle="modal" data-target="#modal_delete">Delete</button>' +
                                '</div>';
                        }

                    },
                    { // status
                        'targets': -2,
                        'defaultContent': '-',
                        'searchable': false,
                        'orderable': false,
                        'width': '10%',
                        'className': 'dt-body-center',
                        'render': function (data, type, full_row, meta){
                            if(full_row.status == "{{ $statuses['active'] }}" || full_row.status == "{{ $statuses['not_active'] }}"){
                                return '<div style="display: block">' +
                                    '<label class="switch"> <input onchange="change_status_action(this.getAttribute(\'data-id\'))" id="checkbox" data-id="' + full_row.id + '" type="checkbox" ' + (full_row.status == "{{ $statuses['active'] }}" ? "checked" : "") + ' /> <div class="slider round"> </div> </label>' +
                                    '</div>';
                            } else {
                                return '<div style="display: block">' +
                                    '<span>' + full_row.status + '</span>' +
                                    '</div>';
                            }
                        }
                    },
                    { // image
                        'targets': -3,
                        'defaultContent': '-',
                        'searchable': false,
                        'orderable': false,
                        'width': '10%',
                        'className': 'dt-body-center',
                        'render': function (data, type, full_row, meta){
                            if(full_row.image){
                                return '<img src="{{ asset('/uploads/' . config('project.user.images_folder') . '/small') }}/' + full_row.image + '" />';
                            } else {
                                return '<img src="{{ asset('/images/no_image/small.jpg') }}" />';
                            }
                        }
                    },
                ],
                // 'order': [1, 'asc'], // Order on init. Number is the column, starting at 0
            });
            return YajraDataTable;
        }
        function change_status_action(item_id){
            // e.preventDefault();
            $.ajax({
                url: "{{ route('admin.users.change_status') }}",
                data: {
                    'item_id': item_id,
                    '_token': "{{ csrf_token() }}"
                },
                type: "POST",
                success: function (response) {
                    // var data = JSON.parse(response);
                    if(response.success){
                        // YajraDataTable.ajax.reload(null, false);
                    }else{
                        console.log(response.message);
                    }
                }
            });
        }

        $(document).ready(function() {

            var YajraDataTable = initDataTable();

            $('#delete_action').on('click', function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('admin.users.delete') }}",
                    data: {
                        'item_id': $('#item_id').val(),
                        '_token': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    success: function (response) {
                        $('#modal_delete').modal('hide');
                        // var data = JSON.parse(response);
                        if (response.success) {
                            YajraDataTable.ajax.reload(null, false);
                        }else{
                            console.log(response.message);
                        }
                    }
                })
            });

            $('#modal_delete').on('hidden.bs.modal', function () {
                $('#item_id').val(0);
            });

        });
    </script>
@endpush
