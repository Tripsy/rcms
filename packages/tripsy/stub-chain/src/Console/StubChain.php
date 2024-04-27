<?php

declare(strict_types=1);

namespace Tripsy\StubChain\Console;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Tripsy\StubChain\Helpers\StubBuilder;

use function Laravel\Prompts\text;

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
        {--init=true : This is a flag which mark the initial command}
        {--related=true : For false related files are not generated}
        {--overwrite=false : For true file(s) will be overwritten if they already exist}
        {--silence=true : If false & silence is true the existing files will remain untouched and no output will be displayed}
        {--gitAdd=false : When true generated file(s) is staged for commit}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create class file based on stub file';

    /**
     * Used to display output information
     */
    private array $files = [];

    private array $counter = [
        'overwritten' => 0,
        'skipped' => 0,
        'generated' => 0,
    ];

    /**
     * Execute the console command.
     */
    public function handle(StubBuilder $builder): void
    {
        try {
            // Set stub
            $builder->setStubArgument($this->argument('stub'));

            // Prepare model
            $modelArgument = $this->argument('model');

            $model = ucfirst($builder->getArgumentValue($modelArgument));

            // Prepare parentModel
            if ($builder->hasExtension('nested') === true) {
                $parentModelArgument = $this->argument('parentModel');

                if (empty($parentModelArgument) === true) {
                    $parentModelArgument = text(
                        label: 'Enter the parent model name',
                        required: true
                    );
                }

                $parentModel = ucfirst($builder->getArgumentValue($parentModelArgument));
            }

            // Build class name
            $className = $builder->buildClassName(
                model: $model,
                parentModel: $parentModel ?? ''
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
            $builder->addStubData('modelVariable', lcfirst($model));

            if (empty($parentModel) === false) {
                $builder->addStubData('parentModel', $parentModel);
                $builder->addStubData('parentVariable', lcfirst($parentModel));
            }

            // Inject additional stub data
            $injectStubData = $this->injectStubData();

            foreach ($injectStubData as $k => $v) {
                $builder->addStubData($k, $v);
            }

            // Determine file name & folder
            $builder->determineDestinationFileName($className);
            $builder->determineDestinationFileFolder();

            // Set flags
            $builder->setOverwrite($this->option('overwrite'));
            $builder->setGitAdd($this->option('gitAdd'));
            $builder->setSilence($this->option('silence'));

            // Generate destination file
            $result = $builder->generate(); //the script doesn't stop if file is not generated because if already exists

            //output message as info OR warn
            if (in_array($result['response'], ['info', 'warn'])) {
                $this->{$result['response']}($result['message']);
            }

            // needs to be declared before the `relatedStubFiles`
            $isInit = $this->option('init') === 'true' ?? false;

            // increment counter
            $filePath = $builder->getDestinationFileFolder().'/'.$builder->getDestinationFileName();

            if (in_array($filePath, $this->files) === false) {
                $this->files[] = $filePath;
                $this->counter[$result['count']]++;
            }

            // Generate related dynamic classes
            if ($builder->getOptionAsBoolean($this->option('related')) === true) {
                $relatedStubFiles = $builder->getRelatedStubFiles();

                foreach ($relatedStubFiles as $s) {
                    $this->call('tripsy:stub-chain', array_filter([
                        'stub' => $s,
                        'model' => $modelArgument,
                        'parentModel' => $parentModelArgument ?? '',
                        '--init' => 'false',
                        '--overwrite' => $this->option('overwrite'),
                        '--silence' => $this->option('silence'),
                        '--gitAdd' => $this->option('gitAdd'),
                    ]));
                }
            }

            // Final output
            if ($isInit === true) {
                foreach ($this->counter as $k => $v) {
                    $this->info(__('stub-chain::stub-chain.count_'.$k, [
                        'count' => $v,
                    ]));
                }
            }
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }
    }

    /**
     * If class is extended this method can be used to inject additional stub data
     */
    public function injectStubData(): array
    {
        return [];
    }
}
