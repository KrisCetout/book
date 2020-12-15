<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Category;
use PDO;

class CategoryController extends AbstractController
{
    public function index(): Response
    {
        
        $db = new PDO(
            'mysql:host=home.3wa.io:3307;dbname=live-38_christopherlim_book;charset=UTF8', 
            'christopherlim', 
            '874c7cbbZjA0MGM1MWM2Nzc3NDlhYjU1ZjdkZTlm807d4b25', [
                // On active les erreurs lors des requêtes
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                // On récupère les résultats dans un tableau associatif uniquement
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
        
        $query = $db->prepare('SELECT * FROM category');
        $query->execute();
        $categories = $query->fetchAll();
        
        return $this->render('categories/index.html.twig', [
            'categories' => $categories   
        ]);
    }
    
    public function show(int $id): Response
    {
        // $id correspond au numéro dans l'url
        
        // dd = var_dump + exit
        
        $db = new PDO(
            'mysql:host=home.3wa.io:3307;dbname=live-38_christopherlim_book;charset=UTF8', 
            'christopherlim', 
            '874c7cbbZjA0MGM1MWM2Nzc3NDlhYjU1ZjdkZTlm807d4b25', [
                // On active les erreurs lors des requêtes
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                // On récupère les résultats dans un tableau associatif uniquement
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
        
        $query = $db->prepare('SELECT * FROM category WHERE id = ?');
        $query->execute([
            $id
        ]);
        
        $category = $query->fetch();
        
        // Si l'article n'a pas été trouvé dans la bdd pour l'id spécifié
        if ($category === null) {
            throw new NotFoundHttpException("Cette catégorie n'existe pas");
        }
        
       return $this->render('categories/show.html.twig', [
            'category' => $category,
        ]);
    }
    
    
    public function create(): Response
    {
        return $this->render('categories/create.html.twig');

    }
    
    public function store(): RedirectResponse
    {
        $request = Request::createFromGlobals();
        $formData = $request->request->all();       // Equivalent $_POST
        
        // Doctrine de faire le lien entre mon objet et la base de données
        $entityManager = $this->getDoctrine()->getManager();
        
        $category = new Category();
        $category->setTitle($request->request->get('title'));

        
        // Envoi dans la base de données
        $entityManager->persist($category);
        $entityManager->flush();
        
        return $this->redirectToRoute('categories');
    }
}
