<?php

namespace Drupal\ten24ym_redirectanon\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure LaCAS settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ten24ym_redirectanon_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['ten24ym_redirectanon.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['path'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Redirect path'),
      '#default_value' => $this->config('ten24ym_redirectanon.settings')->get('path'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('ten24ym_redirectanon.settings')
      ->set('path', $form_state->getValue('path'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
