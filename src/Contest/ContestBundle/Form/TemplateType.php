<?php

namespace ContestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ContestBundle\Entity\Template;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\CallbackTransformer;

class TemplateType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('thumbnail', FileType::class, array(
            'required' => false,
            'attr' => array(
                'class' => 'btn btn-success'
            )
        ))
        ->add('headerImage', FileType::class, array(
            'required' => false,
            'attr' => array(
                'class' => 'btn btn-success'
            )
        ))
        ->add('backgroundImage', FileType::class, array(
            'required' => false,
            'attr' => array(
                'class' => 'btn btn-success'
            )
        ))
        ->add('footerImage', FileType::class, array(
            'required' => false,
            'attr' => array(
                'class' => 'btn btn-success'
            )
        ))
        ->add('backgroundColor', ColorType::class, array(
            'required' => false,
        ))
        ->add('fontColor', ColorType::class, array(
            'required' => false,
        ))
        ->add('fontSize', IntegerType::class, array(
            'required' => false,
        ))
        ->add('postPerLine', IntegerType::class, array(
            'required' => false,
        ))
        ->add('postThumbnailWidth', IntegerType::class, array(
            'required' => false,
        ))
        ->add('spaceBetweenPost', IntegerType::class, array(
            'required' => false,
        ))
        ->add('submit', SubmitType::class, array(
            'label' => 'Zapisz',
            'attr' => array(
                'class' => 'btn btn-primary'
            )
        ));    
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Template::class,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'contestbundle_template';
    }


}
