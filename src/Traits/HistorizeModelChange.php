<?php

namespace Mudandstars\HistorizeModelChanges\Traits;

trait HistorizeModelChange
{
    public function update(array $attributes = [], array $options = [])
    {
        return parent::update($attributes, $options);
    }
}
