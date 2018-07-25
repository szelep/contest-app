<?php

namespace ContestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use ContestBundle\Entity\Vote;

class VoteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('post', SubmitType::class, array(
                'label' => false,
                'attr'  => array(
                    'class' => 'fa fa-heart',
                    'data-send' => 'ajax',
                    'data-href' => $options['post_href'],
                    'data-action'  => 'vote',
                ),
            ));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Vote::class,
            'post_href'  => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'contestbundle_vote';
    }


}
