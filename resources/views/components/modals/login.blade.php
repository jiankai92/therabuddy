<x-modal name="login" focusable>
    <!-- Session Status -->
    <div class="w-full mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <div class="flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <x-application-logo class="w-20 h-20 fill-current text-gray-500"/>
        </div>
        <x-auth-session-status class="mb-4" :status="session('status')"/>

        <form method="POST" x-data="modalLoginForm()" @submit.prevent="modalLogin">
        @csrf
        <!-- Email Address -->
            <div>
                <x-input-label for="emailLogin" :value="__('Email')"/>
                <x-text-input id="emailLogin" class="block mt-1 w-full" type="email" name="emailLogin" :value="old('email')"
                              required autofocus autocomplete="username" x-model="formData.emailLogin"/>
                <template x-if="formErrors.email && formErrors.email.length > 0">
                    <template x-for="item in formErrors.email">
                        <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 space-y-1 mt-2']) }}>
                            <li x-text="item"></li>
                        </ul>
                    </template>
                </template>
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="passwordLogin" :value="__('Password')"/>

                <x-text-input id="passwordLogin" class="block mt-1 w-full"
                              type="password"
                              name="passwordLogin"
                              x-model="formData.passwordLogin"
                              required autocomplete="current-password"/>
                <template x-if="formErrors.password && formErrors.password.length > 0">
                    <template x-for="item in formErrors.password">
                        <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 space-y-1 mt-2']) }}>
                            <li x-text="item"></li>
                        </ul>
                    </template>
                </template>
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                           name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                       href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
                <x-secondary-button class="ml-3" x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>
                <x-primary-button class="ml-3">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-modal>
@push('scripts2')
    <script>
        function modalLoginForm() {
            return {
                formData: {
                    emailLogin: '',
                    passwordLogin: ''
                },
                formErrors: {
                    email: '',
                    password: ''
                },
                modalLogin() {
                    let data = {
                        email: this.formData.emailLogin,
                        password: this.formData.passwordLogin
                    }
                    axios.post('{{route('login')}}', data)
                        .then((response) => {
                            window.location = '/login-ajax/redirect'
                        }, (error) => {
                            if (error.response.status === 422) {
                                this.formErrors = error.response.data.errors
                            }
                        })
                        .catch()
                },
            };
        }
    </script>
@endpush