<?php

namespace Serkarn\ClickhouseMigrations;

class Client extends \ClickHouseDB\Client
{
    
    /**
     * 
     * @param string $sql
     * @param array $bindings
     * @param \ClickHouseDB\Query\WhereInFile $whereInFile
     * @param \ClickHouseDB\Query\WriteToFile $writeToFile
     * @return \Illuminate\Support\Collection
     */
    public function selectAll(
            string $sql,
            array $bindings = [],
            \ClickHouseDB\Query\WhereInFile $whereInFile = null,
            \ClickHouseDB\Query\WriteToFile $writeToFile = null
    ): \Illuminate\Support\Collection
    {
        $statement = parent::select($sql, $bindings, $whereInFile, $writeToFile);
        return new \Illuminate\Support\Collection($statement->rows());
    }

    /**
     * 
     * @param string $table
     * @param array $conditions
     * @return \ClickHouseDB\Statement
     * @throws \RuntimeException
     */
    public function delete(string $table, array $conditions = []): \ClickHouseDB\Statement
    {
        $whereArray = [];
        foreach ($conditions as $condition) {
            if (!is_array($condition) || count($condition) !== 3) {
                throw new \RuntimeException('Invalid where condition');
            }
            $this->transformСompare($condition[1], $condition[2]);
            $this->transformParamValue($condition[2]);
            $whereArray[] = implode(' ', $condition);
        }
        return $this->write('
            ALTER TABLE
                ' . $table . '
            DELETE
            WHERE
                ' . implode(' ' . 'AND' . ' ', $whereArray) . '
        ');
    }
    
    /**
     * 
     * @param mixed $value
     * @return mixed
     */
    protected function transformParamValue(&$value)
    {
        $result = null;
        switch (gettype($value)) {
            case 'string':
                $result = ' \'' . $value . '\' ';
                break;
            case 'array':
                foreach ($value as $element) {
                    $this->transformParamValue($element);
                }
                $result = ' (' . implode(',', $value) . ') ';
            case 'NULL':
                $result = 'NULL';
            default:
                $result = $value;
        }
        $value = $result;
        return $result;
    }
    
    /**
     * 
     * @param string $compare
     * @param mixed $conditionValue
     * @return string
     */
    protected function transformСompare(string $compare, &$conditionValue): string
    {
        $result = null;
        $conditionValueType = gettype($conditionValue);
        switch ($compare) {
            case '<>':
            case '!=':
                switch ($conditionValueType) {
                    case 'array':
                        $result = ' NOT IN ';
                        break;
                    case 'NULL':
                        $result = ' IS NOT ';
                        break;
                    default:
                        $result = ' != ';
                }
                break;
            case '=':
                switch ($conditionValueType) {
                    case 'array':
                        $result = ' IN ';
                        break;
                    case 'NULL':
                        $result = ' IS ';
                        break;
                    default:
                        $result = ' = ';
                }
                break;
            case '<':
            case '<=':
            case '>':
            case '>=':
                settype($conditionValue, 'float');
                break;
            default:
                throw new \RuntimeException('Invalid compare value');
        }
        return $result;
    }
    
}
