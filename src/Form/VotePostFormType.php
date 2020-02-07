<?php

namespace App\Form;

use App\DTO\PostVote as PostVoteDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as CustomAssert;

class VotePostFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'vote',
                HiddenType::class,
                [
                    'constraints' => [
                        new Assert\NotBlank(),
                        new CustomAssert\VoteValue()
                    ],
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PostVoteDto::class,
            'attr' => [
                'id'=> 'vote_form_id',
                'type' => 'hidden'
            ],
        ]);
    }
}
