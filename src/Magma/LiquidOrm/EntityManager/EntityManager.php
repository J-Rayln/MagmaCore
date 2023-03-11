<?php

declare(strict_types=1);

namespace Magma\LiquidOrm\EntityManager;

use Magma\LiquidOrm\EntityManager\CrudInterface;

class EntityManager implements EntityManagerInterface
{
    /** @var CrudInterface */
    protected CrudInterface $crud;

    /**
     * Main constructor.
     * 
     * @param CrudInterface $crud 
     * @return void 
     */
    public function __construct(CrudInterface $crud)
    {
        $this->crud = $crud;
    }

    /**
     * @inheritDoc
     * 
     * @return object 
     */
    public function getCrud(): object
    {
        return $this->crud;
    }
}
