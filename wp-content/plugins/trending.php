
<?php
/*
Plugin Name: Trending Articles
*/

class trending extends WP_Widget {

  function trending() {
    $widget_ops = array( 'classname' => 'trending', 'description' => 'Displays your upcoming posts to tease your readers' ); // Widget Settings
    $control_ops = array( 'id_base' => 'trending' ); // Widget Control Settings
    $this->WP_Widget( 'trending', 'Trending Articles', $widget_ops, $control_ops ); // Create the widget
  }

  function widget($args, $instance) {
    // create curl resource
    $ch = curl_init();
    // set url
    curl_setopt($ch, CURLOPT_URL, "https://api.genderize.io/?name=Dillon");
    // $output contains the output json
    $output = curl_exec($ch);
    // close curl resource to free up system resources
    curl_close($ch);
    // {"name":"Baron","gender":"male","probability":0.88,"count":26}
    echo var_dump(json_decode($output, true));  }
}

add_action('widgets_init', create_function('', 'return register_widget("trending");'));

?>
