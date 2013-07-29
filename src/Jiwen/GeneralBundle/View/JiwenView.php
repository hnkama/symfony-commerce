<?php

/*
 * This file is part of the Pagerfanta package.
 *
 * (c) Pablo Díez <pablodip@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jiwen\GeneralBundle\View;

use Pagerfanta\PagerfantaInterface;
use Pagerfanta\View\TwitterBootstrapView;
use Pagerfanta\View\ViewInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * This view renders the twitter bootstrap view with texts translated.
 *
 * @author Pablo Díez <pablodip@gmail.com>
 */
class JiwenView implements ViewInterface
{
    private $template;

    private $pagerfanta;
    private $proximity;

    private $currentPage;
    private $nbPages;

    private $startPage;
    private $endPage;

	private $options;

    public function __construct(TemplateInterface $template = null)
    {
        $this->template = $template ?: $this->createDefaultTemplate();
    }

    protected function createDefaultTemplate()
    {
        return new JiwenTemplate();
    }

    /**
     * {@inheritdoc}
     */
    public function render(PagerfantaInterface $pagerfanta, $routeGenerator, array $options = array())
    {
        $this->initializePagerfanta($pagerfanta);
        $this->initializeOptions($options);

        $this->configureTemplate($routeGenerator, $options);

        return $this->generate();
    }

    private function initializePagerfanta($pagerfanta)
    {
        $this->pagerfanta = $pagerfanta;

        $this->currentPage = $pagerfanta->getCurrentPage();
        $this->nbPages = $pagerfanta->getNbPages();
    }

    private function initializeOptions($options)
    {
        $this->proximity = isset($options['proximity']) ?
                           (int) $options['proximity'] :
                           $this->getDefaultProximity();
		$this->options = $options;
    }

    protected function getDefaultProximity()
    {
        return 2;
    }

    private function configureTemplate($routeGenerator, $options)
    {
        $this->template->setRouteGenerator($routeGenerator);
        $this->template->setOptions($options);
    }

    private function generate()
    {
        $pages = $this->generatePages();

        return $this->generateContainer($pages);
    }

    private function generateContainer($pages)
    {
        return str_replace('%pages%', $pages, $this->template->container());
    }

    private function generatePages()
    {
        $this->calculateStartAndEndPage();

		if(isset($this->options['position']) && $this->options['position'] == 'top') {
			return 
			'<span class="item">'.$this->currentPage.'/'.  $this->endPage.'</span>'.
			$this->previous().
             $this->next();
		}

        return 
			'<p>'.$this->previous().'</p>'.
               $this->first().
               $this->secondIfStartIs3().
               $this->dotsIfStartIsOver3().
               $this->pages().
               $this->dotsIfEndIsUnder3ToLast().
               $this->secondToLastIfEndIs3ToLast().
               '<b>'.$this->last().'</b>'.
			'<p class="next">'.$this->next().'</p>'
				;
    }

    private function calculateStartAndEndPage()
    {
        $startPage = $this->currentPage - $this->proximity;
        $endPage = $this->currentPage + $this->proximity;

        if ($this->startPageUnderflow($startPage)) {
            $endPage = $this->calculateEndPageForStartPageUnderflow($startPage, $endPage);
            $startPage = 1;
        }
        if ($this->endPageOverflow($endPage)) {
            $startPage = $this->calculateStartPageForEndPageOverflow($startPage, $endPage);
            $endPage = $this->nbPages;
        }

        $this->startPage = $startPage;
        $this->endPage = $endPage;
    }

    private function startPageUnderflow($startPage)
    {
        return $startPage < 1;
    }

    private function endPageOverflow($endPage)
    {
        return $endPage > $this->nbPages;
    }

    private function calculateEndPageForStartPageUnderflow($startPage, $endPage)
    {
        return min($endPage + (1 - $startPage), $this->nbPages);
    }

    private function calculateStartPageForEndPageOverflow($startPage, $endPage)
    {
        return max($startPage - ($endPage - $this->nbPages), 1);
    }

    private function previous()
    {
        if ($this->pagerfanta->hasPreviousPage()) {
            return $this->template->previousEnabled($this->pagerfanta->getPreviousPage());
        }

        return $this->template->previousDisabled();
    }

    private function first()
    {
        if ($this->startPage > 1) {
            return '<b>'.$this->template->first().'</b>';
        }
    }

    private function secondIfStartIs3()
    {
        if ($this->startPage == 3) {
            return '<b>'.$this->template->page(2).'</b>';
        }
    }

    private function dotsIfStartIsOver3()
    {
        if ($this->startPage > 3) {
            return '<b class="dots">'.$this->template->separator().'</b>';
        }
    }

    private function pages()
    {
        $pages = '';
        foreach (range($this->startPage, $this->endPage) as $page) {
			if(isset($this->options['position']) && $this->options['position'] == 'bottom') {
            	$pages .= '<b>'.$this->page($page).'</b>';
			} else {
            	$pages .= $this->page($page);
			}
        }

        return $pages;
    }

    private function page($page)
    {
        if ($page == $this->currentPage) {
            return $this->template->current($page);
        }

        return $this->template->page($page);
    }

    private function dotsIfEndIsUnder3ToLast()
    {
        if ($this->endPage < $this->toLast(3)) {
            return '<b class="dots">'.$this->template->separator().'</b>';
        }
    }

    private function secondToLastIfEndIs3ToLast()
    {
        if ($this->endPage == $this->toLast(3)) {
            return $this->template->page($this->toLast(2));
        }
    }

    private function toLast($n)
    {
        return $this->pagerfanta->getNbPages() - ($n - 1);
    }

    private function last()
    {
        if ($this->pagerfanta->getNbPages() > $this->endPage) {
            return $this->template->last($this->pagerfanta->getNbPages());
        }
    }

    private function next()
    {
        if ($this->pagerfanta->hasNextPage()) {
            return $this->template->nextEnabled($this->pagerfanta->getNextPage());
        }

        return $this->template->nextDisabled();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'jiwen';
    }
}

/*

CSS:

.pagerfanta {
}

.pagerfanta a,
.pagerfanta span {
    display: inline-block;
    border: 1px solid blue;
    color: blue;
    margin-right: .2em;
    padding: .25em .35em;
}

.pagerfanta a {
    text-decoration: none;
}

.pagerfanta a:hover {
    background: #ccf;
}

.pagerfanta .dots {
    border-width: 0;
}

.pagerfanta .current {
    background: #ccf;
    font-weight: bold;
}

.pagerfanta .disabled {
    border-color: #ccf;
    color: #ccf;
}

COLORS:

.pagerfanta a,
.pagerfanta span {
    border-color: blue;
    color: blue;
}

.pagerfanta a:hover {
    background: #ccf;
}

.pagerfanta .current {
    background: #ccf;
}

.pagerfanta .disabled {
    border-color: #ccf;
    color: #cf;
}

*/
