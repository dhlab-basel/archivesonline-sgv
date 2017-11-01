<?php
/**
 * Created by PhpStorm.
 * User: vijeinath
 * Date: 30.10.17
 * Time: 17:01
 */

declare(strict_types = 1);

class XMLBuilder {
    private const VERSION = 1.2;
    private const SCHEMA = "isad";
    private const PACKING = "xml";
    private const FILE_NAME = "output.xml";
    private const ERROR_MESSAGE = "Error, unable to create a xml file!";

    private $maxResult;

    public function __construct(int $maxResult) {
        $this->maxResult = $maxResult;
    }

    public function createXML(array $resources):string {
        $xml = new DOMDocument("1.0");

        //root element
        $el_seReRe = $xml->createElementNS("http://www.loc.gov/zing/srw/", "searchRetrieveResponse");
        $xml->appendChild($el_seReRe);

        $el_seReRe->setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
        $el_seReRe->setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:" . self::SCHEMA, "http://www.expertisecentrumdavid.be/xmlschemas/isad.xsd");
        $el_seReRe->setAttributeNS("http://www.w3.org/2001/XMLSchema-instance", "schemaLocation", "http://www.loc.gov/zing/srw/ http://www.loc.gov/standards/sru/sru1-1archive/xml-files/srw-types.xsd");

        $el_sruVersion = $xml->createElement("version", strval(self::VERSION));
        $el_seReRe->appendChild($el_sruVersion);

        $el_recordsNumber = $xml->createElement("numberOfRecords", strval(count($resources)));
        $el_seReRe->appendChild($el_recordsNumber);

        $el_records = $xml->createElement("records");
        $el_seReRe->appendChild($el_records);

        foreach($resources as $key =>$value) {
            $el_record = $xml->createElement("record");
            $el_records->appendChild($el_record);

            $el_reSchema = $xml->createElement("recordSchema", self::SCHEMA);
            $el_record->appendChild($el_reSchema);

            $el_rePacking = $xml->createElement("recordPacking", self::PACKING);
            $el_record->appendChild($el_rePacking);

            $el_reData = $xml->createElement("recordData");
            $el_record->appendChild($el_reData);

            $el_archivalDes = $xml->createElement(self::SCHEMA . ":archivaldescription");
            $el_reData->appendChild($el_archivalDes);

            $el_identity = $xml->createElement(self::SCHEMA . ":identity");
            $el_archivalDes->appendChild($el_identity);

            $el_reference = $xml->createElement(self::SCHEMA . ":reference");
            $el_identity->appendChild($el_reference);

            $el_title = $xml->createElement(self::SCHEMA . ":title", $value->getTitle());
            $el_identity->appendChild($el_title);

            $el_date = $xml->createElement(self::SCHEMA . ":date");
            $el_identity->appendChild($el_date);

            $el_desLevel = $xml->createElement(self::SCHEMA . ":descriptionlevel");
            $el_identity->appendChild($el_desLevel);

            $el_extent = $xml->createElement(self::SCHEMA . ":extent");
            $el_identity->appendChild($el_extent);

            $el_context = $xml->createElement(self::SCHEMA . ":context");
            $el_archivalDes->appendChild($el_context);

            $el_creator = $xml->createElement(self::SCHEMA . ":creator");
            $el_context->appendChild($el_creator);

            $el_rePosition = $xml->createElement("recordPosition", strval($key + 1));
            $el_record->appendChild($el_rePosition);

            $el_exReData = $xml->createElement("extraRecordData");
            $el_record->appendChild($el_exReData);
        }

        /*
        if (count($resources) - $this->getRecordAmount() != 0) {
            $el_nextRePo = $xml->createElement("nextRecordPosition", strval(count($resources) + 1));
            $el_seReRe->appendChild($el_nextRePo);
        }
        */

        $xml->formatOutput = true;
        //echo $xml->saveXML();
        $xml->save(self::FILE_NAME) or die(self::ERROR_MESSAGE);
        return $xml->saveXML();
    }
}