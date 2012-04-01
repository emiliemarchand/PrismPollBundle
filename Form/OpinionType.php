<?php

namespace Prism\PollBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * OpinionType
 */
class OpinionType extends AbstractType
{
    /**
     * Build Form
     *
     * @param FormBuilder $builder
     * @param array       $options
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('name');
    }

    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return 'opinion';
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
            'data_class' => 'Prism\PollBundle\Entity\Opinion',
        );
    }
}