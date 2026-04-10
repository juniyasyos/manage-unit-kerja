<?php

return [
    // Navigation & General Labels
    'navigation' => [
        'group' => 'الإدارة',
        'title' => 'الأقسام',
        'plural' => 'الأقسام',
        'description' => 'إدارة الأقسام في النظام بكفاءة.',
    ],

    // Columns/Form Fields
    'fields' => [
        'id' => 'معرّف',
        'unit_name' => 'اسم القسم',
        'description' => 'الوصف',
        'created_at' => 'تم الإنشاء في',
        'updated_at' => 'تم التحديث في',
        'users' => 'المستخدمون',
        'user_id' => 'المستخدم',
        'position' => 'الموضع',
    ],

    // Form Sections
    'form' => [
        'unit' => [
            'title' => 'معلومات القسم',
            'description' => 'ملء تفاصيل القسم بشكل صحيح.',
            'name_placeholder' => 'أدخل اسم القسم',
            'description_placeholder' => 'أضف وصفًا موجزًا عن هذا القسم',
            'helper_text' => 'يجب أن يكون اسم القسم فريدًا وحدًا أقصى 100 حرف.',
        ],
        'users' => [
            'title' => 'المستخدمون في القسم',
            'description' => 'أضف مستخدمين إلى هذا القسم.',
            'search_placeholder' => 'ابحث عن مستخدمين...',
            'add_button' => 'إضافة مستخدم',
            'remove_button' => 'حذف مستخدم',
        ],
    ],

    'actions' => [
        'attach' => 'إرفاق مستخدم',
        'add' => 'إضافة قسم',
    ],
];
