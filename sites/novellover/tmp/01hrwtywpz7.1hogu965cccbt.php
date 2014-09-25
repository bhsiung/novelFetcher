<?php echo $this->render('app/view/header.html',$this->mime,get_defined_vars()); ?>
<?php echo $this->render('app/view/navi.html',$this->mime,get_defined_vars()); ?>
<div id="page">
<form role="form" method="post">
	<?php if ($isCrawing): ?>
		
			<div class="form-group">
				<div>Crawing right now!</div>
			</div>
		
		<?php else: ?>
			<div class="form-group">
				<label for="new-url">Email address</label>
				<input type="url" class="form-control" name="new-url" id="new-url" placeholder="Enter URL, e.g. http://tw.kxwxw.com/info/jxzs_96250.html" value="<?php echo $remoteURL; ?>">
			</div>
			<button type="submit" class="btn btn-default">Submit</button>
		
	<?php endif; ?>
	<?php foreach (($errors?:array()) as $error): ?>
		<p class="error"><?php echo $error; ?></p>
	<?php endforeach; ?>
</form>
</div>
