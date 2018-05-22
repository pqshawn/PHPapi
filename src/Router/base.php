<?php
/**
 * base Router
 *
 * @author:Yzwu <Ldos.net>
 */

abstract class BaseRouterLib {

	abstract public function load_mapper($mapper);

	abstract public function parse_url($url_str);

	abstract public function parse_module($model_info);
}