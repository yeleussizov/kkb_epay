<?php

/**
 * Private key loaders' interface.
 */
interface KkbEpay_KeyLoaderInterface
{

  /**
   * Must return an instance of the KkbEpay_Key class loaded with all required
   * information about the key.
   *
   * @return KkbEpay_Key|NULL
   *   An instance of KkbEpay_Key if key was successfully loaded.
   *   NULL in case of any error, e.g. key was not found.
   */
  public function getKey();

  /**
   * Must set debugging mode.
   *
   * @param boolean $flag
   *   TRUE, if payment debugging is performed. In this case, the loader must
   *         load a special debugging key;
   *   FALSE, if real payment is performed. In this case, the real private key
   *          must be loaded.
   *
   * @return NULL
   */
  public function setDebug($flag);

  /**
   * Must check that the private key can be loaded and is valid.
   *
   * @return boolean
   *   TRUE if private key is fine;
   *   FALSE if there are any problems with the key.
   */
  public function validateKey();

}

