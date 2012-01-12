<div id="bad-flash">
  <?php echo $message; ?>
</div>
<? $js->get('#flash-message')->event('click', $js->get('#flash-message')->effect('fadeOut')); ?>