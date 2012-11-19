<?php

/**
 * @file kkb_epay.admin.inc
 *   Admin page callback.
 */

/**
 * Form builder. Debugging mode controll form.
 */
function kkb_epay_admin_debug_form($form, &$form_state) {
  $form['#theme'] = 'system_settings_form';

  $form['kkb_epay_debug_status'] = array(
    '#type' => 'checkbox',
    '#title' => t('Turn on KKB Epay debugging mode'),
    '#default_value' => kkb_epay_debug_status(),
    '#description' => t('When debugging mode is turned on, all payments are processed by a sandbox gateway. Every payment is successful, but customers are not billed. Make sure to turn debugging off as soon as you finish testing. You risk loosing money if real clients make payments while it is on.'),
  );
  $form['password_confirmation'] = array(
    '#type' => 'password',
    '#title' => t('Provide your password to save changes:'),
    '#required' => TRUE,
  );

  $form['actions'] = array(
    '#type' => 'actions',
    'submit' => array(
      '#type' => 'submit',
      '#value' => t('Save configuration'),
    ),
  );

  $form['#validate'] = array('kkb_epay_admin_debug_form_validate');
  $form['#submit'] = array('kkb_epay_admin_debug_form_submit');
  return $form;
}

/**
 * Form handler validates kkb_epay_admin_debug_form.
 */
function kkb_epay_admin_debug_form_validate($form, &$form_state) {
  global $user;
  require_once DRUPAL_ROOT . '/' . variable_get('password_inc', 'includes/password.inc');

  if (!user_check_password($form_state['values']['password_confirmation'], $user)) {
    form_set_error('password_confirmation', t('Your password is incorrect.'));
  }
}

/**
 * Form handler submits kkb_epay_admin_debug_form.
 */
function kkb_epay_admin_debug_form_submit($form, &$form_state) {
  $new_status = (bool) $form_state['values']['kkb_epay_debug_status'];
  variable_set('kkb_epay_debug_status', $new_status);

  if ($new_status) {
    drupal_set_message(t('Debugging mode has been turned on. Turn it off as soon as possible.'), 'warning');
  }
  else {
    drupal_set_message(t('Debugging mode has been turned off.'));
  }
}

/**
 * Form builder. Private key configuration form.
 */
function kkb_epay_admin_private_key_form($form, &$form_state) {
  //
  // STUB TODO
  //
  return $form;
}

