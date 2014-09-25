<?php echo $this->render('app/view/header.html',$this->mime,get_defined_vars()); ?>
<style>
#navigator li .last-chapter{
	text-indent: 2em;
	display: block;
	font-style: italic;
	color: #000;
}
#navigator li{
	margin-bottom: 1em;
}
</style>
<?php echo $this->render('app/view/navi.html',$this->mime,get_defined_vars()); ?>


<ul id="navigator">
<?php foreach (($books?:array()) as $book): ?>
	<li>
		<a href="/book/<?php echo $book['_id']; ?>/<?php echo $book['_source']['bookName']; ?>"><?php echo $book['_source']['bookName']; ?></a>
		<a class="last-chapter" href="/book/<?php echo $book['_source']['bookName']; ?>/article/<?php echo $book['lastChapter']['fields']['chapterId'][0]; ?>/<?php echo $book['lastChapter']['fields']['title'][0]; ?>"><?php echo $book['lastChapter']['fields']['title'][0]; ?></a>
	</li>
<?php endforeach; ?>
</ul>

<!--
<div id="article" class="vertical reader">
</div>
-->
<script src="/static/js/vreader.js"></script>
