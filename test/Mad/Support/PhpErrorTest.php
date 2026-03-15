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
class Mad_Support_PhpErrorTest extends Mad_Test_Unit
{

    public function testExtendsMadSupportException()
    {
        $e = new Mad_Support_PhpError();
        $this->assertTrue($e instanceof Mad_Support_Exception);
    }

    public function testSetsTitleForWarning()
    {
        $e = new Mad_Support_PhpError('', E_WARNING);
        $this->assertEquals('PHP Warning', $e->getTitle());
    }
    
    public function testSetsTitleForNotice()
    {
        $e = new Mad_Support_PhpError('', E_NOTICE);
        $this->assertEquals('PHP Notice', $e->getTitle());
    }
    
    public function testSetsTitleForCoreWarning()
    {
        $e = new Mad_Support_PhpError('', E_CORE_WARNING);
        $this->assertEquals('PHP Core Warning', $e->getTitle());
    }
    
    public function testSetsTitleForCompileWarning()
    {
        $e = new Mad_Support_PhpError('', E_COMPILE_WARNING);
        $this->assertEquals('PHP Compile Warning', $e->getTitle());
    }
    
    public function testSetsTitleForUserError()
    {
        $e = new Mad_Support_PHPError('', E_USER_ERROR);
        $this->assertEquals('PHP User Error', $e->getTitle());
    }
    
    public function testSetsTitleForUserWarning()
    {
        $e = new Mad_Support_PHPError('', E_USER_WARNING);
        $this->assertEquals('PHP User Warning', $e->getTitle());
    }
    
    public function testSetsTitleForUserNotice()
    {
        $e = new Mad_Support_PHPError('', E_USER_NOTICE);
        $this->assertEquals('PHP User Notice', $e->getTitle());
    }

    public function testSetsTitleForRecoverableError()
    {
        $e = new Mad_Support_PHPError('', E_RECOVERABLE_ERROR);
        $this->assertEquals('PHP Recoverable Error', $e->getTitle());
    }

    public function testSetsTitleForDeprecatedNotice()
    {
        $e = new Mad_Support_PHPError('', E_DEPRECATED);
        $this->assertEquals('PHP Deprecated Notice', $e->getTitle());
    }
    
    public function testSetsTitleForUnknownError()
    {
        $e = new Mad_Support_PHPError('', -1);
        $this->assertEquals('PHP Unknown Error', $e->getTitle());
    }
    
    public function testGetDoctoredTraceRemovesFirstFrame()
    {
        try {
            throw new Mad_Support_PHPError();
        } catch (Mad_Support_PHPError $e) {
            // fall through
        }

        $trace    = $e->getTrace();
        $doctored = $e->getDoctoredTrace();
        
        $this->assertEquals(sizeof($trace)-1, sizeof($doctored));
        $this->assertEquals($trace[1], $doctored[0]);
    }

}
