
# Description

Create custom entities based on custom stubs by running an artisan command.

The signature for the console command is defined as:

    tripsy:stub-chain     
      {stub : Stub file}
      {model : The model name}
      {parentModel? : The parent model name}
      {--related=true : For related false related files are not generated}
      {--overwrite=false : For overwrite true files will be overwritten if they already exist}
      {--gitAdd=false : When true generated file is staged for commit}

# Requirements

This package has been build to be used in Laravel.

Recommendations:
  * php ^8.2
  * laravel/framework >=11.0

# Install & Setup

Require the package using composer:

      composer require tripsy/stub-chain

There is no configuration required, however if you wish to create / update the existing stub files,
first you will need to run:

      php artisan vendor:publish --tag=stub-chain

# Flags

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

- The command works with the premise that if you want to create a file ProjectPermission you will set
model argument as `Permission` and the parentModel argument as `Project`

- The destination folder is determined based on the `namespace` defined in the stub file.
    
    - ex1: namespace App\Http\Controllers\{{ $model }} > app/Http/Controllers/{{ $Model}}/
    - ex2: namespace App\Http\Controllers > app/Http/Controllers/
      
- We named the package `stub-chain` because it's purpose is to generated related dynamic classes 
based on the `use` & `extra` clauses. While the `use` clauses are part of the final code, the `extra`
is just a notation to generate some dynamic classes which are not actually imported in the 
generated class (ex: Events, Listeners, etc); The `extra` lines needs to be removed manually, to obtain 
a functional code in the end.

- By default existing files will not be overwritten; see flag `--overwrite` to change the behaviour

- For convenience if flag `--gitAdd=true` is set the generated files will be staged for commit (if git is installed)

- There are three variables (four if argument parentModel is provided) which can be used in the stub files. 

If you provide `project` as parentModel argument and `permission` as model argument for a stub `api-model-controller`:

  - {{ $className }} will be replaced with `ApiProjectPermissionController`
  - {{ $model }} will be replaced with `ProjectPermission`
  - {{ $parentModel }} will be replaced with `Project`
  - {{ $modelVariable }} will be replaced with `projectPermission`

If you provide `project` as model argument and no parentModel argument for a stub `model-delete`:

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
