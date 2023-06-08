<?php

namespace App\Models\Traits;

trait GeneralModelTrait{

    public function scopeActive($query, $activeColumn = 'active'){
        return $this->where($activeColumn, true);
    }
}