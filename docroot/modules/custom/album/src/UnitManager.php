<?php

namespace Drupal\album;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\Discovery\YamlDiscovery;
use Drupal\Core\Plugin\Discovery\ContainerDerivativeDiscoveryDecorator;

/**
 * Contains \Drupal\album\UnitManager.
 */
class UnitManager extends DefaultPluginManager {

  /**
   * The cache key.
   *
   * @var string
   */
  protected $cacheKey;

  /**
   * An array of cache tags to use for the cached definitions.
   *
   * @var array
   */
  protected $cacheTags = [];

  /**
   * Name of the alter hook if one should be invoked.
   *
   * @var string
   */
  protected $alterHook;

  /**
   * @internal
   * The subdirectory within a namespace to look for plugins, or FALSE if the
   * plugins are in the top level of the namespace.
   *
   * @var string|bool
   */
  protected $subdir;

  /**
   * The module handler to invoke the alter hook.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The name of the annotation that contains the plugin definition.
   *
   * @var string
   */
  protected $pluginDefinitionAnnotationName;

  /**
   * The interface each plugin should implement.
   *
   * @var string|null
   */
  protected $pluginInterface;

  /**
   * An object that implements \Traversable which contains the root paths
   *
   * keyed by the corresponding namespace to look for plugin implementations.
   *
   * @var \Traversable
   */
  protected $namespaces;

  /**
   * Additional namespaces the annotation discovery mechanism should scan for
   * annotation definitions.
   *
   * @var string[]
   */
  protected $additionalAnnotationNamespaces = [];


  /**
   * Default values for each unit plugin.
   *
   * @var array
   */
  protected $defaults = [
    'id' => '',
    'label' => '',
    'unit' => '',
    'factor' => 0.00,
    'type' => '',
    'class' => 'Drupal\album\Unit',
  ];

  /**
   * Creates the discovery object.
   *
   * @param string|bool $subdir
   *   The plugin's subdirectory, for example Plugin/views/filter.
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   * @param string|null $plugin_interface
   *   (optional) The interface each plugin should implement.
   * @param string $plugin_definition_annotation_name
   *   (optional) The name of the annotation that contains the plugin definition.
   *   Defaults to 'Drupal\Component\Annotation\Plugin'.
   * @param string[] $additional_annotation_namespaces
   *   (optional) Additional namespaces to scan for annotation definitions.
   */
  public function __construct(CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    $this->moduleHandler = $module_handler;
    $this->setCacheBackend($cache_backend, 'physical_unit_plugins');
  }

  /*public function __construct($subdir, \Traversable $namespaces, ModuleHandlerInterface $module_handler, $plugin_interface = NULL, $plugin_definition_annotation_name = 'Drupal\Component\Annotation\Plugin', array $additional_annotation_namespaces = [], CacheBackendInterface $cache_backend) {
  $this->subdir = $subdir;
  $this->namespaces = $namespaces;
  $this->pluginDefinitionAnnotationName = $plugin_definition_annotation_name;
  $this->pluginInterface = $plugin_interface;
  $this->additionalAnnotationNamespaces = $additional_annotation_namespaces;
  $this->moduleHandler = $module_handler;
  $this->setCacheBackend($cache_backend, 'physical_unit_plugins');
  }*/

  /**
   * {@inheritdoc}
   */
  protected function getDiscovery() {
    if (!isset($this->discovery)) {
      $this->discovery = new YamlDiscovery('units', $this->moduleHandler->getModuleDirectories());
      $this->discovery = new ContainerDerivativeDiscoveryDecorator($this->discovery);
    }
    return $this->discovery;
  }

}
