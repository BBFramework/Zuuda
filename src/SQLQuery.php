<?php

namespace Zuuda;

use Exception;

define( 'mcbm_order_import', 		'_orderImport' );
define( 'mcbm_order_merge', 		'_orderMerge' );
define( 'mcbm_order_merge_left', 	'_orderMergeLeft' );
define( 'mcbm_order_merge_right', 	'_orderMergeRight' );
define( 'mcbm_order_has_one', 		'_orderHasOne' );
define( 'mcbm_order_has_many',		'_orderHasMany' );
define( 'mcbm_order_has_mabtm',		'_orderHasMABTM' );
define( 'mcbm_show_has_one',		'_showHasOne' );
define( 'mcbm_show_has_many',		'_showHasMany' );
define( 'mcbm_show_has_mabtm',		'_showHasManyAsBelongsToMany' );
define( 'mcbm_hide_has_one',		'_hideHasOne' );
define( 'mcbm_hide_has_many',		'_hideHasMany' );
define( 'mcbm_hide_has_mabtm',		'_hideHasManyAsBelongsToMany' );
define( 'mcbm_custom',				'_custom' );
define( 'mcbm_search',				'_search' );
define( 'mcbm_findid',				'_find' );
define( 'mcbm_first',				'_first' );
define( 'mcbm_last',				'_last' );
define( 'mcbm_entity',				'_entity' );
define( 'mcbm_item',				'_item' );
define( 'mcbm_paginate',			'_paginate' );
define( 'mcbm_delete',				'_delete' );
define( 'mcbm_save',				'_save' );
define( 'mcbm_total_pages',			'_totalPages' );
define( 'mcbm_total',				'_total' );
define( 'mcbm_count',				'_count' );
define( 'mcbm_distinct',			'_distinct' );
define( 'mcbm_sum',					'_sum' );
define( 'mcbm_avg',					'_avg' );
define( 'mcbm_max',					'_max' );
define( 'mcbm_min',					'_min' );
define( 'mcbm_implode',				'_implode' );
define( 'mcbm_length',				'_length' );
define( 'mcbm_db_list',				'_dbList' );
define( 'mcbm_row',					'_row' );
define( 'mcbm_setpage',				'_setPage' );
define( 'mcbm_setlimit',			'_setLimit' );
define( 'mcbm_bound',				'_bound' );
define( 'mhd', 						'data' );
define( 'mhj', 						'join' );
define( 'mad', 						'_' ); 

abstract class SQLQuery 
{
	protected $_dbHandle;
	protected $_primaryKey		= 'id'; 
	protected $_querySQL;
	protected $_querySQLs 		= array(); 
	protected $_flagHasExe 		= false;
	protected $_propPrefix		= EMPTY_CHAR;
	protected $_propModel		= EMPTY_CHAR;
	protected $_propAlias		= EMPTY_CHAR; 
	protected $_propTable		= EMPTY_CHAR;
	protected $_propsDescribe 	= array(); 
	protected $_propsUndescribe = array(); 
	protected $_propsCond 		= array(); 
	protected $_propsCondOr 	= array(); 
	protected $_propsCondOn 	= array();
	protected $_propsCondCmd 	= array();
	protected $_propsOrderCmd	= array();
	protected $_propsOrder		= array();
	protected $_propsGroupBy	= array();
	protected $_propPage;
	protected $_propLimit;
	protected $_propOffset		= 0;
	protected $_eventBoot;
	protected $_eventRide;
	protected $_eventDown;
	protected $_propForeignKey;
	protected $_propAliasKey;
	protected $_propAliasModel; 
	protected $_flagHasOne 		= false; 
	protected $_flagHasMany 	= false; 
	protected $_flagHasMABTM 	= false; 
	protected $_propsImport		= array();
	protected $_propsMerge		= array(); 
	protected $_propsMergeLeft	= array(); 
	protected $_propsMergeRight	= array(); 
	protected $_propsHasOne 	= array(); 
	protected $_propsHasMany 	= array(); 
	protected $_propsHasMABTM 	= array(); 
	
	final protected function _setDBHandle( $handle ) { $this->_dbHandle=$handle; return $this; }
	final protected function _setPrefix( $value ) { $this->_propPrefix = $value; return $this; }
	final protected function _setModel( $value ) { return $this->_setModelName( $value ); }
	final protected function _setAlias( $value ) { return $this->_setAliasName( $value ); }
	final protected function _setTable( $value ) { return $this->_setTableName( $value ); } 
	final protected function _new() { return $this->_clear( true ); } 
	final protected function _reset() { return $this->_clear( true ); } 
	final public function _getModel() { return $this->_propModel; } 
	final public function _getTable() { return $this->_propTable; } 
	final public function _getAlias() { return $this->_propAlias; } 
	final public function Set() { return $this->_setData( func_get_args(), func_num_args(), __FUNCTION__ ); }
	final public function Assign() { return $this->_setData( func_get_args(), func_num_args(), __FUNCTION__ ); }
	final public function SetData() { return $this->_setData( func_get_args(), func_num_args(), __FUNCTION__ ); }
	final public function Require( $model ) { return $this->_require( $model ); } 
	final public function SetPrefix( $value ) { return $this->_setPrefix( $value ); }
	final public function SetModelName( $value ) { return $this->_setModelName( $value ); }
	final public function SetAliasName( $value ) { return $this->_setAliasName( $value ); }
	final public function SetTableName( $value ) { return $this->_setTableName( $value ); }
	final public function GetPrefix() { return $this->_getPrefix(); }
	final public function GetModelName() { return $this->_getModel(); }
	final public function GetTableName() { return $this->_getTable(); }
	final public function GetAliasName() { return $this->_getAlias(); }
	final public function Bound() { return $this->_bound( func_get_args(), func_num_args() ); }
	final public function Unbound() { return $this->_unbound( func_get_args(), func_num_args() ); }
	final public function Secure() { return $this->_unbound( func_get_args(), func_num_args() ); }
	final public function Unsecure() { return $this->_unsecure( func_get_args(), func_num_args() ); } 
	final public function Between() { return $this->_between( func_get_args(), func_num_args() ); }
	final public function Equal() { return $this->_equal( func_get_args(), func_num_args() ); }
	final public function Greater() { return $this->_greaterThan( func_get_args(), func_num_args() ); } 
	final public function GreaterThan() { return $this->_greaterThan( func_get_args(), func_num_args() ); } 
	final public function GreaterThanOrEqual() { return $this->_greaterThanOrEqual( func_get_args(), func_num_args() ); } 
	final public function In() { return $this->_in( func_get_args(), func_num_args() ); }
	final public function Is() { return $this->_is( func_get_args(), func_num_args() ); }
	final public function IsNot() { return $this->_isNot( func_get_args(), func_num_args() ); }
	final public function IsNotNull() { return $this->_isNotNull( func_get_args(), func_num_args() ); }
	final public function IsNull() { return $this->_isNull( func_get_args(), func_num_args() ); }
	final public function Less() { return $this->_lessThan( func_get_args(), func_num_args() ); } 
	final public function LessThan() { return $this->_lessThan( func_get_args(), func_num_args() ); } 
	final public function LessThanOrEqual() { return $this->_lessThanOrEqual( func_get_args(), func_num_args() ); } 
	final public function Like() { return $this->_like( func_get_args(), func_num_args() ); }
	final public function Not() { return $this->_not( func_get_args(), func_num_args() ); }
	final public function NotBetween() { return $this->_notBetween( func_get_args(), func_num_args() ); }
	final public function NotEqual() { return $this->_notEqual( func_get_args(), func_num_args() ); }
	final public function NotIn() { return $this->_notIn( func_get_args(), func_num_args() ); }
	final public function NotLike() { return $this->_notLike( func_get_args(), func_num_args() ); }
	final public function NotNull() { return $this->_notNull( func_get_args(), func_num_args() ); }
	final public function Where() { return $this->_where( func_get_args(), func_num_args() ); } 
	final public function BetweenOr() { return $this->_betweenOr( func_get_args(), func_num_args() ); } 
	final public function EqualOr() { return $this->_equalOr( func_get_args(), func_num_args() ); } 
	final public function GreaterOr() { return $this->_greaterThanOr( func_get_args(), func_num_args() ); } 
	final public function GreaterThanOr() { return $this->_greaterThanOr( func_get_args(), func_num_args() ); } 
	final public function GreaterThanOrEqualOr() { return $this->_greaterThanOrEqualOr( func_get_args(), func_num_args() ); } 
	final public function InOr() { return $this->_inOr( func_get_args(), func_num_args() ); } 
	final public function IsOr() { return $this->_isOr( func_get_args(), func_num_args() ); } 
	final public function IsNotOr() { return $this->_isNotOr( func_get_args(), func_num_args() ); } 
	final public function IsNotNullOr() { return $this->_isNotNullOr( func_get_args(), func_num_args() ); } 
	final public function IsNullOr() { return $this->_isNullOr( func_get_args(), func_num_args() ); } 
	final public function LessOr() { return $this->_lessThanOr( func_get_args(), func_num_args() ); } 
	final public function LessThanOr() { return $this->_lessThanOr( func_get_args(), func_num_args() ); } 
	final public function LessThanOrEqualOr() { return $this->_lessThanOrEqualOr( func_get_args(), func_num_args() ); } 
	final public function LikeOr() { return $this->_likeOr( func_get_args(), func_num_args() ); } 
	final public function NotOr() { return $this->_notOr( func_get_args(), func_num_args() ); } 
	final public function NotBetweenOr() { return $this->_notBetweenOr( func_get_args(), func_num_args() ); } 
	final public function NotEqualOr() { return $this->_notEqualOr( func_get_args(), func_num_args() ); } 
	final public function NotInOr() { return $this->_notInOr( func_get_args(), func_num_args() ); } 
	final public function NotLikeOr() { return $this->_notLikeOr( func_get_args(), func_num_args() ); } 
	final public function NotNullOr() { return $this->_notNullOr( func_get_args(), func_num_args() ); } 
	final public function WhereOr() { return $this->_whereOr( func_get_args(), func_num_args() ); } 
	final public function BetweenOn() { return $this->_betweenOn( func_get_args(), func_num_args() ); } 
	final public function EqualOn() { return $this->_equalOn( func_get_args(), func_num_args() ); } 
	final public function GreaterOn() { return $this->_greaterThanOn( func_get_args(), func_num_args() ); } 
	final public function GreaterThanOn() { return $this->_greaterThanOn( func_get_args(), func_num_args() ); } 
	final public function GreaterThanOrEqualOn() { return $this->_greaterThanOrEqualOn( func_get_args(), func_num_args() ); } 
	final public function InOn() { return $this->_inOn( func_get_args(), func_num_args() ); } 
	final public function IsOn() { return $this->_isOn( func_get_args(), func_num_args() ); } 
	final public function IsNotOn() { return $this->_isNotOn( func_get_args(), func_num_args() ); } 
	final public function IsNotNullOn() { return $this->_isNotNullOn( func_get_args(), func_num_args() ); } 
	final public function IsNullOn() { return $this->_isNullOn( func_get_args(), func_num_args() ); } 
	final public function LessOn() { return $this->_lessThanOn( func_get_args(), func_num_args() ); } 
	final public function LessThanOn() { return $this->_lessThanOn( func_get_args(), func_num_args() ); } 
	final public function LessThanOrEqualOn() { return $this->_lessThanOrEqualOn( func_get_args(), func_num_args() ); } 
	final public function LikeOn() { return $this->_likeOn( func_get_args(), func_num_args() ); } 
	final public function NotOn() { return $this->_notOn( func_get_args(), func_num_args() ); } 
	final public function NotBetweenOn() { return $this->_notBetweenOn( func_get_args(), func_num_args() ); } 
	final public function NotEqualOn() { return $this->_notEqualOn( func_get_args(), func_num_args() ); } 
	final public function NotInOn() { return $this->_notInOn( func_get_args(), func_num_args() ); } 
	final public function NotLikeOn() { return $this->_notLikeOn( func_get_args(), func_num_args() ); } 
	final public function NotNullOn() { return $this->_notNullOn( func_get_args(), func_num_args() ); } 
	final public function WhereOn() { return $this->_whereOn( func_get_args(), func_num_args() ); } 
	final public function OrBetween() { return $this->_orBetween( func_get_args(), func_num_args() ); }
	final public function OrEqual() { return $this->_orEqual( func_get_args(), func_num_args() ); }
	final public function OrGreater() { return $this->_orGreater( func_get_args(), func_num_args() ); }
	final public function OrGreaterThan() { return $this->_orGreaterThan( func_get_args(), func_num_args() ); }
	final public function OrGreaterThanOrEqual() { return $this->_orGreaterThanOrEqual( func_get_args(), func_num_args() ); }
	final public function OrIn() { return $this->_orIn( func_get_args(), func_num_args() ); }
	final public function OrIs() { return $this->_orIs( func_get_args(), func_num_args() ); }
	final public function OrIsNot() { return $this->_orIsNot( func_get_args(), func_num_args() ); }
	final public function OrIsNotNull() { return $this->_orIsNotNull( func_get_args(), func_num_args() ); }
	final public function OrIsNull() { return $this->_orIsNull( func_get_args(), func_num_args() ); }
	final public function OrLess() { return $this->_orLess( func_get_args(), func_num_args() ); }
	final public function OrLessThan() { return $this->_orLessThan( func_get_args(), func_num_args() ); }
	final public function OrLessThanOrEqual() { return $this->_orLessThanOrEqual( func_get_args(), func_num_args() ); }
	final public function OrLike() { return $this->_orLike( func_get_args(), func_num_args() ); }
	final public function OrNot() { return $this->_orNot( func_get_args(), func_num_args() ); }
	final public function OrNotBetween() { return $this->_orNotBetween( func_get_args(), func_num_args() ); }
	final public function OrNotEqual() { return $this->_orNotEqual( func_get_args(), func_num_args() ); }
	final public function OrNotIn() { return $this->_orNotIn( func_get_args(), func_num_args() ); }
	final public function OrNotLike() { return $this->_orNotLike( func_get_args(), func_num_args() ); }
	final public function OrNotNull() { return $this->_orNotNull( func_get_args(), func_num_args() ); }
	final public function OrWhere() { return $this->_orWhere( func_get_args(), func_num_args() ); }
	final public function OrBetweenAnd() { return $this->_orBetweenAnd( func_get_args(), func_num_args() ); }
	final public function OrEqualAnd() { return $this->_orEqualAnd( func_get_args(), func_num_args() ); }
	final public function OrGreaterAnd() { return $this->_orGreaterAnd( func_get_args(), func_num_args() ); }
	final public function OrGreaterThanAnd() { return $this->_orGreaterThanAnd( func_get_args(), func_num_args() ); }
	final public function OrGreaterThanOrEqualAnd() { return $this->_orGreaterThanOrEqualAnd( func_get_args(), func_num_args() ); }
	final public function OrInAnd() { return $this->_orInAnd( func_get_args(), func_num_args() ); }
	final public function OrIsAnd() { return $this->_orIsAnd( func_get_args(), func_num_args() ); }
	final public function OrIsNotAnd() { return $this->_orIsNotAnd( func_get_args(), func_num_args() ); }
	final public function OrIsNotNullAnd() { return $this->_orIsNotNullAnd( func_get_args(), func_num_args() ); }
	final public function OrIsNullAnd() { return $this->_orIsNullAnd( func_get_args(), func_num_args() ); }
	final public function OrLessAnd() { return $this->_orLessAnd( func_get_args(), func_num_args() ); }
	final public function OrLessThanAnd() { return $this->_orLessThanAnd( func_get_args(), func_num_args() ); }
	final public function OrLessThanOrEqualAnd() { return $this->_orLessThanOrEqualAnd( func_get_args(), func_num_args() ); }
	final public function OrLikeAnd() { return $this->_orLikeAnd( func_get_args(), func_num_args() ); }
	final public function OrNotAnd() { return $this->_orNotAnd( func_get_args(), func_num_args() ); }
	final public function OrNotBetweenAnd() { return $this->_orNotBetweenAnd( func_get_args(), func_num_args() ); }
	final public function OrNotEqualAnd() { return $this->_orNotEqualAnd( func_get_args(), func_num_args() ); }
	final public function OrNotInAnd() { return $this->_orNotInAnd( func_get_args(), func_num_args() ); }
	final public function OrNotLikeAnd() { return $this->_orNotLikeAnd( func_get_args(), func_num_args() ); }
	final public function OrNotNullAnd() { return $this->_orNotNullAnd( func_get_args(), func_num_args() ); }
	final public function OrWhereAnd() { return $this->_orWhereAnd( func_get_args(), func_num_args() ); } 
	final public function WhereDate(/***/) { return $this->_whereDate( func_get_args(), func_num_args() ); } 
	final public function WhereDay(/***/) { return $this->_whereDay( func_get_args(), func_num_args() ); } 
	final public function WhereMonth(/***/) { return $this->_whereMonth( func_get_args(), func_num_args() ); } 
	final public function WhereYear(/***/) { return $this->_whereYear( func_get_args(), func_num_args() ); } 
	final public function WhereCount(/***/) { return $this->_whereCount( func_get_args(), func_num_args() ); } 
	final public function WhereSum(/***/) { return $this->_whereSum( func_get_args(), func_num_args() ); } 
	final public function WhereAvg(/***/) { return $this->_whereAvg( func_get_args(), func_num_args() ); } 
	final public function WhereMax(/***/) { return $this->_whereMax( func_get_args(), func_num_args() ); } 
	final public function WhereMin(/***/) { return $this->_whereMin( func_get_args(), func_num_args() ); } 
	final public function GroupBy() { return $this->_groupBy( func_get_args(), func_num_args() ); } 
	final public function Sort() { return $this->_orderBy( func_get_args(), func_num_args() ); } 
	final public function SortBy() { return $this->_orderBy( func_get_args(), func_num_args() ); } 
	final public function Order() { return $this->_orderBy( func_get_args(), func_num_args() ); } 
	final public function OrderBy() { return $this->_orderBy( func_get_args(), func_num_args() ); } 
	final public function Limit() { return $this->_setLimit( func_get_args(), func_num_args() ); }
	final public function Offset() { return $this->_setSeek( func_get_args(), func_num_args() ); }
	final public function Seek() { return $this->_setSeek( func_get_args(), func_num_args() ); }
	final public function SetPage() { return $this->_setPage( func_get_args(), func_num_args() ); }
	final public function Page() { return $this->_setPage( func_get_args(), func_num_args() ); } 
	final public function HasOne() { return call_user_func_array([$this, mcbm_order_has_one], array(func_get_args(), func_num_args())); } 
	final public function BlindHasOne() { return call_user_func_array([$this, mcbm_hide_has_one], array()); }
	final public function DisplayHasOne() { return call_user_func_array([$this, mcbm_show_has_one], array()); } 
	final public function HasMany() { return call_user_func_array(array($this, mcbm_order_has_many), array(func_get_args(), func_num_args())); } 
	final public function BlindHasMany() { return call_user_func_array([$this, mcbm_hide_has_many], array()); }
	final public function DisplayHasMany() { return call_user_func_array([$this, mcbm_show_has_many], array()); }
	final public function HasManyAsBelongsToMany() { return call_user_func_array([$this, mcbm_order_has_mabtm], array(func_get_args(), func_num_args())); } 
	final public function BlindHasManyAsBelongsToMany() { return call_user_func_array([$this, mcbm_hide_has_mabtm], array()); }
	final public function DisplayHasManyAsBelongsToMany() { return call_user_func_array([$this, mcbm_show_has_mabtm], array()); }
	final public function Import() { return call_user_func_array([$this, mcbm_order_import], array(func_get_args(), func_num_args())); } 
	final public function Merge() { return call_user_func_array([$this, mcbm_order_merge], array(func_get_args(), func_num_args())); } 
	final public function MergeLeft() { return call_user_func_array([$this, mcbm_order_merge_left], array(func_get_args(), func_num_args())); } 
	final public function MergeRight() { return call_user_func_array([$this, mcbm_order_merge_right], array(func_get_args(), func_num_args())); } 
	final public function New() { return $this->_new(); }
	final public function Reset() { return $this->_new(); }
	final public function Clear( $deep=false ) { return $this->_clear( $deep ); } 
	final public function Query() { return call_user_func_array([$this, mcbm_custom], array(func_get_args(), func_num_args(), 'Query')); } 
	final public function Custom() { return call_user_func_array([$this, mcbm_custom], array(func_get_args(), func_num_args())); }
	final public function Search() { return call_user_func_array([$this, mcbm_search], array(func_get_args(), func_num_args())); } 
	final public function Find() { return call_user_func_array([$this, mcbm_findid], array(func_get_args(), func_num_args())); } 
	final public function First() { return call_user_func_array([$this, mcbm_first], array(func_get_args(), func_num_args())); } 
	final public function Last() { return call_user_func_array([$this, mcbm_last], array(func_get_args(), func_num_args())); } 
	final public function Entity() { return call_user_func_array([$this, mcbm_entity], array(func_get_args(), func_num_args())); } 
	final public function Item() { return call_user_func_array([$this, mcbm_item], array(func_get_args(), func_num_args())); }
	final public function Paginate() { return call_user_func_array([$this, mcbm_paginate], array(func_get_args(), func_num_args())); }
	final public function Delete() { return call_user_func_array([$this, mcbm_delete], array(func_get_args(), func_num_args())); }
	final public function Save() { return call_user_func_array([$this, mcbm_save], array(func_get_args(), func_num_args())); }
	final public function TotalPages() { return call_user_func_array([$this, mcbm_total_pages], array(func_get_args(), func_num_args())); } 
	final public function Total() { return call_user_func_array([$this, mcbm_total], array(func_get_args(), func_num_args())); } 
	final public function Count() { return call_user_func_array([$this, mcbm_count], array(func_get_args(), func_num_args())); } 
	final public function Distinct() { return call_user_func_array([$this, mcbm_distinct], array(func_get_args(), func_num_args())); } 
	final public function Sum() { return call_user_func_array([$this, mcbm_sum], array(func_get_args(), func_num_args())); } 
	final public function Avg() { return call_user_func_array([$this, mcbm_avg], array(func_get_args(), func_num_args())); } 
	final public function Max() { return call_user_func_array([$this, mcbm_max], array(func_get_args(), func_num_args())); } 
	final public function Min() { return call_user_func_array([$this, mcbm_min], array(func_get_args(), func_num_args())); } 
	final public function Implode() { return call_user_func_array([$this, mcbm_implode], array(func_get_args(), func_num_args())); } 
	final public function Length() { return call_user_func_array([$this, mcbm_length], array(func_get_args(), func_num_args())); } 
	final public function DBList() { return call_user_func_array([$this, mcbm_db_list], array(func_get_args(), func_num_args())); }
	final public function Row() { return call_user_func_array([$this, mcbm_row], array(func_get_args(), func_num_args())); } 
	final public function Connect( $address, $account, $pwd, $name ) { return $this->_connect( $address, $account, $pwd, $name ); }
	final public function GetError() { return $this->_getError(); } 
	final public function GetQuery() { return $this->_getQuerySQL(); }
	final public function GetQuerySQLs() { return $this->_getQuerySQLs(); } 
	final public function GetQuerySQL() { return $this->_getQuerySQL(); }
	final public function GetCollectionString() { return $this->_buildCollectionString(); }
	
