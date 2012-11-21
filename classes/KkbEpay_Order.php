<?php

/**
 * Representation of an abstract order that will be payed using KKB Epay
 * processing center. Contains an absolute minimal amount on information to
 * create a payment document for the processing center.
 */
class KkbEpay_Order
{

  protected $_id;

  protected $_amount;

  protected $_currency;

  protected $_items = array();


  public function __construct($order_id = NULL)
  {
    if (!empty($order_id)) {
      $this->setId($order_id);
    }
  }

  /**
   * Validates, sets order ID and modifies it to conform with specification
   * requirements.
   *
   * Order ID must consist of at least 6 digits.
   *
   * This ID must uniquely identify order on the site, as it will be sent
   * back by the processing center with the results of the operation.
   */
  public function setId($id)
  {
    $id = (string) $id;
    if (!preg_match('/^[0-9]+$/', $id)) {
      throw new KkbEpay_Exception('Order ID can consist only of digits.');
    }
    $this->_order_id = str_pad($id, 6, '0', STR_PAD_LEFT);
    return $this;
  }

  /**
   * Sets both order amount and used currency.
   *
   * Currency must be specified with its code. Only few currencies are
   * supported.
   */
  public function setAmount($amount, $currency_code)
  {
    if (!$this->_isSuportedCurrency($currency_code)) {
      throw new KkbEpay_Exception('Unsupported currency.');
    }
    $this->_currency = $currency_code;
    $this->_amount = $amount;
    return $this;
  }

  public function setTengeAmount($amount)
  {
    return $this->setAmount($amount, 398);
  }

  public function setItems(array $items)
  {
    $this->_items = array();
    foreach ($items as $i) {
      $this->addItem($i);
    }
    return $this;
  }

  public function addItem(KkbEpay_OrderItem $i)
  {
    $this->_items[] = $i;
    $i->setNumber(count($this->_items));
    return $this;
  }

  public function getId()
  {
    return $this->_order_id;
  }

  public function getAmount()
  {
    return $this->_amount;
  }

  public function getCurrency()
  {
    return $this->_currency;
  }

  public function getItems()
  {
    return $this->_items;
  }

  protected function _isSuportedCurrency($code)
  {
    switch ($code) {
      case 398:  // Tenge
        return TRUE;
    }

    return FALSE;
  }

}

