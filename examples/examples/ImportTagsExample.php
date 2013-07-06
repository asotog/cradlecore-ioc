<?php
include_once('../cradlecore/ioc/context/Context_Factory.php');
$callerDirectory = dirname(__FILE__);
$configuration = $callerDirectory . '/exampleFiles/configurations/parentConfiguration.xml';

///////////////////////////start time
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$starttime = $mtime;
/////////////////////////////////////
const LBR = "\n";

$context =  Context_Factory::getXmlContext($configuration, true);

$personDao = $context->getObject('personDAO');
echo  LBR . '-- Xml Context using import configuration files tags' . LBR;
echo  LBR . '---- Simple Setter Injection' . LBR;
echo  LBR . $personDao->getName();
echo  LBR .  LBR . 'Setter Injection With The Value Of A Initialized Object' . LBR;
echo  LBR . $personDao->getConnection();

$connection = $context->getObject('connection');
echo  LBR .  LBR . 'Simple Constructor Injection' . LBR;
echo  LBR . $connection->getConnectionString();

$itemDao = $context->getObject('itemDao');
echo  LBR .  LBR . 'Constructor Injection With The Value Of A Initialized Object';
echo  LBR . $itemDao->getItems() . LBR;

///////////////////////////////end time
$mtime = microtime();
$mtime = explode(' ',$mtime);
$mtime = $mtime[1] + $mtime[0];
$endtime = $mtime;
$totaltime = ($endtime - $starttime);
///////////////////////////////////////

echo  LBR . 'This proccess was executed in  ' . $totaltime . ' seconds' . LBR . LBR;
?>