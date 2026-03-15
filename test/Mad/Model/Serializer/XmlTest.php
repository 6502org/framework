<?php
/**
 * @category   Mad
 * @package    Mad_Model
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
 * @todo Tests for sanitizeSql()
 * 
 * @category   Mad
 * @package    Mad_Model
 * @subpackage UnitTests
 * @copyright  (c) 2007-2009 Maintainable Software, LLC
 * @license    http://opensource.org/licenses/bsd-license.php BSD
 */
#[\PHPUnit\Framework\Attributes\Group('model')]
class Mad_Model_Serializer_XmlTest extends Mad_Test_Unit
{
    // set up new db by inserting dummy data into the db
    public function setUp(): void
    {
        $this->fixtures('companies', 'users', 'articles', 'comments', 'unit_tests');
    }


    /*##########################################################################
    # Xml Serialization Test
    ##########################################################################*/

    public function testShouldSerializeDefaultRoot()
    {
        $user = new User;
        $xml  = $user->toXml();
        
        $this->assertStringContainsString('<user>',  $xml);
        $this->assertStringContainsString('</user>', $xml);
    }

    public function testShouldSerializeDefaultRootWithNamespace()
    {
        $user = new User;
        $xml  = $user->toXml(array('namespace' => "http://xml.rubyonrails.org/contact"));
                
        $this->assertStringContainsString('<user xmlns="http://xml.rubyonrails.org/contact">',  $xml);
        $this->assertStringContainsString('</user>', $xml);
    }

    public function testShouldSerializeCustomRoot()
    {
        $user = new User;
        $xml  = $user->toXml(array('root' => "xml_contact"));
        
        $this->assertStringContainsString('<xml-contact>',  $xml);
        $this->assertStringContainsString('</xml-contact>', $xml);
    }

    public function testShouldAllowUndasherizedTags()
    {
        $user = new User;
        $xml  = $user->toXml(array('root' => "xml_contact", 'dasherize' => false));

        $this->assertStringContainsString('<xml_contact>',  $xml);
        $this->assertStringContainsString('</xml_contact>', $xml);
        $this->assertStringContainsString('<created_at',    $xml);
    }


    /*##########################################################################
    # Default Xml Serialization Test
    ##########################################################################*/

    public function testShouldSerializeString()
    {
        $xml = User::find(1)->toXml();
        
        $this->assertStringContainsString('<name>Mike Naberezny</name>', $xml);
    }

    public function testShouldSerializeInteger()
    {
        $xml = User::find(1)->toXml();
        
        $this->assertStringContainsString('<id type="integer">1</id>', $xml);
    }

    public function testShouldSerializeBinary()
    {
        $xml = UnitTest::find(1)->toXml();

        $this->assertStringContainsString('c29tZSBibG9iIGRhdGE=</blob-value>',            $xml);
        $this->assertStringContainsString('<blob-value encoding="base64" type="binary">', $xml);
    }

    public function testShouldSerializeDate()
    {
        $xml = User::find(1)->toXml();

        $this->assertStringContainsString('<created-on type="date">2008-01-01</created-on>', $xml);
    }

    public function testShouldSerializeDatetime()
    {
        $xml = User::find(1)->toXml();

        $this->assertStringContainsString('<created-at type="datetime">2008-01-01T20:20:00+00:00</created-at>', $xml);
    }

    public function testShouldSerializeBoolean()
    {
        $xml = User::find(1)->toXml();

        $this->assertStringContainsString('<approved type="boolean">true</approved>', $xml);
    }


    /*##########################################################################
    # Nil Xml Serialization Test
    ##########################################################################*/

    public function testShouldSerializeNullString()
    {
        $user = new User(array('name' => null));
        $xml = $user->toXml();
        $this->assertStringContainsString('<name nil="true"></name>', $xml);
    }

    public function testShouldSerializeNullInteger()
    {
        $user = new User(array('id' => null));
        $xml = $user->toXml();

        $this->assertStringContainsString('<id type="integer" nil="true"></id>', $xml);
    }

    public function testShouldSerializeNullBinary()
    {
        $user = new UnitTest(array('blob_value' => null));
        $xml = $user->toXml();

        $this->assertStringContainsString('<blob-value encoding="base64" type="binary" nil="true"></blob-value>', $xml);
    }

    public function testShouldSerializeNullDate()
    {
        $user = new User(array('created_on' => null));
        $xml = $user->toXml();
        $this->assertStringContainsString('<created-on type="date" nil="true"></created-on>', $xml);

        $user = new User(array('created_on' => '0000-00-00'));
        $xml = $user->toXml();
        $this->assertStringContainsString('<created-on type="date" nil="true"></created-on>', $xml);
    }

