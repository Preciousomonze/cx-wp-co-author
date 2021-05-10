<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class CX_COA_Co_Authors.
 */
class CX_COA_Co_Authors {


    /**
     * Initialiseeee
     */
    public static function init() {

        // Register meta key in REST.
        add_action( 'init', array( __CLASS__, 'register_meta' ) );

		// Add coauthors data to post.
		add_filter( 'the_content', array( __CLASS__, 'co_authors_post_link' ) );

    }

	/**
	 * Register meta keys for block editor.
	 * 
	 * @since 1.0.0
	 */
	public static function register_meta() {
        // Co-Authors.
		register_meta( 'post', 'cx_coa_co_authors', array(
			'show_in_rest'      => true,
			'type'              => 'string',
			'single'            => true,
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => function() { 
				return current_user_can( 'edit_posts' );
			}
		));

    }
   
	/**
	 * Get the Co Authors Details.
	 *
	 * @param int $post_id
	 * @param int $current_author (optional) Helps to skip the current author if theres a valid int.
	 * @return array
	 */
	public static function get_co_authors( $post_id, $current_author = 0 ) {
		$co_authors = get_post_meta( $post_id, 'cx_coa_co_authors', true );

		if ( empty( $co_authors ) ) {
			return array();
		}

		$co_authors_details = array();
		$co_authors_data = explode( ',', $co_authors );

        foreach( $co_authors_data as $co_author ) {
			$user = get_user_by( 'login', $co_author );

			if ( $user && $user->ID !== (int)$current_author ) {
				$_co_authors_details['user_obj'] = $user;

				// Get the meta.
				$meta = get_user_meta( $user->ID );
				$co_authors_details[] = array_merge( $_co_authors_details, array( 'meta' => $meta ) );
			}
		}

		return $co_authors_details;
	}

	/**
	 * Adds the coauthors details to the post.
	 *
	 * @param string $content
	 * @return string
	 * @since 1.0.0
	 */
	public static function co_authors_post_Link( $content ) {
		global $post;

		// Post type we're capturing.
        $post_types = array( 'post', 'page' );
        
		// Only continue when $post is valid and its a supported post type.
       	if ( ! $post || ! in_array( $post->post_type, $post_types, true ) ) {
			return $content;
		}

        $co_authors = self::get_co_authors( $post->ID, $post->post_author );
        
        if ( empty( $co_authors ) ) {
            return $content;
        }

        // add it to a span tag with data attribute.
        $co_authors_span_html = self::get_co_authors_span_html( $co_authors );

		// Display this after the content.
		$co_authors_banner_html = self::get_co_authors_banner_html( $co_authors );

		return $co_authors_span_html . $content . $co_authors_banner_html;
	}

	/**
	 * Get Co Author Span HTML.
	 *
	 * Gets the data and returns it as html.
	 *
	 * @param array $co_authors
	 * @return string The span class ready to be displayed.
	 */
	public static function get_co_authors_span_html( $co_authors ) {
		$span_data = array();

        foreach ( $co_authors as $co_author_details ) {
			$co_author = $co_author_details['user_obj'];
    
           	$span_data[] = array(
            	'link' => ( ! empty( $co_author->user_url ) ? $co_author->user_url : get_author_posts_url( $co_author->ID ) ),
                'name' => $co_author->display_name,
            );
            
        }

        // Add it to a span tag with data attribute.
        return '<span class="cx-coa-authors-data" data-cx_coa_co_authors=\'' . json_encode( $span_data ) . '\'></span>';
	}

	/**
	 * Get Co Author banner data.
	 *
	 * Gets the author data and displays it with pics and stuff.
	 *
	 * @param string $co_authors
	 * @return string
	 */
	public static function get_co_authors_banner_html( $co_authors ) {
		$html = '<hr><section class="cx-coa-authors-banner><div class="row">';
		$author_count = count( $co_authors );

        foreach ( $co_authors as $co_author_details ) {
			$co_author      = $co_author_details['user_obj'];
			$co_author_meta = $co_author_details['meta'];

			$col_value = 12/$author_count;

			$first_last_name = ( ! empty( $co_author_meta['firstname'][0] ) && ! empty( $co_author_meta['lastname'][0] ) 
			? $co_author_meta['firstname'][0]  . ' ' . $co_author_meta['lastname'][0] : $co_author->display_name );

           	$html .= '<div class="cx-coa-author-details col-sm-' . $col_value . '">
			   <div class="row">
			   	<div class="col-sm-12 img-holder">
				   <img src="' . get_avatar_url( $co_author->ID ) . '" alt="' . $co_author->display_name . '">
				</div>
				<div class="col-sm-12 name">
				<h3>' . $first_last_name. '</h3>
				<h4 class="username">' .  $co_author->user_nicename. '</h4>
				<p class="desc">' . $co_author_meta['description'][0] . '</p>
				<hr>
				</div>
			   </div>
 
			</div>';
        }

		$html .= '</div></section>';
		return $html;

	}

}

CX_COA_Co_Authors::init();
