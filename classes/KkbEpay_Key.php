<?php

/**
 * Representation of a RSA private key that can be used to sign text messages.
 *
 * All setters in this class perform input validation. If any validation
 * constraint is violated, a KkbEpay_KeyException is thrown.
 */
final class KkbEpay_Key
{

  private $_key;

  private $_password;

  private $_merchant_id;

  private $_merchant_name;

  private $_certificate_id;


  public function __construct($key = NULL, $pwd = NULL, $c_id = NULL, $m_id = NULL, $m_name = NULL)
  {
    if ($key) {
      $this->setKey($key);
    }
    if ($pwd) {
      $this->setPassword($pwd);
    }
    if ($c_id) {
      $this->setCertificateId($c_id);
    }
    if ($m_id) {
      $this->setMerchantId($m_id);
    }
    if ($m_name) {
      $this->setMerchantName($m_name);
    }
  }

  public function isValid()
  {
    if (empty($this->_key)) return FALSE;
    if (empty($this->_merchant_id)) return FALSE;
    if (empty($this->_merchant_name)) return FALSE;
    if (empty($this->_certificate_id)) return FALSE;

    return TRUE;
  }

  public function setKey($key)
  {
    if (!is_string($key)) {
      throw new KkbEpay_KeyException('Key must be a string.');
    }
    if (substr($key, 0, 31) != '-----BEGIN RSA PRIVATE KEY-----') {
      throw new KkbEpay_KeyException('Key does not start with a correct RSA key declaration.');
    }
    if (substr($key, -29) != '-----END RSA PRIVATE KEY-----') {
      throw new KkbEpay_KeyException('Key does not end with a correct RSA key declaration.');
    }
    $this->_key = trim($key);
    return $this;
  }

  public function setPassword($pwd)
  {
    if (!is_string($pwd)) {
      throw new KkbEpay_KeyException('Password must be a string.');
    }
    $this->_password = $pwd;
    return $this;
  }

  public function setMerchantId($id)
  {
    if (!is_string($id)) {
      throw new KkbEpay_KeyException('Merchant ID must be a string.');
    }
    if (!preg_match('/^[0-9]{8}$/', $id)) {
      throw new KkbEpay_KeyException('Merchant ID does not much expected format. It must consist of 8 digits.');
    }
    $this->_merchant_id = $id;
    return $this;
  }

  public function setMerchantName($name)
  {
    if (!is_string($name)) {
      throw new KkbEpay_KeyException('Merchant name must be a string.');
    }
    if (strlen($name) > 255) {
      throw new KkbEpay_KeyException('Merchant name is too long. It cannot be longer than 255 characters.');
    }
    if (!preg_match('/^[A-Za-z0-9 _-]{1,255}$/', $name)) {
      throw new KkbEpay_KeyException('Merchant name does not much expected format. It can consist only of English letters, digits, \'-\', \'_\' and space.');
    }
    $this->_merchant_name = $name;
    return $this;
  }

  public function setCertificateId($id)
  {
    if (!is_string($id)) {
      throw new KkbEpay_KeyException('Certificate ID must be a string.');
    }
    if (!preg_match('/^[A-Fa-F0-9]{10}$/', $id)) {
      throw new KkbEpay_KeyException('Certificate ID does not much expected format. It must be exactly 10 characters long and consist of hexadecimal digits.');
    }
    $this->_certificate_id = strtoupper($id);
    return $this;
  }

  public function getKey()
  {
    return $this->_key;
  }

  public function getPassword()
  {
    return empty($this->_password) ? '' : $this->_password;
  }

  public function getMerchantId()
  {
    return $this->_merchant_id;
  }

  public function getMerchantName()
  {
    return $this->_merchant_name;
  }

  public function getCertificateId()
  {
    return $this->_certificate_id;
  }

}

