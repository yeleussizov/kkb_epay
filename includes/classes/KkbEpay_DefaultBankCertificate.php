<?php

/**
 * Default real certificate of a private key that is used by the Epay
 * processing center.
 *
 * Normally this certificate must be used to verify all messages from
 * the server.
 *
 * This certificate was obtained from a package that is provided to all
 * Epay clients. The package consists of this certificate, client's
 * private key, example PHP-code and some documentation.
 *
 * This certificate can be stored in the source code tree because, in essence,
 * it is a public key that can be shared with any party, unlike a private
 * key, which must be kept in a complete secret.
 */
class KkbEpay_DefaultBankCertificate implements KkbEpay_BankCertificateInterface
{

  protected $_filepath;


  public function __construct($filepath = NULL)
  {
    if (isset($filepath)) {
      $this->setCertificateFilepath($filepath);
    }
  }

  /**
   * Implements KkbEpay_BankCertificateInterface::getCertificate().
   */
  public function getCertificate()
  {
    $file = $this->getCertificateFilepath();
    return trim(file_get_contents($file));
  }

  /**
   * Returns configured path to the PEM-encoded file with the certificate.
   *
   * By default, certificate file is searched using relative path to this
   * file, but its location can be changed with setCertificateFilepath().
   *
   * @note
   *   Certificate body cannot be included directly into this file because this
   *   file is licenced under GPL and cannot include binary blobs. Encoded PEM
   *   certificate is such binary blob. Furthermore, copyright status of the
   *   certificate is not clear.
   */
  public function getCertificateFilepath()
  {
    if (!isset($this->_filepath)) {
      $default_path = __DIR__ . '/../../data/kkb_certificate.pem';
      $this->setCertificateFilepath($default_path);
    }
    return $this->_filepath;
  }

  /**
   * Changes path to the PEM-encoded cetrificate file.
   */
  public function setCertificateFilepath($filepath)
  {
    if (!is_file($filepath) || !is_readable($filepath)) {
      throw new KkbEpay_Exception('File with default bank certificate cannot be read.');
    }
    $this->_filepath = $filepath;
    return $this;
  }

}

