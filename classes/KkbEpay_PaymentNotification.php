<?php

/**
 * Representation of payment confirmation received from the processing server.
 *
 * @notice
 *   Object properties are immutable.
 */
class KkbEpay_PaymentNotification
{

  protected $_order_id;

  protected $_timestamp;

  protected $_amount;


  public function __construct(array $params = array())
  {
    if (isset($params['order_id'])) {
      $this->_order_id = $params['order_id'];
    }
    if (isset($params['timestamp'])) {
      $this->_timestamp = $params['timestamp'];
    }
    if (isset($params['amount'])) {
      $this->_amount = $params['amount'];
    }
  }

  /**
   * Returns an order ID from the payment confirmation.
   *
   * Order ID must consist of at least 6 digits, so it may contain few padding
   * zeros at the begining.
   *
   * @param boolean $remove_padding
   *   - TRUE, padding zeros are removed from the beginning;
   *   - FALSE, order ID is returned as is.
   */
  public function getOrderId($remove_padding = TRUE)
  {
    $id = $this->_order_id;
    if ($remove_padding) {
      return ltrim($id, '0');
    }
    return $id;
  }

  public function getTimestamp()
  {
    return $this->_timestamp;
  }

  public function getAmount()
  {
    return $this->_amount;
  }

}

