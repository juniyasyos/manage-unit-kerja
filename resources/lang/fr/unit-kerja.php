<?php

return [
    // Navigation & General Labels
    'navigation' => [
        'group' => 'Administration',
        'title' => 'Départements',
        'plural' => 'Départements',
        'description' => 'Gérez les départements du système efficacement.',
    ],

    // Columns/Form Fields
    'fields' => [
        'id' => 'ID',
        'unit_name' => 'Nom du Département',
        'description' => 'Description',
        'created_at' => 'Créé le',
        'updated_at' => 'Mis à jour le',
        'users' => 'Utilisateurs',
        'user_id' => 'Utilisateur',
        'position' => 'Poste',
    ],

    // Form Sections
    'form' => [
        'unit' => [
            'title' => 'Informations du Département',
            'description' => 'Remplissez correctement les détails du département.',
            'name_placeholder' => 'Entrez le nom du département',
            'description_placeholder' => 'Ajoutez une brève description de ce département',
            'helper_text' => 'Le nom du département doit être unique et maximum 100 caractères.',
        ],
        'users' => [
            'title' => 'Utilisateurs du Département',
            'description' => 'Ajoutez des utilisateurs à ce département.',
            'search_placeholder' => 'Rechercher des utilisateurs...',
            'add_button' => 'Ajouter un Utilisateur',
            'remove_button' => 'Supprimer un Utilisateur',
        ],
    ],

    'actions' => [
        'attach' => 'Rattacher un Utilisateur',
        'add' => 'Ajouter un Département',
    ],
];
