<?php

require_once 'db.php';
require_once 'api.php';
require_once 'page-template.php';

class WPSS
{
    /**
     * @var WPSS_API
     */
    private $api;
    /**
     * @var WPSS_Page_Template
     */
    private $template;

    public function __construct()
    {
        $this->api      = new WPSS_API();
        $this->template = new WPSS_Page_Template();
        $this->init();
    }

    /**
     * @return void
     */
    private function init() : void
    {
        add_action( 'admin_enqueue_scripts', array($this, 'admin_enqueue_scripts') );
    }

    /**
     * @param $hook
     * @return void
     */
    public function admin_enqueue_scripts($hook ): void
    {
        if( 'toplevel_page_wp-seo-search' != $hook ) return;
        wp_enqueue_style(
            'admin-' . WPSEO_SEARCH_BASENAME . '-style',
            WPSEO_SEARCH_URL . '/assets/style.css'
        );
        wp_enqueue_script(
            'admin-' . WPSEO_SEARCH_BASENAME . '-script',
            WPSEO_SEARCH_URL . '/assets/script.js',
            array( 'jquery' )
        );
        wp_localize_script(
            'admin-' . WPSEO_SEARCH_BASENAME . '-script',
            'ajax',
            array(
                'url'   => admin_url( 'admin-ajax.php'),
                'nonce'     => wp_create_nonce( WPSEO_SEARCH_BASENAME )
            )
        );
    }
}

$WPSS = new WPSS();
