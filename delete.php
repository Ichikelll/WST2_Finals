<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// Check if user is logged in
if (!isset($_SESSION["logged_in"])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("No ID specified.");
}

$xmlFile = "members.xml";

// Check if file exists and is writable before loading
if (!file_exists($xmlFile)) {
    die("XML file does not exist.");
}

if (!is_writable($xmlFile)) {
    die("XML file is not writable by the server.");
}

// Load XML using DOM for safer node removal
$dom = new DOMDocument();
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;

if (!$dom->load($xmlFile)) {
    die("Failed to load XML file.");
}

$xpath = new DOMXPath($dom);
$nodes = $xpath->query("//member[id='$id']");

if ($nodes->length === 0) {
    die("Member with ID $id not found.");
}

foreach ($nodes as $node) {
    $node->parentNode->removeChild($node);
}

if (!$dom->save($xmlFile)) {
    die("Failed to save XML file.");
}

// Redirect to home after deletion
header("Location: home.php");
exit();