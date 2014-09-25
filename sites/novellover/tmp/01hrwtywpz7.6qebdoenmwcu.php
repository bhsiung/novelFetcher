<?php echo $this->render('app/view/header.html',$this->mime,get_defined_vars()); ?>
<?php echo $this->render('app/view/beforeBody.html',$this->mime,get_defined_vars()); ?>
<?php echo $this->render('app/view/navi.html',$this->mime,get_defined_vars()); ?>
<div id="bd" class="article">
	<!--<button id="toggle">toggle</button>-->
	<div id="meta">
		<h3><?php echo $article['bookName']; ?></h3>
		<div class="btn-group navi">
			<?php if (isset($prev)): ?>
				<a type="button" class="btn btn-default" href="/book/<?php echo $article['bookName']; ?>/article/<?php echo $prev['chapterId']['0']; ?>/<?php echo $prev['title']['0']; ?>">
					<span class="glyphicon glyphicon-step-backward"></span>
					<?php echo $prev['title']['0']; ?>
				</a>
			<?php endif; ?>
			<a type="button" class="btn btn-default" href="/book/<?php echo $article['bookId']; ?>/<?php echo $article['bookName']; ?>">index</a>
			<?php if (isset($next)): ?>
				<a type="button" class="btn btn-default" href="/book/<?php echo $article['bookName']; ?>/article/<?php echo $next['chapterId']['0']; ?>/<?php echo $next['title']['0']; ?>">
					<?php echo $next['title']['0']; ?>
					<span class="glyphicon glyphicon-step-forward"></span>
				</a>
			<?php endif; ?>
		</div>
	</div>
	<h1><?php echo $article['title']; ?></h1>
	<div id="article" class="reader"><?php echo $this->raw($article['text']); ?></div>
	<div class="btn-group navi">
		<?php if (isset($prev)): ?>
			<a type="button" class="btn btn-default" href="/book/<?php echo $article['bookName']; ?>/article/<?php echo $prev['chapterId']['0']; ?>/<?php echo $prev['title']['0']; ?>">
				<span class="glyphicon glyphicon-step-backward"></span>
				<?php echo $prev['title']['0']; ?>
			</a>
		<?php endif; ?>
		<a type="button" class="btn btn-default" href="/book/<?php echo $article['bookId']; ?>/<?php echo $article['bookName']; ?>">index</a>
		<?php if (isset($next)): ?>
			<a type="button" class="btn btn-default" href="/book/<?php echo $article['bookName']; ?>/article/<?php echo $next['chapterId']['0']; ?>/<?php echo $next['title']['0']; ?>">
				<?php echo $next['title']['0']; ?>
				<span class="glyphicon glyphicon-step-forward"></span>
			</a>
		<?php endif; ?>
	</div>
</div>

<script src="/static/js/vreader.js"></script>
<?php echo $this->render('app/view/afterBody.html',$this->mime,get_defined_vars()); ?>
