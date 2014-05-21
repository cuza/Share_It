<?php

namespace EWZ\Bundle\SearchBundle\Tests\Lucene;

use EWZ\Bundle\SearchBundle\Lucene\LuceneSearch;
use EWZ\Bundle\SearchBundle\Lucene\Document;
use EWZ\Bundle\SearchBundle\Lucene\Field;

class LuceneSearchTest extends \PHPUnit_Framework_TestCase
{
    protected $search;

    public function setUp()
    {
        $this->recursiveDelete(__DIR__ . '/../cache');
        $this->search = new LuceneSearch(__DIR__ . '/../cache');
    }

    public function testInitiatesLucene()
    {
        $this->assertEquals('Zend\Search\Lucene\Index', get_class($this->search->getIndex()), 'return the index object that should have been created on construct');
    }

    public function testAddDocument()
    {
        $doc = new Document();
        $this->search->addDocument($doc);

        $this->assertEquals(1, $this->search->getIndex()->count(), 'a document should be excepted');
    }

    public function testProcessDocuments()
    {
        $greatDoc = new Document();
        $greatDoc->addField(Field::keyword('url', 'domain.com/great-article'));
        $greatDoc->addField(Field::unIndexed('id', '123'));
        $greatDoc->addField(Field::text('title', 'This is a great article about great things'));
        $greatDoc->addField(Field::unstored('body', 'There are so many great things to talk about, isn\' that great?'));

        $unrelatedDoc = new Document();
        $unrelatedDoc->addField(Field::keyword('url', 'domain.com/not-related-article'));
        $unrelatedDoc->addField(Field::unIndexed('id', '234'));
        $unrelatedDoc->addField(Field::text('title', 'Ramblings of a mad man'));
        $unrelatedDoc->addField(Field::unstored('body', 'I\'m not talking about anything here'));

        $goodDoc = new Document();
        $goodDoc->addField(Field::keyword('url', 'domain.com/good-article'));
        $goodDoc->addField(Field::unIndexed('id', '345'));
        $goodDoc->addField(Field::text('title', 'This is a good article about good things'));
        $goodDoc->addField(Field::unstored('body', 'There are so many good things to talk about, isn\'t that great?'));

        $this->search->addDocument($greatDoc);
        $this->search->addDocument($unrelatedDoc);
        $this->search->addDocument($goodDoc);

        $this->search->updateIndex();

        $results = $this->search->find('great');

        $this->assertEquals(2, \sizeof($results), '2 results are expected');
        $this->assertEquals(123, $results[0]->getDocument()->getFieldValue('id'), 'make sure the higher relevance is first');

    }

    public function testDeleteDocument()
    {}

    public function testUpdateDocument()
    {}

    public function testGetFieldType()
    {}

    protected function recursiveDelete($str)
    {
        if (is_file($str)) {
            return @unlink($str);
        } elseif (is_dir($str)) {
            $scan = glob(rtrim($str, '/') . '/*');
            foreach ($scan as $index => $path) {
                $this->recursiveDelete($path);
            }
            return @rmdir($str);
        }
    }
}
