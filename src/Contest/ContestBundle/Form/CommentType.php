<?php

namespace ContestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ContestBundle\Entity\Comment;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

class CommentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('value', TextareaType::class, array(
            'label' => 'Treść komentarza',
            'constraints' => array(
                new Assert\Length(array(
                    'max' => 280,
                )),
                new Assert\NotBlank(array(
                    'message' => 'Komentarz nie może być pusty!'
                ))
            )
        ))
        ->add('post', SubmitType::class, array(
            'label' => 'Dodaj komentarz',
            'attr'  => array(
                'class' => 'btn btn-success',
                'data-send' => 'ajax',
                'data-href' => $options['post_href']
            ),
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Comment::class,
            'post_href'  => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'contestbundle_comment';
    }


}
