<?php

namespace Tests\Feature\Service;

abstract class Calculation
{
    /**
     * @return mixed
     */
    abstract public function subTotal();

    /**
     * @return mixed
     */
    abstract public function discountAmount();

    /**
     * @param $value
     * @return float|int
     */
    protected function discount($value)
    {
        return $value / 100;
    }
}
