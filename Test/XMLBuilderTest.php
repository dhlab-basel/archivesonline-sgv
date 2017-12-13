<?php

use PHPUnit\Framework\TestCase;
include("Model/XMLBuilder.php");
include("Model/Resource.php");

final class XMLBuilderTest extends TestCase{
    const NUMBERS = 50;
    const VERSION = 1.2;
    const SCHEMA = "isad";
    const PACKING = "xml";
    protected $xmlBuilder;

    protected function setUp() {
        $this->xmlBuilder = new \ArchivesOnlineSGV\Model_XMLBuilder(static::NUMBERS);
    }

    private function initializeData( $containsData) {
        $resources = array();
        if ($containsData) {
            $obj = (object) array('dateval1' => '2433008', 'dateval2' => '2433037', 'calendar' => 'GREGORIAN', 'dateprecision1' => 'MONTH' , 'dateprecision2' => 'MONTH');
            $data = new \ArchivesOnlineSGV\Model_Resource("1", "Einem geschenktem Gaul schaut man immer ins Maul!", $obj);
            \array_push($resources, $data);
        }
        return $resources;
    }

    private function baseElements() {
        $DOMDocument = new \DOMDocument("1.0", "utf-8");
        $DOMDocument->xmlStandalone = true;

        $expected_root = $DOMDocument->createElement( "searchRetrieveResponse");
        $DOMDocument->appendChild($expected_root);

        $expected_root->setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
        $expected_root->setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:" . self::SCHEMA, "http://www.expertisecentrumdavid.be/xmlschemas/isad.xsd");
        $expected_root->setAttributeNS("http://www.w3.org/2001/XMLSchema-instance", "schemaLocation", "http://www.loc.gov/zing/srw/ http://www.loc.gov/standards/sru/sru1-1archive/xml-files/srw-types.xsd");

        $el_sruVersion = $DOMDocument->createElement("version", \strval(self::VERSION));
        $expected_root->appendChild($el_sruVersion);

        $el_recordsNumber = $DOMDocument->createElement("numberOfRecords");
        $expected_root->appendChild($el_recordsNumber);

        $el_records = $DOMDocument->createElement("records");
        $expected_root->appendChild($el_records);

        $DOMDocument->formatOutput = true;

        return $DOMDocument;
    }

    private function addRecord(DOMDocument $DOMDocument) {
        $el_records = null;
        $nodeList = $DOMDocument->getElementsByTagName("records");
        if($nodeList->length > 1) {
            $this->fail("There is more than one root node \<records\>");
        } else {
            $el_records = $nodeList->item(0);
        }

        $el_record = $DOMDocument->createElement("record");
        $el_records->appendChild($el_record);

        $el_reSchema = $DOMDocument->createElement("recordSchema", self::SCHEMA);
        $el_record->appendChild($el_reSchema);

        $el_rePacking = $DOMDocument->createElement("recordPacking", self::PACKING);
        $el_record->appendChild($el_rePacking);

        $el_reData = $DOMDocument->createElement("recordData");
        $el_record->appendChild($el_reData);

        $el_archivalDes = $DOMDocument->createElement(self::SCHEMA . ":archivaldescription");
        $el_reData->appendChild($el_archivalDes);

        $el_identity = $DOMDocument->createElement(self::SCHEMA . ":identity");
        $el_archivalDes->appendChild($el_identity);

        $el_reference = $DOMDocument->createElement(self::SCHEMA . ":reference");
        $el_identity->appendChild($el_reference);

        $el_title = $DOMDocument->createElement(self::SCHEMA . ":title");
        $el_identity->appendChild($el_title);

        $el_date = $DOMDocument->createElement(self::SCHEMA . ":date");
        $el_identity->appendChild($el_date);

        $el_desLevel = $DOMDocument->createElement(self::SCHEMA . ":descriptionlevel");
        $el_identity->appendChild($el_desLevel);

        $el_extent = $DOMDocument->createElement(self::SCHEMA . ":extent");
        $el_identity->appendChild($el_extent);

        $el_context = $DOMDocument->createElement(self::SCHEMA . ":context");
        $el_archivalDes->appendChild($el_context);

        $el_creator = $DOMDocument->createElement(self::SCHEMA . ":creator");
        $el_context->appendChild($el_creator);

        $el_rePosition = $DOMDocument->createElement("recordPosition");
        $el_record->appendChild($el_rePosition);

        $el_exReData = $DOMDocument->createElement("extraRecordData");
        $el_record->appendChild($el_exReData);

        $el_score = $DOMDocument->createElement("rel:score");
        $el_score->setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:rel", "info:srw/extension/2/relevancy-1.0");
        $el_link = $DOMDocument->createElement("ap:link");
        $el_link->setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:ap", "http://www.archivportal.ch/srw/extension/");
        $el_beginDate = $DOMDocument->createElement("ap:beginDateISO");
        $el_beginDate->setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:ap", "http://www.archivportal.ch/srw/extension/");
        $el_beginApp = $DOMDocument->createElement("ap:beginApprox", "0");
        $el_beginApp->setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:ap", "http://www.archivportal.ch/srw/extension/");
        $el_endDate = $DOMDocument->createElement("ap:endDateISO");
        $el_endDate->setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:ap", "http://www.archivportal.ch/srw/extension/");
        $el_endApp = $DOMDocument->createElement("ap:endApprox", "0");
        $el_endApp->setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:ap", "http://www.archivportal.ch/srw/extension/");
        $el_digItem = $DOMDocument->createElement("ap:hasDigitizedItems", "0");
        $el_digItem->setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:ap", "http://www.archivportal.ch/srw/extension/");

        $children = array($el_score, $el_link, $el_beginDate, $el_beginApp, $el_endDate, $el_endApp, $el_digItem);

        foreach ($children as $k => $v) {
            $el_exReData->appendChild($v);
        }

        return $DOMDocument;
    }

    /**
     * @test Case when there is no <record> data within the <records> tag
     */
    public function containsNoRecordData() {
        //Creating the expected DOMDocument
        $expected = $this->baseElements();
        $nodeList = $expected->getElementsByTagName("searchRetrieveResponse");
        $expected_root = $nodeList->item(0);

        //Creating the actual DOMDocument
        $actual = new \DOMDocument();
        $xmlString = $this->xmlBuilder->createXML($this->initializeData(false));
        $actual->loadXML($xmlString);
        $nodeList = $actual->getElementsByTagName("searchRetrieveResponse");
        $actual_root = $nodeList->item(0);

        $this->assertEqualXMLStructure($expected_root, $actual_root, true, "Structure is not equal");
    }

    /**
     * @test Case when there is one <record> data within the <records> tag
     */
    public function containsOneRecordData() {
        //Creating the expected DOMDocument
        $expected = $this->baseElements();
        $expected = $this->addRecord($expected);
        $nodeList = $expected->getElementsByTagName("searchRetrieveResponse");
        $expected_root = $nodeList->item(0);

        //Creating the actual DOMDocument
        $actual = new \DOMDocument();
        $xmlString = $this->xmlBuilder->createXML($this->initializeData(true));
        $actual->loadXML($xmlString);
        $nodeList = $actual->getElementsByTagName("searchRetrieveResponse");
        $actual_root = $nodeList->item(0);

        $this->assertEqualXMLStructure($expected_root, $actual_root, true, "Structure is not equal");
        //$this->fail("Show:" . $expected->saveXML(). "*******" . $actual->saveXML());
    }
}
