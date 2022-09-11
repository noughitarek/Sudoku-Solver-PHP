<?php 

namespace noughitarek\sudoku;

use Exception;

class sudoku
{
    private $init_sudoku;
    private $current_sudoku;
    private $posibilities;

    public $dimention;
    public $cube_dimention;
    public $execution_time;

    function __construct(Array $sudoku)
    {
        $this->execution_time = microtime(true);
        $this->dimention = count($sudoku);
        $this->cube_dimention = sqrt(count($sudoku));
        $this->init_sudoku = $sudoku;
        $this->current_sudoku = $sudoku;

        if(!$this->check($sudoku))
        {
            throw new Exception('Sudoku invalid');
        }
    }
    function check(Array $sudoku)
    {
        $dimention = count($sudoku);
        if(sqrt($dimention) != (int)sqrt($dimention))
        {
            return false;
        }
        foreach($sudoku as $row)
        {
            if(count($row)!=$dimention)
            {
                return false;
            }
        }
        for($i=0;$i<$dimention;$i++)
        {
            if(!$this->check_line($i) || !$this->check_column($i) || !$this->check_cube($i))
            {
                return false;
            }
        }
        return true;
    }
    function check_line(int $line)
    {
        for($i=0;$i<$this->dimention;$i++)
        {
            for($j=0;$j<$this->dimention;$j++)
            {
                if($this->current_sudoku[$line][$i] == $this->current_sudoku[$line][$j] && $i!=$j && $this->current_sudoku[$line][$i] != 0)
                {
                    return false;
                }
            }
        }
        return true;
    }
    function check_column(int $column)
    {
        for($i=0;$i<$this->dimention;$i++)
        {
            for($j=0;$j<$this->dimention;$j++)
            {
                if($this->current_sudoku[$i][$column] == $this->current_sudoku[$j][$column] && $i!=$j && $this->current_sudoku[$i][$column] != 0)
                {
                    return false;
                }
            }
        }
        return true;
    }
    function check_cube(int $cube)
    {
        for($i=(int)($cube/$this->cube_dimention)*$this->cube_dimention; $i<(int)(($cube/$this->cube_dimention)+1)*$this->cube_dimention;$i++)
        {
            for($j=($cube%$this->cube_dimention)*$this->cube_dimention; $j<(($cube%$this->cube_dimention)+1)*$this->cube_dimention;$j++)
            {
                for($k=(int)($cube/$this->cube_dimention)*$this->cube_dimention; $k<(int)(($cube/$this->cube_dimention)+1)*$this->cube_dimention;$k++)
                {
                    for($l=($cube%$this->cube_dimention)*$this->cube_dimention; $l<(($cube%$this->cube_dimention)+1)*$this->cube_dimention;$l++)
                    {
                        if($this->current_sudoku[$i][$j] == $this->current_sudoku[$k][$l] && ($i!=$k || $j!=$l) && $i!=0 && $this->current_sudoku[$i][$j] != 0 && $this->current_sudoku[$k][$l] != 0)
                        {
                            return false;
                        }
                    }
                }
            }
        }
        return true;
    }
    function cube(int $line, int $column)
    {
        return (int)($column/$this->cube_dimention)+(int)($line/$this->cube_dimention)*$this->cube_dimention;
    }
    function export_html()
    {
        for($i=0;$i<$this->dimention;$i++)
        {
            for($j=0;$j<$this->dimention;$j++)
            {
                if($this->current_sudoku[$i][$j]==0)
                {
                    $result[$i][$j]['class'] = '';
                    $result[$i][$j]['data'] = '';
                }
                else
                {
                    if($this->current_sudoku[$i][$j] == $this->init_sudoku[$i][$j])
                    {
                        $result[$i][$j]['class'] = 'default';
                    }
                    else
                    {
                        $result[$i][$j]['class'] = 'corrected';
                    }
                    $result[$i][$j]['data'] = $this->current_sudoku[$i][$j];
                }
            }
        }
        return $result;
    }
    function export()
    {
        for($i=0;$i<$this->dimention;$i++)
        {
            for($j=0;$j<$this->dimention;$j++)
            {
                if($this->current_sudoku[$i][$j]==0)
                {
                    $result[$i][$j] = '';
                }
                else
                {
                    if($this->current_sudoku[$i][$j] == $this->init_sudoku[$i][$j])
                    {
                        $result[$i][$j] = $this->current_sudoku[$i][$j];
                    }
                    else
                    {
                        $result[$i][$j] = "\033[32m".$this->current_sudoku[$i][$j]."\033[0m";
                    }
                }
            }
        }
        return $result;
    }
    function get_posibilities()
    {
        $temp_sudoku = $this->current_sudoku;
        for($i=0;$i<$this->dimention;$i++)
        {
            for($j=0;$j<$this->dimention;$j++)
            {
                if($this->current_sudoku[$i][$j] == 0)
                {
                    $this->current_sudoku = $temp_sudoku;
                    for($k=1;$k<=$this->dimention;$k++)
                    {
                        $this->current_sudoku[$i][$j] = $k;
                        if($this->check_line($i) && $this->check_column($j) && $this->check_cube($this->cube($i,$j)))
                        {
                            $posibilities[$i][$j][] = $k;
                        }
                    }
                    if(!isset($posibilities[$i][$j]) || count($posibilities[$i][$j])==0)
                    {
                        return false;
                    }
                }
                else
                {
                    $posibilities[$i][$j][] = $this->current_sudoku[$i][$j];
                }
            }
        }
        $this->posibilities = $posibilities;
        $this->current_sudoku = $temp_sudoku;
        return true;
    }
    function solve()
    {
        if(!$this->simple_solve($this->init_sudoku))
        {
            if(!$this->hard_solve())
            {
                return false;
            }
        }
        $this->execution_time = number_format(microtime(true)-$this->execution_time, 2);
        return true;
    }
    function simple_solve($init_sudoku)
    {
        if(!$this->get_posibilities())
        {
            return false;
        }
        for($i=0;$i<$this->dimention;$i++)
        {
            for($j=0;$j<$this->dimention;$j++)
            {
                if(count($this->posibilities[$i][$j]) == 1)
                {
                    $this->current_sudoku[$i][$j] = $this->posibilities[$i][$j][0];
                }
            }
        }
        if($this->validate())
        {
            return true;
        }
        elseif($this->current_sudoku !== $init_sudoku)
        {
            $this->get_posibilities();
            return $this->simple_solve($this->current_sudoku);
        }
        return false;
    }
    function hard_solve()
    {
        $temp_sudoku = $this->current_sudoku;
        for($i=0;$i<$this->dimention;$i++)
        {
            for($j=0;$j<$this->dimention;$j++)
            {
                if(count($this->posibilities[$i][$j])>1)
                {
                    foreach($this->posibilities[$i][$j] as $posibility)
                    {
                        $this->current_sudoku[$i][$j] = $posibility;
                        if($this->simple_solve($this->current_sudoku))
                        {
                            return true;
                        }
                        if($this->hard_solve())
                        {
                            return true;
                        }
                        $this->current_sudoku = $temp_sudoku;
                    }
                }
            }
        }
        if($this->validate())
        {
            return true;
        }
        else
        {
            return false;
        }
        $this->current_sudoku = $temp_sudoku;
        return true;
    }
    function validate()
    {
        for($i=0;$i<$this->dimention;$i++)
        {
            if(!$this->check_line($i) || !$this->check_column($i) || !$this->check_cube($i))
            {
                return false;
            }
            for($j=0;$j<$this->dimention;$j++)
            {
                if($this->current_sudoku[$i][$j]==0)
                {
                    return false;
                }
            }
        }
        return true;
    }
    
}