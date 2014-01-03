<?php
$logged = $_SESSION['coin_user_id'];
?>
<div id="container">
	<div id="header">
		<div class="div1">
			<div class="div2"><img src="includes/images/logo.png" title="administrator" onclick="location = ''" /></div>
			<?php if ($logged) { ?>
				<div class="div3"><img src="includes/images/lock.png" alt="" style="position: relative; top: 3px;" />&nbsp;<?php echo $logged; ?></div>
			<?php } ?>
		</div>
		
		<?php if ($logged) { ?>
		<div id="menu">
			<ul class="left" style="display: block;">
				<?php if ((hasPermission($logged, 'access', 'user_group')) || (hasPermission($logged, 'access', 'user')) || (hasPermission($logged, 'access', 'customer')) ){ ?>
				<li id="catalog"><a class="top"><?php echo $lang['text_user']; ?></a>
					<ul>
						<?php if (hasPermission($logged, 'access', 'user_group')) { ?>
							<li><a href="user_group.html"><?php echo $lang['text_user_group']; ?></a></li>
						<?php } ?>
						
						<?php if (hasPermission($logged, 'access', 'user')) { ?>
							<li><a href="user.html"><?php echo $lang['text_user']; ?></a></li>
						<?php } ?>
						
						<?php if (hasPermission($logged, 'access', 'customer')) { ?>
							<li><a href="customer.html"><?php echo $lang['text_customer']; ?></a></li>
						<?php } ?>
						
					</ul>
				</li>
				<?php } ?>
				
				<?php if ( (hasPermission($logged, 'access', 'catalog')) || (hasPermission($logged, 'access', 'product')) ) { ?>
					<li id="catalog"><a class="top"><?php echo $lang['text_catalog']; ?></a>
						<ul>
							<?php if (hasPermission($logged, 'access', 'category')) { ?>
								<li><a href="category.html"><?php echo $lang['text_category']; ?></a></li>
							<?php } ?>
							
							<?php if (hasPermission($logged, 'access', 'product')) { ?>
								<li><a href="product.html"><?php echo $lang['text_product']; ?></a></li>
							<?php } ?>
							
							<?php if (hasPermission($logged, 'access', 'graphics')) { ?>
								<li><a href="graphics.html"><?php echo $lang['text_graphics']; ?></a></li>
							<?php } ?>
						</ul>
					</li>
				<?php } ?>
				<?php if (hasPermission($logged, 'access', 'Settings')) { ?>
					<li id="setting"><a class="top"><?php echo $lang['text_setting']; ?></a>
						<ul>
							<li><a href="company.html"><?php echo $lang['text_company']; ?></a></li>
							<li><a href="tokens.html"><?php echo $lang['text_tokens']; ?></a></li>
							<li><a href="status.html"><?php echo $lang['text_status']; ?></a></li>
							<li><a href="email.html"><?php echo $lang['text_email_setup']; ?></a></li>
							<li><a href="social.html"><?php echo $lang['text_social_network']; ?></a></li>
							<li><a href="information.html"><?php echo $lang['text_information']; ?></a></li>
							<li><a href="faq.html"><?php echo $lang['text_faq']; ?></a></li>
						</ul>
					</li>
				<?php } ?>
			</ul>
			<ul class="right">
				<li id="store"><a class="top" href="logout.html"><?php echo $lang['text_logout']; ?></a></li>
			</ul>
		</div>
		<?php } ?>
	</div>