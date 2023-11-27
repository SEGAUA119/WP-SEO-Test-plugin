global $wpdb;

        $query = "
                SELECT $wpdb->posts.post_title, $wpdb->posts.post_content, {$wpdb->postmeta}.meta_value
                FROM $wpdb->posts
                INNER JOIN $wpdb->postmeta
                ON $wpdb->posts.ID = $wpdb->postmeta.post_id
                WHERE
                    (
                        $wpdb->postmeta.meta_key = '_yoast_wpseo_title'
                        AND $wpdb->postmeta.meta_value LIKE '%{$wpdb->esc_like( $search )}%'
                    )
                OR
                    (
                        $wpdb->postmeta.meta_key = '_yoast_wpseo_metadesc'
                        AND $wpdb->postmeta.meta_value LIKE '%{$wpdb->esc_like( $search )}%'
                    )
                OR $wpdb->posts.post_title LIKE '%{$wpdb->esc_like( $search )}%'
                OR $wpdb->posts.post_content LIKE '%{$wpdb->esc_like( $search )}%'
        ";
        $query = $wpdb->prepare( $query, $search );

return $wpdb->get_results( $query );
