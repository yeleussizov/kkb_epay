<?php

/**
 * Bank certificates' interface.
 */
interface KkbEpay_BankCertificateInterface
{

  /**
   * Must return a valid certificate as a string. It must not have any
   * whitespaces at the beginning or the end the string.
   */
  public function getCertificate();

}

