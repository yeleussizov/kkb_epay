<?php

/**
 * Signature checker.
 */
final class KkbEpay_Checker
{

  private $_certificate;


  public function __construct(KkbEpay_BankCertificateInterface $certificate = NULL)
  {
    if (empty($certificate)) {
      $certificate = new KkbEpay_DefaultBankCertificate();
    }

    $resource = openssl_get_publickey(trim($certificate->getCertificate()));
    if (empty($resource)) {
      throw new KkbEpay_Exception('Provided certificate could not be loaded by openssl_get_publickey().');
    }

    $this->_certificate = $resource;
  }

  /**
   * Checks given message with loaded public certificate.
   *
   * @param string $message
   *   The original message whose signature must be verified.
   *   Cannot be empty.
   *
   * @param string $signature
   *   Verified signature as a binary string.
   *
   * @return boolean
   *   TRUE if signature is corrent.
   *   FALSE if signature was not verified.
   */
  public function check($message, $signature)
  {
    if (empty($message)) {
      throw new KkbEpay_Exception('Message cannot be empty.');
    }

    return openssl_verify($message, $signature, $this->_certificate);
  }

}

