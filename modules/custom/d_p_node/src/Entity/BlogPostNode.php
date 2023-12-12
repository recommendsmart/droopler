<?php

declare(strict_types = 1);

namespace Drupal\d_p_node\Entity;

use Drupal\d_p_node\NodeImageInterface;
use Drupal\d_p_node\NodeImageTrait;
use Drupal\node\Entity\Node;

/**
 * Provides additional functionality for blog post nodes.
 */
class BlogPostNode extends Node implements NodeImageInterface {
  use NodeImageTrait;

  /**
   * {@inheritdoc}
   */
  public function getImageFieldName(): string {
    return 'field_blog_media_main_image';
  }

}
