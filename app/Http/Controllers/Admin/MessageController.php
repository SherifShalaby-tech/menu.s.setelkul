<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendMessagesJob;
use App\Models\Message;
use App\Models\System;
use App\Models\User;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class MessageController extends Controller
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
        if (!auth()->user()->can('message.view')) {
            abort(403, __('lang.not_authorized'));
        }

        if (request()->ajax()) {

            $messages = Message::leftjoin('users', 'messages.created_by', 'users.id')
                ->orderBy('created_at', 'asc');


            $messages = $messages->select(
                'messages.*',

            );

            return DataTables::of($messages)
                ->editColumn('created_at', '{{@format_datetime($created_at)}}')
                ->editColumn('body', '{!!$body!!}')
                ->editColumn('attachments', function ($row) {
                    $html = '';
                    foreach ($row->attachments as $item) {
                        $html .= '<a target="_blank" href="' . asset($item) . '">' . str_replace('/emails/', '', $item) . '</a>
                                <br>';
                    }

                    return $html;
                })
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

                        if (auth()->user()->can('message.view')) {
                            $html .=
                                '<li><a href="' . action('Admin\MessageController@show', $row->id) . '" class="btn"
                                target="_blank"><i class="fas fa-eye"></i> ' . __('lang.view') . '</a></li>';
                        }
                        $html .= '<li class="divider"></li>';

                        $html .=
                            '<li><a href="' . action('Admin\MessageController@resend', $row->id) . '"
                                 class="btn text-primary"><i class="fa fa-paper-plane"></i>
                                ' . __('lang.resend') . '</a></li>';


                        $html .= '<li class="divider"></li>';
                        if (auth()->user()->can('message.delete')) {
                            $html .=
                                '<li>
                                    <a data-href="' . action('Admin\MessageController@destroy', $row->id) . '"
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
                    'body',
                    'attachments',
                    'action',
                ])
                ->make(true);
        }

        return view('admin.message.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('message.create')) {
            abort(403, __('lang.not_authorized'));
        }

        $users = User::pluck('name', 'email');
        $email = null;
        if (!empty(request()->user_id)) {
            $user = User::where('users.id', request()->user_id)->first();
            if (!empty($user)) {
                $email = $user->email;
            }
        }

        return view('admin.message.create')->with(compact(
            'users',
            'email'
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
        if (!auth()->user()->can('message.create')) {
            abort(403, __('lang.not_authorized'));
        }

        try {
            $emails = explode(',', $request->to);
            $data["subject"] = $request->subject;
            $data["body"] = $request->body;
            $files = [];
            $attachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $name = $file->getClientOriginalName();
                    $file->move(public_path() . '/emails/', $name);
                    $files[] = public_path() . '/emails/' . $name;
                    $attachments[] = '/emails/' . $name;
                }
            }

            $from = System::getProperty('system_email');

            foreach ($emails as $email) {
                $data["email"] = trim($email);

                dispatch(new SendMessagesJob($data, $files, $from));
            }
            $email_data['emails'] =  $request->to;
            $email_data['subject'] =  $request->subject;
            $email_data['body'] =  $request->body;
            $email_data['attachments'] =  $attachments;
            $email_data['notes'] =  $request->notes;
            $email_data['created_by'] =  Auth::user()->id;

            Message::create($email_data);


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
        if (!auth()->user()->can('message.view')) {
            abort(403, __('lang.not_authorized'));
        }

        $message = Message::find($id);

        return view('admin.message.show')->with(compact(
            'message',
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
        if (!auth()->user()->can('message.edit')) {
            abort(403, __('lang.not_authorized'));
        }

        $message = Message::find($id);
        $users = User::pluck('name', 'email');

        return view('admin.message.edit')->with(compact(
            'message',
            'users'
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
        if (!auth()->user()->can('message.edit')) {
            abort(403, __('lang.not_authorized'));
        }

        try {
            $edit_email = Message::find($id);
            $emails = explode(',', $request->to);
            $data["subject"] = $request->subject;
            $data["body"] = $request->body;
            $files = [];
            $attachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $name = $file->getClientOriginalName();
                    $file->move(public_path() . '/emails/', $name);
                    $files[] = public_path() . '/emails/' . $name;
                    $attachments[] = '/emails/' . $name;
                }
            } else {
                $atts = $edit_email->attachments;
                foreach ($atts as $att) {
                    $files[] = public_path() . $att;
                    $attachments[] = $att;
                }
            }
            $from = System::getProperty('system_email');

            foreach ($emails as $email) {
                $data["email"] = trim($email);

                dispatch(new SendMessagesJob($data, $files, $from));
            }
            $email_data['emails'] =  $request->to;
            $email_data['subject'] =  $request->subject;
            $email_data['body'] =  $request->body;
            $email_data['attachments'] =  $attachments;
            $email_data['notes'] =  $request->notes;
            $email_data['created_by'] =  Auth::user()->id;

            $edit_email->update($email_data);


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
        if (!auth()->user()->can('message.edit')) {
            abort(403, __('lang.not_authorized'));
        }

        try {
            Message::find($id)->delete();
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

        $settings['system_email'] = System::getProperty('system_email');


        return view('admin.message.setting')->with(compact(
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
            $settings['system_email'] = System::saveProperty('system_email', $request->system_email);
            $data['sender_email'] = System::getProperty('system_email');
            $this->commonUtil->addSyncDataWithPos('System', $settings['system_email'], $data, 'POST', 'setting');

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
            $message = Message::find($id);


            $emails = explode(',', $message->emails);
            $data["subject"] = $message->subject;
            $data["body"] = $message->body;
            $files = [];
            $attachments = [];

            $atts = $message->attachments;
            foreach ($atts as $att) {
                $files[] = public_path() . $att;
                $attachments[] = $att;
            }

            $from = System::getProperty('system_email');
            foreach ($emails as $email) {
                $data["email"] = trim($email);

                dispatch(new SendMessagesJob($data, $files, $from));
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
