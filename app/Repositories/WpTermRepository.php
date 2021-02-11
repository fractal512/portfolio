<?php


namespace App\Repositories;

use App\Models\WpTerm;
use App\Models\WpTerm as Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class BlogPostRepository
 *
 * @package App\Repositories
 */
class WpTermRepository extends CoreRepository
{
    /**
     * @return string
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Get translations.
     *
     * @param int $termId
     * @return WpTerm
     */
    public function getTermById($termId)
    {
        $term = $this->startConditions()
            ->select('name')
            ->where('term_id', '=', $termId)
            ->first();

        return $term;
    }
}
