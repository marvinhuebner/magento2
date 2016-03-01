<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Magento\Test\Less;

use Magento\Framework\App\Utility;
use Magento\TestFramework\CodingStandard\Tool\CodeSniffer;
use Magento\TestFramework\CodingStandard\Tool\CodeSniffer\LessWrapper;
use PHPUnit_Framework_TestCase;
use Magento\Test\Php\LiveCodeTest as PHPCodeTest;

/**
 * Set of tests for static code style
 */
class LiveCodeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected static $pathToSource = '';

    /**
     * @var string
     */
    protected static $reportDir = '';

    /**
     * Setup basics for all tests
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        self::$pathToSource = BP;
        self::$reportDir = self::$pathToSource . '/dev/tests/static/report';
        if (!is_dir(self::$reportDir)) {
            mkdir(self::$reportDir, 0770);
        }
    }

    /**
     * Run the magento specific coding standards on the code
     *
     * @return void
     */
    public function testCodeStyle()
    {
        $reportFile = self::$reportDir . '/less_report.xml';
        $wrapper = new LessWrapper();
        $codeSniffer = new CodeSniffer(realpath(__DIR__ . '/_files/lesscs'), $reportFile, $wrapper);

        if (!$codeSniffer->canRun()) {
            $this->markTestSkipped('PHP Code Sniffer is not installed.');
        }

        $codeSniffer->setExtensions([LessWrapper::LESS_FILE_EXTENSION]);

        $whiteList = PHPCodeTest::getWhitelist([LessWrapper::LESS_FILE_EXTENSION], __DIR__);

        $result = $codeSniffer->run($whiteList);

        $this->assertEquals(
            0,
            $result,
            "PHP Code Sniffer has found {$result} error(s): See detailed report in {$reportFile}"
        );
    }
}
