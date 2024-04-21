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

stub-chain 

  add notes like 
    - update EventServiceProvider.php
  
  add readme 
  php artisan vendor:publish --tag=courier-config
