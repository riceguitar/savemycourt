<?php
get_header();

if (have_posts()) :
    while (have_posts()) : the_post();
        $user_id = get_post_meta(get_the_ID(), 'user_id', true);
        $court_id = get_post_meta(get_the_ID(), 'court_id', true);
        $reservation_date = get_post_meta(get_the_ID(), 'reservation_date', true);
        $start_time = get_post_meta(get_the_ID(), 'start_time', true);
        $end_time = get_post_meta(get_the_ID(), 'end_time', true);
        $amount_paid = get_post_meta(get_the_ID(), 'amount_paid', true);
        $payment_status = get_post_meta(get_the_ID(), 'payment_status', true);
        $special_requests = get_post_meta(get_the_ID(), 'special_requests', true);
        $number_of_players = get_post_meta(get_the_ID(), 'number_of_players', true);
        ?>
        test
        <div class="reservation-details">
            <h1><?php the_title(); ?></h1>
            <p><strong>User:</strong> <?php echo esc_html(get_userdata($user_id)->display_name); ?></p>
            <p><strong>Court:</strong> <?php echo esc_html(get_the_title($court_id)); ?></p>
            <p><strong>Date:</strong> <?php echo esc_html($reservation_date); ?></p>
            <p><strong>Time:</strong> <?php echo esc_html($start_time . ' - ' . $end_time); ?></p>
            <p><strong>Amount Paid:</strong> $<?php echo esc_html($amount_paid); ?></p>
            <p><strong>Payment Status:</strong> <?php echo esc_html($payment_status); ?></p>
            <p><strong>Special Requests:</strong> <?php echo esc_html($special_requests); ?></p>
            <p><strong>Number of Players:</strong> <?php echo esc_html($number_of_players); ?></p>

            <?php if ($number_of_players > 0) : ?>
                <h3>Players</h3>
                <ul>
                    <?php for ($i = 1; $i <= $number_of_players; $i++) :
                        $player_user_id = get_post_meta(get_the_ID(), 'player_user_id_' . $i, true);
                        if ($player_user_id) :
                            $player_data = get_userdata($player_user_id);
                            ?>
                            <li><?php echo esc_html($player_data->display_name); ?></li>
                        <?php endif; ?>
                    <?php endfor; ?>
                </ul>
            <?php endif; ?>
        </div>

        <?php
    endwhile;
endif;

get_footer();
?>