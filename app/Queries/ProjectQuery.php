<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\CommonStatus;
use App\Models\Project;
use App\Queries\Traits\FilterByNameQueryTrait;
use App\Queries\Traits\FilterByQueryTrait;
use App\Queries\Traits\FilterByStatusQueryTrait;
use Illuminate\Database\Eloquent\Builder;

class ProjectQuery extends AbstractQuery
{
    use FilterByQueryTrait;
    use FilterByStatusQueryTrait;
    use FilterByNameQueryTrait;

    protected static string $model = Project::class;

    public function __construct()
    {
        parent::__construct();
    }

    public function whereHasPermission(): self
    {
        $this->query->whereHas('permissions', function (Builder $query) {
            $query->where('status', CommonStatus::ACTIVE);
        });

        return $this;
    }

    public function filterByAuthorityName(string $authority_name, string $operator = '='): self
    {
        return $this->filterBy('authority_name', $authority_name, $operator);
    }

    public function filterByAuthorityKey(string $authority_key, string $operator = '='): self
    {
        return $this->filterBy('authority_key', $authority_key, $operator);
    }
}
