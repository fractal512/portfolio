<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WpBwgImage extends Model
{
    /**
     * WpTermTaxonomy constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable('bwg_image');
    }
}
