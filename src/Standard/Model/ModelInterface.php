<?php
namespace PhpApi\Standard\Model;


/**
 * interface controllers
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author: Shawn Yu <pggq@outlook.com>
 */

interface ModelInterface {
	public function create($sql);
	public function update($sql);
	public function retrieve($sql);
	public function delete($sql);
}