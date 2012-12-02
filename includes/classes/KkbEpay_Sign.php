<?php

/**
 * @file
 * Copyright (C) 2012, Victor Nikulshin
 *
 * This file is part of KKB Epay.
 *
 * KKB Epay is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * KKB Epay is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with KKB Epay.  If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

/**
 * Main signer of messages.
 */
final class KkbEpay_Sign
{

  private $_key;


  public function __construct(KkbEpay_Key $key)
  {
    if (!$key->isValid()) {
      throw new KkbEpay_Exception('Provided key is not valid.');
    }

    $resource = openssl_get_privatekey($key->getKey(), $key->getPassword());
    if (empty($resource)) {
      $error = 'Provided key could not be opened by openssl_get_privatekey().';
      $previous = new KkbEpay_OpenSSLException(strval(openssl_error_string()));
      throw new KkbEpay_Exception($error, 0, $previous);
    }

    $this->_key = $resource;
  }

  /**
   * Signs given message with loaded private key.
   *
   * @param string $message
   *   Message that must be signed, as a string. Cannot be empty.
   *
   * @return
   *   Signature as a binary string.
   */
  public function sign($message)
  {
    if (empty($message)) {
      throw new KkbEpay_Exception('Message cannot be empty.');
    }

    $signature = '';
    openssl_sign($message, $signature, $this->_key);

    // The reason why _reverse() must be used here is mysterious.
    // But this action was performed in the original signing code that
    // was provided by the processing center. And the processing center
    // will perform _reverse() operation too when it validates this
    // signature. Thus, this action is required.
    return $this->_reverse($signature);
  }

  /**
   * Signs the message with sign() method and encodes signature with
   * base64 function.
   */
  public function sign64($message)
  {
    return base64_encode($this->sign($message));
  }


  private function _reverse($data)
  {
    return strrev($data);
  }

}

