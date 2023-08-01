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
    <div class="relative overflow-hidden"
         x-data="{ 
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
        }">
        <div class="bg-white dark:bg-gray-700 absolute right-1 rtl:!left-1 rtl:!right-auto px-2 bottom-1 pb-1 text-sm" @if($getWordLimit()) :class="{'text-danger-500': wordCount > {{ $getWordLimit() }}}" @endif>
            <span x-text="wordCount"></span>@if($getWordLimit())/{{ $getWordLimit() }}@endif
        </div>

        <textarea
                @keyup="wordCount = countWords($event.target.value)"
                {!! ($autocapitalize = $getAutocapitalize()) ? "autocapitalize=\"{$autocapitalize}\"" : null !!}
                {!! ($autocomplete = $getAutocomplete()) ? "autocomplete=\"{$autocomplete}\"" : null !!}
                {!! $isAutofocused() ? 'autofocus' : null !!}
                {!! ($cols = $getCols()) ? "cols=\"{$cols}\"" : null !!}
                {!! $isDisabled() ? 'disabled' : null !!}
                id="{{ $getId() }}"
                dusk="filament.forms.{{ $getStatePath() }}"
        {!! ($placeholder = $getPlaceholder()) ? "placeholder=\"{$placeholder}\"" : null !!}
        {!! ($rows = $getRows()) ? "rows=\"{$rows}\"" : null !!}
        {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}"
        @if (! $isConcealed())
            {!! filled($length = $getMaxLength()) ? "maxlength=\"{$length}\"" : null !!}
            {!! filled($length = $getMinLength()) ? "minlength=\"{$length}\"" : null !!}
            {!! $isRequired() ? 'required' : null !!}
        @endif
        {{
            $attributes
                ->merge($getExtraAttributes())
                ->merge($getExtraInputAttributeBag()->getAttributes())
                ->class([
                    'filament-forms-textarea-component block w-full transition duration-75 rounded-lg shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500 disabled:opacity-70',
                    'dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:border-primary-500' => config('forms.dark_mode'),
                    'border-gray-300' => ! $errors->has($getStatePath()),
                    'border-danger-600 ring-danger-600' => $errors->has($getStatePath()),
                    'dark:border-danger-400 dark:ring-danger-400' => $errors->has($getStatePath()) && config('forms.dark_mode')
                ])
        }}
        @if ($shouldAutosize())
            x-data="textareaFormComponent()"
            x-on:input="render()"
            style="height: 150px"
            {{ $getExtraAlpineAttributeBag() }}
        @endif
        ></textarea>
    </div>
</x-dynamic-component>
