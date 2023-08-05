<?php

namespace App\Http\Controllers\Admin;

use App\Events\NewOrderEvent;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Message;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductClass;
use App\Models\User;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $commonUtil;

    /**
     * Constructor
     *
     * @param Util $commonUtil
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
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');


        //divide month in 4 dates
        $dates = $this->commonUtil->divideMonthInDates($start_date, $end_date);

        //divide array in 4 equal   parts
        $dates = $this->commonUtil->divideArrayInEqualParts($dates, 4);
        //get the first element of the multi array
        $dates = $this->commonUtil->getDateArray($dates);

        $order_count_array = [];
        $number_of_products_array = [];
        $total_revenue_array = [];
        $order_count = 0;
        $number_of_products = 0;
        $total_revenue = 0;
        for ($i = 0; $i < 4; $i++) {
            $orders =  Order::whereBetween('created_at', [$dates[$i], $dates[$i + 1]])->get();


            $order_count_array[$i] = $orders->count();
            $number_of_products_array[$i] = 0;
            $total_revenue_array[$i] = 0;
            foreach ($orders as $order) {
                $number_of_products_array[$i] += $order->order_details->sum('quantity');
                $total_revenue_array[$i] += $order->final_total;
            }
            $order_count += $order_count_array[$i];
            $number_of_products += $number_of_products_array[$i];
            $total_revenue += $total_revenue_array[$i];
        }

        $total_revenue = $this->commonUtil->num_f($total_revenue);

        $count_carts_now = Cart::count();
        $total_price_of_basket = $this->commonUtil->num_f(Cart::sum('total_amount'));
        $count_categories = ProductClass::count();
        $count_products = Product::count();
        $count_offers = Offer::count();

        $query = Order::where('id', '>', 0);

        $latest_orders = $query->select(
            'orders.*'
        )->latest()->limit(5)->get();

        $messages = Message::limit(5)->get();

        return view('admin.dashboard')->with(compact(
            'order_count_array',
            'number_of_products_array',
            'total_revenue_array',
            'order_count',
            'number_of_products',
            'total_revenue',
            'count_carts_now',
            'total_price_of_basket',
            'count_categories',
            'count_products',
            'count_offers',
            'latest_orders',
            'messages',
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
