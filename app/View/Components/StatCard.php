<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StatCard extends Component
{
    public $icon;
    public $color;
    public $value;
    public $label;

    public function __construct($icon, $color, $value, $label)
    {
        $this->icon = $icon;
        $this->color = $color;
        $this->value = $value;
        $this->label = $label;
    }

    public function render()
    {
        return view('components.stat-card');
    }
}
