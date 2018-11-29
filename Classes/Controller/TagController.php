<?php
namespace T3Monitor\T3monitoring\Controller;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use T3Monitor\T3monitoring\Domain\Model\Tag;
use T3Monitor\T3monitoring\Domain\Repository\TagRepository;

/**
 * TagController
 */
class TagController extends BaseController
{

    /**
     * @var TagRepository
     */
    protected $tagRepository = null;

    /**
     * @param TagRepository $tagRepository
     */
    public function injectTagRepository(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * action list
     */
    public function listAction()
    {
        $tags = $this->tagRepository->findAll();
        $this->view->assign('tags', $tags);
    }

    /**
     * action show
     *
     * @param Tag $tag
     */
    public function showAction(Tag $tag = null)
    {
        if ($tag === null) {
            $this->redirect('index', 'Statistic');
        }

        $demand = $this->getClientFilterDemand();
        $demand->setTag($tag->getUid());
        $this->view->assignMultiple([
            'tag' => $tag,
            'clients' => $this->clientRepository->findByDemand($demand)
        ]);
    }
}
