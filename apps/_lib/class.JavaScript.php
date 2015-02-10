<?php
/**
 * JavaScript Class
 * ------------------------------------
 * Desc				make JavaScript function
 * first design		Chris chris13jr@gmail.com
 * Date				2011.09.21
 */

Class JavaScript
{
	protected $start_tag = "<script type=\"text/javascript\">";
	protected $end_tag = "</script>";

	public function JavaScript ()
	{
	}

	public function _makeTag ($msg, $singleline=false)
	{
		$rtv = array();
		$rtv[] = $this->start_tag;
		$rtv[] = $msg;
		$rtv[] = $this->end_tag;

		return implode(($singleline ? "" : "\r"), $rtv);
	}

	public function alert ($msg="")
	{
		$msg = "alert('$msg');";
		return $this->_makeTag($msg);
	}

	public function msgBack ($msg="")
	{
		$msg = "alert('$msg'); history.back();";
		return $this->_makeTag($msg);
	}

	public function msgGoUrl ($msg="", $url="/")
	{
		$msg = "alert('$msg'); this.location.href=\"$url\";";
		return $this->_makeTag($msg, true);
	}

	public function noMsgGoUrl ($url="/")
	{
		$msg = "location.href=\"$url\";";
		return $this->_makeTag($msg, true);
	}

	public function msgClose ($msg="")
	{
		$msg = "alert('$msg'); self.close();";
		return $this->_makeTag($msg, true);
	}

	public function msgCloseGoUrl ($url="/")
	{
		$msg = "opener.parent.location=\"$url\" ; self.close(); ";
		return $this->_makeTag($msg, true);
	}


	public function msgCloseColorbox ($msg="")
	{
		$msg = "alert('$msg'); $.colorbox.close();";
		return $this->_makeTag($msg, true);
	}

}

?>
