<?php
function success_message() {
    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e( 'Congrats! This order is successfully sent to AccountIT Database.', 'woo-tracker' ); ?></p>
    </div>
    <?php
}

function error_message() {
    ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e( 'Sorry! Woo Tracker needs woocommerce installed to run, Please install woocommerce first.', 'woo-tracker' ); ?></p>
    </div>
    <?php
}
