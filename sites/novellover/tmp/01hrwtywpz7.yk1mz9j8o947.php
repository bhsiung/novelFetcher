<?php echo $this->render('app/view/header.html',$this->mime,get_defined_vars()); ?>
<p>from body... Hello, <?php echo $name; ?>!</p>
<p>color: <?php echo $params['color']; ?></p>
<p>size: <?php echo $params['size']; ?></p>
<h1><?php echo $func('hello','world'); ?></h1>
<?php if ($params['color']=='red'): ?>
	<h2>Color is red!</h2>
	<?php else: ?><h2>whatever color, sucks @_@</h2>
<?php endif; ?>
<h3>Recipients:</h3>
<ul>
<?php foreach (($recipients?:array()) as $ikey=>$ivalue): ?>
	<li><?php echo $ivalue; ?></li>
<?php endforeach; ?>
</ul>
