<?php

/**
 * Print nearest error
 * 
 * This function prints the nearest mysqli error with decoration support around the priting of the error statment itself
 * to help identify where the printing occurs and ends.
 *
 *
 * 
 * @param   statment $statment   database statment
 * @param   decoration $decoration   flag to set decoration around the error
 *  - flag "d" sets the decoration (Default)
 *  -anything else prints with no decoration around the function 
 *
 * @return  void
 */

function e_Print($statment,$decoration="d")
{
    if($decoration=="d")
        echo "\n<br>Error Start: -----------------------------------<br>\n";
    echo mysqli_stmt_error($statment);
    if($decoration=="d")
        echo "\n<br>Error End:---------------------------------";
}
