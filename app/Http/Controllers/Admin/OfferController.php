<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Product;
use App\Models\ProductClass;
use App\Models\SalesPromotion;
use App\Utils\ProductUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class OfferController extends Controller
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
     * @param Util $commonUtil
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
        $offers = Offer::get();

        if (!auth()->user()->can('offer.view')) {
            abort(403, __('lang.not_authorized'));
        }

        if (request()->ajax()) {
            $offers = Offer::leftjoin('users', 'offers.created_by', 'users.id');

            $offers = $offers->select(
                'offers.*',
                'users.name as created_by_name'
            )->orderBy('offers.name', 'asc');

            return DataTables::of($offers)
                ->addColumn('products', function ($row) {
                    return $row->products->pluck('name')->implode(', ');
                })
                ->editColumn('created_by_name', '{{ucfirst($created_by_name)}}')
                ->editColumn('description', '{!!$description!!}')
                ->editColumn('discount_value', '@if(!empty($discount_value)){{@num_format($discount_value)}}@endif')
                ->editColumn('start_date', '@if(!empty($start_date)){{@format_date($start_date)}}@endif')
                ->editColumn('end_date', '@if(!empty($end_date)){{@format_date($end_date)}}@endif')
                ->editColumn('status', '@if(!empty($status))@lang("lang.active")@else @lang("lang.deactivated")@endif')
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

                        if (auth()->user()->can('offer.view')) {
                            $html .=
                                '<li><a data-href="' . action('Admin\OfferController@show', $row->id) . '"
                                data-container=".view_modal" class="btn btn-modal text-primary"><i class="fa fa-eye"></i>
                                ' . __('lang.view') . '</a></li>';
                        }
                        $html .= '<li class="divider"></li>';
                        if (auth()->user()->can('offer.edit')) {
                            $html .=
                                '<li><a href="' . action('Admin\OfferController@edit', $row->id) . '" class="btn"
                            target="_blank"><i class="fas fa-edit"></i> ' . __('lang.edit') . '</a></li>';
                        }

                        $html .= '<li class="divider"></li>';
                        if (auth()->user()->can('offer.delete')) {
                            $html .=
                                '<li>
                                <a data-href="' . action('Admin\OfferController@destroy', $row->id) . '"
                                    data-check_password="' . action('Admin\UserController@checkPassword', Auth::user()->id) . '"
                                    class="btn text-red delete_item"><i class="fa fa-trash"></i>
                                    ' . __('lang.delete') . '</a>
                            </li>';
                        }
                        $html .=
                            '<li>
                            <a href="' . action('Admin\OfferController@toggleOfferStatus', $row->id) . '"
                                    class="btn text-red">';
                        if ($row->status == 1) {
                            $html .= '<i class="fa fa-ban"></i> ' . __('lang.suspend');
                        } else {
                            $html .= '<i class="fa fa-recycle"></i> ' . __('lang.activate');
                        }
                        $html .= '</a>
                            </li>';

                        $html .= '</ul></div>';

                        return $html;
                    }
                )

                ->rawColumns([
                    'image',
                    'description',
                    'action',
                ])
                ->make(true);
        }

        $products = Product::pluck('name', 'id');

        return view('admin.offer.index')->with(compact(
            'offers',
            'products',
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('offer.create')) {
            abort(403, __('lang.not_authorized'));
        }

        $categories = ProductClass::pluck('name', 'id');
        $products = Product::orderBy('name', 'asc')->pluck('name', 'id');

        return view('admin.offer.create')->with(compact(
            'categories',
            'products'
        ));
    }

    /**
     * store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('offer.create')) {
            abort(403, __('lang.not_authorized'));
        }
        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
            ['discount_value' => ['required', 'max:255']],
            ['start_date' => ['required', 'max:255']],
            ['end_date' => ['required', 'max:255']],
        );

        try {
            $data['name'] = $request->name;
            $data['type'] = 'item_discount';
            $data['code'] = $this->commonUtil->randString(5, 'off');
            $data['product_class_id'] = $request->product_class_id;
            $data['product_ids'] = $request->product_ids;
            $data['description'] = $request->description;
            $data['discount_type'] = $request->discount_type;
            $data['discount_value'] = !empty($request->discount_value) ? $this->productUtil->num_uf($request->discount_value) : 0;
            $data['start_date'] = !empty($request->start_date) ? $this->commonUtil->uf_date($request->start_date) : null;
            $data['end_date'] = !empty($request->end_date) ? $this->commonUtil->uf_date($request->end_date) : null;
            $data['status'] = $request->status ? 1 : 0;
            $data['created_by'] = Auth::user()->id;
            DB::beginTransaction();

            $offer = Offer::create($data);
            $data['product_ids'] = $this->productUtil->getCorrespondingProductIdsReverse($data['product_ids']);
            $this->commonUtil->addSyncDataWithPos('Offer', $offer, $data, 'POST', 'sales-promotion');
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

        return redirect()->to('admin/offers')->with('status', $output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!auth()->user()->can('offer.view')) {
            abort(403, __('lang.not_authorized'));
        }
        $sales_promotion = Offer::find($id);

        return view('admin.offer.show')->with(compact(
            'sales_promotion'
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
        if (!auth()->user()->can('offer.edit')) {
            abort(403, __('lang.not_authorized'));
        }

        $offer = Offer::find($id);
        $categories = ProductClass::pluck('name', 'id');
        $products = Product::orderBy('name', 'asc')->pluck('name', 'id');

        return view('admin.offer.edit')->with(compact(
            'offer',
            'categories',
            'products'
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
        if (!auth()->user()->can('offer.edit')) {
            abort(403, __('lang.not_authorized'));
        }

        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
            ['discount_value' => ['required', 'max:255']],
            ['start_date' => ['required', 'max:255']],
            ['end_date' => ['required', 'max:255']],
        );

        try {
            $data['name'] = $request->name;
            $data['type'] = 'item_discount';
            $data['product_class_id'] = $request->product_class_id;
            $data['product_ids'] = $request->product_ids;
            $data['description'] = $request->description;
            $data['discount_type'] = $request->discount_type;
            $data['discount_value'] = !empty($request->discount_value) ? $this->productUtil->num_uf($request->discount_value) : 0;
            $data['start_date'] = !empty($request->start_date) ? $this->commonUtil->uf_date($request->start_date) : null;
            $data['end_date'] = !empty($request->end_date) ? $this->commonUtil->uf_date($request->end_date) : null;
            $data['status'] = $request->status ? 1 : 0;
            $data['created_by'] = Auth::user()->id;
            DB::beginTransaction();

            $offer = Offer::where('id', $id)->first();
            $offer->update($data);
            $data['product_ids'] = $this->productUtil->getCorrespondingProductIdsReverse($data['product_ids']);
            // print_r($data['product_ids']); die();
            $this->commonUtil->addSyncDataWithPos('Offer', $offer, $data, 'PUT', 'sales-promotion');

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

        return redirect()->to('admin/offers')->with('status', $output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('offer.delete')) {
            abort(403, __('lang.not_authorized'));
        }

        try {
            $offer = Offer::find($id)->first();

            DB::beginTransaction();
            $this->commonUtil->addSyncDataWithPos('Offer', $offer, null, 'DELETE', 'sales-promotion');
            $offer->delete();
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

        return $output;
    }

    /**
     * get product dropdown
     *
     * @param int $id
     * @return void
     */
    public function getProductDropdownByCategory($category_id)
    {
        $products = Product::where('product_class_id', $category_id)->pluck('name', 'id');
        $dropdown = $this->commonUtil->createDropdownHtml($products);

        return  $dropdown;
    }

    /**
     * get product dropdown
     *
     * @param int $id
     * @return void
     */
    public function toggleOfferStatus($offer_id)
    {
        try {
            $offer = Offer::find($offer_id);
            $offer->status = $offer->status == 1 ? 0 : 1;
            $offer->save();
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
}
