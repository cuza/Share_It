<?php

namespace EWZ\Bundle\SearchBundle\Tests\Lucene;

use EWZ\Bundle\SearchBundle\Lucene\Document;
use EWZ\Bundle\SearchBundle\Lucene\Field;

class FieldTest extends \PHPUnit_Framework_TestCase
{
    public function testGetType()
    {
        $binaryField    = Field::Binary('Binary', 'value');
        $keywordField   = Field::Keyword('Keyword', 'value');
        $textField      = Field::Text('Text', 'value');
        $unIndexedField = Field::UnIndexed('UnIndexed', 'value');
        $unStoredField  = Field::UnStored('UnStored', 'value');

        $this->assertEquals('Binary', $binaryField->getType());
        $this->assertEquals('Keyword', $keywordField->getType());
        $this->assertEquals('Text', $textField->getType());
        $this->assertEquals('UnIndexed', $unIndexedField->getType());
        $this->assertEquals('UnStored', $unStoredField->getType());
    }
}
