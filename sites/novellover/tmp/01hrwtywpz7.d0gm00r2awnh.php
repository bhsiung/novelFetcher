<?php echo $this->render('app/view/header.html',$this->mime,get_defined_vars()); ?>
<?php echo $this->render('app/view/navi.html',$this->mime,get_defined_vars()); ?>
<div id="page">
	<h1><?php echo $book['bookName']; ?></h1>
	<div class="util">
		<i>last update</i>
		<a href="/newBook?bookId=<?php echo $bookId; ?>">update</a>
		<a href="?dump">dump</a>
	</div>

	<div class="row" id="chapters">
	<?php foreach (($chapters['hits']?:array()) as $chapter): ?>
		<div class="col-md-4">
			<a href="/book/<?php echo $book['bookName']; ?>/article/<?php echo $chapter['fields']['chapterId']['0']; ?>/<?php echo $chapter['fields']['title']['0']; ?>" title="<?php echo $chapter['fields']['title']['0']; ?>"><?php echo $chapter['fields']['title']['0']; ?></a>
		</div>
	<?php endforeach; ?>
	</div>
</div>

<ul class="pager">
<?php if ($prevPg): ?>
	<li class="previous"><a href="?pg=<?php echo $prevPg; ?>">&larr; Older</a></li>
	<?php else: ?><li class="previous disabled"><a href="#">&larr; Older</a></li>
<?php endif; ?>
<?php if ($nextPg): ?>
	<li class="next"><a href="?pg=<?php echo $nextPg; ?>">Newer &rarr;</a></li>
	<?php else: ?><li class="next disabled"><a href="#">Newer &rarr;</a></li>
<?php endif; ?>
</ul>
<!--
<div id="article" class="vertical reader">
</div>
-->
<script src="/static/js/vreader.js"></script>
<?php echo $this->render('app/view/afterBody.html',$this->mime,get_defined_vars()); ?>
