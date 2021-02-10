<?php


namespace App\Managers\Portfolio;


use App\Models\WpPost;
use App\Repositories\WpPostRepository;
use App\Repositories\WpTermTaxonomyRepository;
use Illuminate\Database\Eloquent\Collection;

class BaseItemManager
{
    /**
     * @var string
     */
    protected $locale;

    /**
     * @var string
     */
    protected $charset = 'UTF-8';

    /**
     * @var WpPostRepository
     */
    protected $wpPostRepository;

    /**
     * @var WpTermTaxonomyRepository
     */
    protected $wpTermTaxonomyRepository;

    /**
     * @var Collection
     */
    protected $translations;

    /**
     * @var array
     */
    protected $translatedPostsIds;

    /**
     * BaseItemManager constructor.
     */
    public function __construct()
    {
        $this->locale = app()->getLocale();

        $this->wpPostRepository = app(WpPostRepository::class);
        $this->wpTermTaxonomyRepository = app(WpTermTaxonomyRepository::class);

        $this->translations = $this->wpTermTaxonomyRepository->getTranslations();
        $this->translatedPostsIds = $this->getAllTranslatedIds();
    }

    /**
     * Get all translated posts ids according to current app locale.
     *
     * @return array
     */
    private function getAllTranslatedIds()
    {
        $passedItems = [];
        foreach ($this->translations as $translation) {
            $post_translations = unserialize($translation->description);
            if(array_key_exists($this->locale, $post_translations)){
                $passedItems[] = $post_translations[$this->locale];
            }
        }

        return $passedItems;
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