	final public function ShareMainQuery() { return $this->_shareMainQuery(); }
	final public function GenRandString( $len=10 ) { return $this->_genRandString($len); }
	
	abstract protected function _initConn();
	final protected function _mergeTable() 
	{ 
		if( EMPTY_CHAR===$this->_propAlias && isset($this->_alias) )
			$this->_propAlias = $this->_alias; 
		if( EMPTY_CHAR===$this->_propTable && isset($this->_table) )
			$this->_propTable = $this->_propPrefix.$this->_table; 
		if( EMPTY_CHAR===$this->_propModel && isset($this->_model))
			$this->_propModel = $this->_model; 
		return $this;
	} 
	
	final protected function _setupModel() 
	{
		$this->_fetchCacheColumns(); 
	} 
	
	final protected function _parseDescribe( $tableName ) 
	{
		global $cache;
		$describe = $cache->get('describe'.$tableName);
		if (!$describe && $this->_dbHandle) 
		{
			$describe = array();
			$sql = 'DESCRIBE '.$tableName;
			$result = $this->_query( $sql );
			while ($row = $this->fetch_row($result)) 
				array_push($describe,$row[0]); 
			$this->free_result($result);
			$cache->set('describe'.$tableName,$describe);
		}
		return $describe; 
	} 
	
	final protected function _fetchCacheColumns() 
	{
		if( empty($this->_propsDescribe) )
			$this->_propsDescribe = $this->_parseDescribe($this->_propTable); 
		foreach( $this->_propsDescribe as $fieldName ) 
		{
			$this->$fieldName = NULL; 
			$this->_boundField( $fieldName ); 
		} 
		return $this->_propsUndescribe;
	} 
	
	private function _clear( $deep=false ) 
	{
		// Be keep to forward the counting
		if( $deep ) 
		{
			foreach( $this->_propsDescribe as $field ) 
				$this->$field = NULL;
			$this->_querySQL = NULL; 
			$this->_querySQLs = array();
			$this->_propLimit = NULL;
			$this->_propOffset = 0;
		}
		$this->_propsUndescribe = $this->_fetchCacheColumns(); 
		$this->_propsCond 		= array(); 
		$this->_propsCondOr 	= array(); 
		$this->_propsCondOn 	= array();
		$this->_propsCondCmd 	= array();
		$this->_propsGroupBy	= array();
		$this->_propPage		= NULL;
		$this->_flagHasOne 		= false;
		$this->_flagHasMany 	= false; 
		$this->_flagHasMABTM 	= false; 
		$this->_propsImport		= array();
		$this->_propsMerge		= array(); 
		$this->_propsMergeLeft	= array(); 
		$this->_propsMergeRight	= array(); 
		$this->_propsOrderCmd = array();			
		$this->_propsOrder = array();
		
		return $this;
	} 
	
	private function _logsql( $sql ) 
	{
		$this->_querySQL = $sql;
		$this->_querySQLs[] = $sql; 
	}
	
