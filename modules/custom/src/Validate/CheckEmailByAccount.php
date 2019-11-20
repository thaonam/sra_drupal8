<?php
namespace Drupal\custom\Validate;

use Drupal\Core\Form\FormStateInterface;

/**
 * Form API callback. Validate element value.
 */
class CheckEmailByAccount {
  /**
   * Validates given element.
   *
   * @param array              $element      The form element to process.
   * @param FormStateInterface $formState    The form state.
   * @param array              $form The complete form structure.
   */
  public static function validate(array &$element, FormStateInterface $formState, array &$form) {
    $webformKey = $element['#webform_key'];
    $value = $formState->getValue($webformKey);
    $requiredValue = $element['#required_value'];
    if ($value != '') {
      if (user_load_by_mail($value) != FALSE or user_load_by_name($value) != FALSE) {
        $tArgs = [
          '%name' => empty($element['#title']) ? $element['#parents'][0] : $element['#title'],
          '%value' => $value,
        ];
        $formState->setError(
          $element,
          t('This email %value already exists in the user system. Please enter another email.', $tArgs)
        );
      }
    }
  }
}