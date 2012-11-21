<?php

/**
 * Representation of a single product or service in the 'appendix' field in
 * the payment details that are submited to the processing center.
 */
class KkbEpay_OrderItem
{

  protected $_name;

  protected $_number;

  protected $_amount;

  protected $_quantity;


  public function __construct($name, $amount = 0, $quantity = 1)
  {
    $this->setName($name);
    $this->setAmount($amount);
    $this->setQuantity($quantity);
  }

  public function setName($name)
  {
    $this->_name = (string) $name;
    return $this;
  }

  public function setAmount($amount)
  {
    $this->_amount = $amount;
    return $this;
  }

  public function setQuantity($quantity)
  {
    $this->_quantity = (int) $quantity;
    return $this;
  }

  public function setNumber($number)
  {
    $this->_number = (int) $number;
    return $this;
  }

  public function getName()
  {
    return $this->_name;
  }

  public function getAmount()
  {
    return $this->_amount;
  }

  public function getQuantity()
  {
    return $this->_quantity;
  }

  public function getNumber()
  {
    return $this->_number;
  }

}

