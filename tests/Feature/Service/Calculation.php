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
     * @return mixed
     */
    abstract public function netPrice();

    /**
     * @return mixed
     */
    abstract public function taxAmount();

    /**
     * @return mixed
     */
    abstract public function totalAmount();

    /**
     * @param $value
     * @return float|int
     */
    protected function discount($value): float
    {
        return $value / 100;
    }

    /**
     * @param $value
     * @return float|int
     */
    protected function taxPercent($value): float
    {
        return $value / 100;
    }

    /**
     * @param $value
     * @return float
     */
    protected function roundOf($value): float
    {
        return round($value,2);
    }
}
