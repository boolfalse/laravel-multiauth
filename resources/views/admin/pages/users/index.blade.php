@extends('admin.layouts.app')

@section('custom_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/datatables.min.css') }}"/>
    <style>
        .switch {
            display: inline-block;
            height: 34px;
            position: relative;
            width: 60px;
        }
        .switch input {
            display:none;
        }
        .slider {
            background-color: #ccc;
            bottom: 0;
            cursor: pointer;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            transition: .4s;
        }
        .slider:before {
            background-color: #fff;
            bottom: 4px;
            content: "";
            height: 26px;
            left: 4px;
            position: absolute;
            transition: .4s;
            width: 26px;
        }
        input:checked + .slider {
            background-color: #66bb6a;
        }
        input:checked + .slider:before {
            transform: translateX(26px);
        }
        .slider.round {
            border-radius: 34px;
        }
        .slider.round:before {
            border-radius: 50%;
        }
    </style>
@endsection

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

    <div class="modal modal-danger fade" id="modal_delete">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Delete User</h4>
                </div>
                <div class="modal-body">
                    <p>Are You sure You want to delete this User?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
                    <button id="delete_action" type="button" class="btn btn-outline">Submit</button>
                    <input type="hidden" id="item_id" value="0" />
                </div>
            </div>
        </div>
    </div>


@endsection

@section('custom_scripts')
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
                    {
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
                    {
                        'targets': -2,
                        'defaultContent': '-',
                        'searchable': false,
                        'orderable': false,
                        'width': '10%',
                        'className': 'dt-body-center',
                        'render': function (data, type, full_row, meta){
                            if(full_row.status == "{{ $status_active }}" || full_row.status == "{{ $status_not_active }}"){
                                return '<div style="display: block">' +
                                    //ss https://codepen.io/AllThingsSmitty/pen/MmxxOz
                                    '<label class="switch"> <input onchange="change_status_action(this.getAttribute(\'data-id\'))" id="checkbox" data-id="' + full_row.id + '" type="checkbox" ' + (full_row.status == "{{ $status_active }}" ? "checked" : "") + ' /> <div class="slider round"> </div> </label>' +
                                    '</div>';
                            } else {
                                return '<div style="display: block">' +
                                    '<span>' + full_row.status + '</span>' +
                                    '</div>';
                            }
                        }
                    },
                    {
                        'targets': -3,
                        'defaultContent': '-',
                        'searchable': false,
                        'orderable': false,
                        'width': '10%',
                        'className': 'dt-body-center',
                        'render': function (data, type, full_row, meta){
                            return '<img onerror="this.src=\'{{ asset('/images/no_image/small.jpg') }}\'" src="{{ asset('/uploads/' . config('project.user.images_folder') . '/small') }}/' + full_row.image + '" />';
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
@endsection