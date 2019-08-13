<?php

namespace Zuuda;

use Zuuda\cFile;

class Html implements iHTML 
{
	private static function _fetchTinyUrl($url) 
	{ 
		$ch = curl_init(); 
		$timeout = 5; 
		curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$url[0]); 
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout); 
		$data = curl_exec($ch); 
		curl_close($ch); 
		return '<a href="'.$data.'" target = "_blank" >'.$data.'</a>'; 
	}

	public static function shortenUrls($data) {
		$data = preg_replace_callback('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', array(get_class($this), '_fetchTinyUrl'), $data);
		return $data;
	}

	public static function sanitize($data) {
		return mysql_real_escape_string($data);
	}

	public static function GetInstance() { return self::_getInstance(); }
	public static function Link( $text, $path, $prompt = null, $confirm_msg = "Are you sure?") { return self::_link( $text, $path, $prompt, $confirm_msg ); }
	public static function AssetPath( $file_path ) { return self::_assetPath( $file_path ); } 
	public static function IncludeJs( $file_name ) { return self::_includeJs( $file_name ); }
	public static function IncludeCss( $file_name ) { return self::_includeCss( $file_name ); }
	public static function IncludeImg( $file_name, $alt_text ) { return self::_includeImg( $file_name, $alt_text ); }
	public static function IncludeGif( $file_name, $alt_text ) { return self::_includeGif( $file_name, $atl_text ); }
	public static function IncludePng( $file_name, $alt_text ) { return self::_includePng( $file_name, $alt_text ); }
	public static function IncludeJpg( $file_name, $alt_text ) { return self::_includeJpeg( $file_name, $alt_text ); }
	public static function IncludeJpeg( $file_name, $alt_text ) { return self::_includeJpeg( $file_name, $alt_text ); }
	public static function Write( $content ) { echo ( $content ); }
	public static function Assign( $name, $value, $template ) { return self::_assign( $name, $value, $template ); }
	
	private function __construct() {}
	private function __clone() {}
	private static function _getInstance() 
	{
		static $_instance;
		if( is_null( $_instance ) ) 
		{
			$_instance = new Html;
		}
		return $_instance;
	}
	
	private static function _link( $text, $path, $prompt, $confirm_msg ) 
	{
		$path = str_replace( ' ', '-', $path );
		if ($prompt) 
			$data = '<a href="javascript:void(0);" onclick="javascript:jumpTo(\''.$path.'\',\''.$confirm_msg.'\')">'.$text.'</a>';
		else 
			$data = '<a href="'.$path.'">'.$text.'</a>';	
		return $data;
	} 

	private static function _assetPath( $file_path ) 
	{
		return _assetPath( $file_path );
	}
	
	private static function _includeJs( $file_name ) 
	{
		return '<script type="text/javascript" src="'.((preg_match('/(https)|(http):\/\//', $file_name))?$file_name:cFile::assetPath('js/'.$file_name.'.js', false)).'"></script>'."\n";
	}
	
	private static function _includeCss( $file_name ) 
	{
		return '<link rel="stylesheet" type="text/css" href="'.((preg_match('/(https)|(http):\/\//', $file_name))?$file_name:cFile::assetPath('skin/css/'.$file_name.'.css', false)).'" />'."\n";
	}
	
	private static function _includeImg( $file_name, $atl_text ) 
	{
		return '<img alt="'.$alt_text.'" src="'.((preg_match('/(https)|(http):\/\//', $file_name))?$file_name:cFile::assetPath($file_name, false)).'" />';
	}
	
	private static function _includeGif( $file_name, $alt_text ) 
	{
		return '<img alt="'.$alt_text.'" src="'.((preg_match('/(https)|(http):\/\//', $file_name))?$file_name:cFile::assetPath($file_name.'.gif', false)).'" />'; 
	}
	
	private static function _includePng( $file_name, $alt_text ) 
	{
		return '<img alt="'.$alt_text.'" src="'.((preg_match('/(https)|(http):\/\//', $file_name))?$file_name:cFile::assetPath($file_name.'.png', false)).'" />';
	}
	
	private static function _includeJpeg( $file_name, $alt_text ) 
	{
		return '<img alt="'.$alt_text.'" src="'.((preg_match('/(https)|(http):\/\//', $file_name))?$file_name:cFile::assetPath($file_name.'.jpg', false)).'" />';
	}
	
	private static function _assign( $name, $value, $template ) 
	{
		return str_replace( "{" . $name . "}", $value, $template );
	}
}