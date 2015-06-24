<?php


/**
 * @group ppp_social
 */
class Tests_Facebook extends WP_UnitTestCase {
	public function setUp() {
		parent::setUp();

		$this->_post_id = $this->factory->post->create( array( 'post_title' => 'Test Post', 'post_type' => 'post', 'post_status' => 'publish' ) );

	}

	public function test_facebook_enabled() {
		$this->assertFalse( ppp_facebook_enabled() );
	}

	public function test_registration_function() {
		$services = ppp_fb_register_service();
		$this->assertTrue( in_array( 'fb', $services ) );
	}

	public function test_facebook_icon() {
		$expected = '<span class="dashicons icon-ppp-fb"></span>';
		$this->assertEquals( $expected, ppp_fb_account_list_icon() );
	}

}
