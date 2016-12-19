<?php
require_once 'template/template.php';

$tmpl_signupform = new Template('signup/form');
$tmpl_signupform->redirect_url = 'signup.php';
$tmpl_signupform->show();
exit();
