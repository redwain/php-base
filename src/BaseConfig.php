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

class Config
{
	use \Redwain\Base\Properties;

	public $_instance; // random alphanumeric, for logging
	private $_allowed_properties = array();
	private static $debugLevel = 45;

	function __construct( string $file, array $allowed_properties )
	{
		$this->_instance = Util::GeraHash(6);
		Log::fxin( new DebEx($this), array($file,$allowed_properties) );

		$this->_allowed_properties = $allowed_properties;

		$cfg = parse_ini_file($file);
		Log::debug( new DebEx($this,self::$debugLevel), Util::json($cfg) );

		foreach($cfg as $key => $val)
			$this->$key = $val;

		Log::fxout( new DebEx($this) );
	}

}

?>