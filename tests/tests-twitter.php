<?php


/**
 * @group ppp_social
 */
class Tests_Twitter extends WP_UnitTestCase {
	public function setUp() {
		parent::setUp();

		$this->_post_id = $this->factory->post->create( array( 'post_title' => 'Test Post', 'post_type' => 'post', 'post_status' => 'publish' ) );

	}

	public function test_twitter_enabled() {
		$this->assertFalse( ppp_twitter_enabled() );
	}

	public function test_registration_function() {
		$services = ppp_tw_register_service();
		$this->assertTrue( in_array( 'tw', $services ) );
	}

	public function test_twitter_icon() {
		$expected = '<span class="dashicons icon-ppp-tw"></span>';
		$this->assertEquals( $expected, ppp_tw_account_list_icon() );
	}

	public function test_twitter_account() {
	}

}
