<?php

namespace EWZ\Bundle\SearchBundle\Tests\Lucene;

use EWZ\Bundle\SearchBundle\Lucene\Document;
use EWZ\Bundle\SearchBundle\Lucene\Field;

class LuceneDocumentTest extends \PHPUnit_Framework_TestCase
{
    public function testGetType()
    {
        $testDoc = new Document();

        $testDoc->addField(Field::Binary('Binary', 'value'));
        $testDoc->addField(Field::Keyword('Keyword', 'value'));
        $testDoc->addField(Field::Text('Text', 'value'));
        $testDoc->addField(Field::UnIndexed('UnIndexed', 'value'));
        $testDoc->addField(Field::UnStored('UnStored', 'value'));

        $this->assertEquals('Binary', $testDoc->getFieldType('Binary'));
        $this->assertEquals('Keyword', $testDoc->getFieldType('Keyword'));
        $this->assertEquals('Text', $testDoc->getFieldType('Text'));
        $this->assertEquals('UnIndexed', $testDoc->getFieldType('UnIndexed'));
        $this->assertEquals('UnStored', $testDoc->getFieldType('UnStored'));
    }
}
