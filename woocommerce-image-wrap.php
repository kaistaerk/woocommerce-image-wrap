<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * Plugin Name: Product image wrapper for WooCommerce
 * Plugin URI: https://www.staerk-software.de
 * Description: Wraps the product images in woocommerce
 * Author: Kai Stärk
 * Author URI: https://www.kaistaerk.de
 * Version: 1.0
 * Text Domain: wc-image-wrap
 * WC requires at least: 3.0.5
 * WC tested up to: 3.0.5
 *
 * License: GPLv2 or later
 *
 * Copyright 2017 Kai Stärk web solutions
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

require_once 'vendor/autoload.php';

if (is_file(plugin_dir_path(__FILE__) . 'woocommerce-image-wrap.css')) {
    wp_register_style('wpwc_css', plugins_url('woocommerce-image-wrap.css', __FILE__));
    wp_enqueue_style('wpwc_css');
}
add_action('admin_menu', 'wpwc_option_menu');

function wpwc_option_menu()
{
    add_options_page('Product image wrapper options', 'Product wrapper', 'manage_options', 'wc-image-wrap-options',
        'wc_image_wrap_options');
}

function wc_image_wrap_options()
{
    if (array_key_exists('save_options', $_POST)) {
        file_put_contents(plugin_dir_path(__FILE__) . 'woocommerce-image-wrap.css',
            filter_var($_POST['wpwc_option_css'], FILTER_SANITIZE_STRING));
    }
    $data = [
        'options' => [
            'css' => file_get_contents(plugin_dir_path(__FILE__) . 'woocommerce-image-wrap.css')
        ]
    ];

    $tplEngine = getTemplateEngine();
    echo $tplEngine->render('options.html', [
        'button' => getWpSubmitButton('Save', 'save_options'),
        'data' => $data
    ]);
}

function getTemplateEngine()
{
    $loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
    $twig = new Twig_Environment($loader);

    return $twig;
}

function getWpSubmitButton($text, $name, $type = 'primary')
{
    ob_start();
    submit_button($text, $type, $name);
    $button = ob_get_contents();
    ob_clean();

    return $button;
}

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
    add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);

    if (!function_exists('woocommerce_template_loop_product_thumbnail')) {
        function woocommerce_template_loop_product_thumbnail()
        {
            echo woocommerce_get_product_thumbnail();
        }
    }

    if (!function_exists('woocommerce_get_product_thumbnail')) {

        function woocommerce_get_product_thumbnail($size = 'shop_catalog', $deprecated1 = 0, $deprecated2 = 0)
        {
            global $post;
            $image_size = apply_filters('single_product_archive_thumbnail_size', $size);

            if (has_post_thumbnail()) {
                $props = wc_get_product_attachment_props(get_post_thumbnail_id(), $post);
                return '<div class="wciw-product-image-wrapper">' . get_the_post_thumbnail($post->ID, $image_size,
                        array(
                            'title' => $props['title'],
                            'alt' => $props['alt'],
                        )) . '</div>';
            } elseif (wc_placeholder_img_src()) {
                return wc_placeholder_img($image_size);
            }
        }
    }
}