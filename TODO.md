## TO DO

https://inertiajs.com/upgrade-guide
- check policies for blueprint component
- create instructions about adding new stuff
- create app/Http/Controllers/BlueprintComponent/ApiBlueprintComponentController.php
- app/Repositories/Interfaces/ItemDataRepositoryInterface.php ??
- test special validation rules

# PACKAGES

tripsy/api-wrapper
  - create example usage files

## Ideas

- new model 
  - build console command 
  - stubs
  - guide related to step needed to create a new model
- build package for Excel


### Stubs

https://medium.com/@ariadoos/laravel-custom-file-stubs-ed32f046ea81

https://github.com/touhidurabir/laravel-stub-generator

https://github.com/spatie/laravel-stubs/blob/main/src/StubsPublishCommand.php

StubGeneratorFacade::from('/app/stubs/repository.stub')
->to('/app/Repositories', true)
->as('UserRepository')
->withReplacers([
'class'             => 'UserRepository',
'model'             => 'User',
'modelInstance'     => 'user',
'modelNamespace'    => 'App\\Models',
'baseClass'         => 'Touhidurabir\\ModelRepository\\BaseRepository',
'baseClassName'     => 'BaseRepository',
'classNamespace'    => 'App\\Repositories',
])
->save();

stub-chain add notes




api-model-controller.stub
api-parentModel-model-controller.stub 

php artisan vendor:publish --tag=courier-config


    /**
     * Return `force` flag value
     */
    protected function getForceOption(): bool
    {
        return $this->option('force');
    }

        $this->call('make:controller', array_filter([
            'name' => "{$controller}Controller",
            '--model' => $this->option('resource') || $this->option('api') ? $modelName : null,
            '--api' => $this->option('api'),
            '--requests' => $this->option('requests') || $this->option('all'),
            '--test' => $this->option('test'),
            '--pest' => $this->option('pest'),
        ]));

    /**
     * Create a policy file for the model.
     *
     * @return void
     */
    protected function createPolicy()
    {
        $policy = Str::studly(class_basename($this->argument('name')));

        $this->call('make:policy', [
            'name' => "{$policy}Policy",
            '--model' => $this->qualifyClass($this->getNameInput()),
        ]
