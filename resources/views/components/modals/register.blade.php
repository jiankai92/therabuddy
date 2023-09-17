<x-modal name="register" focusable>
    <!-- Session Status -->
    <div class="w-full mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <div class="flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <x-application-logo class="w-20 h-20 fill-current text-gray-500"/>
        </div>
        <x-auth-session-status class="mb-4" :status="session('status')"/>
        <form method="POST" x-data="modalRegisterForm()" @submit.prevent="register">
        @csrf
        <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" 
                              x-model="formData.name"
                              required autofocus autocomplete="name"/>
                <x-input-error-ajax fields="formErrors.name" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                              x-model="formData.email"
                              required autocomplete="username"/>
                <x-input-error-ajax fields="formErrors.email" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />

                <x-text-input id="password" class="block mt-1 w-full"
                              type="password"
                              name="password"
                              x-model="formData.password"
                              required autocomplete="new-password" />
                <x-input-error-ajax fields="formErrors.password" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-text-input id="password_confirmation" class="block mt-1 w-full"
                              type="password"
                              name="password_confirmation"
                              x-model="formData.password_confirmation"
                              required autocomplete="new-password" />
                <x-input-error-ajax fields="formErrors.password_confirmation" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer" @click.prevent="$dispatch('close');$dispatch('open-modal', 'login')">
                    {{ __('Already registered?') }}
                </a>
                <x-secondary-button class="ml-3" x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>
                <x-primary-button class="ml-4">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-modal>
@push('scripts2')
    <script>
        function modalRegisterForm() {
            return {
                formData: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                },
                formErrors: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                },
                register() {
                    this.clearError();
                    axios.post('{{route('modal-register')}}', this.formData)
                        .then((response) => {
                            window.location = '/register-ajax/redirect'
                        }, (error) => {
                            if (error.response.status === 422) {
                                this.formErrors = error.response.data.errors
                            }
                        })
                        .catch()
                },
                clearError() {
                    this.formErrors = {
                        name: '',
                        email: '',
                        password: '',
                        password_confirmation: '',
                    }
                }
            };
        }
    </script>
@endpush