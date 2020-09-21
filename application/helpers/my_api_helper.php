<?php
    function removeUnknownFields($rawData, $expectedFields)
    {
        $newData = array();
        foreach($rawData as $fieldName => $fieldValue)
        {
            if($fieldValue != "" && in_array($fieldName, array_values($expectedFields)))
            {
                $newData[$fieldName] = $fieldValue;
            }
        }

        return $newData;
    }

?>