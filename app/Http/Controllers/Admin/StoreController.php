<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Utils\ProductUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class StoreController extends Controller
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
     * @param productUtil $product
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
        if (!auth()->user()->can('settings.store.view')) {
            abort(403, __('lang.not_authorized'));
        }

        if (request()->ajax()) {

            $stores = Store::orderBy('name', 'asc');


            $stores = $stores->select(
                'stores.*',

            );

            return DataTables::of($stores)
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

                        $html .= '<li class="divider"></li>';
                        if (auth()->user()->can('settings.size.edit')) {
                            $html .=
                                '<li><a data-href="' . action('Admin\StoreController@edit', $row->id) . '" data-container=".view_modal" class="btn btn-modal"
                            target="_blank"><i class="fas fa-edit"></i> ' . __('lang.edit') . '</a></li>';
                        }

                        $html .= '<li class="divider"></li>';
                        if (auth()->user()->can('settings.size.delete')) {
                            $html .=
                                '<li>
                            <a data-href="' . action('Admin\StoreController@destroy', $row->id) . '"
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
                    'action',
                ])
                ->make(true);
        }


        return view('admin.store.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $quick_add = request()->quick_add ?? null;


        return view('admin.store.create')->with(compact(
            'quick_add',
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

        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
        );

        try {
            $data = $request->except('_token', 'quick_add');
            $data['created_by'] = Auth::user()->id;
            DB::beginTransaction();

            $store = Store::create($data);

            $store_id = $store->id;
            $this->commonUtil->addSyncDataWithPos('Store', $store, $data, 'POST', 'store');
            DB::commit();
            $output = [
                'success' => true,
                'store_id' => $store_id,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }


        if ($request->quick_add) {
            return $output;
        }

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $store = Store::find($id);

        return view('admin.store.edit')->with(compact(
            'store'
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
        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
        );

        try {
            $data = $request->except('_token', 'quick_add', '_method');
            DB::beginTransaction();

            $store = Store::where('id', $id)->first();
            $store->update($data);
            $this->commonUtil->addSyncDataWithPos('Store', $store, $data, 'PUT', 'store');
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
            $store = Store::find($id)->first();

            DB::beginTransaction();
            $this->commonUtil->addSyncDataWithPos('Store', $store, null, 'DELETE', 'store');
            $store->delete();
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

    public function getDropdown()
    {
        $store = Store::getDropdown();
        $store_dp = $this->commonUtil->createDropdownHtml($store, 'Please Select');

        return $store_dp;
    }
}
