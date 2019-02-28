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

use \Redwain\Base\Log;
use \Redwain\Base\DebEx;

trait Collection
{
	private static $debugLevel = 45;
	private $_collection = array();
	private $_allowed_classes = array();

	public function pop()
	{
		if ( !is_array($this->_collection) || count($this->_collection) == 0 ) return NULL;

		$obj = array_pop($this->_collection);

		Log::debug( new DebEx($this,self::$debugLevel), '<- '. Util::json($obj) .' ('. count($this->_collection) .')');
		return $obj;
	}

	public function push($obj)
	{
		if ( is_array($obj) )
		{
			Log::debug( new DebEx($this,self::$debugLevel), 'foreach array { push }');
			foreach($obj as $f) $this->push($f);
			return;
		}

		if ( !is_object($obj) )
		{
			Log::debug( new DebEx($this,self::$debugLevel), 'refusing to push non-object: '. Util::json($obj) );
			return;
		}
		$objClassName = get_class($obj);
		if ( count($this->_allowed_classes) > 0 && !in_array($objClassName, $this->_allowed_classes) )
		{
			Log::debug( new DebEx($this,self::$debugLevel), 'refusing to push invalid object: '.$objClassName);
			return;
		}

		$this->_collection[] = $obj;

		Log::debug( new DebEx($this,self::$debugLevel),'+ '. $objClassName .' ('. count($this->_collection) .')');
		return;
	}

	public function shift()
	{
		if ( !is_array($this->_collection) || count($this->_collection) == 0 ) return NULL;

		$obj = array_shift($this->_collection);
		Log::debug( new DebEx($this,self::$debugLevel),'<- '. Util::json($obj) .' ('. count($this->_collection) .')');
		return $obj;
	}

	public function unshift($obj)
	{
		if ( is_array($obj) )
		{
			Log::debug( new DebEx($this,self::$debugLevel), 'foreach array { unshift }');
			foreach($obj as $f) $this->unshift($f);
			return;
		}

		if ( !is_object($obj) )
		{
			Log::debug( new DebEx($this,self::$debugLevel), 'refusing to push non-object: '. Util::json($obj) );
			return;
		}
		$objClassName = get_class($obj);
		if ( count($this->_allowed_classes) > 0 && !in_array($objClassName, $this->_allowed_classes) )
		{
			Log::debug( new DebEx($this,self::$debugLevel), 'refusing to push invalid object: '.$objClassName);
			return;
		}

		array_unshift($this->_collection,$obj);

		Log::debug( new DebEx($this,self::$debugLevel),'+ '. $objClassName .' ('. count($this->_collection) .')');
		return;
	}

	public function count()
	{
		$n = count($this->_collection);
		Log::debug( new DebEx($this,self::$debugLevel), $n );
		return $n;
	}

}

?>