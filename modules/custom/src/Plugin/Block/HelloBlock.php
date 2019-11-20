<?php

namespace Drupal\custom\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Hello' Block.
 *
 * @Block(
 *   id = "hello_block",
 *   admin_label = @Translation("Hello block"),
 *   category = @Translation("Hello World"),
 * )
 */
class HelloBlock extends BlockBase {
  
  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();
    $account = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $name = $account->get('name')->value;
    return array (
      '#markup' => $this->t('<div class="members-menu">
            <p class="welcome">You are logged as, <strong>@name</strong></p></div>', array (
        '@name' => $name,
      )),
    );
  }
}