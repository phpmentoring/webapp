<?php

namespace Mentoring\Account\Form;

use Mentoring\Account\Form\DataTransformer\TextToTagsTransformer;
use Mentoring\Account\Validator\Constraints\TimezoneConstraint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Mentoring\Account\Validator\Constraints\TagConstraint;

class ProfileForm extends AbstractType
{
    protected $taxonomyService;

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => function (FormInterface $form) {
                $rules = ['Default'];
                $data = $form->getData();

                if ($data->isMentee()) {
                    $rules[] = 'menteeValidation';
                }

                if ($data->isMentor()) {
                    $rules[] = 'mentorValidation';
                }

                return $rules;
            },
        ));
    }

    public function __construct($taxonomyService)
    {
        $this->taxonomyService = $taxonomyService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $mentorTags = $builder
            ->create('mentorTags', TextType::class, [
                    'required' => false,
                    'constraints' => new TagConstraint(array('groups' => array('mentorValidation'))),
            ])
            ->addModelTransformer(new TextToTagsTransformer($this->taxonomyService, 'mentor'));

        $menteeTags = $builder
            ->create('apprenticeTags', TextType::class, [
                    'required' => false,
                    'constraints' => new TagConstraint(array('groups' => array('menteeValidation'))),
            ])
            ->addModelTransformer(new TextToTagsTransformer($this->taxonomyService, 'apprentice'));

        $builder
            ->add('name', TextType::class, ['constraints' => new NotBlank()])
            ->add('email', EmailType::class, [
                'constraints' => [new Email()],
            ])
            ->add('timezone', TimezoneType::class, [
                'required' => false,
                'constraints' => new TimezoneConstraint(),
            ])
            ->add('isMentor', CheckboxType::class, [
                'required' => false,
            ])
            ->add($mentorTags)
            ->add('isMentee', CheckboxType::class, [
                'required' => false,
            ])
            ->add($menteeTags)
            ->add('profile', TextareaType::class, [
                'required' => false,
            ])
            ->add('sendNotifications', CheckboxType::class, [
                'required' => false,
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function getName()
    {
        return 'profile';
    }
}
