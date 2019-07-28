@extends('admin.layouts.app')

@push('custom_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/datatables.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/switcher3.css') }}"/>
    <script>

        // if(device_is_mobile){
        var status_contents = {
            blocked: '<i class="fa fa-thumbs-down"></i>',
            pending: '<i class="fa fa-pause"></i>',
            approved: '<i class="fa fa-thumbs-up"></i>',
            selector: 'selector',
        }, options_contents = {
            view: '<i class="fa fa-eye"></i>',
            delete: '<i class="fa fa-remove"></i>'
        };
        // } else {
        //     var status_contents = {
        //         blocked: 'blocked',
        //         pending: 'pending',
        //         approved: 'approved',
        //         selector: 'selector'
        //     }, options_contents = {
        //         view: '<i class="fa fa-eye"></i> View',
        //         delete: '<i class="fa fa-remove"></i> Delete'
        //     };
        // }

        function changePosition(item_id, position)
        {
            let item = document.querySelector('.switcher[data-item="' + item_id + '"]');

            let blocked = item.getElementsByClassName("blocked")[0],
                pending = item.getElementsByClassName("pending")[0],
                approved = item.getElementsByClassName("approved")[0],
                selector = item.getElementsByClassName("selector")[0];

            switch (position) {
                case "blocked":
                    selector.style.left = "0px";
                    selector.style.width = blocked.clientWidth + "px";
                    selector.style.backgroundColor = "red";
                    selector.innerHTML = '<i class="fa fa-thumbs-down"></i>';
                    break;
                case "pending":
                    selector.style.left = blocked.clientWidth + "px";
                    selector.style.width = pending.clientWidth + "px";
                    selector.innerHTML = '<i class="fa fa-pause"></i>';
                    selector.style.backgroundColor = "silver";
                    break;
                case "approved":
                    selector.style.left = blocked.clientWidth + pending.clientWidth + 1 + "px";
                    selector.style.width = approved.clientWidth + "px";
                    selector.innerHTML = '<i class="fa fa-thumbs-up"></i>';
                    selector.style.backgroundColor = "green";
                    break;
                default:
                // pending
            }
        }
        function initSwitcher(position, item_id, change)
        {
            if(change){
                $.ajax({
                    url: "{{ route('admin.products.change_status') }}",
                    data: {
                        'item_id': item_id,
                        'status': position,
                        '_token': "{{ csrf_token() }}"
                    },
                    type: "POST",
                    success: function (data) {
                        if(data.success){
                            changePosition(item_id, position)
                        }else{
                            console.log(data.message);
                        }
                    }
                });
            } else {
                changePosition(item_id, position);
            }
        }
    </script>
@endpush

@section('content')

    <table id="yajra_datatable" class="table table-bordered">
        <thead>
        <tr>
            <th>Admin</th>
            <th>Main Image</th>
            <th>Title</th>
            <th>Status</th>
            <th>Options</th>
        </tr>
        </thead>
    </table>

@endsection

@push('custom_scripts')
    <script src="{{ asset('js/admin/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript">
        function initDataTable() {
            var YajraDataTable = $('#yajra_datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('admin.products.ajax') }}",
                "columns":[
                    {
                        "data": "admin_name_for_datatable",
                        "name": "" //ss for avoiding Yajra DataTable search errors, we need to write here any searchable and existing in table column name: e.g. 'title'
                    },
                    {
                        "data": "main_image",
                        "name": "main_image"
                    },
                    {
                        "data": "product_title",
                        "name": "title"
                    },
                    {
                        "data": "status",
                        "name": "status"
                    },
                    {
                        "data": "",
                        "name": "",
                    },
                ],
                "autoWidth": false,
                "drawCallback": function(settings, json) {
                    let items_array = settings.json.data;
                    for (let i = 0; i < items_array.length; i++) {
                        initSwitcher(items_array[i].status, items_array[i].id, false);
                    }
                },
                'columnDefs': [
                    { // admin
                        'targets': 0,
                        'defaultContent': '-',
                        'searchable': false,
                        'orderable': false,
                        'className': 'dt-body-center',
                        // 'width': '10%',
                        'render': function (data, type, full_row, meta){
                            return '<div style="display: block;">' + full_row.admin_name_for_datatable + '</div>';
                        }
                    },
                    { // image
                        'targets': 1,
                        'defaultContent': '-',
                        'searchable': false,
                        'orderable': false,
                        // 'width': '10%',
                        'className': 'dt-body-center',
                        'render': function (data, type, full_row, meta){
                            return '<img onerror="this.src=\'{{ asset('/images/no_image/small.jpg') }}\'" src="{{ asset('/uploads/' . config('project.product.images_folder') . '/small') }}/' + full_row.main_image + '" />';
                        }
                    },
                    { // title
                        'targets': 2,
                        'defaultContent': '-',
                        'searchable': true,
                        'orderable': false,
                        // 'width': '10%',
                        // 'className': 'dt-body-center',
                    },
                    { // status
                        'targets': 3,
                        'defaultContent': '-',
                        'searchable': false,
                        'orderable': false,
                        // 'width': '20%',
                        'render': function (data, type, full_row, meta){
                            return '<div class="switcher" data-item="' + full_row.id + '"> \
                                    <div class="switch2 blocked" onclick="initSwitcher(\'blocked\', this.parentElement.getAttribute(\'data-item\'), true)">' + status_contents.blocked + '</div> \
                                    <div class="switch2 pending" onclick="initSwitcher(\'pending\', this.parentElement.getAttribute(\'data-item\'), true)">' + status_contents.pending + '</div> \
                                    <div class="switch2 approved" onclick="initSwitcher(\'approved\', this.parentElement.getAttribute(\'data-item\'), true)">' + status_contents.approved + '</div> \
                                    <div class="selector"></div> \
                                </div>';
                        }
                    },
                    {
                        'targets': 4,
                        'defaultContent': '-',
                        'searchable': false,
                        'orderable': false,
                        // 'width': '10%',
                        'className': 'dt-body-center',
                        'render': function (data, type, full_row, meta){
                            return '<div style="display: block">' +
                                '<button type="button" class="btn btn-default btn-xs">Will create options later</button>' +
                                '</div>';
                        }
                    },
                ],
                // 'order': [1, 'asc'], // Order on init. Number is the column, starting at 0
            });
            return YajraDataTable;
        }

        $(document).ready(function() {

            var YajraDataTable = initDataTable();

        });
    </script>
@endpush
