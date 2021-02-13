<?php


namespace App\Managers\Portfolio\Item\Actions;


use App\Managers\Portfolio\Item\BaseItemManager;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class IndexItemManager extends BaseItemManager
{
    /**
     * IndexItemManager constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get localized portfolio items.
     *
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate()
    {
        $result = $this->wpPostRepository->getByIdsWithPaginate($this->translatedPostsIds);

        return $result;
    }

    /**
     * Get portfolio items meta.
     *
     * @param LengthAwarePaginator $items
     * @return array
     */
    public function getMeta($items)
    {
        $meta = [];

        foreach($items as $item){
            $thumbnail = $this->getPostMeta('_thumbnail_id', $item) ?: null;
            $wastetime = $this->getPostMeta('wastetime', $item) ?: '';
            $wastetimeunits = $this->getPostMeta('wastetimeunits', $item) ?: '';
            $meta[] = [
                'thumbnail'         =>  $thumbnail,
                'wastetime'         =>  $wastetime,
                'wastetimeunits'    =>  $wastetimeunits
            ];
        }

        return $meta;
    }
}