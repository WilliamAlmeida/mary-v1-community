<?php

namespace Mary\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Label extends Component
{
    public string $uuid;
    public string $tooltipPosition = 'lg:tooltip-top';

    public function __construct(
        public ?string $label = null,
        public ?string $icon = null,
        public ?string $iconRight = null,
        public ?string $iconSize = "h-5 w-5",
        public ?bool $responsive = false,
        public ?string $badge = null,
        public ?string $badgeClasses = null,
        public ?string $tooltip = null,
        public ?string $tooltipLeft = null,
        public ?string $tooltipRight = null,
        public ?string $tooltipBottom = null,
    ) {
        $this->uuid = "mary" . md5(serialize($this));
        $this->tooltip = $this->tooltip ?? $this->tooltipLeft ?? $this->tooltipRight ?? $this->tooltipBottom;
        $this->tooltipPosition = $this->tooltipLeft ? 'lg:tooltip-left' : ($this->tooltipRight ? 'lg:tooltip-right' : ($this->tooltipBottom ? 'lg:tooltip-bottom' : 'lg:tooltip-top'));
    }

    public function render(): View|Closure|string
    {
        return <<<'HTML'
            <label
                wire:key="{{ $uuid }}"
                {{ $attributes->whereDoesntStartWith('class') }}
                {{ $attributes->class(['normal-case', "!inline-flex lg:tooltip $tooltipPosition" => $tooltip]) }}

                @if($tooltip)
                    data-tip="{{ $tooltip }}"
                @endif
            >
                <!-- ICON -->
                @if($icon)
                    <span class="inline-block">
                        <x-mary-icon :name="$icon" class="{{ $iconSize }}" />
                    </span>
                @endif

                <!-- LABEL / SLOT -->
                @if($label)
                    <span @class(["hidden lg:block" => $responsive ])>
                        {{ $label }}
                    </span>
                    @if(strlen($badge ?? '') > 0)
                        <span class="badge badge-primary {{ $badgeClasses }}">{{ $badge }}</span>
                    @endif
                @else
                    {{ $slot }}
                @endif

                <!-- ICON RIGHT -->
                @if($iconRight)
                    <span class="inline-block">
                        <x-mary-icon :name="$iconRight" class="{{ $iconSize }}" />
                    </span>
                @endif
            </label>
        HTML;
    }
}
