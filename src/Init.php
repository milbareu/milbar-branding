<?php

namespace Milbar\Branding;

// Set up plugin class
class Init
{

    public function __construct()
    {
        add_action('login_head', [$this, 'wpb_remove_loginshake']);
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
     *  Remove wp-login form shake effect
     */
    public function wpb_remove_loginshake() {
        remove_action('login_head', 'wp_shake_js', 12);
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
            body.login div#login h1 a{background-image:url("<?=(MILBAR_BRANDING_PLUGIN_URL . 'assets/images/logo-small.png')?>");background-repeat:no-repeat;background-size:contain;background-position:center center;width:300px}:focus{outline:#0085ca auto 5px}@media screen and (min-width:1024px){html{overflow:hidden}body{display:flex;align-items:center;justify-content:center;max-width:50%;padding-left:50%!important;position:relative}body:before{content:'';background:url("<?=(MILBAR_BRANDING_PLUGIN_URL . 'assets/images/login-bg.jpg')?>");display:block;width:50%;position:absolute;height:100vh;left:0;top:0;background-repeat:no-repeat;background-position:center center;background-size:cover}}.login form{background:0 0!important;box-shadow:none!important;padding:0!important;margin-bottom:15px!important}.login form input[type=password],.login form input[type=text]{background:0 0!important;border:0!important;box-shadow:none!important;border-bottom:1px solid!important}.login form input[type=password]:focus,.login form input[type=text]:focus{outline:0!important;box-shadow:none!important;border-bottom:2px solid #0085ca!important}.login form input[type=submit]{border-radius:0!important;background-color:#0085ca!important;border-color:#0085ca!important}.login #backtoblog,.login #nav{margin:0!important;padding:0!important;display:inline-block!important}.login #nav{float:right!important}.login #backtoblog{float:left!important}
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
