<?php

namespace App\Form\DataTransformer;

use App\Entity\Programmation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ProgrammationToNumberTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Transforms an object (programmation) to a string (number).
     *
     * @param  Programmation|null $programmation
     */
    public function transform($programmation): string
    {
        if (null === $programmation) {
            return '';
        }

        return $programmation->getId();
    }

    /**
     * Transforms a string (number) to an object (programmation).
     *
     * @param  string $programmationNumber
     * @throws TransformationFailedException if object (programmation) is not found.
     */
    public function reverseTransform($programmationNumber): ?Programmation
    {
        // no programmation number? It's optional, so that's ok
        if (!$programmationNumber) {
            return null;
        }

        $programmation = $this->entityManager
            ->getRepository(Programmation::class)
            // query for the programmation with this id
            ->find($programmationNumber)
        ;

        if (null === $programmation) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An programmation with number "%s" does not exist!',
                $programmationNumber
            ));
        }

        return $programmation;
    }
}