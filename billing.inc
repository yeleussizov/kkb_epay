<?php

/**
 * @file billing.inc
 *   Main set of tools to work with the processing gateway.
 */

/**
 * Loads current private key with the default Kkb_DrupalKeyLoader.
 */
function kkb_epay_load_default_key() {
  static $key = NULL;
  if (!isset($key)) {
    $loader = new Kkb_DrupalKeyLoader();
    $loader->setDebug(kkb_epay_debug_status());
    $key = $loader->getKey();
  }
  return $key;
}

/**
 * Signs a message with the default key.
 *
 * @return string|NULL
 *   Returnes NULL if any errors happen. Otherwise, returns base64-encoded
 *   signature.
 */
function kkb_epay_sign_message($message) {
  $loader = new KkbEpay_DrupalKeyLoader();
  $loader->setDebug(kkb_epay_debug_status());

  if (!$loader->validateKey()) {
    drupal_set_message(t('Private key could not be loaded. Message will not be signed.'), 'error');
    return NULL;
  }

  $sign = new KkbEpay_Sign($loader->getKey());
  try {
    return $sign->sign64($message);
  }
  catch (KkbEpay_Exception $ex) {
    drupal_set_message(t('There is a problem with the key, message was no signed. Check logs for details.'), 'error');
    watchdog('kkb_epay', $ex->getMessage(), array(), WATCHDOG_ERROR);
    if ($previous = $ex->getPrevious()) {
      watchdog('kkb_epay', $previous->getMessage(), array(), WATCHDOG_ERROR);
    }
    return NULL;
  }
}

/**
 * Checks a message signature with the default certificate.
 *
 * @param text $message
 *   Message whose signature must be verified.
 *
 * @param text $signature
 *   Base64-encoded signature of the message.
 *
 * @return boolean
 *   TRUE if signature was check without errors.
 *   FALSE otherwise.
 */
function kkb_epay_check_signature($message, $signature) {
  $certificate = new KkbEpay_DefaultBankCertificate();
  try {
    $checker = new KkbEpay_Checker($certificate);
    return $checker->check64($message, $signature);
  }
  catch (KkbEpay_Exception $ex) {
    drupal_set_message(t('There is a problem with the certificate. Check logs for details.'), 'error');
    watchdog('kkb_epay', $ex->getMessage(), array(), WATCHDOG_ERROR);
    if ($previous = $ex->getPrevious()) {
      watchdog('kkb_epay', $previous->getMessage(), array(), WATCHDOG_ERROR);
    }
    return FALSE;
  }
}

/**
 * Creates a complete signed order document that can be sent directly to the
 * processing center.
 *
 * @return string
 *   Order document as a string. The document is not base64 encoded.
 */
function kkb_epay_create_signed_order(KkbEpay_Order $order) {
  $key = kkb_epay_load_default_key();

  $merchant_data = theme('kkb_epay_merchant_data', array(
    'amount'         => $order->getAmount(),
    'certificate_id' => $key->getCertificateId(),
    'currency'       => $order->getCurrency(),
    'merchant_id'    => $key->getMerchantId(),
    'merchant_name'  => $key->getMerchantName(),
    'order_id'       => $order->getId(),
  ));

  return theme('kkb_epay_signed_order', array(
    'merchant'  => $merchant,
    'signature' => kkb_epay_sign_message($merchant_data),
  ));
}

