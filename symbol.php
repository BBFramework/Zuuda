<?php
define ( 'DEVELOPMENT_ENVIRONMENT', 'DEVELOPMENT_ENVIRONMENT' );
define ( 'DEVELOPER_WARNING', 'DEVELOPER_WARNING' );
define ( 'AUTOLOAD_ERRORS_WARNING', 'AUTOLOAD_ERRORS_WARNING' );
define ( 'AUTH_DATA', 'authorization' );
define ( 'auth', AUTH_DATA );
define ( 'DS', 	DIRECTORY_SEPARATOR );
define ( 'BS', 	"\\" );
define ( 'PS', 	"/" );
define ( 'NL', 	"\n" );
define ( 'nl', 	NL );
define ( 'TAB', "\t" );
define ( 'tab', TAB );
define ( 'BL', 	"<br/>" );
define ( 'bl', 	BL );
define ( 'DOT', '.' );
define ( 'dot', DOT );
define ( 'COMMA', ',' );
define ( 'comma', COMMA );
define ( 'SEMICOLON', ';' );
define ( 'semicolon', SEMICOLON );
define ( 'EMPTY_CHAR', 		'' );
define ( 'empty_char', 		EMPTY_CHAR );
define ( 'mad', 			'_' ); 
define ( 'SPACE', 			' ' );
define ( 'space', 			' ' );
define ( 'EMPTY_STRING', 	"" );
define ( 'empty_string', 	EMPTY_STRING );
define ( 'HEAD', 			0 ); 
define ( 'head', 			0 ); 
define ( 'ZERO', 			0 ); 
define ( 'zero', 			0 ); 

define ( 'CONTROLLER', 	'Controller' );
define ( 'MODEL', 		'Model' );
define ( 'VIEW', 		'View' );
define ( 'ACTION', 		'Action' );
define ( 'ID', 			'id' );

define ( 'CTRLER_PRE', 	CONTROLLER.'s' );
define ( 'MODEL_PRE', 	MODEL.'s' );
define ( 'VIEW_PRE', 	VIEW.'s' );
define ( 'CTRLER_DIR', 	CTRLER_PRE.DS );
define ( 'MODEL_DIR',	MODEL_PRE.DS );
define ( 'VIEW_DIR', 	VIEW_PRE.DS );
define ( 'LAYOUT_MAIN', 'main.tpl' );

define ( 'ROOT', 		$configs['ROOT'] ); 
define ( 'APP_ROOT', 	$configs['APP_DIR'] );
define ( 'FW_NAME',		'Zuuda' );
define ( 'ROOT_DIR', 	ROOT.DS ); 
DEFINE ( 'VENDOR', 		ROOT_DIR.'vendor' );
DEFINE ( 'VENDOR_DIR', 	VENDOR.DS );
DEFINE ( 'SRC_DIR',		'src'.DS );
define ( 'WEB_ROOT', 	APP_ROOT );
define ( 'APP_DIR', 	WEB_ROOT.DS );
define ( 'WEB_DIR', 	APP_DIR );

