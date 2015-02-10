<?php
/**
 * Html Class
 * ------------------------------------
 * Desc				make JavaScript function
 * first design		Chris chris13jr@gmail.com
 * Date				2011.09.21
 */

Class Authority
{
	protected $cnf = NULL;
	protected $id_tag = "user_id";

	public function __construct ( &$cnf )
	{
		$this->cnf = $cnf;
		$this->info = &$_COOKIE;
	}

	// 로그인 유무
	public function isLogin ()
	{
		return ($this->info[$this->id_tag] != NULL || $this->info[$this->id_tag] != "") ? true : false;
	}

	// 회원
	public function isMember ()
	{
		// XXX : 권한은???
		return ( $this->isLogin() ) ? true : false;
	}

	// 관리자
	public function isAdmin ()
	{
		// XXX : 권한은???
		return ( $this->isLogin() && in_array( $this->info[$this->id_tag], $this->cnf['admin_uids'] ) ) ? true : false;
	}

	// 개발자
	public function isDev ()
	{
		return ( $_SERVER['REMOTE_ADDR'] == "121.166.136.159" ) ? true : false;
	}

	public function get ($x=NULL)
	{
		return $x == NULL ? $this->info : $this->info[$x];
	}

	public function set ($x, $v)
	{
		if ( $x == NULL ) return false;
		$this->info[$x] = $v;
	}
}