    public function testShouldSerializeNullDatetime()
    {
        $user = new User(array('created_at' => null));
        $xml = $user->toXml();
        $this->assertStringContainsString('<created-at type="datetime" nil="true"></created-at>', $xml);

        $user = new User(array('created_at' => '0000-00-00 00:00:00'));
        $xml = $user->toXml();
        $this->assertStringContainsString('<created-at type="datetime" nil="true"></created-at>', $xml);
    }

    public function testShouldSerializeNullBoolean()
    {
        $user = new User(array('approved' => null));
        $xml = $user->toXml();

        $this->assertStringContainsString('<approved type="boolean" nil="true"></approved>', $xml);
    }


    /*##########################################################################
    # Database Connection Xml Serialization Test
    ##########################################################################*/

    public function testPassingHashShouldntReuseBuilder()
    {
        $options = array('include' => 'Comments');
        $mike = $this->users('mike');

        $firstXml  = $mike->toXml($options);
        $secondXml = $mike->toXml($options);
        
        $this->assertEquals($firstXml, $secondXml);
    }

    public function testIncludeUsesAssociationName()
    {
        $xml = $this->companies('maintainable')->toXml(array('include' => 'Users', 'indent' => 0));
        
        $this->assertStringContainsString('<users type="array">', $xml);
        $this->assertStringContainsString('<user>',               $xml);
        $this->assertStringContainsString('<user type="Client">', $xml);
    }

    public function testMethodsAreCalledOnObject()
    {
        $options = array('methods' => 'foo');
        $xmlRpc = $this->articles('xml_rpc');
        
        $xml = $xmlRpc->toXml($options);
        
        $this->assertStringContainsString('<foo>test serializer foo</foo>', $xml);
    }

    public function testPropertiesAreCalledOnObject()
    {
        $options = array('properties' => 'validity', 'indent' => 0);
        $xmlRpc = $this->articles('xml_rpc');
        $xmlRpc->validity = array('is' => 'excellent');

        $xml = $xmlRpc->toXml($options);

        $this->assertStringContainsString('<validity><is>excellent</is></validity>', $xml);
    }

    public function testShouldNotCallMethodsOnAssociationsThatDontRespond()
    {
        $xml = $this->companies('maintainable')->toXml(array('include' => 'Users', 
                                                             'indent'  => 2,
                                                             'methods' => 'foo'));        

        $this->assertTrue(!method_exists($this->companies('maintainable')->users[0], 'foo'));
        $this->assertStringContainsString('  <foo>test serializer foo</foo>', $xml);
        $this->assertStringNotContainsString('    <foo>',                     $xml);
    }

    public function testShouldNotCallPropertiesOnAssociationsThatDontRespond()
    {
        $xml = $this->companies('maintainable')->toXml(array('include'    => 'Users', 
                                                             'indent'  => 2,
                                                             'properties' => 'is_cool'));

        $this->assertStringContainsString('  <is-cool type="boolean">true</is-cool>', $xml);
        $this->assertStringNotContainsString('    <is-cool>',                         $xml);
    }

    public function testShouldIncludeEmptyHasManyAsEmptyArray()
    {
        User::deleteAll();
        
        $xml = $this->companies('maintainable')->toXml(array('include' => 'Users', 'indent' => 2));

        $array = Mad_Support_ArrayObject::fromXml($xml);
        $this->assertEquals(array(), $array['company']['users']);
        
        $this->assertStringContainsString('<users type="array"></users>', $xml);
    }

    public function testShouldHasManyArrayElementsShouldIncludeTypeWhenDifferentFromGuessedValue()
    {
        $xml = $this->companies('maintainable')->toXml(array('include' => 'Employees', 
                                                             'indent'  => 2));

        $this->assertNotNull(Mad_Support_ArrayObject::fromXml($xml));
        $this->assertStringContainsString('<employees type="array">', $xml);
        $this->assertStringContainsString('<employee type="User">',   $xml);
        $this->assertStringContainsString('<employee type="Client">', $xml);
    }


    /*##########################################################################
    # Serialization Include tests
    ##########################################################################*/

    public function testSerializeWithoutIncludes()
    {
        $record  = $this->users('mike');
        $options = array('except' => array('updated_at', 'updated_on', 'first_name'));
        $serializer = new Mad_Model_Serializer_Xml($record, $options);
        
        $xml = $serializer->serialize($record, $options);

        $this->assertStringContainsString('<name>Mike Naberezny</name>', $xml);
        $this->assertStringNotContainsString('<updated-at',              $xml);
    }

    public function testSerializeIncludeSingleBelongsto()
    {
        $record  = $this->articles('xml_rpc');
        $options = array('include' => 'User');
        $serializer = new Mad_Model_Serializer_Xml($record, $options);

        $xml = $serializer->serialize($record, $options);
        
        $this->assertStringContainsString('<article>',                 $xml);
        $this->assertStringContainsString('<title>Easier XML-RPC for', $xml);
        $this->assertStringContainsString('<user>',                    $xml);
        $this->assertStringContainsString('<name>Mike Naberezny',      $xml);
    }

