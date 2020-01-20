<?php

namespace App\ArgumentResolver;

use App\DTO\IRequestDto;
use App\DTO\PostFilterRequest;
use App\Service\ServiceInterface;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestPostFilterResolver implements ArgumentValueResolverInterface
{
    private $validator;

    /**
     * RequestPostFilterResolver constructor.
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @inheritDoc
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        // @TODO add check by Interface (some issue with ReflectionClass)
        if (null !== $argument->getType()
            && PostFilterRequest::class === $argument->getType()
        ) {
            return true;
        }

        return false;
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        // creating new instance of custom request DTO
        $dto = new PostFilterRequest($request);
        $errors = $this->validator->validate($dto);
//        dd($argument);
        if (count($errors) > 0) {
            throw new BadRequestHttpException((string)$errors);
        }

        yield $dto;
    }
}
