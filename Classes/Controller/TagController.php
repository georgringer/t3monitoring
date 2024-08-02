<?php

declare(strict_types=1);

namespace T3Monitor\T3monitoring\Controller;

/*
 * This file is part of the t3monitoring extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Psr\Http\Message\ResponseInterface;
use T3Monitor\T3monitoring\Domain\Model\Tag;
use T3Monitor\T3monitoring\Domain\Repository\TagRepository;
use TYPO3\CMS\Backend\Attribute\AsController;

#[AsController]
class TagController extends BaseController
{
    protected TagRepository $tagRepository;

    public function injectTagRepository(TagRepository $tagRepository): void
    {
        $this->tagRepository = $tagRepository;
    }

    public function listAction(): ResponseInterface
    {
        $tags = $this->tagRepository->findAll();
        $this->view->assign('tags', $tags);
        return $this->htmlResponse();
    }

    public function showAction(?Tag $tag = null): ResponseInterface
    {
        if ($tag === null) {
            return $this->redirect('index', 'Statistic');
        }

        $demand = $this->getClientFilterDemand();
        $demand->setTag($tag->getUid());
        $this->view->assignMultiple([
            'tag' => $tag,
            'clients' => $this->clientRepository->findByDemand($demand)
        ]);
        return $this->htmlResponse();
    }
}
