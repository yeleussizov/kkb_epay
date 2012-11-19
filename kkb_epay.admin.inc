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
    '#description' => t('When debugging mode is turned on, all payments are processed by a sandbox gateway. Every payment is successful, but customers are not billed. Make sure to turn debugging off, as soon as you finish testing. You risk loosing money if real clients make payments while it is on.'),
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
 * Form builder. Private key configuration form.
 */
function kkb_epay_admin_private_key_form($form, &$form_state) {
  //
  // STUB TODO
  //
  return $form;
}

