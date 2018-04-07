
<?php
/*
Plugin Name: Trending Articles
*/

add_action( 'init', 'add_trending' );


function add_trending()
{
  echo "<p style='color: black;'>This is my trending articles widget</p>";
}
?>
