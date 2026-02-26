@use('App\Enums\Person\AuthenticationMethod')

<div class="relative"> {{-- This required for table overflow scrolling --}}
    <fieldset class="fieldset"
              {{-- Binding authenticationMethods to Alpine, it will be re-used in the modal.
                Note that it's necessary for modal to work properly --}}
              x-data="{
                  authenticationMethods: $wire.entangle('authenticationMethods'),
                  openModal: false,
                  modalAuthenticationMethod: new AuthenticationMethod(),
                  newAuthenticationMethod: false,
                  item: 0
              }"
    >
        <legend class="legend">
            <h2>{{ __('patients.authentication_methods') }}</h2>
        </legend>

        <table class="table-input w-inherit">
            <thead class="thead-input">
            <tr>
                <th scope="col" class="th-input">{{ __('forms.type') }}</th>
                <th scope="col" class="th-input">{{ __('forms.actions') }}</th>
            </tr>
            </thead>
            <tbody>
            <template x-for="(authenticationMethod, index) in authenticationMethods">
                <tr>
                    <td class="td-input"
                        x-data="{ authLabels: @js(AuthenticationMethod::options()) }"
                        x-text="authLabels[authenticationMethod.type] ?? authenticationMethod.type"
                    >
                    </td>
                    <td class="td-input">
                        {{-- That all that is needed for the dropdown --}}
                        <div x-data="{
                                 openDropdown: false,
                                 toggle() {
                                     if (this.openDropdown) {
                                         return this.close();
                                     }

                                     this.$refs.button.focus();

                                     this.openDropdown = true;
                                 },
                                 close(focusAfter) {
                                     if (!this.openDropdown) return;

                                     this.openDropdown = false;

                                     focusAfter && focusAfter.focus()
                                 }
                             }"
                             @keydown.escape.prevent.stop="close($refs.button)"
                             @focusin.window="!$refs.panel.contains($event.target) && close()"
                             x-id="['dropdown-button']"
                             class="relative"
                        >
                            {{-- Dropdown Button --}}
                            <button x-ref="button"
                                    @click="toggle()"
                                    :aria-expanded="openDropdown"
                                    :aria-controls="$id('dropdown-button')"
                                    type="button"
                                    class="cursor-pointer"
                            >
                                @icon('edit-user-outline', 'w-6 h-6 text-gray-800 dark:text-gray-200')
                            </button>

                            {{-- Dropdown Panel --}}
                            <div class="absolute" style="left: 50%"> {{-- Center a dropdown panel --}}
                                <div x-ref="panel"
                                     x-show="openDropdown"
                                     x-transition.origin.top.left
                                     @click.outside="close($refs.button)"
                                     :id="$id('dropdown-button')"
                                     x-cloak
                                     class="dropdown-panel relative"
                                     style="left: -50%" {{-- Center a dropdown panel --}}
                                >

                                    <button @click.prevent="
                                                    openModal = true; {{-- Open the modal --}}
                                                    item = index; {{-- Identify the item we are corrently editing --}}
                                                    {{-- Replace the previous authenticationMethod with the current, don't assign object directly (modalAuthenticationMethod = authenticationMethod) to avoid reactiveness --}}
                                                    modalAuthenticationMethod = new AuthenticationMethod(authenticationMethod);
                                                    newAuthenticationMethod = false; {{-- This authenticationMethod is already created --}}
                                                "
                                            class="dropdown-button"
                                    >
                                        {{ __('forms.edit') }}
                                    </button>

                                    <button class="dropdown-button dropdown-delete"
                                            @click.prevent="$wire.deactivateAuthMethod(authenticationMethod)"
                                    >
                                        {{ __('forms.delete') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </template>
            </tbody>
        </table>

        <div>
            {{-- Button to trigger the modal --}}
            <button @click.prevent="
                        openModal = true; {{-- Open the Modal --}}
                        newAuthenticationMethod = true; {{-- We are adding a new authenticationMethod --}}
                        modalAuthenticationMethod = new AuthenticationMethod(); {{-- Replace the data of the previous authenticationMethod with a new one--}}
                    "
                    class="item-add my-5"
            >
                {{ __('forms.add') }}
            </button>

            {{-- Modal --}}
            <template x-teleport="body"> {{-- This moves the modal at the end of the body tag --}}
                <div x-show="openModal"
                     style="display: none"
                     @keydown.escape.prevent.stop="openModal = false"
                     role="dialog"
                     aria-modal="true"
                     x-id="['modal-title']"
                     :aria-labelledby="$id('modal-title')" {{-- This associates the modal with unique ID --}}
                     class="modal"
                >

                    {{-- Overlay --}}
                    <div x-show="openModal" x-transition.opacity class="fixed inset-0 bg-black/25"></div>

                    {{-- Panel --}}
                    <div x-show="openModal"
                         x-transition
                         @click="openModal = false"
                         class="relative flex min-h-screen items-center justify-center p-4"
                    >
                        <div @click.stop
                             x-trap.noscroll.inert="openModal"
                             class="modal-content h-fit w-full lg:max-w-4xl"
                        >
                            {{-- Title --}}
                            <h3 class="modal-header" :id="$id('modal-title')">{{ __('forms.auth_method') }}</h3>

                            {{-- Content --}}
                            <form>
                                <div class="form-row-modal">
                                    {{-- Type --}}
                                    <div>
                                        <label for="authenticationMethodType" class="label-modal">
                                            {{ __('forms.type') }}
                                        </label>
                                        <select x-model="modalAuthenticationMethod.type"
                                                id="authenticationMethodType"
                                                class="input-modal"
                                                type="text"
                                                required
                                        >
                                            <option selected value="">{{ __('forms.select') }} *</option>
                                            @foreach(AuthenticationMethod::cases() as $authenticationMethodType)
                                                <option value="{{ $authenticationMethodType->value }}">
                                                    {{ $authenticationMethodType->label() }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group group !mb-0 self-end">
                                        <button class="button-primary"
                                                @click.prevent="$wire.createAuthMethod(modalAuthenticationMethod)"
                                                :disabled="!modalAuthenticationMethod.type.trim()"
                                        >
                                            {{ __('forms.create') }}
                                        </button>
                                    </div>
                                </div>

                                {{-- If type is OTP --}}
                                <div x-show="modalAuthenticationMethod.type === '{{ AuthenticationMethod::OTP }}'">
                                    <div class="form-row-modal">
                                        <div class="form-group group">
                                            <label for="phoneNumber" class="label-modal">
                                                {{ __('forms.phone_number') }}
                                            </label>
                                            <input x-model="modalAuthenticationMethod.phoneNumber"
                                                   type="tel"
                                                   x-mask="+380999999999"
                                                   name="phoneNumber"
                                                   id="phoneNumber"
                                                   class="input-modal"
                                                   placeholder=" "
                                                   required
                                                   autocomplete="off"
                                            />
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <button type="button"
                                                    wire:click.prevent="resendSms"
                                                    @click="resetCooldown(); startCooldown()"
                                                    x-data="{
                                                        cooldown: 60,
                                                        interval: null,
                                                        modalOpened: false,
                                                        startCooldown() {
                                                            if (this.interval) {
                                                                clearInterval(this.interval);
                                                                this.interval = null;
                                                            }

                                                            this.cooldown = 60;

                                                            if (this.cooldown > 0) {
                                                                this.interval = setInterval(() => {
                                                                    if (this.cooldown > 0) {
                                                                        this.cooldown--;
                                                                    } else {
                                                                        clearInterval(this.interval);
                                                                        this.interval = null;
                                                                    }
                                                                }, 1000);
                                                            }
                                                        },
                                                        resetCooldown() {
                                                            this.cooldown = 60;
                                                            if (this.interval) {
                                                                clearInterval(this.interval);
                                                                this.interval = null;
                                                            }
                                                        }
                                                    }"
                                                    x-init=""
                                                    x-effect="if (!modalOpened) { modalOpened = true; startCooldown(); }"
                                                    :disabled="cooldown > 0"
                                                    :class="{ 'cursor-not-allowed': cooldown > 0 }"
                                                    class="button-minor gap-2"
                                            >
                                                @icon('mail', 'w-4 h-4 text-gray-800 dark:text-white')
                                                <span
                                                    x-text="cooldown > 0 ? `Відправити ще раз (через ${cooldown} с)` : 'Відправити ще раз'">
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Action buttons --}}
                                <div class="mt-6 flex justify-between space-x-2">
                                    <button type="button"
                                            @click="openModal = false"
                                            class="button-minor"
                                    >
                                        {{ __('forms.cancel') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </fieldset>
</div>

<script>
    /**
     * Representation of the user's authentication method.
     */
    class AuthenticationMethod {
        type = '';
        phoneNumber = '';

        constructor(obj = null) {
            if (obj) {
                Object.assign(this, obj);
            }
        }
    }
</script>
