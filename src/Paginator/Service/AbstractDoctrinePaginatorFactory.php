<?php
/**
 * @copyright Copyright (c) 2014 Nico Vogelaar (http://nicovogelaar.nl)
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://nicovogelaar.nl
 */
namespace Paginator\Service;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use Doctrine\ORM\QueryBuilder;
use Paginator\Adapter\DoctrineAdapter;

/**
 * @author Nico Vogelaar <nico@nicovogelaar.nl>
 */
class AbstractDoctrinePaginatorFactory implements AbstractFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if (!$serviceLocator instanceof ServiceLocatorInterface) {
            throw new \BadMethodCallException('This abstract factory is meant to be used only with a service manager');
        }

        $config = $serviceLocator->get('config');

        $return = isset($config['paginators'])
            && isset($config['paginators']['doctrine'])
            && isset($config['paginators']['doctrine'][$requestedName]);

        if ($return) {
            $config = $config['paginators']['doctrine'][$requestedName];
            if (is_array($config) && !isset($config['entity_class'])) {
                $return = false;
            }
        }

        return $return;
    }

    /**
     * {@inheritDoc}
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if (!$this->canCreateServiceWithName($serviceLocator, $name, $requestedName)) {
            throw new \BadMethodCallException('This abstract factory can\'t create service "' . $requestedName . '"');
        }

        $config = $serviceLocator->get('config');

        $config = $config['paginators']['doctrine'][$requestedName];

        if (is_array($config)) {
            $entityClass = $config['entity_class'];
            if (isset($config['repository_method'])) {
                $repositoryMethod = $config['repository_method'];
            }
        } else {
            $entityClass = $config;
        }

        $entityRepository = $serviceLocator->get('Doctrine\ORM\EntityManager')
            ->getRepository($entityClass);

        if (isset($repositoryMethod)) {
            $queryBuilder = $entityRepository->$repositoryMethod();

            if (!$queryBuilder instanceof QueryBuilder) {
                throw new \LogicException('The repository method must return a Query Builder instance.');
            }
        } else {
            $parts = explode('\\', $entityClass);
            $alias = strtolower(substr(end($parts), 0, 1));

            $queryBuilder = $entityRepository->createQueryBuilder($alias);
        }

        return new $requestedName(new DoctrineAdapter($queryBuilder));
    }
}