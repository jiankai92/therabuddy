<div class="p-5 border-2 border-solid border-white clearfix fixed w-full bg-slate-100">
    <img src="/images/logo/therabuddy-logo-A.png" alt="avatar"
         class="float-left max-w-[60px]"/>
    <div class="float-left pl-2.5 mt-1.5">
        <div class="chat-with font-bold text-base">Therabuddy</div>
        <div class="chat-num-messages">Talk to me about anything &#x1f60a;</div>
    </div>
    <i class="fa fa-star"></i>
    @if (Route::has('login'))
        <div class="p-6 text-right sm:top-0 sm:right-0">
            @auth
                <a href="{{ url('/dashboard') }}"
                   class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
            @else
                <a href="{{ route('login') }}"
                   class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log
                    in</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                @endif
            @endauth
        </div>
    @endif
</div> <!-- end chat-header -->
