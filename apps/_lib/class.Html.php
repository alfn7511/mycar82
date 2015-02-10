<?php
/**
 * Html Class
 * ------------------------------------
 * Desc				make JavaScript function
 * first design		Chris chris13jr@gmail.com
 * Date				2011.09.21
 */

Class Html
{
	public function Html ()
	{
	}

	public function makeHead ($el)
	{/*{{{*/
		if ( !is_array($el) ) $el = array($el);

		foreach ($el as $e)
		{
			preg_match("/\.[a-zA-Z0-9]*$/", $e, $_tmp);
			$ext = str_replace(".", NULL, $_tmp[0]);
			if ( $ext == "js" )
			{
				$_rtv = "<script type=\"text/javascript\" src=\"/js/$e\"></script>";
			}
			else if ( $ext == "css" )
			{
				$_rtv = "<link href=\"/css/$e\" rel=\"stylesheet\" type=\"text/css\">";
			}
			$rtv[] = $_rtv;
		}

		return implode("\n", $rtv) . "\n";
	}/*}}}*/

	public function makeSelect($name, $vts, $selected_key="", $notice=NULL, $event=NULL, $ext=NULL)
	{/*{{{*/
		if ( !is_array($vts) ) $vts = array($vts);

		$ext = $ext != NULL ? " " . $ext : NULL;
		$event = $event != NULL ? " " . $event : NULL;
		$rtv = array();

		$rtv[] = "<select name=\"$name\"$event$ext>";
		if ($notice != NULL) $rtv[] = "<option value=\"\">$notice</option>";
				
		foreach ( $vts as $k => $v ){			
			$selected = trim($k) == trim($selected_key) ? " SELECTED" : NULL;			
			$rtv[] = "<option value=\"$k\"$selected>$v</option>";
		}
		$rtv[] = "</select>";

		return implode("\n", $rtv);
	}/*}}}*/

	public function makeCheckBox ($name, $vts, $selected_key="", $delimiter="", $ext=NULL)
	{/*{{{*/
		if ( !is_array($vts) ) $vts = array($vts);

		$ext = $ext != NULL ? " " . $ext : NULL;
		$event = $event != NULL ? " " . $event : NULL;
		$rtv = array();

		foreach ( $vts as $k => $v )
		{
			$selected = $k == $selected_key ? " checked" : NULL;
			$id = $name . "_" . $k;
			$_id = "id=\"$id\"";
			$rtv[] = $this->_makeInput("checkbox", $name, $k, "$_id$ext") . "<label for=\"$id\">" . $v . "</label>";
		}

		return implode($delimiter, $rtv);
	}/*}}}*/
	public function makeRadio ($name, $vts, $selected_key="", $delimiter="", $ext=NULL)
	{/*{{{*/
		if ( !is_array($vts) ) $vts = array($vts);

		$ext = $ext != NULL ? " " . $ext : NULL;
		$event = $event != NULL ? " " . $event : NULL;
		$rtv = array();

		foreach ( $vts as $k => $v )
		{
			$selected = ($k == $selected_key) ? " checked=\"checked\" " : NULL;
			$id = $name."_".$k;
			$_id = " id=\"$id\" ";
			$rtv[] = $this->_makeInput("radio", $name, $k, $_id.$selected.$ext).'<label for="'.$id.'">'.$v.'</label>';
		}

		return implode($delimiter, $rtv);
	}/*}}}*/

	public function makeInputButton ($name, $value, $ext=NULL)
	{
		return $this->_makeInput("button", $name, $value, $ext);
	}

	public function makeInputPasswd ($name, $value, $ext=NULL)
	{
		return $this->_makeInput("password", $name, $value, $ext);
	}

	public function makeInputText ($name, $value, $ext=NULL)
	{
		return $this->_makeInput("text", $name, $value, $ext);
	}

	public function makeHidden ($name, $value, $ext=NULL)
	{
		return $this->_makeInput("hidden", $name, $value, $ext);
	}

	private function _makeInput ($type, $name, $value, $ext)
	{
		$ext = $ext != NULL ? " " . $ext : NULL;
		return "<input type=\"$type\" name=\"$name\" value=\"$value\"$ext />";
	}

	public function openForm ($name, $method="GET", $action=NULL, $ext=NULL)
	{
		$method = $method != NULL ? " method=\"$method\"" : NULL;
		$action = $action != NULL ? " action=\"$action\"" : NULL;
		$ext = $ext != NULL ? " $ext" : NULL;
		$rtv = "<form name=\"$name\"{$method}{$action}{$ext}>";
		return $rtv;
	}

	public function closeForm ()
	{
		return "</form>";
	}

	public function strtohtml ($string) 
	{ 
		$string = str_replace(array("&lt;", "&gt;", '&amp;', '&#039;', '&quot;','&lt;', '&gt;'), array("<", ">",'&','\'','"','<','>'), htmlspecialchars_decode($string, ENT_NOQUOTES));

		return $string;
	} 

}

?>
