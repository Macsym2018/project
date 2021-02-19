<?php

/**
 * @file
 * Contains \Drupal\album\Entity\SiteAnnouncementInterface.
 */
namespace Drupal\album\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

interface SiteAnnouncementInterface extends ConfigEntityInterface
{
 /**
  * Gets the message value.
  *
  * @return string
  */
  public function getMessage();
}
