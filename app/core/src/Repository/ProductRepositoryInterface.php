<?php namespace core\src\Repository;

interface ProductRepositoryInterface {

    public function obCreate();

    public function dbQuery();

    public function find($objectId);

    public function all();

    public function getItems($date, $from = null);

    public function getLastDate($from = null);
} 