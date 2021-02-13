<?php

namespace App\Http\Controllers\Portfolio;

use App\Managers\Portfolio\Item\Actions\IndexItemManager;
use App\Managers\Portfolio\Item\Actions\ShowItemManager;

class ItemController extends BaseController
{
    /**
     * ItemController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display the list of the resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $indexItemManager = app(IndexItemManager::class);

        $items = $indexItemManager->getAllWithPaginate();
        $meta = $indexItemManager->getMeta($items);

        return view('portfolio.items.index', compact('items', 'meta'));
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\View\View|void
     */
    public function show($slug)
    {
        $showItemManager = app(ShowItemManager::class, ['slug' => $slug]);

        $item = $showItemManager->getItemBySlug();
        if( ! $item ){
            return abort(404);
        }

        $localized = $showItemManager->localizedFound($item);
        if( $localized === null ){
            return abort(404);
        }else if( $localized ){
            return redirect('/portfolio/'.$localized->post_name);
        }

        $user = $item->user;

        $wastetime = $showItemManager->getPostMeta('wastetime', $item);
        $wastetimeunits = $showItemManager->getPostMeta('wastetimeunits', $item);
        $demolink = $showItemManager->getPostMeta('demolink', $item);

        $categories = $showItemManager->getTaxonomy('category', $item);
        $tags = $showItemManager->getTaxonomy('post_tag', $item);

        $images = $showItemManager->getGalleryImages();

        $previousItem = $showItemManager->getPreviousItem($item);
        $nextItem = $showItemManager->getNextItem($item);

        return view(
            'portfolio.items.item',
            compact(
                'item',
                'user',
                'wastetime',
                'wastetimeunits',
                'demolink',
                'categories',
                'tags',
                'images',
                'previousItem',
                'nextItem'
            )
        );
    }
}
