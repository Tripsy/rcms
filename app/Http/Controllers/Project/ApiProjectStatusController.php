<?php

declare(strict_types=1);

namespace App\Http\Controllers\Project;

use App\Enums\CommonStatus;
use App\Http\Controllers\Controller;
use App\Models\Project;

class ApiProjectStatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Project $project, CommonStatus $status)
    {
        dump($project);
        dd($status);
    }
}
