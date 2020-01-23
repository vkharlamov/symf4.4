<?php

namespace App\Form;

use App\Dictionary\Constants;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Assert;

class PostFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Post|null $article */
        $article = $options['data'] ?? null;
        $isEdit = $article && $article->getId();

        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length([
                            'max' => Constants::POST_MAX_TITLE,
                            'min' => Constants::POST_MIN_TITLE,
                        ]),
                    ],
                    'attr' => ['class' => 'form-group form-control']
                ]
            )
//            ->add('author', UserSelectTextType::class, [
//                'disabled' => $isEdit
//            ])
            ->add(
                'content',
                TextareaType::class,
                [
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length([
                            'max' => Constants::POST_MAX_CONTENT,
                            'min' => Constants::POST_MIN_CONTENT,
                        ]),
                    ],
                    'attr' => ['class' => 'form-group form-control']
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class
        ]);
    }
}
