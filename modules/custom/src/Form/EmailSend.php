<?php

namespace Drupal\custom\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class EmailSend.
 */
class EmailSend extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'custom.customsendemail',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_email_send_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('custom.customsendemail');
    $form['list_mail'] = [
      '#type' => 'textfield',
      '#title' => $this->t('List email'),
      '#description' => $this->t('Enter list email'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('list_mail'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('custom.customsendemail')
      ->set('list_mail', $form_state->getValue('list_mail'))
      ->save();
  }

}