<?php


namespace App\Form;
use App\Entity\Tax;
use App\Entity\Image;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       $builder
       ->add('name')
       ->add('description')
       ->add('price')
       ->add('weight')
       ->add('active')
       ->add('tax')
       ->add('stock')
       ->add('tax', EntityType::class, [
        'class' => \App\Entity\Tax::class,
        'choice_label' => function ($tax) {
            return $tax->getLabel() . ' - ' . $tax->getRate();
        }
        ])
        ->add('categories', EntityType::class, [
            'class' => Category::class,
            'choice_label' => 'label',
            'multiple' => true,

            
        ])
        ->add('image', FileType::class, [
            'label' => 'name',
            'mapped' => false, // Indique que ce champ n'est pas lié directement à une propriété de l'entité Product
            'required' => false, // Rend le champ facultatif
        ])
        
        ->add('submit',SubmitType::class,[
        'label' => 'Ajouter'
       ]);



    }



















}