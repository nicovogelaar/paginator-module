# Paginator example

## module.config.php
```php
<?php
return array(
    // ...
    'paginators' => array(
        'doctrine' => array(
            'Example\Paginator\FooPaginator' => 'Example\Entity\Foo', // accepts string or array
            'Example\Paginator\ProductPaginator' => array(
                'entity_class' => 'Example\Entity\Product',
                'repository_method' => 'getProductQueryBuilder', // optional
            ),
        ),
    ),
    // ...
);
```

## Paginator
```php
<?php
namespace Example\Paginator;

use Paginator\Paginator;

class ProductPaginator extends Paginator
{
    public function init()
    {
        $this->addSorting('id', 'p.id', 'ID')
            ->addSorting('date', 'p.created', 'Created')
            ->addSorting('title', 'p.title', 'Title')
            ->addSorting('model', 'p.model', 'Model')
            ->addFilter('title', 'p.title')
            ->addFilter('model', 'p.model', self::FILTER_TYPE_CONTAINS);
    }
}
```

## Controller
```php
<?php
namespace Example\Controller

use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Example\Paginator\ProductPaginator;

class ProductController extends AbstractActionController
{
    public function indexAction()
    {
        $paginator = $this->serviceLocator->get('Example\Paginator\ProductPaginator');
        $paginator->setData($this->params()->fromQuery());

        $viewModel = new ViewModel();
        $viewModel->setTemplate('example/product/index');
        $viewModel->setVariable('paginator', $paginator);

        return $viewModel;
    }
}
```

## View
```
<?php
$this->paginator($paginator);

$form = $this->paginator()->filterForm();
$form->prepare();
?>
<h1><?php echo $this->translate('Products'); ?></h1>

<?php echo $this->form()->openTag($form); ?>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th><?php echo $this->paginatorSorting('id'); ?></th>
            <th><?php echo $this->paginatorSorting('date'); ?></th>
            <th><?php echo $this->paginatorSorting('title'); ?></th>
            <th><?php echo $this->paginatorSorting('model'); ?></th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th><?php echo $this->formInput($form->get('filter')->get('title')); ?></th>
            <th><?php echo $this->formInput($form->get('filter')->get('model')); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($paginator()->getCurrentItems() as $product): ?>
            <tr>
                <td><a href="#"><?php echo $product->getId(); ?></a></td>
                <td><?php echo $product->getCreated()->format('d-m-Y'); ?></td>
                <td><?php echo $product->getTitle(); ?></td>
                <td><?php echo $product->getModel(); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<!-- Enables form submission on enter key for filter input fields -->
<input type="submit" style="position: absolute; left: -9999px"/>
<?php echo $this->form()->closeTag(); ?>

<?php echo $this->paginationControl($paginator(), 'Sliding', 'slide-paginator'); ?>
```
