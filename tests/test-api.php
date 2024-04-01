<?php
/**
 * Class SampleTest
 *
 * @package Phpunit
 */

/**
 * API test case.
 */
class Wpu_API_Test extends WP_UnitTestCase {
	/**
	 * Function to get post rest API
	 */
	public function test_wpu_get_post_rest_api() {

		$post_data = array(
			'post_title'   => 'Test Post',
			'post_content' => 'Lorem ipsum dolor sit amet.',
			'post_status'  => 'publish',
		);
		$post_id   = wp_insert_post( $post_data );

		// Get the post via REST API
		$response = $this->wpu_perform_rest_request( 'GET', '/wpunit/v1/post/' . $post_id );
		// Assert the response status code is 200
		$this->assertEquals( 200, $response->get_status() );

		// Assert the response contains the post data
		$data = $response->get_data();
		$this->assertEquals( $post_id, $data['message']['ID'] );
		$this->assertEquals( $post_data['post_title'], $data['message']['Title'] );
		$this->assertEquals( $post_data['post_content'], $data['message']['Desc'] );
		$this->assertEquals( $post_data['post_status'], $data['message']['Status'] );

		// Clean up the post
		wp_delete_post( $post_id, true );
	}
	
	/**
	 * Function to delete post rest API
	 */
	public function test_wpu_delete_post_rest_api() {
		// Create a mock post
		$post_data = array(
			'post_title'   => 'Test Post',
			'post_content' => 'Lorem ipsum dolor sit amet.',
			'post_status'  => 'publish',
		);
		$post_id   = wp_insert_post( $post_data );

		$user_id = $this->factory->user->create();
		$user    = new WP_User( $user_id );
		$user->add_cap( 'delete_posts' ); // Replace with the required capability

		// Set the current user to simulate authentication
		wp_set_current_user( $user_id );

		$response = $this->wpu_perform_rest_request( 'DELETE', '/wpunit/v1/deletepost/' . $post_id );

		$this->assertEquals( 200, $response->status );
		$this->assertEquals( 'Post delete successfully', $response->get_data()['message'] );
	}

	/**
	 * Function to delete post without auth rest API
	 */
	public function test_wpu_delete_post_without_auth_rest_api() {
		// Create a mock post
		$post_data = array(
			'post_title'   => 'Test Post',
			'post_content' => 'Lorem ipsum dolor sit amet.',
			'post_status'  => 'publish',
		);
		$post_id   = wp_insert_post( $post_data );

		$response = $this->wpu_perform_rest_request( 'DELETE', '/wpunit/v1/deletepost/' . $post_id );

		$this->assertEquals( 401, $response->status );
		$this->assertEquals( 'Sorry, you are not allowed to do that.', $response->get_data()['message'] );
	}

	/**
	 * Function to perform REST api
	 */
	private function wpu_perform_rest_request( $method, $path ) {
		global $wp_rest_server;

		if ( is_null( $wp_rest_server ) ) {
			$wp_rest_server = new WP_REST_Server();
			do_action( 'rest_api_init' );
		}

		$request  = new WP_REST_Request( $method, $path );
		$response = $wp_rest_server->dispatch( $request );

		return $response;
	}
}
