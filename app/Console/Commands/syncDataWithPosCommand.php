<?php

namespace App\Console\Commands;

use App\Models\SyncDataWithPos;
use App\Models\Variation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class syncDataWithPosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restaurant:syncDataWithPosCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sync data with pos system';

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
        $syncable_data = SyncDataWithPos::where('is_synced', false)->get();
        $ENABLE_POS_SYNC = env('ENABLE_POS_SYNC', false);
        $POS_SYSTEM_URL = env('POS_SYSTEM_URL', null);
        $POS_ACCESS_TOKEN = env('POS_ACCESS_TOKEN', null);


        if ($ENABLE_POS_SYNC == true && !empty($POS_SYSTEM_URL) && !empty($POS_ACCESS_TOKEN)) {
            foreach ($syncable_data as $sync) {
                if ($sync->request_type == 'POST') {
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $POS_ACCESS_TOKEN,
                    ])->post($POS_SYSTEM_URL . '/api/' . $sync->route_name, $sync->request_data)->json();
                } elseif ($sync->request_type == 'PUT') {
                    $response = Http::withHeaders([
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer ' . $POS_ACCESS_TOKEN,
                    ])->put($POS_SYSTEM_URL . '/api/' . $sync->route_name . '/' . $sync->pos_model_id, $sync->request_data)->json();
                } elseif ($sync->request_type == 'DELETE') {
                    $response = Http::withHeaders([
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer ' . $POS_ACCESS_TOKEN,
                    ])->delete($POS_SYSTEM_URL . '/api/' . $sync->route_name . '/' . $sync->pos_model_id, $sync->request_data)->json();
                }

                if (!empty($response['success'])) {
                    SyncDataWithPos::where('id', $sync->id)->update(['is_synced' => true]);
                    $model_class = 'App\Models\\' . $sync->model_name;
                    if (!empty($response['data']['id'])) {
                        $model_class::where('id', $sync->model_id)->update(['pos_model_id' => $response['data']['id']]);
                    }

                    if ($sync->model_name == 'Product' && ($sync->request_type == 'POST' || $sync->request_type == 'PUT')) {
                        $variations = $response['data']['variations'];

                        foreach ($variations as $v) {
                            Variation::where('id', $v['restaurant_model_id'])->update(['pos_model_id' => $v['id']]);
                        }
                    }
                }
            }
        }
    }
}
