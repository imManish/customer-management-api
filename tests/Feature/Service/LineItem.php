<?php

namespace Tests\Feature\Service;

class LineItem extends Calculation
{
    /**
     * @var object
     */
    public $input;

    /**
     * @var array
     */
    public $document = [ 'tax_type' => 'exclusive' ];

    /**
     * @param $input
     * @param array $document
     */
    public function __construct($input , array $document = array())
    {
        $this->input = (object) $input;
        $this->document = !empty($document) ? (object) $document : (object) $this->document;
    }

    /**
     * This used to get subTotal
     *
     * @formula Unit Price * Qty
     * @return float
     */
    public function subTotal(): float
    {
        return round($this->input->unit_price * $this->input->qty,2);
    }

    /**
     * This used to get discount amount
     *
     * @formula (Discount %) x (Unit Price) x Qty
     * @return float
     */
    public function discountAmount(): float
    {
        return ($this->input->discount_type != 'percent') ?
            round($this->input->discount_value,2) :
            round($this->discount($this->input->discount_value) * $this->subTotal(),2);
    }

    /**
     * This used to get netPrice or Amount Before Tax
     *
     * @formula Qty x (Unit Price – Discount Amount)
     * @formula inclusive taxtype Qty x (Unit Price – Discount Amount) / (1+ Tax %))
     * @return float
     */
    public function netPrice(): float
    {
        return ($this->document->tax_type != 'inclusive') ?
            round($this->input->qty * ($this->input->unit_price - $this->discountAmount()) ,2):
            round($this->input->qty * (($this->input->unit_price - $this->discountAmount()) /
                    (1 + $this->input->tax_percent / 100)),2);
    }

    /**
     * This used to get tax amount
     *
     * @formula Qty x ((Unit Price -Discount Amount) x (Tax %))
     * @formula inclusive taxtype – (Unit Price – Discount Amount / (1+ Tax %))
     * @return float
     */
    public function taxAmount(): float
    {
        return ($this->document->tax_type != 'inclusive') ?
            round($this->input->qty * (($this->input->unit_price - $this->discountAmount()) *
                    $this->input->tax_percent)/100 ,2):
            round($this->input->qty * (($this->input->unit_price - $this->discountAmount()) - (
                $this->input->unit_price - $this->discountAmount()) / (1 + $this->input->tax_percent/100)), 2);
    }

    /**
     * @formula  Tax amount + Amount before tax
     * @return float
     */
    public function totalAmount(): float
    {
        return round($this->taxAmount() + $this->netPrice(), 2);
    }

}
