<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */
require '../src/global.php';
if (!session_id()) session_start();

unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);
unset($_SESSION['userID']);
unset($_SESSION['userScreenName']);
unset($_SESSION['userProfileImageURL']);

header("Location: /");

