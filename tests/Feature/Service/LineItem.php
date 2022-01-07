<?php

namespace Tests\Feature\Service;

class LineItem extends Calculation
{
    /**
     * @var input object
     */
    public $input;

    /**
     * @var document object
     */
    public $document = [
        'tax_type' => 'exclusive'
    ];

    /**
     * @param $input
     */
    public function __construct($input , $document = array())
    {
        $this->input = (object) $input;
        $this->document = !empty($document) ? (object) $document : (object) $this->document;
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
     * @formula inclusive taxtype Qty x (Unit Price – Discount Amount) / (1+ Tax %))
     * @return void
     */
    public function netPrice()
    {
        return ($this->document->tax_type != 'inclusive') ?
            $this->input->qty * ($this->input->unit_price - $this->discountAmount()) :
            $this->input->qty * ($this->input->unit_price - $this->discountAmount()) / (1+ $this->input->tax_percent)/100;
    }

    /**
     * This used to get tax amount
     *
     * @formula Qty x ((Unit Price -Discount Amount) x (Tax %))
     * @formula inclusive taxtype – (Unit Price – Discount Amount / (1+ Tax %))
     * @return mixed|void
     */
    public function taxAmount()
    {
        return($this->document->tax_type != 'inclusive') ?
            $this->input->qty * (($this->input->unit_price - $this->discountAmount()) * $this->input->tax_percent)/100 :
            $this->input->qty * (($this->input->unit_price - $this->discountAmount()) - ($this->input->unit_price -
                    $this->discountAmount()) / (1+ $this->input->tax_percent)/100) ;
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
