<?php

namespace Drupal\custom\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class customController extends ControllerBase
{
  
  /**
   * page get data render template print pdf
   */
  public function custom_submission_template_pdf($submission_id) {
    $webform_submission = \Drupal\webform\Entity\WebformSubmission::load($submission_id);
    if (empty($webform_submission) || $webform_submission == NULL) {
      drupal_set_message(t('Webform submission does not exist!'), 'error');
      exit('Stop!!!');
    }
    global $base_url;
    $full_name_of_company = $webform_submission->getData()['full_name_of_company'];
    $company_registration_no = $webform_submission->getData()['company_registration_no'];
    $date_of_incorporation_of_company = date('d M Y', strtotime($webform_submission->getData()['date_of_incorporation_of_company']));
    $address = $webform_submission->getData()['address'];
    $name_designation = $webform_submission->getData()['name_designation'];
    $logo = $base_url . '' . file_url_transform_relative(file_create_url(theme_get_setting('logo.url')));
    
    $content_submission = array(
      'full_name_of_company' => $full_name_of_company,
      'company_registration_no' => $company_registration_no,
      'date_of_incorporation_of_company' => $date_of_incorporation_of_company,
      'address' => $address,
      'name_designation' => $name_designation,
      'logo' => $logo,
    );
    
    $template = [
      '#theme' => 'submission_pdf_invoice',
      '#title' => 'Demo1',
      '#items' => $content_submission,
    ];
    
    return $template;
  }
  
  public function custom_submission_send_mail($submission_id) {
    
    $element = array(
      '#markup' => 'Hello, world',
    );
    // $webform_submission = \Drupal\webform\Entity\WebformSubmission::load($submission_id);
    // module_load_include('inc', 'custom', 'includes/custom.mail');
    // $test = custom_email_confirm_invoice($submission_id);
    // return $test;
    return $element;
  }
  
  public function custom_profile_user() {
    
    $element = array(
      '#markup' => 'Hello, world',
    );
    $content_submission = array(
      'account' => '',
    );
    $template = [
      '#theme' => 'profile_user',
      '#title' => 'Demo1',
      '#items' => $content_submission,
    ];
    
    return $template;
  }
  
  public function custom_notification_announcement($node_announcement_id) {
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $roles = array(
      'affiliate_member_local_',
      'ordinary_member',
      'affiliate_member_overseas_',
      'honorary_member',
      'exco_member_',
      '_technical_sub_committee_member',
      'education_sub_committee_member',
      'accounts_sub_committee_member',
      'social_sub_committee_member',
      'associate_member_reinsurer_local_',
      'associate_member_non_reinsurer_local_',
      'associate_member_reinsurer_overseas_',
      'associate_member_non_reinsurer_overseas__',
      'wire_sub_committee_member'
    );
    
    $content = array(
      'account' => '',
      'count_roles'=> count(array_intersect($user->getRoles(), $roles))
    );
    $template = [
      '#theme' => 'notification_announcement',
      '#title' => 'Notification',
      '#items' => $content,
    ];
    $config =\Drupal::config('custom.customsendemail');
    if (count(array_intersect($user->getRoles(), $roles)) > 0) {
      $node_storage = \Drupal::entityTypeManager()->getStorage('node');
      $node = $node_storage->load($node_announcement_id);
     
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'custom';
      $key = 'create_announcement';
      $to = $config->get('list_mail');
      $options = ['absolute' => TRUE];
      $url = \Drupal\Core\Url::fromRoute('entity.node.edit_form', ['node' => $node->id()], $options);
    
      $url = $url->toString();
      $params['message'] = 'Hi, <br>';
      $params['message'] .= 'There is an announcement has been created/updated by member. Please review using this link to approve / reject';
      $params['node_title'] = "Announcement created: New Post Approval on Member’s Announcement Page’ ";
      $params['node_link'] = $url;
      $params['nid'] = $node->id();
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $send = TRUE;
      $reply = NULL;
    
      $result = $mailManager->mail($module, $key, $to, $langcode, $params, $reply, $send);
      if ($result['result'] !== TRUE) {
        drupal_set_message(t('There was a problem sending your message and it was not sent.'), 'error');
      }
      else {
        drupal_set_message(t('Your message has been sent.'));
      }
    }
    return $template;
  }

  function custom_webform_submission_notification($webform_id, $submission_id, $user_new_id){
    $user = \Drupal\user\Entity\User::load($user_new_id);
    $roles = array(
      'affiliate_member_local_',
      'ordinary_member',
      'affiliate_member_overseas_',
      'honorary_member',
      'exco_member_',
      '_technical_sub_committee_member',
      'education_sub_committee_member',
      'accounts_sub_committee_member',
      'social_sub_committee_member',
      'associate_member_reinsurer_local_',
      'associate_member_non_reinsurer_local_',
      'associate_member_reinsurer_overseas_',
      'associate_member_non_reinsurer_overseas__',
      'wire_sub_committee_member'
    );
    
    $content = array(
      'account' => '',
      'count_roles'=> count(array_intersect($user->getRoles(), $roles))
    );
    $template = [
      '#theme' => 'webform_submission_notification',
      '#title' => 'Webform Submission Notification',
      '#items' => $content,
    ];
    if (count(array_intersect($user->getRoles(), $roles)) > 0) {
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'custom';
      $key = 'submission_notification';
      $to =  $user->getEmail();
      $options = ['absolute' => TRUE];
      $params['message'] = 'Hi , <br>';
      $params['message'] .= 'Please login using the link: '. user_pass_reset_url($user);
      $params['submisstion_title'] = "Announcement from SRA ";
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $send = TRUE;
      $reply = NULL;
       $result = $mailManager->mail($module, $key, $to, $langcode, $params, $reply, $send);
      if ($result['result'] !== TRUE) {
        drupal_set_message(t('There was a problem sending your message and it was not sent.'), 'error');
      }
      else {
        drupal_set_message(t('Your message has been sent.'));
      }
    }
    return $template;
  }
  function custom_send_email_notification(){
    $template = [
      '#theme' => 'send_email_notification',
      '#title' => 'Notification',
    ];
    return $template;
  }
}