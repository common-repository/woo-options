<?php
/**
 * 
 *
 * @link              http://xaartech.com
 * @since             1.0.1
 * @package           Woocomop
 *
 * @wordpress-plugin
 * Plugin Name:       Remove add to cart
 * Plugin URI:        http://xaartech.com/WooCommerceOptions
 * Description:       Woocommerce options, disable add to cart,remove add to cart, add to cart text, proceed to checkout
 * Version:           1.0.0
 * Author:            xaraar
 * Author URI:        http://xaartech.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocomop
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

add_action('admin_menu', 'woocomop_add_admin_menu');
add_action('admin_init', 'woocomop_settings_init');

if (!function_exists('woocomop_add_admin_menu')) {

    function woocomop_add_admin_menu() {

        add_menu_page('Woocommerce Options', 'Woo Options', 'manage_options', 'woo_basic_features', 'woocomop_options_page');
    }

}

if (!function_exists('woocomop_settings_init')) {

    function woocomop_settings_init() {

        register_setting('pluginPage', 'woocomop_settings');

        add_settings_field(
                'woocomop_disable_add_to_cart_button',
                __('Disable add to cart button',
                        'woocomop'),
                'woocomop_disable_add_to_cart_button_render',
                'pluginPage',
                'woocomop_pluginPage_section'
        );
        add_settings_field(
                'woocomop_disable_add_to_carttext',
                __('Text to show insted of add to cart button',
                        'woocomop'),
                'woocomop_disable_add_to_carttext_render',
                'pluginPage',
                'woocomop_pluginPage_section'
        );

        add_settings_field(
                'woocomop_add_to_cart_text',
                __('Add to cart text',
                        'woocomop'),
                'woocomop_add_to_cart_text_render',
                'pluginPage',
                'woocomop_pluginPage_section'
        );

        add_settings_section(
                'woocomop_pluginPage_section',
                __('',
                        'woocomop'),
                'woocomop_settings_section_callback',
                'pluginPage'
        );

        add_settings_field(
                'woocomop_proceed_to_checkout_button_text_field',
                __('Proceed to Checkout button text',
                        'woocomop'),
                'woocomop_proceed_to_checkout_button_text',
                'pluginPage',
                'woocomop_pluginPage_section'
        );

        add_settings_field(
                'woocomop_disable_shipping_on_cart_page',
                __('Disable shipping info on cart page',
                        'woocomop'),
                'woocomop_disable_shipping_on_cart_page_render',
                'pluginPage',
                'woocomop_pluginPage_section'
        );
    }

}

if (!function_exists('woocomop_proceed_to_checkout_button_text')) {

    function woocomop_proceed_to_checkout_button_text() {
        $options = get_option('woocomop_settings');
        ?>
        <input type='text' name='woocomop_settings[woocomop_proceed_to_checkout_button_text_field]' value='<?php echo $options['woocomop_proceed_to_checkout_button_text_field']; ?>'>
        <span>Leave blank for default</span>
        <?php
    }

}
if (!function_exists('woocomop_add_to_cart_text_render')) {

    function woocomop_add_to_cart_text_render() {

        $options = get_option('woocomop_settings');
        ?>
        <input type='text' name='woocomop_settings[woocomop_add_to_cart_text]' value='<?php echo @$options['woocomop_add_to_cart_text']; ?>'>
        <span>Leave blank for default</span>
        <?php
    }

}
if (!function_exists('woocomop_disable_add_to_carttext_render')) {

    function woocomop_disable_add_to_carttext_render() {

        $options = get_option('woocomop_settings');
        ?>
        <input type='text' name='woocomop_settings[woocomop_disable_add_to_carttext]' value='<?php echo @$options['woocomop_disable_add_to_carttext']; ?>'>
        <span>Leave blank for default</span>
        <?php
    }

}
if (!function_exists('woocomop_disable_shipping_on_cart_page_render')) {

    function woocomop_disable_shipping_on_cart_page_render() {

        $options = get_option('woocomop_settings');
        ?>
        <select name='woocomop_settings[woocomop_disable_shipping_on_cart_page]'>
            <option value='1' <?php selected($options['woocomop_disable_shipping_on_cart_page'], 1); ?>>Yes</option>
            <option value='0' <?php selected($options['woocomop_disable_shipping_on_cart_page'], 0); ?>>No</option>
        </select>

        <?php
    }

}
if (!function_exists('woocomop_disable_add_to_cart_button_render')) {

    function woocomop_disable_add_to_cart_button_render() {

        $options = get_option('woocomop_settings');
        ?>
        <select name='woocomop_settings[woocomop_disable_add_to_cart_button]'>
            <option value='0' <?php selected($options['woocomop_disable_add_to_cart_button'], 0); ?>>No</option>
            <option value='1' <?php selected($options['woocomop_disable_add_to_cart_button'], 1); ?>>Yes</option>
        </select>

        <?php
    }

}

function woocomop_settings_section_callback() {

    echo __('Enables feature in Woocommerce', 'woocomop');
}

if (!function_exists('woocomop_options_page')) {

    function woocomop_options_page() {
        ?>
        <form action='options.php' method='post'>
            <h2>Woocommerce options</h2>
            <?php
            settings_fields('pluginPage');
            do_settings_sections('pluginPage');
            submit_button();
            ?>

        </form>
        <?php
    }

}
if (!function_exists('disable_shipping_calc_on_cart')) {

    function disable_shipping_calc_on_cart($show_shipping) {

        $options = get_option('woocomop_settings');
        if (is_cart() && $options['woocomop_disable_shipping_on_cart_page'] == 1) {
            return false;
        }
        return $show_shipping;
    }

}

add_filter('woocommerce_cart_ready_to_calc_shipping', 'disable_shipping_calc_on_cart', 99);

if (!function_exists('woocommerce_button_proceed_to_checkoutq')) {

    function woocommerce_button_proceed_to_checkoutq() {
        $options = get_option('woocomop_settings');
        if (!empty($options['woocomop_proceed_to_checkout_button_text_field']) && $options['woocomop_proceed_to_checkout_button_text_field'] != '') {


            $checkout_url = WC()->cart->get_checkout_url();
            ?>
            <a href="<?php echo $checkout_url; ?>" class="checkout-button button alt wc-forward"><?php _e($options['woocomop_proceed_to_checkout_button_text_field'], 'woocommerce'); ?></a>
            <?php
        }
    }

}
//single page
add_filter('add_to_cart_text', 'woo_custom_single_add_to_cart_text');                // < 2.1
add_filter('woocommerce_product_single_add_to_cart_text', 'woo_custom_single_add_to_cart_text');  // 2.1 +

//listing page
add_filter( 'add_to_cart_text', 'woo_custom_single_add_to_cart_text' );            // < 2.1
add_filter( 'woocommerce_product_add_to_cart_text', 'woo_custom_single_add_to_cart_text' );  // 2.1 +
    
if (!function_exists('woo_custom_single_add_to_cart_text')) {

    function woo_custom_single_add_to_cart_text() {

        $options = get_option('woocomop_settings');

        if (!empty($options['woocomop_add_to_cart_text']) && $options['woocomop_add_to_cart_text'] != '') {
            return __($options['woocomop_add_to_cart_text'], 'woocommerce');
        }
    }

}

add_action('woocommerce_before_shop_loop_item', 'woocomop_replace_add_to_cart');

if (!function_exists('woocomop_replace_add_to_cart')) {

    function woocomop_replace_add_to_cart() {
        $options = get_option('woocomop_settings');
        if ($options['woocomop_disable_add_to_cart_button'] == 1) {
            add_action('woocommerce_after_shop_loop_item', 'woocomop_add_inqure_us_button');
            remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
        }
    }

}

add_action('woocommerce_before_single_product_summary', 'woocomop_user_filter_addtocart_for_single_product_page');

if (!function_exists('woocomop_user_filter_addtocart_for_single_product_page')) {

    function woocomop_user_filter_addtocart_for_single_product_page() {

        $options = get_option('woocomop_settings');
        if ($options['woocomop_disable_add_to_cart_button'] == 1) {
            add_action('woocommerce_single_product_summary', 'woocomop_add_inqure_us_button');
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
        }
    }

}
if (!function_exists('woocomop_add_inqure_us_button')) {

    function woocomop_add_inqure_us_button($as) {
        $options = get_option('woocomop_settings');
        if (!empty($options['woocomop_disable_add_to_carttext']) && $options['woocomop_disable_add_to_carttext'] != '') {
            echo esc_attr($options['woocomop_disable_add_to_carttext']);
        }
    }

}
