<?php
function only($Amount, $Par=0, $Cur='', $CurPl='')
{
    
    $Fraction ='';

    $OPar[0] = "";
    $CPar[0] = "";
    $OPar[1] = "(";
    $CPar[1] = ")";


    $phrase = $OPar[$Par] . "فقط ";

    $Amounts = trim((string)  $Amount);
    
    if(strpos($Amounts, '.') <> FALSE){
        $Fraction = substr($Amounts,strpos($Amounts,'.')+1,strlen($Amounts)-strpos($Amounts,'.'));
        $Amounts = substr($Amounts,0, strpos($Amounts, "."));
    }
    
    switch (strlen($Amounts))
    {
    case 1:
        $phrase = $phrase . Units($Amounts);
        break;
    case 2:
        $phrase = $phrase . Tens($Amounts);
        break;
     case 3:
        $phrase = $phrase . Hundreds($Amounts);
         break;
    case 4:
        $phrase = $phrase . Thouthands($Amounts);
        break;
    case 5:
        $phrase = $phrase . TenThouthands($Amounts);
        break;
    case 6:
        $phrase = $phrase . HundredThouthands($Amounts);
        break;
    case 7:
        $phrase = $phrase . Millions($Amounts);
        break;
    case 8:
        $phrase = $phrase . TenMillions($Amounts);
        break;
    case 9:
        $phrase = $phrase . HundredMillions($Amounts);
        break;
    default:
    }

    if(substr($Amounts, -2) == "10")
        $phrase = $phrase . $CurPl;
    elseif(strlen($Amounts) == 1)
    {
        if($Amounts == "1" || $Amounts == "2")
            $phrase = $phrase . $Cur;
        else   
            $phrase = $phrase . $CurPl;
    }
    else
        $phrase = $phrase . $Cur;

        
    if($Fraction == '' || $Fraction == '00')
        $phrase = $phrase . " لاغير" . $CPar[$Par];
    else
        $phrase = $phrase . " و" . $Fraction . "\\100 لا غير" . $CPar[$Par];

    return $phrase;
}

function Units($Amounts)
{
    switch($Amounts)
    {
    case "1":
        return "واحد ";
    case "2":
        return "إثنان ";
    case "3":
        return "ثلاثة ";
    case "4":
        return "أربعة ";
    case "5":
        return "خمسة ";
    case "6":
        return "ستة ";
    case "7":
        return "سبعة ";
    case "8":
        return "ثمانية ";
    case "9":
        return "تسعة ";
    default:
        return "";
    }
}

function Tens($Amounts)
{
    $unit = Units(substr($Amounts, -1));
    $phrase='';

    switch(substr($Amounts,0, 1))
    {
    case "1":
        $phrase = "عشر ";
        if(substr($Amounts, -1) == "1")
            $unit = "أحد ";
        elseif (substr($Amounts, -1) == "2")
            $unit = "إثنا ";
        elseif(substr($Amounts, -1)== "0")
            $phrase = "عشرة ";
        break;
    case "2":
        $phrase = "عشرون ";
        break;
    case "3":
        $phrase = "ثلاثون ";
        break;
    case "4":
        $phrase = "أربعون ";
        break;
    case "5":
        $phrase = "خمسون ";
        break;
    case "6":
        $phrase = "ستون ";
        break;
    case "7":
        $phrase = "سبعون ";
        break;
    case "8":
        $phrase = "ثمانون ";
        break;
    case "9":
        $phrase = "تسعون ";
        break;
    default:
    }

    if($unit == "")
        return $phrase;
    elseif(substr($Amounts,0,1) == "1")
        return $unit . $phrase;
    elseif(substr($Amounts,0, 1) == "0")
        return $unit;
    else
        return $unit . "و" . $phrase;
}

function Hundreds($Amounts)
{
    $phrase='';
    $unit = Tens(substr($Amounts, -2));

    switch(substr($Amounts,0, 1))
        {
        Case "1":
            $phrase = "مائة ";
            break;
        Case "2":
            $phrase = "مائتان ";
            break;
        Case "3":
            $phrase = "ثلاثمائة ";
            break;
        Case "4":
            $phrase = "أربعمائة ";
            break;
        Case "5":
            $phrase = "خمسمائة ";
            break;
        Case "6":
            $phrase = "ستمائة ";
            break;
        Case "7":
            $phrase = "سبعمائة ";
            break;
        Case "8":
            $phrase = "ثمانمائة ";
            break;
        Case "9":
            $phrase = "تسعمائة ";
            break;
        default:
        }
    if($unit == "")
        return $phrase;
    elseif(substr($Amounts,0, 1) == "0")
        return $unit;
    else
        return $phrase . "و" . $unit;
}

function Thouthands($Amounts)
{
    $phrase ='';

    $unit = Hundreds(substr($Amounts, -3));

    switch(substr($Amounts,0, 1))
    {
    Case "1":
        $phrase = "ألف ";
        break;
    Case "2":
        $phrase = "ألفين ";
        break;
    Case "3":
        $phrase = "ثلاثة آلاف ";
        break;
    Case "4":
        $phrase = "أربعة آلاف ";
        break;
    Case "5":
        $phrase = "خمسة آلاف ";
        break;
    Case "6":
        $phrase = "ستة آلاف ";
        break;
    Case "7":
        $phrase = "سبعة آلاف ";
        break;
    Case "8":
        $phrase = "ثمانية آلاف ";
        break;
    Case "9":
        $phrase = "تسعة آلاف ";
        break;
    default:
    }        

    if($unit == "")
        return $phrase;
    else
        return $phrase . "و" . $unit;
}

function TenThouthands($Amounts)
{
    $phrase ='';
    $unit = Tens(substr($Amounts,0, 2));
    $Hun = Hundreds(substr($Amounts, -3));
    if($Hun == "")
        return $unit . "الف ";
    else
        return $unit . "ألف و" . $Hun;
}

function HundredThouthands($Amounts)
{
    $unit = Hundreds(substr($Amounts,0,3));
    $Hun = Hundreds(substr($Amounts, -3));
    if($Hun == "")
        return $unit . "الف ";
    else
        return $unit . "الف و" . $Hun;
}

function Millions($Amounts)
{
    $phrase ='';
    $unit = HundredThouthands(substr($Amounts, -6));

    switch(substr($Amounts,0, 1))
    {
     Case "1":
        $phrase = "مليون ";
         break;
    Case "2":
        $phrase = "إثنين مليون ";
        break;
    Case "3":
        $phrase = "ثلاثة ملايين ";
        break;
    Case "4":
        $phrase = "أربعة ملايين ";
        break;
    Case "5":
        $phrase = "خمسة ملايين ";
        break;
    Case "6":
        $phrase = "ستة ملايين ";
        break;
    Case "7":
        $phrase = "سبعة ملايين ";
        break;
    Case "8":
        $phrase = "ثمانية ملايين ";
        break;
    Case "9":
        $phrase = "تسعة ملايين ";
        break;
    default:
    }
    if($unit == "")
        return $phrase;
    else
        return $phrase . "و" . $unit;
}

function TenMillions($Amounts)
{
$unit = Tens(substr($Amounts,0, 2));
$Hun = HundredThouthands(substr($Amounts, -6));
if(Hun == "")
    return $unit . "مليون ";
else
    return $unit . "مليون و" . $Hun;
}

function HundredMillions($Amounts)
{
$unit = Hundreds(substr($Amounts,0,3));
$Hun = HundredThouthands(substr($Amounts, -6));
if($Hun == "")
    return $unit . "مليون ";
else
    return $unit . "مليون و" . $Hun;
}
?>