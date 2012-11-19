<?php

/**
 * Main key loader that fetches private keys from Drupal's variables system
 * where it is stored by the 'kkb_epay' module.
 */
class KkbEpay_DrupalKeyLoader implements KkbEpay_KeyLoaderInterface
{

  protected $_debug = FALSE;


  public function getKey()
  {
    $data = $this->_loadKeyData();
    return new KkbEpay_Key(
      $data['key'],
      $data['password'],
      $data['certificate_id'],
      $data['merchant_id'],
      $data['merchant_name']
    );
  }

  public function setDebug($flag)
  {
    $this->_debug = (bool) $flag;
  }

  public function verifyKey()
  {
    try {
      return $this->getKey()->isValid();
    }
    catch (KkbEpay_Exception $ex) {
      return FALSE;
    }
  }


  protected function _loadKeyData()
  {
    $variant = 'kkb_epay_private_key_' . ($this->_debug ? 'debug' : 'live');

    if (!($key_data = variable_get($variant, NULL))) {
      throw new KkbEpay_KeyException('Private key does not exist as a system variable.');
    }
    if (!($unpacked = $this->_unpack($key_data))) {
      throw new KkbEpay_KeyException('Private key could not be unpacked.');
    }

    return $unpacked;
  }

  protected function _unpack($data)
  {
    return unserialize(base64_decode(preg_replace('/\s/', '', $data)));
  }

}

