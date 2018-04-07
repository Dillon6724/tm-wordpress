
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
    $key = getenv(TM_PARSLEY_KEY);
    $secret = getenv(TM_PARSLEY_SECRET);
    // create curl resource
    $ch = curl_init();
    // set url
    curl_setopt($ch, CURLOPT_URL, "https://api.parsely.com/v2/analytics/posts?apikey=", $key, "&secret=", $secret, "&page=1&limit=10&sort=views&period_start=1w");
    // $output contains the output json
    $output = curl_exec($ch);
    // close curl resource to free up system resources
    curl_close($ch);
    // {"name":"Baron","gender":"male","probability":0.88,"count":26}
    // echo var_dump(json_decode($output, true));
    echo $key;
  }
}

add_action('widgets_init', create_function('', 'return register_widget("trending");'));

?>
