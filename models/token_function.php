<?php
if($token_needed <= 200)									{	$tokenneed = 1;		}
if(($token_needed <= 400)	&&	($token_needed > 200))		{	$tokenneed = 2;		}
if(($token_needed <= 600)	&&	($token_needed > 400))		{	$tokenneed = 3;		}
if(($token_needed <= 800)	&&	($token_needed > 600))		{	$tokenneed = 4;		}
if(($token_needed <= 1000)	&&	($token_needed > 800))		{	$tokenneed = 5;		}
if(($token_needed <= 1200)	&&	($token_needed > 1000))		{	$tokenneed = 6;		}
if(($token_needed <= 1400)	&&	($token_needed > 1200))		{	$tokenneed = 7;		}
if(($token_needed <= 1600)	&&	($token_needed > 1400))		{	$tokenneed = 8;		}
if(($token_needed <= 1800)	&&	($token_needed > 1600))		{	$tokenneed = 9;		}
if(($token_needed <= 2000)	&&	($token_needed > 1800))		{	$tokenneed = 10;	}
if(($token_needed <= 2200)	&&	($token_needed > 2000))		{	$tokenneed = 11;	}
if(($token_needed <= 2400)	&&	($token_needed > 2200))		{	$tokenneed = 12;	}
if(($token_needed <= 2600)	&&	($token_needed > 2400))		{	$tokenneed = 13;	}
if(($token_needed <= 2800)	&&	($token_needed > 2600))		{	$tokenneed = 14;	}
if(($token_needed <= 3000)	&&	($token_needed > 2800))		{	$tokenneed = 15;	}
if(($token_needed <= 3200)	&&	($token_needed > 3000))		{	$tokenneed = 16;	}
if(($token_needed <= 3400)	&&	($token_needed > 3200))		{	$tokenneed = 17;	}
if(($token_needed <= 3600)	&&	($token_needed > 3400))		{	$tokenneed = 18;	}
if(($token_needed <= 3800)	&&	($token_needed > 3600))		{	$tokenneed = 19;	}
if(($token_needed <= 4000)	&&	($token_needed > 3800))		{	$tokenneed = 20;	}
if($token_needed > 4000)									{	$tokenneed = 25;	}
?>