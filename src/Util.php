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

class Util
{

	public static function id($base_fn,$instance=NULL,$class=NULL)
	{
		//$id = $base_fn;
		$id = '';
		if ( $class && $instance )
			$id = '['. $class .'_'. $instance .']';
		elseif ( $instance )
			$id = '['. $instance .']';
		elseif ( $class )
			$id = '['. $class .']';

		return $id;
	}

	// https://php.net/manual/en/function.rand.php#111277
	public static function GeraHash($qtd)
	{ 
		//Under the string $Caracteres you write all the characters you want to be used to randomly generate the code. 
		$Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789'; 
		$QuantidadeCaracteres = strlen($Caracteres); 
		$QuantidadeCaracteres--; 

		$Hash=NULL; 
		for($x=1;$x<=$qtd;$x++)
		{
			$Posicao = rand(0,$QuantidadeCaracteres); 
			$Hash .= substr($Caracteres,$Posicao,1); 
		} 

		return $Hash; 
	}

	static function json($val,$level=0)
	{
		global $phpver_json_encode;

		if ( is_object($val) )
		{
			$out = 'Object:'.get_class($val);
			if ( method_exists($val,'instance') )
				$out .= '_'. $val->instance();
		}
		elseif ( is_array($val) )
		{
			$out = array();
			foreach($val as $k => $v)
			{
				//if ( $k == 'params' ) continue;
				if ( is_array($v) && $k === 'params' ) $out[$k] = 'params';
				elseif ( is_object($v) )
				{
					$out[$k] = 'Object:'.get_class($v);
					if ( method_exists($v,'instance') )
						$out[$k] .= '_'. $v->instance();
				}
				elseif ( is_array($v) ) $out[$k] = self::json($v,$level+1);
				else $out[$k] = $v;
			}
		}
		else $out = $val;

		if ( $level > 0 ) return $out;

		// calculate php version
		if ( !isset($phpver_json_encode) )
		{
			$php_ver = phpversion();
			$ver = explode('.',$php_ver);
			$phpver_json_encode = (int)sprintf('%02d%02d%02d',$ver[0],$ver[1],$ver[2]);
		}
		$ver_id = $phpver_json_encode;

		// json features by php version
		$b = 0;
		if ( $ver_id >= 50500 ) $b = $b | JSON_PARTIAL_OUTPUT_ON_ERROR;
		if ( $ver_id >= 50400 ) $b = $b | JSON_PRETTY_PRINT;

		return json_encode($out, $b);
	}

}

?>