<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EtudiantRepository;
use App\Repository\MatiereRepository;
use App\Entity\Note;




class FormulaireController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(EtudiantRepository $etudiantRepository,MatiereRepository $matiereRepository,Request $request)
    {

        $etudiants = $etudiantRepository->findAll();
        $matiers = $matiereRepository->findAll();

        $JsonEtudiantes = [];
        $JsonMatiers = [];

        foreach ( $etudiants as  $etudiant) {

            $etudiantObjet = [
            "nom" => $etudiant->getNom()
            ];
            $JsonEtudiantes[]= $etudiantObjet;
        }


        foreach ( $matiers as $matier) {

            $matierObjet = [
            "matier" => $matier->getNom()
            ];
            $JsonMatiers[]= $matierObjet;
        }
        
        return $this->render('formulaire/index.html.twig',[
        'etudiants' =>  $JsonEtudiantes,
        'matiers' => $JsonMatiers,

        ]);
    }

    /**
     * @Route("/addNote", name="add_note")
     */
    public function insertNote(Request $request,EtudiantRepository $etudiantRepository,MatiereRepository $matiereRepository)
    {
    
        $matiere =  $request->request->get('matiere');
        $etudiant =  $request->request->get('etudiant');
        $cofficient = (int)($request->request->get('cofficient'));
        $noteEtudiant = (int) ($request->request->get('note'));
      


        $entityManager = $this->getDoctrine()->getManager();
        $note = new Note();
        $newEtudiant = $etudiantRepository->findOneBy(['nom' => $etudiant]);
        $newMatiere = $matiereRepository->findOneBy(['nom' =>  $matiere]);

        $note->setCofficient($cofficient);
        $note->setNote($noteEtudiant);
        $note->addEtudiant($newEtudiant);



        $entityManager->persist($note);
        $entityManager->flush();

        
        return $this->redirectToRoute('homepage');

    }
}
