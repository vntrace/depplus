<?php

/**
 * Class Yoast_Product
 *
 * @todo create a license class and store an object of it in this class
 */
class Yoast_Product {

	/**
	 * @var string The URL of the shop running the EDD API.
	 */
	protected $api_url;

	/**
	 * @var string The item name in the EDD shop.
	 */
	protected $item_name;

	/**
	 * @var string The theme slug or plugin file
	 */
	protected $slug;

	/**
	 * @var string The version number of the item
	 */
	protected $version;

	/**
	 * @var string The absolute url on which users can purchase a license
	 */
	protected $item_url;

	/**
	 * @var string Absolute admin URL on which users can enter their license key.
	 */
	protected $license_page_url;

	/**
	 * @var string The text domain used for translating strings
	 */
	protected $text_domain;

	/**
	 * @var string The item author
	 */
	protected $author;

	public function __construct( $api_url, $item_name, $slug, $version, $item_url = '', $license_page_url = '#', $text_domain = 'yoast', $author = 'Yoast' ) {
		$this->api_url          = $api_url;
		$this->item_name        = $item_name;
		$this->slug             = $slug;
		$this->version          = $version;
		$this->item_url         = $item_url;
		$this->license_page_url = admin_url( $license_page_url );
		$this->text_domain      = $text_domain;
		$this->author           = $author;

		// Fix possible empty item url
		if ( $this->item_url === '' ) {
			$this->item_url = $this->api_url;
		}
	}


	/**
	 * @param string $api_url
	 */
	public function set_api_url( $api_url ) {
		$this->api_url = $api_url;
	}

	/**
	 * @return string
	 */
	public function get_api_url() {
		return $this->api_url;
	}

	/**
	 * @param string $author
	 */
	public function set_author( $author ) {
		$this->author = $author;
	}

	/**
	 * @return string
	 */
	public function get_author() {
		return $this->author;
	}

	/**
	 * @param string $item_name
	 */
	public function set_item_name( $item_name ) {
		$this->item_name = $item_name;
	}

	/**
	 * @return string
	 */
	public function get_item_name() {
		return $this->item_name;
	}

	/**
	 * @param string $item_url
	 */
	public function set_item_url( $item_url ) {
		$this->item_url = $item_url;
	}

	/**
	 * @return string
	 */
	public function get_item_url() {
		return $this->item_url;
	}

	/**
	 * @param string $license_page_url
	 */
	public function set_license_page_url( $license_page_url ) {
		$this->license_page_url = admin_page( $license_page_url );
	}

	/**
	 * @return string
	 */
	public function get_license_page_url() {
		return $this->license_page_url;
	}

	/**
	 * @param string $slug
	 */
	public function set_slug( $slug ) {
		$this->slug = $slug;
	}

	/**
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * @param string $text_domain
	 */
	public function set_text_domain( $text_domain ) {
		$this->text_domain = $text_domain;
	}

	/**
	 * @return string
	 */
	public function get_text_domain() {
		return $this->text_domain;
	}

	/**
	 * @param string $version
	 */
	public function set_version( $version ) {
		$this->version = $version;
	}

	/**
	 * @return string
	 */
	public function get_version() {
		return $this->version;
	}

}