<?php

namespace Mentoring\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConversationStartForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', 'text', [
                'required' => true,
                'constraints' => new NotBlank()
            ])
            ->add('body', 'textarea', [
                'label' => 'Message',
                'required' => true,
                'constraints' => new NotBlank()
            ])
            ->add('to', 'hidden', [
                'required' => false
            ])
            ->add('submit', 'submit')
        ;
    }

    public function getName()
    {
        return 'start_conversation';
    }
}
