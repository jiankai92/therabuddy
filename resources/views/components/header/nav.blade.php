<div class="p-6 text-right hidden sm:block">
    @auth
        <a href="{{ url('/dashboard') }}"
           class="font-bold hover:text-tb-primary dark:hover:text-tb-primary focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
        <form method="POST" class="inline-flex" action="{{ route('logout') }}">
            @csrf
            <a class="font-bold hover:text-tb-primary dark:hover:text-tb-primary focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500 ml-2 cursor-pointer"
               onclick="event.preventDefault();this.closest('form').submit();">Logout</a>
        </form>
    @else
        <a x-data @click.prevent="$dispatch('open-modal', 'login')"
           class="font-bold hover:text-tb-primary dark:hover:text-tb-primary focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500 cursor-pointer">
            Log in
        </a>

        @if (Route::has('register'))
            <a x-data @click.prevent="$dispatch('open-modal', 'register')"
               class="ml-4 font-bold text-base dark:hover:text-tb-primary focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500 cursor-pointer">Register</a>
        @endif
    @endauth
</div>