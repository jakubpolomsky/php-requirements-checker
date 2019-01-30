<?php

use jakubpolomsky\phpRequirementsChecker\Checker;

require_once __DIR__ ."/../vendor/autoload.php";

$checker = new Checker();

$checker->addDependenciesViaComposerFile(__DIR__."/../composer.json", true);

echo "<table border='1px solid black'>";
echo "<tr><td colspan='2'>Composer requires</td></tr>";
echo "<tr><th>Extension</th><th>Enabled?</th></tr>";

foreach($checker->check() as $requirement => $enabled) {
    $enabled = $enabled ? 'yes' : 'no';
    echo "<tr><td>$requirement</td><td>$enabled</td></tr>";
}
echo "</table>";


$checker2 = new Checker();
$checker2->addDependenciesViaString('mcrypt,intl,foo,curl,gd');
$checker2->addDependenciesViaArray(['mysqli','bar']);
echo "<table border='1px solid black'>";
echo "<tr><td colspan='2'>Custom defined<br>requirements</td></tr>";
echo "<tr><th>Extension</th><th>Enabled?</th></tr>";

foreach($checker2->check() as $requirement => $enabled) {
    $enabled = $enabled ? 'yes' : 'no';
    echo "<tr><td>$requirement</td><td>$enabled</td></tr>";
}
echo "</table>";

libxml_use_internal_errors( TRUE );

$dom = new DOMDocument();
$dom->validateOnParse = TRUE;
$dom->preserveWhiteSpace = TRUE;
$dom->formatOutput = TRUE;
$dom->loadHTML( mb_convert_encoding( ob_get_contents(), 'UTF-8' ) );

libxml_clear_errors();

/*
 perform any other operations on dom elements you wish
*/

$buffer = $dom->saveHTML();
ob_end_clean();
echo trim ( $buffer );/* send data to client/browser */