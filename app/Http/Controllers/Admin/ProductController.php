<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductClass;
use App\Models\ProductSize;
use App\Models\Size;
use App\Models\Variation;
use App\Utils\DatatableUtil;
use App\Utils\ProductUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $commonUtil;
    protected $productUtil;


    /**
     * Constructor
     *
     * @param ProductUtils $product
     * @return void
     */
    public function __construct(Util $commonUtil, ProductUtil $productUtil)
    {
        $this->commonUtil = $commonUtil;
        $this->productUtil = $productUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('product.view')) {
            abort(403, __('lang.not_authorized'));
        }
        if (request()->ajax()) {

            $products = Product::
            leftjoin('product_classes', 'products.product_class_id', 'product_classes.id')
            ->orderBy('products.sort')->orderBy('products.created_at','desc')
            ->where(function($query){
                if(env('ENABLE_POS_SYNC')){
                    $query->where('is_raw_material', 0);
                    $query->whereNull('deleted_at');
                }
            });
            if (!empty(request()->product_class_id)) {
                $products->where('products.product_class_id', request()->product_class_id);
            }

            $products = $products->select(
                'products.*',
                'product_classes.name as category',

            );

            return DataTables::of($products)
                ->addColumn('image', function ($row) {
                    $image = images_asset($row->getFirstMediaUrl('product'));
                    if (!empty($image)) {
                        return '<img src="' . $image . '" height="50px" width="50px">';
                    } else {
                        return '<img src="' . images_asset(asset('/uploads/' . session('logo'))) . '" height="50px" width="50px">';
                    }
                })
                ->editColumn('discount_start_date', '@if(!empty($discount_start_date)){{@format_date($discount_start_date)}}@endif')
                ->editColumn('discount_end_date', '@if(!empty($discount_end_date)){{@format_date($discount_end_date)}}@endif')
                // ->editColumn('discount', '{{@num_format($discount)}}')
                ->editColumn('category', function ($row) {
                    $category = ProductClass::find($row->product_class_id);
                    return $category->name ?? '';
                })
                ->editColumn('size',  function ($row) {
                    $size_name='';
                    $product_sizes = Variation::where('product_id',$row->id)->get();
                    if(!empty($product_sizes)){
                    foreach($product_sizes as $size){
                        $size=Size::find($size->size_id);
                        if(isset($size->name)){
                            $size_name.=$size->name.'<br>';
                        }else{
                            $size_name.=''.'<br>';
                        }
                    }
                }
                    return $size_name;
                })
                ->editColumn('sell_price',  function ($row) {
                    $sell='';
                    $product_sizes = Variation::where('product_id',$row->id)->get();
                    if(!empty($product_sizes)){
                    foreach($product_sizes as $size){
                        $p=number_format($size->default_sell_price,  2, '.', ',');
                        $sell.=$p.'<br>';
                    }
                }
                    return $sell;
                })
                ->editColumn('purchase_price', function ($row) {
                    $purchase='';
                    $product_sizes = Variation::where('product_id',$row->id)->get();
                    if(!empty($product_sizes)){
                    foreach($product_sizes as $size){
                        $p=number_format($size->default_purchase_price,  2, '.', ',');
                        $purchase.=$p.'<br>';
                    }
                }
                    return $purchase;
                })
                ->editColumn('discount', '{{@num_format($discount)}}')
                ->editColumn('active', function ($row) {
                    if(!env('ENABLE_POS_SYNC')){
                        if ($row->active == 1) {
                            return '<span class="badge badge-success">' . __('lang.active') . '</span>';
                        } else {
                            return '<span class="badge badge-danger">' . __('lang.deactivated') . '</span>';
                        }
                    }else{
                        if ($row->menu_active == 1) {
                            return '<span class="badge badge-success">' . __('lang.active') . '</span>';
                        } else {
                            return '<span class="badge badge-danger">' . __('lang.deactivated') . '</span>';
                        }
                    }
                })
                ->editColumn('product_details', '{!! $product_details !!}')
                ->addColumn(
                    'action',
                    function ($row) {
                        $html =
                            '<div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">' . __('lang.action') .
                            '<span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">';

                        if (auth()->user()->can('product.view')) {
                            $html .=
                                '<li><a data-href="' . action('Admin\ProductController@show', $row->id) . '"
                                data-container=".view_modal" class="btn btn-modal text-primary"><i class="fa fa-eye"></i>
                                ' . __('lang.view') . '</a></li>';
                        }
                        $html .= '<li class="divider"></li>';
                        if (auth()->user()->can('product.create_and_edit')) {
                            $html .=
                                '<li><a href="' . action('Admin\ProductController@edit', $row->id) . '" class="btn"
                            target="_blank"><i class="fas fa-edit"></i> ' . __('lang.edit') . '</a></li>';
                        }

                        $html .= '<li class="divider"></li>';
                        if (auth()->user()->can('product.delete')) {
                            $html .=
                                '<li>
                            <a data-href="' . action('Admin\ProductController@destroy', $row->id) . '"
                                data-check_password="' . action('Admin\UserController@checkPassword', Auth::user()->id) . '"
                                class="btn text-red delete_item"><i class="fa fa-trash"></i>
                                ' . __('lang.delete') . '</a>
                        </li>';
                        }

                        $html .= '</ul></div>';

                        return $html;
                    }
                )


                ->rawColumns([
                    'image',
                    'active',
                    'size',
                    'sell_price',
                    'purchase_price',
                    'product_details',
                    'active',
                    'action',
                ])
                ->make(true);
        }


        $categories = ProductClass::orderBy('name', 'asc')->pluck('name', 'id');
        return view('admin.product.index')->with(compact(
            'categories',
        ));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('product.create')) {
            abort(403, __('lang.not_authorized'));
        }

        $categories = ProductClass::orderBy('name', 'asc')->pluck('name', 'id');
        $sizes = Size::orderBy('created_at', 'desc')->pluck('name', 'id');

        return view('admin.product.create')->with(compact(
            'categories','sizes'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        
            $data = $request->except('_token', 'image');
            $data['sku'] = $this->productUtil->generateProductSku($data['name']);
            if(empty($request->variations)){
                $data['purchase_price'] = $data['purchase_price'];
                $data['sell_price'] = $data['sell_price'];
            }else{
                // foreach ($request->variations as $v) {
                    $data['purchase_price'] = 0;
                    $data['sell_price'] = 0;
                //     break;
                // }
            }
            $data['discount_type'] = !empty($request->discount_type)? $request->discount_type:null;
            $data['discount'] = !empty($request->discount)?$request->discount : null;
            $data['discount_start_date'] = !empty($data['discount_start_date']) ? $this->commonUtil->uf_date($data['discount_start_date']) : null;
            $data['discount_end_date'] = !empty($data['discount_end_date']) ? $this->commonUtil->uf_date($data['discount_end_date']) : null;
            $data['created_by'] = auth()->user()->id;
            $data['active'] = !empty($data['active']) ? 1 : 0;
            $data['menu_active'] = !empty($data['menu_active']) ? 1 : 0;
            if(env('ENABLE_POS_SYNC')){
                $data['barcode_type'] = !empty($data['barcode_type']) ? $data['barcode_type'] : 'C128';
            }
            $data['type'] = !empty($request->this_product_have_variant) ? 'variable' : 'single';
            $data['translations'] = !empty($data['translations']) ? $data['translations'] : [];
            $data['details_translations'] = !empty($data['details_translations']) ? $data['details_translations'] : [];
            $data['sort'] = !empty($data['sort']) ? $data['sort'] : 1;
            DB::beginTransaction();
            $product = Product::create($data);

            $this->productUtil->createOrUpdateVariations($product, $request->variations);
  

            if ($request->has('image')) {
                if (!empty($request->input('image'))) {
                    if(preg_match('/^data:image/', $request->input('image')))
                    {
                        $extention = explode(";",explode("/",$request->image)[1])[0];
                        $image = rand(1,1500)."_image.".$extention;
                        $filePath = public_path($image);
                        $fp = file_put_contents($filePath,base64_decode(explode(",",$request->image)[1]));
                        $product->addMedia($filePath)->toMediaCollection('product');
                    }
                }
            }


            $data['variations'] = $product->variations->toArray();
            $product_class = ProductClass::find($data['product_class_id']);
            $data['product_class_id'] = $product_class->pos_model_id;

           // $this->commonUtil->addSyncDataWithPos('Product', $product, $data, 'POST', 'product');

            DB::commit();

            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];


        return redirect()->back()->with('status', $output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        return view('admin.product.show')->with(compact(
            'product'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('product.edit')) {
            abort(403, __('lang.not_authorized'));
        }

        $product = Product::find($id);
        $categories = ProductClass::orderBy('name', 'asc')->pluck('name', 'id');
        $sizes = Size::orderBy('created_at', 'desc')->pluck('name', 'id');

        return view('admin.product.edit')->with(compact(
            'product',
            'sizes',
            'categories',
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->except('_token', '_method', 'image');
            if(!empty($request->variations) && count($request->variations)==1){
                if(!empty($request->variations)){
                    foreach ($request->variations as $v) {
                        if($v['name']=='Default'){
                            $data['purchase_price'] = $data['purchase_price'];
                            $data['sell_price'] = $data['sell_price'];
                        }else{
                            $data['purchase_price'] = 0;
                            $data['sell_price'] = 0;
                        }
                    break;
                    }
                }
                
            }
            elseif(empty($request->variations)){
                $data['purchase_price'] = $data['purchase_price'];
                $data['sell_price'] = $data['sell_price'];
            }
            else{
                $data['purchase_price'] = 0;
                $data['sell_price'] = 0;
            }
            $data['discount'] = $data['discount'];
            $data['discount_type'] = $data['discount_type'];
            $data['discount_start_date'] = !empty($data['discount_start_date']) ? $this->commonUtil->uf_date($data['discount_start_date']) : null;
            $data['discount_end_date'] = !empty($data['discount_end_date']) ? $this->commonUtil->uf_date($data['discount_end_date']) : null;
            // $data['active'] = !empty($data['active']) ? 1 : 0;
            $data['menu_active'] = !empty($data['menu_active']) ? 1 : 0;
            $data['created_by'] = auth()->user()->id;
            $data['type'] = !empty($request->this_product_have_variant) ? 'variable' : 'single';
            $data['translations'] = !empty($data['translations']) ? $data['translations'] : [];
            $data['details_translations'] = !empty($data['details_translations']) ? $data['details_translations'] : [];
            $data['sort'] = !empty($data['sort']) ? $data['sort'] :1;
            $product = Product::where('id', $id)->first();

            DB::beginTransaction();
            $product->update($data);
            $this->productUtil->createOrUpdateVariations($product, $request->variations);
            // $this->productUtil->createOrUpdateProductSizes($product, $request->sizes);

           /* if ($request->has('uploaded_image_name')) {
                if (!empty($request->input('uploaded_image_name'))) {
                    $product->addMediaFromDisk($request->input('uploaded_image_name'), 'temp')->toMediaCollection('product');
                }
            } */
            if ($request->has('image')) {
                if (!empty($request->input('image'))) {
                    if(preg_match('/^data:image/', $request->input('image')))
                    {
                    $product->clearMediaCollection('product');
                    $extention = explode(";",explode("/",$request->image)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path($image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$request->image)[1]));
                    $product->addMedia($filePath)->toMediaCollection('product');
                    }
                }
            }
            if(!$request->has('image') || strlen($request->input('image'))==0){
                $product->clearMediaCollection('product');
            }



            $data['variations'] = $product->variations->toArray();
            $product_class = ProductClass::find($data['product_class_id']);
            $data['product_class_id'] = $product_class->pos_model_id;

            // $this->commonUtil->addSyncDataWithPos('Product', $product, $data, 'PUT', 'product');
            DB::commit();
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return redirect()->back()->with('status', $output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $product = Product::find($id);

            $this->commonUtil->addSyncDataWithPos('Product', $product, null, 'DELETE', 'product');
            Variation::where('product_id', $id)->delete();
            DB::commit();

            $product->delete();
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return $output;
    }

    public function deleteProductImage($id)
    {
        try {
            $media = Media::find($id);
            $media->delete();

            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return $output;
    }

    /**
     * add variation to product
     *
     * @return void
     */
    public function getVariationRow()
    {
        $row_id = request()->row_id;

        $sizes = Size::orderBy('created_at', 'desc')->pluck('name', 'id');
        $name = request()->name;
        $purchase_price = request()->purchase_price;
        $sell_price = request()->sell_price;

        return view('admin.product.partial.variation_row')->with(compact(
            'sizes',
            'row_id',
            'name',
            'purchase_price',
            'sell_price'
        ));
    }
    public function getSizeRow()
    {
        $row_id = request()->row_id;

        $sizes = Size::orderBy('created_at', 'desc')->pluck('name', 'id');
        return view('admin.product.partial.size_row')->with(compact(
            'sizes',
            'row_id'
        ));
    }
}
