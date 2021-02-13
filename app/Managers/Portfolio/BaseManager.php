<?php


namespace App\Managers\Portfolio;


use App\Repositories\WpPostRepository;
use App\Repositories\WpTermTaxonomyRepository;
use Illuminate\Database\Eloquent\Collection;

class BaseManager
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
     * Get all translated posts IDs according to the current app locale.
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
}