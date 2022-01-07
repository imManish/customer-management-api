<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Feature\Service\LineItem;

class LineItemTest extends TestCase
{
    /**
     * @return void
     */
    public function test_line_item_default()
    {
        $inputLine = [
            'item_id' => 1,
            'description' => 'desc_1',
            'unit_price' => 12,
            'qty' => 1,
            'discount_type' => 'percent',
            'discount_value' => 20,
            'tax_percent' => 20
        ];
        $line = new LineItem($inputLine);
        $this->assertEquals(12, $line->subTotal());
        $this->assertEquals(2.4, $line->discountAmount());
        $this->assertEquals(9.6, $line->netPrice());
        //$this->assertEquals(1.92, $line->taxAmount());
        //$this->assertEquals(11.52, $line->totalAmount());
    }
}
