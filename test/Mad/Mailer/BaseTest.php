<?php
/**
 * @category   Mad
 * @package    Mad_Mailer
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
 * @package    Mad_Mailer
 * @subpackage UnitTests
 * @copyright  (c) 2007-2009 Maintainable Software, LLC
 * @license    http://opensource.org/licenses/bsd-license.php BSD
 */
#[\PHPUnit\Framework\Attributes\Group('mailer')]
class Mad_Mailer_BaseTest extends Mad_Test_Unit
{
    public function setUp(): void
    {
        $this->fixtures('users');
    }
    
    public function testCreateUsingStringAttributes()
    {
        $n = new Notifier();
        $result = $n->createConfirm(User::find(1));

        // result has both headers/body
        $this->assertStringContainsString('Date:',      $result);
        $this->assertStringContainsString('Dear Mike,', $result);
        $this->assertEquals('derek@maintainable.com', $n->getRecipients());
        $this->assertEquals('Confirmation for Mike',  $n->getSubject());
    
        // headers
        $this->assertStringContainsString('Date:',                      $n->getHeaders());
        $this->assertStringContainsString('From: test@example.com',     $n->getHeaders());
        $this->assertStringContainsString('Cc: test1@example.com',      $n->getHeaders());
        $this->assertStringContainsString('Mime-Version: 1.0',          $n->getHeaders());
        $this->assertStringNotContainsString('Bcc:',                    $n->getHeaders());
    
        // body
        $this->assertStringContainsString('Content-Type: text/plain; charset="utf-8"', $n->getBody());
        $this->assertStringContainsString('Dear Mike,',              $n->getBody());
        $this->assertStringContainsString('http://maintainable.com', $n->getBody());
    }

    public function testCreateUsingArrayAttributes()
    {
        $n = new Notifier();
        $result = $n->createSend(User::find(1));
    
        // result has both headers/body
        $this->assertStringContainsString('Date:',      $result);
        $this->assertStringContainsString('Dear Mike,', $result);
        $this->assertEquals('derek@maintainable.com, Mike Naberezny <mike@maintainable.com>', $n->getRecipients());
        $this->assertEquals('Confirmation for Mike',  $n->getSubject());
    
        $this->assertStringContainsString('From: test@example.com',                    $n->getHeaders());
        $this->assertStringContainsString('Cc: test1@example.com, test2@example.com',  $n->getHeaders());
        $this->assertStringContainsString('Bcc: test3@example.com, test4@example.com', $n->getHeaders());
        $this->assertStringContainsString('Mime-Version: 1.0',                         $n->getHeaders());
        $this->assertStringContainsString('Organization: Maintainable, LLC',           $n->getHeaders());
    
        // body
        $this->assertStringContainsString('Content-Type: text/plain; charset="utf-8"',   $n->getBody());
        $this->assertStringContainsString('Dear Mike,',              $n->getBody());
        $this->assertStringContainsString('http://maintainable.com', $n->getBody());
    }

    public function testSendWithAttachments()
    {
        $n = new Notifier();
        $result = $n->createSendWithAttachments();
        
        $attachments = $n->getAttachments();
        $attachment = current($attachments);
        $this->assertEquals('text/plain',        $attachment['contentType']);
        $this->assertEquals('the attachment',    $attachment['body']);
        $this->assertEquals('check_it_out.txt',  $attachment['filename']);
        $this->assertEquals('base64',            $attachment['transferEncoding']);

        
        // result has both headers/body
        $this->assertStringContainsString('Dear Derek,', $result);
        $this->assertEquals('derek@maintainable.com', $n->getRecipients());
        $this->assertEquals('Confirmation for test',  $n->getSubject());

        // headers
        $this->assertStringContainsString('Date:',                      $n->getHeaders());
        $this->assertStringContainsString('From: test@example.com',     $n->getHeaders());
        $this->assertStringContainsString('Mime-Version: 1.0',          $n->getHeaders());

        // body
        $this->assertStringContainsString('Content-Type: text/plain; charset="utf-8"', $n->getBody());
        $this->assertStringContainsString('Dear Derek,',             $n->getBody());
        $this->assertStringContainsString('The Maintainable Team',   $n->getBody());
        
        // attachments
        $this->assertStringContainsString('Content-Transfer-Encoding: base64',                            $n->getBody());
        $this->assertStringContainsString('Content-Disposition: attachment; filename="check_it_out.txt"', $n->getBody());
    }

    public function testSendWithUniqueAttachmentNames()
    {
        $n = new Notifier();
        $result = $n->createSendWithUniqueAttachmentNames(User::find(1));

        $attachments = $n->getAttachments();

        $attachment1 = current($attachments);
        $attachment2 = next($attachments);
        $attachment3 = next($attachments);

        $this->assertEquals('check_it_out.txt',   $attachment1['filename']);
        $this->assertEquals('check_it_out-1.txt', $attachment2['filename']);
        $this->assertEquals('check_it_out-2.txt', $attachment3['filename']);
    }

    public function testDeliver()
    {
        $n = new Notifier();
        $result = $n->deliverConfirm(User::find(1));
    
        $this->assertTrue($result);
    
        // headers
        $this->assertStringContainsString('Date:',                      $n->getHeaders());
        $this->assertStringContainsString('From: test@example.com',     $n->getHeaders());
        $this->assertStringContainsString('Cc: test1@example.com',      $n->getHeaders());
        $this->assertStringContainsString('Mime-Version: 1.0',          $n->getHeaders());
        $this->assertStringNotContainsString('Bcc:',                    $n->getHeaders());
    
        // body
        $this->assertStringContainsString('Content-Type: text/plain; charset="utf-8"', $n->getBody());
        $this->assertStringContainsString('Dear Mike,',              $n->getBody());
        $this->assertStringContainsString('http://maintainable.com', $n->getBody());
    }

}