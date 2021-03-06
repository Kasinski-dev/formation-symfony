<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use App\Service\PaginationService;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments/{page<\d+>?1}", name="admin_comment_index")
     */
    public function index(CommentRepository $repo, $page, PaginationService $pagination)
    {
        // $repo = $this->getDoctrine()->getRepository(Comment::class);
        // $comments = $repo->findAll();

        $pagination->setEntityClass(Comment::class)
                   ->setLimit(5)
                   ->setPage($page);

        return $this->render('admin/comment/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * Permet de modifier un commentaire
     * 
     * @Route("/admin/comments/{id}/edit", name="admin_comment_edit")
     *
     * @return Response
     */
    public function edit(Comment $comment, Request $request, ObjectManager $manager){

        $form = $this->createForm(AdminCommentType::class, $comment);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
           
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash('success', "Le commentaire numéro <strong>{$comment->getId()}</strong> a bien été enregistré  !");
        }

        return $this->render('admin/comment/edit.html.twig', [
                'comment' => $comment,
                'form' => $form->createView()
            ]);
    }

    /**
     * Permet de supprimer un commentaire
     * 
     * @Route("/admin/comments/{id}/delete", name="admin_comment_delete")
     *
     * @param Comment $comment
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Comment $comment, ObjectManager $manager)
    {
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le commentaire de <strong>{$comment->getauthor()->getFullName()}</strong> a bien été supprimée !"
        );
    
        return $this->redirectToRoute('admin_comment_index');
    }
}
