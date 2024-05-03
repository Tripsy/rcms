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
        'model_not_found' => 'Record not found',
        'not_found' => 'Not found',
        'query' => 'Query error encountered',
        'pdo' => 'PDO error encountered',
        'access_denied' => 'Access Denied: You do not have permission to perform this action',
        'action_denied' => 'Action Denied: You\'re not allowed to perform this action',
        'delete_without_filter' => 'Cannot perform delete operation!',
        'update_without_filter' => 'Cannot perform update operation!',
    ],

    'project' => [
        'not_found' => 'Project #:id does not exist',
        'store_fail' => 'Failed to create project!',
        'already_exist' => 'Project already exist',
    ],

    'project_permission' => [
        'not_found' => 'Project permission #:id does not exist',
        'store_fail' => 'Failed to create project permission!',
        'already_exist' => 'Project permission already set for selected user',
    ],

    'project_blueprint' => [
        'not_found' => 'Project blueprint #:id does not exist',
        'store_fail' => 'Failed to create project blueprint!',
        'already_exist' => 'Project blueprint with selected name already exist',
    ],

    'blueprint_component' => [
        'not_found' => 'Blueprint component #:id does not exist',
        'store_fail' => 'Failed to store blueprint component!',
        'already_exist' => 'Blueprint component with selected name already exist',
    ],

    //    'item' => [
    //        'not_found' => 'Item #:uuid does not exist',
    //        'cannot_create_data' => 'Cannot create data item. Item #:uuid does not exist',
    //    ],
];
