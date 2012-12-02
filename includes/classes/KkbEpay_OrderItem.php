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

