<?php

namespace Drupal\phptest\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements an example form with dynamic select elements.
 */
class DynamicSelectsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dynamic_selects_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['first_select'] = [
      '#type' => 'select',
      '#title' => $this->t('First Select'),
      '#options' => [
        '' => $this->t('- None -'),
        'option1' => $this->t('Option 1'),
        'option2' => $this->t('Option 2'),
      ],
      '#ajax' => [
        'callback' => '::updateSecondSelect',
        'wrapper' => 'second-select-wrapper',
        'event' => 'change',
      ],
    ];

    // Container for the second select. It will be replaced via AJAX.
    $form['second_select_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'second-select-wrapper'],
    ];

    // Determine the currently selected value, if any.
    $selected_option = $form_state->getValue('first_select', '');

    // If an option has been selected, build the second select.
    $form['second_select_wrapper']['second_select'] = [
      '#type' => 'select',
      '#title' => $this->t('Second Select'),
      '#required' => TRUE,
      '#options' => $selected_option ? $this->getSecondSelectOptions($selected_option) : [],
    ];

    return $form;
  }

  /**
   * AJAX callback handler for the first select element.
   *
   * @param array $form
   *   The form render array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   *
   * @return array
   *   The form element to update via AJAX.
   */
  public function updateSecondSelect(array &$form, FormStateInterface $form_state) {
    return $form['second_select_wrapper'];
  }

  /**
   * Returns options for the second select element based on the first selection.
   *
   * @param string $selected_option
   *   The value of the first select element.
   *
   * @return array
   *   An associative array of options for the second select element.
   *   The keys are option values and the values are display strings.
   */
  protected function getSecondSelectOptions($selected_option) {
    // You would generally retrieve these options from a service or database.
    $options = [
      'option1' => [
        'sub_option1' => $this->t('Sub Option 1'),
        'sub_option2' => $this->t('Sub Option 2'),
      ],
      'option2' => [
        'sub_option3' => $this->t('Sub Option 3'),
        'sub_option4' => $this->t('Sub Option 4'),
      ],
    ];

    return isset($options[$selected_option]) ? $options[$selected_option] : [];
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // You can add custom validation for your form elements here.
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Handle the submission of the form and do something with the values.
  }

}
