<?php

namespace Drupal\album\Plugin\Block;

use Drupal\album\AlbumService;
use Drupal\Core\Block\BlockBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'Album' Block.
 *
 * @Block(
 *   id = "album",
 *   admin_label = @Translation("Album block"),
 *   category = @Translation("Hello World"),
 * )
 */
class AlbumBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Guzzle\Client instance.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * Album block constructor.
   *
   * @param array $configuration
   *   The plugin configuration, i.e. an array with configuration values keyed
   *   by configuration option name. The special key 'context' may be used to
   *   initialize the defined contexts by setting it to an array of context
   *   values keyed by context names.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\album\AlbumService $http_client
   *   \Drupal\album\AlbumService instance.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, AlbumService $http_client) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->httpClient = $http_client;
  }

  /**
   * Creates an instance of the plugin.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container to pull out services used in the plugin.
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   *
   * @return static
   *   Returns an instance of this plugin.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('album.album')
    );
  }

  /**
   * Function that form content of block.
   *
   * @return array
   *   Return content.
   */
  public function build() {

    $config = $this->getConfiguration();

    if (isset($config['selected_album'])) {

      /*$albumId = $config['selected_album'];
      $photos = $this->httpClient->getAlbumPhotos($albumId);*/

      return [
        '#theme' => 'album_template',
        '#data' => 'test variable',
      ];

    }
    else {
      return [
        '#markup' => $this->t("Album did not select"),
      ];
    }
  }

  /**
   * Generates fields for block configuration.
   *
   * @return array
   *   Array of form fields.
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();
    $albums = $this->httpClient->getAlbums();
    $options = [];

    foreach ($albums as $p) {
      $options[$p->id] = $p->title;
    }

    $form['selected_album'] = [
      '#type' => 'select',
      '#title' => $this->t('Select album'),
      '#options' => $options,
      '#default_value' => isset($config['selected_album']) ? $config['selected_album'] : '',
    ];

    $form['new_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('You can change the name for selected album'),

    ];

    return $form;
  }

  /**
   * Adds block type-specific submission handling for the block form.
   *
   * @param mixed $form
   *   The form definition array for the full block configuration form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);

    $values = $form_state->getValues();
    $this->configuration['selected_album'] = $values['selected_album'];
  }

}
