<?php 
namespace Zuuda;

class ComService implements iComService 
{
	
	public static function GetInstance() { return self::_getInstance(); }
	public static function BootService( Application $app = NULL ) { return self::_bootService( $app ); }
	
	private function __construct() {} 
	private function __clone() {}
	private static function _getInstance() 
	{
		static $_instance;
		if( is_null( $_instance ) ) 
		{
			$_instance = new ComService;
		}
		return $_instance;
	}
	
	private static function _applyConfigs() 
	{
		if( Config::get( 'COM' ) ) 
		{
			return array (
				'basename' 	=> 'route', 
				'driver'	=> 'driver', 
				'extension'	=> '.xml', 
				'host'		=> CODE,
			);
		}
		return false;
	}
	
	private static function _loadConfigs() 
	{
		$configs = self::_applyConfigs(); 
		if( $configs ) 
		{
			return array(
				$configs[ 'host' ] => $configs[ 'driver' ] . DS . $configs[ 'basename' ] . $configs[ 'extension' ] 
			);
		}
		return false;
	}
	
	private static function _routing( $app, $url, $file_path ) 
	{
		$basename = basename( $file_path );
		$handle = simplexml_load_file( $file_path );
		$len = count( $handle->route );
		for( $i = 0; $i < $len; $i++ ) 
		{
			$name = $handle->route[ $i ][ 'name' ];
			$left = $handle->route[ $i ]->left;
			$right = $handle->route[ $i ]->right;
			if ( $left == $url ) 
			{
				$live_path = str_replace( $basename, 'live.xml', $file_path );

				if( call( cFile::get(), $live_path )->exist() ) 
				{
					$live_xml = simplexml_load_file( $live_path );
					if( $live_xml->live->status == 'true' ) 
					{
						$app->setUrl( str_replace( $left, $right, $url ) );
						return true;
					}
				}
			}
		}
		return false;
	}
	
	private static function _bootService( Application $app = NULL ) 
	{
		if( Config::get( 'COM' ) && !$app->hasUrl() ) 
		{
			$url = getSingleton( 'Global' )->get( 'url' );
			$configs = self::_loadConfigs();
			list( $realpath, $filename ) = each( $configs );
			
			$list = cFile::lookFile( _correctPath( $realpath ), _correctPath( $filename ) );
			foreach( $list as $file_path ) 
			{
				return self::_routing( $app, $url, $file_path );
			}
		}

		return false;
	}
}