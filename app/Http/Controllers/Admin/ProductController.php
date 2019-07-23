<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    public function __construct()
    {
//        parent::__construct();

        $action_full = Route::currentRouteAction();
        $action = substr($action_full, strpos($action_full, "@") + 1);

        View::share('nav', 'products');
        View::share('action', $action);
    }

    public function index()
    {
        return view('admin.pages.products.index');
    }

    public function ajax()
    {
        $items = Product::leftJoin('admins', 'admins.id', '=', 'products.admin_id')
            ->select([
                'products.id',
                'products.admin_id',
                'products.status',
                'products.main_image',
                'products.title',
                DB::raw('LEFT(admins.name , ' . config('project.admin.name_length_limit') . ') AS admin_name_for_datatable'),
                DB::raw('(CASE WHEN LENGTH(products.title) > ' . config('project.product.title_length_limit') .
                    ' THEN CONCAT(LEFT(products.title, ' . config('project.product.title_length_limit') .
                    '), "...") ELSE products.title END) as product_title'),
            ])
            ->orderBy('products.id', 'DESC');

        return DataTables::of($items)
//            ->editColumn('title', function($item) {
//                return mb_substr($item->title, 0, 5);
//            })
            ->make(true);
    }

    public function show($item_id)
    {
        $item = Product::find($item_id);
        if(empty($item)){
            return redirect()->route('admin.dashboard');
        }

        return view('admin.pages.products.show', [
            'item' => $item,
        ]);
    }

    public function edit($item_id)
    {
        $item = Product::find($item_id);
        if(empty($item)){
            return redirect()->route('admin.dashboard');
        }

        return view('admin.pages.products.edit', [
            'item' => $item,
        ]);
    }

    public function create()
    {
        return view('admin.pages.products.create');
    }

    public function delete(Request $request)
    {
        $item_id = $request->get('item_id');
        $item = Product::find($item_id);

        if(empty($item))
        {
            return response()->json([
                'success' => false,
                'message' => 'Item not found!',
            ]);
        } else {
            $item->delete();

            return response()->json([
                'success' => true,
            ]);
        }
    }

    public function image_delete(Request $request)
    {
        $image_id = $request->get('image_id');
        $image = ProductImage::find($image_id);
        if(empty($image)){
            return response()->json([
                'success' => false,
                'message' => 'Image not found!',
            ]);
        }
        $item_id = $request->get('item_id');

        if($item_id == $image->product->id){
            $image->delete();

            return response()->json([
                'success' => true,
            ]);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Image not found!',
            ]);
        }
    }

    public function change_status(Request $request)
    {
        $product_statuses = [
            Product::BLOCKED,
            Product::PENDING,
            Product::APPROVED,
        ];
        $validator = Validator::make($request->all(), [
            'item_id' => 'required|numeric|min:1',
            'status' => [
                'required',
                'regex:/^(' . implode('|', $product_statuses) . ')$/',
            ],
        ]);
        if ($validator->fails()) {
            echo [
                'success' => false,
                'message' => implode(' ', $validator->messages()->all()),
            ];
        } else {
            $item_id = $request->get('item_id');
            $item = Product::find($item_id);
            $status = $request->get('status');

            if(empty($item)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found!',
                ]);
            } else {
                if($item->status == $status){
                    return response()->json([
                        'success' => false,
                        'message' => 'Item already have ' . $status . ' status!',
                    ]);
                } else {
                    $item->status = $status;
                    $item->save();

                    return response()->json([
                        'success' => true,
                    ]);
                }
            }
        }
    }
}
