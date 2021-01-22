<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WpPostMeta extends Model
{
    /**
     * WpPostMeta constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable('postmeta')
            ->setKeyName('meta_id');
    }
}
