<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
function woo_tracker_settings_details() {

    $status = null;

    if( isset($_POST['save-credence']) && !empty($_POST['username']) && !empty($_POST['appkey']) && !empty($_POST['company']) ):

        $status = update_option( 'acc_it_username', $_POST['username'] );
        $status = update_option( 'acc_it_appkey', $_POST['appkey'] );
        $status = update_option( 'acc_it_company', $_POST['company'] );

    endif;
?>
<link rel="stylesheet" href="<?php echo AddFile::addFiles('assets/css', 'bootstrap.min', 'css', true); ?>">
<style media="screen">
.red{
    color:red;
}
.form-area
{
    background-color: #FAFAFA;
    padding: 10px 40px 60px;
    margin: 10px 0px 60px;
}
</style>
<p></p>
<div class="container">
    <div class="col-md-6 col-md-push-3">
        <div class="form-area">
            <img class="img-responsive center-block" src="<?php echo AddFile::addFiles('assets/images', 'icon', 'png', true); ?>" alt="logo">
            <form action="" method="post">
                <br style="clear:both">

				<div class="form-group">
                    <label for="username">User Name</label>
					<input type="text" class="form-control" id="username" name="username" value="<?php echo get_option('acc_it_username'); ?>" placeholder="User Name" required>
				</div>
				<div class="form-group">
                    <label for="username">App Key</label>
					<input type="text" class="form-control" id="appkey" name="appkey" value="<?php echo get_option('acc_it_appkey'); ?>" placeholder="App Key" required>
				</div>
                <div class="form-group">
                    <label for="company">Company Id</label>
					<input type="text" class="form-control" id="company" name="company" value="<?php echo get_option('acc_it_company'); ?>" placeholder="Company Key" required>
				</div>
                <input id="submit" type="submit" name="save-credence" class="btn btn-success btn-sm btn-block" value="Save Data">
            </form>
            <?php if(!empty($status)): ?>
                <br>
                <div class="alert alert-success" role="alert"> <strong>Well done!</strong> You successfully saved this data. </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php
}
