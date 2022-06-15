<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Entity\Produit;
use App\Form\SortieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class SortieController extends AbstractController
{
    #[Route('/Sortie/liste', name: 'sortie_liste')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie, [
            'action' => $this->generateUrl('sortie_add'),
            'method' => 'POST',
        ]);
        $data['form'] = $form->createView();
        $data['sorties'] = $entityManager->getRepository(Sortie::class)->findAll();
        return $this->render('sortie/liste.html.twig', $data);
    }


    #[Route('/Sortie/add', name: 'sortie_add')]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);
        $entityManager = $doctrine->getManager();
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $user = $this->getUser();
            $sortie = $form->getData();
            $sortie->setUser($user);
            $sortie = $form->getData();
            
            $produit = $entityManager->getRepository(Produit::class)->find($sortie->getProduit()->getId());
            if ($produit->getStock() >= $sortie->getQuantite()) {
                $entityManager->persist($sortie);
                $entityManager->flush();

                // Mise à jour du stock après chaque achat
                $nouveauStock = $produit->getStock() - $sortie->getQuantite();
                $produit->setStock($nouveauStock);
                $entityManager->flush();
                $data['addingMessage'] = "Vente éffectuée avec succès";
                
                return $this->redirectToRoute('sortie_liste', $data);
            }
            $data['addingMessage'] = "Quantité en stock (".$produit->getStock().") insuffisante pour éffectuer la vente";
            return $this->redirectToRoute('sortie_liste', $data);
        }
        return $this->redirectToRoute('sortie_liste');
    }
}
