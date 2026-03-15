<?php
/**
 * @category   Mad
 * @package    Support
 * @subpackage UnitTests
 * @copyright  (c) 2007-2009 Maintainable Software, LLC
 * @license    http://opensource.org/licenses/bsd-license.php BSD
 */

/**
 * Set environment
 */
if (!defined('MAD_ENV')) define('MAD_ENV', 'test');
if (!defined('MAD_ROOT')) {
    require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config/environment.php';
}

/**
 * @category   Mad
 * @package    Support
 * @subpackage UnitTests
 * @copyright  (c) 2007-2009 Maintainable Software, LLC
 * @license    http://opensource.org/licenses/bsd-license.php BSD
 */
#[\PHPUnit\Framework\Attributes\Group('support')]
class Mad_Support_PhpErrorHandlerTest extends Mad_Test_Unit
{
    public function testHandleThrowsPhpErrorAsMadSupportException()
    {
        Mad_Support_PhpErrorHandler::install();

        try {
            trigger_error('should be thrown', E_USER_WARNING);
            restore_error_handler();
            $this->fail();
        } catch (Mad_Support_Exception $e) {
            $this->assertEquals('should be thrown', $e->getMessage());
            $this->assertEquals(E_USER_WARNING, $e->getCode());
        }

        restore_error_handler();
    }

    public function testHandleDoesNotThrowSilencedErrors()
    {
        Mad_Support_PhpErrorHandler::install();
        @trigger_error("should never be thrown", E_USER_WARNING);
        restore_error_handler();
        $this->assertTrue(true);
    }
}
