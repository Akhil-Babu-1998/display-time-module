<?php

namespace Drupal\display_time\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a form to configure location and timezones.
 * 
 * @internal
 */
class LocationConfigurationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'display_time_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['display_time.settings'];
  }

  /** 
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('display_time.settings');
    $timezones = [
      'America/Chicago' => $this->t('America/Chicago'),
      'America/New_York' => $this->t('America/New_York'),
      'Asia/Tokyo' => $this->t('Asia/Tokyo'),
      'Asia/Dubai' => $this->t('Asia/Dubai'),
      'Asia/Kolkata' => $this->t('Asia/Kolkata'),
      'Europe/Amsterdam' => $this->t('Europe/Amsterdam'),
      'Europe/Oslo' => $this->t('Europe/Oslo'),
      'Europe/London' => $this->t('Europe/London')
    ];

    $form['country_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Please enter country name.'),
      '#required' => TRUE,
      '#default_value' => $config->get('country_name')
    ];

    $form['city_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Please enter city name.'),
      '#required' => TRUE,
      '#default_value' => $config->get('city_name')
    ];

    $form['timezone'] = [
      '#type' => 'select',
      '#options' => $timezones,
      '#title' => $this->t('Please select the timezone.'),
      '#required' => TRUE,
      '#default_value' => $config->get('timezone')
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('display_time.settings');
    foreach ($form_state->getValues() as $key => $value) {
      $config->set($key, $value);
    }
    $config->save();
    parent::submitForm($form, $form_state);
  }
}