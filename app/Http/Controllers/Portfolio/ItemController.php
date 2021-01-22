<?php

namespace App\Http\Controllers\Portfolio;

use App\Models\WpPost;
use App\Models\WpTerm;
use App\Models\WpTermTaxonomy;
use App\Models\WpBwgGallery;
use App\Models\WpBwgImage;
use Illuminate\Http\Request;

class ItemController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // == Start With Eager Loading Relations Method ==
        // Queries: 2
        // Execution time: 0.05315899848938s
        /* $items = WpPost::with('taxonomies')
            ->where('post_type', '=', 'post')
            ->where('post_status','=', 'publish')
            ->get(['ID']);
        $locale = app()->getLocale();
        $passedItems = [];
        foreach ($items as $item) {
            foreach ($item->taxonomies as $taxonomy) {
                if($taxonomy->taxonomy == 'post_translations'){
                    $post_translations = unserialize($taxonomy->description);
                    if(array_key_exists($locale, $post_translations)){
                        if( $post_translations[$locale] == $item->ID ){
                            $passedItems[] = $item->ID;
                        }
                    }
                }
            }
        } */
        // == End With Eager Loading Relations Method ==

        // == Start Alternative Direct Fetching Taxonomy Method ==
        // Queries: 1
        // Execution time: 0.0017039775848389s
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
        // == End Alternative Direct Fetching Taxonomy Method ==

        $items = WpPost::whereIn('ID', $passedItems)
            ->where('post_type', '=', 'post')
            ->where('post_status','=', 'publish')
            ->orderByDesc('post_date')
            ->paginate(10);

        $meta = [];
        foreach($items as $item){
            $postMetaSet = $item->meta->filter(function ($meta) {
                return $meta->meta_key == '_thumbnail_id'
                    || $meta->meta_key == 'wastetime'
                    || $meta->meta_key == 'wastetimeunits'
                    || $meta->meta_key == 'demolink';
            })->toArray();

            $thumbnail = null;
            $wastetime = '';
            $wastetimeunits = '';
            foreach ($postMetaSet as $postMetaEntry) {
                if(in_array('_thumbnail_id', $postMetaEntry)){
                    $value = $postMetaEntry['meta_value'];
                    $thumbnail = WpPost::where('ID', '=', $value)->first();
                }
                if(in_array('wastetime', $postMetaEntry)){
                    $wastetime = $postMetaEntry['meta_value'];
                }
                if(in_array('wastetimeunits', $postMetaEntry)){
                    $wastetimeunits = $postMetaEntry['meta_value'];
                }
            }
            $meta[] = [
                'thumbnail'         =>  $thumbnail,
                'wastetime'         =>  $wastetime,
                'wastetimeunits'    =>  $wastetimeunits
            ];
        }
        return view('portfolio.items.index', compact('items', 'meta'));
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
     * @param  string  $slag
     * @return \Illuminate\Http\Response
     */
    public function show($slag)
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

        $item = WpPost::where('post_name', '=', $slag)->first();

        if( !in_array($item->ID, $passedItems) ){
            foreach ($translations as $translation) {
                $post_translations = unserialize($translation->description);
                if(in_array($item->ID, $post_translations)){
                    if( isset($post_translations[$locale]) ){
                        $translatedItem = $post_translations[$locale];
                    }
                }
            }
            $item = WpPost::where('ID', '=', $translatedItem)->first();
            if($item){
                return redirect('/portfolio/'.$item->post_name);
            }else{
                return abort(404);
            }
        }

        $meta = $item->meta->filter(function ($meta) {
            return $meta->meta_key == 'wastetime'
                || $meta->meta_key == 'wastetimeunits'
                || $meta->meta_key == 'demolink';
        });
        $user = $item->user;

        $taxonomies = $item->taxonomies;
        $categories = [];
        $tags = [];
        foreach ($taxonomies as $taxonomy) {
            if($taxonomy->taxonomy == 'category'){
                $category = WpTerm::where('term_id', '=', $taxonomy->term_id)->first();
                $categories[] = $category->name;
            }
            if($taxonomy->taxonomy == 'post_tag'){
                $tag = WpTerm::where('term_id', '=', $taxonomy->term_id)->first();
                $tags[] = $tag->name;
            }
        }
        $categories = implode(', ', $categories);
        $tags = implode(', ', $tags);

        $wastetime = '';
        $wastetimeunits = '';
        $demolink = '';
        foreach ($meta as $postMetaEntry) {
            if($postMetaEntry->meta_key == 'wastetime'){
                $wastetime = $postMetaEntry['meta_value'];
            }
            if($postMetaEntry->meta_key == 'wastetimeunits'){
                $wastetimeunits = $postMetaEntry['meta_value'];
            }
            if($postMetaEntry->meta_key == 'demolink'){
                $demolink = $postMetaEntry['meta_value'];
            }
        }

        $charset = 'UTF-8';
        $content = $item->post_content;
        //$content = '012345 [Best_Wordpress_Gallery id="154" gal_title="Посадка HTML на Wordpress (WooCommerce)"] abc';
        $contentLength = iconv_strlen($content, $charset);
        $shortcodeStart = iconv_strpos($content, '[Best_Wordpress_Gallery', 0, $charset);
        $shortcodeEnd = iconv_strpos($content, ']', $shortcodeStart, $charset);
        $galleryShortcode = iconv_substr($content, $shortcodeStart, $shortcodeEnd-$shortcodeStart+1, $charset);
        $beforeShortcode = iconv_substr($content, 0, $shortcodeStart, $charset);
        $afterShortcode = iconv_substr($content, $shortcodeEnd+1, $contentLength-$shortcodeEnd-1, $charset);
        $content = $beforeShortcode . $afterShortcode;
        /*dd(
            //$content,
            $contentLength,
            $shortcodeStart,
            $shortcodeEnd,
            $galleryShortcode,
            $beforeShortcode,
            $afterShortcode,
            $content
        );*/
        $item->post_content = $content;
        //dd($content);
        $needleStart = 'gal_title="';
        $needleEnd = '"';
        $needleLength = iconv_strlen($needleStart, $charset);
        $galleryIdPositionStart = iconv_strpos($galleryShortcode, $needleStart, 0, $charset);
        $galleryIdPositionEnd = iconv_strpos($galleryShortcode, $needleEnd, $galleryIdPositionStart+$needleLength, $charset);
        $galleryTitle = iconv_substr(
            $galleryShortcode,
            $galleryIdPositionStart+$needleLength,
            $galleryIdPositionEnd-$galleryIdPositionStart-$needleLength,
            $charset
        );
        //dd($galleryIdPositionStart,$galleryIdPositionEnd,$galleryTitle);

        $gallery = WpBwgGallery::where('name', '=', $galleryTitle)->first();

        $images = $gallery->images;
        //dd($images);

        $previousItem = WpPost::whereIn('ID', $passedItems)
            ->where('post_date', '<', $item->post_date)
            ->where('post_type', '=', 'post')
            ->where('post_status', '=', 'publish')
            ->orderBy('post_date', 'desc')
            ->first();
        $nextItem = WpPost::whereIn('ID', $passedItems)
            ->where('post_date', '>', $item->post_date)
            ->where('post_type', '=', 'post')
            ->where('post_status', '=', 'publish')
            ->orderBy('post_date', 'asc')
            ->first();
        //dd($previousItem,$nextItem);


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
