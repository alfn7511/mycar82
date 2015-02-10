<?php
/**
 * Html Class
 * ------------------------------------
 * Desc				make JavaScript function
 * first design		Chris chris13jr@gmail.com
 * Date				2011.09.21
 */

Class String
{
	public function String ()
	{
	}

	public function last_index_of2 ($sub_str, $instr)
	{
		if ( strstr($instr, $sub_str) != "" )
		{
			$subleng = strpos(strrev($instr), $sub_str);
			return(substr ($instr, -$subleng));
		}
	}

	public function getExtInFilename ($fname)
	{
		if ( $fname == NULL || $fname == "" ) return;
		$rtv = NULL;

		preg_match("/\..*$/", $fname, $ext);
		if ( sizeof($ext) > 0 )
		{
			$rtv = str_replace("\.", NULL, $ext[0]);
		}
		return $rtv;
	}

	public function stringCut ($str, $len=10, $rest="...")
	{
/*
		if ( strlen($str) < $len) return $str;
		$str2 = substr($str, 0, $len);
		for ($i = 0; $i < strlen($str2); $i++)
		{
			(ord($str2[$i]) > 127) ? $zz++ : $yy++;
		}
		if ($zz % 2) $zz++;
		$size = $yy + $zz;
		return substr($str, 0, $size) . $rest;
*/
		if ( strlen($str) < $len) return $str;
		$str2 = substr($str, 0, $len);
		$len2 = strlen($str2);
		for ($i = 0; $i < $len2; $i++)
		{
			if (ord($str2[$i]) > 127) $zz++;
			else $yy++;
		}
		if ($zz%2) $zz++;
		$size = $yy + $zz;
		return substr($str, 0, $size) . $rest;
	}

	public function html_to_onlytext2 ($value)
	{
		$value = str_replace('<', '&lt;', $value);
		$value = str_replace('>', '&gt;', $value);
		return $value;
	}

}

?>
