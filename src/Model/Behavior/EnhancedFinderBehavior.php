<?php
namespace App\Model\Behavior;

use Cake\ORM\Behavior;

/**
 * EnhancedFinder behavior
 * 
 * Behavior providing additional methods for retrieving data.
 */
class EnhancedFinderBehavior extends Behavior
{

    /**
     * Retrieve a single field value
     * 
     * @param  string $fieldName The name of the table field to retrieve.
     * @param  array $conditions An array of conditions for the find.
     * @return mixed The value of the specified field from the first row of the result set.
     */
    public function field($fieldName, array $conditions)
    {
        $field = $this->_table->getAlias() . '.' . $fieldName;
        $query = $this->_table->find()->select($field)->where($conditions);

        if ($query->isEmpty()) {
            return null;
        }
        return $query->first()->{$fieldName};
    }
}