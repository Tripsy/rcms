<?php

declare(strict_types=1);

namespace Tripsy\StubCreator\Commands;

use Exception;
use Illuminate\Console\Command;

//{{ $controllerName }} - Project
//{{ $modelName }} - project

class MakeApiController extends MakeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:tripsy-controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Http/Controllers/Api{{Name}}Controller';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {
            if (empty($this->option('minutes'))) {
                throw new Exception('The value for option "minutes" has to be greater than 0');
            }

            $this->info('controller created');
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }
    }

    protected function getStub(): string
    {
        return $this->resolveStubPath('/stubs/model.stub');
    }
}
