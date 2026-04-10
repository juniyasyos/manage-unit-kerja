<?php

return [
    // Navigation & General Labels
    'navigation' => [
        'group' => 'Administración',
        'title' => 'Departamentos',
        'plural' => 'Departamentos',
        'description' => 'Gestione departamentos en el sistema de manera eficiente.',
    ],

    // Columns/Form Fields
    'fields' => [
        'id' => 'ID',
        'unit_name' => 'Nombre del Departamento',
        'description' => 'Descripción',
        'created_at' => 'Creado en',
        'updated_at' => 'Actualizado en',
        'users' => 'Usuarios',
        'user_id' => 'Usuario',
        'position' => 'Posición',
    ],

    // Form Sections
    'form' => [
        'unit' => [
            'title' => 'Información del Departamento',
            'description' => 'Complete los detalles del departamento correctamente.',
            'name_placeholder' => 'Ingrese el nombre del departamento',
            'description_placeholder' => 'Agregue una breve descripción sobre este departamento',
            'helper_text' => 'El nombre del departamento debe ser único y máximo 100 caracteres.',
        ],
        'users' => [
            'title' => 'Usuarios en Departamento',
            'description' => 'Agregue usuarios a este departamento.',
            'search_placeholder' => 'Buscar usuarios...',
            'add_button' => 'Agregar Usuario',
            'remove_button' => 'Eliminar Usuario',
        ],
    ],

    'actions' => [
        'attach' => 'Adjuntar Usuario',
        'add' => 'Agregar Departamento',
    ],
];
