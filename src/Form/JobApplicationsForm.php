<?php
/**
 * @file
 * Contains \Drupal\job_applications\Form\JobApplicationsForm.
 */
namespace Drupal\job_applications\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides an Job App Email form.
 */
class JobApplicationsForm extends FormBase {

  /**
   * (@inheritdoc)
   */
  public function getFormId() {
    return 'job_applications_form';
  }

  /**
   * (@inheritdoc)
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['name'] = array(
      '#title' => $this->t('Name'),
      '#type' => 'textfield',
      '#size' => 32,
      '#required' => TRUE,
    );

    $form['email'] = array(
      '#title' => $this->t('Email'),
      '#type' => 'textfield',
      '#size' => 32,
      '#required' => TRUE,
    );

    // Job type dropdown options.
    $type_options = static::getTypeDropdownOptions();

    // Check if the value of job type already exist.
    if (empty($form_state->getValue('type'))) {
      // Use a default value.
      $selected_type = key($type_options);
    }
    else {
      // Get the value if it already exists.
      $selected_type = $form_state->getValue('type');
    }

    $form['type'] = array(
      '#type' => 'select',
      '#title' => $this->t('Type'),
      '#options' => $type_options,
      '#default_value' => $selected_type,
      '#ajax' => array(
        'event' => 'change',
        'callback' => '::technologyDropdownCallback',
        'wrapper' => 'technology-container',
      ),
    );

    // Container for technology dropdown.
    $form['technology_container'] = array(
      '#type' => 'container',
      '#attributes' => array(
        'id' => 'technology-container'
      ),
    );

    $form['technology_container']['technology'] = array(
      '#type' => 'select',
      '#title' => $this->t('Technology'),
      '#options' => static::getTechnologyDropdownOptions($selected_type),
    );

    $form['message'] = array(
      '#title' => $this->t('Message'),
      '#type' => 'textarea',
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Send'),
    );

    return $form;
  }

  /**
   * Provide a new dropdown based on the AJAX call.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   *
   * @return array
   *   The portion of the render structure that will replace the
   *   technology-dropdown-replace form element.
   */
  public function technologyDropdownCallback(array $form) {
    return $form['technology_container'];
  }

  /**
   * (@inheritdoc)
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $value = $form_state->getValue('email');
    if ( $value ==! \Drupal::service('email.validator')->isValid($value) ) {
      $form_state->setErrorByName( 'email', $this->t('The email address %mail is not valid.', array('%mail' => $value)) );
    }
  }

  /**
   * (@inheritdoc)
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $name = $form_state->getValue('name');
    $email = $form_state->getValue('email');
    $type = $form_state->getValue('type');
    $technology = $form_state->getValue('technology');
    $message = $form_state->getValue('message');

    // Send mail to site mail.
    $mailManager = \Drupal::service('plugin.manager.mail');
    $module = 'job_applications';
    $key = 'job_applications_send_mail';
    $to = \Drupal::config('system.site')->get('mail');
    $params['message'] = static::formatMessage($name, $email, $type, $technology, $message);
    $params['subject'] = $this->t('@name job application', array('@name' => $name));
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $send = true;
    $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);

    if ($result['result'] !== true) {
      $this->messenger()->addError($this->t('There was a problem sending your message and it was not sent.'), 'error');
    }
    else {
      $this->messenger()->addStatus($this->t('Your message has been sent.'));
    }

    // Save data to db table 'job_applications'.
    $field_arr = array(
      'name' => $name,
      'email' => $email,
      'type' => $type,
      'technology' => $technology,
      'message' => $message,
      'created_at' => time(),
    );
    $query = \Drupal::database();
    $query->insert('job_applications')
          ->fields($field_arr)
          ->execute();
    $this->messenger()->addStatus($this->t('Your job application have been submitted.'));

  }

  /**
   * Helper function to populate the Type dropdown.
   *
   * @return array
   *   Dropdown options.
   */
  public static function getTypeDropdownOptions() {
    return array(
      'Backend' => 'Backend',
      'Frontend' => 'Frontend'
    );
  }

  /**
   * Helper function to populate the Technology dropdown.
   *
   * @param string $key
   *   This will determine which set of options is returned.
   *
   * @return array
   *   Dropdown options.
   */
  public static function getTechnologyDropdownOptions($key = '') {
    switch ($key) {
      case 'Backend':
        $options = array(
          'PHP' => 'PHP',
          'Java' => 'Java'
        );
        break;
      case 'Frontend':
        $options = array(
          'AngularJS' => 'AngularJS',
          'ReactJS' => 'ReactJS'
        );
        break;
      default:
        $options = array('none' => 'none');
        break;
    }
    return $options;
  }

  /**
   * Helper function to format message for email.
   *
   * @param string $name
   * @param string $email
   * @param string $type
   * @param string $technology
   * @param string $message
   *
   * @return string
   *   Return formated message.
   */
  public static function formatMessage($name, $email, $type, $technology, $message) {
    $formated_message = 'Name: ' . $name . '<br>'
                      . 'Email: '. $email . '<br>'
                      . 'Type: '. $type . '<br>'
                      . 'Technology: '. $technology . '<br>'
                      . 'Message: '. $message . '.';
    return $formated_message;
  }

}
