<document>
  <?php foreach ($items as $i): ?>
    <item number="<?php print check_plain($i->getNumber()); ?>"
          name="<?php print check_plain($i->getName()); ?>"
          quantity="<?php print check_plain($i->getQuantity()); ?>"
          amount="<?php print check_plain($i->getAmount()); ?>" />
  <?php endforeach; ?>
</document>
