<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoryFormType extends AbstractType
{

    public function __construct(
        private CategoryRepository $categoryRepository
    )

    {
        $this->categoryRepository = $categoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label')
            ->add('description')
            ->add('active')
            ->add('dateAdd')
            // ->add('dateUpdate')
            ->add('isRootCategory')
            ->add('positionning')
            // ->add('products', EntityType::class, [
            //     'class' => Product::class,
            //     'choice_label' => 'id',
            //     'multiple' => true,
            // ])
            // ->add('parentCategory', EntityType::class, [
            //     'class' => Category::class,
            //     'choice_label' => 'id'])

            // ->add('parentCategory', ChoiceType::class, [
            //     // 'class' => Category::class,
            //     'choices' => [
            //         'Catégorie parent' => null,
                    
                    
            //     ] ])

            ->add('submit', SubmitType::class, [
                    'label' => 'Ajouter',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
