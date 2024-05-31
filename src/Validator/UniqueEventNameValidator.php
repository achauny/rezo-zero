<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Event;

class UniqueEventNameValidator extends ConstraintValidator
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint): void
    {
        /* @var $constraint UniqueEventName */

        if (!$value instanceof Event) {
            throw new \LogicException('Ceci n\'est pas un objet Event');
        }

        if (null === $value->getName() || '' === $value->getName()) {
            return;
        }

        $existingEvent = $this->entityManager->getRepository(Event::class)
            ->findOneBy(['name' => $value->getName()]);

        if ($existingEvent && $existingEvent->getId() !== $value->getId()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value->getName())
                ->addViolation();
        }
    }
}