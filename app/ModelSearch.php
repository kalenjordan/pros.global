<?php

namespace App;

use DB;
use OE\Lukas\Parser\QueryParser;
use OE\Lukas\QueryTree\ConjunctiveExpressionList;
use OE\Lukas\QueryTree\DisjunctiveExpressionList;
use OE\Lukas\QueryTree\ExplicitTerm;
use OE\Lukas\QueryTree\Negation;
use OE\Lukas\QueryTree\SubExpression;
use OE\Lukas\QueryTree\Text;
use OE\Lukas\QueryTree\Word;

/**
 * Class ContactSearch
 *
 * @package App
 */
class ModelSearch
{
    protected $searchFilters;
    protected $alias;

    public function parseSearchFilters($query)
    {
        $filters = [];
        $parser = new QueryParser();
        $parser->readString($query);
        $parsedQuery = $parser->parse();

        if ($parsedQuery instanceof ExplicitTerm) {
            $this->_parseSearchFilter($parsedQuery, $filters);
        } elseif ($parsedQuery instanceof ConjunctiveExpressionList) {
            foreach ($parsedQuery->getExpressions() as $expression) {
                if ($expression instanceof ExplicitTerm) {
                    $this->_parseSearchFilter($expression, $filters);
                }
            }
        }

        return $filters;
    }

    /**
     * @param $expression ExplicitTerm
     * @param $filters
     */
    protected function _parseSearchFilter($expression, & $filters)
    {
        /** @var Word $nominator */
        $nominator = $expression->getNominator();
        $nominatorType = $nominator->getToken();
        $term = $expression->getTerm();
        $termToken = $term->getToken();

        foreach ($this->searchFilters as $searchFilter) {
            if ($nominatorType == $searchFilter) {
                $filters[$nominatorType] = $termToken;
            }
        }
    }

    /**
     * @param $parsedQuery ExplicitTerm
     */
    protected function _callSearchFilterFunction($collection, $parsedQuery)
    {
        /** @var Word $nominator */
        $nominator = $parsedQuery->getNominator();
        $nominatorType = $nominator->getToken();
        $term = $parsedQuery->getTerm();
        $termToken = $term->getToken();

        foreach ($this->searchFilters as $searchFilter) {
            if ($nominatorType == $searchFilter) {
                $functionName = str_replace('-', ' ', $nominatorType);
                $functionName = '_query' . ucwords($functionName);
                $functionName = str_replace(' ', '', $functionName);
                call_user_func(array($this, $functionName), $collection, $termToken);
            }
        }
    }

    /**
     * @param $collection \Illuminate\Database\Query\Builder
     * @param $word
     */
    protected function _queryUpdatedAfter($collection, $word)
    {
        $alias = $this->alias;

        if ($word == 'never') {
            $collection->whereNull("$alias.updated_at");
        }

        try {
            $date = Date::parse($word);
            $collection->where("$alias.updated_at", '>', $date);
        } catch (\Exception $e) {
            // Ignore parse exceptions with date
        }
    }

    /**
     * @param $collection \Illuminate\Database\Query\Builder
     * @param $word
     */
    protected function _queryUpdatedBefore($collection, $word)
    {
        $alias = $this->alias;

        if ($word == 'never') {
            $collection->whereNull("$alias.updated_at");
            return;
        }

        try {
            $collection->whereNested(function ($model) use ($word, $alias) {
                $date = Date::parse($word);

                /** @var $model \Illuminate\Database\Query\Builder */
                $model->orWhere("$alias.updated_at", '<', $date);
                $model->orWhereNull("$alias.updated_at");
            });
        } catch (\Exception $e) {
            // Ignore parse exceptions with date
        }
    }

    /**
     * @param $collection \Illuminate\Database\Query\Builder
     * @param $word
     */
    protected function _queryCreatedBefore($collection, $word)
    {
        $alias = $this->alias;
        try {
            $collection->whereNested(function ($model) use ($word, $alias) {
                /** @var $model \Illuminate\Database\Query\Builder */
                $date = Date::parse($word);
                $model->where("$alias.created_at", '<', $date);
                $model->orWhereNull("$alias.created_at");
            });
        } catch (\Exception $e) {
            // Ignore parse exceptions with date
        }
    }

    /**
     * @param $collection \Illuminate\Database\Query\Builder
     * @param $word
     */
    protected function _queryCreatedAfter($collection, $word)
    {
        $alias = $this->alias;
        try {
            $date = Date::parse($word);
            $collection->where("$alias.created_at", '>', $date);
        } catch (\Exception $e) {
            // Ignore parse exceptions with date
        }
    }
}
