<?php

namespace Tests\Feature\Service;

class LineItem extends Calculation
{
    /**
     * @var input
     */
    public $input;

    /**
     * @param $input
     */
    public function __construct($input)
    {
        $this->input = (object) $input;
    }

    /**
     * This used to get subTotal
     *
     * @formula Unit Price * Qty
     * @return float|int
     */
    public function subTotal()
    {
        return $this->input->unit_price * $this->input->qty;
    }

    /**
     * This used to get discount amount
     *
     * @formula (Discount %) x (Unit Price) x Qty
     * @return mixed|void
     */
    public function discountAmount()
    {
        return ($this->input->discount_type != 'percent') ?
            $this->input->discount_value :
            $this->discount($this->input->discount_value) * $this->subTotal();
    }

    /**
     * This used to get netPrice or Amount Before Tax
     *
     * @formula Qty x (Unit Price – Discount Amount)
     * @return void
     */
    public function netPrice()
    {
        return $this->input->qty * ($this->input->unit_price - $this->discountAmount());
    }

    /**
     * This used to get tax amount
     *
     * @formula Qty x ((Unit Price -Discount Amount) x (Tax %))
     * @return mixed|void
     */
    public function taxAmount()
    {
        return $this->input->qty * (($this->input->unit_price - $this->discountAmount()) * $this->input->tax_percent)/100;
    }

    /**
     * @formula  Tax amount + Amount before tax
     * @return mixed|void
     */
    public function totalAmount()
    {
        return $this->taxAmount() + $this->netPrice();
    }

}
