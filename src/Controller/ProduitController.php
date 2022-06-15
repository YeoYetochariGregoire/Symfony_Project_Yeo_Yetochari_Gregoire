<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class ProduitController extends AbstractController
{
    #[Route('/Produit/liste', name: 'produit_liste')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit, [
            'action' => $this->generateUrl('produit_add'),
            'method' => 'POST',
        ]);
        $data['form'] = $form->createView();
        $data['produits'] = $em->getRepository(Produit::class)->findAll();
        return $this->render('produit/liste.html.twig', $data);
    }

    #[Route('/Produit/add', name: 'produit_add')]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $user = $this->getUser();
            $produit = $form->getData();
            $produit->setUser($user);
            
            $entityManager = $doctrine->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('produit_liste');
        }
        return $this->redirectToRoute('produit_liste');
    }
}
