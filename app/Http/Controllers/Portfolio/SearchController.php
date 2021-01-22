<?php

namespace App\Http\Controllers\Portfolio;

use App\Models\WpPost;
use App\Models\WpTermTaxonomy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $data = $request->input('s');

        $validator = Validator::make($request->all(), [
            's' => 'required|alpha_num',
        ]);

        if ($validator->fails()) {
//            return redirect('search')
//                ->withErrors($validator)
//                ->withInput();
            $items = collect([]);
            return view('search', compact('data','items'))
                ->withErrors($validator);
        }

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
        $items = WpPost::whereIn('ID', $passedItems)
            ->where('post_content', 'LIKE', "%".$data."%")
            ->where('post_type', '=', 'post')
            ->where('post_status','=', 'publish')
            ->paginate(10);
        //echo $data;
        //dd($data,$items->count());
        $items = $items->appends(Input::except('page'));
        return view('search', compact('data','items'));
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
