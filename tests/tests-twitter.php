<?php


/**
 * @group ppp_twitter
 */
class Tests_Twitter extends WP_UnitTestCase {
	public function setUp() {
		parent::setUp();

		$this->_post_id = $this->factory->post->create( array( 'post_title' => 'Test Post', 'post_type' => 'post', 'post_status' => 'publish' ) );
	}

	public function test_twitter_enabled() {
		$this->assertFalse( ppp_twitter_enabled() );
	}

}
