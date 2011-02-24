<?php
/*
Plugin Name: Sierotki
Plugin URI: http://iworks.pl/2011/02/16/sierotki/
Description: Wtyczka zamienia leżące za sierotkami spacje w jedną twardą.
Author: Marcin Pietrzak
Version: 1.4
Author URI: http://iworks.pl/
*/

function iworks_orphan_options()
{
    return array
        (
            'comment_text' => array ( 'description' => __('Use for comments:', 'iworks_orphan'), 'type' => 'checkbox', 'label' => __('Enabled the substitution of orphans in the comments.', 'iworks_orphan'), 'sanitize_callback' => 'absint'),
            'the_excerpt'  => array ( 'description' => __('Use for excerpt:',  'iworks_orphan'), 'type' => 'checkbox', 'label' => __('Enabled the substitution of orphans in the excerpt.', 'iworks_orphan'), 'sanitize_callback' => 'absint'),
            'the_content'  => array ( 'description' => __('Use for content:',  'iworks_orphan'), 'type' => 'checkbox', 'label' => __('Enabled the substitution of orphans in the content.', 'iworks_orphan'), 'sanitize_callback' => 'absint'),
            'own_orphans'  => array ( 'description' => __('User definied orphans:', 'iworks_orphan'), 'label' => __('Use a comma to separate orphans.', 'iworks_orphan'), 'sanitize_callback' => 'esc_html')
        );
}

function iworks_orphan($content)
{
    if ( $content ) {
        $therms = array
            (
                'ale',
                'do',
                'na',
                'nad',
                'nr',
                'od',
                'po',
                'pod',
                'to',
                'ul.',
                'we',
                'za',
                'że',
                'ks.'
            );
        $own_orphans = get_option('iworks_orphan_own_orphans', '');
        if ($own_orphans) {
            $therms = array_merge($therms, preg_split('/,[ \t]*/', strtolower($own_orphans)));
        }
        $re = '/([ >\(]+)([aiouwz]|'.preg_replace('/\./', '\.', implode('|', $therms)).') +/i';
        return preg_replace($re, "$1$2&nbsp;", $content);
    }
    return $content;
}

function iworks_orphan_option_page()
{
    ?>
<div class="wrap">
    <h2><?php _e('Orphan', 'iworks_orphan') ?></h2>
    <form method="post" action="options.php">
        <?php settings_fields('iworks_orphan'); ?>
        <table class="form-table">
            <tbody>
<?php
    foreach ( iworks_orphan_options() as $filter => $option ) {
        $field = 'iworks_orphan_'.$filter;
        printf ('<tr valign="top"><th scope="row">%s</th><td>', $option['description']);
        switch( $option['type'] ) {
        case 'checkbox':
            printf
                (
                    '<label for="%s"><input type="checkbox" name="%s" value="1"%s id="%s"/> %s</label>',
                    $field,
                    $field,
                    (get_option($field, 1) == 1)?' checked="checked"':'',
                    $field,
                    isset($option['label'])? $option['label']:'&nbsp;'
                );
            break;
        case 'text':
        default:
            printf
                (
                    '<input type="text" name="%s" value="%s" id="%s" class="regular-text code" /> <label for="%s">%s</label>',
                    $field,
                    get_option($field, ''),
                    $field,
                    $field,
                    isset($option['label'])? $option['label']:'&nbsp;'
                );
            break;
        }
        print '</td></tr>';
    }
?>
            </tbody>
        </table>
        <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
    </form>
</div><?php
}

function iworks_orphan_admin_menu()
{
    if (function_exists('add_theme_page')) {
        add_theme_page( __('Orphan', 'iworks_orphan'),  __('Orphan', 'iworks_orphan'), 'edit_posts', basename(__FILE__), 'iworks_orphan_option_page' );
    }
}

function iworks_orphan_admin_init()
{
    foreach ( iworks_orphan_options() as $filter => $option) {
        $sanitize_callback = isset($option['sanitize_callback'])? $option['sanitize_callback']:null;
        register_setting('iworks_orphan', 'iworks_orphan_'.$filter, $sanitize_callback);
    }
}

function iworks_orphan_init()
{
    $mo_file = dirname(__FILE__).'/languages/'.get_locale().'.mo';
    if (file_exists($mo_file) && is_readable($mo_file)) {
        load_textdomain('iworks_orphan', $mo_file);
    }
    if (get_option('iworks_orphan_initialized', 0 ) == 0) {
        foreach ( iworks_orphan_options() as $filter => $option ) {
            switch( $option['type'] ) {
            case 'checkbox':
                update_option('iworks_orphan_'.$filter, 1);
                break;
            case 'text':
            default:
                update_option('iworks_orphan_'.$filter, '');
                break;
            }
        }
        update_option('iworks_orphan_initialized', 1);
    }
    foreach ( array_keys(iworks_orphan_options()) as $filter ) {
        if ( get_option('iworks_orphan_'.$filter, 1) == 1 ) {
            add_filter($filter, 'iworks_orphan');
        }
    }
}

add_action('init',       'iworks_orphan_init');
add_action('admin_init', 'iworks_orphan_admin_init');
add_action('admin_menu', 'iworks_orphan_admin_menu');

?>
