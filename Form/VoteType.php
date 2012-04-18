<?php

namespace Prism\PollBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Form\Exception\MissingOptionsException;

/**
 * VoteType
 */
class VoteType extends AbstractType
{
    /**
     * Build Form
     *
     * @param FormBuilder $builder
     * @param array       $options
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('opinions', 'choice', array(
                'multiple' => false,
                'expanded' => true,
                'choices' => $options['opinionsChoices']
            ));
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
     * Get Default Options
     *
     * @param array $options
     *
     * @return array
     */
    public function getDefaultOptions(array $options)
    {
        if (!isset($options['opinionsChoices'])) {
            throw new MissingOptionsException("You must provide the \"opinionsChoices\" option.", $options);
        }

        $collectionConstraint = new Collection(array(
            'opinions' => array(
                new NotNull(array('message' => "Please select a choice.")),
                new Choice(array('choices' => array_keys($options['opinionsChoices'])))
            )
        ));

        return array(
            'opinionsChoices' => $options['opinionsChoices'],
            'validation_constraint' => $collectionConstraint
        );
    }
}