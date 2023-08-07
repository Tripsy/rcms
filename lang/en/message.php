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
        'unauthenticated' => 'Failed to authenticate your connection',
        'not_found' => 'Record not found',
        'query' => 'Query error encountered',
        'pdo' => 'PDO error encountered'
    ],

    'account' => [
        'create' => 'Account #:id created with success.'
    ],

    'item' => [
        'not_found' => 'Item #:uuid does not exist',
        'cannot_create_data' => 'Cannot create data item. Item #:uuid does not exist',
    ],

];
