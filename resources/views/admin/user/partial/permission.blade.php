<table class="table" id="permission_table">
    <thead>
        <tr>
            <th class="">
                @lang('lang.module') {!! Form::checkbox('all_module_check_all', 1, false, ['class' => 'all_module_check_all']) !!}
            </th>
            <th>
                @lang('lang.sub_module')
            </th>
            <th class="">
                @lang('lang.select_all')
            </th>
            <th class="">
                @lang('lang.view')
            </th>
            <th class="">
                @lang('lang.create')
            </th>
            <th class="">
                @lang('lang.edit')
            </th>
            <th class="">
                @lang('lang.delete')
            </th>
        </tr>

    <tbody>
        @foreach ($modulePermissionArray as $key_module => $moudle)
            <div>
                <tr class="module_permission" data-moudle="{{ $key_module }}">
                    <td class="">{{ $moudle }}</td>
                    @if (empty($subModulePermissionArray[$key_module]))
                        <td class=""></td>
                        <td class="">
                            {!! Form::checkbox('checked_all', 1, false, ['class' => 'checked_all']) !!}
                        </td>
                        @php
                            $view_permission = $key_module . '.view';
                            $create_permission = $key_module . '.create';
                            $edit_permission = $key_module . '.edit';
                            $delete_permission = $key_module . '.delete';
                        @endphp
                        @if (Spatie\Permission\Models\Permission::where('name', $view_permission)->first())
                            <td class="">
                                {!! Form::checkbox('permissions[' . $view_permission . ']', 1, !empty($user) && !empty($user->hasPermissionTo($view_permission)) ? true : false, ['class' => 'check_box']) !!}
                            </td>
                        @endif
                        @if (Spatie\Permission\Models\Permission::where('name', $create_permission)->first())
                            <td class="">
                                {!! Form::checkbox('permissions[' . $create_permission . ']', 1, !empty($user) && !empty($user->hasPermissionTo($create_permission)) ? true : false, ['class' => 'check_box']) !!}
                            </td>
                        @endif
                        @if (Spatie\Permission\Models\Permission::where('name', $edit_permission)->first())
                            <td class="">
                                {!! Form::checkbox('permissions[' . $edit_permission . ']', 1, !empty($user) && !empty($user->hasPermissionTo($edit_permission)) ? true : false, ['class' => 'check_box']) !!}
                            </td>
                        @endif
                        @if (Spatie\Permission\Models\Permission::where('name', $delete_permission)->first())
                            <td class="">
                                {!! Form::checkbox('permissions[' . $delete_permission . ']', 1, !empty($user) && !empty($user->hasPermissionTo($delete_permission)) ? true : false, ['class' => 'check_box']) !!}
                            </td>
                        @endif
                    @else
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    @endif
                </tr>
                @if (!empty($subModulePermissionArray[$key_module]))
                    @php
                        $sub_module_permission_array = $subModulePermissionArray[$key_module];
                    @endphp
                    @foreach ($sub_module_permission_array as $key_sub_module => $sub_module)
                        <tr class="sub_module_permission_{{ $key_module }}">
                            <td class=""></td>
                            <td>{{ $sub_module }}</td>
                            <td class="">
                                {!! Form::checkbox('checked_all', 1, false, ['class' => 'checked_all']) !!}
                            </td>
                            @php
                                $view_permission = $key_module . '.' . $key_sub_module . '.view';
                                $create_permission = $key_module . '.' . $key_sub_module . '.create';
                                $edit_permission = $key_module . '.' . $key_sub_module . '.edit';
                                $delete_permission = $key_module . '.' . $key_sub_module . '.delete';
                            @endphp
                            @if (Spatie\Permission\Models\Permission::where('name', $view_permission)->first())
                                <td class="">
                                    {!! Form::checkbox('permissions[' . $view_permission . ']', 1, !empty($user) && !empty($user->hasPermissionTo($view_permission)) ? true : false, ['class' => 'check_box']) !!}
                                </td>
                            @endif
                            @if (Spatie\Permission\Models\Permission::where('name', $create_permission)->first())
                                <td class="">
                                    {!! Form::checkbox('permissions[' . $create_permission . ']', 1, !empty($user) && !empty($user->hasPermissionTo($create_permission)) ? true : false, ['class' => 'check_box']) !!}
                                </td>
                            @endif
                            @if (Spatie\Permission\Models\Permission::where('name', $edit_permission)->first())
                                <td class="">
                                    {!! Form::checkbox('permissions[' . $edit_permission . ']', 1, !empty($user) && !empty($user->hasPermissionTo($edit_permission)) ? true : false, ['class' => 'check_box']) !!}
                                </td>
                            @endif
                            @if (Spatie\Permission\Models\Permission::where('name', $delete_permission)->first())
                                <td class="">
                                    @if ($delete_permission != 'sale.pos.delete' && $delete_permission != 'sale.sale.delete' && $delete_permission != 'stock.add_stock.delete')
                                        {!! Form::checkbox('permissions[' . $delete_permission . ']', 1, !empty($user) && !empty($user->hasPermissionTo($delete_permission)) ? true : false, ['class' => 'check_box']) !!}
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @endif
            </div>
        @endforeach
    </tbody>
    </thead>
</table>
