<?php

/*
 * Absolute location of a file that will hold the callback log.
 * Caution! This file WILL be edited and may be deleted. Customize this parameter to indicate an empty files.
 */
$tmpDir = '/tmp/callback';

$result = array();

$event = (!empty($_POST['event'])) ? $_POST['event'] : null;
$result['event'] = $event;

$result['project_id']           = (!empty($_POST['project_id']))            ? (int) $_POST['project_id']            : null;
$result['project_status_code']  = (!empty($_POST['project_status_code']))   ? $_POST['project_status_code']         : null;
switch ($event) {
    case 'project.comments.new' :
        $result['comment_id'] = (!empty($_POST['comment_id'])) ? (int) $_POST['comment_id'] : null;
        $result['comment_date'] = (!empty($_POST['comment_date'])) ? $_POST['comment_date'] : null;
        $result['commenter_name'] = (!empty($_POST['commenter_name'])) ? $_POST['commenter_name'] : null;
        $result['commenter_role'] = (!empty($_POST['commenter_role'])) ? $_POST['commenter_role'] : null;
        $result['comment_content'] = (!empty($_POST['comment_content'])) ? $_POST['comment_content'] : null;
        break;
    case 'project.resources.new':
        $result['resource_uuid'] = (!empty($_POST['resource_uuid'])) ? $_POST['resource_uuid'] : null;
        $result['resource_type'] = (!empty($_POST['resource_type'])) ? $_POST['resource_type'] : null;
        break;
}
for ($i = 0; $i < 10; $i++) {
    $result['custom' . $i] = (!empty($_POST['custom' . $i])) ? $_POST['custom' . $i] : null;
}

ob_start();

print_r($result);

$ob = ob_get_clean();
ob_end_clean();

$h = fopen($tmpDir, 'a');
fwrite($h, "*************" . PHP_EOL);
fwrite($h, date('Y-m-d H:i:s') . PHP_EOL);
fwrite($h, $ob);
fwrite($h, PHP_EOL . "*************" . PHP_EOL . PHP_EOL);
fclose($h);
