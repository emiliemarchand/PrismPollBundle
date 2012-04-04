<?php

namespace Prism\PollBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * PollType
 */
class PollType extends AbstractType
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
            ->add('name')
            ->add('published')
            ->add('closed')
            ->add('opinions', 'collection', array(
                'type' => new $options['opinion_form'],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ));
    }

    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return 'poll';
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
        return array(
            'opinion_form' => 'Prism\PollBundle\Form\OpinionType',
        );
    }
}