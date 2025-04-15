<?php

namespace App\Form;

use App\Entity\UserProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfileType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name')
      ->add('bio')
      ->add('websiteUrl')
      ->add('photogramUsername')
      ->add('location')
      ->add('dateOfBirth', null, [
        'widget' => 'single_text',
      ])
      ->add('company')
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => UserProfile::class,
    ]);
  }
}
