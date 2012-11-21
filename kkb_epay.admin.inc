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
  $form['key_data'] = array(
    '#tree' => TRUE,
  );

  $form['key_data']['certificate_id'] = array(
    '#type' => 'textfield',
    '#title' => t('Certificate ID:'),
    '#size' => 20,
    '#maxlegnth' => 10,
    '#required' => TRUE,
  );

  $form['key_data']['merchant_name'] = array(
    '#type' => 'textfield',
    '#title' => t('Merchant name:'),
    '#size' => 60,
    '#maxlegnth' => 255,
    '#required' => TRUE,
  );

  $form['key_data']['merchant_id'] = array(
    '#type' => 'textfield',
    '#title' => t('Merchant ID:'),
    '#size' => 20,
    '#maxlegnth' => 8,
    '#required' => TRUE,
  );

  $form['key_data']['password'] = array(
    '#type' => 'textfield',
    '#title' => t('Private key password:'),
    '#size' => 60,
    '#maxlegnth' => 255,
    '#required' => TRUE,
  );

  $form['key_data']['key'] = array(
    '#type' => 'textarea',
    '#title' => t('Private key:'),
    '#cols' => 80,
    '#rows' => 20,
    '#required' => TRUE,
  );

  $form['actions'] = array(
    '#type' => 'actions',
    'submit' => array(
      '#type' => 'submit',
      '#value' => t('Import new private key'),
    ),
  );

  $form['#validate'] = array('kkb_epay_admin_private_key_form_validate');
  $form['#submit'] = array('kkb_epay_admin_private_key_form_submit');

  return $form;
}

/**
 * Form handler validates kkb_epay_admin_private_key_form.
 *
 * The KkbEpay_Key model is capable of self-validation and since its
 * constraints can be quite complicated, it would be inefficient to repeat all
 * of them here. That is why we will create an instance of KkbEpay_Key class,
 * set it with inputed values and catch any validation error.
 */
function kkb_epay_admin_private_key_form_validate($form, &$form_state) {
  $key = new KkbEpay_Key();

  $fields = array(
    'key', 'password', 'merchant_id', 'merchant_name', 'certificate_id',
  );
  foreach ($fields as $f) {
    $method = 'set' . str_replace('_', '', $f);

    try {
      $key->$method($form_state['values']['key_data'][$f]);
    }
    catch (KkbEpay_KeyException $ex) {
      form_set_error("key_data][$f", t($ex->getMessage()));
    }
  }

  if (!$key->isValid()) {
    form_set_error('key_data', t('There is something wrong with this key. Please, check that the provided information is correct.'));
  }
}

/**
 * Form handler submits kkb_epay_admin_private_key_form.
 *
 * @todo
 *   It would be much better to write packed private key directly to the
 *   settings.php file and not ask user to do it manually.
 */
function kkb_epay_admin_private_key_form_submit($form, &$form_state) {
  $packed = _kkb_epay_pack_private_key($form_state['values']['key_data']);

  $_SESSION['kkb_epay']['packed_private_key'] = $packed;
  $form_state['redirect'] = 'admin/config/services/epay/save-private-key';
}

/**
 * Menu page callback. Displayes previously imported private key in a packaged
 * format and gives futher instructions to the user.
 */
function kkb_epay_admin_private_key_save_page() {
  if (empty($_SESSION['kkb_epay']['packed_private_key'])) {
    drupal_goto('admin/config/services/epay/private-key');
  }

  $conf = "\$conf['kkb_epay_private_key_live'] = <<<ENDPRIVATEKEY\n";
  $conf .= $_SESSION['kkb_epay']['packed_private_key'];
  $conf .= "\nENDPRIVATEKEY;\n\n";

  $page = array();
  $page['help'] = array(
    array(
      '#prefix' => '<p>',
      '#markup' => t('<strong>Your private key has been imported, but it was not yet saved.</strong> These instructions will tell you how to do it.'),
      '#suffix' => '</p>',
    ),
    array(
      '#prefix' => '<p>',
      '#markup' => t('The private key must be kept <strong>in secret</strong> because anyone who has it can perform financial operations on your behalf, for example, debit any credit cards, cancel payments, transfer money from your account, etc.'),
      '#suffix' => '</p>',
    ),
    array(
      '#prefix' => '<p>',
      '#markup' => t(
        'Copy the code below. Do not modify it! Place it at the end of the <strong>@file</strong> file and save the file.',
        array('@file' => conf_path() . '/settings.php')
      ),
      '#suffix' => '</p>',
    ),
  );

  $page['configuration'] = array(
    '#type' => 'textarea',
    '#title' => t('Place this code into your settings.php file:'),
    '#value' => $conf,
    '#rows' => 20,
    '#cols' => 80,
    '#attributes' => array(
      'readonly' => 'readonly',
    ),
  );

  return $page;
}

