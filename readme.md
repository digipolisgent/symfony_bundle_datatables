#PHPro Datatables

### Installing this bundle :

#### 1. Composer Require
```sh
$php composer require phpro/datatables
```

#### 2. Enable the bundle
In AppKernel add the following line inside the RegisterBundles() method :

```php
public function registerBundles()
{
    $bundles = [
        // other bundles
        new Phpro\DatatablesBundle\DatatablesBundle(),
    ];
}
```

Add the bundle's routes to your application in your routing.yml file :

```yml
phpro_datatables:
    resource: "@DatatablesBundle/Resources/config/routing.yml"
```

Add the bundle's javascript to your application in your layout.html.twig file :
```twig
{% block javascripts %}
    <!-- other scripts -->
    <script src="{{ asset('bundles/datatables/js/datatables.min.js') }}"></script>
{% endblock %}
```
This bundle assumes you have a working version of jQuery running inside your application.

### Creating a Datatable :

#### 1. Create a DataExtractor
The data extractor holds one method; 'extract'. This method will recieve a DatatableRequest which holds the default
symfony2 HttpRequest and some extra parameter such as Page, PageSize, Query, Offset etc.

The data extractor will extract the data from an external source (such as a database or API) based on these parameters.

The data will be passed in a wrapper object called "Extraction". This is so that the bundle doesn't depend on external
Paginator classes and/or bundles. The Extraction object holds one page of data, and the count of all available records
to come. ExtractionInterface is also available

Here is one example ;

```php
class ProductDataExtractor implements DataExtractorInterface
{
    // constructor etc.
    public function extract(RequestInterface $request)
    {
        $query = $this->entityManager->createQuery('// some DQL');
        $query->setMaxResults($request->getPageSize());
        $query->setFirstResult($request->getOffset());

        $paginator = new Paginator($query);

        return new Extraction(
            $paginator->getIterator()->getArrayCopy(),
            $paginator->count()
        );
    }
}
```

Note: In this example the use of the "search" parameter is omitted. In practice, you should build your query based on
this parameter. The parameter is accessed through :

```php
$request->getSearch();
```

#### 2. Create your table in a factory
We create our Datatable implementation in a factory. The Datatable is constructed with an alias and an extractor as
arguments. Columns are added onto the table in the factory method.

```php
// ...
class ProductDataTableFactory
{
    public static function create(Extractor $extractor)
    {
        $table = new DataTable('product', $extractor);

        $table
            ->createColumn('owner', ['property' => 'owner.name'])
            ->createColumn('sku')
            ->createColumn('price')
            ->addColumn(new DateTimeColumn('created_at', ['format' => 'd-m-Y']))
        ;

        return $table;
    }
}
```

#### 3. Register Datatable as a service and tag it as such.
When a datatable is registered as a Datatable, it will be injected into the DatatableManager by the
DatatableCompilerPass. This will allow the DataController to call the correct datatable and return its data.

```yml
services:

    #  Extractor
    app.datatable.extractor.product:
        class: App\AppBundle\Datatable\Extractor\ProductExtractor
        arguments:
            - '@doctrine'

    #  Table
    app.datatable.table.product:
        class: phpro\DatatablesBundle\Datatable\Datatable
        factory: [App\AppBundle\Factory\ProductTableFactory, 'create]
        arguments:
            - '@app.datatable.extractor.product'
        tags:
            - {name: 'phpro_datatables.table'}

```

#### 4. Use the Datatable
When your Datatable is registered correctly, we can now pass the Datatable itself into a controller and
passed onto the view. Datatables can be rendered easily with a Twig function. Besides rendering a Datatable, you don't
need to perform any more actions, the Datatable is automatically instantiated and will operate by itself.

The data displayed in the Datatable will be fetched from the DataController in this bundle.

Pass the datatable into the Twig template:

```php
/**
 * @var DatatableInterface
 */
private $datatable;

public function listAction()
{
    retun $this->renderer->renderResponse('path/to/twig.html.twig', [
        'table' => $this->datatable
    ]);
}
```

Render the table with the Twig function:

```twig
{% block content %}
    <div class="some-wrapper">
        {{ data_table_render_table(table) }}
    </div>
{% endblock %}
```
