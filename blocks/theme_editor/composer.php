<?php
defined('C5_EXECUTE') or die("Access Denied.");

$this->inc('form_setup_html.php', array('view' => $view,
										'fIDs' => $controller->getFilesIds(),
										'fDetails' => $controller->getFilesDetails($controller->getFilesIds()),
										'fileSets' => $controller->getFileSetList(),
										'isComposer' => true
										));
?>