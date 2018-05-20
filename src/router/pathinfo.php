<?php
/**
 * Pathinfo URL MODEL
 *
 * @author:Yzwu <Ldos.net>
 */

class PathinfoRouterLib extends BaseRouterLib {

	public $_mapper = array();
	public $_parse_mapper = array();
	public $_pathinfo = '';


	public function __construct($mapper) {
		if(empty($mapper) || !is_array($mapper)) trigger_error('please configure your config/mapper.php');
		$this->_mapper = $mapper;
		$this->load_mapper($mapper);
		if(empty($_SERVER['PATH_INFO']) && $_SERVER['REQUEST_URI'] != '/') {
			$tempo = $_SERVER['REQUEST_URI'];
			if(strpos($tempo, '?') !== false) {
				list($tempo, $query_string) = explode('?', $tempo);
			}
		} else {
			$tempo = $_SERVER['PATH_INFO'];
		}
		if(empty($tempo))
			return new NormalRouterLib($mapper);
		else
			$this->_pathinfo = $tempo;
	}

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
		if(empty($url_str)) $url_str = $this->_pathinfo;
		empty($url_str)? $url_str = '/gallery.html':'';
		$parse_mapper = $this->_parse_mapper;
		$action_params = array();
		if(!empty($parse_mapper) && is_array($parse_mapper)) {
			foreach($parse_mapper as $mk => $mv) {
				if(empty($mv['pattern'])) return false;
				if(!isset($mv['extension'])) $mv['extension'] = MAPPER_EXTEND_NAME;
				$url_str_noline = substr($url_str, 1);
				if(preg_match('/^'.$mv['pattern'].$mv['extension'].'$/i', $url_str_noline, $match_mapper)) {
					$i = 1;
					$action_params = $mv;
					if(isset($mv['config']) && is_array($mv['config'])) {
						foreach($mv['config'] as $ck => $cv) {
							if(isset($match_mapper[$i])) {
								$action_params['parameter'][$ck] = $match_mapper[$i++];
							}
						}
					}
                                        if($_SERVER['REQUEST_URI']) {
						$parse = parse_url($_SERVER['REQUEST_URI']);
						if($parse['query']) {
							parse_str($parse['query'], $query);
							if(is_array($query)) {
								$action_params['parameter'] = array_merge($action_params['parameter'], $query);
							}
						}
					}
					break;
				}// end preg_match
			}

		}
		return $action_params;
	}

	/**
	 * @param array $model_info
	 * array('app' => '', 'module' => '', 'action' => '', 'parameter' => array());
	 */
	public function parse_module($model_info) {
		if(empty($model_info) && !is_array($model_info)) return false;
		$parse_mapper = $this->_parse_mapper;
		$action_k = $model_info['app'].'/'.$model_info['module'].'/'.$model_info['action'];
		$action_url = '';
		if(array_key_exists($action_flat, $parse_mapper) === true) {
			$action_flat = $parse_mapper[$action_k];
			$action_url = $parse_mapper[$action_k]['pattern'];
			if(is_array($action_flat['default'])) {
				foreach ($action_flat['default'] as $key => $value) {
					if(!empty($model_info['parameter'][$key])) {
						$param_v = $model_info['parameter'][$key];
					} else {
						$param_v = $value;
					}
					$action_url = str_replace('{'.$key.'}', $param_v, $action_url);
				}
			}
		}// action exist
		return $action_url;
	}
}