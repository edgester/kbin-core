<?php

declare(strict_types=1);

namespace App\Controller\Entry\Comment;

use App\Controller\AbstractController;
use App\Entity\Entry;
use App\Entity\EntryComment;
use App\Entity\Magazine;
use App\Form\LangType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EntryCommentModerateController extends AbstractController
{
    #[ParamConverter('magazine', options: ['mapping' => ['magazine_name' => 'name']])]
    #[ParamConverter('entry', options: ['mapping' => ['entry_id' => 'id']])]
    #[ParamConverter('comment', options: ['mapping' => ['comment_id' => 'id']])]
    #[IsGranted('moderate', subject: 'comment')]
    public function __invoke(
        Magazine $magazine,
        Entry $entry,
        EntryComment $comment,
        Request $request
    ): Response {
        if ($entry->magazine !== $magazine) {
            return $this->redirectToRoute(
                'entry_single',
                ['magazine_name' => $entry->magazine->name, 'entry_id' => $entry->getId(), 'slug' => $entry->slug],
                301
            );
        }

        $form = $this->createForm(LangType::class);
        $form->get('lang')
            ->setData($comment->lang);

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'html' => $this->renderView('entry/comment/_moderate_panel.html.twig', [
                    'magazine' => $magazine,
                    'entry' => $entry,
                    'comment' => $comment,
                    'form' => $form->createView()
                ]),
            ]);
        }

        return $this->render('entry/comment/moderate.html.twig', [
            'magazine' => $magazine,
            'entry' => $entry,
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }
}
