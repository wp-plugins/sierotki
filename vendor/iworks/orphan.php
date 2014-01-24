<?php
/*
Plugin Name: Sierotki
Plugin URI: http://iworks.pl/2011/02/16/sierotki/
Description: Wtyczka zamienia leżące za sierotkami spacje w jedną twardą.
Author: Marcin Pietrzak
Version: trunk
Author URI: http://iworks.pl/
*/

class iworks_orphan
{
    private $options;
    private $admin_page;

    public function __construct()
    {
        /**
         * l10n
         */
        load_plugin_textdomain( 'iworks_orphan', false, dirname( plugin_basename( dirname(dirname(__FILE__))) ).'/languages' );

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

    public function replace($content)
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
        /**
         * replace space in numbers
         */
        $content = preg_replace( '/(\d) (\d)/', "$1&nbsp;$2", $content );
        /**
         * single letters
         */
        $re = '/([ >\(]+)([aiouwz]|'.preg_replace('/\./', '\.', implode('|', $therms)).') +/i';
        $content = preg_replace( $re, "$1$2&nbsp;", $content );
        /**
         * single letter after previous orphan
         */
        $re = '/(&nbsp;)([aiouwz]) +/i';
        $content = preg_replace( $re, "$1$2&nbsp;", $content );
        /**
         * return
         */
        return $content;
    }

    public function option_page()
    {
?>
<div class="wrap">
    <h2><?php _e('Orphans', 'iworks_orphan') ?></h2>
    <div class="postbox-container" style="width:75%">
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
        </div>
        <div class="postbox-container" style="width:23%;margin-left:2%">
            <div class="metabox-holder">
                <div id="links" class="postbox">
                    <h3 class="hndle"><?php _e( 'Loved this Plugin?', 'iworks_orphan' ); ?></h3>
                    <div class="inside">
                        <p><?php _e( 'Below are some links to help spread this plugin to other users', 'iworks_orphan' ); ?></p>
                        <ul>
                            <li><a href="http://wordpress.org/extend/plugins/sierotki/"><?php _e( 'Give it a 5 star on Wordpress.org', 'iworks_orphan' ); ?></a></li>
                            <li><a href="http://wordpress.org/extend/plugins/sierotki/"><?php _e( 'Link to it so others can easily find it', 'iworks_orphan' ); ?></a></li>
                        </ul>
                    </div>
                </div>
                <div id="help" class="postbox">
                    <h3 class="hndle"><?php _e( 'Need Assistance?', 'iworks_orphan' ); ?></h3>
                    <div class="inside">
                        <p><?php _e( 'Problems? The links bellow can be very helpful to you', 'iworks_orphan' ); ?></p>
                        <ul>
                            <li><a href="<?php _e( 'http://wordpress.org/support/plugin/sierotki', 'iworks_orphan' ); ?>"><?php _e( 'Wordpress Help Forum', 'iworks_orphan' ); ?></a></li>
                            <li><a href="mailto:<?php echo antispambot('marcin@iworks.pl'); ?>"><?php echo antispambot( 'marcin@iworks.pl' ); ?></a></li>
                        </ul>
                        <hr />
                        <p class="description"><?php _e('Created by: ', 'iworks_orphan' ); ?> <a href="http://iworks.pl/"><span>iWorks.pl</span></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div><?php
    }

    public function admin_menu()
    {
        if ( function_exists( 'add_theme_page' ) ) {
            $this->admin_page = add_theme_page( __( 'Orphan', 'iworks_orphan'),  __('Orphan', 'iworks_orphan'), 'manage_options', basename(__FILE__), array( &$this, 'option_page' ) );
            add_action( 'load-'.$this->admin_page, array( &$this, 'add_help_tab' ) );
        }
    }

    public function add_help_tab()
    {
        $screen = get_current_screen();
        if ( $screen->id != $this->admin_page ) {
            return;
        }
        // Add my_help_tab if current screen is My Admin Page
        $screen->add_help_tab( array(
            'id'    => 'overview',
            'title' => __( 'Orphans', 'iworks_orphan' ),
            'content'   => '<p>' . __( 'Plugin fix some Polish gramary rules with orphans.', 'iworks_orphan' ) . '</p>',
        ) );
            /**
             * make sidebar help
             */
            $screen->set_help_sidebar(
                '<p><strong>' . __( 'For more information:' ) . '</strong></p>' .
                '<p>' . __( '<a href="http://wordpress.org/extend/plugins/sierotki/" target="_blank">Plugin Homepage</a>', 'iworks_orphan' ) . '</p>' .
                '<p>' . __( '<a href="http://wordpress.org/support/plugin/sierotki/" target="_blank">Support Forums</a>', 'iworks_orphan' ) . '</p>' .
                '<p>' . __( '<a href="http://iworks.pl/en/" target="_blank">break the web</a>', 'iworks_orphan' ) . '</p>'
            );

    }

    public function admin_init()
    {
        foreach ( $this->options as $filter => $option) {
            $sanitize_callback = isset($option['sanitize_callback'])? $option['sanitize_callback']:null;
            register_setting('iworks_orphan', 'iworks_orphan_'.$filter, $sanitize_callback);
        }
        add_filter( 'plugin_row_meta', array( &$this, 'links' ), 10, 2 );
    }

    public function init()
    {
        if ( 0 == get_option('iworks_orphan_initialized', 0 ) ) {
            foreach ( $this->options as $filter => $option ) {
                if ( !isset( $option['type'] ) ) {
                    $option['type'] = 'undefinied';
                }
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

    public function links($links, $file)
    {
        if ( $file == plugin_basename(__FILE__) ) {
            if ( !is_multisite() ) {
                $dir = explode('/', dirname(__FILE__));
                $dir = $dir[ count( $dir ) - 1 ];
                $links[] = '<a href="themes.php?page='.$dir.'.php">' . __('Settings') . '</a>';
            }
            $links[] = '<a href="http://iworks.pl/donate/sierotki.php">' . __('Donate') . '</a>';
        }
        return $links;
    }
}
