<?php

return [
    // Navigation & General Labels
    'navigation' => [
        'group' => 'Administration',
        'title' => 'Departments',
        'plural' => 'Departments',
        'description' => 'Manage departments in the system efficiently.',
    ],

    // Columns/Form Fields
    'fields' => [
        'id' => 'ID',
        'unit_name' => 'Department Name',
        'description' => 'Description',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'users' => 'Users',
        'user_id' => 'User',
        'position' => 'Position',
    ],

    // Form Sections
    'form' => [
        'unit' => [
            'title' => 'Department Information',
            'description' => 'Fill in the department details correctly.',
            'name_placeholder' => 'Enter department name',
            'description_placeholder' => 'Add a brief description about this department',
            'helper_text' => 'Department name must be unique and maximum 100 characters.',
        ],
        'users' => [
            'title' => 'Users in Department',
            'description' => 'Add users to this department.',
            'search_placeholder' => 'Search users...',
            'add_button' => 'Add User',
            'remove_button' => 'Remove User',
        ],
    ],

    'actions' => [
        'attach' => 'Attach User',
        'add' => 'Add Department',
    ],
];
