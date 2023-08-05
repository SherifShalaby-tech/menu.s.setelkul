<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiningRoom;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class DiningRoomController extends Controller
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
        if (!auth()->user()->can('settings.dining_room.view')) {
            abort(403, __('lang.not_authorized'));
        }

        if (request()->ajax()) {

            $dining_rooms = DiningRoom::orderBy('name', 'asc');


            $dining_rooms = $dining_rooms->select(
                'dining_rooms.*',

            );

            return DataTables::of($dining_rooms)
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
                        if (auth()->user()->can('settings.dining_room.edit')) {
                            $html .=
                                '<li><a data-href="' . action('Admin\DiningRoomController@edit', $row->id) . '" data-container=".view_modal" class="btn btn-modal"
                            target="_blank"><i class="fas fa-edit"></i> ' . __('lang.edit') . '</a></li>';
                        }

                        $html .= '<li class="divider"></li>';
                        if (auth()->user()->can('settings.dining_room.delete')) {
                            $html .=
                                '<li>
                            <a data-href="' . action('Admin\DiningRoomController@destroy', $row->id) . '"
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


        return view('admin.dining_room.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('settings.dining_room.create')) {
            abort(403, __('lang.not_authorized'));
        }


        $quick_add = request()->quick_add ?? null;

        $dining_rooms = DiningRoom::orderBy('name', 'asc')->pluck('name', 'id');

        return view('admin.dining_room.create')->with(compact(
            'quick_add',
            'dining_rooms'
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
        if (!auth()->user()->can('settings.dining_room.create')) {
            abort(403, __('lang.not_authorized'));
        }

        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
        );

        try {
            $data = $request->except('_token', 'quick_add');

            DB::beginTransaction();
            $dining_room = DiningRoom::create($data);

            $dining_room_id = $dining_room->id;

            $this->commonUtil->addSyncDataWithPos('DiningRoom', $dining_room, $data, 'POST', 'dining-room');

            DB::commit();
            $output = [
                'success' => true,
                'dining_room_id' => $dining_room_id,
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
        if (!auth()->user()->can('settings.dining_room.edit')) {
            abort(403, __('lang.not_authorized'));
        }


        $dining_room = DiningRoom::find($id);

        return view('admin.dining_room.edit')->with(compact(
            'dining_room'
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
        if (!auth()->user()->can('settings.dining_room.edit')) {
            abort(403, __('lang.not_authorized'));
        }


        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
        );

        try {
            $data = $request->except('_token', '_method');

            DB::beginTransaction();
            $dining_room = DiningRoom::where('id', $id)->first();
            $dining_room->update($data);

            $this->commonUtil->addSyncDataWithPos('DiningRoom', $dining_room, $data, 'PUT', 'dining-room');
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
        if (!auth()->user()->can('settings.dining_room.delete')) {
            abort(403, __('lang.not_authorized'));
        }


        try {
            $dining_room = DiningRoom::find($id);

            DB::beginTransaction();
            $this->commonUtil->addSyncDataWithPos('DiningRoom', $dining_room, null, 'DELETE', 'dining-room');
            $dining_room->delete();
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
        $dining_room = DiningRoom::orderBy('name', 'asc')->pluck('name', 'id');
        $dining_room_dp = $this->commonUtil->createDropdownHtml($dining_room, 'Please Select');

        return $dining_room_dp;
    }
}
