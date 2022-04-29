<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Service\VerificationCommentService;
use Doctrine\DBAL\Schema\View;
use Doctrine\ORM\EntityManagerInterface;
use Proxies\__CG__\App\Entity\Article as EntityArticle;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType as TypeDateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    // /article -> va lister l'ensemble des articles

    /**
    * @Route("/default", name="liste_articles", methods={"GET"})
    */

    public function listArticles(ArticleRepository $articleRepository): Response {

        $articles = $articleRepository->findBy([
            'state' => 'publier'
        ]);

        return $this->render('default/index.html.twig', [
            'articles' => $articles,
            'brouillon' => false
        ]);
    }

    // /12 -> va afficher le détail de l'article 12

    /**
    * @Route("/{id}", name="vue_article", requirements={"id"="\d+"}, methods={"GET", "POST"})
    */

    public function vueArticle(Article $article, HttpFoundationRequest $request, EntityManagerInterface $manager, VerificationCommentService $verifComment, FlashBagInterface $session) {

        
        $comment = new Comment();
        $comment->setArticle($article);
        
        $form = $this->createForm(CommentType::class, $comment);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {

            if($verifComment->commentaireNonAutorise($comment) == false) {
                $manager->persist($comment);
                $manager->flush();
                
                return $this->redirectToRoute('vue_article', ['id' => $article->getId()]);
            }
            else
            {
                $session->add('danger', 'Le commentaire contient un mot interdit');
            }
        }

        return $this->render('default/vue.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }
}
