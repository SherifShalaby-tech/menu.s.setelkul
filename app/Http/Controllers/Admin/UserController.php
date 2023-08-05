<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
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
        if (!auth()->user()->can('settings.user.view')) {
            abort(403, __('lang.not_authorized'));
        }

        if (request()->ajax()) {

            $users = User::orderBy('name', 'asc');


            $users = $users->select(
                'users.*',

            );

            return DataTables::of($users)
                ->editColumn('date_of_join', '@if(!empty($date_of_join)){{@format_date($date_of_join)}}@endif')
                ->addColumn('image', function ($row) {
                    $image = images_asset($row->getFirstMediaUrl('profile'));
                    if (!empty($image)) {
                        return '<img src="' . $image . '" height="50px" width="50px">';
                    } else {
                        return '<img src="' . images_asset(asset('/uploads/' . session('logo'))) . '" height="50px" width="50px">';
                    }
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

                        if (auth()->user()->can('settings.user.view')) {
                            $html .=
                                '<li><a href="' . action('Admin\UserController@show', $row->id) . '"
                                 class="btn text-primary"><i class="fa fa-eye"></i>
                                ' . __('lang.view') . '</a></li>';
                        }
                        $html .= '<li class="divider"></li>';
                        if (auth()->user()->can('settings.user.edit')) {
                            $html .=
                                '<li><a href="' . action('Admin\UserController@edit', $row->id) . '" class="btn"><i class="fas fa-edit"></i> ' . __('lang.edit') . '</a></li>';
                        }

                        $html .= '<li class="divider"></li>';
                        if (auth()->user()->can('settings.user.delete')) {
                            $html .=
                                '<li>
                                    <a data-href="' . action('Admin\UserController@destroy', $row->id) . '"
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
                    'image',
                    'action',
                ])
                ->make(true);
        }


        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('settings.user.create')) {
            abort(403, __('lang.not_authorized'));
        }

        $modulePermissionArray = User::modulePermissionArray();
        $subModulePermissionArray = User::subModulePermissionArray();

        return view('admin.user.create')->with(compact(
            'modulePermissionArray',
            'subModulePermissionArray',
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
        if (!auth()->user()->can('settings.user.create')) {
            abort(403, __('lang.not_authorized'));
        }

        $this->validate(
            $request,
            [
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users|max:255',
                'password' => 'required|confirmed|max:255|min:6',
            ],
        );

        try {
            $data = $request->except('_token', 'quick_add');

            DB::beginTransaction();
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'date_of_join' => !empty($request->date_of_join) ? $this->commonUtil->uf_date($request->date_of_join) : null,
            ];
            $user = User::create($data);

            if ($request->has('image')) {
                $user->addMedia($request->image)->toMediaCollection('profile');
            }

            //assign permissions to user
            $permissions = $request->permissions;
            $permission_array = [];
            if (!empty($permissions)) {
                foreach ($permissions as $key => $value) {
                    $permission_array[] = $key;
                }

                if (!empty($permission_array)) {
                    $user->syncPermissions($permission_array);
                }
            }

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
        $user = User::find($id);

        return view('admin.user.show')->with(compact(
            'user'
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
        if (!auth()->user()->can('settings.user.edit')) {
            abort(403, __('lang.not_authorized'));
        }


        $user = User::find($id);

        $modulePermissionArray = User::modulePermissionArray();
        $subModulePermissionArray = User::subModulePermissionArray();

        return view('admin.user.edit')->with(compact(
            'modulePermissionArray',
            'subModulePermissionArray',
            'user'
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
        if (!auth()->user()->can('settings.user.edit')) {
            abort(403, __('lang.not_authorized'));
        }

        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
            ['email' => ['required', 'max:255']],
        );

        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'date_of_join' => !empty($request->date_of_join) ? $this->commonUtil->uf_date($request->date_of_join) : null,
            ];

            if (!empty($request->password)) {
                $validated = $request->validate([
                    'password' => 'required|confirmed|max:255',
                ]);
                $data['password'] = Hash::make($request->password);
            }
            DB::beginTransaction();
            $user = User::where('id', $id)->first();
            $user->update($data);

            if ($request->has('image')) {
                $user->clearMediaCollection('profile');
                $user->addMedia($request->image)->toMediaCollection('profile');
            }

            $permissions = $request->permissions;
            $permission_array = [];
            if (!empty($permissions)) {
                foreach ($permissions as $key => $value) {
                    $permission_array[] = $key;
                }

                if (!empty($permission_array)) {
                    $user->syncPermissions($permission_array);
                }
            }

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
        if (!auth()->user()->can('settings.user.delete')) {
            abort(403, __('lang.not_authorized'));
        }


        try {
            $user = User::find($id);
            $user->clearMediaCollection('profile');
            $user->delete();

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
     * get profile
     *
     * @param Request $request
     * @return void
     */
    public function getProfile(Request $request)
    {
        $user = User::find(Auth::user()->id);

        return view('admin.user.profile')->with(compact(
            'user'
        ));
    }

    /**
     * update profile
     *
     * @param Request $request
     * @return void
     */
    public function updateProfile(Request $request)
    {
        $user = User::find(Auth::user()->id);
        if (!empty($request->current_password) || !empty($request->password) || !empty($request->password_confirmation)) {
            $this->validate($request, [
                'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                    if (!\Hash::check($value, $user->password)) {
                        return $fail(__('The current password is incorrect.'));
                    }
                }],
                'password' => 'required|confirmed',
            ]);
        }

        try {

            $user->email = $request->email;
            $user->name = $request->name;
            $user->phone = $request->phone;

            if (!empty($request->password)) {
                $user->password  = Hash::make($request->password);
            }
            $user->save();

            if ($request->has('image')) {
                $user->clearMediaCollection('profile');
                $user->addMedia($request->image)->toMediaCollection('profile');
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

    /**
     * check password
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkPassword($id)
    {
        $user = User::where('id', $id)->first();

        if (Hash::check(request()->value, $user->password)) {
            return ['success' => true];
        }

        return ['success' => false];
    }

    public function getDropdown()
    {
        $user = User::orderBy('name', 'asc')->pluck('name', 'id');
        $user_dp = $this->commonUtil->createDropdownHtml($user, 'Please Select');

        return $user_dp;
    }
}
