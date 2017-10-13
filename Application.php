<?php
namespace Zuuda;

const SCRIPT_ASSET = 'script';
const STYLE_ASSET = 'style';
const HTML_ASSET = 'html';
const HEADER_LAYOUT = 'header';
const FOOTER_LAYOUT = 'footer';
const MAIN_LAYOUT = 'main'; 

use Kuwamoto;

class Application 
{
	private static $_data = array();
	
	final public function rootName() { return __CLASS__; } 
	final static public function HasUrl() { return self::_hasUrl(); }
	final static public function GetUrl() { return self::_getUrl(); }
	final static public function SetUrl( $value ) { return self::_setUrl( $value ); }
	
	private static function _hasUrl() 
	{
		if( isset( self::$_data[ 'url' ] ) )
		{
			return true;
		}
		return false;
	}
	
	private static function _getUrl() { return ( NULL !== self::$_data[ 'url' ] ) ? self::$_data[ 'url' ] : NULL; }
	
	private static function _setUrl( $value ) 
	{
		self::$_data = array_merge
		(
			self::$_data, 
			array( 'url' => $value ) 
		);
		return $value;
	}
	
	private function __construct() {}
	private function __clone() {}

	private function _routeURL( $url ) 
	{
		if( Config::get( 'COM' ) ) 
		{
			if( self::_hasUrl() ) 
			{
				return self::_getUrl(); 
			}
		}
		
		$url = Route::routing( $url );
		
		return ( $url );
	}
	
	private function _bootService() 
	{
		GlobalModifier::set( 'cache', new Cache() );
		GlobalModifier::set( 'irregularWords', array() );
		GlobalModifier::set( 'inflect', new Kuwamoto\Inflection() );
		GlobalModifier::set( 'html', Html::getInstance() );
		GlobalModifier::set( 'file', cFile::getInstance() );
		GlobalModifier::set( '$_post', array() );
		GlobalModifier::loadUrl();
	}
	
	private function _bootParams() 
	{
		global $configs;
		
		$configs['QUERY_STRING'] = array_map( 'ucfirst' , explode( PS, $this->_routeURL( getSingleton( 'Global' )->get( 'url' ) ) ) );
	
		$has_vars = stripos( $_SERVER[ "REQUEST_URI" ], '?' );
		if( $has_vars ) 
		{
			$has_vars = substr( $_SERVER[ "REQUEST_URI" ], $has_vars + 1 ); 
			if( $has_vars ) 
			{
				$arr_vars = explode( '&', $has_vars );
				$configs[ 'REQUEST_VARIABLES' ] = array();
				foreach( $arr_vars as $key => $value ) 
				{
					$var = explode( '=', $value ); 
					if( isset( $configs[ 'REQUEST_VARIABLES' ][ $var[ 0 ] ] ) ) 
					{
						if( !is_array( $configs[ 'REQUEST_VARIABLES' ][ $var[ 0 ] ] ) ) 
						{
							$first_value = $configs[ 'REQUEST_VARIABLES' ][ $var[ 0 ] ];
							$configs[ 'REQUEST_VARIABLES' ][ $var[ 0 ] ] = array( $first_value );
						}
						array_push( $configs[ 'REQUEST_VARIABLES' ][ $var[ 0 ] ], urldecode( $var[ 1 ] ) );
					}
					else 
					{
						$configs[ 'REQUEST_VARIABLES' ][ $var[ 0 ] ] = isset( $var[ 1 ] ) ? urldecode( $var[ 1 ] ) : '';
					}
				}
			}
			
			if( in_array( 'REQUEST_VARIABLES', $configs ) ) 
			{
				GlobalModifier::set( '_GET', $configs[ 'REQUEST_VARIABLES' ] );
			}
		}
		else 
		{
			GlobalModifier::set( '_GET', array() );
		}
	}
	
	private function _bootServices( $serviceInst, $appInst = NULL ) 
	{
		if( Config::get( 'COM' ) ) 
		{
			return $serviceInst->bootService( $appInst );
		}
		return false;
	}

