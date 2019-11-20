<?php

namespace Drupal\custom\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class customController extends ControllerBase {
    
    /**
     * page get data render template print pdf
     */
    public function custom_submission_template_pdf($submission_id){
        $webform_submission = \Drupal\webform\Entity\WebformSubmission::load($submission_id);
        if(empty($webform_submission) || $webform_submission == NULL) {
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
            'full_name_of_company'             => $full_name_of_company,
            'company_registration_no'          => $company_registration_no,
            'date_of_incorporation_of_company' => $date_of_incorporation_of_company,
            'address'                          => $address,
            'name_designation'                 => $name_designation,
            'logo'                             => $logo,
        );
        
        $template = [
            '#theme' => 'submission_pdf_invoice',
            '#title' => 'Demo1',
            '#items' => $content_submission,
        ];
        
        return $template;
    }
    
    public function custom_submission_send_mail($submission_id){
        
        $element = array(
            '#markup' => 'Hello, world',
        );
        // $webform_submission = \Drupal\webform\Entity\WebformSubmission::load($submission_id);
        // module_load_include('inc', 'custom', 'includes/custom.mail');
        // $test = custom_email_confirm_invoice($submission_id);
        // return $test;
        return $element;
    }
    
    public function custom_profile_user(){
    
        $element = array(
            '#markup' => 'Hello, world',
        );
        // $account = \Drupal\user\Entity\User::load($user_id); // pass your uid
        // $photo = $account->get('user_picture');
        // dsm($account);
        // dsm($account->user_picture->entity->getFileUri());
        $content_submission =array(
            'account'=> ''
        );
        $template = [
            '#theme'   => 'profile_user',
            '#title'   => 'Demo1',
            '#items'   => $content_submission,
        ];
    
        return $template;
    }
}