<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Log Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'project' => [
        'created' => 'Project #:project_id with name ":name" created by user #:action_by',
        'updated' => 'Project #:project_id updated by user #:action_by',
        'activated' => 'Project #:project_id activated by user #:action_by',
        'deleting' => 'Project #:project_id delete init by user #:action_by',
    ],

    'project_permission' => [
        'created' => 'Project #:project_id permission #:project_permission_id created for user #:user_id by user #:action_by ',
        'updated' => 'Project #:project_id permission #:project_permission_id updated by user #:action_by',
        'activated' => 'Project #:project_id permission #:project_permission_id activated by user #:action_by',
        'deleting' => 'Project #:project_id permission #:project_permission_id delete init by user #:action_by',
    ],

    'project_blueprint' => [
        'created' => 'Project #:project_id blueprint #:project_blueprint_id created by user #:action_by ',
        'updated' => 'Project #:project_id blueprint #:project_blueprint_id updated by user #:action_by',
        'activated' => 'Project #:project_id blueprint #:project_blueprint_id activated by user #:action_by',
        'deleting' => 'Project #:project_id blueprint #:project_blueprint_id delete init by user #:action_by',
    ],

    'blueprint_component' => [
        'created' => 'Project blueprint #:project_blueprint_id component #:blueprint_component_id created by user #:action_by ',
        'updated' => 'Project blueprint #:project_blueprint_id component #:blueprint_component_id updated by user #:action_by',
        'activated' => 'Project blueprint #:project_blueprint_id component #:blueprint_component_id activated by user #:action_by',
        'deleting' => 'Project blueprint #:project_blueprint_id component #:blueprint_component_id delete init by user #:action_by',
    ],

    'tags' => [
        'created' => 'Project #:project_id tag `:name` (eg: #:tags_id) created by user #:action_by ',
        'updated' => 'Project #:project_id tag #:tags_id updated by user #:action_by',
        'activated' => 'Project #:project_id tag #:tags_id activated by user #:action_by',
        'deleting' => 'Project #:project_id tag #:tags_id delete init by user #:action_by',
    ],

    'item' => [
        'created' => 'Item #:item_id created by user #:action_by for blueprint #:project_blueprint_id',
        'updated' => 'Item #:item_id from blueprint #:project_blueprint_id updated by user #:action_by',
        'activated' => 'Item #:item_id from blueprint #:project_blueprint_id activated by user #:action_by',
        'deleting' => 'Item #:item_id from blueprint #:project_blueprint_id delete init by user #:action_by',
    ],

    'item_content' => [
        'created' => 'Content #:item_content_id added for item #:item_id by user #:action_by ',
        'updated' => 'Content #:item_content_id belonging to item #:item_id updated by user #:action_by',
        'deleting' => 'Content #:item_content_id belonging to item #:item_id deleted by user #:action_by',
    ],
];
