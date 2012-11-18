<?php

final class KkbEpay_Sign
{

  private $_key;


  public function __construct(KkbEpay_Key $key)
  {
    if (!$key->isValid()) {
      throw new KkbEpay_Exception('Provided key is not valid.');
    }
    $resource = openssl_get_privatekey($key->getKey(), $key->getPasword());
    if (empty($resource)) {
      throw new KkbEpay_Exception('Provided key could not be opened by openssl_get_privatekey().');
    }
    $this->_key = $resource;
  }

  public function sign($message)
  {
    $signature = '';
    openssl_sign($message, $signature, $this->_key);

    // The reason why _reverse() must be used here is mysterious.
    // But this action was performed in the original signing code that
    // was provided by the processing center. And the processing center
    // will perform _reverse() operation too when it validates this
    // signature. Thus, this action is required.
    return $this->_reverse($signature);
  }

  private function _reverse($data)
  {
    return strrev($data);
  }

}

