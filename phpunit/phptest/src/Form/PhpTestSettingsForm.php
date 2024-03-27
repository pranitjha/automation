<?php

namespace Drupal\phptest\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form Class.
 */
class PhpTestSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'phptest_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['phptest.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('phptest.settings');
    $form['dummy_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Add a dummy text to be stored in config'),
      '#default_value' => $config->get('dummy_text'),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('phptest.settings')
      ->set('dummy_text', $form_state->getValue('dummy_text'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
