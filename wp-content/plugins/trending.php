
<?php
/*
Plugin Name: Trending Articles
*/

class trending extends WP_Widget {
  $widget_ops = array( 'classname' => 'trending', 'description' => 'Displays your upcoming posts to tease your readers' ); // Widget Settings
  $control_ops = array( 'id_base' => 'trending' ); // Widget Control Settings
  $this->WP_Widget( 'trending', 'Trending Articles', $widget_ops, $control_ops ); // Create the widget
}
?>
