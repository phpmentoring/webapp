<?php

namespace Mentoring\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConversationReplyForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('body', TextareaType::class, [
                'label' => 'Reply',
                'required' => true,
                'constraints' => new NotBlank()
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function getName()
    {
        return 'reply';
    }
}
