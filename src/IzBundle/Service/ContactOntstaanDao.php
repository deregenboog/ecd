<?php

namespace IzBundle\Service;

use IzBundle\Entity\ContactOntstaan;
use AppBundle\Service\AbstractDao;

class ContactOntstaanDao extends AbstractDao implements ContactOntstaanDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'contactOntstaan.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'contactOntstaan.id',
            'contactOntstaan.naam',
            'contactOntstaan.actief',
        ],
    ];

    protected $class = ContactOntstaan::class;

    protected $alias = 'contactOntstaan';

    public function create(ContactOntstaan $entity)
    {
        $this->doCreate($entity);
    }

    public function update(ContactOntstaan $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(ContactOntstaan $entity)
    {
        $this->doDelete($entity);
    }
}
