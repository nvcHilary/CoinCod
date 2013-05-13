<ul id="menu">
	<li><a href="<?=mainPageURL()?>/admin/">Home</a></li>
	<li>
		<a href="<?=mainPageURL()?>/admin/user_manage/">Member Listing</a>
	</li>
	<li>
		<a href="#">Product</a>
		<ul>
			<li><a href="<?=mainPageURL()?>/admin/product/">Product Listing</a></li>
			<li><a href="<?=mainPageURL()?>/admin/product/product_edit.php">Product Editing</a></li>
		</ul>
	</li>
	<li><a href="<?=mainPageURL()?>/admin/status/">Status</a></li>
	<li>
		<a href="#">Test</a>
		<ul>
			<li><a href="#">Test1</a></li>
			<li><a href="#">Test2</a></li>
			<li><a href="#">Test3</a></li>
			<li>
				<a href="#">Test4</a>
				<ul>
					<li><a href="#">Test4-1</a></li>
					<li><a href="#">Test4-2</a></li>
					<li><a href="#">Test4-3</a></li>
					<li><a href="#">Test4-4</a></li>
				</ul>
			</li>
		</ul>
	</li>
</ul>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript">
$(function() {
  if ($.browser.msie && $.browser.version.substr(0,1)<7)
  {
	$('li').has('ul').mouseover(function(){
		$(this).children('ul').css('visibility','visible');
		}).mouseout(function(){
		$(this).children('ul').css('visibility','hidden');
		})
  }
}); 
</script>