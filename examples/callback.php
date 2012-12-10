<?php


/* 
 * Absolute location of a file that will hold the callback log.
 * Caution! This file WILL be edited and may be deleted. Customize this parameter to indicate an empty files.  
 */
$tmpDir = '/tmp/callback';

$result = array();

$type = (!empty($_POST['type'])) ? $_POST['type'] : NULL ;
$result['type'] = $type;

$result['project_id'] = (!empty($_POST['project_id'])) ? 	(int)$_POST['project_id'] : NULL ;
switch ($type) {
	case 'status_change' :
		
		$result['word_count'] = (!empty($_POST['word_count'])) ? 	(int)$_POST['word_count'] : NULL ;
		$result['source'] = (!empty($_POST['source'])) ? 	$_POST['source'] : NULL ;
		$result['target'] = (!empty($_POST['target'])) ? 	$_POST['target'] : NULL ;
		$result['credits'] = (!empty($_POST['credits'])) ? (int)$_POST['credits'] : NULL ;
		$result['project_status'] = (!empty($_POST['project_status'])) ? $_POST['project_status'] : NULL ;
		$result['estimate_finish'] = (!empty($_POST['estimate_finish'])) ? $_POST['estimate_finish'] : NULL ;
		$result['translation_ready'] = (!empty($_POST['translation_ready'])) ? (bool)$_POST['translation_ready'] : NULL ;
		$result['project_url'] = (!empty($_POST['project_url'])) ? $_POST['project_url'] : NULL ;
		break;
	case 'translation_submitted':
		$result['original_content'] = (!empty($_POST['original_content'])) ? base64_decode($_POST['original_content']) : NULL ;
		$result['translated_content'] = (!empty($_POST['translated_content'])) ? base64_decode($_POST['translated_content']) : NULL ;
		$result['content_type'] = (!empty($_POST['content_type'])) ? $_POST['content_type'] : NULL ;
		break;
		
}
$result['user_reference'] = (!empty($_POST['user_reference'])) ? 	$_POST['user_reference'] : NULL ;
$result['project_reference'] = (!empty($_POST['project_reference'])) ? 	$_POST['project_reference'] : NULL ;
for($i=0;$i<10;$i++){
	$result['custom'.$i] = (!empty($_POST['custom'.$i])) ? 	$_POST['custom'.$i] : NULL ;
}

ob_start();

print_r($result);

$ob = ob_get_clean();
ob_end_clean();

$h = fopen($tmpDir,'a');
fwrite($h,"*************".PHP_EOL);
fwrite($h,date('Y-m-d H:i:s').PHP_EOL);
fwrite($h,$ob);
fwrite($h,PHP_EOL."*************".PHP_EOL.PHP_EOL);
fclose($h);

?>