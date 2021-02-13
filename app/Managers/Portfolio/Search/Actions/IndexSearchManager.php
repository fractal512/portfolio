<?php


namespace App\Managers\Portfolio\Search\Actions;


use App\Managers\Portfolio\Search\BaseSearchManager;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class IndexSearchManager extends BaseSearchManager
{
    /**
     * @var Validator
     */
    private $validator;

    /**
     * IndexSearchManager constructor.
     *
     * @param Request $request
     */
    public function __construct($request)
    {
        parent::__construct();

        $this->validator = Validator::make($request->all(), [
            's' => 'required|alpha_num',
        ]);
    }

    /**
     * Validator instance getter.
     *
     * @return Validator
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * Get search results.
     *
     * @param string $search
     * @return Collection|null
     */
    public function getResults($search)
    {
        $results = $this->wpPostRepository->getSearchResults($search, $this->translatedPostsIds);

        return $results;
    }
}