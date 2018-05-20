<?php
/**
 * The nutrient in the blood,supply where need
 * nutrient for body
 *
 * @author:Yzwu <Ldos.net>
 * Date:2015/1/3 
 */

class NutrientLib {

	public static function mkdir($dirpath) {
		if(is_dir($dirpath)) 
		{
			return true;
		} else {
			$mk_res = is_string($dirpath)?mkdir($dirpath, 0755, true):false;
			return $mk_res;	
		}
		
	}

	public static function mkfile($filepath) {
		$p_arr = explode('/', str_replace('\\', '/', $filepath));
		if(count($p_arr) > 0) {
			$rdir_p = strpos($filepath, ROOT_DIR);
			if($rdir_p !== false) {
				//in case multistep as the ROOT_DIR same
				$ensure_dir = ROOT_DIR.substr($filepath, strlen(ROOT_DIR));
				$ensure_de = explode('/', $ensure_dir);
				array_pop($ensure_de);
				self::mkdir(implode('/', $ensure_de));
			} else {
				trigger_error('Dont set your filepath in which not webroot', E_USER_ERROR);
			}
		}
	}

	//setup
	public static function setup() {
		$sql_file_count = 0;
		$sql_command_count = 0;
		$install_lock = ROOT_DIR.'/config/slock.php';
		try{
			if(is_file($install_lock)) {
				return true;
			} else {			
				$files_path = array();
				$all_files_flag = false;
				if(!defined('DB_FILES_DIR'))
				return new Exception('please setup your dir of files for DB!'); 
				self::readdir(DB_FILES_DIR, $files_path);
				
				do {
					$all_files_flag = true;
					foreach($files_path as $k => &$row) {
						if($row[1] == 'dir') {
							$all_files_flag = false;
							self::readdir($row[0], $files_path);
							unset($files_path[$k]);
						}
					}
				} while ($all_files_flag == false);
				
				$sql_file_count = count($files_path);
				$sql_command_count = self::command_count($files_path);
				$command_per = 500.00 / $sql_command_count;

				if (ob_get_level() == 0) ob_start();
				echo "<p>Installed the DB,count of files:{$sql_file_count},",
					"count of commands:{$sql_command_count}, please waiting......</p>",
					'<p id="loading_size"></p>',
				    '<p style="width:500px;height:20px;background:#000000;">',
					'<span id="loading_img" style="float:left;height:20px;background:blue;text-align:center;"></span></p>';
				if(is_array($files_path) && !empty($files_path)) {
					$setup_count = 0;
					$db = Blood::DB(1);
					foreach($files_path as $file) {
						$sql_arr = self::getline($file[0]);
						if(is_array($sql_arr) && !empty($sql_arr)) {
							foreach($sql_arr as $v) {
								if(preg_match('/^(\s)*$/i', $v)) continue;
								$sql = $v.';';
								$res = $db->exec($sql);
								if(!$res) throw new Exception("The sql file has wrong syntax,check your file:{$file[0]}");
								$setup_count++;
								$comman_cur_per = $command_per * $setup_count;
								echo "<script>document.getElementById('loading_img').style.width='",$comman_cur_per,
									"';document.getElementById('loading_size').innerText='".(round($comman_cur_per * 100 / 500))."%';</script>";
								ini_set('output_buffering', 0);
								echo str_pad('',4096)."\n";   
								ob_flush();
								flush();
								sleep(1);
							}	
						}
					}

					//create lock
					if($setup_count == $sql_command_count) {
						echo '<button onclick="window.location.reload()">plese refresh the page</button>';
						self::cfile($install_lock);
					}
					exit();
				}
			}//$ else

		} catch(Exception $e) {
			Blood::user_exception_handle();
			echo 'Message: ' .$e->getMessage();
		}

	}

	public static function readdir($dir, &$files_path) {
		$root_dir = $dir;
		$handle = opendir($dir);
		while($handle && ($entry = readdir($handle)) !== false) {
			if($entry == "." || $entry == "..") continue;
			$new_dir = $root_dir.DIRECTORY_SEPARATOR.$entry;
			if(is_dir($new_dir)) {
				$files_path[] = array($new_dir, 'dir');
			} else {
				$files_path[] = array($new_dir, 'file');
			}
			 
		}
		closedir($handle);
	}

	public static function getline($filepath) {
		if(!is_file($filepath)) return false;
		$file = file_get_contents($filepath, null, null, -1);
		$sql_arr = explode(';', $file);
		return $sql_arr;
	}

	public static function command_count($file = array()) {
		if(is_array($file) && !empty($file)) {
			$count = 0;
			foreach($file as $v) {
				$com_arr = self::getline($v[0]);
				if(is_array($com_arr) && !empty($com_arr)) {
					foreach($com_arr as $cv) {
						if(preg_match('/^(\s)*$/i', $cv)) continue;
						$count++;
					}	
				}
			}
			return $count++;
		} else {
			return false;
		}
	}

	public static function cfile($filename) {
		$fp = fopen($filename, 'w+');
		$dir = dirname($filename);
		if(!is_writable($filename)) trigger_error("The dir has not authority: {$dir}");
		$flag = date('Y-m-d H:i:s');
		fwrite($fp, $flag);
		fclose($fp);
	}

	public static function class_explode($class_name) {
		$nspace = $class_name;
		$nspace_path = str_replace('\\', '/', $nspace);
		$nspace_arr = explode('/', $nspace_path);
		if(count($nspace_arr) > 1) {
			$class_name = array_pop($nspace_arr);
		}
		$ex_res = preg_split("/(?=[A-Z])/", $class_name, 0, PREG_SPLIT_NO_EMPTY);
		return $ex_res;
	}

}