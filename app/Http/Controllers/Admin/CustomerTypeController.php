<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerType;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class CustomerTypeController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $commonUtil;

    /**
     * Constructor
     *
     * @param ProductUtils $product
     * @return void
     */
    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
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

            $customer_types = CustomerType::orderBy('name', 'asc');


            $customer_types = $customer_types->select(
                'customer_types.*',

            );

            return DataTables::of($customer_types)
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

        return view('admin.customer_type.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $quick_add = request()->quick_add ?? null;

        return view('admin.customer_type.create');
    }

    /**
     * store a newly created resource in storage.
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
            $data = $request->only('name');
            $data['created_by'] = Auth::user()->id;
            DB::beginTransaction();

            $customer_type = CustomerType::create($data);

            $customer_type_id = $customer_type->id;


            DB::commit();
            $output = [
                'success' => true,
                'customer_type_id' => $customer_type_id,
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

        return redirect()->to('customer-type')->with('status', $output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer_type_id = $id;
        $customer_type = CustomerType::find($id);

        $discount_query = Transaction::leftjoin('customers', 'transactions.customer_id', 'customers.id')
            ->whereIn('transactions.type', ['sell'])
            ->whereIn('transactions.status', ['final'])
            ->where(function ($q) {
                $q->where('total_sp_discount', '>', 0);
                $q->orWhere('total_product_discount', '>', 0);
                $q->orWhere('total_coupon_discount', '>', 0);
            });

        if (!empty(request()->start_date)) {
            $discount_query->where('transaction_date', '>=', request()->start_date);
        }
        if (!empty(request()->end_date)) {
            $discount_query->where('transaction_date', '<=', request()->end_date);
        }
        if (!empty($customer_type_id)) {
            $discount_query->where('customers.customer_type_id', $customer_type_id);
        }
        $discounts = $discount_query->select(
            'transactions.*'
        )->groupBy('transactions.id')->get();

        $query = Customer::leftjoin('transactions', 'customers.id', 'transactions.customer_id')
            ->where('customers.customer_type_id', $customer_type_id)
            ->select(
                'customers.*',
                DB::raw('SUM(IF(transactions.type="sell", final_total, 0)) as total_purchase'),
                DB::raw('SUM(IF(transactions.type="sell", total_sp_discount, 0)) as total_sp_discount'),
                DB::raw('SUM(IF(transactions.type="sell", total_product_discount, 0)) as total_product_discount'),
                DB::raw('SUM(IF(transactions.type="sell", total_coupon_discount, 0)) as total_coupon_discount'),
            );
        $customers = $query->groupBy('customers.id')->get();
        $payment_types = $this->commonUtil->getPaymentTypeArrayForPos();

        return view('admin.customer_type.show')->with(compact(
            'discounts',
            'customers',
            'customer_type',
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
        $customer_type = CustomerType::find($id);

        return view('admin.customer_type.edit')->with(compact(
            'customer_type',
            'customer_types',
            'products',
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
            $data = $request->only('name');
            DB::beginTransaction();

            $customer_type = CustomerType::where('id', $id)->update($data);

            $customer_type_id = $id;

            DB::commit();
            $output = [
                'success' => true,
                'customer_type_id' => $customer_type_id,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return redirect()->to('customer-type')->with('status', $output);
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
            CustomerType::find($id)->delete();
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
}
