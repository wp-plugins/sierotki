<?php
/*
Plugin Name: Sierotki
Plugin URI: http://iworks.pl/2011/02/16/sierotki/
Description: Wtyczka zamienia leżące za sierotkami spacje w jedną twardą.
Author: Marcin Pietrzak
Version: trunk
Author URI: http://iworks.pl/
*/

function iworks_orphan_options()
{
    return array
        (
            'comment_text' => __('Use for comments.', 'iworks_orphan'),
            'the_excerpt'  => __('Use for excerpt.',  'iworks_orphan'),
            'the_content'  => __('Use for content.',  'iworks_orphan'),
        );
}

function iworks_orphan($content)
{
    if ( $content ) {
        return preg_replace('/([ >\(]+)([aiouwz]|że|za|na|od|nad|pod|to|ale|we|do|ul\.|po|nr) +/i', "$1$2&nbsp;", $content);
    }
    return $content;
}

function iworks_orphan_option_page()
{
    ?>
<div class="wrap">
    <h2><?php _e('Orphan', 'iworks_orphan') ?></h2>
    <!--form method="post" action="themes.php?page=<?php echo basename(__FILE__); ?>"-->
    <form method="post" action="options.php">
        <?php settings_fields('iworks_orphan'); ?>
        <table class="form-table">
            <tbody>
<?php
    foreach ( iworks_orphan_options() as $filter => $description ) {
        $field = 'iworks_orphan_'.$filter;
        printf
            (
                '<tr><td><label for="%s"><input type="checkbox" name="%s" value="1"%s id="%s"/> %s</label></td></tr>',
                $field,
                $field,
                (get_option($field, 1) == 1)?' checked="checked"':'',
                $field,
                $description
            );
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
    if (function_exists('add_submenu_page')) {
        add_theme_page( __('Orphan', 'iworks_orphan'),  __('Orphan', 'iworks_orphan'), 'edit_post', basename(__FILE__), 'iworks_orphan_option_page' );
    }
}

function iworks_orphan_admin_init()
{
    foreach ( array_keys(iworks_orphan_options()) as $filter ) {
        register_setting('iworks_orphan', 'iworks_orphan_'.$filter, 'absint');
    }
}

function iworks_orphan_init()
{
    $mo_file = dirname(__FILE__).'/languages/'.get_locale().'.mo';
    if (file_exists($mo_file) && is_readable($mo_file)) {
        load_textdomain('iworks_orphan', $mo_file);
    }
    if (get_option('iworks_orphan_initialized', 0 ) == 0) {
        foreach ( array_keys(iworks_orphan_options()) as $filter ) {
            update_option('iworks_orphan_'.$filter, 1);
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
