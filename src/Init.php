<?php

namespace Milbar\Branding;

// Set up plugin class
class Init
{
    /**
     * Remove Option to change color Scheme from admin
     */
    public function admin_color_scheme()
    {
        global $_wp_admin_css_colors;
        $_wp_admin_css_colors = 0;
    }

    /**
     * Add milbar color scheme
     */
    public function milbar_admin_color_scheme() {
        wp_admin_css_color( 'milbar', __( 'MilBar' ),
            MILBAR_BRANDING_PLUGIN_URL . 'assets/css/admin-color.css',
            array( '#005C8C', '#21678C', '#0085CA', '#ffffff' )
        );
    }

    /**
     * @param $user_id
     * Set default admin color scheme to milbar when user register.
     */

    public function set_default_admin_color($user_id) {
        $args = array(
            'ID' => $user_id,
            'admin_color' => 'milbar'
        );
        wp_update_user( $args );
    }

    public function __construct()
    {
        add_action('login_enqueue_scripts', [$this, 'login_logo']);
        add_filter('admin_footer_text', [$this, 'admin_footer'], 11);
        add_action('admin_bar_menu', [$this, 'remove_wp_logo'], 999);
        add_action('admin_bar_menu', [$this, 'create_menu'], 1);
        add_action('wp_before_admin_bar_render', [$this, 'menu_custom_logo']);
        add_filter('login_headerurl', [$this, 'login_logo_url']);
        add_filter('login_headertext', [$this, 'login_logo_title']);
        add_action('admin_head', [$this, 'admin_color_scheme']);
        add_action('admin_init', [$this, 'milbar_admin_color_scheme']);
        add_action('user_register', [$this, 'set_default_admin_color']);
    }


    /**
     * Remove WordPress admin bar menu
     */
    public function remove_wp_logo($wp_admin_bar)
    {
        $wp_admin_bar->remove_node('wp-logo');
    }


    /**
     * Replace login screen logo
     */
    public function login_logo()
    {
        ?>
        <style type="text/css">
            body.login div#login h1 a {
                background-image: url( <?=(MILBAR_BRANDING_PLUGIN_URL . 'assets/images/logo-small.png')?> );
                background-repeat: no-repeat;
                background-size: contain;
                background-position: center center;
                width: 300px;
            }
        </style>
        <?php
    }


    /**
     * Replace login screen logo link
     */
    public function login_logo_url($url)
    {
        return 'https://milbar.eu';
    }


    // Replace login logo title
    public function login_logo_title()
    {
        return 'Powered by MilBar';
    }


    // Create custom admin bar m enu
    public function create_menu()
    {
        global $wp_admin_bar;
        $menu_id = 'my-logo';
        $wp_admin_bar->add_node([
            'id' => $menu_id,
            'title' =>
                '<span class="ab-icon"><img src="' . MILBAR_BRANDING_PLUGIN_URL . "assets/images/icon-white-small.png" . '" alt="milbar-icon"></span>',
            'href' => '/'
        ]);
        $wp_admin_bar->add_node([
            'parent' => $menu_id,
            'title' => __('Homepage'),
            'id' => 'my-logo-home',
            'href' => 'https://milbar.eu',
            'meta' => ['target' => '_blank']
        ]);
    }


    /**
     * Replace login screen logo
     */
    public function menu_custom_logo()
    {
        ?>
        <style type="text/css">
            #wpadminbar #wp-admin-bar-my-logo > .ab-item .ab-icon {
                height: 20px;
                width: 20px;
                margin-right: 0 !important;
                padding-top: 7px !important;
            }

            #wpadminbar #wp-admin-bar-my-logo > .ab-item .ab-icon img {
                width: 100%;
            }
        </style>
        <?php
    }

    /**
     * Add "designed and developed..." to admin footer.
     */
    public function admin_footer($content)
    {
        return 'Made with &#10084; by <a href="https://milbar.eu">Milan Bartalovics</a>';
    }
}
