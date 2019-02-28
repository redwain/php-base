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

use \Redwain\Base\Log;
use \Redwain\Base\DebEx;

trait Properties
{
	private $_option = array();
	private static $debugLevel = 45;

	function __set($key, $val)
	{
		// sanity: property_exists
		if ( !property_exists($this,'_allowed_properties') )
			throw new \Exception('class does not have required property: '. Util::json('_allowed_properties') );

		if ( Log::$debug >= self::$debugLevel )
		{
			$logEx = $this instanceof LogException ? NULL : new DebEx($this,self::$debugLevel);
			Log::debug( $logEx, $key .' <= '. Util::json($val) );
		}

		// sanity: _allowed
		if ( !in_array($key,$this->_allowed_properties) )
			throw new \Exception('option not allowed: '. $key );

		$this->_option[$key] = $val;
	}

	function __get($key)
	{
		// sanity: _allowed
		if ( !in_array($key,$this->_allowed_properties) )
			throw new \Exception('option not allowed: '. $key );

		$val = NULL;
		if ( array_key_exists($key, $this->_option) )
			$val = $this->_option[$key];

		if ( Log::$debug >= self::$debugLevel )
		{
			$logEx = $this instanceof LogException ? NULL : new DebEx($this,self::$debugLevel);
			Log::debug( $logEx, $key .' => '. Util::json($val) );
		}

		return $val;
	}

	function __isset(string $key)
	{
		// sanity: _allowed
		if ( !in_array($key,$this->_allowed_properties) )
			throw new \Exception('option not allowed: '. $key );

		$val = false;
		if ( array_key_exists($key,$this->_option) )
			$val = isset($this->_option[$key]);

		if ( Log::$debug >= self::$debugLevel )
		{
			$logEx = $this instanceof LogException ? NULL : new DebEx($this,self::$debugLevel);
			Log::debug( $logEx, $key . ( $val ? ' is set' : ' is not set' ) );
		}

		return $val;
	}

	function __unset(string $key)
	{
		// sanity: _allowed
		if ( !in_array($key,$this->_allowed_properties) )
			throw new \Exception('option not allowed: '. Util::json($key) );

		unset($this->_option[$key]);

		if ( Log::$debug >= self::$debugLevel )
		{
			$logEx = $this instanceof LogException ? NULL : new DebEx($this,self::$debugLevel);
			Log::debug( $logEx, $key .' unset');
		}
	}

}

?>