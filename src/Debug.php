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

namespace Redwain\Base;

require_once __DIR__.'/Base.php';

class LogException extends \Exception
{
	function __construct($message=NULL)
	{
		parent::__construct($message);
	}

}

class DebEx extends LogException
{
	use Properties;

	/* Properties */
	protected $message;
	protected $code;
	protected $file;
	protected $line;

	private $_allowed_properties = array(
		'classobj'
		,'callerObj'
		,'level'
	);

	public static $stack = array();
	public static $basenames = array();
	public static $output_line = 0;

	function __construct(object $callerObj=NULL, int $level=NULL, $message=NULL)
	{
		$this->callerObj = $callerObj;
		$this->level = is_integer($level) ? $level : 999;
		if ( !is_string($message) )
			$message = Util::json($message);
		parent::__construct($message);
	}

	public function getFunction()
	{
		$arr = $this->getTrace();
		$fx = $arr[0]['function'];
		return $fx;
	}

	public function getLevel()
	{
		return $this->level;
	}

	public function getCallerObj()
	{
		return $this->callerObj;
	}

	public function getInstance()
	{
		return $this->_instance;
	}

	public function setClassname(string $val=NULL)
	{
		$this->_classname = $val;
	}

	function __toString()
	{
		$loc = '';

		$fn = $this->getFile();
		$fx = $this->getFunction();
		$ln = $this->getLine();

		$_debug = NULL; // default
		$_instance = NULL;
		$_classname = NULL;
		$_callerObj = $this->getCallerObj();
		if ( is_object($_callerObj) )
		{
			$_classname = get_class($_callerObj);
			if ( property_exists($_callerObj,'_instance') )
				$_instance = $_callerObj->_instance;
		}

		// basename cache
		$base_fn = '';
		if ( $fn )
		{
			if ( !array_key_exists($fn,self::$basenames) )
				$base_fn = self::$basenames[$fn] = substr( basename($fn), 0, -4); // trim .php
			else
				$base_fn = self::$basenames[$fn];
		}
		$id = Util::id($base_fn,$_instance,$_classname);

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
		if ( $fx )
			$loc .= $fx .'(): ';

		return trim($loc);
	}

}

?>