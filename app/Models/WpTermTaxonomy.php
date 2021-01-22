<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WpTermTaxonomy extends Model
{
    /**
     * WpTermTaxonomy constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable('term_taxonomy')
            ->setKeyName('term_taxonomy_id');
    }
}
