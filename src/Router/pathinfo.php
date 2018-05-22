<?php
namespace PhpApi;
/**
 * Pathinfo URL MODEL
 *
 * @author:Yzwu <Ldos.net>
 */

class PathinfoRouterLib extends BaseRouterLib {


	public function __desctruct() {}

	public function load_mapper($mapper) {
		$this->_parse_mapper = array();
		foreach($mapper as $m) {
			if(isset($m['config']) && is_array($m['config'])) {
				foreach($m['config'] as $ck => $cv) {
					$m['pattern'] = str_replace('{'.$ck.'}', '('.$cv.')', $m['pattern']);
				}
			}
			$action_loc = $m['app'].'/'.$m['module'].'/'.$m['action'];
			$this->_parse_mapper[$action_loc] = $m;
		}
	}

	public function parse_url($url_str = '') {
	}

	/**
	 * @param array $model_info
	 * array('app' => '', 'module' => '', 'action' => '', 'parameter' => array());
	 */
	public function parse_module($model_info) {
		
	}
}