<?php

namespace App\Form;

use App\DTO\PostVote as PostVoteDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
//                    'empty_data' => '',
//                    'mapped' => false,
                    /*'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length([
                            'max' => Constants::COMMENT_MAX_LENGTH,
                            'min' => Constants::COMMENT_MIN_LENGTH,
                        ]),
                    ]*/
                    'attr' => ['type' => 'hidden']
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
            'attr' => ['id'=> 'vote_form_id']
        ]);
    }
}
