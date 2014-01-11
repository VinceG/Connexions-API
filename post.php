<?php


$xml = '<RESPONSE_GROUP MISMOVersionID="2.4" xmlns:ns1="http://www.inhouse-solutions.com">
  <RESPONSE>
    
  </RESPONSE>
</RESPONSE_GROUP>';


error_reporting(-1);
ini_set('display_errors', true);

require_once('vendor/autoload.php');
require_once('Connexions.php');

$login = '';
$token = '';

$obj = new Connexions($login, $token);

$obj->setAppraisalId('xxx');
//$obj->setStatusCode('4');

//$obj->setBorrower('Vincent', 'Gabriel', '8882721214', 'vgabriel@xxxx');
//$obj->setCoBorrower('Vincent', 'Gabriel', '8882721214');

//$obj->setProperty('5444 Tujunga Ave', null, 'North Hollywood', 'CA', 91601, null, Connexions::TITLE_CAT_TWO_FOUR_UNIT);
//$obj->setPropertySalesHistory(250000);

//$obj->addNote('Some Note');

$obj->setContactDetail('Vince', 'Gabriel', 'vgab@ss.com', 'Developer', '8882721212');
//$obj->setAMCProcessor('Vincent', 'Gab', 'vgabriel@xxx', '8882721214');

//$obj->addDocumentFromString('text.xml', Connexions::FILE_OTHER_DOCUMENT, $contents, 'text/xml');

$obj->setAppraisalStatusUpdate(13327);

$obj->debug();