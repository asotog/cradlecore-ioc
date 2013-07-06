<?php
include_once('../cradlecore/ioc/context/Context_Factory.php');
$callerDirectory = dirname(__FILE__);
$configuration = $callerDirectory . '/exampleFiles/configurations/parentUnsortedConfiguration.xml';

///////////////////////////start time
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$starttime = $mtime;
/////////////////////////////////////
const LBR = "\n";

$context =  Context_Factory::getXmlContext($configuration, true);

$personDao = $context->getObject('personDAO');
echo LBR . '-- Xml Context with dependency graph algorithm from ' . $configuration . LBR;
echo LBR . LBR . '---- Simple Setter Injection';
echo $personDao->getName() . '<br/>';
echo LBR . LBR . '---- Setter Injection With The Value Of A Initialized Object' . LBR;
echo $personDao->getConnection() . '<br/>';

$connection = $context->getObject('connection');
echo LBR . LBR . '---- Simple Constructor Injection' . LBR;
echo $connection->getConnectionString();

$itemDao = $context->getObject('itemDao');
echo LBR . LBR . '---- Constructor Injection With The Value Of A Initialized Object' . LBR;
echo LBR . $itemDao->getItems() . LBR;

///////////////////////////////end time
$mtime = microtime();
$mtime = explode(' ',$mtime);
$mtime = $mtime[1] + $mtime[0];
$endtime = $mtime;
$totaltime = ($endtime - $starttime);
///////////////////////////////////////

echo LBR . '-- This proccess was executed in  ' . $totaltime . ' seconds' . LBR . LBR;
?>