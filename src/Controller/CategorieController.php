<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Container51IulZ0\getDoctrine_UlidGeneratorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class CategorieController extends AbstractController
{
    #[Route('/Categorie/liste', name: 'categorie_liste')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie, [
            'action' => $this->generateUrl('categorie_add'),
            'method' => 'POST',
        ]);
        $data['form'] = $form->createView();
        $data['categories'] = $em->getRepository(Categorie::class)->findAll();
        return $this->render('categorie/liste.html.twig', $data);
    }


    #[Route('/Categorie/add', name: 'categorie_add')]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $categorie = $form->getData();
            
            $entityManager = $doctrine->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('categorie_liste');
        }
        return $this->redirectToRoute('categorie_liste',);
    }
}
