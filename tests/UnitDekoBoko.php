<?php

require_once(dirname(__FILE__) . '/../../toppa-plugin-libraries-for-wordpress/ToppaFunctionsFacadeWp.php');
require_once(dirname(__FILE__) . '/../DekoBoko.php');
Mock::generate('ToppaFunctionsFacadeWp');

class UnitDekoBoko extends UnitTestCase {
    private $functionsFacade;

    public function __construct() {
        $this->UnitTestCase();
    }

    public function setUp() {
        $this->functionsFacade = new MockToppaFunctionsFacadeWp();
//        $this->dbFacade->setReturnValue('sqlSelectRow', $this->samplePhotoData);
    }

    public function testCheckHeaderIsSafeUsingMaliciousData() {
        $dekoBoko = new DekoBoko($this->functionsFacade);
        $this->assertFalse($dekoBoko->checkHeaderIsSafe("content-type: something evil"));
    }
}