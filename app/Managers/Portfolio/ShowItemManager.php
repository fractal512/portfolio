<?php


namespace App\Managers\Portfolio;


use App\Models\WpPost;
use App\Repositories\WpBwgGalleryRepository;
use App\Repositories\WpTermRepository;
use Illuminate\Database\Eloquent\Collection;

class ShowItemManager extends BaseItemManager
{
    /**
     * @var string
     */
    private $slug;

    /**
     * @var string|null
     */
    private $galleryShortcode = null;

    /**
     * @var WpTermRepository
     */
    private $wpTermRepository;

    /**
     * @var WpBwgGalleryRepository
     */
    private $wpBwgGalleryRepository;

    /**
     * ShowItemManager constructor.
     * @param string $slug
     */
    public function __construct($slug)
    {
        parent::__construct();

        $this->slug = $slug;
        $this->wpTermRepository = app(WpTermRepository::class);
        $this->wpBwgGalleryRepository = app(WpBwgGalleryRepository::class);
    }

    /**
     * Get portfolio item.
     *
     * @param string $slug
     * @return WpPost
     */
    public function getItemBySlug()
    {
        $item = $this->wpPostRepository->getSpecifiedBySlug($this->slug);

        if($item){
            $item->post_content = $this->separateShortcode($item->post_content);
        }

        return $item;
    }

    /**
     * Separate Wordpress shortcode from content.
     *
     * @param string $content
     * @return string
     */
    private function separateShortcode($content)
    {
        $contentLength = iconv_strlen($content, $this->charset);
        $shortcodeStart = iconv_strpos($content, '[Best_Wordpress_Gallery', 0, $this->charset);
        if( $shortcodeStart === false ) return $content;
        $shortcodeEnd = iconv_strpos($content, ']', $shortcodeStart, $this->charset);

        $this->galleryShortcode = iconv_substr($content, $shortcodeStart, $shortcodeEnd-$shortcodeStart+1, $this->charset);

        $beforeShortcode = iconv_substr($content, 0, $shortcodeStart, $this->charset);
        $afterShortcode = iconv_substr($content, $shortcodeEnd+1, $contentLength-$shortcodeEnd-1, $this->charset);
        $content = $beforeShortcode . $afterShortcode;

        return $content;
    }

    /**
     * Check if portfolio item corresponds to current app locale.
     *
     * @param WpPost $item
     * @return WpPost|bool
     */
    public function localizedFound($item)
    {
        if( ! in_array($item->ID, $this->translatedPostsIds) ){

            $translatedItemId = false;

            foreach ($this->translations as $translation) {
                $post_translations = unserialize($translation->description);
                if(in_array($item->ID, $post_translations)){
                    if( isset($post_translations[$this->locale]) ){
                        $translatedItemId = $post_translations[$this->locale];
                        break;
                    }
                }
            }

            if( ! $translatedItemId ){
                return false;
            }

            $item = $this->wpPostRepository->getSpecifiedById($translatedItemId);

            return $item;
        }

        return false;
    }

    /**
     * Get item taxonomy (categories, tags).
     *
     * @param string $taxonomyName
     * @param WpPost $post
     * @return string
     */
    public function getTaxonomy($taxonomyName, $post)
    {
        $taxonomies = $post->taxonomies;
        $taxonomyItems = [];

        foreach ($taxonomies as $taxonomy) {
            if($taxonomy->taxonomy == $taxonomyName){
                $category = $this->wpTermRepository->getTermById($taxonomy->term_id);
                $taxonomyItems[] = $category->name;
            }
        }

        $taxonomyItems = implode(', ', $taxonomyItems);

        return $taxonomyItems;
    }

    /**
     * Get BWG gallery images.
     *
     * @return Collection|null
     */
    public function getGalleryImages()
    {
        if($this->galleryShortcode === null){
            return null;
        }

        $needleStart = 'gal_title="';
        $needleEnd = '"';
        $needleLength = iconv_strlen($needleStart, $this->charset);
        $galleryIdPositionStart = iconv_strpos($this->galleryShortcode, $needleStart, 0, $this->charset);
        if( $galleryIdPositionStart === false ) return null;
        $galleryIdPositionEnd = iconv_strpos($this->galleryShortcode, $needleEnd, $galleryIdPositionStart+$needleLength, $this->charset);
        $galleryTitle = iconv_substr(
            $this->galleryShortcode,
            $galleryIdPositionStart+$needleLength,
            $galleryIdPositionEnd-$galleryIdPositionStart-$needleLength,
            $this->charset
        );

        if(!$galleryTitle){
            return null;
        }

        $gallery = $this->wpBwgGalleryRepository->getGallery($galleryTitle);

        $images = $gallery->images;

        return $images ?: null;
    }

    /**
     * Get previous portfolio item.
     *
     * @param WpPost $currentPost
     * @return WpPost
     */
    public function getPreviousItem($currentPost)
    {
        $previousItem = $this->wpPostRepository->getPreviousPost($currentPost, $this->translatedPostsIds);

        return $previousItem;
    }

    /**
     * Get next portfolio item.
     *
     * @param WpPost $currentPost
     * @return WpPost
     */
    public function getNextItem($currentPost)
    {
        $nextItem = $this->wpPostRepository->getNextPost($currentPost, $this->translatedPostsIds);

        return $nextItem;
    }
}