define ( 'MOD_NAME_DIR',"modules".DS );
define ( 'COM_NAME', 	"com".DS );
define ( 'CODE_NAME', 	"code".DS );
define ( 'MOD_DIR', 		APP_DIR.MOD_NAME_DIR );
define ( 'COM', 			MOD_DIR.COM_NAME );
define ( 'COM_DIR', 		MOD_DIR.COM_NAME );
define ( 'CODE', 			MOD_DIR.CODE_NAME );
define ( 'CODE_DIR', 		MOD_DIR.CODE_NAME );
define ( 'TMP_NAME_DIR', 			"tmp".DS );
define ( 'CACHE_NAME_DIR', 			TMP_NAME_DIR."cache".DS );
define ( 'CACHE_TPL_NAME_DIR',		CACHE_NAME_DIR."templates".DS );
define ( 'CACHE_LAYOUT_NAME_DIR',	CACHE_TPL_NAME_DIR."layout".DS );
define ( 'LOG_NAME_DIR',			TMP_NAME_DIR."logs".DS );
define ( 'TMP_DIR', 			APP_DIR.TMP_NAME_DIR );
define ( 'CACHE_DIR', 			APP_DIR.CACHE_NAME_DIR );
define ( 'CACHE_TPL_DIR', 		CACHE_DIR.CACHE_TPL_NAME_DIR );
define ( 'CACHE_LAYOUT_DIR', 	CACHE_TPL_DIR.CACHE_LAYOUT_NAME_DIR );
define ( 'LOG_DIR', 			TMP_DIR.LOG_NAME_DIR );
define ( 'THEME_NAME_DIR',	"themes".DS );
define ( 'THEME_DIR', 		APP_DIR.THEME_NAME_DIR );
define ( 'MEDIA_NAME_DIR',				"media".DS );
define ( 'MEDIA_PHOTO_NAME_DIR',		MEDIA_NAME_DIR."Photos".DS );
define ( 'MEDIA_AUDIO_NAME_DIR',		MEDIA_NAME_DIR."Audios".DS );
define ( 'MEDIA_VIDEO_NAME_DIR', 		MEDIA_NAME_DIR."Videos".DS );
define ( 'MEDIA_DOCUMENT_NAME_DIR', 	MEDIA_NAME_DIR."Documents".DS );
define ( 'MEDIA_COMPRESSED_NAME_DIR', 	MEDIA_NAME_DIR."Compressed".DS );
define ( 'MEDIA_OTHERS_NAME_DIR', 		MEDIA_NAME_DIR."Others".DS );
define ( 'MEDIA_ROOT_NAME_DIR', 		MEDIA_NAME_DIR."Root".DS );
define ( 'MEDIA_DIR', 				APP_DIR.MEDIA_NAME_DIR );
define ( 'MEDIA_PHOTO_DIR', 		MEDIA_DIR.MEDIA_PHOTO_NAME_DIR );
define ( 'MEDIA_AUDIO_DIR', 		MEDIA_DIR.MEDIA_AUDIO_NAME_DIR );
define ( 'MEDIA_VIDEO_DIR', 		MEDIA_DIR.MEDIA_VIDEO_NAME_DIR );
define ( 'MEDIA_DOCUMENT_DIR', 		MEDIA_DIR.MEDIA_DOCUMENT_NAME_DIR );
define ( 'MEDIA_COMPRESSED_DIR', 	MEDIA_DIR.MEDIA_COMPRESSED_NAME_DIR );
define ( 'MEDIA_OTHERS_DIR', 		MEDIA_DIR.MEDIA_OTHERS_NAME_DIR );
define ( 'MEDIA_ROOT_DIR',	 		MEDIA_DIR.MEDIA_ROOT_NAME_DIR );
define ( 'PHOTO_DIR', 				MEDIA_PHOTO_DIR );
define ( 'AUDIO_DIR', 				MEDIA_AUDIO_DIR );
define ( 'VIDEO_DIR', 				MEDIA_VIDEO_DIR );
define ( 'TPL_NAME_DIR', 		"templates".DS );
define ( 'TPL_LAYOUT_NAME_DIR', TPL_NAME_DIR."layout".DS );
define ( 'TPL_BLOCK_NAME_DIR', 	TPL_NAME_DIR."block".DS );
define ( 'TPL_WIDGET_NAME_DIR', TPL_NAME_DIR."widget".DS );
define ( 'TPL_DIR', 		APP_DIR.TPL_NAME_DIR );
define ( 'TPL_LAYOUT_DIR', 	TPL_LAYOUT_NAME_DIR );
define ( 'TPL_BLOCK_DIR', 	TPL_BLOCK_NAME_DIR );
define ( 'TPL_WIDGET_DIR', 	TPL_WIDGET_NAME_DIR );
define ( 'LAYOUT_DIR', 		TPL_LAYOUT_DIR );
define ( 'BLOCK_DIR', 		TPL_BLOCK_DIR );
define ( 'WIDGET_DIR', 		TPL_WIDGET_DIR );
define ( 'SKIN_DIR',		APP_DIR."skin".DS );
define ( 'IMG_DIR',			SKIN_DIR."img".DS );
define ( 'CSS_DIR',			SKIN_DIR."css".DS );
define ( 'JS_DIR',			APP_DIR."js".DS );
define ( 'JUI_DIR',			APP_DIR."jui".DS );

define ( 'ORIGIN_DOMAIN', 			$configs['ORIGIN_DOMAIN'] );
define ( 'WEB_PATH', 				ORIGIN_DOMAIN.((isset($configs['APP_PATH']))?PS.$configs['APP_PATH']:PS) );
define ( 'TPL_PATH', 				WEB_PATH."templates".PS );
define ( 'MEDIA_PATH', 				WEB_PATH.'media'.PS );
define ( 'MEDIA_PHOTO_PATH', 		MEDIA_PATH.'Photos'.PS );
define ( 'MEDIA_AUDIO_PATH', 		MEDIA_PATH.'Audios'.PS );
define ( 'MEDIA_VIDEO_PATH', 		MEDIA_PATH.'Videos'.PS );
define ( 'MEDIA_DOCUMENT_PATH', 	MEDIA_PATH.'Documents'.PS );
define ( 'MEDIA_COMPRESSED_PATH', 	MEDIA_PATH.'Compressed'.PS );
define ( 'MEDIA_OTHER_PATH', 		MEDIA_PATH.'Others'.PS );
define ( 'PHOTO_PATH', 				MEDIA_PHOTO_PATH );
define ( 'AUDIO_PATH', 				MEDIA_AUDIO_PATH );
define ( 'VIDEO_PATH', 				MEDIA_VIDEO_PATH );
define ( 'JS_PATH', 				WEB_PATH."js".PS );
define ( 'JUI_PATH', 				WEB_PATH."jui".PS );
define ( 'SKIN_PATH', 				WEB_PATH."skin".PS );
define ( 'CSS_PATH', 				SKIN_PATH."css".PS );
define ( 'IMG_PATH', 				SKIN_PATH."img".PS );
define ( 'IMAGE_PATH', 				IMG_PATH );
define ( 'THEME_PATH', 				WEB_PATH."themes".PS );

if( $configs[DEVELOPER_WARNING] ) 
{ 
	ini_set( 'display_errors', 1 ); 
} 