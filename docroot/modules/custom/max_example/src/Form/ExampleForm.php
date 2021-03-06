<?php

/**
 * @file
 * Contains \Drupal\max_example\Form\ExampleForm.
 */

namespace Drupal\max_example\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class ExampleForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['drupalform.company'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'any_custom_form_id';
  }

  /**
   * {@inheritdoc}
   */
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['company_name'] = array(
    '#type' => 'textfield',
      '#title' => t('Company name'),
      '#default_value' => $this->config('drupalform.company')->get('company_name'),
    );
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validation covered in later recipe, required to satisfy interface

    if (!$form_state->isValueEmpty('company_name')) {
      if (strlen($form_state->getValue('company_name')) <= 5) {
        $form_state->setErrorByName('company_name', t ('Company name is less than 5 characters'));
      }
    }

  }

  /**
   * {@inheritdoc}
   */
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $this->config('drupalform.company')->set('name', $form_state->getValue('company_name'));
  }

}

