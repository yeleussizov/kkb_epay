<merchant cert_id="<?php print check_plain($certificate_id); ?>"
          name="<?php print check_plain($merchant_name); ?>">
  <order order_id="<?php print check_plain($order_id); ?>"
         amount="<?php print check_plain($amount); ?>"
         currency="<?php print check_plain($currency); ?>">
    <department merchant_id="<?php print check_plain($merchant_id); ?>"
                amount="<?php print check_plain($amount); ?>" abonent_id="" terminal=""/>
  </order>
</merchant>
