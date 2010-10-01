<tr>
  <td><?php echo $title ?> Percentage <?php echo $suffix ?></td>
  <td class="right"><?php echo $results['percent'][$key] ?></td>
</tr>
<tr>
  <td><?php echo $title ?> Number</td>
  <td class="right"><?php echo $results['total'][$key] ?></td>
</tr>
<?php if($key == 'other_purchases'): ?>
<tr>
  <td colspan="2"><b>Brands:</b> 
    <?php foreach($results['other_brands'] as $brand){ 
      echo $brand . ", ";
    } ?>
  </td>
</tr>  
<?php endif; ?>
<tr>
  <td colspan="2" class="spacer">&nbsp;</td>
</tr>