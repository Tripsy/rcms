<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Message Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'Ops! Some errors occurred',
    'success' => 'All good!',

    'exception' => [
        'method_not_supported' => 'Method (eg: :method) is not supported on this route',
        'class_method_not_found' => 'Method (eg: :method) not defined in class :class',
        'unauthenticated' => 'Failed to authenticate your connection',
        'not_found' => 'Record not found',
        'query' => 'Query error encountered',
        'pdo' => 'PDO error encountered',
        'access_denied' => 'Access Denied: You do not have permission to perform this action'
    ],

    'account' => [
        'create' => 'Project #:id created with success.'
    ],

    'item' => [
        'not_found' => 'Item #:uuid does not exist',
        'cannot_create_data' => 'Cannot create data item. Item #:uuid does not exist',
    ],

    'project' => [
        'not_found' => 'Project #:id does not exist',
        'store_fail' => 'Failed to create project!',
        'already_exist' => 'Project already exist',
    ],
];