	private function _extractController() 
	{
		global $configs;
		global $router;

		$_extract = array();

		if(is_array($configs['QUERY_STRING'])) 
		{
			$module = $configs['QUERY_STRING'][0];
			array_push($_extract, array_shift($configs['QUERY_STRING']));
			$configs["MODULE"] = $module;

			$controller = (isset($configs['QUERY_STRING']) && isset($configs['QUERY_STRING'][0])) ? $configs['QUERY_STRING'][0] : NULL;
			array_push($_extract, array_shift($configs['QUERY_STRING']));
			$configs["CONTROLLER"] = $controller;
			
			$configs['ACTION'] = array_shift($configs['QUERY_STRING']);

			$_extract = $module.DS.CTRL_DIR.$controller.CONTROLLER;
			
			return $_extract;
		}
		return $router['default']['controller'];
	}

	static function Booting() 
	{
		static $_instance;
		Session::Start();
		$_instance = new Application();
		$_instance->_bootService();
		if( Config::get( 'COM' ) ) 
		{
			$_instance->_bootServices( BTShipnelService::getInstance() );
			$_instance->_bootServices( ThemeService::getInstance() );
			$_instance->_bootServices( ComService::getInstance(), $_instance );
			$_instance->_bootServices( CateService::getInstance(), $_instance );
			$_instance->_bootServices( RouteService::getInstance(), $_instance );			
		}
		$_instance->_bootParams();
		return $_instance;
	}
	
	public function SetReporting() 
	{
		global $configs;
		if ( $configs[ 'DEVELOPMENT_ENVIRONMENT' ] == true ) 
		{
			error_reporting(E_ALL);
			ini_set('display_errors', 1);
		} 
		else 
		{
			error_reporting(E_ALL);
			ini_set('display_errors', 0);
			ini_set('log_errors', 1);
			ini_set('error_log', _correctPath(WEB_DIR.DS.'tmp'.DS.'logs'.DS.'error.log') );
		}
		return $this;
	}

	public function SecureMagicQuotes() 
	{
		if ( get_magic_quotes_gpc() ) 
		{
			$_GET    = _stripSlashesDeep( $_GET );
			$_POST   = _stripSlashesDeep( $_POST );
			$_COOKIE = _stripSlashesDeep( $_COOKIE );
		}
		return $this;
	}

	public function UnregisterGlobals() 
	{
		$registed = array
		(
			'_SESSION', 
			'_POST', 
			'_GET', 
			'_COOKIE', 
			'_REQUEST', 
			'_SERVER', 
			'_ENV', 
			'_FILES'
		);
		foreach( $registed as $value ) 
		{
			foreach ( GlobalModifier::get( $value ) as $key => $var ) 
			{
				if ( $var === GlobalModifier::get( $key ) ) 
				{
					GlobalModifier::destroy( $key );
				}
			}
		}
		return $this;
	}

	public function Start() 
	{
		global $configs;
		
		try 
		{
			$controller_class_name = $this->_extractController(); 
			$controller_class_file = _currentControllerFile(); 
			
			if(file_exists( $controller_class_file ) ) 
			{
				$dispatch = new $controller_class_name();
				
				$action = $configs['ACTION'].ACTION;

				if((int)method_exists($dispatch, $action)) 
				{
					if( isset( $configs["QUERY_STRING"] ) ) 
					{
						$lim = count( $configs["QUERY_STRING"] );
						for( $i = 0; $i < $lim; $i++ ) 
						{
							$configs["QUERY_STRING"][ $i ] = strtolower( $configs["QUERY_STRING"][ $i ] );
						}
					} 
					
					call_user_func_array(array($dispatch, "CheckMass"), $_POST); 
					call_user_func_array(array($dispatch, "BeforeAction"), $configs["QUERY_STRING"]);
					call_user_func_array(array($dispatch, $action), $configs["QUERY_STRING"]);
					call_user_func_array(array($dispatch, "AfterAction"), $configs["QUERY_STRING"]);
				}
				else 
				{
					// die( "Action couldn't found!" );
					die( "<h1>Error 404! Could not found file.</h1>" );
				}
			}
			else 
			{
				die( "<h1>Error 404! Could not found file.</h1>" );
			}
		}
		catch(Exception $e) 
		{
			echo $e->message();
		}
		_closeDB();
		return $this;
	}
}