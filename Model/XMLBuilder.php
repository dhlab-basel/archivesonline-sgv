<?php

namespace ArchivesOnlineSGV;

/**
 * Class Model_XMLBuilder This class builds the XML structure by means of the received resources.
 * @package ArchivesOnlineSGV
 */
class Model_XMLBuilder {
    /**
     * @var float Version number
     */
    const VERSION = 1.2;
    /**
     * @var string Schema
     */
    const SCHEMA = "isad";
    /**
     * @var string Packing
     */
    const PACKING = "xml";
    /**
     * @var string Resource url
     */
    const RESOURCE_URL = "http://archiv.sgv-sstp.ch/resource/";
    /**
     * @var int The maximum result
     */
    private $maxResult;

    /**
     * Model_XMLBuilder constructor.
     * @param int $maxResult
     */
    public function __construct($maxResult) {
        $this->maxResult = $maxResult;
    }

    /**
     * Creates a XML and adds the resources to the XML by creating the "record" tag which contains all the details for a resource.
     * Functions converts this XML into a string and gives it back.
     * @param array $resources contains all the resources with the information needed to create the XML structure.
     * @return string
     */
    public function createXML(array $resources) {
        $xml = new \DOMDocument("1.0", "utf-8");
        $xml->xmlStandalone = true;

        //root element
        $el_seReRe = $xml->createElementNS("http://www.loc.gov/zing/srw/", "searchRetrieveResponse");
        $xml->appendChild($el_seReRe);

        $el_seReRe->setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
        $el_seReRe->setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:" . self::SCHEMA, "http://www.expertisecentrumdavid.be/xmlschemas/isad.xsd");
        $el_seReRe->setAttributeNS("http://www.w3.org/2001/XMLSchema-instance", "schemaLocation", "http://www.loc.gov/zing/srw/ http://www.loc.gov/standards/sru/sru1-1archive/xml-files/srw-types.xsd");

        $el_sruVersion = $xml->createElement("version", \strval(self::VERSION));
        $el_seReRe->appendChild($el_sruVersion);

        $el_recordsNumber = $xml->createElement("numberOfRecords", \strval(count($resources)));
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

            $el_title = $xml->createElement(self::SCHEMA . ":title");
            $el_title->appendChild($xml->createTextNode($value->getTitle()));
            $el_identity->appendChild($el_title);

            $el_date = $xml->createElement(self::SCHEMA . ":date", $value->getDate());
            $el_identity->appendChild($el_date);

            $el_desLevel = $xml->createElement(self::SCHEMA . ":descriptionlevel");
            $el_identity->appendChild($el_desLevel);

            $el_extent = $xml->createElement(self::SCHEMA . ":extent");
            $el_identity->appendChild($el_extent);

            $el_context = $xml->createElement(self::SCHEMA . ":context");
            $el_archivalDes->appendChild($el_context);

            $el_creator = $xml->createElement(self::SCHEMA . ":creator");
            $el_context->appendChild($el_creator);

            $el_rePosition = $xml->createElement("recordPosition", \strval($key + 1));
            $el_record->appendChild($el_rePosition);

            $el_exReData = $xml->createElement("extraRecordData");
            $el_record->appendChild($el_exReData);

            $el_score = $xml->createElement("rel:score", 1);
            $el_score->setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:rel", "info:srw/extension/2/relevancy-1.0");
            $el_link = $xml->createElement("ap:link");
            $el_link->appendChild($xml->createTextNode(static::RESOURCE_URL . $value->getNumberID()));
            $el_link->setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:ap", "http://www.archivportal.ch/srw/extension/");
            $el_beginDate = $xml->createElement("ap:beginDateISO", $value->getDate1Full());
            $el_beginDate->setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:ap", "http://www.archivportal.ch/srw/extension/");
            $el_beginApp = $xml->createElement("ap:beginApprox", "0");
            $el_beginApp->setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:ap", "http://www.archivportal.ch/srw/extension/");
            $el_endDate = $xml->createElement("ap:endDateISO", $value->getDate2Full());
            $el_endDate->setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:ap", "http://www.archivportal.ch/srw/extension/");
            $el_endApp = $xml->createElement("ap:endApprox", "0");
            $el_endApp->setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:ap", "http://www.archivportal.ch/srw/extension/");
            $el_digItem = $xml->createElement("ap:hasDigitizedItems", "1");
            $el_digItem->setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:ap", "http://www.archivportal.ch/srw/extension/");

            $children = array($el_score, $el_link, $el_beginDate, $el_beginApp, $el_endDate, $el_endApp, $el_digItem);

            foreach ($children as $k => $v) {
                $el_exReData->appendChild($v);
            }
        }

        $xml->formatOutput = true;
        return $xml->saveXML();
    }

}
