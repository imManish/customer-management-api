<?php

namespace App\Services;

abstract class AbstractLayer
{
    /**
     * Process the field and make any modifications.
     *
     * @param $field
     * @param $fieldValue
     * @return array
     */
    public static function process($field, $fieldValue){
        return [
            $field => $fieldValue
        ];
    }
}
