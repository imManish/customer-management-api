<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * This is used to create relationship with customer and user
     *
     * @return void
     */
    public function user()
    {
        $this->belongsTo(User::class);
    }


}
