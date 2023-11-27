<?php

class WPSS_Page_Template
{
    public function __construct()
    {
        add_action( 'admin_menu', array($this, 'create_admin_page') );
    }

    /**
     * @return void
     */
    public function create_admin_page(): void
    {
        add_menu_page(
            __('SEO Search', WPSEO_SEARCH_BASENAME),
            __('SEO Search', WPSEO_SEARCH_BASENAME),
            'manage_options',
            WPSEO_SEARCH_BASENAME,
            array( $this, 'load_page' ),
            '',
            10
        );
    }

    /**
     * @return void
     */
    public function load_page() : void
    {
        $html = '';
        $html .= $this->notice_template();
        $html .= $this->search_form_template();
        $html .= $this->table_template();
        $page_title = __('Search Posts', WPSEO_SEARCH_BASENAME);
        printf(
            '<div class="wrap"><h1 class="wp-heading-inline">%s</h1>%s</div>',
            $page_title,
            $html
        );
    }

    /**
     * @param WP_Post[] $posts
     * @return string
     */
    public static function format_posts( array $posts ) : string
    {
        $html = '';
        if( !empty($posts) ) {
            foreach( $posts as $post ) {
                $html .= sprintf(
                    '<tr class="post-data" data-post-id="%d"><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',
                    $post->ID,
                    get_the_title( $post ),
                    get_the_excerpt( $post ),
                    get_post_meta( $post->ID, '_yoast_wpseo_title', TRUE ),
                    get_post_meta( $post->ID, '_yoast_wpseo_metadesc', TRUE )
                );
            }
        }
        return $html;
    }

    /**
     * @return string
     */
    private function notice_template() : string
    {
        return sprintf(
            '<div id="update-success" style="display: none;" class="notice notice-success">
                        <p>%s</p>
                    </div>',
            __( "Done!", WPSEO_SEARCH_BASENAME )
        );
    }

    /**
     * @return string
     */
    private function table_template() : string
    {
        $html = '<table class="wpss-table wp-list-table widefat fixed striped table-view-list posts">
                    <thead><tr>%s</tr></thead>
                    <tbody id="wpss-posts-list"></tbody>
                </table>';
        $forms_html = '';
        $fields = array(
            'title' => __('Post title', WPSEO_SEARCH_BASENAME),
            'content' => __('Post content', WPSEO_SEARCH_BASENAME),
            'seo-title' => __('SEO title', WPSEO_SEARCH_BASENAME),
            'seo-content' => __('SEO content', WPSEO_SEARCH_BASENAME)
        );
        foreach( $fields as $slug => $name ) {
            $forms_html .= $this->change_form_template( $slug, $name );
        }
        return sprintf( $html, $forms_html );
    }

    /**
     * @return string
     */
    private function search_form_template() : string
    {
        return '<form id="wpss-search-form" class="wpss-search-form" method="get">
            <p class="search-box">
                <label class="screen-reader-text" for="post-search-input">' . __('Search Posts:', WPSEO_SEARCH_BASENAME) . '</label>
                <input type="search" id="post-search-input" name="s" value="">
                <input type="submit" class="button" value="' . __('Search Posts', WPSEO_SEARCH_BASENAME) . '">
            </p>
            <br class="clear">
        </form>';
    }

    /**
     * @param string $field_slug
     * @param string $field_name
     * @return string
     */
    private function  change_form_template( string $field_slug, string $field_name ) : string
    {
        return sprintf(
            '<th><form class="wpss-change-form" method="post">
                    <p class="search-box">
                        <div class="title">%s</div>
                        <input type="search" id="post-update-input" name="text" value="">
                        <input type="submit" class="button" value="' . __('Update', WPSEO_SEARCH_BASENAME) . '">
                        <input type="hidden" name="field" value="%s">
                    </p>
                </form></th>',
            esc_attr($field_name),
            esc_attr($field_slug)
        );
    }
}