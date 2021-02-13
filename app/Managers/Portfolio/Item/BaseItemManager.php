<?php


namespace App\Managers\Portfolio\Item;


use App\Managers\Portfolio\BaseManager;
use App\Models\WpPost;

class BaseItemManager extends BaseManager
{
    /**
     * BaseItemManager constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get post meta information.
     *
     * @param string $metaKey
     * @param WpPost $post
     * @return string|WpPost
     */
    public function getPostMeta($metaKey, $post)
    {
        $postMeta = '';
        $meta = $post->meta;

        foreach ($meta as $postMetaEntry) {
            if($postMetaEntry->meta_key == $metaKey){
                $postMeta = $postMetaEntry->meta_value;
                break;
            }
        }
        if('_thumbnail_id' == $metaKey){
            $postMeta = $this->wpPostRepository->getThumbnail($postMeta);
        }

        return $postMeta;
    }
}