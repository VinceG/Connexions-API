Connexions-API
==============

This is a PHP class used to communicate with InHouse Connexions API. inhouseusa.com

Installation
==============


1. Clone repository
2. Install dependencies using composer

```
php composer.phar install
```

3. check the examples (list.php, pull.php, post.php) for reference


#### List Appraisals

```php
$obj = new Connexions($login, $token);

$orders = $obj->getAppraisals()->getXML();
$obj->debug();
```

#### Pull Appraisal Info

```php
$obj = new Connexions($login, $token);

$req = $obj->getUpdatedAppraisal('0000');
$obj->debug();
```

#### Post Status Update

```php
$obj = new Connexions($login, $token);

// Set appraisal id
$obj->setAppraisalId('xxx');

// Set status code
//$obj->setStatusCode('4');

// set borrower
//$obj->setBorrower('Vincent', 'Gabriel', '8882721214', 'vgabriel@xxxx');

// set co borrower
//$obj->setCoBorrower('Vincent', 'Gabriel', '8882721214');

// Set property info
//$obj->setProperty('5444 Tujunga Ave', null, 'North Hollywood', 'CA', 91601, null, Connexions::TITLE_CAT_TWO_FOUR_UNIT);

// property sales history
//$obj->setPropertySalesHistory(250000);

// add note
//$obj->addNote('Some Note');

// add multiple notes
// $obj->addNote('Some Note')->addNote('Another note');

// Set contact details
// $obj->setContactDetail('Vince', 'Gabriel', 'vgab@ss.com', 'Developer', '8882721212');

// set amc processor
// $obj->setAMCProcessor('Vincent', 'Gab', 'vgabriel@xxx', '8882721214');

// add new document
//$obj->addDocumentFromString('text.xml', Connexions::FILE_OTHER_DOCUMENT, $contents, 'text/xml');

// send appraisal update
$obj->setAppraisalStatusUpdate(13327);

$obj->debug();
```
