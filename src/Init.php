<?php

namespace Milbar\Branding;

// Set up plugin class
class Init
{

    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'admin_styles'], 0);
        add_action('login_enqueue_scripts', [$this, 'login_styles'], 0);
        add_filter('admin_footer_text', [$this, 'admin_footer'], 11);
        add_action('admin_bar_menu', [$this, 'remove_wp_logo'], 999);
        add_action('wp_before_admin_bar_render', [$this, 'menu_custom_logo']);
        add_filter('login_headerurl', [$this, 'login_logo_url']);
        add_filter('login_headertext', [$this, 'login_logo_title']);
        add_filter('pre_site_transient_update_core', [$this, 'remove_core_updates']);
        add_filter('pre_site_transient_update_plugins', [$this, 'remove_core_updates']);
        add_filter('pre_site_transient_update_themes', [$this, 'remove_core_updates']);
        add_role('superintendent', 'Superintendent', get_role('administrator')->capabilities);
        add_action('delete_user', [$this, 'do_not_delete_superintendent']);
        add_filter('user_row_actions', [$this, 'superintendent_user_delete'], 10, 2);

    }

    /**
     * Remove WordPress admin bar menu
     */
    public function remove_wp_logo($wp_admin_bar)
    {
        $wp_admin_bar->remove_node('wp-logo');
    }

    /**
     * Admin style
     */
    public function admin_styles()
    {
        wp_enqueue_style('milbar/admin', MILBAR_BRANDING_PLUGIN_URL . 'assets/css/admin.css', false, MILBAR_BRANDING_PLUGIN_VERSION);
    }

    /**
     * Replace login screen style
     */
    public function login_styles()
    {
        wp_enqueue_style('milbar/login', MILBAR_BRANDING_PLUGIN_URL . 'assets/css/login.css', false, MILBAR_BRANDING_PLUGIN_VERSION);
    }

    /**
     * Replace login screen logo link
     */
    public function login_logo_url($url)
    {
        return 'https://milbar.eu';
    }

    public function remove_core_updates()
    {
        global $wp_version;
        $return = (object)[
            'last_checked' => time(),
            'version_checked' => $wp_version,
            'updates' => [],
        ];

        return $return;
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
        $menu_id = 'milbar-logo';
        $wp_admin_bar->add_node([
            'id' => $menu_id,
            'title' =>
                '<span class="ab-icon"><img src="' . MILBAR_BRANDING_PLUGIN_URL . "assets/images/logo-white.svg" . '" alt="milbar-icon"></span>',
            'href' => '/'
        ]);
        $wp_admin_bar->add_node([
            'parent' => $menu_id,
            'title' => __('Homepage'),
            'id' => 'milbar-logo-home',
            'href' => 'https://milbar.eu',
            'meta' => ['target' => '_blank']
        ]);

//        http://milbar.local/wp/wp-admin/edit.php?post_type=acf-field-group

        if (current_user_can('superintendent')) {
            $wp_admin_bar->add_node([
                'parent' => $menu_id,
                'title' => __('Field Groups'),
                'id' => 'acf',
                'href' => '/wp/wp-admin/edit.php?post_type=acf-field-group',
            ]);
        }
    }


    /**
     * Replace login screen logo
     */
    public function menu_custom_logo()
    {
        ?>
        <style type="text/css">
            #wpadminbar #wp-admin-bar-milbar-logo > .ab-item .ab-icon {
                height: 20px;
                width: 20px;
                margin-right: 0 !important;
                padding-top: 7px !important;
            }

            #wpadminbar #wp-admin-bar-milbar-logo > .ab-item .ab-icon img {
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

    public function do_not_delete_superintendent($id)
    {
        $current_user = wp_get_current_user();
        $current_user_roles = $current_user->roles;

        if (in_array('superintendent', $current_user_roles, true)) {
            return $id;
        }

        $user_meta = get_userdata($id);
        $user_roles = $user_meta->roles;


        if (in_array('superintendent', $user_roles, true)) {
            wp_safe_redirect(admin_url('/') . 'users.php');
            exit;
        }
    }

    public function superintendent_user_delete($actions, $user_object)
    {

        $current_user = wp_get_current_user();
        $current_user_roles = $current_user->roles;

        if (in_array('superintendent', $current_user_roles, true)) {
            return $actions;
        }

        $user_meta = get_userdata($user_object->ID);
        $user_roles = $user_meta->roles;

        if (!in_array('superintendent', $user_roles, true)) {
            return $actions;
        }

        unset($actions['edit']);
        unset($actions['view']);

        if (!is_multisite() && get_current_user_id() != $user_object->ID && current_user_can('delete_user', $user_object->ID)) {
            unset($actions['delete']);
        }
        if (is_multisite() && get_current_user_id() != $user_object->ID && current_user_can('remove_user', $user_object->ID)) {
            unset($actions['remove']);
        }

        return $actions;
    }
}
