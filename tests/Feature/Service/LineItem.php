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
        return $this->roundOf($this->input->unit_price * $this->input->qty);
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
            $this->roundOf($this->input->discount_value) :
            $this->roundOf($this->discount($this->input->discount_value) * $this->subTotal());
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
            $this->roundOf($this->input->qty * ($this->input->unit_price - $this->discountAmount())):
            $this->roundOf($this->input->qty * (($this->input->unit_price - $this->discountAmount()) /
                    (1 + $this->taxPercent($this->input->tax_percent))));
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
            $this->roundOf($this->input->qty * ($this->input->unit_price - $this->discountAmount()) *
                $this->taxPercent($this->input->tax_percent) ):
            $this->roundOf($this->input->qty * (($this->input->unit_price - $this->discountAmount()) -
                    ($this->input->unit_price - $this->discountAmount()) /
                    (1 + $this->taxPercent($this->input->tax_percent))));
    }

    /**
     * @formula  Tax amount + Amount before tax
     * @return float
     */
    public function totalAmount(): float
    {
        return $this->roundOf($this->taxAmount() + $this->netPrice());
    }
}
