<?php
/**
 * @file Custom mail functions handler.
 */

/**
 * @file
 * This is the primary module file.
 */

use Drupal\Core\Render\Markup;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Url;

// Define message formats.
define('SWIFTMAILER_FORMAT_PLAIN', 'text/plain');
define('SWIFTMAILER_FORMAT_HTML', 'text/html');

/**
 * Handler for all email messages.
 * $params['account'];
 * Implements hook_mail().
 * This function completes the email, allowing for placeholder substitution.
 */
function custom_mail($key, &$message, $params) {
  if (($key == 'email_confirm_invoice')) {
    // $message['headers']['Content-Type'] = SWIFTMAILER_FORMAT_HTML;
    // $message['from'] = 'info.codeexpertz@gmail.com';
    // $message['subject'] = "Test Mail from Thaomario invoice";
    // $message['headers']['Content-Type'] = 'text/html; charset=UTF-8; format=flowed; delsp=yes';
    // $message['body'][] = $params['message'];
    $user = \Drupal::currentUser();
    $message['headers']['Content-Type'] = SWIFTMAILER_FORMAT_HTML;
    
    $text[] = '<h3>' . t('Dear @user,', ['@user' => $user->getDisplayName()]) . '</h3>';
    $text[] = '<p>' . t('This e-mail has been sent from @site by the Swift Mailer module. The module has been successfully configured.', [
        '@site' => \Drupal::config('system.site')
          ->get('name'),
      ]) . '</p>';
    $text[] = t('Kind regards') . '<br /><br />';
    $text[] = t('The Swift Mailer module');
    
    $message['subject'] = t('Swift Mailer has been successfully configured! by ThaoMario');
    $message['body'] = array_map(function ($text) {
      return Markup::create($text);
    }, $text);
    
  }
}

/*
 * Handler send email invoice when user roles sub-admin approve
 **/
function custom_email_confirm_invoice($submission_id) {
  
  // // Send the pass reset link to user's email.
  $mailManager = \Drupal::service('plugin.manager.mail');
  $module = 'custom';
  $key = 'email_confirm_invoice';
  $to = 'thaomario95@gmail.com';
  $params['message'] = '<h2>Demo Nè</h2>';
  $params['node_title'] = 'Test Demo 16/07/2019';
  $langcode = \Drupal::currentUser()->getPreferredLangcode();
  $send = TRUE;
  $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
  // if ($result['result'] !== true) {
  //   drupal_set_message(t('There was a problem sending your message and it was not sent.'), 'error');
  // }
  // else {
  //   drupal_set_message(t('Your message has been sent.'));
  // }
  
  kint($result);
}

