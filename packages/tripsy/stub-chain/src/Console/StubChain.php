<?php

declare(strict_types=1);

namespace Tripsy\StubChain\Console;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Tripsy\StubChain\Helpers\StubBuilder;

class StubChain extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tripsy:stub-chain     
        {stub : Stub file}
        {model : The model name}
        {parentModel? : The parent model name}
        {--related=true : For related false related files are not generated}
        {--overwrite=false : For overwrite true files will be overwritten if they already exist}
        {--gitAdd=false : When true generated file is staged for commit}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create class file based on stub file';

    /**
     * Execute the console command.
     */
    public function handle(StubBuilder $builder): void
    {
        try {
            $stubArgument = $this->argument('stub');
            $modelArgument = $this->argument('model');
            $parentModelArgument = $this->argument('parentModel') ?? '';

            // Set stub
            $builder->setStubArgument($stubArgument);

            $model = ucfirst($builder->getArgumentValue($modelArgument));
            $parentModel = ucfirst($builder->getArgumentValue($parentModelArgument));

            /**
             * The command works with the premise that if you want to create a file ProjectPermission you will set
             * model argument as `Permission` and the parentModel argument as `Project`
             */
            if ($parentModel) {
                $model = $parentModel.$model;
            }

            // Build class name
            $className = $builder->buildClassName(
                model: $model,
                parentModel: $parentModel
            );

            // First we need to ensure that the given name is not a reserved word within the PHP
            // language and that the class name will actually be valid. If it is not valid we
            // can error now and prevent from polluting the filesystem using invalid files.
            if ($builder->isReservedName($className)) {
                throw new FileNotFoundException(__('stub-chain::stub-chain.is_reserved_name', [
                    'className' => $className,
                ]));
            }

            // Set the stub data
            $builder->addStubData('className', $className);
            $builder->addStubData('model', $model);
            $builder->addStubData('parentModel', $parentModel);
            $builder->addStubData('modelVariable', lcfirst($model));

            // Determine file name & folder
            $builder->determineDestinationFileName($className);
            $builder->determineDestinationFileFolder($model);

            // Set flags
            $builder->setOverwrite($this->option('overwrite'));
            $builder->setGitAdd($this->option('gitAdd'));

            // Generate destination file
            $result = $builder->generate(); //the script doesn't stop if file is not generated because if already exists

            //output message as info OR warn
            $this->{$result['response']}($result['message']);

            // Generate related dynamic classes
            if ($builder->getOptionAsBoolean($this->option('related')) === true) {
                $relatedStubFiles = $builder->getRelatedStubFiles();

                foreach ($relatedStubFiles as $s) {
                    $this->call('tripsy:stub-chain', array_filter([
                        'stub' => $s,
                        'model' => $modelArgument,
                        'parentModel' => $parentModelArgument,
                    ]));
                }
            }
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
