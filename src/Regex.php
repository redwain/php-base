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

class RegEx
{
	public $pattern;

	function __construct( string $pattern )
	{
		$this->pattern = $pattern;
	}

	function __toString()
	{
		$out = '';
		if ( isset($this->pattern) && is_string($this->pattern) )
			$out = $this->pattern;
		return $out;
	}

}

?>