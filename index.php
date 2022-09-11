<?php

namespace noughitarek\sudoku;

include 'sudoku.php';

use noughitarek\sudoku\sudoku;

$sudoku = array(
    array(0, 0, 0,      0, 0, 0,        0, 0, 0),
    array(0, 0, 0,      0, 0, 0,        0, 0, 0),
    array(0, 0, 0,      0, 0, 0,        0, 0, 0),

    array(0, 0, 0,      0, 0, 0,        0, 0, 0),
    array(0, 0, 0,      0, 0, 0,        0, 0, 0),
    array(0, 0, 0,      0, 0, 0,        0, 0, 0),

    array(0, 0, 0,      0, 0, 0,        0, 0, 0),
    array(0, 0, 0,      0, 0, 0,        0, 0, 0),
    array(0, 0, 0,      0, 0, 0,        0, 0, 0),
); // 9X9 blank
$sudoku = array(
    array(0, 0, 0,      0, 0, 0,        5, 0, 0),
    array(3, 0, 2,      0, 7, 0,        9, 1, 0),
    array(6, 0, 0,      9, 0, 0,        0, 0, 0),

    array(0, 0, 0,      0, 0, 0,        0, 2, 6),
    array(0, 2, 0,      3, 0, 0,        1, 5, 9),
    array(7, 9, 0,      6, 0, 5,        0, 8, 0),

    array(1, 0, 9,      7, 0, 0,        0, 0, 0),
    array(4, 5, 0,      0, 0, 0,        2, 3, 0),
    array(0, 3, 8,      4, 5, 0,        6, 0, 0),
); // 9X9 Easy

$sudoku = array(
    array(0, 0, 0,      6, 0, 0,        0, 1, 0),
    array(0, 0, 7,      0, 0, 0,        0, 0, 0),
    array(8, 2, 0,      0, 0, 9,        3, 0, 0),

    array(0, 0, 4,      0, 0, 0,        5, 0, 0),
    array(0, 0, 3,      0, 0, 7,        0, 0, 0),
    array(5, 7, 0,      9, 0, 0,        0, 0, 6),

    array(0, 0, 0,      0, 8, 0,        0, 0, 3),
    array(9, 5, 0,      0, 0, 2,        8, 0, 0),
    array(4, 0, 0,      0, 0, 0,        0, 0, 0),
); // 9X9 Evil


$sudoku = new Sudoku($sudoku);
if(isset($_SERVER['REMOTE_ADDR']))
{
	echo 'Follow me on <a target="_blank" href="https://github.com/noughitarek/">GitHub</a>';
}
else
{
	echo 'Follow me on GitHub.com/noughitarek';
}
	
$sudoku->solve();

if(isset($_SERVER['REMOTE_ADDR']))
{
    $res = $sudoku->export_html();
    $table = '<table>';

    for($i=0; $i<$sudoku->dimention;$i++)
    {
        $table .= '<tr>';
        for($j=0; $j<$sudoku->dimention;$j++)
        {
            $table .= '<td class="'.($j%$sudoku->cube_dimention==0?'left_cell':'').' '.($i%$sudoku->cube_dimention==0?'top_cell':'').' '.($j==$sudoku->dimention-1?'right_cell':'').' '.($i==$sudoku->dimention-1?'bottom_cell':'').'">';
            
            $table .= '<font class="'.$res[$i][$j]['class'].'">'.$res[$i][$j]['data'].'</font>';

            $table .= '</td>';
        }
        $table .= '</tr>';
    }
    $table .= '</table>';
    $table .= '<h4>Execution time: '.$sudoku->execution_time.'s</h4>';
    }
else
{
    echo "\n";
    $res = $sudoku->export();
    echo "Solution\n\n";
    for($i=0; $i<$sudoku->dimention;$i++)
    {
        for($j=0; $j<$sudoku->dimention;$j++)
        {
            echo $res[$i][$j];
            if($j%$sudoku->cube_dimention==2)
            {
                echo "\t";
            }
        } 
        if($i%$sudoku->cube_dimention==2)
        {
            echo "\n";
        }
        echo "\n";
    }
    echo "Execution time: \033[91m".$sudoku->execution_time."s\033[0m";
    echo "\n";
    exit;
}


?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.27.2/axios.min.js" integrity="sha512-odNmoc1XJy5x1TMVMdC7EMs3IVdItLPlCeL5vSUPN2llYKMJ2eByTTAIiiuqLg+GdNr9hF6z81p27DArRFKT7A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    </head>
    <body>
        <h1>Solution</h1>
        <?=$table?>
    </body>
</html>