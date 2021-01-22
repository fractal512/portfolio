<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WpBwgGallery extends Model
{
    /**
     * WpTermTaxonomy constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable('bwg_gallery');
    }

    /**
     * WpBwgGallery images.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function images()
    {
        return $this->hasMany(WpBwgImage::class, 'gallery_id', 'id');
    }
}
