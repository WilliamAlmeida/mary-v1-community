<?php

namespace Mary\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ColorPalette extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $hintClass = 'label-text-alt text-gray-400 py-1 pb-0',
        public ?bool $inline = false,
        public ?bool $clearable = false,

        // Validations
        public ?string $errorField = null,
        public ?string $errorClass = 'text-red-500 label-text-alt p-1',
        public ?bool $omitError = false,
        public ?bool $firstErrorOnly = false,
    ) {
        $this->uuid = "mary" . md5(serialize($this));
    }

    public function modelName(): ?string
    {
        return $this->attributes->whereStartsWith('wire:model')->first()
            ?? $this->attributes->whereStartsWith('x-model')->first();
    }

    public function isWireModel(): bool
    {
        return $this->attributes->whereStartsWith('wire:model')->first() !== null;
    }

    public function errorFieldName(): ?string
    {
        return $this->errorField ?? $this->modelName();
    }

    /** Alpine expression to read current value (e.g. $wire.benefits_bg or myVar) */
    public function valueExpr(): string
    {
        return $this->isWireModel() ? '$wire.' . $this->modelName() : $this->modelName();
    }

    public function render(): View|Closure|string
    {
        return <<<'HTML'
            <div>
                @php
                    // Wee need this extra step to support models arrays. Ex: wire:model="emails.0"  , wire:model="emails.1"
                    $uuid = $uuid . $modelName()
                @endphp

                <!-- STANDARD LABEL -->
                @if($label && !$inline)
                    <label for="{{ $uuid }}" class="pt-0 label label-text font-semibold">
                        <span>
                            {{ $label }}

                            @if($attributes->get('required'))
                                <span class="text-error">*</span>
                            @endif
                        </span>
                    </label>
                @endif

                @php
                    $isLive = $attributes->wire('model')->hasModifier('live');
                    $clearExpr = $isWireModel()
                        ? '$wire.set(\'' . $modelName() . '\', \'\', ' . ($isLive ? 'true' : 'false') . ')'
                        : $modelName() . ' = \'\'';
                @endphp

                <div
                    class="relative"
                    x-data="{
                        show: false,
                        loaded: false,
                        toggle() {
                            this.show = !this.show;

                            if (this.show) {
                                this.loaded = true;
                            }
                        }
                    }"
                    @click.outside="show = false"
                >
                    <div class="flex">
                        <!-- COLOR PREVIEW SWATCH -->
                        <div
                            @class([
                                "rounded-s-lg flex items-center justify-center w-12 cursor-pointer",
                                "border px-3",
                                "border-0 bg-base-300" => $attributes->has('disabled') && $attributes->get('disabled') == true,
                                "border-dashed" => $attributes->has('readonly') && $attributes->get('readonly') == true,
                                "!border-error" => $errorFieldName() && $errors->has($errorFieldName()) && !$omitError
                            ])
                            x-on:click="toggle()"
                            :style="{ backgroundColor: ({!! $valueExpr() !!} || 'transparent') }"
                        ></div>

                        <!-- INPUT -->
                        <div class="flex-1 relative">
                            <input
                                id="{{ $uuid }}"
                                type="text"
                                readonly
                                placeholder="{{ $attributes->whereStartsWith('placeholder')->first() }}"
                                :value="$store.maryColorPalette.getLabel({!! $valueExpr() !!})"
                                {{
                                    $attributes
                                        ->except(['wire:model', 'wire:model.live', 'wire:model.blur', 'wire:model.debounce', 'x-model'])
                                        ->class([
                                            'input input-primary w-full cursor-pointer peer',
                                            'h-14' => ($inline),
                                            'pt-3' => ($inline && $label),
                                            'rounded-s-none' => true,
                                            'border border-dashed' => $attributes->has('readonly') && $attributes->get('readonly') == true,
                                            'input-error' => $errorFieldName() && $errors->has($errorFieldName()) && !$omitError
                                    ])
                                }}
                                x-on:click="toggle()"
                            />

                            <!-- CLEAR ICON -->
                            @if($clearable)
                                <x-mary-icon
                                    name="o-x-mark"
                                    class="absolute top-1/2 end-10 -translate-y-1/2 cursor-pointer text-gray-400 hover:text-gray-600"
                                    x-show="{!! $valueExpr() !!}"
                                    x-on:click="{{ $clearExpr }}"
                                />
                            @endif

                            <!-- SWATCH ICON -->
                            <x-mary-icon
                                name="o-swatch"
                                @class(["absolute top-1/2 end-3 -translate-y-1/2 text-gray-400 cursor-pointer hover:text-gray-600"])
                                x-on:click="toggle()"
                            />

                            <!-- INLINE LABEL -->
                            @if($label && $inline)
                                <label for="{{ $uuid }}" class="absolute text-gray-400 duration-300 transform -translate-y-1 scale-75 top-2 origin-left rtl:origin-right rounded px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-1 start-3">
                                    {{ $label }}
                                </label>
                            @endif
                        </div>
                    </div>

                    <!-- DROPDOWN PALETTE -->
                    <div
                        x-show="show"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute z-50 mt-1 p-3 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-base-300 max-h-72 overflow-auto soft-scrollbar"
                        x-cloak
                    >
                        <template x-if="loaded">
                            <div class="space-y-0.5">
                                <template x-for="(shades, colorName) in $store.maryColorPalette.palette" :key="colorName">
                                    <div class="flex gap-0.5">
                                        <template x-for="(hex, shade) in shades" :key="shade">
                                            <button
                                                type="button"
                                                class="w-5 h-5 rounded-sm cursor-pointer hover:scale-125 transition-transform duration-100 flex items-center justify-center ring-offset-1"
                                                :class="{!! $valueExpr() !!} && {!! $valueExpr() !!}.toLowerCase() === hex.toLowerCase() ? 'ring-2 ring-primary' : 'hover:ring-2 hover:ring-gray-400'"
                                                :style="{ backgroundColor: hex }"
                                                :title="colorName + '-' + shade"
                                                x-on:click="
                                                    @if($isWireModel())
                                                        $wire.set('{{ $modelName() }}', hex, {{ json_encode($attributes->wire('model')->hasModifier('live')) }});
                                                    @else
                                                        {{ $modelName() }} = hex;
                                                    @endif
                                                    show = false;
                                                "
                                            >
                                                <svg
                                                    x-show="{!! $valueExpr() !!} && {!! $valueExpr() !!}.toLowerCase() === hex.toLowerCase()"
                                                    :class="$store.maryColorPalette.isLight(hex) ? 'text-gray-800' : 'text-white'"
                                                    class="w-3 h-3"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    stroke-width="3"
                                                >
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- ERROR -->
                @if(!$omitError && $errors->has($errorFieldName()))
                    @foreach($errors->get($errorFieldName()) as $message)
                        @foreach(Arr::wrap($message) as $line)
                            <div class="{{ $errorClass }}" x-classes="text-red-500 label-text-alt p-1">{{ $line }}</div>
                            @break($firstErrorOnly)
                        @endforeach
                        @break($firstErrorOnly)
                    @endforeach
                @endif

                <!-- HINT -->
                @if($hint)
                    <div class="{{ $hintClass }}" x-classes="label-text-alt text-gray-400 py-1 pb-0">{{ $hint }}</div>
                @endif
            </div>
            HTML;
    }
}
