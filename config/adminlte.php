<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed explanations, please check the official documentation.
    |
    */

    'title' => 'ENSUP Library',
    'title_prefix' => 'ENSUP | ',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed explanations, please check the official documentation.
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed explanations, please check the official documentation.
    |
    */

    'logo' => '<b>ENSUP</b>Library',
    'logo_img' => 'images/ensup-logo2.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_alt' => 'ENSUP Logo',
    'use_logo_image' => true,
    'brand_text' => 'ENSUP Library',

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed explanations, please check the official documentation.
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-primary', // Using ENSUP blue as primary
    'usermenu_profile_url' => true,
    'usermenu_image' => true,
    'usermenu_desc' => true,
    'usermenu_post_desc' => null,
    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration. Currently, two
    | modes are supported: 'fullscreen' for a fullscreen preloader animation
    | and 'cwrapper' to attach the preloader animation into the content-wrapper
    | element and avoid overlapping it with the sidebars and the top navbar.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => false,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'images/ensup-logo.png',
            'alt' => 'ENSUP Library Loading',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed explanations, please check the official documentation.
    |
    */

    'layout_topnav' => null, // Set to true for top navigation, null for sidebar
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => true,
    'layout_fixed_footer' => true,
    'layout_dark_mode' => null, // Set to true for dark mode, null for light mode

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed explanations, please check the official documentation.
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the Admin Panel.
    |
    | For detailed explanations, please check the official documentation.
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'main-sidebar sidebar-dark-primary elevation-4', // As per JSON
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-expand-md navbar-light navbar-white', // As per JSON
    'classes_topnav_nav' => 'navbar-nav',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed explanations, please check the official documentation.
    |
    */

    'sidebar_mini' => 'lg', // 'lg' for desktop, 'xs' for mobile, or false
    'sidebar_collapse' => false, // Initial collapse state
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_on_body_push' => true, // Auto collapse on mobile as per responsive_design
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_flat_style' => true,
    'sidebar_nav_legacy_style' => false,
    'sidebar_nav_compact_style' => false,
    'sidebar_nav_child_indent' => true,
    'sidebar_nav_collapse_on_click' => true,

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we define the URLs used in the admin panel.
    |
    | For detailed explanations, please check the official documentation.
    |
    */

    'use_route_url' => false,
    'dashboard_url' => '/', // This will be dynamically redirected by your root_redirection
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => 'profile',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we configure your sidebar menu.
    |
    | For detailed explanations, please check the official documentation.
    |
    */

    'menu' => [
        // User Panel (always visible if usermenu_enabled is true)
        [
            'text' => 'Mon profil',
            'url' => 'profile', // This will be handled by your role-based profile route
            'icon' => 'fas fa-fw fa-user',
        ],
        ['header' => 'NAVIGATION PRINCIPALE'],

        // Student Menu
        [
            'text' => 'Tableau de bord',
            'url' => 'student/dashboard',
            'icon' => 'fas fa-fw fa-tachometer-alt',
            'active' => ['student/dashboard'],
            'can' => 'student', // Check if user has 'student' role
        ],
        [
            'text' => 'Livres',
            'icon' => 'fas fa-fw fa-book',
            'active' => ['student/books*'],
            'can' => 'student',
            'submenu' => [
                [
                    'text' => 'Tous les livres',
                    'url' => 'student/books',
                    'active' => ['student/books'],
                ],
                [
                    'text' => 'Rechercher des livres',
                    'url' => 'student/books/search',
                    'active' => ['student/books/search'],
                ],
            ],
        ],
        [
            'text' => 'Mes demandes',
            'url' => 'student/requests',
            'icon' => 'fas fa-fw fa-book-open',
            'active' => ['student/requests*'],
            'can' => 'student',
        ],

        // Librarian Menu
        [
            'text' => 'Tableau de bord',
            'url' => 'librarian/dashboard',
            'icon' => 'fas fa-fw fa-tachometer-alt',
            'active' => ['librarian/dashboard'],
            'can' => 'librarian', // Check if user has 'librarian' role
        ],
        [
            'text' => 'Gestion des livres',
            'icon' => 'fas fa-fw fa-book',
            'active' => ['librarian/books*', 'librarian/categories*', 'librarian/authors*', 'librarian/publishers*', 'librarian/tags*'],
            'can' => 'librarian',
            'submenu' => [
                [
                    'text' => 'Tous les livres',
                    'url' => 'librarian/books',
                    'active' => ['librarian/books'],
                ],
                [
                    'text' => 'Ajouter un livre (Form)',
                    'url' => 'librarian/books/create',
                    'active' => ['librarian/books/create'],
                ],
                [
                    'text' => 'Ajouter par ISBN',
                    'url' => 'librarian/books/create/isbn',
                    'active' => ['librarian/books/add'],
                ],
                [
                    'text' => 'Catégories',
                    'url' => 'librarian/categories',
                    'active' => ['librarian/categories*'],
                ],
                [
                    'text' => 'Auteurs',
                    'url' => 'librarian/authors',
                    'active' => ['librarian/authors*'],
                ],
                [
                    'text' => 'Éditeurs',
                    'url' => 'librarian/publishers',
                    'active' => ['librarian/publishers*'],
                ],
                [
                    'text' => 'Tags',
                    'url' => 'librarian/tags',
                    'active' => ['librarian/tags*'],
                ],
            ],
        ],
        [
            'text' => 'Demandes d\'emprunt',
            'url' => 'librarian/requests',
            'icon' => 'fas fa-fw fa-clipboard-list',
            'active' => ['librarian/requests*'],
            'can' => 'librarian',
        ],
        [
            'text' => 'Emprunts actifs',
            'url' => 'librarian/borrowings',
            'icon' => 'fas fa-fw fa-hand-holding',
            'active' => ['librarian/borrowings*'],
            'can' => 'librarian',
        ],
        [
            'text' => 'Statistiques',
            'icon' => 'fas fa-fw fa-chart-bar',
            'active' => ['librarian/statistics*', 'librarian/students/statistics*'],
            'can' => 'librarian',
            'submenu' => [
                [
                    'text' => 'Statistiques générales',
                    'url' => 'librarian/statistics',
                    'active' => ['librarian/statistics'],
                ],
                [
                    'text' => 'Statistiques des étudiants',
                    'url' => 'librarian/students/statistics',
                    'active' => ['librarian/students/statistics'],
                ],
            ],
        ],

        // Administrator Menu
        [
            'text' => 'Tableau de bord',
            'url' => 'admin',
            'icon' => 'fas fa-fw fa-tachometer-alt',
            // 'active' => ['admin'],
            'can' => 'admin',
        ],
        [
            'text' => 'Gestion des utilisateurs',
            'url' => 'admin/users/index',
            'icon' => 'fas fa-fw fa-users',
            'active' => ['admin/users/*'],
            'can' => 'admin',
        ],
        [
            'text' => 'Statistiques globales',
            'url' => 'admin/statistics',
            'icon' => 'fas fa-fw fa-chart-line',
            'active' => ['admin/statistics/*'],
            'can' => 'admin',
            'submenu' => [
                [
                    'text' => 'Les etudiants',
                    'url' => 'admin/statistics/users',
                    'active' => ['admin/statistics/users*', 'admin/statistics/student*'],
                ],
                [
                    'text' => 'Les bibliothecaires',
                    'url' => 'admin/statistics/librarian',
                    'active' => ['admin/statistics/librarian*'],
                ],
                [
                    'text' => 'Les livres',
                    'url' => 'admin/statistics/books',
                ],
            ],
        ],
        [
            'text' => 'Paramètres système',
            'url' => 'admin/settings',
            'icon' => 'fas fa-fw fa-cogs',
            'active' => ['admin/settings/*'],
            'can' => 'admin',
        ],

        // Logout button (always visible for authenticated users)
        ['header' => 'COMPTE'],
        // [
        //     'text' => 'Changer le mot de passe',
        //     'url' => 'admin/settings/password', // Example route for password change
        //     'icon' => 'fas fa-fw fa-lock',
        //     'can' => 'view-profile', // Or any permission that allows user to change password
        // ],
        [
            'text' => 'Déconnexion',
            'url' => 'logout',
            'icon' => 'fas fa-fw fa-sign-out-alt',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can enable the menu filters.
    |
    | For detailed explanations, please check the official documentation.
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can activate and configure the plugins.
    |
    | For detailed explanations, please check the official documentation.
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Select2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                ],
            ],
        ],
        'Toastr' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        // Add other plugins as needed
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed explanations, please check the official documentation.
    |
    */

    'livewire' => false,

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we can enable the iframe mode.
    |
    | For detailed explanations, please check the official documentation.
    |
    */

    'iframe' => [
        'enabled' => false,
        'auto_show_new_tab' => false,
        'auto_remove_other_tabs' => false,
        'max_tabs' => 10,
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'navigation_class' => 'mb-2',
        'show_navigation' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Extra Alert Messages
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the alert messages.
    |
    | For detailed explanations, please check the official documentation.
    |
    */

    'alert_messages' => [
        'success' => [
            'class' => 'alert-success',
            'icon' => 'fas fa-check-circle',
            'text' => 'Success!',
        ],
        'error' => [
            'class' => 'alert-danger',
            'icon' => 'fas fa-times-circle',
            'text' => 'Error!',
        ],
        'warning' => [
            'class' => 'alert-warning',
            'icon' => 'fas fa-exclamation-triangle',
            'text' => 'Warning!',
        ],
        'info' => [
            'class' => 'alert-info',
            'icon' => 'fas fa-info-circle',
            'text' => 'Info!',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Realtime Notifications
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the realtime notifications.
    |
    | For detailed explanations, please check the official documentation.
    |
    */

    'realtime_notifications' => [
        'enabled' => false,
        'via_channels' => ['database'],
        'send_to_all_users' => false,
        'filter_by_user' => true,
        'timeout' => 5000,
        'position' => 'topRight',
        'show_progress_bar' => true,
        'progress_bar_color' => 'primary',
        'animation_in' => 'fadeInDown',
        'animation_out' => 'fadeOutUp',
        'icon' => 'fas fa-bell',
        'icon_color' => 'dark',
        'text_color' => 'dark',
        'actions' => [
            [
                'name' => 'view',
                'text' => 'View',
                'url' => '#',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom CSS and JS
    |--------------------------------------------------------------------------
    |
    | Here you can include custom CSS and JS files that will be loaded in
    | all pages, including the login and register pages.
    |
    | For detailed explanations, please check the official documentation.
    |
    */

    'css' => [
        'resources/css/app.css', // Laravel Mix/Vite default
        'vendor/fontawesome-free/css/all.min.css',
        'vendor/overlayScrollbars/css/OverlayScrollbars.min.css',
        'vendor/adminlte/dist/css/adminlte.min.css',
        'css/ensup-custom.css', // Your custom CSS file
    ],

    'js' => [
        'resources/js/app.js', // Laravel Mix/Vite default
        'vendor/bootstrap/js/bootstrap.bundle.min.js',
        'vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js',
        'vendor/adminlte/dist/js/adminlte.min.js',
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option.
    |
    | For detailed explanations, please check the official documentation.
    |
    */

    'laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed explanations, please check the official documentation.
    |
    */

    'livewire' => false,

    /*
    |--------------------------------------------------------------------------
    | Version
    |--------------------------------------------------------------------------
    |
    | Here you can define the version of your application. This will be
    | displayed in the footer.
    |
    */

    'version' => '1.0.0', // From project.version in your JSON

];
