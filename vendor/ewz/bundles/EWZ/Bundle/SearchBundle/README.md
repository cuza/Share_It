EWZSearchBundle
=============

This bundle provides advance search capability for Symfony.

## Installation

Installation depends on how your project is setup:

### Step 1: Installation using the `bin/vendors.php` method

If you're using the `bin/vendors.php` method to manage your vendor libraries,
add the following entries to the `deps` in the root of your project file:

```
[EWZSearchBundle]
    git=http://github.com/excelwebzone/EWZSearchBundle.git
    target=/bundles/EWZ/Bundle/SearchBundle

; Dependency:
;------------
[Search]
    git=http://github.com/excelwebzone/zend-search.git
    target=/zend-search

```

Next, update your vendors by running:

``` bash
$ ./bin/vendors
```

Great! Now skip down to *Step 2*.

### Step 1 (alternative): Installation with submodules

If you're managing your vendor libraries with submodules, first create the
`vendor/bundles/EWZ/Bundle` directory:

``` bash
$ mkdir -pv vendor/bundles/EWZ/Bundle
```

Next, add the necessary submodules:

``` bash
$ git submodule add git://github.com/excelwebzone/zend-search.git vendor/zend-search/Zend/Search
$ git submodule add git://github.com/excelwebzone/EWZSearchBundle.git vendor/bundles/EWZ/Bundle/SearchBundle
```

### Step2: Configure the autoloader

Add the following entry to your autoloader:

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    // ...

    'Zend\\Search' => __DIR__.'/../vendor/zend-search/',
    'EWZ'          => __DIR__.'/../vendor/bundles',
));
```

### Step3: Enable the bundle

Finally, enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...

        new EWZ\Bundle\SearchBundle\EWZSearchBundle(),
    );
}
```

### Step4: Configure the bundle's

Finally, add the following to your config file:

``` yaml
# app/config/config.yml

ewz_search:
    analyzer: Zend\Search\Lucene\Analysis\Analyzer\Common\TextNum\CaseInsensitive
    path:     %kernel.root_dir%/cache/%kernel.environment%/lucene/index
```

**NOTE**: If you want to include numbers in your search queries then you'll need to set
analyzer to Zend\Search\Lucene\Analysis\Analyzer\Common\TextNum\CaseInsensitive
See http://framework.zend.com/manual/en/zend.search.lucene.extending.html for more information

Congratulations! You're ready!

## Basic Usage

To index an object use the following example:

``` php
<?php

use EWZ\Bundle\SearchBundle\Lucene\LuceneSearch;

$search = $this->get('ewz_search.lucene');

$document = new Document();
$document->addField(Field::keyword('key', $story->getId()));
$document->addField(Field::text('title', $story->getTitle()));
$document->addField(Field::text('url', $story->getUrl()));
$document->addField(Field::unstored('body', $story->getDescription()));

$search->addDocument($document);
$search->updateIndex();
```

When you want to retrieve data, use:

``` php
<?php

use EWZ\Bundle\SearchBundle\Lucene\LuceneSearch;

$search = $this->get('ewz_search.lucene');
$query = 'Symfony2';

$results = $search->find($query);
```

**NOTE**: See the Zend documentation for more information.
