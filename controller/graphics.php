<?php
$data = array();
$graphics = getGraphics($data);

$title = $lang['head_graphics'];
?>
<h5><?php echo $lang['head_graphics']; ?></h5>
<article class="auction_container">
	<section id="graphics">		
		<?php if($graphics) { ?>
			<?php foreach($graphics as $graphic) { ?>
				<img src="data/image/graphics/<?php echo $graphic['image']; ?>" alt="<?php echo $graphic['name']; ?>">
					<a href="includes/js/download?file=<?php echo $graphic['image']; ?>">Download</a>
			<?php } ?>
		<?php } ?>
	</section>
</article>