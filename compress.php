<?php
ini_set('max_execution_time', 300);
ini_set('memory_limit','500M');

$src='./outputs/';//path which contains the files to be zipped
$des='./backup/backup_'.date('Y-m-d' ).'.zip';//destination

compress($src, $des);
echo 'File Zipped successfully';

function compress($src, $des) {
	if (extension_loaded('zip')) {
		if (file_exists($src)) {
			$zip = new ZipArchive();
			if ($zip->open($des, ZIPARCHIVE::CREATE)) {
				$src = realpath($src);
				if (is_dir($src)) {
					$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($src), RecursiveIteratorIterator::SELF_FIRST);
					
					
					foreach ($files as $file) {
						
						$file = realpath($file);
						
						$filelastmodified = filemtime($file);
						
						 if((time()-$filelastmodified) >24*3600)//checks the files created before 24 hours
                                                 {
	  
						if (is_dir($file)) {
							$zip->addEmptyDir(str_replace($src . '/', '', $file . '/'));
							
						} else if (is_file($file)) {
							$zip->addFromString(str_replace($src . '/', '', $file), file_get_contents($file));
						
						}
						
						unlink($file);//delete files after zipping
					}
	
						
					}
				} else if (is_file($src)) {
					$zip->addFromString(basename($src), file_get_contents($src));
				}
			}
			return $zip->close();
		}
	}
	return false;
}

?>