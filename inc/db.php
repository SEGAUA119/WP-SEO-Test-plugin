<?php

class WPSS_DB_Search {

    /**
     * @var array
     */
    private $ids;
    private $search;
    private bool $search_completed;

    public function __construct( string $search )
    {
        $this->search = $search;
        $this->search_completed = FALSE;
    }

    public function update_posts_title( string $update ) {
        $this->ids = $this->get_searched_post_ids();
        foreach( $this->ids as $post_id ) {
            print_r(get_the_title( $post_id ));
            wp_update_post(array(
                'ID'    => $post_id,
                'post_title'    => str_ireplace($this->search, $update, get_the_title( $post_id ))
            ));
        }
        return $this->get_searched_posts();
    }

    public function update_posts_content( string $update ) {
        $this->ids = $this->get_searched_post_ids();
        foreach( $this->ids as $post_id ) {
            wp_update_post(array(
                'ID'    => $post_id,
                'post_content'    => str_ireplace($this->search, $update, get_post_field('post_content', $post_id))
            ));
        }
        return $this->get_searched_posts();
    }

    public function update_posts_meta_title( string $update ) {
        $this->ids = $this->get_searched_post_ids();
        foreach( $this->ids as $post_id ) {
            $meta = get_post_meta( $post_id, '_yoast_wpseo_title', TRUE );
            update_post_meta( $post_id, '_yoast_wpseo_title', str_ireplace($this->search, $update, $meta) );
        }
        return $this->get_searched_posts();
    }

    public function update_posts_meta_desc( string $update ) {
        $this->ids = $this->get_searched_post_ids();
        foreach( $this->ids as $post_id ) {
            $meta = get_post_meta( $post_id, '_yoast_wpseo_metadesc', TRUE );
            update_post_meta( $post_id, '_yoast_wpseo_metadesc', str_ireplace($this->search, $update, $meta) );
        }
        return $this->get_searched_posts();
    }

    public function get_searched_posts() : array
    {
        $posts = array();
        if( !$this->search_completed ) {
            $this->ids = $this->get_searched_post_ids();
        }
        if( !empty($this->ids) ) {
            $posts = get_posts( array(
                'numberposts'   => -1,
                'post__in'      => $this->ids
            ) );
        }
        return $posts;
    }

    /**
     * @param string $search
     * @return WP_Post[]
     */
    private function get_searched_post_ids() : array
    {
        global $wpdb;
        $query = "
                SELECT DISTINCT $wpdb->posts.ID
                FROM $wpdb->posts
                INNER JOIN $wpdb->postmeta
                ON $wpdb->posts.ID = $wpdb->postmeta.post_id
                WHERE $wpdb->posts.post_type = 'post'
                AND (
                        (
                            $wpdb->postmeta.meta_key = '_yoast_wpseo_title'
                            AND $wpdb->postmeta.meta_value LIKE '%{$wpdb->esc_like( $this->search )}%'
                        )
                    OR
                        (
                            $wpdb->postmeta.meta_key = '_yoast_wpseo_metadesc'
                            AND $wpdb->postmeta.meta_value LIKE '%{$wpdb->esc_like( $this->search )}%'
                        )
                    OR $wpdb->posts.post_title LIKE '%{$wpdb->esc_like( $this->search )}%'
                    OR $wpdb->posts.post_content LIKE '%{$wpdb->esc_like( $this->search )}%'
                )
                GROUP BY $wpdb->posts.ID
        ";
        $query = $wpdb->prepare( $query );
        $ids = $wpdb->get_results( $query, OBJECT_K );
        if( !empty($ids) ) {
            $ids = array_keys( $ids );
        }

        $this->search_completed = TRUE;

        return $ids;
    }
}