<?php

class ReportsOptionPage {
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
    }

    public function add_plugin_page() {
        if (current_user_can( 'manage_options' )) {
            add_menu_page(
                'Reportes',
                'Reportes',
                'manage_options',
                'reports',
                array( $this, 'create_admin_page' ),
                'dashicons-chart-line',
                3
            );

            add_submenu_page(
                'reports',
                'General',
                'General',
                'manage_options',
                'reports',
                array( $this, 'create_admin_page' )
            );

            add_submenu_page(
                'reports',
                'Accesos',
                'Accesos',
                'manage_options',
                'accesses',
                array( $this, 'create_admin_accesses_page' )
            );

            add_submenu_page(
                'reports',
                'Vistas',
                'Vistas',
                'manage_options',
                'views',
                array( $this, 'create_admin_views_page' )
            );

            add_submenu_page(
                'reports',
                'Descargas',
                'Descargas',
                'manage_options',
                'downloads',
                array( $this, 'create_admin_downloads_page' )
            );

            add_submenu_page(
                'reports',
                'Cuestionarios tema',
                'Cuestionarios tema',
                'manage_options',
                'tests',
                array( $this, 'create_admin_tests_page' )
            );

            add_submenu_page(
                'reports',
                'Estilos de aprendizaje',
                'Estilos de aprendizaje',
                'manage_options',
                'learning',
                array( $this, 'create_admin_learning_page' )
            );

            add_submenu_page(
                'reports',
                'Cuestionarios de seguimiento',
                'Cuestionarios de seguimiento',
                'manage_options',
                'questionaries',
                array( $this, 'create_admin_questionaries_page' )
            );            

            add_submenu_page(
                'reports',
                'Encuestas de satisfacción',
                'Encuestas de satisfacción',
                'manage_options',
                'polls',
                array( $this, 'create_admin_polls_page' )
            );

            add_submenu_page(
                'reports',
                'Matriculas',
                'Matriculas',
                'manage_options',
                'enrollments',
                array( $this, 'create_admin_enrollments_page' )
            );
        }
    }

    public function create_admin_page() {
        include_once __DIR__.'/assets/enqueue.php';
        include_once __DIR__.'/views/index.php';
    }

    public function create_admin_accesses_page() {
        include_once __DIR__.'/assets/enqueue.php';        
        include_once __DIR__.'/views/accesses.php';
    }

    public function create_admin_views_page() {
        include_once __DIR__.'/assets/enqueue.php';        
        include_once __DIR__.'/views/videos.php';
    }

    public function create_admin_downloads_page() {
        include_once __DIR__.'/assets/enqueue.php';        
        include_once __DIR__.'/views/materials.php';
    }

    public function create_admin_tests_page() {
        include_once __DIR__.'/assets/enqueue.php';        
        include_once __DIR__.'/views/tests.php';
    }

    public function create_admin_learning_page() {
        include_once __DIR__.'/assets/enqueue.php';        
        include_once __DIR__.'/views/learning.php';
    }
    
    public function create_admin_questionaries_page() {
        include_once __DIR__.'/assets/enqueue.php';        
        include_once __DIR__.'/views/questionaries.php';
    }
    
    public function create_admin_polls_page() {
        include_once __DIR__.'/assets/enqueue.php';        
        include_once __DIR__.'/views/polls.php';
    }
    
    public function create_admin_enrollments_page() {
        include_once __DIR__.'/assets/enqueue.php';        
        include_once __DIR__.'/views/enrollments.php';
    }
}

if ( is_admin()) {    
    $reports = new ReportsOptionPage();
}
