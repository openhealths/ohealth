@props(['modelPath', 'dictionaryName'])

<div x-data="selectComponent('{{ $dictionaryName }}', '{{ $modelPath }}')"
     x-modelable="selected"
     x-model="{{ $modelPath }}"
     @click.away="hideOptions"
     x-cloak
>
    <input class="{{ $attributes->get('class', 'input-modal') }}"
           {{ $attributes->except('class') }}
           type="search"
           placeholder="{{ __('forms.select') }}"
           x-model="search"
           @input.debounce.150ms="showOptions"
           id="{{ $attributes['id'] ?? '' }}"
           autocomplete="off"
           role="combobox"
    />

    <div class="relative">
        <div x-show="optionsVisible"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="absolute z-50 border p-2 overflow-y-auto bg-white dark:bg-gray-800 dark:text-white max-h-60 grid shadow-lg rounded-md"
        >
            <template x-for="(option, index) in paginatedOptions" :key="`${option.value}-${index}`">
                <a @click="selectOption(option)"
                   x-html="highlightedText(option)"
                   class="cursor-pointer px-2 py-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
                ></a>
            </template>

            <div x-show="filteredOptions.length === 0 && !isLoading" class="px-2 py-1 text-gray-500">
                {{ __('forms.nothing_found') }}
            </div>

            <div x-show="isLoading" class="px-2 py-1 text-gray-500">
                {{ __('general.loading') }}...
            </div>

            {{-- Show the 'Show more' button if there are more options --}}
            <div x-show="canLoadMore()" class="px-2 py-1 text-center">
                <button @click="loadMore" class="text-blue-500 hover:text-blue-700 text-sm">
                    {{ __('general.show_more') }} (<span x-text="remainingCount()"></span> {{ __('general.remain') }})
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function selectComponent(dictionaryKey, modelPath) {
        return {
            search: '',
            selected: '',
            optionsVisible: false,
            options: [],
            filteredOptions: [],
            paginatedOptions: [],
            isLoading: false,
            currentPage: 0,
            pageSize: 50,

            // Cache for highlightedText
            highlightCache: new Map(),
            lastSearchTerm: '',

            init() {
                this.initializeOptions();
                this.watchSelected();

                this.$watch('search', Alpine.debounce(() => {
                    this.filterOptions();
                }, 100));
            },

            async initializeOptions() {
                this.isLoading = true;

                try {
                    const rawData = this.$wire.dictionaries?.[dictionaryKey] ?? {};

                    if (dictionaryKey === 'eHealth/LOINC/observation_codes') {
                        const codeMap = this.$wire.observationCodeMap;
                        const allowedCodes = codeMap.laboratory ?? [];

                        this.options = Object.entries(rawData)
                            .filter(([value]) => allowedCodes.includes(value))
                            .map(([value, label]) => ({value, label}));
                    } else if (dictionaryKey === 'eHealth/ICF/classifiers') {
                        this.updateIcfOptions(rawData);
                        this.$watch('modalObservation.categories[0].coding[0].code', () => {
                            this.updateIcfOptions(rawData);
                        });
                    } else if (dictionaryKey === 'custom/services') {
                        // Based on diff model, change path for watcher
                        const rootPath = modelPath.split('.')[0];
                        const isModalProcedure = rootPath === 'modalProcedure';
                        const categoryPath = isModalProcedure
                            ? `${rootPath}.category.coding[0].code`
                            : `${rootPath}.category[0].coding[0].code`;

                        this.$watch(categoryPath, (newCode) => {
                            this.options = Object.entries(rawData)
                                // Based on category value show relevant codes or when category is not defined
                                .filter(([_, service]) => {
                                    return service.category === newCode;
                                })
                                .map(([_, service]) => ({
                                    value: service.id,
                                    code: service.code,
                                    label: service.name
                                }));

                            this.filterOptions();
                        });
                    } else {
                        this.options = Object.entries(rawData).map(([value, label]) => ({value, label}));
                    }

                    this.filterOptions();
                } finally {
                    this.isLoading = false;
                }
            },

            updateIcfOptions(rawData) {
                const categoryCode = this.modalObservation?.categories[0].coding[0]?.code;
                const prefixMap = {
                    functions: 'b',
                    structures: 's',
                    activities: 'd',
                    environmental: 'e'
                };

                const prefix = prefixMap[categoryCode] ?? null;

                this.options = Object.entries(rawData)
                    .filter(([key]) => !prefix || key.startsWith(prefix))
                    .map(([value, label]) => ({value, label}));

                this.filterOptions();
            },

            filterOptions() {
                const searchTerm = this.search.toLowerCase().trim();

                if (!searchTerm) {
                    this.filteredOptions = this.options;
                } else {
                    this.filteredOptions = this.options.filter(option => {
                        const searchText = `${option.label} ${option.value}`.toLowerCase();
                        return searchText.includes(searchTerm);
                    });
                }

                // Remove pagination in new searches
                this.currentPage = 0;
                this.updatePaginatedOptions();
                this.clearHighlightCache();
            },

            updatePaginatedOptions() {
                const endIndex = (this.currentPage + 1) * this.pageSize;
                this.paginatedOptions = this.filteredOptions.slice(0, endIndex);
            },

            loadMore() {
                this.currentPage++;
                this.updatePaginatedOptions();
            },

            canLoadMore() {
                return this.paginatedOptions.length < this.filteredOptions.length;
            },

            remainingCount() {
                return this.filteredOptions.length - this.paginatedOptions.length;
            },

            showOptions() {
                this.optionsVisible = true;
                if (this.paginatedOptions.length === 0) {
                    this.updatePaginatedOptions();
                }
            },

            hideOptions() {
                this.optionsVisible = false;
                this.currentPage = 0; // Remove pagination when closing
            },

            selectOption(option) {
                this.selected = option.value;
                this.search = `[${option.value}] – ${option.label}`;
                this.hideOptions();
            },

            highlightedText(option) {
                const text = `[${option.code ?? option.value}] – ${option.label}`;
                const searchTerm = this.search.toLowerCase().trim();

                if (!searchTerm) return text;

                const cacheKey = `${text}-${searchTerm}`;

                if (this.highlightCache.has(cacheKey)) {
                    return this.highlightCache.get(cacheKey);
                }

                const escaped = searchTerm.replace(/[-/\\^$*+?.()|[\]{}]/g, '\\$&');
                const re = new RegExp(escaped, 'gi');
                const highlighted = text.replace(re, match =>
                    `<span class='bg-purple-300 dark:bg-purple-600'>${match}</span>`
                );

                // Limiting cache size
                if (this.highlightCache.size > 200) {
                    this.highlightCache.clear();
                }

                this.highlightCache.set(cacheKey, highlighted);
                return highlighted;
            },

            clearHighlightCache() {
                if (this.lastSearchTerm !== this.search) {
                    this.highlightCache.clear();
                    this.lastSearchTerm = this.search;
                }
            },

            watchSelected() {
                this.$watch('selected', (value) => {
                    if (value === undefined || value === null || value === '') {
                        this.search = '';
                    } else {
                        const opt = this.options.find(option => option.value === value);
                        if (opt) {
                            this.search = `[${opt.code ?? opt.value}] – ${opt.label}`;
                        }
                    }
                });
            }
        }
    }
</script>
