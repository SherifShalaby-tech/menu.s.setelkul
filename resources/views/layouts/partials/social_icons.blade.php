<div x-data="{ iconOpen: false }" class="relative">
    <button @click="iconOpen = !iconOpen" x-show="!iconOpen"
        class="absolute right-0 border border-dark rounded-tl-full rounded-bl-full">
        <img src="{{ asset('images/chat-icon.png') }}" alt="chat-icon" class=" w-10 h-10 ">
    </button>
    {{-- <div x-show="iconOpen" @click="iconOpen = false" class="fixed inset-0 h-full w-full z-10">
        </div> --}}
    <div x-show="iconOpen" x-transition x-transition.duration.500ms x-transition:leave.duration.400ms
        x-transition.scale.origin.right
        class="flex flex-row bg-red py-2 pl-4 rounded-tl-full rounded-bl-full border border-dark">


        <a target="_blank" href="{{ App\Models\System::getProperty('instagram') }}"
            class="">
            <img src="{{ asset('images/instagram.png') }}" alt="instagram" class=" w-6 h-6 mr-2 ">
        </a>

        <a target="_blank" href="{{ App\Models\System::getProperty('twitter') }}"
            class="">
            <img src="{{ asset('images/twitter.png') }}" alt="twitter" class=" w-6 h-6 mr-2 ">
        </a>

        <a target="_blank" href="{{ App\Models\System::getProperty('youtube') }}"
            class="">
            <img src="{{ asset('images/youtube.png') }}" alt="youtube" class=" w-6 h-6 mr-2 ">
        </a>

        <a target="_blank" href="https://t.me/{{ App\Models\System::getProperty('telegram') }}"
            class="">
            <img src="{{ asset('images/telegram.png') }}" alt="telegram" class=" w-6 h-6 mr-2 ">
        </a>

        <a target="_blank" href="https://api.whatsapp.com/send?phone={{ App\Models\System::getProperty('whatsapp') }}"
            class="">
            <img src="{{ asset('images/whatsapp.png') }}" alt="whatsapp" class=" w-6 h-6 mr-2 ">
        </a>

        <a target="_blank" href="{{ App\Models\System::getProperty('facebook') }}"
            class="">
            <img src="{{ asset('images/facebook.png') }}" alt="facebook" class=" w-6 h-6 mr-2 ">
        </a>

    </div>
</div>
