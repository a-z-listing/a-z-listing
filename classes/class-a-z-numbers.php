<?php
/**
 * Adds and maintains filters providing number functionality to the alphabet
 *
 * @since 2.0.0
 */
class A_Z_Numbers {
	/**
	 * Add filters to append or prepend numbers to the alphabet with optional grouping
	 *
	 * @since 2.0.0
	 */
	public function __construct( $position = 'after', $group = false ) {
		$this->position = $position;
		$this->group = $group;

		add_filter( 'a-z-listing-alphabet', array( $this, 'add_to_alphabet' ) );
		add_filter( 'the-a-z-letter-title', array( $this, 'title' ) );
	}

	/**
	 * Remove the numbers filters we added previously
	 *
	 * @since 2.0.0
	 */
	public function teardown() {
		remove_filter( 'a-z-listing-alphabet', array( $this, 'add_to_alphabet' ) );
		remove_filter( 'the-a-z-letter-title', array( $this, 'title' ) );
	}

	/**
	 * Add numbers to the alphabet
	 *
	 * @since 2.0.0
	 * @param string $alphabet The alphabet to add numbers into
	 * @return string The alphabet with numbers either prepended or appended
	 */
	public function add_to_alphabet( $alphabet ) {
		$numbers = '0,1,2,3,4,5,6,7,8,9';
		if ( true === $this->group ) {
			$numbers = '0123456789';
		}

		if ( 'before' === $position ) {
			return join( ',', array( $numbers, $alphabet ) );
		}
		return join( ',', array( $alphabet, $numbers ) );
	}

	/**
	 * Override the group title for grouped numbers
	 *
	 * @since 2.0.0
	 * @param string $letter The original title of the group
	 * @return string The new title for the group
	 */
	public function title( $letter ) {
		if ( '0' === $letter && true === $this->group ) {
			return '0-9';
		}
		return $letter;
	}
}
