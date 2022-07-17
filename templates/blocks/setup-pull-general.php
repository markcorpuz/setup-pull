 <?php

if( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$a = new SetupPullMain();
echo $a->setup_pull_general( $block, 'taxonomy' );
// EOF