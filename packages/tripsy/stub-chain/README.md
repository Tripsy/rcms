
# Description

Create custom entities based on custom stubs by running an artisan command.

The signature for the console command is defined as:

    tripsy:stub-chain     
      {stub : Stub file}
      {model : The model name}
      {parentModel? : The parent model name (only used by nested stubs)}
      {--related=true : For related false related files are not generated}
      {--overwrite=false : For overwrite true files will be overwritten if they already exist}
      {--gitAdd=false : When true generated file is staged for commit}

The final scope is to avoid redundant work, especially creating multiple classes for a new model (eg: controllers, requests, events, listeners, policies, etc).

*Please be aware that the generated classes are based on templates and further customization & configuration is needed based on requirements.
The generate files, depending on how the stub content is defined, can be a fully functional code from start, but we are templating so feel free to 
add notes / hints in the stub file if is suitable; code from generated file can (& should be) cleaned / revised after.*

# Requirements

This package has been build to be used in Laravel.

  * php ^8.2
  * laravel/framework >=11.0

# Install & Setup

Require the package using composer:

      composer require tripsy/stub-chain

There is no further configuration needed, however if you wish to update the existing stub files,
first you will need to run:

      php artisan vendor:publish --tag=stub-chain

*This will copy all the predefined stubs into /stubs/tripsy and also publish config file and language file.*

If you want just to create new stubs just place them in the `/stubs` directory and follow the naming conventions (see **Managing** below)

# Flags

--init=true

This is a flag which is used to control the output, there is no need to change it; it doesn't have any impact on functionality

--silence=true

Again this is a flag which is used to control the output; 
If overwrite is false & silence is true the existing files will remain untouched and no output will be displayed

--related=true

If set as `true` the related dynamic classes, extracted based on "use" & "extra"
(from stub file), will also be created based on correspondent stub file.

--overwrite=false

If set as `true` the existing files will be overwritten by the generated files.
When set as `false` the existing files will remain untouched and the generation
of a file with same name will be skipped.

--gitAdd=false

If set as `true` and git is installed the new generated files will be staged for commit automatically.

# Managing

- The file name and also the class name are determined by the stub file name (ex: my-model-action.stub => MyModelAction.php)
- The stubs which contain parent related placeholders should be named as model-controller.nested.stub
- The command works with the premise that if you want to create a file ProjectPermission you will set
model argument as `Permission` and the parentModel argument as `Project` and the stub file is `model.stub`

- The destination folder is determined based on the `namespace` defined in the stub file.
    
    - ex1: namespace App\Http\Controllers\{{ $model }} > app/Http/Controllers/{{ $Model}}/
    - ex2: namespace App\Http\Controllers > app/Http/Controllers/
      
- We named the package `stub-chain` because it's purpose is to generate related dynamic classes 
based on the `use` & `extra` clauses. While the `use` clauses are part of the final code, the `extra`
is just a notation to generate some dynamic classes which are not actually imported in the 
generated class (ex: Events, Listeners, etc); The `extra` lines needs to be removed manually, to obtain 
a functional code in the end.

- By default, existing files will not be overwritten; see flag `--overwrite` to change the behaviour

- For convenience if flag `--gitAdd=true` is set the generated files will be staged for commit (if git is installed)

- If you provide `project` as parentModel argument and `permission` as model argument for a stub `api-model-controller`:

  - {{ $className }} will be replaced with `ApiProjectPermissionController`
  - {{ $model }} will be replaced with `ProjectPermission`
  - {{ $modelVariable }} will be replaced with `projectPermission`
  - {{ $parentModel }} will be replaced with `Project`
  - {{ $parentVariable }} will be replaced with `project`

- If you provide `project` as model argument and no parentModel argument for a stub `model-delete`:

  - {{ $className }} will be replaced with `ProjectDelete`
  - {{ $model }} will be replaced with `Project`
  - {{ $modelVariable }} will be replaced with `project`    

# Examples

    $ php artisan tripsy:stub-chain api-model-controller project

This command will generate an API controller (eg: app/Http/Controllers/Project/ApiProjectController.php)

    $ php artisan tripsy:stub-chain api-model-controller permission project

This command will generate an API controller (eg: app/Http/Controllers/ProjectPermission/ApiProjectPermissionController.php)

*Note: In this example `project` is the argument parentModel and `permission` is set as the model argument, but on generation the actual 
model is `ProjectPermission`*


## Stub Sample

    <?php
    
    declare(strict_types=1);
    
    namespace App\Actions;
    
    use App\Actions\Traits\AsAction;
    use App\Commands\{{ $model }}DeleteCommand;
    use App\Exceptions\ActionException;
    use App\Queries\{{ $model }}DeleteQuery;
    
    class {{ $className }}
    {
        use AsAction;
    
        private {{ $model }}DeleteQuery $query;
    
        public function __construct({{ $model }}DeleteQuery $query)
        {
            $this->query = $query;
        }
    
        /**
         * @throws ActionException
         */
        public function handle({{ $model }}DeleteCommand $command): void
        {
            $this->query
                ->filterById($command->getId())
                ->deleteFirst();
        }
    }
