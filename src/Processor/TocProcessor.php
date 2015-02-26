<?php
namespace Bookdown\Bookdown\Processor;

use Aura\Cli\Stdio;
use Bookdown\Bookdown\Content\Page;
use Bookdown\Bookdown\Content\IndexPage;

class TocProcessor
{
    protected $tocEntries;

    public function __invoke(Page $page, Stdio $stdio)
    {
        if (! $page->isIndex()) {
            $stdio->outln("Skipping TOC entries for non-index {$page->getTarget()}");
            return;
        }

        $stdio->outln("Adding TOC entries for {$page->getTarget()}");
        $this->tocEntries = array();
        $this->addTocEntries($page);
        $page->setTocEntries($this->tocEntries);
    }

    protected function addTocEntries(IndexPage $index)
    {
        foreach ($index->getChildren() as $child) {
            $headings = $child->getHeadings();
            foreach ($headings as $heading) {
                $this->tocEntries[] = $heading;
            }
            if ($child->isIndex()) {
                $this->addTocEntries($child);
            }
        }
    }
}
