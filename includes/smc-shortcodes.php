<?php


// Shortcode to display Complex Information
// Usage: [smc_complex_courts]
// Optionally you can specify the complex ID [smc_complex_courts id="88888"]


function smc_modify_complex_content($content) {
	// Check if it's a complex post type page
    if (is_singular('complex')) {
        
        global $post;

	    $complex_id = $post->ID;

	    // Check if a complex ID is provided
	    if ($complex_id) {
	        $complex = get_post($complex_id);
	        if ($complex) {
	            $complex_title = esc_html($complex->post_title);

	            $output = '';

	            // Address
	            $output .= '<div class="address-part">' . get_field('street_address') . '</div>';
	            $output .= '<div class="address-part">' . get_field('city') . ' ' . get_field('state') . ', ' . get_field('zippostal_code') . '</div>';

	            // Hours
	            if( have_rows('hours') ):
				    $output .= '<ul class="hours">';
				    $output .= '<li><span class="day"></span><span class="open">Open</span><span class="close">Close</span></li>';
				    while( have_rows('hours') ): the_row(); 
				        $output .= 
				        	'<li>' . 
				        		'<span class="day">' . get_sub_field('day_of_the_week') . '</span>' . 
				        		'<span class="open">' . get_sub_field('open') . '</span>' . 
				        		'<span class="close">' . get_sub_field('close') . '</span></li>';
				    endwhile;
				    $output .= '</ul>';
				endif;

				// Gallery
				$images = get_field( 'complex_images' );
		        if ( $images ) :
		            // Grab each image.
		            foreach ( $images as $image ) :
		                $image_id      = $image['ID'];
		                $image_src     = $image['url'];
		                $image_caption = $image['caption'];

		                    $output .= '<a href="' . esc_url( $image_src ) .'" title="' . esc_html( $image_caption ) .'" class="thickbox">';
		                    $output .= wp_get_attachment_image( $image_id, "thumbnail" );
		                    $output .= '</a>';

		            endforeach;
		        endif;


		        // Display 'court' posts with matching complex ID
                $court_args = array(
                    'post_type' => 'court',
                    'posts_per_page' => -1,
                    'meta_query' => array(
                        array(
                            'key' => 'complex', // Adjust this to your actual ACF custom field key
                            'value' => $complex_id,
                        ),
                    ),
                );

                $court_query = new WP_Query($court_args);

                if ($court_query->have_posts()) {
                    $output .= '<h2>Courts at ' . $complex_title . '</h2>';
                    $output .= '<ul class="court-list">';

                    while ($court_query->have_posts()) : $court_query->the_post();
                        $court_title = esc_html(get_the_title());
                        $output .= '<li>' . $court_title . '</li>';
                    endwhile;

                    $output .= '</ul>';
                    wp_reset_postdata(); // Restore the global post data
                }
	        }

    	} else {

    		$output = '<!-- No valid Complex found -->';

    	}


    // Concatenate your custom content with the original content
    $content = $output . $content;
    }

    return $content;
}
add_filter('the_content', 'smc_modify_complex_content');
