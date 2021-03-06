<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

if ($setting->getValue('disable_contactform')) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Contactform is currently disabled. Please try again later.', 'TYPE' => 'errormsg');
  $smarty->assign("CONTENT", "empty");
} else if ($setting->getValue('disable_contactform_guest') && !$user->isAuthenticated(false)) {
  $_SESSION['POPUP'][] = array('CONTENT' => 'Contactform is disabled for guests.', 'TYPE' => 'errormsg');
  $smarty->assign("CONTENT", "disabled.tpl");
} else {
  if ($setting->getValue('recaptcha_enabled')) {
    require_once(INCLUDE_DIR . '/lib/recaptchalib.php');
    $smarty->assign("RECAPTCHA", recaptcha_get_html($setting->getValue('recaptcha_public_key')));
  }
  // csrf token
  if ($config['csrf']['enabled'] && !in_array('contact', $config['csrf']['disabled_forms'])) {
    $token = $csrftoken->getBasic($user->getCurrentIP(), 'contact');
    $smarty->assign('CTOKEN', $token);
  }
  // Tempalte specifics
  $smarty->assign("CONTENT", "default.tpl");
}
?>
