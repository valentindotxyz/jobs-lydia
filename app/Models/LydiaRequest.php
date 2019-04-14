<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LydiaRequest extends Model
{
    protected $table = 'requests';
    protected $guarded = ['id'];

    /**
     * @param $value
     * @return string
     * As the Request amount is saved as integer, we return it formatted accordingly to the currency.
     */
    public function getAmountAttribute($value)
    {
        return money_format('%!n', $value) . ' €';
    }
}