<?php
/*
Plugin Name: Sierotki
Plugin URI: http://iworks.pl/2011/02/16/sierotki/
Description: Wtyczka zamienia leżące za sierotkami spacje w jedną twardą.
Author: Marcin Pietrzak
Version: 2.0
Author URI: http://iworks.pl/
*/

class iWorks_Orphan
{
    private $options;

    public function __construct()
    {
        /**
         * l10n
         */
        load_plugin_textdomain( 'iworks_orphan', false, dirname( plugin_basename( __FILE__) ).'/languages' );

        /**
         * actions
         */
        add_action( 'init',       array( &$this, 'init' ) );
        add_action( 'admin_init', array( &$this, 'admin_init' ) );
        add_action( 'admin_menu', array( &$this, 'admin_menu' ) );

        /**
         * options
         */
        $this->options = array (
            'comment_text' => array ( 'description' => __( 'Use for comments:',      'iworks_orphan' ), 'type'  => 'checkbox', 'label' => __('Enabled the substitution of orphans in the comments.', 'iworks_orphan'), 'sanitize_callback' => 'absint'),
            'the_title'    => array ( 'description' => __( 'Use for post title:',    'iworks_orphan' ), 'type'  => 'checkbox', 'label' => __('Enabled the substitution of orphans in the post_title.', 'iworks_orphan'), 'sanitize_callback' => 'absint'),
            'the_excerpt'  => array ( 'description' => __( 'Use for excerpt:',       'iworks_orphan' ), 'type'  => 'checkbox', 'label' => __('Enabled the substitution of orphans in the excerpt.', 'iworks_orphan'), 'sanitize_callback' => 'absint'),
            'the_content'  => array ( 'description' => __( 'Use for content:',       'iworks_orphan' ), 'type'  => 'checkbox', 'label' => __('Enabled the substitution of orphans in the content.', 'iworks_orphan'), 'sanitize_callback' => 'absint'),
            'own_orphans'  => array ( 'description' => __( 'User definied orphans:', 'iworks_orphan' ), 'label' => __('Use a comma to separate orphans.', 'iworks_orphan'), 'sanitize_callback' => 'esc_html')
        );
    }

    public function replace( $content )
    {
        if ( empty( $content ) ) {
            return;
        }
        $therms = array (
            'al.', 'ale', 'ależ',
            'b.', 'bł.', 'bm.', 'bp', 'br.', 'by', 'bym', 'byś',
            'cyt.', 'cz.', 'czyt.',
            'dn.', 'do', 'doc.', 'dr', 'ds.', 'dyr.', 'dz.',
            'fot.',
            'gdy', 'gdyby', 'gdybym', 'gdybyś', 'gdyż', 'godz.',
            'im.', 'inż.',
            'jw.',
            'kol.', 'komu', 'ks.', 'która', 'którego', 'której', 'któremu', 'który', 'których', 'którym', 'którzy',
            'lic.',
            'max', 'mgr', 'm.in.', 'min', 'moich', 'moje', 'mojego', 'mojej', 'mojemu', 'mój', 'mych', 'na', 'nad', 'np.', 'nr', 'nt.', 'nw.',
            'od', 'oraz', 'os.',
            'p.', 'pl.', 'pn.', 'po', 'pod', 'pot.', 'prof.', 'przed', 'pt.', 'pw.', 'pw.',
            'śp.', 'św.',
            'tamtej', 'tamto', 'tej', 'tel.', 'tj.', 'to', 'twoich', 'twoje', 'twojego', 'twojej', 'twój', 'twych',
            'ul.',
            'we', 'wg', 'woj.',
            'za', 'ze',
            'że', 'żeby', 'żebyś',
        );
        $own_orphans = trim( get_option( 'iworks_orphan_own_orphans', '' ), ' \t,');
        if ( $own_orphans ) {
            $own_orphans = preg_replace('/\,\+/', ',', $own_orphans);
            $therms = array_merge( $therms, preg_split('/,[ \t]*/', strtolower( $own_orphans ) ) );
        }
        $re = '/^([aiouwz]|'.preg_replace('/\./', '\.', implode('|', $therms)).') +/i';
        $content = preg_replace( $re, "$1$2&nbsp;", $content );
        $re = '/([ >\(]+)([aiouwz]|'.preg_replace('/\./', '\.', implode('|', $therms)).') +/i';
        return preg_replace( $re, "$1$2&nbsp;", $content );
    }

    public function option_page()
    {
?>
<div class="wrap">
    <?php screen_icon(); ?>
    <h2><?php _e('Orphan', 'iworks_orphan') ?></h2>
    <form method="post" action="options.php">
        <?php settings_fields('iworks_orphan'); ?>
        <table class="form-table">
            <tbody>
<?php
        foreach ( $this->options as $filter => $option ) {
            $field = 'iworks_orphan_'.$filter;
            printf ('<tr valign="top"><th scope="row">%s</th><td>', $option['description']);
            switch( $option['type'] ) {
            case 'checkbox':
                printf (
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
                printf (
                    '<input type="text" name="%s" value="%s" id="%s" class="regular-text code" />%s',
                    $field,
                    get_option($field, ''),
                    $field,
                    isset($option['label'])? '<p class="description">'.$option['label'].'</p>':''
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

    public function admin_menu()
    {
        if ( function_exists( 'add_theme_page' ) ) {
            add_theme_page( __( 'Orphan', 'iworks_orphan'),  __('Orphan', 'iworks_orphan'), 'manage_options', basename(__FILE__), array( &$this, 'option_page' ) );
        }
    }

    public function admin_init()
    {
        foreach ( $this->options as $filter => $option) {
            $sanitize_callback = isset($option['sanitize_callback'])? $option['sanitize_callback']:null;
            register_setting('iworks_orphan', 'iworks_orphan_'.$filter, $sanitize_callback);
        }
    }

    public function init()
    {

        if ( 0 == get_option('iworks_orphan_initialized', 0 ) ) {
            foreach ( $this->options as $filter => $option ) {
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
        foreach ( array_keys( $this->options ) as $filter ) {
            if ( 1 == get_option( 'iworks_orphan_'.$filter, 1 ) ) {
                add_filter( $filter, array( &$this, 'replace' ) );
            }
        }
    }

}

new iWorks_Orphan();

