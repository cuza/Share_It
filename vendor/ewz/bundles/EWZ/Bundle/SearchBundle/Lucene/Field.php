<?php

namespace EWZ\Bundle\SearchBundle\Lucene;

use Zend\Search\Lucene\Document\Field as ZendField;

class Field extends ZendField
{
    /*
     * convience function to find the way the field was created
     * instead of having to check the is* individually
     *
     * @returns string
     */
    public function getType()
    {
        if (!$this->isStored) {
            // only UnStored meets this criteria
            return 'UnStored';
        }

        if ($this->isBinary) {
            // only Binary isBinary :)
            return 'Binary';
        }

        if ($this->isIndexed) {
            // Keyword or Text
            if( $this->isTokenized ) {
                // only text is tokenized
                return 'Text';
            } else {
                return 'Keyword';
            }
        }

        // only unIndexed is left
        return 'UnIndexed';
    }
}
