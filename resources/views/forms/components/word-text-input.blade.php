@php
    $datalistOptions = $getDatalistOptions();

    $affixLabelClasses = [
        'whitespace-nowrap group-focus-within:text-primary-500',
        'text-gray-400' => ! $errors->has($getStatePath()),
        'text-danger-400' => $errors->has($getStatePath()),
    ];
@endphp
<x-dynamic-component
        :component="$getFieldWrapperView()"
        :id="$getId()"
        :label="$getLabel()"
        :label-sr-only="$isLabelHidden()"
        :helper-text="$getHelperText()"
        :hint="$getHint()"
        :hint-action="$getHintAction()"
        :hint-color="$getHintColor()"
        :hint-icon="$getHintIcon()"
        :required="$isRequired()"
        :state-path="$getStatePath()"
>
    <div x-data="{ 
            wordLimit: {{ $getWordLimit() }}, 
            wordCount: {{ str_word_count($getState() ? $getState() : '') }},
            countWords(str) {
                // Remove leading and trailing white spaces
                str = str.trim();
            
                // Split the string into an array of words
                var words = str.split(/\s+/);
            
                // Return the length of the array
                return words.length;
            }
        }" {{ $attributes->merge($getExtraAttributes())->class(['filament-forms-text-input-component flex items-center space-x-2 rtl:space-x-reverse group']) }}>
        @if (($prefixAction = $getPrefixAction()) && (! $prefixAction->isHidden()))
            {{ $prefixAction }}
        @endif
        @if ($icon = $getPrefixIcon())
            <x-dynamic-component :component="$icon" class="w-5 h-5" />
        @endif

        @if ($label = $getPrefixLabel())
            <span @class($affixLabelClasses)>
                {{ $label }}
            </span>
        @endif
        <div class="relative flex-1">
            <div
                    {{ $getExtraInputAttributeBag()->class([
                            'border focus:ring-0 ring-0 overflow-hidden flex w-full bg-white dark:bg-gray-700 transition duration-75 rounded-lg shadow-sm focus:ring-1 focus:ring-inset focus:ring-primary-500 disabled:opacity-70 ring-primary-500 focus-within:ring-1 focus-within:border-primary-500',
                            'dark:bg-gray-700 dark:border-gray-600 dark:focus-within:border-primary-500' => config('forms.dark_mode'),
                        ]) }}
                    x-bind:class="{
                        'border-gray-300': ! (@js($getStatePath()) in $wire.__instance.serverMemo.errors),
                        'dark:border-gray-600 ': ! (@js($getStatePath()) in $wire.__instance.serverMemo.errors) && @js(config('forms.dark_mode')),
                        'border-danger-600 ring-danger-600': (@js($getStatePath()) in $wire.__instance.serverMemo.errors),
                        'dark:border-danger-400 dark:ring-danger-400': (@js($getStatePath()) in $wire.__instance.serverMemo.errors) && @js(config('forms.dark_mode')),
                    }"

            >
                <input
                        @keyup="wordCount = countWords($event.target.value)"
                        @unless ($hasMask())
                            x-data="{}"
                {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}"
                type="{{ $getType() }}"
                @else
                    x-data="textInputFormComponent({
                            {{ $hasMask() ? "getMaskOptionsUsing: (IMask) => ({$getJsonMaskConfiguration()})," : null }}
                    state: $wire.{{ $applyStateBindingModifiers('entangle(\'' . $getStatePath() . '\')', lazilyEntangledModifiers: ['defer']) }},
                        })"
                    type="text"
                    wire:ignore
                    {!! $isLazy() ? "x-on:blur=\"\$wire.\$refresh\"" : null !!}
                    {!! $isDebounced() ? "x-on:input.debounce.{$getDebounce()}=\"\$wire.\$refresh\"" : null !!}
                    {{ $getExtraAlpineAttributeBag() }}
                @endunless
                dusk="filament.forms.{{ $getStatePath() }}"
                {!! ($autocapitalize = $getAutocapitalize()) ? "autocapitalize=\"{$autocapitalize}\"" : null !!}
                {!! ($autocomplete = $getAutocomplete()) ? "autocomplete=\"{$autocomplete}\"" : null !!}
                {!! $isAutofocused() ? 'autofocus' : null !!}
                {!! $isDisabled() ? 'disabled' : null !!}
                id="{{ $getId() }}"
                {!! ($inputMode = $getInputMode()) ? "inputmode=\"{$inputMode}\"" : null !!}
                {!! $datalistOptions ? "list=\"{$getId()}-list\"" : null !!}
                {!! ($placeholder = $getPlaceholder()) ? "placeholder=\"{$placeholder}\"" : null !!}
                {!! ($interval = $getStep()) ? "step=\"{$interval}\"" : null !!}
                @if (! $isConcealed())
                    {!! filled($length = $getMaxLength()) ? "maxlength=\"{$length}\"" : null !!}
                    {!! filled($value = $getMaxValue()) ? "max=\"{$value}\"" : null !!}
                    {!! filled($length = $getMinLength()) ? "minlength=\"{$length}\"" : null !!}
                    {!! filled($value = $getMinValue()) ? "min=\"{$value}\"" : null !!}
                    {!! $isRequired() ? 'required' : null !!}
                @endif
                class="appearance-none bg-transparent w-full border-0 disabled:opacity-70 focus:ring-0 focus:!outline-none focus:!shadow-none ltr:!pr-0 rtl:!pl-0"
                />
                    <div class="flex items-center px-2 text-sm" @if($getWordLimit()) :class="{'text-danger-500': wordCount > {{ $getWordLimit() }}}" @endif>
                    <span x-text="wordCount"></span>@if($getWordLimit())/{{ $getWordLimit() }}@endif
                </div>
            </div>
        </div>

        @if ($label = $getSuffixLabel())
            <span @class($affixLabelClasses)>
                {{ $label }}
            </span>
        @endif

        @if ($icon = $getSuffixIcon())
            <x-dynamic-component :component="$icon" class="w-5 h-5" />
        @endif

        @if (($suffixAction = $getSuffixAction()) && (! $suffixAction->isHidden()))
            {{ $suffixAction }}
        @endif
    </div>

    @if ($datalistOptions)
        <datalist id="{{ $getId() }}-list">
            @foreach ($datalistOptions as $option)
                <option value="{{ $option }}" />
            @endforeach
        </datalist>
    @endif
</x-dynamic-component>
