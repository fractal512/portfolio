<?php

namespace App\Http\Controllers\Portfolio;

use App\Managers\Portfolio\Item\Actions\ShowItemManager;
use App\Models\WpPost;
use App\Models\WpTermTaxonomy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $translations = WpTermTaxonomy::where('taxonomy', '=', 'post_translations')
            ->get(['description']);
        $locale = app()->getLocale();
        $passedItems = [];
        foreach ($translations as $translation) {
            $post_translations = unserialize($translation->description);
            if(array_key_exists($locale, $post_translations)){
                $passedItems[] = $post_translations[$locale];
            }
        }
        $item = WpPost::whereIn('ID', $passedItems)
            ->where('post_type', '=', 'page')
            ->where('post_status','=', 'publish')
            ->get(['post_name'])
            ->first();
        $showItemManager = app(ShowItemManager::class, ['slug' => $item->post_name]);
        $item = $showItemManager->getItemBySlug();
        $item = $showItemManager->dropBottomNavigationLinks($item);
        $images = $showItemManager->getGalleryImages();
        return view('about', compact('item','images'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
