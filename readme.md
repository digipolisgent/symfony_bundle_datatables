#Avdb Datatables

## Installing this bundle :

#### 1. Composer Require
```sh
$php composer require avdb/datatables
```

#### 2. Enable the bundle
In AppKernel add the following line inside the RegisterBundles() method :

```php
public function registerBundles()
{
    $bundles = [
        // other bundles
        new Avdb\DatatablesBundle\DatatablesBundle(),
    ];
}
```

Add the bundle's routes to your application in your routing.yml file :

```yml
datatables:
    resource: "@DatatablesBundle/Resources/config/routing.yml"
```

Add the bundle's css to your application in your layout.html.twig file
```twig
    {% block stylesheets %}
        <!--- Other stylesheets -->
        <link rel="stylesheet" href="{{ asset('bundles/datatables/css/datatables.min.css') }}">
    {% endblock %}
```

Add the bundle's javascript to your application in your layout.html.twig file :
```twig
{% block javascripts %}
    <!-- other scripts -->
    <script src="{{ asset('bundles/datatables/js/datatables.min.js') }}"></script>
{% endblock %}
```
This bundle assumes you have a working version of jQuery running inside your application.

## Creating a Datatable :

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
            - {name: 'avdb_datatables.table'}

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
        {{ datatables_render_table(table) }}
    </div>
{% endblock %}
```

## Elements

The DatatableBundle contains two "Element" classes to generate labels & buttons. They generate a simple HTML string that
will be outputted by the Datatable. This way you can easily create edit/delete buttons and/or labels.

Both elements implement the ElementInterface which contains one method 'generate'. This method accepts an array with
options as an argument.

#### 1. Buttons
The Button element generates an "a" tag with the default btn class.

The Button element has 4 options :

1. **link** : The uri that will be added to the href attribute. (Default: '#')

2. **type**: The specified type will be added to the btn-%s class. So "danger" would result in btn btn-danger. (Default: 'danger')

3. **class**: Custom classname will be added to the class attribute (Default: null)

4. **text**: The html body of the a-element. (Default: 'Edit')

#### 2. Labels
The Label element generates a 'span' tag with the default 'label' class.

The Label element has 3 options:

1. **type**: The specified type will be added to the label-%s class. So "success" would result in label-success. (Default: 'success')

2. **class**: Custom classname will be added to the class attribute (Default: null)

3. **text**: The html body of the span-element. (Default: 'yes')

#### 3. Example
So generating a button or label is easy. As an example we will create a simple label :
```php
$label = Label::generate([
    'type' => 'danger',
    'class' => 'example-label',
    'text' => 'Some Generated Text'
]);
```

Would result in :

```html
<span class="label label-danger example-label">Some Generated Text</span>
```

## Advanced Usage

#### Columns
##### 1. Column options
The default columns have a set of predefined options you can pass on.

**Property** : This defines which property should be extracted by the column. The default extractor will use the Symfony\PropertyAccess
class the access the objects properties. See : http://symfony.com/doc/current/components/property_access/introduction.html for more information
on the PropertyAccess class and how you should form the "property" option.

```php
$shop = new Shop();
$shop->setName('Some Shop');
$user = new User();
$user->setShop($shop);

$column = new Column('shop', ['property' => 'shop.name']);

$value = $column->extractValue($user); // $value === 'Some Shop'
```

**Extractor** : This option should contain a valid callback or callable class that implements the __invoke method. You
can provide a custom extractor as a means to override the default PropertyAccess extractor used by the default Column.
With the use of custom extractors, you can _almost_ do anything. In this example we use a custom extractor to generate
a button based on the given entity.

```php
$callback = function(User $user) use($router) {
    return Button::generate([
        'link' => $router->generate('user_edit', ['id' => $user->getId()]),
        'text' => 'Edit User'
    ])
};

$column = new Column('_edit', ['extractor' => $callback]);
```
When using a custom extractor, the "property" option can be omitted.
In the table, this column would show the following for our User entity :

```html
<tr>
    <!-- other columns-->
    <td>
        <a href="/user/48/edit" class="btn btn-danger btn-flat">Edit User</a>
    </td>
</tr>
```

**Attributes** : This option supports an array of key => $value attributes. These attributes will be rendered to the
column header in the table. By default there's a data-name attribute present.

```php
$column = new Column('name', ['attributes' => ['data-status' => 'status_1']]);
```

The previous code would result in the following :

```html
<tr>
    <!-- other columns -->
    <th data-status="status_1">Name</th>
</tr>
```

**Label**: You can provide a custom label for each column. The label is used in the table headers. Labels are passed trough
the translator component before being outputted. Lets assume the following code and general.first_name is translated
into "First Name":

```php
$column = new Column('first_name', ['label' => 'general.first_name']);
```

The previous code would result in the following :

```html
<tr>
    <!-- other columns -->
    <th data-name="first_name">First Name</th>
</tr>
```

By default the label is generated as ucfirst($column->getName());

##### 1. Custom columns
In this bundle you have the default "Column" object that is used to define the table's column. The 'createColumn' method
in the Datatable will create a default Column with the given parameters.

But it's also possible to extend or create a custom column using the ColumnInterface. Custom columns are added through
the 'addColumn' method in the Datatable

```php
$datatable = new Datatable('example', $extractor);

$datatable
    ->createColumn('id')
    ->addColumn(new CustomColumn())
;
```


#### Bootstrap configuration

To enable the bootstrap implementation of Datatables in your application, you need to include different files
in your layout.html.twig file:

```twig
{% block stylesheets %}
    <!--- Other stylesheets -->
    <link rel="stylesheet" href="{{ asset('bundles/datatables/css/datatables-bootstrap.min.css') }}">
{% endblock %}
```

Note how instead of the default datatables.min.css we include datatables-bootstrap.min.css. You only need to include
one of these two files. Not both.

The same goes for the javascript file:

```twig
{% block javascripts %}
    <!-- other scripts -->
    <script src="{{ asset('bundles/datatables/js/datatables-bootstrap.min.js') }}"></script>
{% endblock %}
```

#### Font-Awesome icons

To enable the use of Font-Awesome icons (sorting icons etc.) you need to include a second css file:
```twig
{% block stylesheets %}
    <!--- Other stylesheets -->
    <link rel="stylesheet" href="{{ asset('bundles/datatables/css/datatables-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bundles/datatables/css/datatables-font-awesome.min.css') }}">
{% endblock %}
```

Note that here we do include both files. You can combine font-awesome icons with the default datatables layout.
