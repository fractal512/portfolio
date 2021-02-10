<?php


namespace App\Repositories;

use App\Models\WpTermTaxonomy as Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class BlogPostRepository
 *
 * @package App\Repositories
 */
class WpTermTaxonomyRepository extends CoreRepository
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
     * @return Collection
     */
    public function getTranslations()
    {
        $translations = $this->startConditions()
            ->where('taxonomy', '=', 'post_translations')
            ->get(['description']);

        return $translations;
    }
}
