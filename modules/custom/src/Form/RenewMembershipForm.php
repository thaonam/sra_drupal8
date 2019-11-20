<?php
namespace Drupal\custom\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;
use Drupal\Core\Routing\RouteMatchInterface;
/**
 * Implements a simple form.
 */
class RenewMembershipForm extends FormBase {

  /**
   * Build the simple form.
   *
   * @param array $form
   *   Default form array structure.
   * @param FormStateInterface $form_state
   *   Object containing current form state.
   * @param NodeInterface $node_member_companies => ID node in content tyle
   *   "Member Companies"
   *
   * @return $form
   */
  public function buildForm(array $form, FormStateInterface $form_state, NodeInterface $node_member_companies = NULL) {
    $form['member_companies'] = array(
      '#type' => 'hidden',
      '#value' => $node_member_companies->id(),
    );

    // Group submit handlers in an actions element with a key of "actions" so
    // that it gets styled correctly, and so that other modules may add actions
    // to the form. This is not required, but is convention.
    $form['actions'] = [
      '#type' => 'actions',
    ];

    // Add a submit button that handles the submission of the form.
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Renew Membership'),
    ];

    return $form;
  }

  /**
   * Getter method for Form ID.
   *
   * The form ID is used in implementations of hook_form_alter() to allow other
   * modules to alter the render array built by this form controller.  it must
   * be unique site wide. It normally starts with the providing module's name.
   *
   * @return string
   *   The unique ID of the form defined by this class.
   */
  public function getFormId() {
    return 'custom_renew_membership_form';
  }

  /**
   * Implements form validation.
   *
   * The validateForm method is the default method called to validate input on
   * a form.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param FormStateInterface $form_state
   *   Object describing the current state of the form.
   * @param NodeInterface $node_member_companies => ID node in content tyle
   *   "Member Companies"
   */
  public function validateForm(array &$form, FormStateInterface $form_state, NodeInterface $node_member_companies = NULL) {
    $node_member_companies_detail = \Drupal\node\Entity\Node::load($node_member_companies);
    if (!empty($node_member_companies_detail)) {
      if ($node_member_companies_detail->getType() != 'member_companies') {
        $form_state->setErrorByName('title', $this->t('Unable to send email!'));
      }
    }
  }

  /**
   * Implements a form submit handler.
   *
   * The submitForm method is the default method called for any submit elements.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param FormStateInterface $form_state
   *   Object describing the current state of the form.
   * @param NodeInterface $node_member_companies => ID node in content tyle
   *   "Member Companies"
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /*
     * This would normally be replaced by code that actually does something
     * with the title.
     */
    $config =\Drupal::config('custom.customsendemail');
    $node_member_companies = $form_state->getValue('member_companies');
    $node_member_companies_detail = \Drupal\node\Entity\Node::load($node_member_companies);
    global $base_url;
    if (!empty($node_member_companies_detail)) {
      // Global User Login
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
        'wire_sub_committee_member',
      );
      $roles_admin = array('webmaster', 'siteadmin', 'administrator');
      $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());

      if (count(array_intersect($user->getRoles(), $roles)) > 0) {
        $options = ['absolute' => TRUE];
        $url = \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' => $node_member_companies], $options);
        $url = $url->toString();
        $mailManager = \Drupal::service('plugin.manager.mail');
        $module = 'custom';
        $key = 'submission_notification';
        $to = $config->get('list_mail');
        $options = ['absolute' => TRUE];
        $params['message'] = 'Hi Administration, <br>';
        $params['message'] .= 'Member "A" wants to renew membership. Please visit this link to renew your membership: ';
        $params['message'] .= $url;
        $langcode = \Drupal::currentUser()->getPreferredLangcode();
        $send = TRUE;
        $reply = NULL;
        $result = $mailManager->mail($module, $key, $to, $langcode, $params, $reply, $send);
        if ($result['result'] !== TRUE) {
 
          drupal_set_message(t('There was a problem sending your message and it was not sent.'), 'error');
        } else {
          $node_member_companies_detail->set('field_renewal', 1);
          $node_member_companies_detail->save();
          $path = $base_url . '/send/notification';
          $response = new RedirectResponse($path);
          $response->send();
     
          drupal_set_message(t('Your message has been sent.'));
        }
      }

      //User is site-admin
      if (count(array_intersect($user->getRoles(), $roles_admin)) > 0) {
        
        if ($node_member_companies_detail->get('field_renewal')->value == '1') {
          $node_member_companies_detail->set('field_expiry_date', date("Y-m-d", strtotime($node_member_companies_detail->get('field_expiry_date')->value . " + 1 year")));
          $node_member_companies_detail->set('field_renewal', 0);
          if ($node_member_companies_detail->save()) {
            $options = ['absolute' => TRUE];
            $url = \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' => $node_member_companies], $options);
            $url = $url->toString();
            $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
            $mailManager = \Drupal::service('plugin.manager.mail');
            $module = 'custom';
            $key = 'submission_notification';
            $to = $node_member_companies_detail->getOwner()->getEmail();
            $options = ['absolute' => TRUE];
            $params['message'] = 'Hi ' . $node_member_companies_detail->getTitle() . ', <br>';
            $params['message'] .= 'We approve and renew your membership. ';
            $params['message'] .= $url;
            $langcode = \Drupal::currentUser()->getPreferredLangcode();
            $send = TRUE;
            $reply = NULL;
            $result = $mailManager->mail($module, $key, $to, $langcode, $params, $reply, $send);
            if ($result['result'] !== TRUE) {
              drupal_set_message(t('There was a problem sending your message and it was not sent.'), 'error');
            } else {
              $path = $base_url . '/send/notification';
              $response = new RedirectResponse($path);
              $response->send();
              drupal_set_message(t('Your message has been sent.'));
            }
          }
        }
      }
    } else {
      $title = $form_state->getValue('member_companies');
      drupal_set_message($this->t('There was a problem sending your message and it was not sent. 1234 %node', ['%node' => $title]), 'error');
    }
  }
}