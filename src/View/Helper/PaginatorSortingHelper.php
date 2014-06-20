<?php
/**
 * @copyright Copyright (c) 2014 Nico Vogelaar (http://nicovogelaar.nl)
 * @license   http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link      http://nicovogelaar.nl
 */
namespace Nicovogelaar\Paginator\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * PaginatorSortingHelper
 * 
 * @author Nico Vogelaar <nico@nicovogelaar.nl>
 */
class PaginatorSortingHelper extends AbstractHelper
{
    /**
     * Invokable
     *
     * @param string $key        Key
     * @param string $label      Label
     * @param string $attributes Attributes
     *
     * @return string
     */
    public function __invoke($key, $label = null, array $attributes = array())
    {
        return $this->sorting($key, $label, $attributes);
    }

    /**
     * Generate sorting link
     *
     * @param string $key        Key
     * @param string $label      Label
     * @param string $attributes Attributes
     *
     * @return string
     */
    public function sorting($key, $label = null, array $attributes = array())
    {
        $helper = $this->view->plugin('paginator');

        if (null === $label) {
            $paginator = $helper->getPaginator();
            if ($sorting = $paginator->getSorting($key)) {
                $translate = $this->view->plugin('translate');
                $label = $translate($sorting['label']);
            }
        }

        $url = $helper->sortingUrl($key);
        $sortDirection = $helper->sortDirection($key);

        $class = isset($attributes['class']) ? $attributes['class'] : '';
        $class .= ' sort' . ($sortDirection ? '-' . $sortDirection : '');

        $attributes['href'] = $url;
        $attributes['class'] = trim($class);

        $escapeHtml = $this->view->plugin('escapehtml');

        $attributesStrings = array();
        foreach ($attributes as $key => $value) {
            $attributesStrings[] = sprintf(
                '%s="%s"',
                $escapeHtml($key),
                $escapeHtml($value)
            );
        }

        return sprintf(
            '<a %s>%s</a>',
            implode(' ', $attributesStrings),
            $label
        );
    }
}
