<?php

require_once('../../../private/initialize.php');

$session->require_login();

$bicycle = new Bicycle;

if(is_post_request()) {

  // Create record using post parameters (HTML assoc. array)
  $args = $_POST['bicycle'] ?? NULL;

  $bicycle = new Bicycle($args);
  $result = $bicycle->create();
  if($result === true) {
    $new_id = $bicycle->id;
    $session->message('The bicycle was created successfully.');
    redirect_to(url_for('/staff/bicycles/show.php?id=' . $new_id));
  } else {
    // show errors
  }
}

?>

<?php $page_title = 'Create Bicycle'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/bicycles/index.php'); ?>">&laquo; Back to List</a>

  <div class="bicycle new">
    <h1>Create Bicycle</h1>

    <?php  echo display_errors($bicycle->errors); ?>

    <form action="<?php echo url_for('/staff/bicycles/new.php'); ?>" method="post">

      <?php include('form_fields.php'); ?>

      <div id="operations">
        <input type="submit" value="Create Bicycle" />
      </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
