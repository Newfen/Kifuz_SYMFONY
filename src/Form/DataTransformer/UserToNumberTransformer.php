<?php

namespace App\Form\DataTransformer;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class UserToNumberTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Transforms an object (user) to a string (number).
     *
     * @param  User|null $user
     */
    public function transform($user): string
    {
        if (null === $user) {
            return '';
        }

        return $user->getId();
    }

    /**
     * Transforms a string (number) to an object (user).
     *
     * @param  string $userNumber
     * @throws TransformationFailedException if object (user) is not found.
     */
    public function reverseTransform($userNumber): ?User
    {
        // no user number? It's optional, so that's ok
        if (!$userNumber) {
            return null;
        }

        $user = $this->entityManager
            ->getRepository(User::class)
            // query for the user with this id
            ->find($userNumber)
        ;

        if (null === $user) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An user with number "%s" does not exist!',
                $userNumber
            ));
        }

        return $user;
    }
}