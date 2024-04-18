<?php

declare(strict_types=1);

namespace Tripsy\StubChain\Console;

use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class StubChain extends StubCommand
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
    public function handle(): void
    {
        try {
            $model = ucfirst($this->getArgumentValue('model'));
            $parentModel = ucfirst($this->getArgumentValue('parentModel'));

            if ($parentModel) {
                $model = $parentModel.$model;
            }

            // Set stub
            $this->setStubName($this->getArgumentValue('stub'));

            // Build class name
            $className = $this->buildClassName(
                model: $model,
                parentModel: $parentModel
            );

            // First we need to ensure that the given name is not a reserved word within the PHP
            // language and that the class name will actually be valid. If it is not valid we
            // can error now and prevent from polluting the filesystem using invalid files.
            if ($this->isReservedName($className)) {
                throw new FileNotFoundException(__('stub-chain::stub-chain.is_reserved_name', [
                    'className' => $className,
                ]));
            }

            // Set the stub data
            $this->addStubData('className', $className);
            $this->addStubData('model', $model);
            $this->addStubData('parentModel', $parentModel);
            $this->addStubData('modelVariable', lcfirst($model));

            // Determine `destinationFileFolder`
            $this->determineDestinationFileName($className);

            // Determine destination file folder based on namespace found in stub content
            $this->determineDestinationFileFolder($model);

            //generate destination file
            if ($this->generate() === false) {
                $this->warn(__('stub-chain::stub-chain.file_already_exist', [
                    'fileName' => $this->destinationFileName,
                    'fileFolder' => $this->destinationFileFolder,
                ]));
            } else {
                $this->info(__('stub-chain::stub-chain.file_generated', [
                    'fileName' => $this->destinationFileName,
                    'fileFolder' => $this->destinationFileFolder,
                    'stub' => $this->stub,
                ]));
            }

            if ($this->getOption('related') === true) {
                //generated related dynamic classes
                $relatedStubFiles = $this->getRelatedStubFiles();

                foreach ($relatedStubFiles as $s) {
                    $this->call('tripsy:stub-chain', array_filter([
                        'stub' => $s,
                        'model' => $this->getArgumentValue('model'),
                        'parentModel' => $this->getArgumentValue('parentModel'),
                    ]));
                }
            }
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
