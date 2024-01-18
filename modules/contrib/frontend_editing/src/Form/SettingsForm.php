<?php

namespace Drupal\frontend_editing\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure frontend_editing settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'frontend_editing_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['frontend_editing.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('frontend_editing.settings');

    $form['width_settings'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Width settings'),
    ];

    $form['width_settings']['sidebar_width'] = [
      '#title' => $this->t('Sidebar width'),
      '#type' => 'number',
      '#default_value' => $config->get('sidebar_width') ?? 30,
      '#description' => $this->t('Set the width of the editing sidebar when it opens. Minimum width is 30%.'),
      '#min' => 30,
      '#max' => 40,
      '#required' => TRUE,
    ];

    $form['width_settings']['full_width'] = [
      '#title' => $this->t('Full width'),
      '#type' => 'number',
      '#default_value' => $config->get('full_width') ?? 70,
      '#description' => $this->t('Set the width of the editing sidebar when it is expanded. Minimum width is 50%.'),
      '#min' => 50,
      '#max' => 95,
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('frontend_editing.settings')
      ->set('sidebar_width', $form_state->getValue('sidebar_width'))
      ->set('full_width', $form_state->getValue('full_width'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
