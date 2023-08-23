<div class="p-2 border-2 border-solid border-white clearfix fixed w-full bg-slate-100">
    <img src="/images/logo/therabuddy-logo-A.png" alt="avatar"
         class="float-left max-w-[60px]"/>
    <div class="float-left pl-2.5 mt-1.5">
        <div class="chat-with font-bold text-base">Therabuddy</div>
        <div class="chat-num-messages">Talk to me about anything &#x1f60a;</div>
    </div>
    @if (Route::has('login'))
        <x-header.nav></x-header.nav>
        <x-header.nav-mobile></x-header.nav-mobile>
    @endif
</div> <!-- end chat-header -->
<x-modals.register></x-modals.register>
<x-modals.login></x-modals.login>
