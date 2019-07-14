<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');

        View::share('action', 'no_add');
        View::share('nav', 'users');
    }

    public function index()
    {
        return view('admin.pages.users.index', [
            'statuses' => [
                'active' => User::ACTIVE,
                'not_active' => User::NOT_ACTIVE,
            ],
        ]);
    }

    public function ajax()
    {
        $items = User::select(
            'name',
            'image',
            'status',
            'email',
            'id'
        )->orderBy('id', 'DESC');

        return DataTables::of($items)->make(true);
    }

    public function show($item_id)
    {
        $item = User::find($item_id);
        if(empty($item)){
            return redirect()->route('admin.dashboard');
        }

        return view('admin.pages.users.show', [
            'item' => $item,
        ]);
    }

    public function change_status(Request $request)
    {
        $item_id = $request->get('item_id');
        $item = User::find($item_id);

        if(empty($item)) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found!',
            ], 404);
        } else {
            if($item->status == User::ACTIVE || $item->status == User::NOT_ACTIVE){
                $item->status = ($item->status == User::ACTIVE ? User::NOT_ACTIVE : User::ACTIVE);
                $item->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Item status successfully changed.',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found!',
                ], 404);
            }
        }
    }

    public function delete(Request $request)
    {
        $item_id = $request->get('item_id');
        $item = User::find($item_id);

        if(empty($item)) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found!',
            ], 404);
        } else {
            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item successfully deleted.',
            ], 200);
        }
    }
}
