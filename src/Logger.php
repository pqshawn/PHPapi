<?php
namespace PhpApi;

use PhpApi\NutrientLib;

class Logger {
    public static function log($message, $show = false) {
		if(!empty($show)) {
			echo $message."\n";
		} else {
			$log = sprintf("%s\t%s\n", date('Y-m-d H:i:s'), $message);
			!defined('LOG_FILE_PATH')?define('LOG_FILE_PATH', ROOT.'/../logs/{date}.log'):'';
			if(stripos(LOG_FILE_PATH, '{date}') !== false) {
				$logfile = str_replace('{date}', date('Y_m_d'), LOG_FILE_PATH);	
			} else {
				$logfile = $logfile.time();
			}
			
			if(!is_file($logfile)) {
				Nutrient::mkfile($logfile);
				$logfile_h = "<?php exit(); ?>\n";
				file_put_contents($logfile, $logfile_h);
			}
			
			error_log($log, 3, $logfile);
		}
	}
}