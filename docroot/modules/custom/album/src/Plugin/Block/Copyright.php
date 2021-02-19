<?php

namespace Drupal\album\Plugin\Block;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Provides a 'Copyright' Block.
 *
 * @Block(
 * id = "copyright_block",
 * admin_label = @Translation("Copyright"),
 * category = @Translation("Custom")
 * )
 */
class Copyright extends BlockBase implements ContainerFactoryPluginInterface {


  /**
   * RouteMatchInterface instance.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */

  protected $routeMatch;

  /**
   * Copyright block constructor.
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
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   \Drupal\album\AlbumService instance.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RouteMatchInterface $route_match) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->routeMatch = $route_match;
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
      $container->get('current_route_match')
    );
  }

  /**
   * Render text on block.
   */
  public function build() {
    $date = new \DateTime();
    return [
      '#markup' => t('Copyright @year&copy; @company', [
        '@year' => $date->format('Y'),
        '@company' => $this->configuration['company_name'],
      ]),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'company_name' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    $form['company_name'] = [
      '#type' => 'textfield',
      '#title' => t('Company name'),
      '#default_value' => $this->configuration['company_name'],
    ];
    return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {

    // Load the manager service.
    $unit_manager = \Drupal::service('plugin.manager.unit');
    // Create a class instance through the manager.
    $feet_instance = $unit_manager->createInstance('feet');
    // Convert 12ft into meters.
    $meters_value = $feet_instance->toBase(12);

    $this->configuration['company_name'] = $form_state->getValue('company_name');
    $this->configuration['company_name'] = $meters_value;
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    $route_name = $this->routeMatch->getRouteName();
    if ($account->isAnonymous() && !in_array($route_name,
        ['user.login', 'user.logout'])) {
      return AccessResult::allowed()->addCacheContexts(['route.name',
        'user.roles:anonymous',
      ]);
    }
    return AccessResult::forbidden();
  }

}
