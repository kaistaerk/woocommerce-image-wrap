<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * Plugin Name: WC image wrap
 * Plugin URI: https://www.staerk-software.de
 * Description: Wraps the product images in woocommerce
 * Author: WooCommerce
 * Version: 1.0
 * Developer: Kai Stärk
 * Developer URI: https://www.kaistaerk.de
 * Text Domain: woocommerce-image-wrap
 * WC requires at least: 3.0.5
 * WC tested up to: 3.0.5

License: GPLv2 or later

  Copyright 2017 Kai Stärk web solutions

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

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