<?php

/**
 * @file callbacks.inc
 *   Menu callbacks.
 */

/**
 * Menu page callback. User landing page, payment was successful.
 */
function kkb_epay_callback_back_success($id) {
  $page = module_invoke_all('kkb_epay_payment_success_page');
  if (empty($page)) {
    $page['message'] = array(
      '#prefix' => '<p>',
      '#markup' => t('<strong>Thank you, you payment was successfully accepted.</strong>'),
      '#suffix' => '</p>',
    );
    $page['return'] = array(
      '#prefix' => '<p>',
      '#markup' => l(t('Back to front page.'), '<front>'),
      '#suffix' => '</p>',
    ),
  }
  return $page;
}

/**
 * Menu page callback. User landing page, payment failed.
 */
function kkb_epay_callback_back_failure($id) {
  $page = module_invoke_all('kkb_epay_payment_failure_page');
  if (empty($page)) {
    $page['message'] = array(
      '#prefix' => '<p>',
      '#markup' => t('<strong>Unfortunately your payments was not completed.</strong>'),
      '#suffix' => '</p>',
    );
    $page['return'] = array(
      '#prefix' => '<p>',
      '#markup' => l(t('Back to front page.'), '<front>'),
      '#suffix' => '</p>',
    ),
  }
  return $page;
}

/**
 * Menu page callback. Handles success response from the processing center.
 */
function kkb_epay_callback_post_success() {
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
 * Menu page callback. Handles failure resposne from the processing center.
 */
function kkb_epay_callback_post_failure() {
  // TODO
  echo '0';
}

