@php
    $hasZipError = $errors->has('address.zip');
    $hasApartmentError = $errors->has('address.apartment');
    $hasBuildingError = $errors->has('address.building');
    $hasAreaError = $errors->has('address.area');
    $hasSettlementTypeError = $errors->has('address.settlementType');
    $hasRegionError = $errors->has('address.region');
    $hasSettlementError = $errors->has('address.settlement');
    $hasStreetTypeError = $errors->has('address.streetType');
    $hasStreetError = $errors->has('address.street');

    natcasesort($dictionaries['STREET_TYPE']);
@endphp

<div
    x-data="{
        searchStartLength: 2,
        address: $wire.entangle('address'),
        readonly: {{ $readonly ? 'true' : 'false' }},
        selecting: false,
        clearStreet() {
            this.address.building = '';
            this.address.apartment = '';
            this.address.zip = '';
        },
        clearSettlement() {
            this.address.streetType = '';
            this.address.street = '';
            this.clearStreet();
        },
        clearRegion() {
            this.address.settlementType = '';
            this.address.settlement = '';
            this.address.settlementId = '';
            this.clearSettlement();
        },
        clearArea() {
            this.address.region = '';
            this.clearRegion();
        },
        init() {
            this.$watch('address.area', value => {
                this.clearArea();
            });
            this.$watch('address.region', value => {
                if (!this.selecting) {
                    return;
                }

                this.clearRegion();
            });
            this.$watch('address.settlement', value => {
                if (this.address.area === 'М.КИЇВ') {
                    this.address.settlementType = 'CITY';
                    this.address.settlement = 'Київ';
                    this.address.settlementId = 'adaa4abf-f530-461c-bcbf-a0ac210d955b';

                    return;
                }

                if (!this.selecting) {
                    return;
                }

                this.clearSettlement();
            });
            this.$watch('address.street', value => {
                if (!this.selecting) {
                    return;
                }

                this.clearStreet();
            });
        }
    }"
    x-init="init()"
    class="{{ $class }}"
