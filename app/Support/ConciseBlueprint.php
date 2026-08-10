<?php

namespace App\Support;

use Illuminate\Database\Schema\Blueprint;

class ConciseBlueprint extends Blueprint
{
    /**
     * Create a default index name for the table, keeping MySQL's 64-character
     * identifier limit into account when schema-qualified table names are used.
     *
     * @param  string  $type
     * @param  array  $columns
     * @return string
     */
    protected function createIndexName($type, array $columns)
    {
        $table = $this->table;

        if ($this->connection->getConfig('prefix_indexes')) {
            $table = str_contains($this->table, '.')
                ? substr_replace($this->table, '.'.$this->connection->getTablePrefix(), strrpos($this->table, '.'), 1)
                : $this->connection->getTablePrefix().$this->table;
        }

        $index = str_replace(['-', '.'], '_', strtolower($table.'_'.implode('_', $columns).'_'.$type));

        if (strlen($index) > 64) {
            $index = substr($index, 0, 44).'_'.substr(md5($index), 0, 19);
        }

        return $index;
    }
}
