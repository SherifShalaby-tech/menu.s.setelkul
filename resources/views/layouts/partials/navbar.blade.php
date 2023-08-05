{{-- @php
$menu = \App\Models\Menu::whereNull('parent_id')->where('is_visible', 1)->orderBy('view_order', 'asc')->get();
@endphp
<div class="navbar bg-white w-full">
    <nav class="flex items-center justify-between flex-wrap bg-white p-6">
        <div class="flex items-center flex-shrink-0 text-white lg:ml-32 xl-48 sm:ml-16 lg:mr-24 xl:mr-80  2xl:mr-96">
            <img class="" src="{{asset('images/logo.png')}}" alt="logo">
        </div>
        <div class="block lg:hidden">
            <button
                class="flex items-center px-3 py-2 border rounded text-teal-200 border-teal-400 hover:text-white hover:border-white">
                <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <title>@lang('lang.menu')</title>
                    <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                </svg>
            </button>
        </div>
        <div class="w-full block flex-grow lg:flex lg:items-center lg:w-auto text-secondary text-lg uppercase ">
            <div class="text-sm lg:flex-grow 2xl:ml-80">
                <ul class="flex">
                    @foreach ($menu as $item)
                    @if(!empty($item->sub_menu) && $item->sub_menu->count() > 0)
                    <li class="hoverable hover:text-primary text-secondary">
                        <a href="#"
                            class="relative block mt-4 lg:inline-block lg:mt-0 text-teal-200 mr-7  py-8 outline-none hover:border-secondary justify-center border-transparent border-b-2 hover:border-current">{{$item->name}}</a>
                        <div class="p-6 mega-menu mb-16 sm:mb-0 shadow-xl  bg-white">
                            <div class="container mx-auto w-full flex flex-wrap justify-between mx-2">
                                <ul class="px-4 w-full sm:w-1/2 lg:w-1/4">
                                    @foreach ($item->sub_menu as $sub_menu)

                                    <li>
                                        <a href="{{url($sub_menu->slug)}}"
                                            class="block p-3 hover:text-primary text-gray-700 hover:text-white">{{$sub_menu->name}}</a>
                                    </li>
                                    @endforeach

                                </ul>

                            </div>
                        </div>
                    </li>
                    @else
                    <li class="hover:text-primary text-secondary">
                        <a href="{{url($item->slug)}}"
                            class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 mr-7  py-8 outline-none hover:border-secondary justify-center border-transparent border-b-2 hover:border-current">
                            {{$item->name}}
                        </a>
                    </li>
                    @endif
                    @endforeach

                    <li class="hover:text-primary text-secondary">
                        <a href="#responsive-header"
                            class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 mr-7  py-8 outline-none hover:border-secondary justify-center border-transparent border-b-2 hover:border-current">
                            <i class="fa fa-search"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div> --}}
