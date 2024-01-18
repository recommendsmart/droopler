<?php

namespace Drupal\frontend_editing\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure frontend_editing settings for this site.
 */
class UiSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'frontend_editing_ui_settings';
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
    $form['ajax_content_update'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Ajax content update'),
      '#description' => $this->t('When enabled, the content after editing or adding entities will be updated via Ajax instead of a page refresh.'),
      '#default_value' => $config->get('ajax_content_update'),
    ];
    if ($config->get('ajax_content_update')) {
      $this->messenger()->addStatus($this->t('Ajax content update is enabled.'));
    }
    $exclude_fields = $config->get('exclude_fields') ? implode("\r\n", $config->get('exclude_fields')) : '';
    $form['exclude_fields'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Do not modify the following fields for ajax content update:'),
      '#description' => $this->t('List of fields of type entity reference/entity reference (revisions) that should not have an additional wrapper used for ajax content update. Use this setting in case your template is sensitive to markup or you get some properties directly from field render array variables. Put one field per line. Format: entity_type.bundle.field_name'),
      '#default_value' => $exclude_fields,
      '#states' => [
        'visible' => [
          ':input[name="ajax_content_update"]' => ['checked' => TRUE],
        ],
      ],
    ];
    $message = $this->t('When using ajax content update the html markup of entity reference (revisions) fields is changed slightly to have the reliable target for injecting the updated content. Potentially this can break the HTML on the page, therefor use "Exclude fields" setting or disable ajax content update completely if you experience any issues.');
    $this->messenger()->addWarning($message);
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('frontend_editing.settings');
    $config->set('ajax_content_update', (bool) $form_state->getValue('ajax_content_update'));
    $exclude_fields = $form_state->getValue('exclude_fields');
    $exclude_fields = explode("\r\n", $exclude_fields);
    $exclude_fields = array_filter($exclude_fields);
    $config->set('exclude_fields', $exclude_fields);
    $config->save();
    parent::submitForm($form, $form_state);
  }

}
