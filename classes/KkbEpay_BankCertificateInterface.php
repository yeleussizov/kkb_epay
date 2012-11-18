<?php

/**
 * Bank certificates' interface.
 */
interface KkbEpay_BankCertificateInterface
{

  /**
   * Must return a valid certificate as a string.
   */
  public function getCertificate();

}

