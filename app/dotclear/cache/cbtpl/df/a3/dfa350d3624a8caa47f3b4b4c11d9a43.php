<!--top.html-->
<div id="top" role="banner">
  <img id="logo" src="/gam/themes/gam/img/logo.png" />
  <span id="hamburger">&nbsp;</span>
  
  	<!--Dans _top.html-->
	<?php echo tplBreadcrumb::displayBreadcrumb(''); ?>
  

  <?php if ($core->hasBehavior('publicTopAfterContent')) { $core->callBehavior('publicTopAfterContent',$core,$_ctx);} ?>
</div>

<div id="nav">
	
<?php echo tplMenuPortail::getList('<h3>%s</h3>','<ul class="menu">%s</ul>','<li>%s</li>'); ?>

</div>