<?php

/*
 * TEMPLATE: PULL ALL
 */

global $block_css, $pid;

// add more class selectors here
$classes = array();

$classes = array_merge( $classes, explode( ' ', $block_css ) );

// container wrap
echo '<div class="'.join( ' ', $classes ).'">';

	$content = get_the_content( NULL, FALSE, $pid );
    /**
     * Filters the post content.
     *
     * @since 0.71
     *
     * @param string $content Content of the current post.
     */
    $content = apply_filters( 'the_content', $content );
    $content = str_replace( ']]>', ']]&gt;', $content );
    echo $content;

echo '</div>';