<?php
//start the session
session_start();
// start output buffering
ob_start();

//include any libraries/classes needed
function __autoload($className) {
	require_once '../classes/' . $className . '.class.php';
}

// returns the ad to use, and updates its counter

$ad = getBannerAd();

// display the banner to use
echo($ad);

// flush out the output
ob_end_flush();
?>

<?php

// gets the next banner to be displayed as a string
function getBannerAd() {
	$result = "";

	// setup banner array
	$banners = getBanners();

	// sort the banners based on their display value (weight*count)
	usort($banners, "bannerSort");

	// choose the one with the smallest display value (first one)
	$result = $banners[0]['filename'];
	// update count
	$banners[0]['count'] = $banners[0]['count'] + 1;

	// update banners file
	updateBannerCount($banners[0]['id'], $banners[0]['count']);

	// prepend with image folder location
	$result = $result;

	return $result;
}

function getBanners() {
	// grab an instance of the database connection
	$db = Database::getInstance();

	// build the query to get the editorial
	$query = "select * from cms_banner";

	// execute the query
	$db -> doQuery($query);

	// grab all results
	$banners = $db -> fetch_all_array();

	return $banners;
}

function bannerSort($a, $b) {
	// compare their display value
	$one = $a['weight'] * $a['count'];
	$two = $b['weight'] * $b['count'];
	$result = $one - $two;

	if ($result == 0) {
		// compare their weight
		$one = $a['weight'];
		$two = $b['weight'];
		$result = $one - $two;
	}

	if ($result == 0) {
		// compare their count
		$one = $a['count'];
		$two = $b['count'];
		$result = $one - $two;
	}

	return $result;
}

/**
 * Updates the count to the banner to the new count that is passed in. Determines which banner to update
 * based on the passed in id
 *
 * @param id 				The id of the banner to update
 * @param newCount	the new count for the banner
 */
function updateBannerCount($bannerId, $newCount) {

	// make db connection
	$db = Database::getInstance();

	// setup query
	$query = "update cms_banner set count=$newCount where id=$bannerId";

	// execute query
	$err = $db -> doQuery($query);

	if (!empty($err)) {
		echo "<p>There was a problem</p>";
	}
}
?>
