<?php

/**
 * Loader of a private key provided by the bank for testing.
 *
 * This private key works only with the sandbox gateway.
 */
class KkbEpay_DebugKeyLoader implements KkbEpay_KeyLoaderInterface
{

  protected $_debug = FALSE;


  public function getKey()
  {
    if (!$this->_debug) {
      throw new KkbEpay_KeyException('Debug key can be loaded only when debugging mode is turned on. Call setDebug() method with TRUE parameter.');
    }
    $key = new KkbEpay_Key();

    $key->setCertificateId('00C182B189');
    $key->setMerchantName('Demo sShop');
    $key->setMerchantId('92061101');
    $key->setPassword('nissan');
    $key->setKey($this->_getKeyString());

    return $key;
  }

  public function setDebug($flag)
  {
    $this->_debug = (bool) $flag;
  }

  public function validateKey()
  {
    return TRUE;
  }


  private function _getKeyString()
  {
    $key = <<<EOF
-----BEGIN RSA PRIVATE KEY-----
Proc-Type: 4,ENCRYPTED
DEK-Info: DES-EDE3-CBC,25E4520A4E5EE17A

r1Uz/b1FZpMJg0kh2efZoaXpLnEg9xR8rkU8nH5y5LTP7q15zldAWm0BqGax6ZHm
5xe/zTjFcZKYjh7NeINlTKrAnbNNYZnYxqqj9GGUa1gEpvHn8TukXB83cEbvsDeS
jrbvbj5itRqqa9fNNs4rzizVdaGFpQKVhCqx4u7lE8oWdR1WCUHOywpFpkpHznDr
od/B2JSzG6OekuwCB4tnyZmJ1RYncbsM7NysOGcUZcT9ZmfzteYkVjPxZKcHzjTr
pLzhlYeAr0by9jNhtodGaYoRHEs2cqK8zEPBRMmgDydVA9Fg2NIIDaBB7ugdjaUw
XuWUo1y5JrU0hRnB7FdAEizO1g5CNG5aZ5UDcg9jbNeKEqrZy2VcBKARYxVDUIlm
INB98tXargbAgbCRwKvn76m8R0ClBMlIHiMzP3LCTfQaJnCIIDirfA==
-----END RSA PRIVATE KEY-----
EOF;
    return trim($key);
  }

}

