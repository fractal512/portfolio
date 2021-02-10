<?php


namespace App\Repositories;

use App\Models\WpPost as Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class BlogPostRepository
 *
 * @package App\Repositories
 */
class WpPostRepository extends CoreRepository
{
    /**
     * @return string
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Get posts list.
     *
     * @param array $passedItems
     * @return LengthAwarePaginator
     */
    public function getByIdsWithPaginate($passedItems)
    {
        $items = $this->startConditions()
            ->whereIn('ID', $passedItems)
            ->where('post_type', '=', 'post')
            ->where('post_status','=', 'publish')
            ->orderByDesc('post_date')
            ->paginate(10);

        return $items;
    }

    /**
     * Get post thumbnail.
     *
     * @param int $id
     *
     * @return Model
     */
    public function getThumbnail($id)
    {
        $thumbnail = $this->startConditions()
            ->where('ID', '=', $id)
            ->first();

        return $thumbnail;
    }

    /**
     * Get post specified by slug.
     *
     * @param string $slug
     *
     * @return Model
     */
    public function getSpecifiedBySlug($slug)
    {
        $post = $this->startConditions()
            ->where('post_name', '=', $slug)
            ->first();

        return $post;
    }

    /**
     * Get post specified by ID.
     *
     * @param string $id
     *
     * @return Model
     */
    public function getSpecifiedById($id)
    {
        $post = $this->startConditions()
            ->where('ID', '=', $id)
            ->first();

        return $post;
    }

    /**
     * Get previous post.
     *
     * @param Model $post
     * @param array $translatedPosts
     * @return Model
     */
    public function getPreviousPost($post, $translatedPosts)
    {
        $previousPost = $this->startConditions()
            ->whereIn('ID', $translatedPosts)
            ->where('post_date', '<', $post->post_date)
            ->where('post_type', '=', 'post')
            ->where('post_status', '=', 'publish')
            ->orderBy('post_date', 'desc')
            ->first();

        return $previousPost;
    }

    /**
     * Get next post.
     *
     * @param Model $post
     * @param array $translatedPosts
     * @return Model
     */
    public function getNextPost($post, $translatedPosts)
    {
        $nextPost = $this->startConditions()
            ->whereIn('ID', $translatedPosts)
            ->where('post_date', '>', $post->post_date)
            ->where('post_type', '=', 'post')
            ->where('post_status', '=', 'publish')
            ->orderBy('post_date', 'asc')
            ->first();

        return $nextPost;
    }
}
