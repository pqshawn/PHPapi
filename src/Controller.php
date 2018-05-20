<?php
/**
 * base controllers
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author: Yzwu <distil@163.com>
 */

class ControllerLib implements ControllerInterfaceLib {

	protected $_pagedata = array();
	protected $_css_dir = CSS_DIR;
	protected $_js_dir = JS_DIR;
	protected $_img_dir = IMG_DIR;
	protected $_html = '';
	protected $_request = '';
	protected $_response = '';
	protected $_compiler = '';
	protected $_model = '';
	protected $_vars = array();
	public function __construct() {
		$this->_request = Blood::single('library\RequestLib');
		$this->_response = Blood::single('library\ResponseLib');
		$this->get_compiler();
		$this->_pagedata['css_dir'] = $this->_css_dir;
		$this->_pagedata['js_dir'] = $this->_js_dir;
		$this->_pagedata['img_dir'] = $this->_img_dir;
		$this->_pagedata = &$this->_vars;
		$this->_model = $this->model();
	}

	public function __destruct() {}

	public function get_header() {
		$header_file = VIEWS.'/header.tpl';
		$header_htm = file_get_contents($header_file);
		return $header_htm;
	}

	public function get_footer() {
		$footer_file = VIEWS.'/footer.tpl';
		$footer_htm = file_get_contents($footer_file);
		return $footer_htm;
	}

	public function display($filepath, $fetch = true, $pub_head = true, $pub_foot = true) {
		$content = $this->display_tmp($filepath, $fetch, $pub_head, $pub_foot);
		$this->_response->set_body($content);
	}

	public function display_tmp($filepath, $fetch = false, $pub_head = true, $pub_foot = true) {
		if($pub_head)
			$header_htm = $this->get_header();
		else
			$header_htm = '';
		if($pub_foot)
			$footer_htm = $this->get_footer();
		else
			$footer_htm = '';
		$body_fpath = VIEWS.'/'.$filepath;
		$body_htm = file_get_contents($body_fpath);

		$html = $header_htm.$body_htm.$footer_htm;
		$compile_html = $this->_compiler->compile($html);
		//echo $compile_html;exit;
		ob_start();
		eval("?>".$compile_html);
		$content = ob_get_contents();
		ob_end_clean();
		if($fetch) return $content;
		else {
			echo $content;
		}
	}

	public function get_compiler() {
		if(empty($this->_compiler)) {
			$this->_compiler = Blood::single('TplcompilerLib');
		}
		return $this->_compiler;
	}

	public function model($model_name = '') {
		if(class_exists($model_name)) {	
			return Blood::single($model_name);
		} else {
			$ex_res = NutrientLib::class_explode(get_class($this));
			array_pop($ex_res);
			return Blood::single(implode('', $ex_res).'Mod');
		}
	}

}