<?php

namespace App\Http\Controllers;

use App\Jobs\SendMessagesJob;
use App\Models\Cart;
use App\Models\Message;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\DiningTable;
use App\Models\Product;
use App\Models\System;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;
use App\Events\NewOrderEvent;
use Illuminate\Support\Facades\Http;
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $user_id = session('user_id');
        if (\Cart::session($user_id)->getTotal() == 0) {
            $output = [
                'success' => false,
                'msg' => __('lang.cart_is_empty')
            ];
            return redirect()->back()->with('status', $output);
        }

        try {

            $string = trim(preg_replace('/\s+/', ' ', $request->sales_note));
            $data['sales_note'] = $string;
            $data['store_id'] = env('ENABLE_POS_SYNC')?$request->store_id:0;
            $data['customer_name'] = $request->customer_name;
            $data['phone_number'] = 0;
            $data['address'] = !empty($request->address) ? $request->address : null;
            $data['order_type'] = !empty($request->order_type) ? 'order_later' : 'order_now';
            $data['month'] = $request->month;
            $data['day'] = $request->day;
            $data['year'] = !empty($request->year) ? $request->year : date('Y');
            $data['time'] = $request->time;
            $data['delivery_type'] = $request->delivery_type;
            $data['payment_type'] = !empty($request->payment_type) ? 'cash_on_delivery' : 'pay_online';
            $data['out_of_restaurant'] = !empty($request->out_of_restaurant) ? 'out_of_restaurant' : 'in_restaurant';
            $data['table_no'] = !empty($request->table_no) ? $request->table_no : null;
            $data['status'] = 'pending';
            $data['delivery_status'] = 'pending';
            $data['ip'] = request()->ip();
            $data['final_total'] = \Cart::session($user_id)->getTotal();

            DB::beginTransaction();
            $order = Order::create($data);


            $cart_content = \Cart::session($user_id)->getContent();
            $text = '%20';
            foreach ($cart_content as $content) {
                $discount_attr = $content->attributes->discount;
                $discount =  !empty($discount_attr) ? $discount_attr : 0;
                $order_details = [
                    'order_id' => $order->id,
                    'product_id' => $content->associatedModel->id,
                    'variation_id' => $content->attributes->variation_id,
                    'discount' => $discount,
                    'quantity' => $content->attributes->quantity,
                    'price' => $content->price,
                    'sub_total' => $content->price * $content->attributes->quantity,
                ];
                $product = Product::find($content->associatedModel->id);
                $text.=urlencode($product->name).'+'.$content->attributes->size.'+%3A'.$order_details['quantity'] ."+%2A+".$order_details['price'].'='.$order_details['sub_total']."+".session('currency')['code']. "%0A";
                
                OrderDetails::create($order_details);
            }
            $order->discount_amount = $order->order_details->sum('discount') ?? 0;
            $order->save();

            \Cart::session($user_id)->clear();
            Cart::where('user_id', $user_id)->delete();

            DB::commit();
            if(env('ENABLE_POS_SYNC') && !empty($request->table_no)){
                $options = array(
                    'cluster' =>  env('PUSHER_APP_CLUSTER'),
                    'useTLS' => true
                );
        
        
                $pusher = new Pusher(
                    env('PUSHER_APP_KEY'),
                    env('PUSHER_APP_SECRET'),
                    env('PUSHER_APP_ID'),
                    $options
                );
        
                $table=DiningTable::find($order->table_no);
                $data = [
                    'order_id'=>$order->id,
                    'table_no'=>$order->table_no,
                    'room_no'=>$table->dining_room_id,
                    'orders_count'=>$order->order_details()->count()
                ];
                $pusher->trigger('order-channel', 'new-order', $data);
            }
            //send email for order

            $email = System::getProperty('system_email'); //system email
            $data["subject"] = 'New Order No: ' . $order->id;
            $data["body"] = view('admin.order.partial.email_body', compact('order'))->render();

            $from = $email;
            $data["email"] = trim($email);

            dispatch(new SendMessagesJob($data, [], $from));

            $email_data['emails'] =  $data["email"];
            $email_data['subject'] =  $data["subject"];
            $email_data['body'] =  $data["body"];
            $email_data['attachments'] =  [];
            $email_data['notes'] =  null;
            Message::create($email_data);


            $site_title = System::getProperty('site_title');
            $text .= "-----+" . urlencode($site_title) . "+----- %0D%0A+" . __('lang.order_no') . "%3A" . $order->id . " " . __('lang.total') . "%3A" . $order->final_total . "+" . session('currency')['code'] . "%0D%0A+" . __('lang.quantity') . "%3A" . $order->order_details->count();
            // $text .= "%0D%0A+------------------ %0D%0A+";
            if ($order->order_type == 'order_now') {
                $text .= __('lang.date_and_time_url') . "+%3A+" . urlencode(date('m/d/Y H:i A'));
            }

            if ($order->order_type == 'order_later') {
                $text .= __('lang.date_and_time_url') . "+%3A+" . $order->month . '/' . $order->day . '/' . $order->year . '%20' . $order->time;
            }

            if ($order->delivery_type == 'home_delivery') {
                $text .= "%0D%0A+" . __('lang.home_delivery');
                $text .= "%0D%0A+" . __('lang.address')."+".$order->address;
            }else if($order->delivery_type == 'dining_in'){
                $text .= "%0D%0A+" . __('lang.dinnig_in_restaurant');
                $text .= "%0D%0A+" . __('lang.table_no')."+".$order->table_no;
            }
            else {
                $text .= "%0D%0A+" . __('lang.i_will_pick_it_up_my_self');
            }
            if ($order->payment_type == 'cash_on_delivery') {
                $text .= "%0D%0A+" . __('lang.cash_on_delivery');
            } else {
                $text .= "%0D%0A+" . __('lang.pay_online');
            }
            $text .= "%0D%0A+" . __('lang.customer') . "%3A" . $order->customer_name;
            // $text .= "%0D%0A+" . __('lang.phone_number') . "%3A" . $order->phone_number;
            $text .= "%0D%0A+" . __('lang.note') . "%3A" . $order->sales_note;

            $POS_SYSTEM_URL = env('POS_SYSTEM_URL', null);
            $POS_ACCESS_TOKEN = env('POS_ACCESS_TOKEN', null);
            $order= Order::where('id', $order->id)->whereNull('pos_transaction_id')->with(['order_details', 'store'])->first()->toArray();
            $order['dining_table_id'] = null;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $POS_ACCESS_TOKEN,
            ])->post($POS_SYSTEM_URL . '/api/order', $order)->json();
            if (!empty($response['success'])) {
                $res_data = $response['data'];
                event(new NewOrderEvent($res_data['id']));

                Order::where('id', $order['id'])->update(['pos_transaction_id' => $res_data['id']]);

                foreach ($res_data['transaction_sell_lines'] as $line) {
                    OrderDetails::where('id', $line['restaurant_order_detail_id'])->update(['pos_transaction_sell_line_id' => $line['id']]);
                }
            }
            
            
            
            $whatsapp = System::getProperty('whatsapp');
            $url = "https://api.whatsapp.com/send/?phone=" . $whatsapp . "&text=" . $text . "&app_absent=0";
            session()->put('order_completed', '1');

            return redirect()->to($url);


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
            return redirect()->back()->with('status', $output);
        }

        
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