	private function _custom( $args, $argsNum, $mn="Custom" ) 
	{
		try 
		{
			if( $argsNum ) 
			{
				global $inflect; 
				$sql = current( $args );
				$sql = str_replace( ':table', $this->_propTable, $sql ); 
				$sql = str_replace( ':model', $this->_propModel, $sql ); 
				$qr = $this->_query( $sql );
				$out = array(); 
				if( $qr ) 
				{
					$ts;
					$fs; 
					$numf = $this->fetch_field( $qr, $ts, $fs ); 
					while( $r = $this->fetch_row($qr) ) 
					{
						$tmps = array(); 
						for( $i=head; $i<$numf; $i++ ) 
							$tmps[$ts[$i]][$fs[$i]] = $r[$i];
						
						array_push($out,$tmps);
					}
					$this->free_result($qr); 
				}
				return $out;
			} 
			else 
				throw new Exception( "Usage <strong>Model::".$mn."()</strong> is incorrect." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
	} 
	
	final protected function _parseSqlSelection( $m, $d ) 
	{
		$sqls = array(); 
		foreach( $d as $f ) 
			if( NULL===$f['label'] ) 
				$sqls[] = " `{$m}`.`{$f['name']}`"; 
			else 
				$sqls[] = " `{$m}`.`{$f['name']}` AS `{$f['label']}`"; 
		return $sqls; 
	} 
	
	final protected function _parseSqlCondition( $m, $c ) 
	{
		$sqls = array(); 
		foreach( $c as $f ) 
		{ 
			if(is_numeric($f[2])) 
			{
				$sqls[] = "`{$m}`.`{$f[0]}` {$f[1]} {$f[2]} ";
			} 
			else 
			{
				$sqls[] = "`{$m}`.`{$f[0]}` {$f[1]} '{$this->escape_string($f[2])}' ";
			}
		} 
		return $sqls;
	} 
	
	final protected function _parseSqlHasOne() 
	{
		return "LEFT JOIN `{$this->_propTable}` AS `{$this->_propModel}` ON `{$this->_propModel}`.`{$this->_propForeignKey}` = `{$this->_propAliasModel}`.`{$this->_propAliasKey}` "; 
	} 
	
	final protected function _parseSqlMerge() 
	{
		return "INNER JOIN `{$this->_propTable}` AS `{$this->_propModel}` ON `{$this->_propModel}`.`{$this->_propForeignKey}` = `{$this->_propAliasModel}`.`{$this->_propAliasKey}` "; 
	} 
	
	final protected function _parseSqlMergeLeft() 
	{
		return "LEFT JOIN `{$this->_propTable}` AS `{$this->_propModel}` ON `{$this->_propModel}`.`{$this->_propForeignKey}` = `{$this->_propAliasModel}`.`{$this->_propAliasKey}` "; 
	} 
	
	final protected function _parseSqlMergeRight() 
	{
		return "RIGHT JOIN `{$this->_propTable}` AS `{$this->_propModel}` ON `{$this->_propModel}`.`{$this->_propForeignKey}` = `{$this->_propAliasModel}`.`{$this->_propAliasKey}` "; 
	}
	
	final protected function _buildSqlSelection() 
	{
		$defSql = "SELECT"; 
		$outSql = EMPTY_STRING; 
		
		$sqls = $this->_parseSqlSelection( $this->_propModel, $this->_propsUndescribe ); 
		
		if( $this->_flagHasOne ) 
			if( $this->_flagHasOne && !empty($this->_propsHasOne) ) 
				foreach( $this->_propsHasOne as $model ) 
					if( $model->isLive() ) 
						$sqls = array_unique(array_merge($sqls, $model->parseSqlSelection())); 
					
		if( !empty($this->_propsMerge) ) 
			foreach( $this->_propsMerge as $model ) 
				$sqls = array_unique(array_merge($sqls, $model->parseSqlSelection())); 
		
		if( !empty($this->_propsMergeLeft) ) 
			foreach( $this->_propsMergeLeft as $model ) 
				$sqls = array_unique(array_merge($sqls, $model->parseSqlSelection())); 
		
		if( !empty($this->_propsMergeRight) ) 
			foreach( $this->_propsMergeRight as $model ) 
				$sqls = array_unique(array_merge($sqls, $model->parseSqlSelection()));
		
		$outSql = $defSql . implode( comma, $sqls ) . space; 
		return $outSql; 
	} 
	
	final protected function _buildSqlFrom() 
	{
		$defSql = "FROM"; 
		$outSql = "`{$this->_propTable}` AS `{$this->_propModel}` "; 
		
		if( $this->_flagHasOne ) 
			if( $this->_flagHasOne && !empty($this->_propsHasOne) ) 
				foreach( $this->_propsHasOne as $model ) 
					$outSql .= $model->parseSqlHasOne(); 
					
		if( !empty($this->_propsMerge) ) 
			foreach( $this->_propsMerge as $model ) 
				$outSql .= $model->parseSqlMerge(); 
		
		if( !empty($this->_propsMergeLeft) ) 
			foreach( $this->_propsMergeLeft as $model ) 
				$outSql .= $model->parseSqlMergeLeft(); 
		
		if( !empty($this->_propsMergeRight) ) 
			foreach( $this->_propsMergeRight as $model ) 
				$outSql .= $model->parseSqlMergeRight(); 
		
		$outSql = $defSql . space . $outSql; 
		return $outSql;
	} 
	
	final protected function _buildSqlCondition() 
	{ 
		$defSql = "WHERE 1=1"; 
		$outSql = EMPTY_STRING; 
		$sqls = array(); 
		
		if( !empty($this->_propsCond) ) 
			$sqls += $this->_parseSqlCondition( $this->_propModel, $this->_propsCond ); 
		if( !empty($this->_propsCondOr) ) 
			leave($this->_propsCondOr);  
		if( !empty($this->_propsCondCmd) ) 
			leave($this->_propsCondCmd); 
		if( !empty($sqls) ) 
			$outSql .= "AND ";
		$outSql .= implode("AND ", $sqls);
		$outSql = $defSql . space . $outSql;
		return $outSql;
	} 
	
	final protected function _buildSqlRange() 
	{
		$outSql = EMPTY_STRING;
		if( $this->_propLimit ) 
		{
			if( isset($this->_propPage) )  
				$offset = ( $this->_propPage-1 ) * $this->_propLimit;
			else
				$offset = $this->_propOffset;
			$outSql .= "LIMIT {$this->_propLimit} OFFSET {$offset} ";
		} 
		return $outSql;
	} 
	
	final protected function _buildSqlGroup() 
	{
		$outSql = EMPTY_STRING;
		if( !empty($this->_propsGroupBy) ) 
		{
			debug($this->_propsGroupBy); 
		} 
		return $outSql;
	} 
	
	final protected function _buildSqlOrder() 
	{
		$defSql = "ORDER BY"; 
		$outSql = EMPTY_STRING;
		if( !empty($this->_propsOrder) ) 
			foreach( $this->_propsOrder as $field ) 
				$outSql .= " `{$this->_propModel}`.`{$field[0]}` {$field[1]} "; 
		if( !empty($this->_propsOrderCmd) ) 
		{
			leave($this->_propsOrderCmd);
		}
		if( EMPTY_STRING!==$outSql ) 
			$outSql = $defSql . space . $outSql; 
		return $outSql;
	} 
	
	final protected function _buildSqlIdCondition( $value ) 
	{
		$outSql = EMPTY_STRING; 
		if( is_string($value) ) 
		{
			$value = $this->escape_string($value);
			$outSql = "WHERE `{$this->_propModel}`.`{$this->_primaryKey}` = '$value' "; 
		} 
		else if( is_numeric($value) )
		{
			$outSql = "WHERE `{$this->_propModel}`.`{$this->_primaryKey}` = $value "; 
		} 
		return $outSql;
	} 
	
	final protected function _buildSqlOneRange() 
	{
		return "LIMIT 1 OFFSET 0 ";
	} 
	
	final protected function _buildSqRevertOrder() 
	{
		return "ORDER BY `{$this->_propModel}`.`{$this->_primaryKey}` DESC ";
	}
	
	private function _fetchResult( $qr ) 
	{
		$out = array(); 
		if( $qr ) 
		{
			$ts; 
			$fs; 
			$numf = $this->fetch_field( $qr, $ts, $fs ); 
			while( $r = $this->fetch_row($qr) ) 
			{
				$tmps = array(); 
				for( $i=head; $i<$numf; $i++ ) 
					$tmps[$ts[$i]][$fs[$i]] = $r[$i];
				
				if( $this->_flagHasMany && !empty($this->_propsHasMany) ) 
				{
					leave($this->_propsHasMany);
				} 
				
				if( $this->_flagHasMABTM && !empty($this->_propsHasMABTM) ) 
				{ 
					leave($this->_propsHasMABTM); 
				} 
				array_push($out,$tmps); 
			} 
			$this->free_result($qr); 
		} 
		$this->_clear(); 
		return $out; 
	}
	
	private function _search( $args, $argsNum ) 
	{
		$selectSql = $this->_buildSQLSelection(); 
		$fromSql = $this->_buildSqlFrom(); 
		$condSql = $this->_buildSqlCondition(); 
		$groupSql = $this->_buildSqlGroup(); 
		$orderSql = $this->_buildSqlOrder(); 
		$rangeSql = $this->_buildSqlRange(); 
		$sql = $selectSql . $fromSql . $condSql . $groupSql . $orderSql . $rangeSql; 
		$queryResult = $this->_query( $sql ); 
		
		return $this->_fetchResult($queryResult); 
		
		global $inflect;
		$commandTable = 'command'; 
		$conditionsChild = '';
		$fromChild = '';
		$prefix = $this->_retrivePrefix();
		
		
		$this->_buildMainQuery( $prefix );
		$result = array();
		$table = array();
		$field = array();
		$tempResults = array();
		$rsNumRows = 0;
		array_push( $this->_querySQLs, $this->_querySQL );
		
		if( $this->_result ) 
		{
			$rsNumRows = mysqli_num_rows( $this->_result );
			if( $rsNumRows > 0 ) 
			{
				$numOfFields = mysqli_num_fields( $this->_result );
				while( $field_info = mysqli_fetch_field($this->_result) )
				{
					$ignoreField = false;
					foreach( $this->_propsUndescribe as $columnName ) 
					{
						if( stripos($columnName, DOT) ) 
						{
							if( "$field_info->table.$field_info->name" === $columnName ) 
								$ignoreField = true;
						}
						else if( $field_info->name === $columnName && $field_info->table === $this->_propModel ) 
						{
							$ignoreField = true; 
						}
					} 

					if( $ignoreField ) 
					{
						array_push( $table, NULL );
						array_push( $field, NULL );
					}
					else 
					{
						if( $field_info->table == EMPTY_CHAR ) 
						{
							if( EMPTY_CHAR!==$this->_propModel )
								$commandTable = $this->_propModel;
							array_push( $table, $commandTable );
						}
						else
							array_push( $table, $field_info->table );
						array_push( $field, $field_info->name );
					} 
				}

				while( $row = mysqli_fetch_row( $this->_result ) ) 
				{
					for( $i=0; $i<$numOfFields; ++$i ) 
						if( NULL!==$table[$i] && NULL!==$field[$i] ) 
							$tempResults[$table[$i]][$field[$i]] = $row[$i];
					if( $this->_flagHasMany && isset( $this->_hasMany ) ) 
					{
						foreach ( $this->_hasMany as $modelChild => $aliasChild ) 
						{
							if( in_array( $modelChild, $this->_hasManyBlind ) ) 
							{
								continue; 
							}

							$queryChild = '';
							$conditionsChild = '';
							$limitChild = NL."LIMIT 1000";
							$orderChild = "";
							$fromChild = '';
							$aliasKey = $aliasChild[ 'key' ];
							$tableChild = $aliasChild[ 'table' ];
							$aliasChild = explode( '_', $aliasChild[ 'table' ] );
							if( ($aliasChild[0].'_')===$prefix ) unset($aliasChild[0]);
							foreach( $aliasChild as $key => $value ) 
								$aliasChild[ $key ] = ucfirst( $inflect->singularize( $value ) );
							$modelAlias = implode( '', $aliasChild );
							$fromChild .= '`'.$tableChild.'` as `'.$modelAlias.'`';
							$conditionsChild .= '`'.$modelAlias.'`.`'.$aliasKey.'` = \''.$tempResults[$this->_propModel][ID].'\' '.NL;

							if( isset($this->_hasMany[ $modelChild ][ 'conds' ]) ) 
							{
								if( is_array($this->_hasMany[ $modelChild ][ 'conds' ]) ) 
								{
									$conds = $this->_hasMany[ $modelChild ][ 'conds' ];
									foreach( $conds as $cond ) 
									{
										switch( $cond[ 'operator' ] ) 
										{
											case 'BETWEEN':
												if( is_string($cond[ 'start' ]) ) 
													$sql_start_value = "'".mysqli_real_escape_string( $this->_dbHandle, $cond[ 'start' ] )."'";
												elseif( is_bool($cond[ 'start' ]) ) 
													$sql_start_value = ($cond[ 'start' ])?1:0; 
												elseif( is_null($cond[ 'start' ]) ) 
													$sql_start_value = 'NULL';
												else
													$sql_start_value = $cond[ 'start' ];

												if( is_string($cond[ 'end' ]) )
													$sql_end_value = "'".mysqli_real_escape_string( $this->_dbHandle, $cond[ 'end' ] )."'"; 
												elseif( is_bool($cond[ 'end' ]) ) 
													$sql_end_value = ($cond[ 'end' ])?1:0;
												elseif( is_null($cond[ 'end' ]) )
													$sql_end_value = 'NULL';
												else 
													$sql_end_value = $cond[ 'end' ];

												$sql_value = $sql_start_value . " AND " . $sql_end_value;
												break;

											case 'IN': 
											case 'NOT IN': 
												$sql_value = $cond[ 'value' ];
												break; 

											case 'LIKE': 
											case 'NOT LIKE': 
												try 
												{
													if( is_null($cond[ 'value' ]) ) 
														throw new Exception("The value with ".$cond[ 'operator' ]." operator could not be NULL", 1);
													elseif( is_bool($cond[ 'value' ]) ) 
														throw new Exception("The value with ".$cond[ 'operator' ]." operator could not be BOOLEAN", 1);
													else 
														$sql_value = "'%".mysqli_real_escape_string( $this->_dbHandle, $cond[ 'value' ] )."%'";
												} 
												catch( Exception $e ) 
												{
													trace_once( $e );
												}
												break;

											default:
												if( is_string($cond[ 'value' ]) ) 
													$sql_value = "'".mysqli_real_escape_string( $this->_dbHandle, $cond[ 'value' ] )."'";
												elseif( is_null($cond[ 'value' ]) ) 
													$sql_value = 'NULL';
												elseif( is_bool($cond[ 'value' ]) ) 
													$sql_value = ($cond[ 'value' ])?1:0;
												else 
													$sql_value = $cond[ 'value' ];
												break;
										}
										$conditionsChild .= "AND `".$modelAlias."`.`".$cond[ 'field' ]."` ".$cond[ 'operator' ]." ".$sql_value." ".NL;
									}
								}
							} 

							$describeArr_r = array();
							if( isset($this->_hasMany[ $modelChild ][ 'describe' ]) ) 
							{
								$describes = $this->_hasMany[ $modelChild ][ 'describe' ];
								if( is_array($describes) ) 
								{
									foreach( $describes as $describe ) 
									{
										foreach($describe[ 'field' ] as $field_r) 
										{
											$describeSql_r = "`".$modelAlias."`.`".$field_r."`";
											if( isset($describe[ 'label' ]) && isset($describe[ 'label' ][ $field_r ]) )
												$describeSql_r .= " AS '".$describe[ 'label' ][ $field_r ]."'";
											array_push( $describeArr_r, $describeSql_r );
										}
									}
								}
							}
							if( !empty($describeArr_r) ) 
							{
								$includeColume = implode( ', ', $describeArr_r );
							} 
							else 
							{
								$includeColume = '*';
							} 

							if( isset($this->_hasMany[ $modelChild ][ 'num_rows' ]) ) 
							{
								$limitChild = NL."LIMIT ".$this->_hasMany[ $modelChild ][ 'num_rows' ];
							}

							if( isset($this->_hasMany[ $modelChild ][ 'reverse' ]) ) 
							{
								$orderChild = NL."ORDER BY `".$modelAlias."`.`".mysqli_real_escape_string($this->_dbHandle, $this->_hasMany[ $modelChild ][ 'reverse' ])."` DESC";
							}

							$queryChild =  'SELECT '.$includeColume.' FROM '.$fromChild.' WHERE '.$conditionsChild.$orderChild.$limitChild;	
							$resultChild = mysqli_query( $this->_dbHandle, $queryChild );
							
							$tableChild = array();
							$fieldChild = array();
							$temp_results_child = array();
							$results_child = array();
							array_push( $this->_querySQLs, $queryChild );

							if( $resultChild ) 
							{
								if( mysqli_num_rows($resultChild) > 0 ) 
								{
									$undescribes = NULL;
									if( isset($this->_hasMany[ $modelChild ][ 'undescribe' ]) ) 
									{
										$undescribes = $this->_hasMany[ $modelChild ][ 'undescribe' ];
									}
									
									$numOfFieldsChild = mysqli_num_fields( $resultChild );

									while( $field_info = mysqli_fetch_field($resultChild) ) 
									{
										if( NULL!==$undescribes && in_array($field_info->name, $undescribes) ) 
										{
											array_push( $fieldChild, 0 );
										}
										else
										{
											array_push( $fieldChild, $field_info->name );
										}
										array_push( $tableChild, $field_info->table );
									}

									while( $rowChild = mysqli_fetch_row($resultChild) ) 
									{
										for ($j = 0;$j < $numOfFieldsChild; ++$j) 
										{
											if($fieldChild[$j])
												$temp_results_child[$tableChild[$j]][$fieldChild[$j]] = $rowChild[$j];
										}
										array_push( $results_child, $temp_results_child );
									}
								}
								
								if(!empty( $results_child ))
									$tempResults[ $modelChild ] = $results_child;
								else 
									unset( $tempResults[ $modelChild ] );
								
								mysqli_free_result($resultChild);
							}
						} 
					}

					if ($this->_flagHasMABTM && isset($this->_hasManyAndBelongsToMany)) 
					{
						foreach ($this->_hasManyAndBelongsToMany as $modelChild => $aliasChild) 
						{
							$queryChild = '';
							$conditionsChild = '';
							$limitChild = NL."LIMIT 1000";
							$orderChild = "";
							$fromChild = '';

							$cacheChild = $aliasChild;
							
							// $joinKey = strtolower($inflect->singularize($aliasChild)).'_id';
							// if( isset( $cacheChild[ 'join' ] ) ) 
							// {
							// 	$tableModel = $this->_propModelSort( $cacheChild['data']['table'] );
							// 	$joinTable = $prefix . $this->_propTableSort( $cacheChild['join']['table'] );
							// 	$joinModel = $this->_propModelSort( $cacheChild['join']['table'] );
							// 	$aliasKey = $cacheChild['join']['key'];
							// } 
							// else 
							// {
							// 	$pluralAliasTable = strtolower($this->_propAlias);
							// 	$pluralAliasChild = strtolower($aliasChild);
							// 	$sortTables = explode( '_', $pluralAliasTable.'_'.$pluralAliasChild );
							// 	sort($sortTables);
							// 	foreach( $sortTables as $key => $value ) 
							// 	{
									
							// 	}
							// 	$joinTable = $prefix . implode('_',$sortTables);
							// 	$tableModel = $this->_propModelSort( $aliasChild );
							// 	$tableChild = $prefix . $this->_propTableSort( $aliasChild );
							// 	$sortAliases = array( $this->_propModel, $tableModel );
							// 	sort($sortAliases);
							// 	$joinModel = implode('', $sortAliases);
							// 	$aliasKey = $this->_propAlias.'_id';
							// }
							
							$tableChild = $cacheChild['data']['table'];
							$aliasChild = explode( '_', $tableChild );
							if( ($aliasChild[0].'_')===$prefix ) unset($aliasChild[0]);
							foreach( $aliasChild as $key => $value )
								$aliasChild[ $key ] = ucfirst( $inflect->singularize( $value ) ); 
							$tableModel = implode( '', $aliasChild ); 
							$joinTable = $cacheChild['join']['table'];
							$joinAlias = explode( '_', $joinTable );
							if( ($joinAlias[0].'_')===$prefix ) unset($joinAlias[0]);
							foreach( $joinAlias as $key => $value )
								$joinAlias[ $key ] = ucfirst( $inflect->singularize( $value ) ); 
							$joinModel = implode( '', $joinAlias ); 
							$fromChild .= '`'.$tableChild.'` as `'.$tableModel.'`,';
							$fromChild .= '`'.$joinTable.'` as `'.$joinModel.'`,';
							$conditionsChild .= "`".$joinModel."`.`".$cacheChild['data']['key']."` = `".$tableModel."`.`id`"." ".NL;
							$conditionsChild .= "AND `".$joinModel."`.`".$cacheChild['join']['key']."` = '".$tempResults[$this->_propModel]['id']."'"." ".NL;

							if( isset($this->_hasManyAndBelongsToMany[ $modelChild ][ 'conds' ]) ) 
							{
								if( is_array($this->_hasManyAndBelongsToMany[ $modelChild ][ 'conds' ] ) ) 
								{
									$conds = $this->_hasManyAndBelongsToMany[ $modelChild ][ 'conds' ];
									foreach( $conds as $cond ) 
									{
										switch( $cond[ 'operator' ] ) 
										{
											case 'BETWEEN':
												if( is_string($cond[ 'start' ]) ) 
													$sql_start_value = "'".mysqli_real_escape_string( $this->_dbHandle, $cond[ 'start' ] )."'";
												elseif( is_bool($cond[ 'start' ]) ) 
													$sql_start_value = ($cond[ 'start' ])?1:0; 
												elseif( is_null($cond[ 'start' ]) ) 
													$sql_start_value = 'NULL';
												else
													$sql_start_value = $cond[ 'start' ];

												if( is_string($cond[ 'end' ]) )
													$sql_end_value = "'".mysqli_real_escape_string( $this->_dbHandle, $cond[ 'end' ] )."'"; 
												elseif( is_bool($cond[ 'end' ]) ) 
													$sql_end_value = ($cond[ 'end' ])?1:0;
												elseif( is_null($cond[ 'end' ]) )
													$sql_end_value = 'NULL';
												else 
													$sql_end_value = $cond[ 'end' ];

												$sql_value = $sql_start_value . " AND " . $sql_end_value;
												break;

											case 'IN': 
											case 'NOT IN': 
												$sql_value = $cond[ 'value' ];
												break; 

											case 'LIKE': 
											case 'NOT LIKE': 
												try 
												{
													if( is_null($cond[ 'value' ]) ) 
														throw new Exception("The value with ".$cond[ 'operator' ]." operator could not be NULL", 1);
													elseif( is_bool($cond[ 'value' ]) ) 
														throw new Exception("The value with ".$cond[ 'operator' ]." operator could not be BOOLEAN", 1);
													else 
														$sql_value = "'%".mysqli_real_escape_string( $this->_dbHandle, $cond[ 'value' ] )."%'";
												} 
												catch( Exception $e ) 
												{
													trace_once( $e );
												}
												break;

											default:
												if( is_string($cond[ 'value' ]) ) 
													$sql_value = "'".mysqli_real_escape_string( $this->_dbHandle, $cond[ 'value' ] )."'";
												elseif( is_null($cond[ 'value' ]) ) 
													$sql_value = 'NULL';
												elseif( is_bool($cond[ 'value' ]) ) 
													$sql_value = ($cond[ 'value' ])?1:0;
												else 
													$sql_value = $cond[ 'value' ];
												break;
										}
										$conditionsChild .= "AND `".$tableModel."`.`".$cond[ 'field' ]."` ".$cond[ 'operator' ]." ".$sql_value." ".NL;
									}
								}
							} 

							$describeArr_r = array();
							if( isset($this->_hasManyAndBelongsToMany[ $modelChild ][ 'describe' ]) ) 
							{
								$describes = $this->_hasManyAndBelongsToMany[ $modelChild ][ 'describe' ];
								if( is_array($describes) ) 
								{
									foreach( $describes as $describe ) 
									{
										foreach($describe[ 'field' ] as $field_r) 
										{
											$describeSql_r = "`".$tableModel."`.`".$field_r."`";
											if( isset($describe[ 'label' ]) && isset($describe[ 'label' ][ $field_r ]) )
												$describeSql_r .= " AS '".$describe[ 'label' ][ $field_r ]."'";
											array_push( $describeArr_r, $describeSql_r );
										}
									}
								}
							}
							if( !empty($describeArr_r) ) 
							{
								$includeColume = implode( ', ', $describeArr_r );
							} 
							else 
							{
								$includeColume = '*';
							} 

							if( isset($this->_hasManyAndBelongsToMany[ $modelChild ][ 'num_rows' ]) ) 
							{
								$limitChild = NL."LIMIT ".$this->_hasManyAndBelongsToMany[ $modelChild ][ 'num_rows' ];
							} 

							if( isset($this->_hasManyAndBelongsToMany[ $modelChild ][ 'reverse' ]) ) 
							{
								$orderChild = NL."ORDER BY `".$tableModel."`.`".mysqli_real_escape_string( $this->_dbHandle, $this->_hasManyAndBelongsToMany[ $modelChild ][ 'reverse' ])."` DESC";
							} 

							$fromChild = substr($fromChild,0,-1);
							$queryChild =  'SELECT '.$includeColume.' FROM '.$fromChild.' WHERE '.$conditionsChild.$orderChild.$limitChild;
							$resultChild = mysqli_query( $this->_dbHandle, $queryChild );
							$tableChild = array();
							$fieldChild = array();
							$temp_results_child = array();
							$results_child = array();
							array_push( $this->_querySQLs, $queryChild );
							
							if( $resultChild ) 
							{
								if ( mysqli_num_rows( $resultChild ) > 0 ) 
								{
									$undescribes = NULL;
									if( isset($this->_hasManyAndBelongsToMany[ $modelChild ][ 'undescribe' ]) ) 
									{
										$undescribes = $this->_hasManyAndBelongsToMany[ $modelChild ][ 'undescribe' ];
									}

									$numOfFieldsChild = mysqli_num_fields( $resultChild );

									while( $field_info = mysqli_fetch_field($resultChild) ) 
									{
										if( NULL!==$undescribes && in_array($field_info->name, $undescribes) ) 
										{
											array_push( $fieldChild, 0 );
										}
										else 
										{
											array_push( $fieldChild, $field_info->name );
										}
										array_push( $tableChild, $field_info->table );
									}

									while ( $rowChild = mysqli_fetch_row( $resultChild ) ) 
									{
										for ( $j = 0;$j < $numOfFieldsChild; ++$j ) 
										{
											if( isset($this->_hasManyAndBelongsToMany[ $modelChild ][ 'hide_rel' ]) ) 
												$showRelative = $joinModel !== $tableChild[$j];
											else
												$showRelative = true;

											if($fieldChild[$j] && $showRelative) 
											{
												$temp_results_child[$tableChild[$j]][$fieldChild[$j]] = $rowChild[$j];
											}
										}
										array_push( $results_child,$temp_results_child );
									}
								}
								
								if( !empty($results_child) ) 
									$tempResults[ $modelChild ] = $results_child; 
								else 
									unset( $tempResults[ $modelChild ] );

								mysqli_free_result( $resultChild );
							}
						}
					}
					array_push( $result,$tempResults );
				}
			} 	
			mysqli_free_result( $this->_result );
		}
		$this->clear();
		// Make eloquent item.
		if( $this->id != NULL && $rsNumRows === 1 ) 
		{
			$this->_setData( $result[0][$this->_propModel] );
			if( $this->_flagHasOne || $this->_flagHasMany || $this->_flagHasMABTM ) 
				return $result[0]; 
			else 
				return $this;	// Eloquent item. 
		} 
		else if( strpos($this->_collection, 'COUNT') !== false ) 
		{
			return (int)each($result[0][$commandTable])['value'];
		} 
		return $result;
	} 
	
	private function _find( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
			{
				$selectSql = $this->_buildSQLSelection(); 
				$fromSql = $this->_buildSqlFrom(); 
				$condSql = $this->_buildSqlIdCondition( current($args) ); 
				$groupSql = $this->_buildSqlGroup(); 
				$orderSql = $this->_buildSqlOrder(); 
				$rangeSql = $this->_buildSqlRange(); 
				$sql = $selectSql . $fromSql . $condSql . $groupSql . $orderSql . $rangeSql; 
				$queryResult = $this->_query( $sql ); 
				$dataResult = $this->_fetchResult($queryResult); 
				if( isset($dataResult[head]) )
					return $dataResult[head]; 
				else 
					return $dataResult;
			} 
			else 
				throw new Exception( "Usage <strong>Model::find()</strong> is incorrect." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		}
	}
	
	private function _first( $args, $argsNum ) 
	{
		try 
		{ 
			if( 0===$argsNum ) 
			{
				$selectSql = $this->_buildSQLSelection(); 
				$fromSql = $this->_buildSqlFrom(); 
				$condSql = $this->_buildSqlCondition(); 
				$groupSql = $this->_buildSqlGroup(); 
				$orderSql = $this->_buildSqlOrder(); 
				$rangeSql = $this->_buildSqlOneRange(); 
				$sql = $selectSql . $fromSql . $condSql . $groupSql . $orderSql . $rangeSql; 
				$queryResult = $this->_query( $sql ); 
				$dataResult = $this->_fetchResult($queryResult); 
				if( isset($dataResult[head]) )
					return $dataResult[head]; 
				else 
					return $dataResult;
			} 
			else 
				throw new Exception( "Usage <strong>Model::first()</strong> is incorrect." );
		}
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
	} 
	
	private function _last( $args, $argsNum ) 
	{
		try 
		{ 
			if( 0===$argsNum ) 
			{
				$selectSql = $this->_buildSQLSelection(); 
				$fromSql = $this->_buildSqlFrom(); 
				$condSql = $this->_buildSqlCondition(); 
				$groupSql = $this->_buildSqlGroup(); 
				$orderSql = $this->_buildSqRevertOrder(); 
				$rangeSql = $this->_buildSqlOneRange(); 
				$sql = $selectSql . $fromSql . $condSql . $groupSql . $orderSql . $rangeSql; 
				$queryResult = $this->_query( $sql ); 
				$dataResult = $this->_fetchResult($queryResult); 
				if( isset($dataResult[head]) )
					return $dataResult[head]; 
				else 
					return NULL;
			} 
			else 
				throw new Exception( "Usage <strong>Model::last()</strong> is incorrect." );
		}
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
	} 
	
	private function _clone() 
	{
		$out = clone $this; 
		return $out->_reset(); 
	}
	
	private function _entity( $args, $argsNum ) 
	{ 
		try 
		{
			if( $argsNum ) 
			{
				$entity = $this->_clone();
				$dataResult = $entity->find( current($args) ); 
				if( isset($dataResult[$this->_propModel]) ) 
					return $entity->assign( $dataResult[$this->_propModel] ); 
				else 
					return NULL; 
			} 
			else 
				throw new Exception( "Usage <strong>Model::entity()</strong> is incorrect." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
	} 
	
	private function _item( $args, $argsNum ) 
	{
		if( zero===$argsNum ) 
			$mod = ':first'; 
		else if( 2===$argsNum ) 
			$mod = $args[0];
		else 
			$mod = current($args); 
		switch( $mod ) 
		{
			case ':id': 
				$data = call_user_func_array( [$this,mcbm_findid], array([$args[1]], 1) );
				break;
			case ':last':
				$data = call_user_func_array( [$this,mcbm_last], array([], 0) );
				break;
			case ':first': 
			default:
				$data = call_user_func_array( [$this,mcbm_first],array([], 0) ); 
				break;
		} 
		if( isset($data[$this->_propModel]) ) 
			return $data[$this->_propModel]; 
		else 
			return array(); 
	}
	
	private function _paginate( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
			{
				$options = current($args); 
				if( isset($options['page']) ) 
				{
					$page = $options['page']; 
					call_user_func_array( [$this, mcbm_setpage], array([$page], 1) ); 
				}
				elseif( is_null($this->_propPage) ) 
				{
					$page = 1; 
					call_user_func_array( [$this, mcbm_setpage], array([$page], 1) ); 
				} 
				else 
				{
					$page = $this->_propPage;
				}
				
				if( isset($options['limit']) ) 
				{
					$limit = $options['limit'];
					call_user_func_array( [$this, mcbm_setlimit], array([$limit], 1) );
				}
				elseif( is_null($this->_propLimit) ) 
				{
					$limit = 1000; 
					call_user_func_array( [$this, mcbm_setlimit], array([$limit], 1) ); 
				} 
				else 
				{
					$limit = $this->_propLimit; 
				} 
					
				$data = call_user_func_array( [$this, mcbm_search], array([], 0) ); 
				$total = call_user_func_array( [$this, mcbm_total], array([], 0) ); 
				$pages = (int) ceil( $total/$limit ); 
				
				return array(
					'pages'	=> $pages, 
					'total' => $total, 
					'data'	=> $data,
					'page'	=> (int) $page, 
					'limit' => (int) $limit, 
				); 
			}
		}
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
	}
	
	private function _total( $args, $argsNum ) 
	{
		try 
		{
			if( zero===$argsNum ) 
			{
				if( $this->_querySQL ) 
				{
					if( $this->_propLimit ) 
						$pattern = '/SELECT (.*?) FROM (.*)LIMIT(.*)/i';
					else
						$pattern = '/SELECT (.*?) FROM (.*)/i'; 
					$replacement = 'SELECT COUNT(*) AS `total` FROM $2';
					$sql = preg_replace( $pattern, $replacement, $this->_querySQL );
					$qr = $this->_query( $sql ); 
					$this->_reset();
					if( $qr ) 
					{
						$result = $this->fetch_assoc( $qr ); 
						$this->free_result( $qr ); 
						return (int)$result['total'];
					}
					else 
						return $qr; 
				} 
				else 
				{
					check("calulator total");
				}
			} 
			else 
				throw new Exception( "Usage <strong>Model::total()</strong> is unvalidable." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
	} 
	
	private function _distinct( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
			{
				$f = current($args); 
				$this->_propsUndescribe = array(); 
				$selectSql = $this->_buildSQLSelection(); 
				$selectSql = str_replace( "SELECT ", "SELECT DISTINCT(`{$f}`), ", $selectSql ); 
				$fromSql = $this->_buildSqlFrom(); 
				$condSql = $this->_buildSqlCondition(); 
				$groupSql = $this->_buildSqlGroup(); 
				$orderSql = $this->_buildSqlOrder(); 
				$rangeSql = $this->_buildSqlRange(); 
				$sql = $selectSql . $fromSql . $condSql . $groupSql . $orderSql . $rangeSql; 
				$qr = $this->_query( $sql ); 
				return $this->_fetchResult( $qr ); 
			}
			else 
				throw new Exception( "Usage <strong>Model::distinct()</strong> is incorrect." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		} 
	} 
	
	private function _delete( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
			{
				$cond_clause = ' WHERE 1 AND '.preg_replace("/`".$this->_propModel."`./", '', $this->_extraConditions);
				$limit_clause = '';

				if( isset($this->_propLimit) ) 
					$limit_clause = 'LIMIT '.$this->_propLimit.' '; 
				
				if( NULL===$id && isset($this->id) ) 
					$id = $this->id;

				if( $id ) 
					if( is_string($id) ) 
					{
						$id = "'".mysqli_real_escape_string( $this->_dbHandle, $id )."'";
						$cond_clause .= '`id`='.$id.' AND ';
					} 
					elseif( is_array($id) )
					{
						$id = mysqli_real_escape_string( $this->_dbHandle, implode(comma, $id) );
						$cond_clause .= '`id` IN ('.$id.') AND ';
					} 
				
				$cond_clause = substr( $cond_clause, 0, -4 ); 
				if( method_exists($this, 'down') ) 
					$this->down();
				$query = 'DELETE FROM `'.$this->_propTable.'`'.$cond_clause.$limit_clause; 
				$this->_result = mysqli_query( $this->_dbHandle, $query ); 
				$this->_querySQL = $query;
				array_push( $this->_querySQLs, $query );
				$this->clear(); 
				if( $this->_result == 0 ) 
					return -1; 
				else 
					if( method_exists($this, 'ondown') ) 
						$this->ondown(); 
			}
			else 
				throw new Exception( "Usage <strong>Model::delete()</strong> is incorrect." ); 
		}
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
	}

	private function _save( $args, $argsNum ) 
	{
		try 
		{
			$qr;
			$data = current($args); 
			if( $argsNum && is_array($data) ) 
			{
				if( is_string(key($data)) ) 
				{
					if( array_key_exists($this->_primaryKey, $data) ) 
					{
						$condSql = "`{$this->_primaryKey}` = {$data[$this->_primaryKey]}"; 
						$saveSql = array(); 
						
						if(method_exists($this, 'ride')) 
							$this->_eventRide = $this->ride();

						foreach ( $this->_propsDescribe as $field ) 
						{
							if( $field == $this->_primaryKey ) 
								continue;
							else if( array_key_exists($field, $data) ) 
								if( is_null($data[$field]) ) 
								{
									$saveSql[] = "`{$field}` = NULL"; 
								} 
								else 
								{
									$value = $this->escape_string( $data[$field] ); 
									$saveSql[] = "`{$field}` = '{$value}'"; 
								}
							else if( is_array($this->_eventRide) ) 
								if( array_key_exists($field, $this->_eventRide) ) 
								{
									$value = $this->escape_string( $this->_eventRide[$field] ); 
									$saveSql[] = "`{$field}` = '{$value}'"; 
									$data[$field] = $value;
								} 
							else if( isset($this->timestamp) && is_array($this->timestamp) ) 
								if( in_array($field, $this->timestamp) ) 
								{
									$value = date('Y-m-d H:i:s'); 
									$saveSql[] = "`{$field}` = '{$value}'"; 
									$data[$field] = $value;
								} 
						}
						$saveSql = implode( comma, $saveSql ); 
						$sql = "UPDATE `{$this->_propTable}` SET {$saveSql} WHERE {$condSql}"; 
						$qr = $this->_query( $sql ); 
						if( !$qr ) 
							return NULL; 
						if( method_exists($this, 'onride') ) 
							$this->onride(); 
						return $data; 
					}
					else 
					{
						$fields = array();
						$values = array(); 
						if(method_exists($this, 'boot')) 
							$this->_eventBoot = $this->boot();
						foreach ($this->_propsDescribe as $field ) 
							if( array_key_exists($field, $data) ) 
							{
								$values[] = $this->escape_string( $data[$field] );
								$fields[] = "`".$field."`";
							}
							else if( is_array($this->_eventBoot) && array_key_exists($field, $this->_eventBoot) ) 
							{
								$values[] = $this->escape_string( $this->_eventBoot[$field] ); 
								$fields[] = "`".$field."`";
								$data[$field] = $this->_eventBoot[$field]; 
							}
						$fields = implode( comma, $fields );
						$values = implode( "','", $values );
						$sql = "INSERT INTO `{$this->_propTable}` ({$fields}) VALUES ('{$values}')"; 
						if( $this->_query($sql) ) 
						{
							$data[$this->_primaryKey] = $this->insert_id(); 
							if( method_exists($this, 'onboot') ) 
								$this->onboot(); 
							return $data;
						}
						return NULL; 
					} 
				} 
				else 
				{
					check($data);
				}
			} 
			else 
			{
				$data = array();
				foreach( $this->_propsDescribe as $field ) 
					$data[$field] = $this->{$field}; 
				return call_user_func_array([$this, mcbm_save], array(array($data), 1)); 
			}
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
	}
	
	private function _totalPages( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
			{
				if( $this->_querySQL && $this->_propLimit ) 
				{
					$limit = $this->_propLimit; 
					$count = $this->_total(); 
					return (int) ceil( $count/$limit ); 
				} 
				return 0; 
			} 
			else 
				throw new Exception( "Usage <strong>Model::totalPages()</strong> is unvalidable." ); 
		}
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
	} 
	
	private function _count( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				return $this->_select('count')->_search();
			else 
				throw new Exception( "Usage <strong>Model::count()</strong> is incorrect." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
	} 
	
	private function _sum( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
			{
				$f = current($args); 
				$selectSql = "SELECT SUM(`{$f}`) AS `SUM` "; 
				$fromSql = $this->_buildSqlFrom(); 
				$condSql = $this->_buildSqlCondition(); 
				$sql = $selectSql . $fromSql . $condSql; 
				$qr = $this->_query( $sql ); 
				$data = $this->fetch_assoc($qr); 
				return (int)$data['SUM']; 
			} 
			else 
				throw new Exception( "Usage <strong>Model::sum()</strong> is incorrect." ); 
		}
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
	} 
	
	private function _avg( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
			{ 
				$f = current($args); 
				$selectSql = "SELECT AVG(`{$f}`) AS `AVG` "; 
				$fromSql = $this->_buildSqlFrom(); 
				$condSql = $this->_buildSqlCondition(); 
				$sql = $selectSql . $fromSql . $condSql; 
				$qr = $this->_query( $sql ); 
				$data = $this->fetch_assoc($qr); 
				return (int)$data['AVG']; 
			} 
			else 
				throw new Exception( "Usage <strong>Model::avg()</strong> is incorrect." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
	} 
	
	private function _max( $args, $argsNum ) 
	{
		try 
		{ 
			if( $argsNum ) 
			{
				$f = current($args); 
				$selectSql = "SELECT MAX(`{$f}`) AS `MAX` "; 
				$fromSql = $this->_buildSqlFrom(); 
				$condSql = $this->_buildSqlCondition(); 
				$sql = $selectSql . $fromSql . $condSql; 
				$qr = $this->_query( $sql ); 
				$data = $this->fetch_assoc($qr); 
				return (int)$data['MAX']; 
			} 
			else 
				throw new Exception( "Usage <strong>Model::max()</strong> is incorrect." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
	} 
	
	private function _min( $args, $argsNum ) 
	{ 
		try 
		{ 
			if( $argsNum ) 
			{ 
				$f = current($args); 
				$selectSql = "SELECT MIN(`{$f}`) AS `MIN` "; 
				$fromSql = $this->_buildSqlFrom(); 
				$condSql = $this->_buildSqlCondition(); 
				$sql = $selectSql . $fromSql . $condSql; 
				$qr = $this->_query( $sql ); 
				$data = $this->fetch_assoc($qr); 
				return (int)$data['MIN']; 
			} 
			else 
				throw new Exception( "Usage <strong>Model::min()</strong> is incorrect." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
	} 
	
	private function _implode( $args, $argsNum ) 
	{ 
		try 
		{ 
			if( $argsNum ) 
			{
				return NULL;
			} 
			else 
				throw new Exception( "Usage <strong>Model::implode()</strong> is incorrect." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		} 
	} 
	
	private function _length( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
			{
				return $this->_total(); 
			}
			else 
				throw new Exception( "Usage <strong>Model::length()</strong> is incorrect." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
	} 
	
	private function _dbList( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
			{
				global $configs;
				$result = $this->query( 'SELECT table_name as `table_name` FROM information_schema.tables where table_schema="' . $configs['DATASOURCE']['DATABASE'] . '"' );
				return $result;
			}
			else 
				throw new Exception( "Usage <strong>Model::dbList()</strong> is incorrect." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
	}
	
	private function _row( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
			{ 
				$row = current( $args ); 
				if( is_array($row) && isset( $row[$this->_propModel] ) ) 
					return $row[$this->_propModel]; 
				else 
					return NULL; 
			} 
			else 
				throw new Exception( "Usage <strong>Model::raw()</strong> is incorrect." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
	}
	
	private function _setData( $args, $argsNum, $method="_setData" ) 
	{
		try 
		{ 
			$twoArg = 2; 
			$oneArg = 1; 
			$dispatcher = $this; 
			if( $argsNum ) 
				if( $oneArg===$argsNum ) 
				{
					$data = current($args); 
					if( is_array($data) ) 
						foreach( $data as $key => $value ) 
							if( is_string($key) && in_array($key, $this->_propsDescribe) )
								$this->{$key} = $value;
				}
				else 
				{
					$field = $args[0]; 
					$value = $args[1]; 
					$dispatcher->{$field} = $value; 
				}
			else 
				throw new Exception( "Usage <strong>Model::$method</strong> is incorrect." ); 
		} 
		catch( Exception $e ) 
		{ 
			abort( 400, $e->getMessage() );
		}
		return $this;
	} 

	protected function _setAliasName( $value ) 
	{
		if( _useDB() ) 
			$this->_propAlias = $value;
		return $this;
	}
	
	protected function _setModelName( $value ) 
	{
		if( _useDB() ) 
			$this->_propModel = $value; 
		return $this;
	}
	
	protected function _setTableName( $value ) 
	{
		if( _useDB() ) 
			$this->_propTable = $this->_propPrefix.$value; 
		return $this;
	}

	private function _boundField( $fieldName, $fieldLabel=NULL ) 
	{
		if( in_array($fieldName, $this->_propsDescribe) ) 
		{
			$describe = $this->_propsUndescribe; 
			$field = [ $fieldName => array( 
				'name'	=> $fieldName, 
				'label'	=> $fieldLabel, 
			)]; 
			$this->_propsUndescribe = array_merge($describe, $field); 
		}
		return $this; 
	} 
	
	private function _unboundField( $fieldName ) 
	{
		if( isset($this->_propsUndescribe[$fieldName]) ) 
			unset($this->_propsUndescribe[$fieldName]);
	} 
	
	private function _unsecureField( $fieldName ) 
	{
		if( !array_key_exists($fieldName, $this->_propsUndescribe) ) 
			return $this->_boundField( $fieldName ); 
	}
	
	private function _bound( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
			{
				$oneArg = 1; 
				$zeroArg = 0;
				$dispatcher = $this;
				if( $argsNum===$oneArg ) 
				{
					$data = current($args); 
					if( is_string($data) ) 
					{
						$this->_propsUndescribe = array();
						$this->_boundField( $data ); 
					}
					else if(count($data)===$oneArg) 
					{
						$this->_propsUndescribe = array();
						$this->_boundField( key($data), current($data) ); 
					} 
					else 
					{
						$args[$oneArg] = count($args[$zeroArg]); 
						call_user_func_array( array($dispatcher, '_bound'), $args ); 
					}
				}
				else 
				{
					$this->_propsUndescribe = array();
					foreach( $args as $fieldKey => $fieldValue ) 
						if( is_numeric($fieldKey) ) 
						{
							if( is_array($fieldValue) ) 
								$this->_boundField(key($fieldValue), current($fieldValue));
							else 
								$this->_boundField( $fieldValue ); 
						}
						else if( is_string($fieldKey) ) 
							$this->_boundField( $fieldKey, $fieldValue ); 
				}
			} 
			else 
				throw new Exception( "Using <strong>Model::bound()</strong> has a syntax error." );
		} 
		catch( Exception $e ) 
		{ 
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 

	private function _unbound( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
			{
				$oneArg = 1; 
				$zeroArg = 0;
				$dispatcher = $this;
				if( $argsNum===$oneArg ) 
					if( is_array($args[$zeroArg]) )
					{
						$args[$oneArg] = count($args[$zeroArg]); 
						call_user_func_array( array($dispatcher, '_unbound'), $args );
					} 
					else 
						$this->_unboundField( $args[$zeroArg] ); 
				else 
					foreach( $args as $fieldKey => $fieldValue ) 
						if( is_numeric($fieldKey) ) 
							$this->_unboundField( $fieldValue ); 
			} 
			else 
				throw new Exception( "Using <strong>Model::bound()</strong> has a syntax error." );
		} 
		catch( Exception $e ) 
		{ 
			abort( 400, $e->getMessage() ); 
		} 
		return $this;
	} 
	
	private function _unsecure( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
			{
				$oneArg = 1; 
				$zeroArg = 0;
				$dispatcher = $this;
				if( $argsNum===$oneArg ) 
					if( is_array($args[$zeroArg]) )
					{
						$args[$oneArg] = count($args[$zeroArg]); 
						call_user_func_array( array($dispatcher, '_unsecure'), $args );
					} 
					else 
						$this->_unsecureField( $args[$zeroArg] ); 
				else 
					foreach( $args as $fieldKey => $fieldValue ) 
						if( is_numeric($fieldKey) ) 
							$this->_unsecureField( $fieldValue ); 
			}
			else 
				throw new Exception( "Using <strong>Model::Unsecure()</strong> has a syntax error." ); 
		}
		catch( Exception $e ) 
		{ 
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 

	private function _between( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_between_operator'), array($args, $argsNum, 'between') ); 
			else 
				throw new Exception( "Using <strong>Model::between</strong> has a syntax error." ); 
		}
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
		return $this;
	} 

	private function _equal( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_operator'), array($args, $argsNum, '=', '_equal') ); 
			else 
				throw new Exception( "Using <strong>Model::equal()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{ 
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 

	private function _greaterThan( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_operator'), array($args, $argsNum, '>', '_greaterThan'));
			else 
				throw new Exception( "Using <strong>Model::greaterThan()</strong> has a syntax error." );
		}
		catch( Exception $e ) 
		{ 
			abort( 400, $e->getMessage() );
		}
		return $this; 
	} 

	private function _greaterThanOrEqual( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_operator'), array($args, $argsNum, '>=', '_greaterThanOrEqual') );
			else 
				throw new Exception( "Using <strong>Model::greaterThanOrEqual()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this; 
	} 

	private function _in( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_in_operator'), array($args, $argsNum, 'in', '_in') );
			else 
				throw new Exception( "Using <strong>Model::in()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		}
		return $this;
	}

	private function _is( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_operator'), array($args, $argsNum, 'is', '_is') );
			else 
				throw new Exception( "Using <strong>Model::is()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;  
	} 

	private function _isNot( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_operator'), array($args, $argsNum, 'is not', '_isNot') ); 
			else 
				throw new Exception( "Using <strong>Model::isNot()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this; 
	} 

	private function _isNotNull( $args, $argsNum ) 
	{
		try 
		{ 
			if( $argsNum ) 
				call_user_func_array( array($this, '_null_operator'), array($args, $argsNum, 'is not', '_isNotNull') ); 
			else 
				throw new Exception( "Using <strong>Model::isNotNull()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
		return $this;
	} 

	private function _isNull( $args, $argsNum ) 
	{
		try 
		{ 
			if( $argsNum )
				call_user_func_array( array($this, '_null_operator'), array($args, $argsNum, 'is', '_isNull') ); 
			else 
				throw new Exception( "Using <strong>Model::isNull()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
		return $this;
	} 

	private function _lessThan( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_operator') , array($args, $argsNum, '<', '_lessThan') );
			else 
				throw new Exception( "Using <strong>Model::lessThan()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this; 
	} 

	private function _lessThanOrEqual( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_operator'), array($args, $argsNum, '<=', '_lessThanOrEqual') );
			else 
				throw new Exception( "Using <strong>Model::lessThanOrEqual()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 

	private function _like( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_operator') , array($args, $argsNum, 'like', '_like') );
			else 
				throw new Exception( "Using <strong>Model::like()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	}

	private function _not( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_operator'), array($args, $argsNum, '!=', '_not') );
			else 
				throw new Exception( "Using <strong>Model::not()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _notBetween( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_between_operator'), array($args, $argsNum, 'not between') );
			else 
				throw new Exception( "Using <strong>Model::notBetween()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 

	private function _notEqual( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_operator'), array($args, $argsNum, '!=', '_notEqual') );
			else 
				throw new Exception( "Using <strong>Model::notEqual()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	}

	private function _notIn( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_in_operator'), array($args, $argsNum, 'not in', '_notIn') );
			else 
				throw new Exception( "Using <strong>Model::notIn()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		}
		return $this;
	} 

	private function _notLike( $args, $argsNum, $type=NULL ) 
	{
		try 
		{
			if( $argsNum ) 
			{
				call_user_func_array( array($this, '_where_operator'), array($args, $argsNum, 'not like', '_notLike') );
			}
			else 
				throw new Exception( "Using <strong>Model::notLike()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	}

	private function _notNull( $args, $argsNum ) 
	{
		try 
		{ 
			if( $argsNum ) 
				call_user_func_array( array($this, '_null_operator'), array($args, $argsNum, 'is not', '_notNull') ); 
			else 
				throw new Exception( "Using <strong>Model::notNull()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
		return $this;
	} 
	
	private function _between_operator( $args, $argsNum, $sign ) 
	{
		$twoArg = 2;
		$oneArg = 1; 
		$dispatcher = $this;
		if( $argsNum===$twoArg ) 
		{
			$tmp = $args[1]; 
			$args[1] = $sign; 
			$args[2] = $tmp;
			call_user_func_array(array($dispatcher, '_where'), array($args, count($args)));
		}
		else if($argsNum===$oneArg) 
		{
			$params = current($args); 
			foreach( $params as $param ) 
			{
				$tmp = array(key($param)); 
				$tmp[] = $sign; 
				$tmp[] = current($param);
				call_user_func_array(array($dispatcher, '_where'), array($tmp, count($tmp)));
			}
		}
	} 
	
	private function _in_operator( $args, $argsNum, $sign, $method ) 
	{
		$twoArg = 2; 
		$oneArg = 1; 
		$dispatcher = $this; 
		if( $twoArg===$argsNum ) 
		{
			$argsNum = 3;
			$tmp = $args[1];
			$args[1] = $sign;
			$args[2] = $tmp; 
			call_user_func_array( array($dispatcher, '_where'), array($args, $argsNum) ); 
		} 
		else if( $argsNum>$twoArg ) 
		{
			$tmp = array();
			$tmp[] = array_shift($args); 
			$tmp[] = $sign;
			$tmp[] = $args;
			call_user_func_array( array($dispatcher, '_where'), array($tmp, 3) ); 
		}
		else 
		{
			$params = current($args); 
			if( is_array($params) ) 
				foreach( $params as $args ) 
					call_user_func_array( array($dispatcher, $method), array([key($args), current($args)], $twoArg) ); 
		}
	}
	
	private function _null_operator( $args, $argsNum, $sign, $method ) 
	{
		$twoArg = 2; 
		$oneArg = 1; 
		$dispatcher = $this; 
		if( $oneArg===$argsNum ) 
		{
			if( is_string($args[0]) ) 
			{
				$args[] = $sign; 
				$args[] = NULL;
				call_user_func_array( array($dispatcher, '_where'), array( $args, 3 ) );
			}
			else 
			{
				$args = current($args);
				foreach( $args as $arg ) 
					call_user_func_array( array($dispatcher, $method), array((array)$arg, 1) );
			}
		} 
		else if( $twoArg<= $argsNum ) 
			call_user_func_array( array($dispatcher, $method), array(array ($args), $oneArg) );
	}
	
	private function _where_operator( $args, $argsNum, $sign, $method ) 
	{
		$twoArg = 2; 
		$oneArg = 1; 
		$dispatcher = $this;
		if( $oneArg===$argsNum ) 
		{
			$params = current($args); 
			if( is_array($params) ) 
			{
				$tmp = array();
				foreach( $params as $key => $value ) 
					if(is_string($key)) 
						call_user_func_array( array($dispatcher, $method), array([$key, $value], $twoArg) ); 
					else if( is_numeric($key) ) 
						$tmp[] = $value;
				if( !empty($tmp) ) 
					call_user_func_array( array($dispatcher, $method), array($tmp, count($tmp)) ); 
			}
		}
		else if( $twoArg<=$argsNum ) 
		{
			if( is_string($args[0]) ) 
			{
				$argsNum = 3;
				$tmp = $args[1];
				$args[1] = $sign;
				$args[2] = $tmp; 
				call_user_func_array( array($dispatcher, '_where'), array($args, $argsNum) ); 
			} 
			else 
			{
				foreach( $args as $arg ) 
				{
					$argsNum = count($arg);
					$tmp = array(); 
					if( $oneArg===$argsNum ) 
					{
						$tmp[] = key($arg); 
						$tmp[] = $sign;
						$tmp[] = current($arg);
					} 
					else 
					{
						$tmp[] = $arg[0]; 
						$tmp[] = $sign;
						$tmp[] = $arg[1];
					}
					$arg = $tmp;
					call_user_func_array( array($dispatcher, '_where'), array($arg, count($arg)) ); 
				}
			}
		}
	}

	private function _where( $args, $argsNum, $embed=false ) 
	{
		try 
		{
			if( $argsNum )
			{
				$threeArg = 3;
				$twoArg = 2;
				$oneArg = 1; 
				$dispatcher = $this; 
				if( $argsNum===$twoArg ) 
				{ 
					if( 'is not null' === strtolower($args[1]) ) 
						call_user_func_array(array($dispatcher, '_where'), array([$args[0], 'is not', NULL], 3));
					if( 'not null' === strtolower($args[1]) )
						call_user_func_array(array($dispatcher, '_where'), array([$args[0], 'not', NULL], 3));
					else
						call_user_func_array(array($dispatcher, '_where'), array([$args[0], '=', $args[1]], 3));
				} 
				else if( $threeArg===$argsNum ) 
				{
					$allowOps = array(
						'between'				=>'BETWEEN', 
						'equal' 				=>'=',
						'==='					=>'=', 
						'=='					=>'=', 
						'='						=>'=', 
						'greater than'			=>'>',
						'>'						=>'>', 
						'greater than or equal'	=>'>=',
						'>='					=>'>=', 
						'in'					=>'IN', 
						'is'					=>'IS', 
						'is not'				=>'IS NOT', 
						'less than' 			=>'<',
						'<' 					=>'<', 
						'less than or equal' 	=>'<=',
						'<='					=>'<=', 
						'like'					=>'LIKE', 
						'not'					=>'NOT', 
						'not between'			=>'NOT BETWEEN',
						'not equal'				=>'!=',
						'!='					=>'!=', 
						'not in'				=>'NOT IN', 
						'not like'				=>'NOT LIKE', 
					);
					if( in_array($args[0], $this->_propsDescribe) && array_key_exists($args[1], $allowOps) ) 
					{
						$args[1] = $allowOps[strtolower($args[1])]; 
						if( $embed ) 
							return $args; 
						$this->_propsCond[] = $args;
					} 
					else 
					{
						if( !in_array($args[0], $this->_propsDescribe)) 
							throw new Exception( 'Cột <strong>'.$args[0].'</strong> không có trong bảng <strong>'.$this->_propTable.'</strong>.' ); 
						if( !array_key_exists($args[1], $allowOps) )
							throw new Exception( 'Không chấp nhận toán tử <strong>'.$args[1].'</strong>.' );
					}
				} 
				else if( $argsNum>$threeArg ) 
				{
					$tmp = array();
					$tmp[] = array_shift($args);
					$tmp[] = array_shift($args);
					$tmp[] = $args;
					call_user_func_array(array($dispatcher, '_where'), array(array( $name, $ops, $args ), 3));
				} 
				else 
				{
					$params = current($args); 
					foreach( $params as $args ) 
					{
						if( $oneArg===count($args) ) 
							continue; 
						call_user_func_array(array($dispatcher, '_where'), array($args, count($args)));
					}
				}
			}
		}
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		}
		return $this;
	} 
	
	private function _betweenOr( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_between_or_operator'), array($args, $argsNum, 'between') );
			else 
				throw new Exception( "Using <strong>Model::betweenOr()</strong> has a syntax error." ); 
		}
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
	} 
	
	private function _equalOr( $args, $argsNum ) 
	{ 
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_or_operator'), array($args, $argsNum, '=') );
			else 
				throw new Exception( "Using <strong>Model::equalOr()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _greaterThanOr( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_or_operator'), array($args, $argsNum, '>') );
			else 
				throw new Exception( "Using <strong>Model::greaterThanOr()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _greaterThanOrEqualOr( $args, $argsNum ) 
	{ 
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_or_operator'), array($args, $argsNum, '>=') );
			else 
				throw new Exception( "Using <strong>Model::greaterThanOrEqualOr()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _inOr( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_or_operator'), array($args, $argsNum, 'in') );
			else 
				throw new Exception( "Using <strong>Model::inOr()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _isOr( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_or_operator'), array($args, $argsNum, 'is') );
			else 
				throw new Exception( "Using <strong>Model::isOr()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	private function _isNotOr( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_or_operator'), array($args, $argsNum, 'is not') );
			else 
				throw new Exception( "Using <strong>Model::isNotOr()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _isNotNullOr( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_null_or_operator'), array($args, $argsNum, 'is not') );
			else 
				throw new Exception( "Using <strong>Model::isNotNullOr()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _isNullOr( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_null_or_operator'), array($args, $argsNum, 'is') );
			else 
				throw new Exception( "Using <strong>Model::isNullOr()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _lessOr( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_or_operator'), array($args, $argsNum, '<') );
			else 
				throw new Exception( "Using <strong>Model::lessOr()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _lessThanOr( $args, $argsNum ) 
	{ 
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_or_operator'), array($args, $argsNum, '<') );
			else 
				throw new Exception( "Using <strong>Model::lessThanOr()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _lessThanOrEqualOr( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_or_operator'), array($args, $argsNum, '<=') );
			else 
				throw new Exception( "Using <strong>Model::lessThanOrEqualOr()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _likeOr( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_or_operator'), array($args, $argsNum, 'like') );
			else 
				throw new Exception( "Using <strong>Model::likeOr()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _notOr( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_or_operator'), array($args, $argsNum, 'not') );
			else 
				throw new Exception( "Using <strong>Model::notOr()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _notBetweenOr( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_between_or_operator'), array($args, $argsNum, 'not between') );
			else 
				throw new Exception( "Using <strong>Model::notBetweenOr()</strong> has a syntax error." ); 
		}
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
	} 
	
	private function _notEqualOr( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_or_operator'), array($args, $argsNum, '!=') );
			else 
				throw new Exception( "Using <strong>Model::notEqualOr()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	}
	
	private function _notInOr( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_or_operator'), array($args, $argsNum, 'not in') );
			else 
				throw new Exception( "Using <strong>Model::notInOr()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	}
	
	private function _notLikeOr( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_or_operator'), array($args, $argsNum, 'not like') );
			else 
				throw new Exception( "Using <strong>Model::notLikeOr()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _notNullOr( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_null_or_operator'), array($args, $argsNum, 'is not') );
			else 
				throw new Exception( "Using <strong>Model::notNullOr()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _between_or_operator( $args, $argsNum, $sign ) 
	{
		$oneArg = 1; 
		$dispatcher = $this; 
		if( $oneArg===$argsNum ) 
			$params = current($args); 
		else 
			$params = $args; 
		$args = array();
		foreach( $params as $arg ) 
		{
			$param = array(key($arg)); 
			$param[] = $sign; 
			$param[] = current($arg);
			$args [] = $param;
		}
		call_user_func_array( array($dispatcher, '_whereOr'), array($args, count($args)) );
	}
	
	private function _null_or_operator( $args, $argsNum, $sign ) 
	{
		$oneArg = 1; 
		$dispatcher = $this; 
		$oneArg = 1; 
		$dispatcher = $this; 
		$tmps = array(); 
		if( $oneArg<$argsNum ) 
			foreach( $args as $arg ) 
				$tmps[] = array($arg, $sign, NULL); 
		else 
		{
			$args = current($args);
			foreach( $args as $key ) 
			{
				$tmps[] = array($key, $sign, NULL); 
			}
		}
		call_user_func_array( array($dispatcher, '_whereOr'), array($tmps, count($tmps)) ); 
	}
	
	private function _where_or_operator( $args, $argsNum, $sign ) 
	{
		$oneArg = 1; 
		$dispatcher = $this; 
		$tmps = array(); 
		if( $oneArg<$argsNum ) 
			foreach( $args as $arg )
				$tmps[] = array(key($arg), $sign, current($arg)); 
		else 
		{
			$args = current($args);
			foreach( $args as $key => $value ) 
				$tmps[] = array($key, $sign, $value); 
		}
		call_user_func_array( array($dispatcher, '_whereOr'), array($tmps, count($tmps)) ); 
	}
	
	private function _whereOr( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
			{
				$threeArg = 3; 
				$twoArg = 2; 
				$oneArg = 1; 
				$dispatcher = $this; 
				if( $oneArg===$argsNum ) 
				{
					$params = current($args); 
					if( is_array($params) ) 
					{
						$argsNum = count($params);
						if( $oneArg>=$argsNum ) 
							throw new Exception( "Using <strong>Model::whereOr()</strong> has a syntax error." ); 
					} 
					else 
						throw new Exception( "Using <strong>Model::whereOr()</strong> has a syntax error." ); 
				} 
				else 
					$params = $args; 
				
				$tmps = array(); 
				foreach( $params as $key => $arg ) 
				{
					if( is_string($key) ) 
					{
						$tmp = $arg; 
						$arg = array(
							$key, 
							'=', 
							$tmp 
						);
					}
					else if( $twoArg===count($arg) ) 
					{
						$tmp = $arg[1]; 
						$arg[1] = '='; 
						$arg[2] = $tmp; 
					} 
					else if( $oneArg===count($arg) )
					{
						$tmp = $arg; 
						$arg = array(
							key($tmp), 
							'=', 
							current($tmp) 
						);
					}
					$args = array($arg);
					$args[] = count($arg); 
					$args[] = true;
					$tmps[] = call_user_func_array( array($dispatcher, '_where'), $args ); 
				}
				if( !empty($tmps) )
					$this->_propsCond[] = array('OR'=>$tmps); 
			} 
			else 
				throw new Exception( "Using <strong>Model::whereOr()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{ 
			abort( 400, $e->getMessage() );
		} 
	} 
	
	private function _betweenOn( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_between_on_operator'), array($args, $argsNum, 'between') );
			else 
				throw new Exception( "Using <strong>Model::betweenOn()</strong> has a syntax error." ); 
		}
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
	} 
	
	private function _equalOn( $args, $argsNum ) 
	{ 
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_on_operator'), array($args, $argsNum, '=', '_equalOn') );
			else 
				throw new Exception( "Using <strong>Model::equalOn()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _greaterThanOn( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_on_operator'), array($args, $argsNum, '>', '_greaterThanOn') );
			else 
				throw new Exception( "Using <strong>Model::greaterThanOn()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _greaterThanOrEqualOn( $args, $argsNum ) 
	{ 
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_on_operator'), array($args, $argsNum, '>=', '_greaterThanOrEqualOn') );
			else 
				throw new Exception( "Using <strong>Model::greaterThanOrEqualOn()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _inOn( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_on_operator'), array($args, $argsNum, 'in', '_inOn') );
			else 
				throw new Exception( "Using <strong>Model::inOn()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _isOn( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_on_operator'), array($args, $argsNum, 'is', '_isOn') );
			else 
				throw new Exception( "Using <strong>Model::isOn()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _isNotOn( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_on_operator'), array($args, $argsNum, 'is not', '_isNotOn') );
			else 
				throw new Exception( "Using <strong>Model::isNotOn()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _isNotNullOn( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_null_on_operator'), array($args, $argsNum, 'is not', '_isNotNullOn') );
			else 
				throw new Exception( "Using <strong>Model::isNotNullOn()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _isNullOn( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_null_on_operator'), array($args, $argsNum, 'is', '_isNullOn') );
			else 
				throw new Exception( "Using <strong>Model::isNullOn()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _lessOn( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_on_operator'), array($args, $argsNum, '<', '_lessOn') );
			else 
				throw new Exception( "Using <strong>Model::lessOn()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _lessThanOn( $args, $argsNum ) 
	{ 
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_on_operator'), array($args, $argsNum, '<', '_lessThanOn') );
			else 
				throw new Exception( "Using <strong>Model::lessThanOn()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _lessThanOrEqualOn( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_on_operator'), array($args, $argsNum, '<=', '_lessThanOrEqualOn') );
			else 
				throw new Exception( "Using <strong>Model::lessThanOrEqualOn()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _likeOn( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_on_operator'), array($args, $argsNum, 'like', '_likeOn') );
			else 
				throw new Exception( "Using <strong>Model::likeOn()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _notOn( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_on_operator'), array($args, $argsNum, 'not', '_notOn') );
			else 
				throw new Exception( "Using <strong>Model::notOn()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _notBetweenOn( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_between_on_operator'), array($args, $argsNum, 'not between') );
			else 
				throw new Exception( "Using <strong>Model::notBetweenOn()</strong> has a syntax error." ); 
		}
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
	} 
	
	private function _notEqualOn( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_on_operator'), array($args, $argsNum, '!=', '_notEqualOn') );
			else 
				throw new Exception( "Using <strong>Model::notEqualOn()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	}
	
	private function _notInOn( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_on_operator'), array($args, $argsNum, 'not in', '_notInOn') );
			else 
				throw new Exception( "Using <strong>Model::notInOn()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	}
	
	private function _notLikeOn( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_where_on_operator'), array($args, $argsNum, 'not like', '_notLikeOn') );
			else 
				throw new Exception( "Using <strong>Model::notLikeOn()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _notNullOn( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_null_on_operator'), array($args, $argsNum, 'is not', '_notNullOn') );
			else 
				throw new Exception( "Using <strong>Model::notNullOn()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _between_on_operator( $args, $argsNum, $sign ) 
	{
		$twoArg = 2;
		$oneArg = 1; 
		$dispatcher = $this;
		if( $argsNum===$twoArg ) 
		{
			$tmp = $args[1]; 
			$args[1] = $sign; 
			$args[2] = $tmp;
			call_user_func_array(array($dispatcher, '_whereOn'), array($args, count($args)));
		}
		else if($argsNum===$oneArg) 
		{
			$params = current($args); 
			foreach( $params as $param ) 
			{
				$tmp = array(key($param)); 
				$tmp[] = $sign; 
				$tmp[] = current($param);
				call_user_func_array(array($dispatcher, '_whereOn'), array($tmp, count($tmp)));
			}
		}
	}
	
	private function _in_on_operator( $args, $argsNum, $sign, $method ) 
	{
		$twoArg = 2; 
		$oneArg = 1; 
		$dispatcher = $this; 
		if( $twoArg===$argsNum ) 
		{
			$argsNum = 3;
			$tmp = $args[1];
			$args[1] = $sign;
			$args[2] = $tmp; 
			call_user_func_array( array($dispatcher, '_whereOn'), array($args, $argsNum) ); 
		} 
		else if( $argsNum>$twoArg ) 
		{
			$tmp = array();
			$tmp[] = array_shift($args); 
			$tmp[] = $sign;
			$tmp[] = $args;
			call_user_func_array( array($dispatcher, '_whereOn'), array($tmp, 3) ); 
		}
		else 
		{
			$params = current($args); 
			if( is_array($params) ) 
				foreach( $params as $args ) 
					call_user_func_array( array($dispatcher, $method), array([key($args), current($args)], $twoArg) ); 
		}
	}
	
	private function _null_on_operator( $args, $argsNum, $sign, $method ) 
	{
		$twoArg = 2; 
		$oneArg = 1; 
		$dispatcher = $this; 
		if( $oneArg===$argsNum ) 
		{
			if( is_string($args[0]) ) 
			{
				$args[] = $sign; 
				$args[] = NULL;
				call_user_func_array( array($dispatcher, '_whereOn'), array( $args, 3 ) );
			}
			else 
			{
				$args = current($args);
				foreach( $args as $arg ) 
					call_user_func_array( array($dispatcher, $method), array((array)$arg, 1) );
			}
		} 
		else if( $twoArg<= $argsNum ) 
			call_user_func_array( array($dispatcher, $method), array(array ($args), $oneArg) );
	}
	
	private function _where_on_operator( $args, $argsNum, $sign, $method ) 
	{
		$twoArg = 2; 
		$oneArg = 1; 
		$dispatcher = $this;
		if( $oneArg===$argsNum ) 
		{
			$params = current($args); 
			if( is_array($params) ) 
			{
				$tmp = array();
				foreach( $params as $key => $value ) 
					if(is_string($key)) 
						call_user_func_array( array($dispatcher, $method), array([$key, $value], $twoArg) ); 
					else if( is_numeric($key) ) 
						$tmp[] = $value;
				if( !empty($tmp) ) 
					call_user_func_array( array($dispatcher, $method), array($tmp, count($tmp)) ); 
			}
		}
		else if( $twoArg<=$argsNum ) 
		{
			if( is_string($args[0]) ) 
			{
				$argsNum = 3;
				$tmp = $args[1];
				$args[1] = $sign;
				$args[2] = $tmp; 
				call_user_func_array( array($dispatcher, '_whereOn'), array($args, $argsNum) ); 
			} 
			else 
			{
				foreach( $args as $arg ) 
				{
					$argsNum = count($arg);
					$tmp = array(); 
					if( $oneArg===$argsNum ) 
					{
						$tmp[] = key($arg); 
						$tmp[] = $sign;
						$tmp[] = current($arg);
					} 
					else 
					{
						$tmp[] = $arg[0]; 
						$tmp[] = $sign;
						$tmp[] = $arg[1];
					}
					$arg = $tmp;
					call_user_func_array( array($dispatcher, '_whereOn'), array($arg, count($arg)) ); 
				}
			}
		}
	}
	
	private function _whereOn( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
			{
				$threeArg = 3;
				$twoArg = 2; 
				$oneArg = 1; 
				$dispatcher = $this; 
				$params = array();
				if( $twoArg===$argsNum ) 
				{
					$tmp = $args[1]; 
					$args[1] = '='; 
					$args[2] = $tmp;
					$params[] = call_user_func_array( array($dispatcher, '_where'), array($args, $threeArg, true) ); 
				} 
				else if( $threeArg===$argsNum ) 
				{
					$params[] = call_user_func_array( array($dispatcher, '_where'), array($args, $threeArg, true) );
				} 
				else if( $threeArg<$argsNum ) 
				{
					$tmp = $args;
					$args = array(array_shift($tmp)); 
					$args[] = array_shift($tmp); 
					$args[] = $tmp; 
					$params[] = call_user_func_array( array($dispatcher, '_where'), array($args, $threeArg, true) );
				} 
				else 
				{
					$args = current($args);
					foreach($args as $arg) 
						call_user_func_array( array($dispatcher, '_whereOn'), array($arg, count($arg)) ); 
				}
				if( count($params) ) 
					foreach($params as $args) 
						$this->_propsCondOn[] = $args; 
			} 
			else 
				throw new Exception( "Using <strong>Model::whereOn()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{ 
			abort( 400, $e->getMessage() );
		} 
	} 
	
	private function _orBetween( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_between_operator'), array($args, $argsNum, 'between') );
			else 
				throw new Exception( "Using <strong>Model::orBetween()</strong> has a syntax error." ); 
		}
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
	} 
	
	private function _orEqual( $args, $argsNum ) 
	{ 
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_operator'), array($args, $argsNum, '=', '_orEqual') );
			else 
				throw new Exception( "Using <strong>Model::orEqual()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orGreater( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_operator'), array($args, $argsNum, '>', '_orGreater') );
			else 
				throw new Exception( "Using <strong>Model::orGreater()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orGreaterThan( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_operator'), array($args, $argsNum, '>', '_orGreaterThan') );
			else 
				throw new Exception( "Using <strong>Model::orGreaterThan()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orGreaterThanOrEqual( $args, $argsNum ) 
	{ 
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_operator'), array($args, $argsNum, '>=', '_orGreaterThanOrEqual') );
			else 
				throw new Exception( "Using <strong>Model::orGreaterThanOrEqualOn()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orIn( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_operator'), array($args, $argsNum, 'in', '_orIn') );
			else 
				throw new Exception( "Using <strong>Model::orIn()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orIs( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_operator'), array($args, $argsNum, 'is', '_orIs') );
			else 
				throw new Exception( "Using <strong>Model::orIs()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orIsNot( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_operator'), array($args, $argsNum, 'is not', '_orIsNot') );
			else 
				throw new Exception( "Using <strong>Model::orIsNot()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orIsNotNull( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_null_operator'), array($args, $argsNum, 'is not', '_orIsNotNull') );
			else 
				throw new Exception( "Using <strong>Model::orIsNotNull()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orIsNull( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_null_operator'), array($args, $argsNum, 'is', '_orIsNull') );
			else 
				throw new Exception( "Using <strong>Model::orIsNull()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orLess( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_operator'), array($args, $argsNum, '<', '_orLess') );
			else 
				throw new Exception( "Using <strong>Model::orLess()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orLessThan( $args, $argsNum ) 
	{ 
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_operator'), array($args, $argsNum, '<', '_orLessThan') );
			else 
				throw new Exception( "Using <strong>Model::orLessThan()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orLessThanOrEqual( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_operator'), array($args, $argsNum, '<=', '_orLessThanOrEqual') );
			else 
				throw new Exception( "Using <strong>Model::orLessThanOrEqual()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orLike( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_operator'), array($args, $argsNum, 'like', '_orLike') );
			else 
				throw new Exception( "Using <strong>Model::orLike()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orNot( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_operator'), array($args, $argsNum, 'not', '_orNot') );
			else 
				throw new Exception( "Using <strong>Model::orNot()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orNotBetween( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_between_operator'), array($args, $argsNum, 'not between') );
			else 
				throw new Exception( "Using <strong>Model::orNotBetween()</strong> has a syntax error." ); 
		}
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
	} 
	
	private function _orNotEqual( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_operator'), array($args, $argsNum, '!=', '_orNotEqual') );
			else 
				throw new Exception( "Using <strong>Model::orNotEqual()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	}
	
	private function _orNotIn( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_operator'), array($args, $argsNum, 'not in', '_orNotIn') );
			else 
				throw new Exception( "Using <strong>Model::orNotIn()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	}
	
	private function _orNotLike( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_operator'), array($args, $argsNum, 'not like', '_orNotLike') );
			else 
				throw new Exception( "Using <strong>Model::orNotLike()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orNotNull( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_null_operator'), array($args, $argsNum, 'is not', '_orNotNull') );
			else 
				throw new Exception( "Using <strong>Model::orNotNull()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _or_between_operator( $args, $argsNum, $sign ) 
	{
		$twoArg = 2;
		$oneArg = 1; 
		$dispatcher = $this;
		if( $argsNum===$twoArg ) 
		{
			$tmp = $args[1]; 
			$args[1] = $sign; 
			$args[2] = $tmp;
			call_user_func_array(array($dispatcher, '_orWhere'), array($args, count($args)));
		}
		else if($argsNum===$oneArg) 
		{
			$params = current($args); 
			foreach( $params as $param ) 
			{
				$tmp = array(key($param)); 
				$tmp[] = $sign; 
				$tmp[] = current($param);
				call_user_func_array(array($dispatcher, '_orWhere'), array($tmp, count($tmp)));
			}
		}
	}
	
	private function _or_in_operator( $args, $argsNum, $sign, $method ) 
	{
		$twoArg = 2; 
		$oneArg = 1; 
		$dispatcher = $this; 
		if( $twoArg===$argsNum ) 
		{
			$argsNum = 3;
			$tmp = $args[1];
			$args[1] = $sign;
			$args[2] = $tmp; 
			call_user_func_array( array($dispatcher, '_orWhere'), array($args, $argsNum) ); 
		} 
		else if( $argsNum>$twoArg ) 
		{
			$tmp = array();
			$tmp[] = array_shift($args); 
			$tmp[] = $sign;
			$tmp[] = $args;
			call_user_func_array( array($dispatcher, '_orWhere'), array($tmp, 3) ); 
		}
		else 
		{
			$params = current($args); 
			if( is_array($params) ) 
				foreach( $params as $args ) 
					call_user_func_array( array($dispatcher, $method), array([key($args), current($args)], $twoArg) ); 
		}
	}
	
	private function _or_null_operator( $args, $argsNum, $sign, $method ) 
	{
		$twoArg = 2; 
		$oneArg = 1; 
		$dispatcher = $this; 
		if( $oneArg===$argsNum ) 
		{
			if( is_string($args[0]) ) 
			{
				$args[] = $sign; 
				$args[] = NULL;
				call_user_func_array( array($dispatcher, '_orWhere'), array( $args, 3 ) );
			}
			else 
			{
				$args = current($args);
				foreach( $args as $arg ) 
					call_user_func_array( array($dispatcher, $method), array((array)$arg, 1) );
			}
		} 
		else if( $twoArg<= $argsNum ) 
			call_user_func_array( array($dispatcher, $method), array(array ($args), $oneArg) );
	}
	
	private function _or_where_operator( $args, $argsNum, $sign, $method ) 
	{
		$twoArg = 2; 
		$oneArg = 1; 
		$dispatcher = $this;
		if( $oneArg===$argsNum ) 
		{
			$params = current($args); 
			if( is_array($params) ) 
			{
				$tmp = array();
				foreach( $params as $key => $value ) 
					if(is_string($key)) 
						call_user_func_array( array($dispatcher, $method), array([$key, $value], $twoArg) ); 
					else if( is_numeric($key) ) 
						$tmp[] = $value;
				if( !empty($tmp) ) 
					call_user_func_array( array($dispatcher, $method), array($tmp, count($tmp)) ); 
			}
		}
		else if( $twoArg<=$argsNum ) 
		{
			if( is_string($args[0]) ) 
			{
				$argsNum = 3;
				$tmp = $args[1];
				$args[1] = $sign;
				$args[2] = $tmp; 
				call_user_func_array( array($dispatcher, '_orWhere'), array($args, $argsNum) ); 
			} 
			else 
			{
				foreach( $args as $arg ) 
				{
					$argsNum = count($arg);
					$tmp = array(); 
					if( $oneArg===$argsNum ) 
					{
						$tmp[] = key($arg); 
						$tmp[] = $sign;
						$tmp[] = current($arg);
					} 
					else 
					{
						$tmp[] = $arg[0]; 
						$tmp[] = $sign;
						$tmp[] = $arg[1];
					}
					$arg = $tmp;
					call_user_func_array( array($dispatcher, '_orWhere'), array($arg, count($arg)) ); 
				}
			}
		}
	}
	
	private function _orWhere( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
			{
				$threeArg = 3;
				$twoArg = 2; 
				$oneArg = 1; 
				$dispatcher = $this; 
				$params = array();
				if( $twoArg===$argsNum ) 
				{
					$tmp = $args[1]; 
					$args[1] = '='; 
					$args[2] = $tmp;
					$params[] = call_user_func_array( array($dispatcher, '_where'), array($args, $threeArg, true) ); 
				} 
				else if( $threeArg===$argsNum ) 
				{
					$params[] = call_user_func_array( array($dispatcher, '_where'), array($args, $threeArg, true) );
				} 
				else if( $threeArg<$argsNum ) 
				{
					$tmp = $args;
					$args = array(array_shift($tmp)); 
					$args[] = array_shift($tmp); 
					$args[] = $tmp; 
					$params[] = call_user_func_array( array($dispatcher, '_where'), array($args, $threeArg, true) );
				} 
				else 
				{
					$args = current($args);
					foreach($args as $arg) 
						call_user_func_array( array($dispatcher, '_orWhere'), array($arg, count($arg)) ); 
				}
				if( count($params) ) 
					foreach($params as $args) 
						$this->_propsCondOr[] = $args; 
			} 
			else 
				throw new Exception( "Using <strong>Model::orWhere()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{ 
			abort( 400, $e->getMessage() );
		} 
	} 
	
	private function _orBetweenAnd( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_between_and_operator'), array($args, $argsNum, 'between') );
			else 
				throw new Exception( "Using <strong>Model::orBetweenAnd()</strong> has a syntax error." ); 
		}
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
	} 
	
	private function _orEqualAnd( $args, $argsNum ) 
	{ 
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_and_operator'), array($args, $argsNum, '=') );
			else 
				throw new Exception( "Using <strong>Model::orEqualAnd()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orGreaterAnd( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_and_operator'), array($args, $argsNum, '>') );
			else 
				throw new Exception( "Using <strong>Model::orGreaterAnd()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orGreaterThanOrEqualAnd( $args, $argsNum ) 
	{ 
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_and_operator'), array($args, $argsNum, '>=') );
			else 
				throw new Exception( "Using <strong>Model::orGreaterThanOrEqualAnd()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orInAnd( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_and_operator'), array($args, $argsNum, 'in') );
			else 
				throw new Exception( "Using <strong>Model::orInAnd()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orIsAnd( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_and_operator'), array($args, $argsNum, 'is') );
			else 
				throw new Exception( "Using <strong>Model::orIsAnd()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orIsNotAnd( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_and_operator'), array($args, $argsNum, 'is not') );
			else 
				throw new Exception( "Using <strong>Model::orIsNotAnd()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orIsNotNullAnd( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_null_and_operator'), array($args, $argsNum, 'is not') );
			else 
				throw new Exception( "Using <strong>Model::orIsNotNullAnd()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orIsNullAnd( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_null_and_operator'), array($args, $argsNum, 'is') );
			else 
				throw new Exception( "Using <strong>Model::orIsNullAnd()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orLessAnd( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_and_operator'), array($args, $argsNum, '<') );
			else 
				throw new Exception( "Using <strong>Model::orLessAnd()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orLessThanAnd( $args, $argsNum ) 
	{ 
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_and_operator'), array($args, $argsNum, '<') );
			else 
				throw new Exception( "Using <strong>Model::orLessThanAnd()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orLessThanOrEqualAnd( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_and_operator'), array($args, $argsNum, '<=') );
			else 
				throw new Exception( "Using <strong>Model::orLessThanOrEqualAnd()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orLikeAnd( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_and_operator'), array($args, $argsNum, 'like') );
			else 
				throw new Exception( "Using <strong>Model::orLikeAnd()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orNotAnd( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_and_operator'), array($args, $argsNum, 'not') );
			else 
				throw new Exception( "Using <strong>Model::orNotAnd()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orNotBetweenAnd( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_between_and_operator'), array($args, $argsNum, 'not between') );
			else 
				throw new Exception( "Using <strong>Model::orBetweenAnd()</strong> has a syntax error." ); 
		}
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
	} 
	
	private function _orNotNullAnd( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_null_and_operator'), array($args, $argsNum, 'is not') );
			else 
				throw new Exception( "Using <strong>Model::orNotNullAnd()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _orNotEqualAnd( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_and_operator'), array($args, $argsNum, '!=') );
			else 
				throw new Exception( "Using <strong>Model::orNotEqualAnd()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	}
	
	private function _orNotInAnd( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_and_operator'), array($args, $argsNum, 'not in') );
			else 
				throw new Exception( "Using <strong>Model::orNotInAnd()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	}
	
	private function _orNotLikeAnd( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
				call_user_func_array( array($this, '_or_where_and_operator'), array($args, $argsNum, 'not like') );
			else 
				throw new Exception( "Using <strong>Model::orNotLikeAnd()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	}
	
	private function _or_between_and_operator( $args, $argsNum, $sign ) 
	{
		$oneArg = 1; 
		$dispatcher = $this; 
		if( $oneArg===$argsNum ) 
			$params = current($args); 
		else 
			$params = $args; 
		$args = array();
		foreach( $params as $arg ) 
		{
			$param = array(key($arg)); 
			$param[] = $sign; 
			$param[] = current($arg);
			$args [] = $param;
		}
		call_user_func_array( array($dispatcher, '_orWhereAnd'), array($args, count($args)) );
	}
	
	private function _or_null_and_operator( $args, $argsNum, $sign ) 
	{
		$oneArg = 1; 
		$dispatcher = $this; 
		$oneArg = 1; 
		$dispatcher = $this; 
		$tmps = array(); 
		if( $oneArg<$argsNum ) 
			foreach( $args as $arg ) 
				$tmps[] = array($arg, $sign, NULL); 
		else 
		{
			$args = current($args);
			foreach( $args as $key ) 
			{
				$tmps[] = array($key, $sign, NULL); 
			}
		}
		call_user_func_array( array($dispatcher, '_orWhereAnd'), array($tmps, count($tmps)) ); 
	}
	
	private function _or_where_and_operator( $args, $argsNum, $sign ) 
	{
		$oneArg = 1; 
		$dispatcher = $this; 
		$tmps = array(); 
		if( $oneArg<$argsNum ) 
			foreach( $args as $arg )
				$tmps[] = array(key($arg), $sign, current($arg)); 
		else 
		{
			$args = current($args);
			foreach( $args as $key => $value ) 
				$tmps[] = array($key, $sign, $value); 
		}
		call_user_func_array( array($dispatcher, '_orWhereAnd'), array($tmps, count($tmps)) ); 
	}
	
	private function _orWhereAnd( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
			{
				$threeArg = 3; 
				$twoArg = 2; 
				$oneArg = 1; 
				$dispatcher = $this; 
				if( $oneArg===$argsNum ) 
				{
					$params = current($args); 
					if( is_array($params) ) 
					{
						$argsNum = count($params);
						if( $oneArg>=$argsNum ) 
							throw new Exception( "Using <strong>Model::orWhereAnd()</strong> has a syntax error." ); 
					} 
					else 
						throw new Exception( "Using <strong>Model::orWhereAnd()</strong> has a syntax error." ); 
				} 
				else 
					$params = $args; 
				
				$tmps = array(); 
				foreach( $params as $key => $arg ) 
				{
					if( is_string($key) ) 
					{
						$tmp = $arg; 
						$arg = array(
							$key, 
							'=', 
							$tmp 
						);
					}
					else if( $twoArg===count($arg) ) 
					{
						$tmp = $arg[1]; 
						$arg[1] = '='; 
						$arg[2] = $tmp; 
					} 
					else if( $oneArg===count($arg) )
					{
						$tmp = $arg; 
						$arg = array(
							key($tmp), 
							'=', 
							current($tmp) 
						);
					}
					$args = array($arg);
					$args[] = count($arg); 
					$args[] = true;
					$tmps[] = call_user_func_array( array($dispatcher, '_where'), $args ); 
				}
				if( !empty($tmps) )
					$this->_propsCondOr[] = array('AND'=>$tmps); 
			} 
			else 
				throw new Exception( "Using <strong>Model::orWhereAnd()</strong> has a syntax error." ); 
		} 
		catch( Exception $e ) 
		{ 
			abort( 400, $e->getMessage() );
		} 
	} 
	
	private function _groupBy( $args, $argsNum ) 
	{
		try 
		{
			if( $argsNum ) 
			{
				if( NULL===$this->_propsGroupBy ) 
					$this->_propsGroupBy = $args; 
				else 
					$this->_propsGroupBy += $args;
			}
			else 
				throw new Exception( "Usage <strong>Model::groupBy()</strong> is incorrect." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		}
		return $this;
	} 
	
	private function _orderBy( $args, $argsNum ) 
	{ 
		try 
		{ 
			if( $argsNum ) 
			{ 
				$threeArg = 3; 
				$twoArg = 2; 
				$oneArg = 1; 
				$dispatcher = $this; 
				if( $oneArg===$argsNum ) 
				{
					$tmp = current($args); 
					if( is_string( $tmp ) ) 
					{
						$args[] = 'ASC';
						$this->_propsOrder[] = $args; 
					}
				} 
				else if( $twoArg===$argsNum ) 
				{
					$b0 = is_string($args[0]);
					$b1 = is_string($args[1]);
					if( $b0&&$b1 ) 
					{
						$args[1] = strtoupper($args[1]);
						$this->_propsOrder[] = $args; 
						goto ORDER_BREAK;
					} 
					else 
						goto ORDER_ARR;
				}
				else if( $threeArg===$argsNum ) 
				{
					$b0 = is_string($args[0]);
					$b1 = is_string($args[1]);
					$b2 = is_string($args[2]);
					if( $b0&&$b1&&$b2 ) 
					{
						$args[2] = strtoupper($args[2]);
						$this->_propsOrderCmd[] = $args; 
						goto ORDER_BREAK;
					}
					else 
						goto ORDER_ARR;
				} 
				
				ORDER_ARR:
				foreach($args as $arg) 
					call_user_func_array( array($dispatcher, '_orderBy'), array($arg, count($arg)) ); 
				ORDER_BREAK:
			} 
			else 
				throw new Exception( "Usage <strong>Model::orderBy()</strong> is incorrect." );
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
		return $this;
	}
	
	private function _setLimit( $args, $argsNum ) 
	{
		$this->_propLimit = (int) current($args); 
		if( is_null($this->_propPage) ) 
			$this->_propPage = 1; 
		return $this;
	} 

	private function _setSeek( $args, $argsNum ) 
	{
		$this->_propOffset = (int) current($args); 
		return $this;
	}
	
	private function _setPage( $args, $argsNum ) 
	{
		$this->_propPage = (int) current($args); 
		return $this;
	} 
	
	private function _require( $model ) 
	{
		if( method_exists($this, $model) ) 
			call_user_func_array(array($this, $model), array()); 
		return $this;
	}
	
	private function _orderHasOne( $args, $argsNum ) 
	{
		global $inflect; 
		try 
		{
			if( $argsNum ) 
			{
				$fourArg = 4; 
				$oneArg = 1; 
				$zeroArg = 0;
				if( $oneArg===$argsNum ) 
				{
					$model = current($args);
					if( array_key_exists($model, $this->_propsHasOne) ) 
						return $this->_propsHasOne[$model]; 
					else 
						return;
				}
				else if( $fourArg===$argsNum ) 
				{
					if( array_key_exists($args[$zeroArg], $this->_propsHasOne) ) 
						return $this->_propsHasOne[ $args[$zeroArg] ]; 
					
					$args[] = $this->_propModel;
					$args[] = $this->_propPrefix;
					$model = new StdModel($args); 
					$this->_propsHasOne += array( $args[$zeroArg]=>$model );
					return $model;
				}
				else
					throw new Exception( "Usage <strong>Model::hasOne()</strong> is incorrect." ); 
			} 
			else 
				throw new Exception( "Usage <strong>Model::hasOne()</strong> is incorrect." ); 
		} 
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		} 
		return $this;
	} 
	
	private function _showHasOne() { $this->_flagHasOne = true; return $this; } 
	private function _hideHasOne() { $this->_flagHasOne = false; return $this; } 
	
	private function _orderHasMany( $args, $argsNum ) 
	{ 
		global $inflect; 
		try 
		{
			if( $argsNum ) 
			{
				$fourArg = 4; 
				$oneArg = 1; 
				$zeroArg = 0; 
				if( $oneArg===$argsNum ) 
				{
					$model = current($args);
					if( array_key_exists($model, $this->_propsHasOne) ) 
						return $this->_propsHasMany[$model];
					else 
						return;
				}
				else if( $fourArg===$argsNum ) 
				{
					if( array_key_exists($args[$zeroArg], $this->_propsHasOne) ) 
						return $this->_propsHasOne[ $args[$zeroArg] ]; 
					
					$args[] = $this->_propTable;
					$args[] = $this->_propPrefix;
					$model = new StdModel($args); 
					$this->_propsHasMany += array( $args[$zeroArg]=>$model );
					return $model;
				}
				else
					throw new Exception( "Usage <strong>Model::hasMany()</strong> is incorrect." ); 
			}
			else 
				throw new Exception( "Usage <strong>Model::hasMany()</strong> is incorrect." ); 
		}
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		} 
		return $this;
	} 
	
	private function _showHasMany() { $this->_flagHasMany = true; return $this; }
	private function _hideHasMany() { $this->_flagHasMany = false; return $this; } 
	
	private function _orderHasMABTM( $args, $argsNum ) 
	{
		global $inflect; 
		try 
		{
			if( $argsNum ) 
			{ 
				$fiveArg = 5; 
				$oneArg = 1; 
				$zeroArg = 0; 
				if( $oneArg===$argsNum ) 
				{
					$model = current($args); 
					if( array_key_exists($model, $this->_propsHasMABTM) ) 
						return $this->_propsHasMABTM[$model][mhd];
					else 
						return; 
				} 
				else if( $fiveArg===$argsNum ) 
				{
					if( array_key_exists($args[$zeroArg], $this->_propsHasMABTM) ) 
						return $this->_propsHasMABTM[$args[$zeroArg]][mhd]; 
					
					$data = array( $args[0], $args[1], $args[2], $args[4], $this->_propTable, $this->_propPrefix ); 
					$dataModel = new StdModel( $data ); 
					$dataAlias = explode(mad, $dataModel->getAliasName()); 
					$mainAlias = explode(mad, $this->getAliasName()); 
					$table = array_merge($dataAlias, $mainAlias); 
					sort($table); 
					foreach( $table as $key => $word ) 
						$table[$key] = $inflect->pluralize(strtolower($word)); 
					$table = $this->_propPrefix.implode(mad, $table); 
					$joinModel = array(
						'tb_name' => $table, 
						'fk_main' => $args[3], 
						'fk_data' => $args[2], 
					);
					$model = array(
						mhd => $dataModel, 
						mhj => $joinModel, 
					); 
					$this->_propsHasMABTM += array( $args[$zeroArg]=>$model ); 
				} 
				else 
					throw new Exception( "Usage <strong>Model::hasManyAsBelongsToMany()</strong> is incorrect." ); 
			} 
			else 
				throw new Exception( "Usage <strong>Model::hasManyAsBelongsToMany()</strong> is incorrect." ); 
		}
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() ); 
		}
		return $this;
	} 

	private function _showHasManyAsBelongsToMany() { $this->_flagHasMABTM = true; return $this; } 
	private function _hideHasManyAsBelongsToMany() { $this->_flagHasMABTM = false; return $this; } 
	
	private function _orderImport( $model ) { $this->_propsImport[] = $model; return $this; } 
	
	private function _orderMerge( $args, $argsNum ) 
	{
		try 
		{
			$fourArg = 4; 
			if( $fourArg===$argsNum ) 
			{
				if( array_key_exists($args[head], $this->_propsMerge) ) 
					return $this->_propsMerge[$args[head]];
				$args[] = $this->_propModel;
				$args[] = $this->_propPrefix;
				$model = new StdModel($args); 
				$this->_propsMerge += array( $args[head]=>$model );
				return $model;
			}
			else 
				throw new Exception( "Usage <strong>Model::merge()</strong> is incorrect." ); 
		}
		catch( Exception $e ) 
		{
			abort( 400, $e->getMessage() );
		}
	} 
	
	private function _orderMergeLeft( $args, $argsNum ) 
	{
		try 
		{
			$fourArg = 4; 
			if( $fourArg===$argsNum ) 
			{
				if( array_key_exists($args[head], $this->_propsMergeLeft) ) 
					return $this->_propsMergeLeft[$args[head]]; 
				$args[] = $this->_propModel;
				$args[] = $this->_propPrefix; 
				$model = new StdModel($args); 
				$this->_propsMergeLeft += array( $args[head]=>$model ); 
				return $model;
			} 
			else 
				throw new Exception( "Usage <strong>Model::mergeLeft()</strong> is incorrect." ); 
		} 
		catch( Exception $e ) 
		{ 
			abort( 400, $e->getMessage() ); 
		} 
	} 
	
	private function _orderMergeRight( $args, $argsNum ) 
	{
		try 
		{ 
			$fourArg = 4;
			if( $fourArg===$argsNum ) 
			{ 
				if( array_key_exists($args[head], $this->_propsMergeRight) ) 
					return $this->_propsMergeRight[$args[head]]; 
				$args[] = $this->_propModel;
				$args[] = $this->_propPrefix; 
				$model = new StdModel($args); 
				$this->_propsMergeRight += array( $args[head]=>$model ); 
				return $model; 
			} 
			else 
				throw new Exception( "Usage <strong>Model::mergeRight()</strong> is incorrect." ); 
		} 
		catch( Exception $e ) 
		{ 
			abort( 400, $e->getMessage() ); 
		} 
	} 
	
	private function _shareMainQuery() 
	{
		$remcols = $this->_collection; 
		$this->_collection = '*'; 
		$prefix = $this->_retrivePrefix(); 
		$this->_buildMainQuery( $prefix ); 
		$query = $this->_querySQL; 
		$this->_collection = $remcols; 
		$this->clear(); 
		return substr( $query, 0, strlen($query)-1 ); 
	}

	private function _buildMainQuery( $prefix ) 
	{
		global $inflect;

		$collections = $this->_buildCollectionString();
		$from = '`'.$this->_propTable.'` as `'.$this->_propModel.'` ';
		$conditions = '\'1\'=\'1\' AND ';
		$groupBy = '';
		$groupWith = ''; 
		$orderBy = '';
		$limit = '';
		$hasOne = '';
		$combine = '';

		if( isset($this->id) ) 
		{
			$conditions .= '`'.$this->_propModel.'`.`id` = \''.mysqli_real_escape_string( $this->_dbHandle, $this->id ).'\' AND ';
		}
		$conditions .= $this->_extraConditions;
		$conditions = ' WHERE ' . substr( $conditions, 0, -4 );

		if( isset( $this->_propsGroupBy ) ) 
		{
			$groupBy .= " GROUP BY `".$this->_propModel."`.`".$this->_propsGroupBy."`";
		} 
		
		if( isset( $this->_propsOrderCmd ) ) 
		{
			$orderBy .= ' ORDER BY `'.$this->_propModel.'`.`'.$this->_propsOrderCmd.'` '.$this->_propsOrder;
		}

		if ( isset( $this->_propPage ) ) 
		{
			if( isset($this->_propOffset) ) 
				$offset = $this->_propOffset;
			else
				$offset = ( $this->_propPage-1 ) * $this->_propLimit;
			$limit .= ' LIMIT '.$this->_propLimit.' OFFSET '.$offset;
		}

		if( $this->_flagHasOne && !empty($this->_propsHasOne) ) 
		{
			foreach ( $this->_propsHasOne as $modelChild => $modelChildParams ) 
			{
				if( $modelChildParams['live'] ) 
				{
					$aliasKey = $modelChildParams['alias_key']; 
					$foreignKey = $modelChildParams['foreign_key']; 
					$tableChild = $modelChildParams['table'];
					$hasOne .= 'LEFT JOIN `'.$tableChild.'` as `'.$modelChild.'` ON `'.$this->_propModel.'`.`'.$foreignKey.'` = `'.$modelChild.'`.`'.$aliasKey.'` ';
				}
			}
		}

		if( $this->_combineFlg && isset($this->_imerge) ) 
		{
			$combine = $this->_imerge; 
		} 
		
		$this->_querySQL = 'SELECT '.$collections.' FROM '.$from.$hasOne.$combine.$conditions.$groupBy.$groupWith.$orderBy.$limit; 

		if( $this->_propsImport ) 
		{
			$unionQuery = ' UNION ALL ' . implode( ' UNION ALL ', $this->_propsImport ); 
			if( $this->_flagHasOne && $hasOne ) 
			{
				$selfQuery = 'SELECT * FROM ' . $from . $conditions; 
				$unionQuery = $selfQuery . $unionQuery;
				$this->_querySQL = 'SELECT ' . $collections . ' FROM ( ' . $unionQuery . ' ) AS `' . $this->_propModel . '` ' . $hasOne . $groupBy . $groupWith . $orderBy . $limit; 
			}
			else if( false!==strpos($collections, 'COUNT') ) 
			{
				$selfQuery = 'SELECT * FROM ' . $from . $conditions; 
				$unionQuery = $selfQuery . $unionQuery;
				$this->_querySQL = 'SELECT ' . $collections . ' FROM ( ' . $unionQuery . ' ) AS `' . $this->_propModel . '` ' . $hasOne . $groupBy . $groupWith . $orderBy . $limit; 
			}
			else 
			{
				$this->_querySQL .= $unionQuery; 
			}
		}
	} 

	private function _getError() 
	{
		return array( 
			'error_msg'	=>$this->error(), 
			'error_no'	=>$this->errno() 
		); 
	} 
	
	private function _genRandString( $max_len = 10 ) 
	{
		$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ$!';
		$output = '';
		$char_len = strlen($chars);
		for ($i = 0; $i < $max_len; $i++) 
		{
			$output .= $chars[rand(0, $char_len - 1)];
		}
		return $output;
	} 
	
	private function errno() { return mysql_errno( $this->_dbHandle ); } 
	private function error() { return mysqli_error( $this->_dbHandle ); } 
	private function insert_id() { return mysqli_insert_id( $this->_dbHandle ); } 
	private function fetch_assoc( $rs ) {return mysqli_fetch_assoc( $rs ); } 
	private function fetch_row( $rs ) { return mysqli_fetch_row( $rs ); } 
	private function free_result( $rs ) { return mysqli_free_result( $rs ); } 
	private function escape_string( $str ) { return mysqli_real_escape_string( $this->_dbHandle, trim($str) ); } 
	
	private function fetch_field( $rs, &$ts, &$fs ) 
	{
		$ts = array(); 
		$fs = array(); 
		while($f=mysqli_fetch_field($rs)) 
		{
			$ts[] = $f->table;
			$fs[] = $f->name;
		} 
		return count($fs);
	}
	
	private function _query( $sql ) 
	{
		$sql = trim($sql);
		
		$result = mysqli_query( $this->_dbHandle, $sql ); 
		
		$this->_logsql( $sql ); 
		return $result;
	}
	
	private function _connect( $address, $username, $password, $dbname ) 
	{
		global $configs;
		$dbLink = mysqli_connect($address, $username, $password);
		if ( $dbLink ) 
			if ( mysqli_select_db($dbLink, $dbname) ) 
			{
				mysqli_query( $dbLink, 'SET CHARSET utf8' );	
				$this->_setDBHandle( $dbLink ); 
				$configs[ 'DATASOURCE' ][ 'HANDLECN' ] = $dbLink; 
				return 1;
			}
		return 0;
	}
}