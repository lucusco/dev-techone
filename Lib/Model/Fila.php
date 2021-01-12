<?php

namespace Techone\Lib\Model;

use Techone\Lib\Api\DataRecord;
use Techone\Lib\Helper\ModelFunctionsTrait;

class Fila extends DataRecord
{
    use ModelFunctionsTrait;
    
    const TABLENAME = 'queues';

    private $id;
    private $number;
    private $description;
    private $strategy;
    private $extensions;



}