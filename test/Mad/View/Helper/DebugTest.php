<?php
/**
 * @category   Mad
 * @package    Mad_View
 * @subpackage UnitTests
 * @copyright  (c) 2007-2009 Maintainable Software, LLC
 * @license    http://opensource.org/licenses/bsd-license.php BSD 
 */

/**
 * Set environment
 */
if (!defined('MAD_ENV')) define('MAD_ENV', 'test');
if (!defined('MAD_ROOT')) {
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/config/environment.php';
}

/**
 * @category   Mad
 * @package    Mad_View
 * @subpackage UnitTests
 * @copyright  (c) 2007-2009 Maintainable Software, LLC
 * @license    http://opensource.org/licenses/bsd-license.php BSD
 */
#[\PHPUnit\Framework\Attributes\Group('view')]
class Mad_View_Helper_DebugTest extends Mad_Test_Unit
{
    public function setUp(): void
    {
        $this->helper = new Mad_View_Helper_Debug(new Mad_View_Base());
    }

    // test truncate
    public function testDebug()
    {
        $result = $this->helper->debug('foo&bar');
        $this->assertStringContainsString('<pre class="debug_dump">', $result);
        $this->assertStringContainsString('string(7) &quot;foo&amp;bar&quot;', $result);
        $this->assertStringContainsString('</pre>', $result);
    }
    
}
