<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PostType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('description')
      ->add('location')
      ->add('postImage', FileType::class, [
        'label' => 'Upload image (JPG or PNG)',
        'mapped' => false,
        'required' => true,
        'constraints' => array_filter([
          new File([
            'maxSize' => '1024k',
            'mimeTypes' => ['image/jpeg', 'image/png'],
            'mimeTypesMessage' => 'Please upload a valid PNG/JPEG image.',
            'maxSizeMessage' => 'Maximum image size: 1024 KB',
          ]),
          $options['is_edit'] === false ? new NotNull(['message' => 'Image is required.']) : null,
        ]),
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Post::class,
      'is_edit' => false
    ]);
  }
}
