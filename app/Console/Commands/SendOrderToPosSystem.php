<?php

namespace App\Console\Commands;

use App\Events\NewOrderEvent;
use App\Models\DiningTable;
use App\Models\Order;
use App\Models\OrderDetails;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendOrderToPosSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restaurant:sendOrderToPosSystem';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ENABLE_POS_SYNC = env('ENABLE_POS_SYNC', false);
        $POS_SYSTEM_URL = env('POS_SYSTEM_URL', null);
        $POS_ACCESS_TOKEN = env('POS_ACCESS_TOKEN', null);

        if ($ENABLE_POS_SYNC == true && !empty($POS_SYSTEM_URL) && !empty($POS_ACCESS_TOKEN)) {
            $orders = Order::whereNull('pos_transaction_id')->with(['order_details', 'store'])->get()->toArray();

            foreach ($orders as $order) {
                $order['dining_table_id'] = null;
                if (!empty($order['table_no'])) {
                    $dining_table = DiningTable::find($order['table_no']);
                    if (!empty($dining_table)) {
                        $order['dining_table_id'] = $dining_table->pos_model_id;
                    }
                }
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
            }
        }
    }
}
