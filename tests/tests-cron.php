<?php


/**
 * @group ppp_cron
 */
class Tests_Cron extends WP_UnitTestCase {
	public function setUp() {
		parent::setUp();

		$this->_post_id = $this->factory->post->create( array( 'post_title' => 'Test Post', 'post_type' => 'post', 'post_status' => 'publish' ) );

		add_filter( 'ppp_get_scheduled_crons', array( $this, 'add_crons' ) );
	}

	public function test_get_scheduled_crons() {
		$scheduled_crons = ppp_get_shceduled_crons();

		$this->assertInternalType( 'array', $scheduled_crons );
		$this->assertTrue( ! empty( $scheduled_crons ) );
	}

	public function test_ppp_has_cron_within() {
		$current_time = current_time( 'timestamp' );

		$this->assertTrue( ppp_has_cron_within() );
		$this->assertTrue( ppp_has_cron_within( $current_time ) );
		$this->assertFalse( ppp_has_cron_within( $current_time + WEEK_IN_SECONDS, 60 ) );
		$this->assertFalse( ppp_has_cron_within( $current_time - WEEK_IN_SECONDS, 60 ) );

	}

	public function add_crons() {
		$test_crons = array(
			'ef1e2ad70394f45f6281fe7281be8c2e' => array(
				'schedule' => false,
				'args'     => array(
					$this->_post_id,
					'sharedate_3_' . $this->_post_id
				),
				'timestamp' => current_time( 'timestamp' )
			)
		);

		return $test_crons;

	}

}
