<?php
class UserPinList {

  function __construct($userId) {
    $this->userId = $userId;
    $this->isUser = $userId == get_current_user_id();
  }

  function getPinUrlFragment($value) {
    $wpNonce = wp_create_nonce('user_' . $value);
    return 'id=' . $value . '&_wpnonce=' . $wpNonce;
  }

  function getStatusFromSOM($value) {
    return $value == 'APPROVED' ? 'Approved' : 'Waiting Approval';
  }

  function getItems() {
    global $wpdb;

    $query = "
      SELECT ID, name, description, approval_status, lat, lng, filters
      FROM   pp_pins
      WHERE  user_ID = $this->userId
      ORDER BY created_date DESC";

    return $wpdb->get_results($query);
  }

  function displayItems() {
    $records = $this->getItems();

    echo "<ul class='pin-list'>";

    foreach ($records as $record) {
      $published = $this->getStatusFromSOM($record->approval_status);
      $desc = substr($record->description, 0, 45);
      $urlFragment = $this->getPinUrlFragment($record->ID);

      $itemActions = $this->isUser
        ? "<a href='?action=edit&$urlFragment' class='pin-item__button'>Edit</a>
          <a href='?action=del&$urlFragment' class='pin-item__button'>Delete</a>"
        : "";
      
      $decodedFilters = json_decode($record->filters, true);
      $filters = implode(",", $decodedFilters);
      $viewLink = "http://precious-plastic-dev.s3-website-us-east-1.amazonaws.com/?lat=$record->lat&lng=$record->lng&filters=$filters";
      $itemActions .= "<a href='$viewLink' target='_blank' class='pin-item__button'>View</a>";

      $status = $this->isUser
        ? "<p class='pin-item__text pin-item__status'>$published</p>"
        : "";

      echo "<li class='pin-item'>
        <div class='pin-item__actions'>
          $itemActions
        </div>
        <h3 class='pin-item__title'>$record->name</h3>
        <p class='pin-item__text'>$record->description</p>
        $status
      </li>";
    }

    echo "</ul>";

  }
}
?>
