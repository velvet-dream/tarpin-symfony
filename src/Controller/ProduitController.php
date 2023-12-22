<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Tax;
use App\Entity\Image;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Form\ProductFormType;




use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ImageRepository;
use App\Repository\CategoryRepository;
use App\Repository\TaxRepository;
 


class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function show_product(ProductRepository $product): Response
    {
        $produit = $product->findAll();

        

        return $this->render('produit/index.html.twig', [
            'produit' => $produit,
        ]);
    }


    #[Route('/gestionProduct', name: 'app_gestionProduct')]
    public function gestionProduct(ProductRepository $product): Response
    {
        $produit = $product->findAll();

        return $this->render('produit/GestionProduit.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/updateProduct/{id}', name: 'app_updateProduct')]
    public function updateProduct(ProductRepository $productRepo, Product $product,FormCategoryService $formCategoryService,
    ): Response
    {

        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);
       



        return $this->render('produit/ajoutProduit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('produit/detail/{id}', name: 'app_detail')]
    public function detail(Product $produit, ProductRepository $productRepo): Response
    {



        // Récupérez les produits similaires en fonction de la catégorie du produit actuel, par exemple.
        $category = $produit->getCategories()->first(); // Récupérez la première catégorie (à adapter selon votre logique)
        $products = $productRepo->getProductByCategory($category->getLabel()); // Obtenez les produits similaires

        return $this->render('produit/detail.html.twig', [
            'produit' => $produit,
            'products' => $products,
        ]);

    }

    #[Route('/produit/{category}', name: 'app_categorie')]
    public function showCategorie (ProductRepository $productRepo, string $category): Response
    {
        $product= $productRepo->getProductByCategory($category);
        
    

        return $this->render('produit/categorie.html.twig', [
            'produit' => $product,
        ]);

    }



    #[Route('/addProduct', name: 'add_product')]
    public function addproduct (Request $request, ProductRepository $productrepo, ImageRepository $imageRepo): Response
    {
        $product = new Product(); // Ou récupérez une catégorie existante à éditer

        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData(); // Récupérer le fichier image soumis
            
            // Vérifier si une image a été soumise
            if ($imageFile) {
                // Générer un nom de fichier unique
                $newFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME).'.'.$imageFile->guessExtension();
    
                // Déplacer le fichier vers le répertoire où vous souhaitez le stocker
                $imageFile->move(
                    $this->getParameter('kernel.project_dir') . '/assets/img/web/', // Remplacez 'dossier_images' par le nom de votre répertoire
                    $newFilename
                );
    
                // Enregistrer le nom de l'image dans l'entité Product
                $img = new Image;
                $img->setName($newFilename);
                $imageRepo->save($img);
                $product->addImage($img);
            }

            $productrepo->save($product);

        }

        return $this->render('produit/ajoutProduit.html.twig', [
            'form' => $form->createView(),
    
    
        ]);

    }

}
