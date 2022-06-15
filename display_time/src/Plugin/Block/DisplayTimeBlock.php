<?php

namespace Drupal\display_time\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\display_time\GetTime;

/**
 * Provides a Block to display time.
 *
 * @Block(
 *   id = "display_time_block",
 *   admin_label = @Translation("Display Time Block"),
 *   category = @Translation("Custom"),
 * )
 */
class DisplayTimeBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The config factory.
   *
   * @var Drupal\Core\Config\ConfigFactory.
   */
  protected $configFactory;

  /**
   * The GetTime service.
   *
   * @var Drupal\display_time\GetTime.
   */
  protected $time;

  /**
   * Constructor.
   * 
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param Drupal\Core\Config\ConfigFactory $config_factory.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactory $config_factory, GetTime $time) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
    $this->time = $time;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('display_time.get_time')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->configFactory->get('display_time.settings');
    $timezone_selected = !empty($config->get('timezone')) ? $config->get('timezone') : '';
    $country = !empty($config->get('country_name')) ? $config->get('country_name') : '';
    $city = !empty($config->get('city_name')) ? $config->get('city_name') : '';
    // Call custom service to render time based on timezone selected.
    if ($timezone_selected) {
      $output_time = $this->time->getTime($timezone_selected);
    }
    return [
      '#theme' => 'display_time',
      '#country' => $country,
      '#city' => $city,
      '#output_time' => $output_time,
      '#cache' => [
        'tags' => $config->getCacheTags(),
      ],
    ];
  }
}
