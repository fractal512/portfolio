<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WpTerm extends Model
{
    /**
     * WpTermTaxonomy constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable('terms')
            ->setKeyName('term_id');
    }
}
