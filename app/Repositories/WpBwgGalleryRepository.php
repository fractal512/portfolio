<?php


namespace App\Repositories;

use App\Models\WpBwgGallery as Model;

/**
 * Class BlogPostRepository
 *
 * @package App\Repositories
 */
class WpBwgGalleryRepository extends CoreRepository
{
    /**
     * @return string
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Get BWG gallery.
     *
     * @param string $galleryTitle
     * @return Model
     */
    public function getGallery($galleryTitle)
    {
        $gallery = $this->startConditions()
            ->select('id')
            ->with([
                'images' => function($query){
                    $query->select('gallery_id', 'image_url', 'order');
                }
            ])
            ->where('name', '=', $galleryTitle)
            ->first();

        return $gallery;
    }
}
