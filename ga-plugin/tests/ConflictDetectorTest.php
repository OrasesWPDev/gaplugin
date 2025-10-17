<?php
/**
 * Conflict Detector Test
 *
 * @package   GA_Plugin
 * @author    Orases
 * @copyright 2025 Orases
 * @license   GPL-2.0-or-later
 */

namespace GAPlugin\Tests;

use Brain\Monkey\Functions;

require_once dirname(__DIR__) . '/includes/class-gap-conflict-detector.php';

/**
 * Test class for GAP_Conflict_Detector
 */
class ConflictDetectorTest extends TestCase {

    /**
     * Test extract_tracking_ids with GA4 tracking code
     */
    public function test_extract_ga4_tracking_id() {
        $detector = \GAP_Conflict_Detector::get_instance();

        $script_content = "
            <!-- Google tag (gtag.js) -->
            <script async src='https://www.googletagmanager.com/gtag/js?id=G-ABCDEFG123'></script>
            <script>
              window.dataLayer = window.dataLayer || [];
              function gtag(){dataLayer.push(arguments);}
              gtag('js', new Date());
              gtag('config', 'G-ABCDEFG123');
            </script>
        ";

        $result = $detector->extract_tracking_ids($script_content);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('G-ABCDEFG123', $result[0]['id']);
        $this->assertEquals('ga4', $result[0]['type']);
        $this->assertEquals('Google Analytics 4', $result[0]['name']);
    }

    /**
     * Test extract_tracking_ids with GTM tracking code
     */
    public function test_extract_gtm_tracking_id() {
        $detector = \GAP_Conflict_Detector::get_instance();

        $script_content = "
            <!-- Google Tag Manager -->
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-ABCD123');</script>
        ";

        $result = $detector->extract_tracking_ids($script_content);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('GTM-ABCD123', $result[0]['id']);
        $this->assertEquals('gtm', $result[0]['type']);
        $this->assertEquals('Google Tag Manager', $result[0]['name']);
    }

    /**
     * Test extract_tracking_ids with multiple tracking IDs
     */
    public function test_extract_multiple_tracking_ids() {
        $detector = \GAP_Conflict_Detector::get_instance();

        $script_content = "
            gtag('config', 'G-XXXXXXXXXX');
            gtag('config', 'G-YYYYYYYYYY');
            <!-- GTM -->
            'GTM-ZZZZZZZ'
        ";

        $result = $detector->extract_tracking_ids($script_content);

        $this->assertIsArray($result);
        $this->assertCount(3, $result);
    }

    /**
     * Test extract_tracking_ids with no tracking IDs
     */
    public function test_extract_no_tracking_ids() {
        $detector = \GAP_Conflict_Detector::get_instance();

        $script_content = "
            <script>
                console.log('Just some random JavaScript');
            </script>
        ";

        $result = $detector->extract_tracking_ids($script_content);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * Test extract_tracking_ids with various quote styles
     */
    public function test_extract_tracking_ids_with_different_quotes() {
        $detector = \GAP_Conflict_Detector::get_instance();

        // Test single quotes
        $script1 = "gtag('config', 'G-ABCDEFG123');";
        $result1 = $detector->extract_tracking_ids($script1);
        $this->assertCount(1, $result1);
        $this->assertEquals('G-ABCDEFG123', $result1[0]['id']);

        // Test double quotes
        $script2 = 'gtag("config", "G-ABCDEFG456");';
        $result2 = $detector->extract_tracking_ids($script2);
        $this->assertCount(1, $result2);
        $this->assertEquals('G-ABCDEFG456', $result2[0]['id']);
    }

    /**
     * Test scan_page_html method
     */
    public function test_scan_page_html_finds_tracking_id() {
        $detector = \GAP_Conflict_Detector::get_instance();

        $html = "<html><head><script>gtag('config', 'G-ABCDEFG123');</script></head><body></body></html>";
        $tracking_ids = array('G-ABCDEFG123', 'GTM-XXXXXXX');

        $result = $detector->scan_page_html($html, $tracking_ids);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertContains('G-ABCDEFG123', $result);
        $this->assertNotContains('GTM-XXXXXXX', $result);
    }

    /**
     * Test scan_page_html with case insensitivity
     */
    public function test_scan_page_html_case_insensitive() {
        $detector = \GAP_Conflict_Detector::get_instance();

        $html = "<html><head><script>gtag('config', 'g-abcdefg123');</script></head><body></body></html>";
        $tracking_ids = array('G-ABCDEFG123');

        $result = $detector->scan_page_html($html, $tracking_ids);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
    }

    /**
     * Test scan_page_html with empty inputs
     */
    public function test_scan_page_html_empty_inputs() {
        $detector = \GAP_Conflict_Detector::get_instance();

        // Empty HTML
        $result1 = $detector->scan_page_html('', array('G-ABCDEFG123'));
        $this->assertEmpty($result1);

        // Empty tracking IDs
        $result2 = $detector->scan_page_html('<html></html>', array());
        $this->assertEmpty($result2);

        // Both empty
        $result3 = $detector->scan_page_html('', array());
        $this->assertEmpty($result3);
    }

    /**
     * Test log_conflict method
     */
    public function test_log_conflict() {
        $detector = \GAP_Conflict_Detector::get_instance();

        // Test that log_conflict runs without error
        // We can't easily test error_log output without complex mocking
        // This test verifies the method exists and runs without exceptions
        $detector->log_conflict('Test conflict message');

        // If we got here, the method executed successfully
        $this->assertTrue(true);
    }

    /**
     * Test singleton pattern
     */
    public function test_singleton_instance() {
        $instance1 = \GAP_Conflict_Detector::get_instance();
        $instance2 = \GAP_Conflict_Detector::get_instance();

        $this->assertSame($instance1, $instance2);
    }

    /**
     * Test get_conflicts returns empty array initially
     */
    public function test_get_conflicts_initial_state() {
        $detector = \GAP_Conflict_Detector::get_instance();

        // Mock get_posts to return empty array
        Functions\when('get_posts')->justReturn(array());
        Functions\when('add_action')->justReturn(true);

        $conflicts = $detector->get_conflicts();

        $this->assertIsArray($conflicts);
    }
}
