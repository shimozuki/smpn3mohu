<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolOperationalAssistance extends Model
{
    protected $guarded = [];

    /**
     * Get the commodities associated with the commodity location.
     */
    public function commodities()
    {
        return $this->hasMany(Commodity::class);
    }
}
