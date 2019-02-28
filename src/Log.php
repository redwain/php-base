<?php /*

Copyright 2019 Glendon S F Johnson

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License. */

/*
** debug levels:
**  0 = none (default)
**  10 = What is created
**  20 = Values
**  45 = Base Properties
**  50 = Function in/out
*/

namespace Redwain\Base;

class Log
{
	public static $dest = 'stdout'; // stdout, stderr, file://path/file.ext
	public static $debug = 0; // default
	public static $stack = array();
	public static $basenames = array();
	public static $output_line = 0;

	static function fxin( LogException $logEx, $data=NULL )
	{
		if ( self::$debug < 50 ) return;

		self::_log( $logEx, 'start:', $data);
	}

	static function fxout( LogException $logEx, $data=NULL )
	{
		if ( self::$debug < 50 ) return;

		self::_log( $logEx, 'end:', $data);
	}

	static function info( LogException $logEx, $data=NULL )
	{
		self::_log( $logEx, $data);
	}

	static function debug( LogException $logEx=NULL, $data=NULL )
	{
		// check master level
		if ( self::$debug < 1 ) return;

		// check this message level
		$level = $logEx instanceof LogException ? $logEx->getLevel() : 999;
		if ( self::$debug < $level ) return;

		self::_log( $logEx, $data);
	}

	private static function _log( ... $arr )
	{
		$r = [];
		array_unshift($arr, self::$output_line++ );
		foreach($arr as $a)
		{
			if ( is_string($a) || is_integer($a) )
				$r[] = (string)$a;
			elseif ( is_object($a) && $a instanceof LogException )
				$r[] = (string)$a;
			elseif ( $a === NULL )
				continue;
			else
				$r[] = Util::json($a);
		}
		$msg = join(' ',$r);
		self::_out($msg);
	}

	private static function _old_log($fn,$fx,$ln,$msg,$instance=NULL,$class=NULL,$type=NULL)
	{
		$loc = '';

		// basename cache
		$base_fn = '';
		if ( $fn )
		{
			if ( !array_key_exists($fn,self::$basenames) )
				$base_fn = self::$basenames[$fn] = substr( basename($fn), 0, -4); // trim .php
			else
				$base_fn = self::$basenames[$fn];
		}
		$id = Util::id($base_fn,$instance,$class);

		// stack
		$arr = array();
		for($i=0; $i <= count(self::$stack); $i++)
		{
			if ( $i != count(self::$stack) && self::$stack[$i] != $base_fn.'->'.$fx )
				$arr[] = self::$stack[$i];
		}
		if ( $type === 'in' && count($arr) )
		{
			$stack = join(' >> ',$arr);
			$_line = self::$output_line++;
			//log_message('debug',$_line.' '.$stack .': stack');
			self::_out( $_line.' '.$stack .': stack' );
		}

		// class id
		if ( $id )
			$loc .= $id .' ';

		$fnln = '';
		if ( $fn || $ln > 0 )
		{
			$fnln = '[';

			// filename
			if ( $fn )
				$fnln .= $base_fn .':';

			// line number
			if ( $ln > 0 )
				$fnln .= $ln;

			$fnln .= '] ';
		}

		if ( $fnln )
			$loc .= $fnln;

		// function
		if ( $fx ) $loc .= $fx .'(): ';

		// output
		$_line = self::$output_line++;
		//log_message('debug',$_line.' '.$loc.$msg);
		self::_out($_line.' '.$loc.$msg);
	}

	private static function _out($msg)
	{
		if ( strpos(self::$dest,'file://') === 0 && strlen(self::$dest) > 7 )
		{
			$fn = substr(self::$dest,7);
			echo $fn.PHP_EOL;
			if ( !is_writeable($fn) )
				die('unable to write to file: '. $fn);
			file_put_contents($fn,$msg.PHP_EOL,FILE_APPEND);
		}
		elseif ( self::$dest === 'console' || self::$dest === 'stdout' )
			echo $msg .PHP_EOL;
		elseif ( self::$dest === 'stderr' )
			error_log( $msg .PHP_EOL ); // XXX: is this appropriate??
	}

}

?>