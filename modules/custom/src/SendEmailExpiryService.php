<?php
namespace Drupal\custom;
use Drupal\Core\Entity\EntityTypeManager;

class SendEmailExpiryService {
  protected $entityTypeManager;
  public function __construct(EntityTypeManager $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }
  public function load() {
    $storage = $this->entityTypeManager->getStorage('node');
    $query = $storage->getQuery();

    // Get all Member_companiesower node IDs.
    $nids = $query->condition('type', 'member_companies')->execute();
    // Load all Member_companies nodes.
    $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);
    $arr_node_id = array();

    foreach ($nodes as $node) {
      if (!empty($node->get('field_expiry_date')->value)) {
        if (strtotime("+30 days") == strtotime($node->get('field_expiry_date')->value)) {
          $arr_node_id[] = $node;
        }
      }
    }
    return $arr_node_id;
  }
}