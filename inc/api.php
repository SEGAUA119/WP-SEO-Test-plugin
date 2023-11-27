<?php

class WPSS_API
{

    public function __construct()
    {
        $this->register_endpoints();
    }

    /**
     * @return void
     */
    public function register_endpoints() : void
    {
        add_action('wp_ajax_search_posts', array($this, 'search_posts'));
        add_action('wp_ajax_update_posts', array($this, 'update_posts'));
    }

    /**
     * @param $nonce
     * @return void
     */
    private function check_nonce($nonce ) : void
    {
        if ( !wp_verify_nonce( $nonce, WPSEO_SEARCH_BASENAME ) ) {
            header( 'Status: 403 Forbidden' );
            header( 'HTTP/1.1 403 Forbidden' );
            exit();
        }
    }

    /**
     * @return void
     */
    public function search_posts() : void
    {
        $this->check_nonce( $_GET['nonce'] );
        if( !empty( $_GET['search'] ) ) {
            $db = new WPSS_DB_Search( $_GET['search'] );
            echo WPSS_Page_Template::format_posts($db->get_searched_posts( $_GET['search'] ));
        }
        wp_die();
    }

    /**
     * @return bool
     */
    public function update_posts() : void
    {
        $this->check_nonce( $_REQUEST['nonce'] );
        if( !empty( $_REQUEST['search'] ) && isset( $_REQUEST['update'] ) ) {
            $posts = array();
            $db = new WPSS_DB_Search( $_REQUEST['search'] );
            if( $_REQUEST['field'] === 'title' ) {
                $posts = $db->update_posts_title( $_REQUEST['update'] );
            } elseif( $_REQUEST['field'] === 'content' ) {
                $posts = $db->update_posts_content( $_REQUEST['update'] );
            } elseif(  $_REQUEST['field'] === 'seo-title'  ) {
                $posts = $db->update_posts_meta_title( $_REQUEST['update'] );
            } elseif( $_REQUEST['field'] === 'seo-content' ) {
                $posts = $db->update_posts_meta_desc( $_REQUEST['update'] );
            }
            echo WPSS_Page_Template::format_posts($posts);
        }
        wp_die();
    }
}