<?php

namespace Mentoring\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConversationReplyForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('body', 'textarea', [
                'label' => 'Reply',
                'required' => true,
                'constraints' => new NotBlank()
            ])
            ->add('submit', 'submit')
        ;
    }

    public function getName()
    {
        return 'reply';
    }
}
