<?php
/**
 * Base Test Case
 *
 * @package   GA_Plugin
 * @author    Orases
 * @copyright 2025 Orases
 * @license   GPL-2.0-or-later
 */

namespace GAPlugin\Tests;

use Brain\Monkey;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * Base test case class
 */
class TestCase extends PHPUnitTestCase {

    /**
     * Set up test environment
     */
    protected function setUp(): void {
        parent::setUp();
        Monkey\setUp();
    }

    /**
     * Tear down test environment
     */
    protected function tearDown(): void {
        Monkey\tearDown();
        parent::tearDown();
    }
}
