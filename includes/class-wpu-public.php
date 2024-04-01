<?php
/**
 * Public class to handle frontend files
 *
 * @package WP Unit
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Public Pages Class
 *
 * Handles all the different features and functions
 * for the front end pages.
 *
 * @package WP Unit
 * @since 1.0.0
 */
class Wpu_Public {

	/**
	 * Create a new namespace and endpoint
	 *
	 * @param string $rest column name.
	 * @since 1.0.0
	 */
	public function wpu_post_endpoint( $rest ) {
		register_rest_route(
			'wpunit/v1',
			'/post/(?P<id>\d+)',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'wpu_post_details' ),
				'permission_callback' => '__return_true',
			)
		);
		register_rest_route(
			'wpunit/v1',
			'/deletepost/(?P<id>\d+)',
			array(
				'methods'             => 'DELETE',
				'callback'            => array( $this, 'wpu_post_delete_details' ),
				'permission_callback' => function () {
					return current_user_can( 'delete_posts' );
				},
			)
		);
	}

	/**
	 * API endpoint callback function
	 *
	 * @param array $data endpoint passed data.
	 * @since 1.0.0
	 */
	public function wpu_post_details( $data ) {
		$post = get_post( $data['id'] );
		if ( empty( $post ) ) {
			return new WP_Error( 'no_attachment', 'Invalid post id', array( 'status' => 404 ) );
		}
		$post_array['ID']     = $post->ID;
		$post_array['Title']  = $post->post_title;
		$post_array['Desc']   = $post->post_content;
		$post_array['Status'] = $post->post_status;
		$post_array['Date']   = $post->post_date;
		$post_array['Slug']   = $post->post_name;
		$post_array['Link']   = $post->guid;

		return new WP_REST_Response( array( 'message' => $post_array ), 200 );
	}

	/**
	 * API delete post endpoint callback function
	 *
	 * @param array $data endpoint passed data.
	 * @since 1.0.0
	 */
	public function wpu_post_delete_details( $data ) {
		if ( empty( $data['id'] ) ) {
			$message = new WP_REST_Response( array( 'message' => 'Invalid post id' ), 400 );
		}

		$deleted = wp_delete_post( $data['id'], true );
		if ( $deleted ) {
			$message = new WP_REST_Response( array( 'message' => 'Post delete successfully' ), 200 );
		} else {
			$message = new WP_REST_Response( array( 'message' => 'Something went wrong so post deletion failed.' ), 400 );
		}

		return $message;
	}
	public function add_hooks() {
		// Create a new namespace and endpoint.
		add_action( 'rest_api_init', array( $this, 'wpu_post_endpoint' ) );
	}
}
