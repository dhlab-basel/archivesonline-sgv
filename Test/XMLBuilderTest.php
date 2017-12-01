<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
include("Model/XMLBuilder.php");
include("Model/Resource.php");

final class XMLBuilderTest extends TestCase{
    private const NUMBERS = 50;
    private const SCHEMA = "isad";
    private const PACKING = "xml";
    protected $xmlBuilder;

    protected function setUp(): void {
        $this->xmlBuilder = new \ArchivesOnlineSGV\Model_XMLBuilder(static::NUMBERS);
    }

    private function initializeData(bool $containsData): array {
        $resources = array();
        if ($containsData) {
            $obj = (object) array('dateval1' => '2433008', 'dateval2' => '2433037', 'calendar' => 'GREGORIAN', 'dateprecision1' => 'MONTH' , 'dateprecision2' => 'MONTH');
            $data = new \ArchivesOnlineSGV\Model_Resource("1", "Einem geschenktem Gaul schaut man immer ins Maul!", $obj);
            \array_push($resources, $data);
        }
        return $resources;
    }

    private function baseElements(): DOMDocument {
        $DOMDocument = new \DOMDocument("1.0");

        $expected_root = $DOMDocument->createElement( "searchRetrieveResponse");
        $DOMDocument->appendChild($expected_root);

        $el_sruVersion = $DOMDocument->createElement("version");
        $expected_root->appendChild($el_sruVersion);

        $el_recordsNumber = $DOMDocument->createElement("numberOfRecords");
        $expected_root->appendChild($el_recordsNumber);

        $el_records = $DOMDocument->createElement("records");
        $expected_root->appendChild($el_records);

        $DOMDocument->formatOutput = true;

        return $DOMDocument;
    }

    private function addRecord(DOMDocument $DOMDocument): DOMDocument{
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
        $el_link = $DOMDocument->createElement("ap:link");
        $el_beginDate = $DOMDocument->createElement("ap:beginDateISO");
        $el_beginApp = $DOMDocument->createElement("ap:beginApprox");
        $el_endDate = $DOMDocument->createElement("ap:endDateISO");
        $el_endApp = $DOMDocument->createElement("ap:endApprox");
        $el_digItem = $DOMDocument->createElement("ap:hasDigitizedItems");

        $children = array($el_score, $el_link, $el_beginDate, $el_beginApp, $el_endDate, $el_endApp, $el_digItem);

        foreach ($children as $k => $v) {
            $el_exReData->appendChild($v);
        }

        return $DOMDocument;
    }

    /**
     * @test Case when there is no <record> data within the <records> tag
     */
    public function containsNoRecordData(): void {
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

        $this->assertEqualXMLStructure($expected_root, $actual_root, false, "Structure is not equal");
    }

    /**
     * @test Case when there is one <record> data within the <records> tag
     */
    public function containsOneRecordData(): void {
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

        $this->assertEqualXMLStructure($expected_root, $actual_root, false, "Structure is not equal");
        //$this->fail("Show:" . $expected->saveXML(). "*******" . $actual->saveXML());
    }
}
