<?php

namespace AppBundle\Doctrine;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Subselect;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

/**
 * FirstFunction ::=
 *     "FIRST" "(" Subselect ")"
 */
class FirstFunction extends FunctionNode
{
    /**
     * @var Subselect
     */
    private $subselect;

    public function parse(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);
        $this->subselect = $parser->Subselect();
        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return '(' . $this->subselect->dispatch($sqlWalker) . ' LIMIT 1)';
    }
}
