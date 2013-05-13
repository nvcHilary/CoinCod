<?php
// Load the Savant3 class file and create an instance.
require_once '../libs/Savant3.php';

// initialize template engine
$tpl = new Savant3();

// add a series of template directories
$tpl->addPath('template', '../template');

//Set Values
$resource_path = "../";
$title = "Location";
$meta_description = "Welcome to CoinCod - a unique auction system built to draw everyone closer to their dream products.";

$contentContainer = array(
    array(
        "title" => $title,
        "content" => '<article>
						<!--Google Map Display-->
						<iframe width="700" height="450" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com.my/maps?q=3.13006,101.627523&amp;num=1&amp;hl=en&amp;ie=UTF8&amp;ll=3.129789,101.62751&amp;spn=0.005817,0.010568&amp;t=m&amp;z=14&amp;output=embed">
						</iframe>
						<br />
						<small>
							<a href="https://maps.google.com.my/maps?q=3.13006,101.627523&amp;num=1&amp;hl=en&amp;ie=UTF8&amp;ll=3.129789,101.62751&amp;spn=0.005817,0.010568&amp;t=m&amp;z=14&amp;source=embed" style="color:#0000FF;text-align:left">View Larger Map</a>
						</small>
						
						<!--Display the Company address-->
						<address>
							<ul>
								<li>Address: </li>
								<li>NexVend Sdn Bhd </li>
								<li>Unit No. 2.17, 2nd Floor, </li> 
								<li>Lobby No. 5, Block A, </li>
								<li>Damansara Intan, No. 1, </li>
								<li>Jalan SS 20/27, </li>
								<li>47400 Petaling Jaya,</li>
								<li>Selangor Darul Ehsan, Malaysia</li>
							</ul>
						</address>',
		"bottom_image" =>'<div class="location"><img src="../template/template_image/bottom/location.png" alt="pointer"></div>'
    )
);

// Assign values to the Savant instance.
$tpl->resource_path = $resource_path;
$tpl->title = $title;
$tpl->meta_description = $meta_description;
$tpl->content_container = $contentContainer;

$tpl->setTemplate('main.tpl');
$tpl->display();

?>