<?php
/**
 * Class SampleTest
 *
 * @package Phpunit
 */

/**
 * Sample test case.
 */
class Wpu_ExampleTest extends WP_UnitTestCase {

	/**
	 * A single example test which will be always success.
	 */
	public function test_wpu_sample() {
		// Replace this with some actual testing code.
		$this->assertTrue( true );
	}

	/**
	 * Check page based tests.
	 */
	public function test_wpu_tsome_page() {
		$this->go_to( '/' );
		$this->assertQueryTrue( 'is_home', 'is_front_page' );

		$this->go_to( '/?p=123456789' );
		$this->assertQueryTrue( 'is_404' );
	}

	/**
	 * Check metadata based tests.
	 */
	public function test_wpu_nds_custom_meta_add() {
		$factory_user_id = $this->factory->user->create( array( 'role' => 'author' ) );
		update_user_meta( $factory_user_id, 'preferred_browser', 'chrome' );
		$get_user_meta = get_user_meta( $factory_user_id, 'preferred_browser', true );

		// an empty string will be returned as the user was not an editor
		$this->assertEquals( $get_user_meta, 'chrome' );
	}

	/**
	 * Create a new post realated testcases.
	 */
	public function test_wpu_create_new_post() {
		$post_id = $this->factory->post->create(
			array(
				'post_title' => 'Hello World',
				'post_type'  => 'post',
			)
		);

		// Add some custom metadata to the post
		add_post_meta( $post_id, 'my_key', 'my_value' );

		// Call the function to retrieve the post metadata
		$metadata = get_post_meta( $post_id, 'my_key', true );

		// Check the expected post title.
		$this->assertEquals( 'Hello World', get_the_title( $post_id ) );
		// Check the expected post type.
		$this->assertEquals( 'post', get_post_type( $post_id ) );

		// Assert that the retrieved metadata matches the expected value
		$this->assertEquals( 'my_value', $metadata );
	}

	/**
	 * latest post related test cases.
	 */
	public function test_wpu_get_latest_posts() {
		// Call the function you want to test
		$posts = wp_get_recent_posts( $output = ARRAY_A );

		if ( ! empty( $posts ) ) {
			// Assert that the returned value is an array
			$this->assertIsArray( $posts );
			// Assert that the returned array is not empty
			$this->assertNotEmpty( $posts );
		} else {
			// Assert that the returned value is an array
			$this->assertIsArray( $posts );
			// Assert that the returned array is empty
			$this->assertEmpty( $posts );
		}
	}
}
