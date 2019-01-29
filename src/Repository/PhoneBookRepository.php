<?php

namespace Project\Repository;

use Jhg\DoctrinePagination\ORM\PaginatedRepository;
use Jhg\DoctrinePagination\ORM\PaginatedQueryBuilder;

/**
 * Class PhoneBookRepository
 * @package Project\Repository
 */
class PhoneBookRepository extends PaginatedRepository
{
    /**
     * {@inheritdoc}
     */
    protected function processCriteria(PaginatedQueryBuilder $qb, array $criteria)
    {
        foreach ($criteria as $field => $value) {
            switch ($field) {
                case 'firstNameLike':
                    $qb->andWhere(sprintf('%s.firstName LIKE :firstName', $this->getEntityAlias()))->setParameter('firstName', $value . '%');
                    unset($criteria[$field]);
                    break;
            }
        }

        parent::processCriteria($qb, $criteria);
    }

}