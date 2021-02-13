<?php

namespace App\Http\Controllers\Portfolio;

use App\Managers\Portfolio\Search\Actions\IndexSearchManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $data = $request->input('s');
        $indexSearchManager = app(IndexSearchManager::class, ['request' => $request]);

        if ($indexSearchManager->getValidator()->fails()) {
            $items = collect([]);
            return view('search', compact('data','items'))
                ->withErrors($indexSearchManager->getValidator());
        }

        $items = $indexSearchManager->getResults($data);
        $items = $items->appends(Input::except('page'));
        return view('search', compact('data','items'));
    }
}
