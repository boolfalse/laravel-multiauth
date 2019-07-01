@extends('admin.layouts.app')

@section('custom_styles')
@endsection

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Users Table</h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 10px">Num.</th>
                            <th>Name</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $k => $user)
                        <tr>
                            <td>{{ $k + 1 }}</td>
                            <td>{{ $user->name }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('custom_scripts')
@endsection