<?php

/*
 If you're ever validating that a user on your site is a patron,
 you should *not* use this code.
 Instead, please use the proper OAuth flow,
 as detailed [in the documentation](https://www.patreon.com/platform/documentation/oauth)
 and in the snippet [in the README](https://www.github.com/Patreon/patreon-php/blob/master/README.md)

 If you want to just get a list of your patrons for non-authentication purposes,
 this snippet is a good example of how to do so.
 */

require_once('patreonAPI/patreon.php');

use Patreon\API;
use Patreon\OAuth;


$access_token = "9_y3Lr7Lb0NyCxwbdNrnCYdbexSzvwb2r_NXcGvAEKA";
$refresh_token = "QmyJjEMX0a_2ua2xOcKMs9AGmcMeeppYI3qeRoIGR2Y";
$api_client = new Patreon\API($access_token);

// Get your campaign data
$campaign_response = $api_client->fetch_campaign();

// If the token doesn't work, get a newer one
if ($campaign_response['errors']) {
    // Make an OAuth client
    // Get your Client ID and Secret from https://www.patreon.com/platform/documentation/clients
    $client_id = null;
    $client_secret = null;
    $oauth_client = new Patreon\OAuth($client_id, $client_secret);
    // Get a fresher access token
    $tokens = $oauth_client->refresh_token($refresh_token, null);
    if ($tokens['access_token']) {
        $access_token = $tokens['access_token'];
        echo "Got a new access_token! Please overwrite the old one in this script with: " . $access_token . " and try again.";
    } else {
        echo "Can't recover from access failure\n";
        print_r($tokens);
    }
    return;
}

// get page after page of pledge data
$campaign_id = $campaign_response['data'][0]['id'];
$cursor = null;
?>
<ul>
<?php
//while (true) {
    $pledges_response = $api_client->fetch_page_of_pledges($campaign_id, 5, $cursor);
//print_r($pledges_response);

    // get all the users in an easy-to-lookup way
    $user_data = [];
    foreach ($pledges_response['included'] as $included_data) {
        if ($included_data['type'] == 'user') {
            $user_data[$included_data['id']] = $included_data;
        }
    }
    // loop over the pledges to get e.g. their amount and user name
    foreach ($pledges_response['data'] as $pledge_data) {
        $pledge_amount = $pledge_data['attributes']['amount_cents'];
        $patron_id = $pledge_data['relationships']['patron']['data']['id'];
        $patron_full_name = $user_data[$patron_id]['attributes']['full_name'];
        echo "<li>";
//        echo "<span class='amount'>&euro;". number_format(floatval($donor->purchase_value)) . "</span>";
        echo "<span class='amount'>&euro;". $pledge_amount/100 . "</span>";
        echo "<span class='user'>By ". $patron_full_name . "</span>";
        $since = human_time_diff(strtotime($pledge_data['attributes']['created_at']), current_time('timestamp')) .  " ago";
        echo "<span class='since'>". $since . "</span>";
        echo "</li>";
    }
/*
    // get the link to the next page of pledges
    $next_link = $pledges_response['links']['next'];
    if (!$next_link) {
        // if there's no next page, we're done!
        break;
    }
    // otherwise, parse out the cursor param
    $next_query_params = explode("?", $next_link)[1];
    parse_str($next_query_params, $parsed_next_query_params);
    $cursor = $parsed_next_query_params['page']['cursor'];
}
/**/
?>

</ul>
