<?php

/**
 * Class Post_model
 * Model made in order to store posts that the bestuur has created.
 * Note: The current board never used this....
 */
class Post_model extends CI_Model {
	const POSTS_TABLE = 'posts';
	const POST_ID = 'post_id';
	const FACEBOOK_POSTS_TABLE = 'facebook_posts';
	const FACEBOOK_POST_ID = 'post_id';

	/**
	 * Add a post to the database
	 *
	 * @param $data array The data from the post
	 *
	 * @return int The number of rows affected (hopefully 1)
	 */
	public function add_post( $data ) {
		$this->db->set( $data );
		$this->db->insert( $this::POSTS_TABLE );

		return $this->db->affected_rows();
	}

	/**
	 * Delete a post from the database
	 *
	 * @param $id int The id that is to be deleted
	 *
	 * @return int The number of rows affected (hopefully 1)
	 */
	public function delete_post( $id ) {
		$this->db->delete( $this::POSTS_TABLE, [ $this::POST_ID => $id ] );

		return $this->db->affected_rows();
	}

	/**
	 * Get all the posts from the database and show the newest post first
	 * @return stdClass All the posts currently in the database.
	 */
	public function get_posts() {
		$this->db->order_by( "post_timestamp", "desc" );
		$query = $this->db->get( $this::POSTS_TABLE );

		return $query->result();
	}

	/**
	 * Insert a post into the database. The function also checks if the ID does not yet exist.
	 *
	 * @param $data array The post
	 *
	 * @return int The number of affected rows.
	 */
	public function insert_facebook_post( $data ) {
		$exists = $this->get_facebook_post_by_id( $data['id'] );
		if( $exists !== FALSE )
			return 0;
		$this->db->set( $data );
		$this->db->insert( $this::FACEBOOK_POSTS_TABLE );

		return $this->db->affected_rows();
	}

	/**
	 * Function to get a facebookpost from our database by id
	 *
	 * @param $id int The id of the post
	 *
	 * @return bool | array On success return the array
	 *                      On failure return FALSE (ID does not yet exist)
	 */
	public function get_facebook_post_by_id( $id ) {
		$query  = $this->db->get_where( $this::FACEBOOK_POSTS_TABLE, [ 'id' => $id ] );
		$result = $query->result();

		return empty( $result ) ? FALSE : $result;
	}

	/**
	 * Function to get all the facebookposts from our database, limit by 5
	 * @return array An array of facebook posts
	 */
	public function get_facebook_posts() {
		$this->db->order_by( "created", "desc" );
		$this->db->limit( 5 );
		$query = $this->db->get( $this::FACEBOOK_POSTS_TABLE );

		return $query->result();
	}
}