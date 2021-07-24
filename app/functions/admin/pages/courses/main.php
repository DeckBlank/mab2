<?php

class CategoriesOptionPage {
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
    }

    public function add_plugin_page() {
        if (current_user_can( 'manage_options' )) {
            add_submenu_page(
                'edit.php?post_type=course',
                'Importar categorias',
                'Importar categorias',
                'manage_options',
                'import-categories',
                array( $this, 'create_admin_page' )
            );
        }
    }

    public function create_admin_page() {
        include_once __DIR__.'/view.php';
    }
}

if ( is_admin()) {
    $categoriesOptionsPage = new CategoriesOptionPage();
}
