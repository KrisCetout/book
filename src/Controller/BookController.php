<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Book;
use App\Entity\Category;
use PDO;

class BookController extends AbstractController
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
        
        $query = $db->prepare('SELECT * FROM book');
        $query->execute();
        $books = $query->fetchAll();
        
        return $this->render('books/index.html.twig', [
            'books' => $books   
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
        
        $query = $db->prepare('SELECT * FROM book WHERE id = ?');
        $query->execute([
            $id
        ]);
        
        $book = $query->fetch();
        
        
        return $this->render('books/show.html.twig', [
            'book' => $book,
        ]);
    }
    
    
    public function create(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repository->findAll();
        
        return $this->render('books/create.html.twig', [
            'categories' => $categories    
        ]);

    }
    
    public function store(): RedirectResponse
    {
        $request = Request::createFromGlobals();
        $formData = $request->request->all();       // Equivalent $_POST
        
        // Doctrine de faire le lien entre mon objet et la base de données
        $entityManager = $this->getDoctrine()->getManager();

        $category = $entityManager->getReference(Category::class, $request->request->get('category'));
        
        $book = new Book();
        $book->setTitle($request->request->get('title'));
        $book->setCategory($category);
        $book->setOverview($request->request->get('overview'));
        $book->setCreatedAt(new \Datetime());
        
        // Envoi dans la base de données
        $entityManager->persist($book);
        $entityManager->flush();
        
        return $this->redirectToRoute('book');
    }
}
