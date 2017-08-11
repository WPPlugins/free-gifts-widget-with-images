<?php 
/* Plugin Name: Free Gifts Widget with Images
Plugin URI: http://www.yatko.com
Description: Free Gifts is a lightweight widget to display today's free gifts and gift cards on your wordpress blog or website. The widget is showing the latest gifteng images, title and description. Gifteng is a unique social community where you can give and receive things you love, for free.
Version: 1.0
Author: Yatko
Author URI: http://www.yatko.com
*/   

// Enqueue the style
function freeGifts_widget_style()
{
    // Register the style for the plugin:
    wp_register_style( 'gift-style', plugins_url( '/css/style.css', __FILE__ ), array(), '20140527', 'all' );
    // Enqueue the style:
    wp_enqueue_style( 'gift-style' );
}
add_action( 'wp_enqueue_scripts', 'freeGifts_widget_style' );

// Enqueue own jquery for testing only, the jQuery library included with WordPress is set to the noConflict() mode
// see "jQuery noConflict Wrappers" http://codex.wordpress.org/Function_Reference/wp_enqueue_script

/*
function freeGifts_widget_jquery()
{
    // Register the script like this for a plugin:
    wp_register_script( 'gift-jquery', plugins_url( '/js/jquery-1.11.0.min.js', __FILE__ ) );
    // Enqueue the script:
    wp_enqueue_script( 'gift-jquery' );
}
add_action( 'wp_enqueue_scripts', 'freeGifts_widget_jquery' );
*/

// Enqueue built-in jquery
wp_enqueue_script('jquery');

// Enqueue the js
function freeGifts_widget_script()
{
    // Register the script like this for a plugin:
    wp_register_script( 'gift-script', plugins_url( '/js/gift.js', __FILE__ ) );
    // Enqueue the script:
    wp_enqueue_script( 'gift-script' );
}
add_action( 'wp_enqueue_scripts', 'freeGifts_widget_script' );

// Creating the widget 
class freeGifts_widget extends WP_Widget {	  
function __construct() {
parent::__construct(
// Base ID of your widget
'freeGifts_widget', 

// Widget name will appear in UI
__('Free Gifts Widget', 'freeGifts_widget_domain'), 

// Widget description
array( 'description' => __( 'Free gifts and gift cards from gifteng.com', 'freeGifts_widget_domain' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
$widget_border = $instance['widget_border'];
$widget_border_radius = $instance['widget_border_radius'];
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output
$xml = simplexml_load_file("https://s3.amazonaws.com/gifteng/public/result.xml");
$divId=1;
foreach ($xml->gift as $gift) {
    echo __( '
    <div id="gift_' . $divId . '" class="gift">
	    <a href="http://gifteng.com/#/browse?view=' . $gift["id"] . '">
	    	<div id="gift_image">
	    		<img src="https://s3.amazonaws.com/gifteng/ad/'. $gift["imageId"] .'_320" style="border:' . $widget_border . ';border-radius:' . $widget_border_radius . ';">' . '
	    	</div>
	    	<div id="gift_details" class="gift_details" style="border-radius:' . $widget_border_radius . ';">
	    		<h2>' . $gift->title . '</h2>' . '
	    		<p>' . $gift->description . '</p>
	    	</div>
	    </a>
    </div>
    ', 'freeGifts_widget_domain' );
    $divId++;
    }

echo $args['after_widget'];
}		
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
	$title = $instance[ 'title' ];
	}
	else {
		$title = __( 'Today\'s Free Gifts', 'freeGifts_widget_domain' );
	}
if ( isset( $instance[ 'widget_border' ] ) ) {
        $widget_border = $instance[ 'widget_border' ];
    }
    else {
        $widget_border = __( '0px solid #ffffff', 'wpb_widget_domain' );
    	}
    //Repeat for option2
    if ( isset( $instance[ 'widget_border_radius' ] ) ) {
        $widget_border_radius = $instance[ 'widget_border_radius' ];
    }
    else {
        $widget_border_radius = __( '4px', 'wpb_widget_domain' );
        }
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id( 'widget_border' ); ?>"><?php _e( 'Border:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'widget_border' ); ?>" name="<?php echo $this->get_field_name( 'widget_border' ); ?>" type="text" value="<?php echo esc_attr( $widget_border ); ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id( 'widget_border_radius' ); ?>"><?php _e( 'Radius:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'widget_border_radius' ); ?>" name="<?php echo $this->get_field_name( 'widget_border_radius' ); ?>" type="text" value="<?php echo esc_attr( $widget_border_radius ); ?>" />
</p>
<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
$instance['widget_border'] = ( ! empty( $new_instance['widget_border'] ) ) ? strip_tags( $new_instance['widget_border'] ) : '';
$instance['widget_border_radius'] = ( ! empty( $new_instance['widget_border_radius'] ) ) ? strip_tags( $new_instance['widget_border_radius'] ) : '';
return $instance;
}
} // Class freeGifts_widget ends here

// Register and load the widget
function freeGifts_load_widget() {
	register_widget( 'freeGifts_widget' );
}
add_action( 'widgets_init', 'freeGifts_load_widget' );
?>