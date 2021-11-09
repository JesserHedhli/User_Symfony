<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Prod;
use App\Form\ProductType;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="ListProd")
     */
    public function index(): Response
    {
        $repo = $this->getDoctrine()->getRepository(Prod::class);
        $products = $repo->findAll();
        #products=["article1","article2","article2"];
        # $products=[1, {"libel": "article1","prix":"12","description":""}]
        return $this->render('product/index.html.twig', ['products' => $products]);
    }


    /**
     * @Route("/product/add", name="add")
     */
    public function add(): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $product = new Prod();
        $product->setLibelle("lib_test2")
            ->setPrix('500')
            ->setDescription("test description de l'article")
            ->setImage("http://placehold.it/350*150");
        $manager->persist($product);

        $manager->flush();
        return new Response('ajout validé' . $product->getId());
    }


    /**
     * @Route("/product/detail/{id}", name="detail")
     */
    public function detail($id): Response
    {
        $repo = $this->getDoctrine()->getRepository(Prod::class);
        $product = $repo->find($id);
        return $this->render('product/detail.html.twig', ['product' => $product]);
    }


    /**
     * @Route("/product/delete/{id}", name="delete")
     */
    public function delete($id): Response
    {
        $repo = $this->getDoctrine()->getRepository(Prod::class);
        $product = $repo->find($id);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($product);
        $manager->flush();


        return new Response('Suppression Valider');
        #return $this->render('product/index.html.twig', ['product' => $product]);
    }

    /**
     * @Route("/product/add2", name="add")
     */
    public function new(Request $request): Response
    {
        $prod = new Prod();


        $form = $this->createForm(ProductType::class, $prod);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            #$task = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($prod);
            $entityManager->flush();

            return $this->redirectToRoute('ListProd');
        }


        return $this->renderForm('product/new.html.twig', [
            'formpro' => $form,
        ]);
    }
}