    public function testSerializeIncludeSingleHasMany()
    {
        $record  = $this->articles('xml_rpc');
        $options = array('include' => 'Comments');
        $serializer = new Mad_Model_Serializer_Xml($record, $options);

        $xml = $serializer->serialize($record, $options);

        $this->assertStringContainsString('<article>',                 $xml);
        $this->assertStringContainsString('<title>Easier XML-RPC for', $xml);
        $this->assertStringContainsString('<comments type="array">',   $xml);
        $this->assertStringContainsString('<comment>',                 $xml);
        $this->assertStringContainsString('<body>Comment A</body>',    $xml);
        $this->assertStringContainsString('<body>Comment B</body>',    $xml);
    }

    public function testSerializeIncludeMultiple()
    {
        $record  = $this->articles('xml_rpc');
        $options = array('include' => array('User', 'Comments'));
        $serializer = new Mad_Model_Serializer_Xml($record, $options);

        $xml = $serializer->serialize($record, $options);

        $this->assertStringContainsString('<article>',                 $xml);
        $this->assertStringContainsString('<title>Easier XML-RPC for', $xml);
        $this->assertStringContainsString('<user>',                    $xml);
        $this->assertStringContainsString('<name>Mike Naberezny',      $xml);
        $this->assertStringContainsString('<comments type="array">',   $xml);
        $this->assertStringContainsString('<comment>',                 $xml);
        $this->assertStringContainsString('<body>Comment A</body>',    $xml);
        $this->assertStringContainsString('<body>Comment B</body>',    $xml);
    }

    public function testSerializeIncludeWithOptions()
    {
        $record  = $this->articles('xml_rpc');
        $options = array('include' => array('User'     => array('only'   => 'name'), 
                                            'Comments' => array('except' => 'article_id')));
        $serializer = new Mad_Model_Serializer_Xml($record, $options);

        $xml = $serializer->serialize($record, $options);

        $this->assertStringContainsString('<article>',             $xml);
        $this->assertStringContainsString('<title>Easier XML-RPC', $xml);

        $this->assertStringContainsString('<user>',                $xml);
        $this->assertStringNotContainsString('<company_id>',       $xml);

        $this->assertStringContainsString('<comment>',             $xml);
        $this->assertStringNotContainsString('<article_id>',       $xml);
    }

    public function testSerializeWithMethods()
    {
        $record  = $this->articles('xml_rpc');
        $options = array('methods' => array('foo', 'intMethod', 'boolMethod'));
        $serializer = new Mad_Model_Serializer_Xml($record, $options);

        $xml = $serializer->serialize($record, $options);
        
        $this->assertStringContainsString('<foo>test serializer foo</foo>',                 $xml);
        $this->assertStringContainsString('<int-method type="integer">123</int-method>',    $xml);
        $this->assertStringContainsString('<bool-method type="boolean">true</bool-method>', $xml);        
    }

    public function testSerializeWithProperties()
    {
        $record  = $this->articles('xml_rpc');
        $record->validity = array('is' => 'great');
        $record->is_good  = true;

        $options = array('properties' => array('validity', 'is_good'), 'indent' => 0);
        $serializer = new Mad_Model_Serializer_Xml($record, $options);

        $xml = $serializer->serialize($record, $options);

        $this->assertStringContainsString('<is-good type="boolean">true</is-good>', $xml);
        $this->assertStringContainsString('<validity><is>great</is></validity>',    $xml);
    }

    /*##########################################################################
    # Model conversion Serialization Test
    ##########################################################################*/

    public function testToXml()
    {
        $record  = $this->users('mike');
        $options = array('include' => array('Comments' => array('only' => 'body')), 
                         'only'    => 'name');
    
        $xml = $record->toXml($options);

        $this->assertStringContainsString('<user>',                  $xml);
        $this->assertStringContainsString('<comments type="array">', $xml);
        $this->assertStringContainsString('<comment>',               $xml);
    }

    public function testFromXml()
    {
        $record = new Article;

        $xml = '<?xml version="1.0" encoding="UTF-8"?><article>'.
               '<id type="integer">1</id><title>Easier XML-RPC for PHP5</title>'.
               '<user-id type="integer">1</user-id></article>';
        $article = $record->fromXml($xml);

        $this->assertInstanceOf('Article', $article);
        
        $this->assertEquals(1, $article->id);
        $this->assertEquals("Easier XML-RPC for PHP5", $article->title);
    }

    public function testToXmlBooleanCast()
    {
        $record  = $this->users('mike');
        $record->approved = 0;

        $xml = $record->toXml();

        $this->assertStringContainsString('<approved type="boolean">false</approved>', $xml);
    }
    /*##########################################################################
    ##########################################################################*/

}