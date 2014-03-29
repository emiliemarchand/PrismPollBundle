<?php

namespace Prism\PollBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Choice;

/**
 * VoteType
 */
class VoteType extends AbstractType
{
    /**
     * Build Form
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('opinions', 'choice', array(
                'multiple' => false,
                'expanded' => true,
                'choices' => $options['opinionsChoices'],
                'constraints' => array(
                    new NotNull(array('message' => "Please select a choice.")),
                    new Choice(array('choices' => array_keys($options['opinionsChoices'])))
                ))
            );
    }

    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return "vote";
    }

    /**
     * Set Default Options
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array('opinionsChoices'));
    }
}