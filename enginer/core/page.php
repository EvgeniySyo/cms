<?php
class Simple_Page
{
    public function NumberList($Count,$ThisPage,$BackTheme,$NextTheme,$OnPageTheme,$OtherPageTheme,$NumberList)
    {
        $MaxGoBack = $Count-$NumberList;
        if($ThisPage > ($Count-1) || $ThisPage < 0) $ThisPage = 0;

        if(($ThisPage+1) <= $NumberList && ($ThisPage-(round($NumberList/2)))>=0)
        {
            if(($ThisPage+(round($NumberList/2))) < ($Count-1))
            {
                $start = $ThisPage-(round($NumberList/2));
                $end = $NumberList+$start;
            }
            else
            {
                $start = ($Count-1)-$NumberList;
                $end = ($Count-1);
            }
        }
        else if(($ThisPage+1) <= $NumberList)
        {
            $start = 0;
            if($NumberList > $Count) $end = $Count;
            else $end = $NumberList;
        }
        else
        {

            if($ThisPage == ($Count-1) || $ThisPage > $MaxGoBack)
            {
                $start = $Count-$NumberList;
                $end = $Count;
            }
            else
            {
                $start = $ThisPage-(round($NumberList/2));
                $end = $NumberList+$start;
            }
        }
        
        if($ThisPage != 0) $content .= str_replace('{n}',($ThisPage-1),$BackTheme);

        for ($p=$start;$p<$end;$p++)
        {
            if($ThisPage == $p )
            {
                $xThene = str_replace('{n}',($p),$OnPageTheme);
                $xThene = str_replace('{n1}',($p+1),$xThene);
                $content .= $xThene;
            }
            else
            {
                
                $xThene = str_replace('{n}',($p),$OtherPageTheme);
                $xThene = str_replace('{n1}',($p+1),$xThene);
                $content .= $xThene;
            }
        }

        
        if(($Count-1) != $ThisPage) $content .= str_replace('{n}',($ThisPage+1),$NextTheme);
        return $content;

    }
}
?>