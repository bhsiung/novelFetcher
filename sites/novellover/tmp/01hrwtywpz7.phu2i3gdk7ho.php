<?php echo $this->render('app/view/header.html',$this->mime,get_defined_vars()); ?>
<?php echo $this->render('app/view/navi.html',$this->mime,get_defined_vars()); ?>

<div id="page">
	<div class="page-header">
		<h1>Search result for <?php echo $keyword; ?></h1>
	</div>
	<div class="row">
		<ul id="navigator">
			<?php foreach (($searchResult['hits']?:array()) as $item): ?>
				<div class="col-md-4"><a href="/book/<?php echo $item['_source']['bookName']; ?>/article/<?php echo $item['_source']['chapterId']; ?>/<?php echo $item['_source']['title']; ?>">
					<?php echo $item['_source']['title']; ?>
				</a></div>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
<script src="/static/js/vreader.js"></script>

