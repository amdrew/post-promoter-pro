<?php


/**
 * @group ppp_general
 */
class Tests_General extends WP_UnitTestCase {
	public function setUp() {
		parent::setUp();

		$this->_post_id = $this->factory->post->create( array( 'post_title' => 'Test Post', 'post_type' => 'post', 'post_status' => 'publish' ) );
	}

	public function test_session_start() {
		$this->assertFalse( ppp_maybe_start_session() );
	}

	public function test_link_tracking() {
		global $ppp_share_settings;

		$this->assertFalse( ppp_link_tracking_enabled() );

		$ppp_share_settings['analytics'] = '1';
		$this->assertTrue( ppp_link_tracking_enabled() );
	}

	public function test_post_slug_by_id() {
		$this->assertEquals( 'test-post', ppp_get_post_slug_by_id( $this->_post_id ) );
	}

	public function test_text_tokens() {
		$expected = array(
			array( 'token' => 'post_title', 'description' => 'The title of the post being shared' ),
			array( 'token' => 'site_title', 'description' => 'The site title, from Settings > General' )
		);

		$this->assertEquals( $expected, ppp_get_text_tokens() );

	}

	public function test_entities_and_slashes() {

		$string = 'There\'s something missing here &amp; here';
		$expected = "There's something missing here & here";
		$this->assertEquals( $expected, ppp_entities_and_slashes( $string ) );

	}


}
