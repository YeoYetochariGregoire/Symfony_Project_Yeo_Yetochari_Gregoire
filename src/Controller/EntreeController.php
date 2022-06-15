<?php

namespace App\Controller;

use App\Entity\Entree;
use App\Entity\Produit;
use App\Form\EntreeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class EntreeController extends AbstractController
{
    #[Route('/Entree/liste', name: 'entree_liste')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $entree = new Entree();
        $form = $this->createForm(EntreeType::class, $entree, [
            'action' => $this->generateUrl('entree_add'),
            'method' => 'POST',
        ]);
        $data['form'] = $form->createView();
        $data['entrees'] = $em->getRepository(Entree::class)->findAll();
        return $this->render('entree/liste.html.twig', $data);
    }

    #[Route('/Entree/add', name: 'entree_add')]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $entree = new Entree();
        $form = $this->createForm(EntreeType::class, $entree);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $entree = $form->getData();
            $entree->setUser($user);
            $entree = $form->getData();
            
            $entityManager = $doctrine->getManager();
            $entityManager->persist($entree);
            $entityManager->flush();

            // Mise à jour du stock après chaque achat
            $produit = $entityManager->getRepository(Produit::class)->find($entree->getProduit()->getId());
            $nouveauStock = $produit->getStock() + $entree->getQuantite();
            $produit->setStock($nouveauStock);
            $entityManager->flush();

            return $this->redirectToRoute('entree_liste');
        }
        return $this->redirectToRoute('entree_liste');
    }
}