>
    {{-- AREA --}}
    <div class="form-group group !z-[28]">
        <select
            x-model.live="address.area"
            required
            id="addressArea"
            @blur="selecting=false"
            @change="address.settlement=null" {{-- This need to properly set a Kyiv area --}}
            aria-describedby="{{ $hasAreaError ? 'addressAreaErrorHelp' : '' }}"
            class="input-select text-gray-800 {{ $hasAreaError ? 'input-error border-red-500 focus:border-red-500' : ''}} peer"
            :disabled="readonly"
        >
            <option value="_placeholder_" hidden>-- {{ __('forms.select') }} --</option>

            @forelse ($regions as $regionItem)
                <option value="{{ $regionItem['name'] }}">
                    {{ $regionItem['name'] }}
                </option>
            @empty
            @endforelse
        </select>

        @if($hasAreaError)
            <p id="addressAreaErrorHelp" class="text-error">
                {{ $errors->first('address.area') }}
            </p>
        @endif

        <label for="addressArea" class="label z-10">
            {{ __('forms.area') }}
        </label>
    </div>

    {{-- REGION --}}
    <div class="form-group group !z-[27]"
        {{-- @mouseleave="timeout = setTimeout(() => { showTo = false }, 800)" --}}
        x-data="{
            showTo: false,
            districts: $wire.entangle('districts'),
            initialized: false,
            init() {
                // tracking changes of region, but skip first time
                this.$watch('address.region', value => {
                    if (!this.initialized) {
                        this.initialized = true;

                        return; // do nothing at first time
                    }

                    if (this.selecting || address.area === 'М.КИЇВ') return;

                    if (!value || value.length < searchStartLength) {
                        this.showTo = false;
                        return;
                    }

                    $wire.call('updateRegion', 'address', 'districts', value).then(() => this.showTo = true);
                });

                // when Livewire returned districts — decide to show dropdown or not
                this.$watch('districts', value => {
                    if (this.selecting) {
                        return;
                    }

                    this.showTo = Array.isArray(value) && value.length > 0;
                });
            }
        }"
        x-init="init()"
    >
        <input
            x-model.debounce.400ms="address.region"
            @keydown.escape="showTo = false"
            @change="showTo = false"
            @blur="selecting = false; districts = []"
            type="text"
            placeholder=" "
            id="addressRegion"
            autocomplete="off"
            aria-describedby="{{ $hasRegionError ? 'addressRegionErrorHelp' : '' }}"
            class="input {{ $hasRegionError ? 'input-error border-red-500 focus:border-red-500' : ''}} peer"
            :disabled="!address.area || address.area === 'М.КИЇВ' || readonly"
        />

        <div x-show="showTo" x-cloak>
            <div
                x-on:click.away="showTo = false"
                x-transition
                class="absolute left-0 right-0 top-full bg-white border border-gray-300 rounded-bl-md rounded-br-md shadow-lg dark:bg-gray-800 dark:border-gray-500"
            >
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownHoverButton">
                    <template x-for="district in districts" :key="district.id">
                        <li
                            x-on:mousedown.stop="
                                selecting = true;
                                showTo = false;

                                address.region = district.name.replace(/'/g, '\'');
                            "
                            class="cursor-pointer px-4 py-2 hover:bg-gray-100 dark:hover:text-gray-200 dark:hover:bg-blue-800"
                        >
                            <span x-text="district.name"></span>
                        </li>
                    </template>

                    <div x-show="!districts || (Array.isArray(districts) && districts.length === 0)" x-cloak>
                        <li class="cursor-default px-4 py-2">
                            {{ __('forms.nothing_found') }}
                        </li>
                    </div>
                </ul>
            </div>
        </div>

        @if($hasRegionError)
            <p id="addressRegionErrorHelp" class="text-error">
                {{ $errors->first('address.region') }}
            </p>
        @endif

        <label for="addressRegion" class="label z-10">
            {{ __('forms.region') }}
        </label>
    </div>

    {{-- TYPE --}}
    <div class="form-group group !z-[26]">
        <select
            {{-- wire:model.live="address.settlementType" --}}
            x-model="address.settlementType"
            required
            @blur="selecting=false"
            id="addressSettlementType"
            aria-describedby="{{ $hasSettlementTypeError ? 'addressSettlementTypeErrorHelp' : '' }}"
            class="input-select text-gray-800 {{ $hasSettlementTypeError ? 'input-error border-red-500 focus:border-red-500' : ''}} peer"
            :disabled="!address.region || readonly"
        >
            <option value="_placeholder_" selected hidden>-- {{ __('forms.select') }} --</option>

            @isset($dictionaries['SETTLEMENT_TYPE'])
                @foreach($dictionaries['SETTLEMENT_TYPE'] as $key => $type)
                    <option class="normal-case"
                            {{ isset($address['settlementType']) && $address['settlementType'] === $key ? 'selected': ''}}
                            value="{{ $key }}"
                    >
                        {{ $type }}
                    </option>
                @endforeach
            @endif
        </select>

        @if($hasSettlementTypeError)
            <p id="addressSettlementTypeErrorHelp" class="text-error">
                {{ $errors->first('address.settlementType') }}
            </p>
        @endif

        <label for="addressSettlementType" class="label z-10">
            {{ __('forms.settlement_type') }}
        </label>
    </div>

    {{-- SETTLEMENT --}}
    <div class="form-group group !z-[25]"
        {{-- @mouseleave="timeout = setTimeout(() => { showTo = false }, 800)" --}}
        x-data="{
            showTo: false,
            settlements: $wire.entangle('settlements'),
            initialized: false,
            init() {
                this.$watch('address.settlement', value => {
                    // tracking changes of settlement, but skip first time
                    if (!this.initialized) {
                        this.initialized = true;

                        return; // do nothing at first time
                    }

                    if (this.selecting || address.area === 'М.КИЇВ') return;

                    if (!value || value.length < searchStartLength) {
                        this.showTo = false;
                        return;
                    }

                    $wire.call('updateSettlement', 'address', 'settlements', value).then(() =>  this.showTo = true);
                });

                // when Livewire returned settlements — decide to show dropdown or not
                this.$watch('settlements', value => {
                    if (this.selecting) {
                        return;
                    }

                    this.showTo = Array.isArray(value) && value.length > 0;
                });
            }
        }"
        x-init="init()"
    >
        <input
            x-model.debounce.400ms="address.settlement"
            @keydown.escape="showTo = false"
            @change="showTo = false; settlements = []"
            @blur="selecting = false"
            required
            type="text"
            placeholder=" "
            id="addressSettlement"
            autocomplete="off"
            aria-describedby="{{ $hasSettlementError? 'addressSettlementErrorHelp' : '' }}"
            class="input {{ $hasSettlementError ? 'input-error border-red-500 focus:border-red-500' : ''}} peer"
            :disabled="!address.settlementType || address.area === 'М.КИЇВ' || readonly"
        />

        <div x-show="showTo && address.area !== 'М.КИЇВ'" x-cloak>
            <div
                @click.away="showTo = false"
                x-transition
                class="absolute left-0 right-0 top-full bg-white border border-gray-300 rounded-bl-md rounded-br-md shadow-lg dark:bg-gray-800 dark:border-gray-500"
            >
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownHoverButton">
                    <template x-for="settlement in settlements" :key="settlement.id">
                        <li
                            x-on:mousedown.stop="
                                selecting = true;
                                showTo = false;

                                address.settlement = settlement.name.replace(/'/g, '\'');
                                address.settlementId = settlement.id;
                            "
                            class="cursor-pointer px-4 py-2 hover:bg-gray-100 dark:hover:text-gray-200 dark:hover:bg-blue-800"
                        >
                            <span x-text="settlement.name"></span>
                        </li>
                    </template>

                    <div x-show="!settlements || (Array.isArray(settlements) && settlements.length === 0)" x-cloak>
                        <li class="cursor-default px-4 py-2">
                            {{ __('forms.nothing_found') }}
                        </li>
                    </div>
                </ul>
            </div>
        </div>

        @if($hasSettlementError)
            <p id="addressSettlementErrorHelp" class="text-error">
                {{ $errors->first('address.settlement') }}
            </p>
        @endif

        <label for="addressSettlement" class="label z-10">
            {{ __('forms.settlement') }}
        </label>
    </div>

    {{-- STREET_TYPE --}}
    <div class="form-group group !z-[24]">
        <select
            x-model="address.streetType"
            id="addressStreetType"
            @blur="selecting=false"
            aria-describedby="{{ $hasStreetTypeError ? 'addressStreetTypeErrorHelp' : '' }}"
            class="input-select text-gray-800 {{ $hasStreetTypeError ? 'input-error border-red-500 focus:border-red-500' : ''}} peer"
            :disabled="!address.settlement || readonly"
        >
            <option value="_placeholder_" selected hidden>-- {{ __('forms.select') }} --</option>

            @if($dictionaries['STREET_TYPE'])
                @foreach($dictionaries['STREET_TYPE'] as $key => $type)
                    <option class="normal-case"
                            {{ isset($address['streetType']) && $address['streetType'] === $key ? 'selected': ''}}
                            value="{{ $key }}"
                    >
                        {{ $type }}
                    </option>
                @endforeach
            @endif
        </select>

        @if($hasStreetTypeError)
            <p id="addressStreetTypeErrorHelp" class="text-error">
                {{ $errors->first('address.streetType') }}
            </p>
        @endif

        <label for="addressStreetType" class="label absolute z-20">
            {{ __('forms.street_type') }}
        </label>
    </div>

    {{-- STREET --}}
    <div class="form-group group !z-[23]"
       {{-- @mouseleave="timeout = setTimeout(() => { showTo = false }, 800)" --}}
        x-data="{
            showTo: false,
            streets: $wire.entangle('streets'),
            initialized: false,
            init() {
                this.$watch('address.street', value => {
                    // tracking changes of settlement, but skip first time
                    if (!this.initialized) {
                        this.initialized = true;

                        return; // at first time do nothing
                    }

                    // skip when selecting from dropdown
                    if (this.selecting) {
                        return;
                    }

                    if (!value || value.length < searchStartLength) {
                        this.showTo = false;
                        return;
                    }

                    $wire.call('updateStreet', 'address', 'streets', value).then(() => this.showTo = true);
                });

                // when Livewire returned streets — decide to show dropdown or not
                this.$watch('streets', value => {
                    if (this.selecting) {
                        return;
                    }

                    this.showTo = Array.isArray(value) && value.length > 0;
                });
            }
        }"
        x-init="init()"
    >
        <input
            x-model.debounce.400ms="address.street"
            @keydown.escape="showTo = false"
            @change="showTo = false; streets = []"
            @blur="selecting = false"
            type="text"
            placeholder=" "
            id="addressStreet"
            autocomplete="off"
            aria-describedby="{{ $hasStreetError ? 'addressStreetErrorHelp' : '' }}"
            class="input {{ $hasStreetError ? 'input-error border-red-500 focus:border-red-500' : ''}} peer"
            :disabled="(!address.settlementType && !selecting) || readonly"
        />

        <div x-cloak x-show="showTo"
             @click.away="showTo = false"
             x-transition
             class="absolute left-0 right-0 top-full bg-white border border-gray-300 rounded-bl-md rounded-br-md shadow-lg dark:bg-gray-800 dark:border-gray-500"
        >
            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownHoverButton">
                <template x-for="street in streets" :key="street.id">
                    <li
                        x-on:mousedown.stop="
                            selecting = true;
                            showTo = false;
                            address.street = street.name.replace(/'/g, '\'');
                        "
                        class="cursor-pointer px-4 py-2 hover:bg-gray-100 dark:hover:text-gray-200 dark:hover:bg-blue-800"
                    >
                        <span x-text="street.name"></span>
                    </li>
                </template>

                    <div x-show="!streets || (Array.isArray(streets) && streets.length === 0)" x-cloak>
                        <li class="cursor-default px-4 py-2">
                            {{ __('forms.nothing_found') }}
                        </li>
                    </div>
                </ul>
            </div>

        @if($hasStreetError)
            <p id="addressStreetErrorHelp" class="text-error">
                {{ $errors->first('address.street') }}
            </p>
        @endif

        <label for="addressStreet" class="label z-10">
            {{ __('forms.street') }}
        </label>
    </div>

    {{-- BUILDING --}}
    <div class="form-group group !z-[22]">
        <input
            x-model="address.building"
            type="text"
            placeholder=" "
            id="addressBuilding"
            aria-describedby="{{ $hasBuildingError ? 'addressBuildingErrorHelp' : '' }}"
            class="input {{ $hasBuildingError ? 'input-error border-red-500 focus:border-red-500' : ''}} peer"
            :disabled="!address.street || readonly"
        />

        @if($hasBuildingError)
            <p id="addressBuildingErrorHelp" class="text-error">
                {{ $errors->first('address.building') }}
            </p>
        @endif

        <label for="addressBuilding" class="label z-10">
            {{ __('forms.building') }}
        </label>
    </div>

    {{-- APARTMENT --}}
    <div class="form-group group !z-[21]">
        <input
            x-model="address.apartment"
            type="text"
            placeholder=" "
            id="addressApartment"
            aria-describedby="{{ $hasApartmentError ? 'addressApartmentErrorHelp' : '' }}"
            class="input {{ $hasApartmentError ? 'input-error border-red-500 focus:border-red-500' : ''}} peer"
            :disabled="!address.street || readonly"
        />

        @if($hasApartmentError)
            <p id="addressApartmentErrorHelp" class="text-error">
                {{ $errors->first('address.apartment') }}
            </p>
        @endif

        <label for="addressApartment" class="label z-10">
            {{ __('forms.apartment') }}
        </label>
    </div>

    {{-- ZIP --}}
    <div class="form-group group">
        <input
            x-model="address.zip"
            type="text"
            x-mask="99999"
            placeholder=" "
            id="addressZip"
            aria-describedby="{{ $hasZipError ? 'addressZipErrorHelp' : '' }}"
            class="input {{ $hasZipError ? 'input-error border-red-500 focus:border-red-500' : ''}} peer"
            :disabled="!address.street || readonly"
        />

        @if($hasZipError)
            <p id="addressZipErrorHelp" class="text-error">
                {{ $errors->first('address.zip') }}
            </p>
        @endif

        <label for="addressZip" class="label z-10">
            {{ __('forms.zip_code') }}
        </label>
    </div>
</div>
