<?php

/**
 * @file callbacks.inc
 *   Menu callbacks.
 */

/**
 * Menu page callback. User landing page, payment was successful.
 */
function kkb_epay_callback_back_success($id) {
}

/**
 * Menu page callback. User landing page, payment failed.
 */
function kkb_epay_callback_back_failure($id) {
}

/**
 * Menu page callback. Processing center response callback.
 */
function kkb_epay_callback_post_success($id) {
  if (empty($_POST['response'])) {
    echo '0';
    drupal_exit();
  }
  $document = substr($_POST['response'], 0, 2000);
  if (kkb_epay_bank_response_document_validate($document)) {
    $notification = kkb_epay_bank_response_document_parse($document);

    module_invoke_all('kkb_epay_raw_success_response', $document);
    module_invoke_all('kkb_epay_payment', $notification);
  }
  echo '0';
}

/**
 * Menu page callback. Processing center response callback.
 */
function kkb_epay_callback_post_failure($id) {
  // TODO
  echo '0';
}

