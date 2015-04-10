<?php


/**
 * @group ppp_filters
 */
class Tests_Filters extends WP_UnitTestCase {
	public function setUp() {
		parent::setUp();
	}

	public function test_bitly_filters() {
		global $wp_filter;
		$this->assertarrayHasKey( 'ppp_apply_bitly', $wp_filter['ppp_apply_shortener-bitly'][10] );
	}

	public function test_facebook_filters() {
		global $wp_filter;
		$this->assertarrayHasKey( 'ppp_fb_register_service', $wp_filter['ppp_register_social_service'][10] );
		$this->assertarrayHasKey( 'ppp_fb_account_list_icon', $wp_filter['ppp_account_list_icon-fb'][10] );
		$this->assertarrayHasKey( 'ppp_fb_account_list_avatar', $wp_filter['ppp_account_list_avatar-fb'][10] );
		$this->assertarrayHasKey( 'ppp_fb_account_list_name', $wp_filter['ppp_account_list_name-fb'][10] );
		$this->assertarrayHasKey( 'ppp_fb_account_list_actions', $wp_filter['ppp_account_list_actions-fb'][10] );
		$this->assertarrayHasKey( 'ppp_fb_account_list_extras', $wp_filter['ppp_account_list_extras-fb'][10] );
		$this->assertarrayHasKey( 'ppp_fb_query_vars', $wp_filter['query_vars'][10] );
		$this->assertarrayHasKey( 'ppp_fb_add_meta_tab', $wp_filter['ppp_metabox_tabs'][10] );
		$this->assertarrayHasKey( 'ppp_fb_register_metabox_content', $wp_filter['ppp_metabox_content'][10] );
	}

	public function test_twitter_filters() {
		global $wp_filter;
	}

	public function test_linkedin_filters() {
		global $wp_filter;
	}

}
