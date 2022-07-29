<div
    x-data="{

        tab: null,

        init: function () {
            this.tab = this.getTabs()[@js($getActiveTab() - 1)]
        },

        getTabs: function () {
            return JSON.parse(this.$refs.tabsData.value)
        },

    }"
    x-on:expand-concealing-component.window="
        if (getTabs().includes($event.detail.id)) {
            tab = $event.detail.id
            $el.scrollIntoView({ behavior: 'smooth', block: 'start' })
        }
    "
    x-cloak
    {!! $getId() ? "id=\"{$getId()}\"" : null !!}
    {{ $attributes->merge($getExtraAttributes())->class([
        'rounded-xl shadow-sm border border-gray-300 bg-white filament-forms-tabs-component',
        'dark:bg-gray-800 dark:border-gray-700' => config('forms.dark_mode'),
    ]) }}
    {{ $getExtraAlpineAttributeBag() }}
>
    <input
        type="hidden"
        value='{{
            collect($getChildComponentContainer()->getComponents())
                ->filter(static fn (\Filament\Forms\Components\Tabs\Tab $tab): bool => ! $tab->isHidden())
                ->map(static fn (\Filament\Forms\Components\Tabs\Tab $tab) => $tab->getId())
                ->values()
                ->toJson()
        }}'
        x-ref="tabsData"
    />

    <div
        {!! $getLabel() ? 'aria-label="' . $getLabel() . '"' : null !!}
        role="tablist"
        @class([
            'rounded-t-xl flex overflow-y-auto bg-gray-100',
            'dark:bg-gray-700' => config('forms.dark_mode'),
        ])
    >
        @foreach ($getChildComponentContainer()->getComponents() as $tab)
            <button
                type="button"
                aria-controls="{{ $tab->getId() }}"
                x-bind:aria-selected="tab === '{{ $tab->getId() }}'"
                x-on:click="tab = '{{ $tab->getId() }}'"
                role="tab"
                x-bind:tabindex="tab === '{{ $tab->getId() }}' ? 0 : -1"
                class="flex items-center gap-2 shrink-0 p-3 text-sm font-medium"
                x-bind:class="{
                    'text-gray-400 dark:text-gray-400': tab !== '{{ $tab->getId() }}',
                    'bg-white text-primary-600 dark:bg-gray-800': tab === '{{ $tab->getId() }}'
                }"
            >
                @if ($icon = $tab->getIcon())
                    <x-dynamic-component
                        :component="$icon"
                        class="h-5 w-5"
                    />
                @endif

                <span>{{ $tab->getLabel() }}</span>

                @if ($tab->getBadge())
                    <span
                        class="inline-flex items-center justify-center ml-auto rtl:ml-0 rtl:mr-auto min-h-4 px-2 py-0.5 text-xs font-medium tracking-tight rounded-xl whitespace-normal transition"
                        x-bind:class="{
                            'text-gray-400 bg-gray-500/10 dark:bg-gray-600 dark:text-gray-400': tab !== '{{ $tab->getId() }}',
                            'bg-gray-500/10 text-primary-600 font-medium': tab === '{{ $tab->getId() }}'
                        }"
                    >
                        {{ $tab->getBadge() }}
                    </span>
                @endif
            </button>
        @endforeach
    </div>

    @foreach ($getChildComponentContainer()->getComponents() as $tab)
        {{ $tab }}
    @endforeach
</div>
