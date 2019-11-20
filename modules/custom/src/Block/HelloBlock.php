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
    return array (
      '#markup' => $this->t('12344 name!'),
    );
  }
}