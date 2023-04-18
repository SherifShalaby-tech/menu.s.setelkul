<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendSmsJob;
use App\Models\Order;
use App\Models\Sms;
use App\Models\System;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class SmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('sms.view')) {
            abort(403, __('lang.not_authorized'));
        }

        if (request()->ajax()) {

            $sms = Sms::leftjoin('users', 'sms.created_by', 'users.id')
                ->orderBy('created_at', 'asc');


            $sms = $sms->select(
                'sms.*',
                'users.name as sent_by'

            );

            return DataTables::of($sms)
                ->editColumn('created_at', '{{@format_datetime($created_at)}}')
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

                        if (auth()->user()->can('sms.view')) {
                            $html .=
                                '<li><a data-href="' . action('Admin\SmsController@resend', $row->id) . '"
                                data-container=".view_modal" class="btn btn-modal text-primary"><i class="fa fa-paper-plane"></i>
                                ' . __('lang.resend') . '</a></li>';
                        }
                        $html .= '<li class="divider"></li>';
                        if (auth()->user()->can('sms.edit')) {
                            $html .=
                                '<li><a href="' . action('Admin\SmsController@edit', $row->id) . '" class="btn"
                            target="_blank"><i class="fas fa-edit"></i> ' . __('lang.edit') . '</a></li>';
                        }

                        $html .= '<li class="divider"></li>';
                        if (auth()->user()->can('sms.delete')) {
                            $html .=
                                '<li>
                                    <a data-href="' . action('Admin\SmsController@destroy', $row->id) . '"
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


        return view('admin.sms.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('sms.create')) {
            abort(403, __('lang.not_authorized'));
        }


        $users = User::select('name', 'phone as mobile')->pluck('name', 'mobile')->toArray();
        $customers = Order::select('customer_name as name', 'phone_number as mobile')->distinct('mobile')->pluck('name', 'mobile')->toArray();

        $user_mobile_number = null;
        if (!empty(request()->employee_id)) {
            $employee = User::find(request()->employee_id);
            if (!empty($employee)) {
                $user_mobile_number = $employee->mobile;
            }
        }
        $customer_mobile_number = null;
        if (!empty(request()->customer_id)) {
            $customer = Order::find(request()->customer_id);
            if (!empty($customer)) {
                $customer_mobile_number = $customer->mobile;
            }
        }

        return view('admin.sms.create')->with(compact(
            'users',
            'customers',
            'user_mobile_number',
            'customer_mobile_number',
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
        if (!auth()->user()->can('sms.create')) {
            abort(403, __('lang.not_authorized'));
        }

        try {
            $mobile_numbers = explode(',', $request->to);

            $data['message'] = urlencode($request->message);
            foreach ($mobile_numbers as $number) {
                $data['mobile_number'] = $number;
                dispatch(new SendSmsJob($data));
            }


            $sms_data['mobile_numbers'] =  $request->to;
            $sms_data['message'] =  $request->message;
            $sms_data['notes'] =  $request->notes;
            $sms_data['created_by'] =  Auth::user()->id;

            Sms::create($sms_data);

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!auth()->user()->can('sms.view')) {
            abort(403, __('lang.not_authorized'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('sms.edit')) {
            abort(403, __('lang.not_authorized'));
        }

        $sms = Sms::find($id);
        $users = User::select('name', 'phone as mobile')->pluck('name', 'mobile')->toArray();
        $customers = Order::select('customer_name as name', 'phone_number as mobile')->distinct('mobile')->pluck('name', 'mobile')->toArray();

        $user_mobile_number = null;
        if (!empty(request()->employee_id)) {
            $employee = User::find(request()->employee_id);
            if (!empty($employee)) {
                $user_mobile_number = $employee->mobile;
            }
        }
        $customer_mobile_number = null;
        if (!empty(request()->customer_id)) {
            $customer = Order::find(request()->customer_id);
            if (!empty($customer)) {
                $customer_mobile_number = $customer->mobile;
            }
        }

        return view('admin.sms.edit')->with(compact(
            'sms',
            'users',
            'customers',
            'user_mobile_number',
            'customer_mobile_number',
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
        if (!auth()->user()->can('sms.edit')) {
            abort(403, __('lang.not_authorized'));
        }

        try {
            $mobile_numbers = explode(',', $request->to);

            $data['message'] = urlencode($request->message);
            foreach ($mobile_numbers as $number) {
                $data['mobile_number'] = $number;
                dispatch(new SendSmsJob($data));
            }


            $sms_data['mobile_numbers'] =  $request->to;
            $sms_data['message'] =  $request->message;
            $sms_data['notes'] =  $request->notes;

            Sms::where('id', $id)->update($sms_data);

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
        if (!auth()->user()->can('sms.delete')) {
            abort(403, __('lang.not_authorized'));
        }

        try {
            Sms::find($id)->delete();
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
     * get the sms setting from storage
     *
     * @return void
     */
    public function getSetting()
    {

        $settings['sms_username'] = System::getProperty('sms_username');
        $settings['sms_password'] = System::getProperty('sms_password');
        $settings['sms_sender_name'] = System::getProperty('sms_sender_name');


        return view('admin.sms.setting')->with(compact(
            'settings'
        ));
    }

    /**
     * save the sms setting from storage
     *
     * @return void
     */
    public function saveSetting(Request $request)
    {

        try {
            $settings['sms_username'] = System::saveProperty('sms_username', $request->sms_username);
            $settings['sms_password'] = System::saveProperty('sms_password', $request->sms_password);
            $settings['sms_sender_name'] = System::saveProperty('sms_sender_name', $request->sms_sender_name);

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

    public function resend($id)
    {
        try {
            $sms = Sms::find($id);


            $mobile_numbers = explode(',', $sms->mobile_numbers);

            $data['message'] = urlencode($sms->message);
            foreach ($mobile_numbers as $number) {
                $data['mobile_number'] = $number;
                dispatch(new SendSmsJob($data));
            }

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
