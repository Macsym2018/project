<?php

namespace Drupal\album\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormBuilderInterface;

/**
 * Defines AlbumController class.
 */
class AlbumController extends ControllerBase {

  /**
   * FormBuilderInterface instance.
   *
   * @var \Form\FormBuilderInterface
   */

  protected $blockBuilder;

  /**
   * AlbumController constructor.
   *
   * @param \Drupal\Form\FormBuilderInterface $form_builder
   *   \Drupal\album\AlbumService instance.
   */
  public function __construct(FormBuilderInterface $form_builder) {
    $this->blockBuilder = $form_builder;
  }

  /**
   * Creates an instance of the plugin.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container to pull out services used in the plugin.
   *
   * @return static
   *   Returns an instance of this plugin.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('form_builder'),
    );
  }

  /**
   * Return Content.
   *
   * @return array
   *   Return text
   */
  public function content() {

    return [
      '#theme' => 'album_template',
      '#data' => 'Hello!',
    ];
  }

  /**
   * Return Content.
   *
   * @return array
   *   Return text
   */
  public function formContent() {

    $form = $this->blockBuilder->getForm('Drupal\album\Form\PhotosForm');
    $form['#attached']['library'][] = 'photos/global-styling';

    return [
      '#theme' => 'photos_template',
      '#form' => $form,
    ];
  }

}
