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
            ->select('ID', 'post_date', 'post_title', 'post_name')
            ->with([
                'meta' => function($query){
                    $query->select('post_id', 'meta_key', 'meta_value');
                }
            ])
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
     * @return Model
     */
    public function getThumbnail($id)
    {
        $thumbnail = $this->startConditions()
            ->select('post_title', 'guid')
            ->where('ID', '=', $id)
            ->first();

        return $thumbnail;
    }

    /**
     * Get post specified by slug.
     *
     * @param string $slug
     * @return Model
     */
    public function getSpecifiedBySlug($slug)
    {
        $post = $this->startConditions()
            ->select('ID', 'post_author', 'post_date', 'post_content', 'post_title')
            ->with([
                'user' => function($query){
                    $query->select('ID', 'display_name');
                },
                'meta' => function($query){
                    $query->select('post_id', 'meta_key', 'meta_value');
                },
                'taxonomies' => function($query){
                    $query->select('term_id', 'taxonomy');
                }
            ])
            ->where('post_name', '=', $slug)
            ->first();

        return $post;
    }

    /**
     * Get post specified by ID.
     *
     * @param string $id
     * @return Model
     */
    public function getSpecifiedById($id)
    {
        $post = $this->startConditions()
            ->select('post_name')
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
            ->select('post_name')
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
            ->select('post_name')
            ->whereIn('ID', $translatedPosts)
            ->where('post_date', '>', $post->post_date)
            ->where('post_type', '=', 'post')
            ->where('post_status', '=', 'publish')
            ->orderBy('post_date', 'asc')
            ->first();

        return $nextPost;
    }

    /**
     * Get search results.
     *
     * @param string $search
     * @param array $translatedPosts
     * @return LengthAwarePaginator
     */
    public function getSearchResults($search, $translatedPosts)
    {
        $results = $this->startConditions()
            ->select('post_content', 'post_title', 'post_name')
            ->whereIn('ID', $translatedPosts)
            ->where('post_content', 'LIKE', "%".$search."%")
            ->where('post_type', '=', 'post')
            ->where('post_status','=', 'publish')
            ->orderBy('post_date', 'desc')
            ->paginate(10);

        return $results;
    }
}
