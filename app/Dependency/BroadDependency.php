<?php

namespace App\Dependency;

class BroadDependency
{
    // defines all cost format
    protected $currencyType = '₱';

    // uses in tables
    protected $rowsToDisplay = 10;

    // method to set pages
    public function setPages($dataTables)
    {
        $attributes = array('page' => 1, 'row' => 1);
        
        foreach($dataTables as $dataTable)
        {
            $dataTable['page'] = $attributes['page'];
            $dataTable['show'] = true;

            if($attributes['row'] === $this->rowsToDisplay)
            {
                $attributes['page']++;
                $attributes['row'] = 1;
            }
            else
            {
                $attributes['row']++;
            }
        } // end  of foreach datatables

        return $dataTables;
    }
}