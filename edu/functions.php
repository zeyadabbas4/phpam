<?php

/**
 * Functiions for the education system
 */

/**
 * get user's year details
 * 
 * @version 1.0.0
 * 
 * @author MKE <mr_mke@yahoo.com>
 * 
 * @param   int     $userid     The Id of the user to get the year's details for from PHPAM
 * @param   object  $dbc        database connection
 * 
 * @return  array   ['Id'],['Desc']
 */
function getuseryear($userid, $dbc)
{
    $Year = array();
    $q = "select YearId,YearDesc,YearStart,YearEnd from UserYears inner join Years on YearId=UYyid where UYuid=? and UYsid='E'";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $userid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $Year['Id'], $Year['Desc'],$Year['Start'],$Year['End']);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }
    $r = mysqli_query($dbc, $q);
    return ($Year);
}
/**
 * Prints current mode
 *
 * @version 1.0.0
 *
 * @author ahs <skandranii@gmail.com>
 *
 * @param  string $mode
 * @param  bool   $show prints out the mode if true
 *
 *  @return void
 */
function printMode($mode, $show)
{
    if ($show) {
        echo "<br>";
        echo "mode= $mode";
    }
}

/**
 * get_TravelData
 *
 * @version 1.0.0
 * 
 * @author MKE <mr_mke@yahoo.com>
 * 
 * @param  object $dbc
 * @param  int $lecId
 *
 *  @return int
 */
function get_TravelData($dbc, $lecId)
{
    $q = "SELECT staffcontract,staffdegree FROM staff WHERE staffid=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $lecId)) {
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_bind_result($stmt, $staffcontract, $staffdegree)) {
                    mysqli_stmt_fetch($stmt);
                }
            }
        }
        mysqli_stmt_close($stmt);
    }

    switch ($staffcontract) {
        case 1:
        case 2:
        case 3:
        case 4:
        case 5:
            $q = "SELECT degTravel FROM Degrees WHERE degId=?";
            if ($stmt = mysqli_prepare($dbc, $q)) {
                if (mysqli_stmt_bind_param($stmt, "i", $staffdegree)) {
                    if (mysqli_stmt_execute($stmt)) {
                        if (mysqli_stmt_bind_result($stmt, $degTravel)) {
                            mysqli_stmt_fetch($stmt);
                        }
                    }
                }
                mysqli_stmt_close($stmt);
            }
            return ($degTravel);
            break;
        case 7:
            $q = "SELECT lecfees FROM Lecturers WHERE lecid=?";
            if ($stmt = mysqli_prepare($dbc, $q)) {
                if (mysqli_stmt_bind_param($stmt, "i", $lecId)) {
                    if (mysqli_stmt_execute($stmt)) {
                        if (mysqli_stmt_bind_result($stmt, $lecfees)) {
                            mysqli_stmt_fetch($stmt);
                        }
                    }
                }
                mysqli_stmt_close($stmt);
            }
            $q = "SELECT feetravel FROM fees WHERE feeid=?";
            if ($stmt = mysqli_prepare($dbc, $q)) {
                if (mysqli_stmt_bind_param($stmt, "i", $lecfees)) {
                    if (mysqli_stmt_execute($stmt)) {
                        if (mysqli_stmt_bind_result($stmt, $feetravel)) {
                            mysqli_stmt_fetch($stmt);
                        }
                    }
                }
                mysqli_stmt_close($stmt);
            }
            return ($feetravel);
            break;
        default:
            return (0);
    }
}


/**
 * get transport fees
 * 
 * gets transport fees from soursce to distination
 *
 * @version 1.0.0
 * 
 * @author MKE <mr_mke@yahoo.com>
 * 
 * @param object $dbc database connection
 * @param int $from source of transport
 * @param int $to distination of transport
 * 
 * @return int transport fees
 */
function getTransportFees($dbc, $from, $to)
{
    $dstrAmount = 0;
    $q = "SELECT dstrAmount FROM districtTravel WHERE dstrFrom=? AND dstrTo=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "ii", $from, $to)) {
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_bind_result($stmt, $dstrAmount)) {
                    mysqli_stmt_fetch($stmt);
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    return ($dstrAmount);
}

/**
 * list education training year
 * 
 * @version 1.0.0
 * 
 * @author MKE <mr_mke@yahoo.com>
 *
 * @param object $dbc database connection
 * @param string $indexColumn the colum to use for the index of the array
 * 
 * @return array either indexed with yearId or two dimentional array with a nserial index and yearid and year desc in the second dimention
 */
function listEduTrnYears($dbc, $indexColumn = '__serial')
{
    $result = array();
    $i = 0;
    $q = "SELECT YearId,YearDesc,YearStart,YearEnd FROM Years WHERE YearType='E'";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_bind_result($stmt, $YearId, $YearDesc,$YearStart,$YearEnd)) {
                while (mysqli_stmt_fetch($stmt)) {
                    if ($indexColumn == "__serial") {
                        $result[$i]['id'] = $YearId;
                        $result[$i]['desc'] = $YearDesc;
                        $result[$i]['start'] = $YearStart;
                        $result[$i]['end'] = $YearEnd;
                    } else {
                        $result[$YearId]['desc'] = $YearDesc;
                        $result[$YearId]['start'] = $YearStart;
                        $result[$YearId]['end'] = $YearEnd;
                    }
                    $i++;
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    return ($result);
}

/**
 * read companies
 * 
 * read comopanies into an array
 *
 * @param object $dbc   database connection
 * @param boolean $readContributorsOnly read plan conributor companies only
 * @param int $district id of the company's district
 * 
 * @return array
 */
function readCompanies($dbc, $readContributorsOnly = false, $district = "")
{
    $comps = array();
    $whereClause = "";
    if ($readContributorsOnly) {
        $whereClause = " WHERE cmpPlanCont=1";
    }
    if ($district != "") {
        if ($whereClause == "") {
            $whereClause = "WHERE cmpDistrict=$district";
        } else {
            $whereClause .= "AND cmpDistrict=$district";
        }
    }
    $q = "select cmpId,cmpName from companies$whereClause order by cmpName";
    $r = mysqli_query($dbc, $q);
    if ($r) {
        while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
            $comps[$row['cmpId']] = $row['cmpName'];
        }
    }
    return ($comps);
}

/**
 * read Governrates
 * 
 * read Governrates into an array
 *
 * @param object $dbc   database connection
 * 
 * @return array
 */
function readGovernrates($dbc)
{
    $result = array();
    $q = "select GovId,GovName from Governrates order by GovName";
    $r = mysqli_query($dbc, $q);
    if ($r) {
        while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
            $result[$row['GovId']] = $row['GovName'];
        }
    }
    return ($result);
}

/**
 * read Coutries
 * 
 * read Coutries into an array
 *
 * @param object $dbc   database connection
 * 
 * @return array
 */
function readCoutries($dbc, $lang = "Ar")
{
    $result = array();
    $nameField = "CntArabName";
    if ($lang = "En") {
        $nameField = "cntName";
    }
    $q = "select cntId,$nameField from Countries order by cntName";
    $r = mysqli_query($dbc, $q);
    if ($r) {
        while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
            $result[$row['cntId']] = $row[$nameField];
        }
    }
    return ($result);
}
/**
 * drawCourseLecturerTable function
 * 
 * Draws the Lecturer's table for a single course
 *
 * @param object $dbc
 * @param int $coursId
 * @param boolean $showFees
 * 
 * @return void
 */
function drawCourseLecturerTable($dbc, $coursId, $showFees = false, $periodsId = 0)
{
    $lecs = array();
    if ($periodsId == 0) {
        $q = "SELECT clhLecId,staffname,clhHoursP, clhHoursT, clhNights, clhratio, clhDistrictFrom, clhDistrictTo, clhDays, clhReturn, lecfees FROM CourseLecHours INNER JOIN staff ON clhLecId=staffid INNER JOIN Lecturers ON clhLecId=lecid WHERE clhCrsId=$coursId";
    } else {
        $q = "SELECT clhLecId,staffname,clhHoursP, clhHoursT, clhNights, clhratio, clhDistrictFrom, clhDistrictTo, clhDays, clhReturn, lecfees FROM CourseLecHours INNER JOIN staff ON clhLecId=staffid INNER JOIN Lecturers ON clhLecId=lecid WHERE clhCrsId=$coursId  and clhPeriod = $periodsId";
    }
    $r = mysqli_query($dbc, $q);
    if ($r) {
        $i = 0;
        while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
            $lecs[$i]['lecId'] = $row['clhLecId'];
            $lecs[$i]['name'] = $row['staffname'];
            $lecs[$i]['thhrs'] = $row['clhHoursT'];
            $lecs[$i]['prhrs'] = $row['clhHoursP'];
            $lecs[$i]['Nights'] = $row['clhNights'];
            $lecs[$i]['ratio'] = $row['clhratio'];
            $lecs[$i]['From'] = $row['clhDistrictFrom'];
            $lecs[$i]['To'] = $row['clhDistrictTo'];
            $lecs[$i]['Days'] = $row['clhDays'];
            $lecs[$i]['Return'] = $row['clhReturn'];
            $lecs[$i]['fees'] = $row['lecfees'];
            $i++;
        }
    }

    $fees = array();
    $q = "SELECT feeid,feetheoritical,feespractical,feetravel FROM fees";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_bind_result($stmt, $feeid, $feetheoritical, $feespractical, $feetravel)) {
                while (mysqli_stmt_fetch($stmt)) {
                    $fees[$feeid]['theoritical'] = $feetheoritical;
                    $fees[$feeid]['practical'] = $feespractical;
                    $fees[$feeid]['travel'] = $feetravel;
                }
            }
        }
        mysqli_stmt_close($stmt);
    }

    //بيانات المحاضرين
    echo "<div style='text-align: right;font-weight: bold;text-decoration:underline;'>بيانات المحاضرين:</div>";
    echo "<table width='80%' class='lecTable'>";
    if ($showFees) {
        echo "<tr><th> م </th><th> اسم المحاضر </th><th> ساعات نظري </th><th> ساعات عملي </th><th> اجمالي الساعات </th><th> الفئة نظري </th><th> الفئة عملي </th><th> مكافأة نظري </th><th> مكافأة عملي </th><th> إجمالي المكافأة </th></tr>";
    } else {
        echo "<tr><th> م </th><th> اسم المحاضر </th><th> ساعات نظري </th><th> ساعات عملي </th><th> اجمالي الساعات </th></tr>";
    }
    $totThours = 0;
    $totPhours = 0;
    $totCrsHours = 0;
    $totCrsAmount = 0;
    foreach ($lecs as $key => $value) {
        $ser = $key + 1;
        $lecName = $value['name'];
        $tHours = $value['thhrs'];
        $pHours = $value['prhrs'];
        $totLecHours = $tHours + $pHours;
        $totThours += $tHours;
        $totPhours += $pHours;
        $totCrsHours = $totThours + $totPhours;
        $lFees = $value['fees'];
        $tFees = $fees[$lFees]['theoritical'];
        $pFees = $fees[$lFees]['practical'];
        $tAmount = $tFees * $tHours;
        $pAmount = $pFees * $pHours;
        $totAmount = $tAmount + $pAmount;
        $totCrsAmount += $totAmount;
        if ($showFees) {
            echo "<tr><td> $ser </td><td> $lecName </td><td> " . number_format($tHours, 0) . " </td><td> " . number_format($pHours, 0) . " </td><td> $totLecHours </td><td> $tFees </td><td> $pFees </td><td> $tAmount </td><td> $pAmount </td><td> $totAmount </td></tr>";
        } else {
            echo "<tr><td> $ser </td><td> $lecName </td><td> " . number_format($tHours, 0) . " </td><td> " . number_format($pHours, 0) . " </td><td> $totLecHours </td></tr>";
        }
    }
    if ($showFees) {
        echo "<tr><td colspan='2'>اﻹجمالي</td><td>$totThours</td><td>$totPhours</td><td>$totCrsHours</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td> $totCrsAmount </td></tr>";
    } else {
        echo "<tr><td colspan='2'>اﻹجمالي</td><td>$totThours</td><td>$totPhours</td><td>$totCrsHours</td></tr>";
    }
    echo "</table>";
}

/**
 * readCourseLecturers function
 * 
 * reads lecturers of a course into an array
 *
 * @param object $dbc database connection
 * @param int $coursId Course Id
 * @return array
 */
function readCourseLecturers($dbc, $coursId, $period = 0)
{
    $result = array();
    $q = "SELECT clhLecId,staffname,clhHoursP, clhHoursT, clhNights, clhratio, clhDistrictFrom, clhDistrictTo, clhDays, clhReturn, lecfees FROM CourseLecHours INNER JOIN staff ON clhLecId=staffid INNER JOIN Lecturers ON clhLecId=lecid WHERE clhCrsId=$coursId and clhPeriod=$period";
    $r = mysqli_query($dbc, $q);
    if ($r) {
        $i = 0;
        while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
            $result[$i]['lecId'] = $row['clhLecId'];
            $result[$i]['name'] = $row['staffname'];
            $result[$i]['thhrs'] = $row['clhHoursT'];
            $result[$i]['prhrs'] = $row['clhHoursP'];
            $result[$i]['Nights'] = $row['clhNights'];
            $result[$i]['ratio'] = $row['clhratio'];
            $result[$i]['From'] = $row['clhDistrictFrom'];
            $result[$i]['To'] = $row['clhDistrictTo'];
            $result[$i]['Days'] = $row['clhDays'];
            $result[$i]['Return'] = $row['clhReturn'];
            $result[$i]['fees'] = $row['lecfees'];
            $i++;
        }
    }
    return ($result);
}


/**
 * readCurrnecy
 *
 * read Currency names
 *
 * @param    object  $dbc        database connection
 *
 * @return   array   $result   array of currenies names
 *
 * */
function readCurrnecy($dbc)
{
    $result = array();
    $q = "select CurId,CurName from Currencies";
    $r = mysqli_query($dbc, $q);
    if ($r) {
        while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
            $result[$row['CurId']] = $row['CurName'];
        }
    }

    return $result;
}




/**
 * getCourseTranineesConracted function
 *
 * Gets Course Trainees Conracted Courses
 *
 * @param object $dbc dtabase connection
 * @param int $coursId Course Id
 *
 * @return result
 */
function getCourseTranineesConracted($dbc, $coursId)
{
    $result = array();
    $q = "SELECT TrnName,cmpname,crstrntrainee,crstrncompany,crstrnFees,crstrnCurrency,crstrnCertCount,CurName FROM  coursetrainees INNER JOIN Trainees ON crstrntrainee=TrnNo INNER JOIN companies ON crstrncompany=cmpId INNER JOIN Currencies ON crstrnCurrency=CurId WHERE crstrncourse=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $coursId)) {
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_bind_result($stmt, $TrnName, $cmpname, $crstrntrainee, $crstrncompany, $crstrnFees, $crstrnCurrency, $crstrnCertCount, $CurName)) {
                    $i = 0;
                    while (mysqli_stmt_fetch($stmt)) {
                        $result[$i]['TrnName'] = $TrnName;
                        $result[$i]['cmpname'] = $cmpname;
                        $result[$i]['crstrnFees'] = $crstrnFees;
                        $result[$i]['crstrnCurrency'] = $crstrnCurrency;
                        $result[$i]['crstrnCertCount'] = $crstrnCertCount;
                        $result[$i]['CurName'] = $CurName;
                        $i++;
                    }
                }
            }
        }
    
        mysqli_stmt_close($stmt);
    }
    return $result;
}


/**
 * drawCourseTranineesConracted function
 *
 * Draw Course Trainees Conracted Courses
 *
 * @param object $dbc dtabase connection
 * @param int $coursId Course Id
 *
 * @return void
 */
function drawCourseTraineesConracted($dbc, $coursId, $periodsId = 0)
{

    // sql
    $titleStyle=" style='font-weight:bold;text-align:center;'";
    $numberStyle=" style='text-align:center;'";
    $textStyle=" style='text-align:right;padding-right:10px;'";    
    $nomData = getCourseTranineesConracted($dbc, $coursId);
    echo "<div style='text-align: right;font-weight: bold;text-decoration:underline;'>بيانات المتدربين</div>";
    echo "<table width='80%' class='lecTable'>";
    echo "<tr>";
    echo "<td$titleStyle>م</td>";
    echo "<td$titleStyle>اﻹسم</td>";
    echo "<td$titleStyle>الشركة</td>";
    echo "<td$titleStyle>المصروفات</td>";
    echo "<td$titleStyle>العملة</td>";
    echo "<td$titleStyle>عدد الشهادات</td>";
    echo "</tr>";
    foreach ($nomData as $key => $value) {
        $ser=$key+1;
        echo "<tr>";
        echo "<td$numberStyle>" . $ser . "</td>";
        echo "<td$textStyle>" . $value['TrnName'] . "</td>";
        echo "<td$textStyle>" . $value['cmpname'] . "</td>";
        echo "<td$numberStyle>" . $value['crstrnFees'] . "</td>";
        echo "<td$textStyle>" . $value['CurName'] . "</td>";
        echo "<td$numberStyle>" . $value['crstrnCertCount'] . "</td>";
        echo "</tr>";
    }

    echo " </table>";
}



/**
 * drawTravelTable function
 *
 * Draw Course Lecturer Travel Table 
 * 
 * @param object $dbc dtabase connection 
 * @param int $coursId Course Id
 * 
 * @return void
 */
function drawTravelTable($dbc, $coursId, $periodsId = 0)
{
    $lecs = readCourseLecturers($dbc, $coursId, $periodsId);
    $dists = readDistrcts($dbc);
    echo "<div style='text-align: right;font-weight: bold;text-decoration:underline;'>بيانات بدل السفر والانتقال:</div>";
    echo "<table width='80%' class='lecTable'>";
    echo "<tr><th>م</th><th>المحاضر</th><th>عدد الليالي</th><th>الفئة</th><th>النسبة</th><th>بدل السفر</th><th> من </th><th> الى </th><th> العدد </th><th>الفئة</th><th>بدل الانتقال</th></tr>";
    foreach ($lecs as $key => $value) {
        $ser = $key + 1;
        $lectId = $value['lecId'];
        $lecName = $value['name'];
        $Nights = $value['Nights'];
        if ($Nights != 0) {
            $ratio = $value['ratio'];
            $fees = get_TravelData($dbc, $lectId);
            $travelFees = $fees * $Nights * $ratio / 100;
        } else {
            $ratio = 0;
            $fees = 0;
            $travelFees = 0;
        }
        $to = $value['To'];
        $from = $value['From'];
        $days = $value['Days'];
        if ($to != 0) {
            $source = $dists[$from]['Name'];
            $distination = $dists[$to]['Name'];
            $toFees = getTransportFees($dbc, $from, $to);
            $returnFees = getTransportFees($dbc, $to, $from);
        } else {
            $distination = "";
            $source = "";
            $toFees = 0;
            $returnFees = 0;
        }
        $transportCount = $value['Days'];
        $return = $value['Return'];
        $transportFees = $toFees * $transportCount + $returnFees * $transportCount * $return;
        echo "<tr><td> $ser </td><td> $lecName </td><td> $Nights </td><td> $fees </td><td> $ratio % </td><td> $travelFees </td><td> $source </td><td> $distination </td><td> $days </td><td> $toFees </td><td> $transportFees </td></tr>";
    }
    echo "</table>";
}
/**
 * bssDescription function
 *
 * reads basic studies specialities into an array
 * 
 * @param object $dbc database connection
 
 * @return array
 */
function readSpecialities($dbc)
{
    $result = array();
    $q = "SELECT CrsId,CrsName FROM CoursesGuide WHERE CrsProgram=11 ORDER BY CrsName";
    $r = mysqli_query($dbc, $q);
    if ($r) {
        while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
            $result[$row['CrsId']] = $row['CrsName'];
        }
    }
    return ($result);
}

/**
 * readBatchs function
 *
 * reads basic studies batchs into an array
 * 
 * @param object $dbc
 *
 * @return array
 */
function readBatchs($dbc)
{
    $result = array();
    $q = "SELECT YearId,YearDesc FROM Years WHERE YearType='B' ORDER BY YearDesc";
    $r = mysqli_query($dbc, $q);
    if ($r) {
        while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
            $result[$row['YearId']] = $row['YearDesc'];
        }
    }
    return ($result);
}

/**
 * readBatch function
 *
 * @param object $dbc database connection
 * @param int $batchId batch id
 * 
 * @return array
 */
function readBatch($dbc, $batchId)
{
    $result = array();
    $q = "SELECT YearDesc,YearStart,YearEnd FROM Years WHERE YearId=? AND YearType='B'";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $batchId)) {
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_bind_result($stmt, $result['Description'], $result['Start'], $result['End'])) {
                    mysqli_stmt_fetch($stmt);
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    return ($result);
}

/**
 * readCourseNames function
 *
 * reads course names from the course guide
 * 
 * @param object $dbc database connection
 * 
 * @return array 
 */
function readCourseNames($dbc)
{
    $result = array();
    $q = "select CrsId,CrsProgram,CrsCode,CrsName from CoursesGuide";
    $r = mysqli_query($dbc, $q);
    if ($r) {
        while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
            $result[$row['CrsId']]['Name'] = $row['CrsName'];
            $result[$row['CrsId']]['Code'] = $row['CrsCode'];
            $result[$row['CrsId']]['Program'] = $row['CrsProgram'];
        }
    }
    return ($result);
}

/**
 * readCourseGuide function
 *
 * Reads data from Course Guide table
 *
 * @param object $dbc database connection
 * @param int $CoursCrsId from table Courses
 *
 * @return array
 */
function readCourseGuide($dbc, $CoursCrsId)
{
    $result = array();
    $q = "select CrsName from CoursesGuide where CrsId=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $CoursCrsId)) {
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_bind_result($stmt, $result['name'])) {
                    mysqli_stmt_fetch($stmt);
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    return ($result);
}

/**
 * readClass function
 *
 * @param object $dbc database connection
 * @param int $classId class id
 * 
 * @return array
 */
function readClass($dbc, $classId)
{
    $result = array();
    $q = "SELECT CoursDescription,CoursCrsId,CoursYear,CoursStatus FROM Courses WHERE CoursId=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $classId)) {
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_bind_result($stmt, $result['Description'], $result['Speciality'], $result['Batch'], $result['Status'])) {
                    mysqli_stmt_fetch($stmt);
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    return ($result);
}

/**
 * readdistricts function
 *
 * @param object $dbc database connection
 * 
 * @return array
 */
function readDistrcts($dbc)
{
    $result = array();
    $q = "SELECT dist_id,dist_name,dist_local FROM districts ORDER BY dist_name";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_bind_result($stmt, $dist_id, $dist_name, $dist_local)) {
                while (mysqli_stmt_fetch($stmt)) {
                    $result[$dist_id]['Name'] = $dist_name;
                    $result[$dist_id]['Local'] = $dist_local;
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    return ($result);
}

/**
 * readCourseTypes function
 *
 * @param object $dbc database connection
 * 
 * @return array
 */
function readCourseTypes($dbc)
{
    $result = array();
    $q = "SELECT CrstpId,CrstpDescription FROM CourseTypes ORDER BY CrstpDescription";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_bind_result($stmt, $CrstpId, $CrstpDescription)) {
                while (mysqli_stmt_fetch($stmt)) {
                    $result[$CrstpId] = $CrstpDescription;
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    return ($result);
}

/**
 * readClasses function
 *
 * @param object $dbc
 * @param int $batchId
 * 
 * @return array
 */
function readClasses($dbc, $batchId)
{
    $result = array();
    $q = "SELECT CoursId,CoursDescription,CoursCrsId,CoursStatus FROM Courses WHERE CoursYear=? ORDER BY CoursDescription";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $batchId)) {
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_bind_result($stmt, $clsId, $clsDescription, $clsSpeciality, $CoursStatus)) {
                    while (mysqli_stmt_fetch($stmt)) {
                        $result[$clsId]['Description'] = $clsDescription;
                        $result[$clsId]['Speciality'] = $clsSpeciality;
                        $result[$clsId]['Status'] = $CoursStatus;
                    }
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    return ($result);
}

/**
 * comparePeriodStatus function
 *
 * Compare  period Status  with form status
 *
 * @param object $dbc
 * @param int $crsId
 * @param string $case
 * @param int $formStatusLevel
 *
 * @return array of periods having sataus $case  $formStatusLevel
 */
function comparePeriodStatus($dbc, $crsId, $case, $formStatusLevel)
{
    $result = array();
    $q = "SELECT crprId,crprDescription,crprFrom,crprTo,crprStatus,crprCloseDate FROM coursPeriods WHERE crprCourseId=? ";
    switch ($case) {
        case "=":
            $midPart = "and crprStatus = ? ";
            break;
        case "<":
            $midPart = "and crprStatus < ? ";
            break;
        case "<=":
            $midPart = "and crprStatus <= ? ";
            break;
        case ">":
            $midPart = "and crprStatus > ? ";
            break;
        case ">=":
            $midPart = "and crprStatus >= ? ";
            break;
        default:
            $midPart = "";
    }

    $endPart = " ORDER BY crprDescription";

    $q = $q . $midPart . $endPart;

    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "ii", $crsId, $formStatusLevel)) {
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_bind_result($stmt, $crprId, $crprDescription, $crprFrom, $crprTo, $crprStatus, $crprCloseDate)) {
                    while (mysqli_stmt_fetch($stmt)) {
                        $result[$crprId]['Description'] = $crprDescription;
                        $result[$crprId]['From'] = $crprFrom;
                        $result[$crprId]['To'] = $crprTo;
                        $result[$crprId]['Status'] = $crprStatus;
                        $result[$crprId]['Close'] = $crprCloseDate;
                    }
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    return ($result);
}


/**
 * getCrsStat Function
 *
 * Get Course Status
 *
 * @param object $dbc
 *
 * @param int $coureId
 *
 * @return int
 */
function getCrsStat($dbc, $courseId)
{
    $result = readClass($dbc, $courseId)['Status'];
    return $result;
}


/**
 * incCrsStat Function
 *
 * inc Course Status by 1
 *
 * @param object $dbc
 *
 * @param int $coureId
 *
 *
 * @return void
 */
function incCrsStat($dbc, $courseId,$amount=1)
{
    $status = getCrsStat($dbc, $courseId);
    setCrsStat($dbc, $courseId, $status + $amount);
}



/**
 * decCrsStat Function
 *
 * dec Course Status by 1
 *
 * @param object $dbc
 *
 * @param int $coureId
 *
 *
 * @return void
 */

function decCrsStat($dbc, $courseId,$amount=1)
{
    $status = getCrsStat($dbc, $courseId);
    setCrsStat($dbc, $courseId, $status - $amount);
}



/**
 * setCrsStat Function
 *
 * Update Period Status
 *
 * @param object $dbc
 *
 * @param int $coureId
 *
 * @param int $status
 *
 * @return void
 */
function setCrsStat($dbc, $courseId, $status)
{
    $q = "update Courses set CoursStatus=? where CoursId=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "ii", $status, $courseId)) {
            if (!mysqli_stmt_execute($stmt)) {
                $errorMessage .= "Error saving reading [030103" . $__uid . date("YmdHis") . "]!...<br>";
                appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "030103" . $__uid . date("YmdHis"));
            }
        } else {
            $errorMessage .= "Error saving reading [030102" . $__uid . date("YmdHis") . "]!...<br>";
            appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "030102" . $__uid . date("YmdHis"));
        }
        mysqli_stmt_close($stmt);
    } else {
        $errorMessage .= "Error saving reading [030101" . $__uid . date("YmdHis") . "]!...<br>";
        appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "030101" . $__uid . date("YmdHis"));
    }
}

/**
 * setPerStat Function
 *
 * set Period Status
 *
 * @param object $dbc
 *
 * @param int $coureId
 *
 * @param int $status
 *
 * @return void
 */
function setPerStat($dbc, $periodId, $status)
{
    $q = "update coursPeriods set crprStatus=? where crprId=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "ii", $status, $periodId)) {
            if (!mysqli_stmt_execute($stmt)) {
                $errorMessage .= "Error saving reading [030103" . $__uid . date("YmdHis") . "]!...<br>";
                appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "030103" . $__uid . date("YmdHis"));
            }
        } else {
            $errorMessage .= "Error saving reading [030102" . $__uid . date("YmdHis") . "]!...<br>";
            appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "030102" . $__uid . date("YmdHis"));
        }
        mysqli_stmt_close($stmt);
    } else {
        $errorMessage .= "Error saving reading [030101" . $__uid . date("YmdHis") . "]!...<br>";
        appendLog($logDir, $logFile, $q . " - " . mysqli_error($dbc), "030101" . $__uid . date("YmdHis"));
    }
}



/**
 * getPrdStat Function
 *
 * Get Period Status
 *
 * @param object $dbc
 *
 * @param int $periodId
 *
 * @return int
 */
function getPrdStat($dbc, $periodId)
{
    $result = readPeriod($dbc, $periodId)['Status'];
    return $result;
}


/**
 * incPerStat Function
 *
 * increase Period Status by 1
 *
 * @param object $dbc
 *
 * @param int $periodId
 *
 *
 * @return void
 */
function incPerStat($dbc, $periodId,$amount=1)
{
    $status = getPrdStat($dbc, $periodId);
    setPerStat($dbc, $periodId, $status + $amount);
}



/**
 * decPerStat Function
 *
 * dec Period Status by 1
 *
 * @param object $dbc
 *
 * @param int $periodId
 *
 *
 * @return void
 */

function decPerStat($dbc, $periodId,$amount=1)
{
    $status = getPrdStat($dbc, $periodId);
    setPerStat($dbc, $periodId, $status - $amount);
}






/**
 * isAllPeridosEnded
 *
 * This functions answers the questions of "Did this $courseId Periods  end or not"
 *
 * @param object $dbc
 *
 * @param int $coureId
 *
 * @return bool
 */
function allPeriodsEnded($dbc, $courseId, $formStatusLevel)
{
    $periods = readPeriods($dbc, $courseId);
    $closed =  comparePeriodStatus($dbc, $courseId, ">", $formStatusLevel);
    if (count($closed) == count($periods)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}



/**
 * readPeriods function
 *
 * reads course periods into an array
 * 
 * @param object $dbc
 * @param int $crsId
 *
 * @return array
 */
function readPeriods($dbc, $crsId)
{
    $result = array();
    $q = "SELECT crprId,crprDescription,crprFrom,crprTo,crprStatus,crprCloseDate FROM coursPeriods WHERE crprCourseId=? ORDER BY crprStatus,crprFrom";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $crsId)) {
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_bind_result($stmt, $crprId, $crprDescription, $crprFrom, $crprTo, $crprStatus, $crprCloseDate)) {
                    while (mysqli_stmt_fetch($stmt)) {
                        $result[$crprId]['Description'] = $crprDescription;
                        $result[$crprId]['From'] = $crprFrom;
                        $result[$crprId]['To'] = $crprTo;
                        $result[$crprId]['Status'] = $crprStatus;
                        $result[$crprId]['Close'] = $crprCloseDate;
                    }
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    return ($result);
}

/**
 * readPeriod function
 *
 * reads course period into an array
 * 
 * @param object $dbc
 *
 * @return array
 */
function readPeriod($dbc, $perId)
{
    $result = array();
    $q = "SELECT crprDescription,crprFrom,crprTo,crprCourseId,crprStatus,crprCloseDate FROM coursPeriods WHERE crprId=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $perId)) {
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_bind_result($stmt, $crprDescription, $crprFrom, $crprTo, $crprCourseId, $crprStatus, $crprCloseDate)) {
                    while (mysqli_stmt_fetch($stmt)) {
                        $result['Description'] = $crprDescription;
                        $result['From'] = $crprFrom;
                        $result['To'] = $crprTo;
                        $result['Course'] = $crprCourseId;
                        $result['Status'] = $crprStatus;
                        $result['Close'] = $crprCloseDate;
                    }
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    return ($result);
}

/**
 * readLecturerNames function
 * 
 * Read lecturer names into array
 *
 * @param object $dbc
 * 
 * @return array
 */
function readLecturerNames($dbc)
{
    $result = array();
    $q = "SELECT staffid,staffname FROM staff WHERE staffislec=1 AND staffdeleted=0 ORDER BY staffname";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_bind_result($stmt, $staffid, $staffname)) {
                while (mysqli_stmt_fetch($stmt)) {
                    $result[$staffid] = $staffname;
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    return ($result);
}

/**
 * provides yes no array with enlgish and arabic values
 *
 * @param string $lang "En"/"Ar"
 *
 * @return array $result of yes/no values
 */
function yesNo($lang)
{
    $result = array();
    switch ($lang) {
        case "En":
            $result[0] = "No";
            $result[1] = "Yes";
            break;
        case "Ar":
            $result[0] = "لا";
            $result[1] = "نعم";
            break;
    }

    return $result;
}


/**
 * readCourseLecturersNoPeriods function
 *
 * reads lecturers of a course reagarless of the period into an array
 *
 * @param object $dbc database connection
 * @param int $coursId Course Id
 * @return array
 */
function readCourseLecturersNoPeriods($dbc, $coursId)
{
    $result = array();
    $q = "SELECT clhLecId,staffname,clhHoursP, clhHoursT, clhNights, clhratio, clhDistrictFrom, clhDistrictTo, clhDays, clhReturn, lecfees,clhPeriod FROM CourseLecHours INNER JOIN staff ON clhLecId=staffid INNER JOIN Lecturers ON clhLecId=lecid WHERE clhCrsId=$coursId";
    $r = mysqli_query($dbc, $q);
    if ($r) {
        $i = 0;
        while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
            $result[$i]['lecId'] = $row['clhLecId'];
            $result[$i]['name'] = $row['staffname'];
            $result[$i]['thhrs'] = $row['clhHoursT'];
            $result[$i]['prhrs'] = $row['clhHoursP'];
            $result[$i]['Nights'] = $row['clhNights'];
            $result[$i]['ratio'] = $row['clhratio'];
            $result[$i]['From'] = $row['clhDistrictFrom'];
            $result[$i]['To'] = $row['clhDistrictTo'];
            $result[$i]['Days'] = $row['clhDays'];
            $result[$i]['Return'] = $row['clhReturn'];
            $result[$i]['fees'] = $row['lecfees'];
            $result[$i]['Periods'] = $row['clhPeriod'];
            $i++;
        }
    }
    return ($result);
}


/**
 * courseHasLecs function
 *
 * asnwers the following questoin "Does this course has lecutrers or not?"
 *
 * @param object $dbc database connection
 * @param int $CoursId
 *
 * @return bool $result
 */
function courseHasLecs($dbc, $CrsId) // to be checked again for null
{
    $q = "select count(clhLecId) from CourseLecHours where clhCrsId =?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $CrsId)) {
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_bind_result($stmt, $count)) {
                    if (mysqli_stmt_fetch($stmt)) {
                    }
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    if ($count > 0)
        $result = true;
    else
        $result = false;
    return $result;
}

/**
 * courseIsDevidable function
 *
 * asnwers the following questoin "Does this course is devidable or not?
 *
 * @param object $dbc database connection
 * @param int $CoursId
 *
 * @return bool $result
 */

function courseIsDevidable($dbc, $CrsId)
{
    $q = "select CoursDevidable from Courses where CoursId=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $CrsId)) {
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_bind_result($stmt, $isDvd)) {
                    if (mysqli_stmt_fetch($stmt));
                } //end of execute
            }
            mysqli_stmt_close($stmt);
            if ($isDvd)
                $result = true;
            else
                $result = false;
            return $result;
        }
    }
}


/**
 * manshourIsFound function
 *
 * asnwers the following questoin "Does this mansohour is found or not?
 *
 * @param object $dbc database connection
 * @param int $manshourId masnhour Id
 * @param int $crsYear course year
 *
 * @return bool $result
 */

function manshourIsFound($dbc, $crsBulletin, $crsYear)
{
    $q = "select CoursId from Courses where CoursBulletin=? and CoursYear=? and CoursType=2";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "ii", $crsBulletin, $crsYear)) {
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_bind_result($stmt, $CoursId)) {
                    if (mysqli_stmt_fetch($stmt));
                } //end of execute
            }
            mysqli_stmt_close($stmt);
            if ($CoursId)
                $result = true;
            else
                $result = false;
            return $result;
        }
    }
}


/**
 * getCourseDetails
 *
 * get Course Details from Courses Table
 *
 * @param   int     $courseId
 *
 * @param   int     $coursYear
 *
 * @return  array   $result
 */

function getCourseDetails($dbc, $coursId, $coursYear)
{
    $result = array();
    $q = "select CoursCrsId,CoursType,CoursBulletin,CoursFromAct,CoursStatus,CoursToAct,CoursFromPln,CouursToPln,  CoursSupervisorInt,CoursSupervisorExt,CoursYear,CoursArea,CoursSection,CoursGenRept,CoursAreaRept,CoursLocation, CoursDevidable,CoursDescription from Courses where coursId=? and  coursYear=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "ii", $coursId, $coursYear)) {
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_bind_result($stmt, $CoursCrsId, $CoursType, $CoursBulletin, $CoursFromAct, $CoursStatus, $CoursToAct, $CoursFromPln, $CouursToPln, $CoursSupervisorInt, $CoursSupervisorExt, $CoursYear, $CoursArea, $CoursSection, $CoursGenRept, $CoursAreaRept, $CoursLocation, $CoursDevidable, $CoursDescription)) {
                    if (mysqli_stmt_fetch($stmt)) {
                        $result['CoursCrsId'] = $CoursCrsId;
                        $result['CoursType'] = $CoursType;
                        $result['CoursBulletin'] = $CoursBulletin;
                        $result['CoursFromAct'] = $CoursFromAct;
                        $result['CoursStatus'] = $CoursStatus;
                        $result['CoursToAct'] = $CoursToAct;
                        $result['CoursFromPln'] = $CoursFromPln;
                        $result['CouursToPln'] = $CouursToPln;
                        $result['CoursSupervisorInt'] = $CoursSupervisorInt;
                        $result['CoursSupervisorExt'] = $CoursSupervisorExt;
                        $result['CoursYear'] = $CoursYear;
                        $result['CoursArea'] = $CoursArea;
                        $result['CoursSection'] = $CoursSection;
                        $result['CoursGenRept'] = $CoursGenRept;
                        $result['CoursAreaRept'] = $CoursAreaRept;
                        $result['CoursLocation'] = $CoursLocation;
                        $result['CoursDevidable'] = $CoursDevidable;
                        $result['CoursDescription'] = $CoursDescription;
                    }
                } //end of execute
            }
            mysqli_stmt_close($stmt);
        }
    }

    return ($result);
}



/**
 * displayCourseDetails
 *
 * echoing Course details
 *
 * @param   object  $dbc        database connection
 * @param   int     $courseId
 * @param   int     $userYear
 *
 * @return  void
 */


function displayCourseDetails($dbc, $courseId, $userYear, $periodId = 0)
{
    $courseDetails = getCourseDetails($dbc, $courseId, $userYear);
    $periodDetails = readPeriod($dbc, $periodId);
    $dividable = courseIsDevidable($dbc, $courseId);
    $prgs = readCourseGuide($dbc, $courseDetails['CoursCrsId']);
    $dists = readDistrcts($dbc);
    $comps = readCompanies($dbc);
    $staff = readLecturerNames($dbc);
    echo "<table width='100%'>";
    echo "<tr>";
    echo "<td>اسم الدورة: </td><td>" . $prgs["name"] . "</td>";
    echo "<td>رقم المنشور: </td><td>" . $courseDetails['CoursBulletin'] . "</td>";
    echo "<td>المنطقة: </td><td> " . $dists[$courseDetails['CoursArea']]['Name'] . "</td>";
    echo "</tr>";
    echo "</table>";
    echo "<table width='100%'>";
    echo "<tr>";
    echo "<td>خلال الفترة";
    if ($courseDetails['CoursType'] == 1) {
        echo "(فعلي)";
    }
    echo " من:";
    if ($dividable) {
        echo  $periodDetails['From'];
    } else {
        echo  $courseDetails['CoursFromAct'];
    }
    echo "الى:";
    if ($dividable) {
        echo  $periodDetails['To'];
    } else {
        echo  $courseDetails['CoursToAct'];
    }
    echo "<br>";
    echo str_repeat("&nbsp;", 12);
    if ($courseDetails['CoursType'] == 1) {
        echo "(مخطط)من: " . $courseDetails['CoursFromPln'];
        echo "الى: " . $courseDetails['CouursToPln'] . "</td>";
    }
    echo "<td>مكان اﻹنعقاد: " . $comps[$courseDetails['CoursLocation']] . "</td>";
    if ($courseDetails['CoursType'] == 1) {
        echo "<td>اﻹشراف: " . $staff[$courseDetails['CoursSupervisorInt']] . "</td>";
    }
    echo "</tr>";  // الاشراف الخارجي." / ".$comps[$CoursSupervisorExt]
    echo "</table>";
}

/**
 *
 * @name tagPrd
 *
 * @desc Function to add tags to periods as in "new|Revised|"
 *
 * @param   array   $periods
 * @param   int   $formStatusLevel
 *
 * @return  array
 */
function tagPrd($periods, $formStatusLevel)
{
    foreach ($periods as $key => $value) {
        if ($value['Status'] == $formStatusLevel) {
            $periods[$key]['tag'] = "جديد";
        } else {
            $periods[$key]['tag'] = "تمت المراجعه";
        }
    }
    return $periods;
}

/**
 *
 * @name srtPrd
 *
 * @desc Function to sort Periods according to column
 *
 * @param   array   $periods
 * @param   string   $var
 *
 * @return  array
 */
function srtPrd($periods, $var)
{
    $keys = array_keys($periods);
    for ($i = 0; $i < sizeof($keys) - 1; $i++) {
        for ($j = 0; $j < sizeof($keys) - 1 - $i; $j++) {
            if ($periods[$keys[$j]][$var] > $periods[$keys[$j + 1]][$var]) {
                $flipped = $keys[$j + 1];
                $keys[$j + 1] = $keys[$j];
                $keys[$j] = $flipped;
            }
        }
    }
    $result = array();
    foreach ($keys as $item) {
        $result[$item] =  $periods[$item];
    }

    return $result;
}


/**
 *
 * @name rsrtPrd
 *
 * @desc Function to reverse sort Periods according to column
 *
 * @param   array   $periods
 * @param   string   $var
 *
 * @return  array
 */
function rsrtPrd($periods, $var)
{
    $keys = array_keys($periods);
    for ($i = 0; $i < sizeof($keys) - 1; $i++) {
        for ($j = 0; $j < sizeof($keys) - 1 - $i; $j++) {
            if ($periods[$keys[$j]][$var] < $periods[$keys[$j + 1]][$var]) {
                $flipped = $keys[$j + 1];
                $keys[$j + 1] = $keys[$j];
                $keys[$j] = $flipped;
            }
        }
    }
    $result = array();
    foreach ($keys as $item) {
        $result[$item] =  $periods[$item];
    }

    return $result;
}
/**
 *
 * @name tagPrdBenefits
 *
 * @desc Function to add tags to periods in benefits
 *
 * @param   array   $periods
 * @param   int   $formStatusLevel
 *
 * @return  array
 */
function tagPrdBenefits($periods, $formStatusLevel)
{
    foreach ($periods as $key => $value) {
        if ($value['Status'] == $formStatusLevel) {
            $periods[$key]['tag'] = "جديد";
        } elseif ($value['Status'] == $formStatusLevel + 1) {
            $periods[$key]['tag'] = "تمت المراجعة";
        } elseif ($value['Status'] == $formStatusLevel + 2) {
            $periods[$key]['tag'] = "تم الاعتماد";
        }
    }
    return $periods;
}
/* get year details
* 
* @version 1.0.0
* 
* @author MKE <mr_mke@yahoo.com>
* 
* @param   int     $yearId     The Id of the year to get 
* @param   object  $dbc        database connection
* 
* @return  array   ['Id'],['Desc']
*/
function getYear($dbc, $yearId)
{
    $Year = array();
    $q = "select YearId,YearDesc,YearStart,YearEnd,YearChart,YearType from Years Where YearId=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $yearId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $Year['Id'], $Year['Desc'], $Year['Start'], $Year['End'], $Year['Chart'], $Year['Type']);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }
    $r = mysqli_query($dbc, $q);
    return ($Year);
}

/* get con trainee count
* 
* @version 1.0.0
* 
* @author MKE <mr_mke@yahoo.com>
* 
* @param   int     $courseId     The Id of the year to get 
* @param   object  $dbc        database connection
* 
* @return  array   ['Id'],['Desc']
*/
function getConTrnData($dbc, $courseId, $totals = false)
{
    $result = array();
    if ($totals) {
        $result['Cost'] = 0;
        $result['Currency'] = 0;
        $result['TrnCount'] = 0;
        $result['TrnCerts'] = 0;
    }
    $q = "select concrsCmp,concrsCost,concrsCurrency,concrsTrnCount,concrsTrnCerts from conCourses Where concrsId=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_bind_param($stmt, "i", $courseId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $concrsCmp, $concrsCost, $concrsCurrency, $concrsTrnCount, $concrsTrnCerts);
        while (mysqli_stmt_fetch($stmt)) {
            if ($totals) {
                $result['Cost'] += $concrsCost;
                $result['Currency'] += $concrsCurrency;
                $result['TrnCount'] += $concrsTrnCount;
                $result['TrnCerts'] += $concrsTrnCerts;
            } else {
                $result[$concrsCmp]['Cost'] = $concrsCost;
                $result[$concrsCmp]['Currency'] = $concrsCurrency;
                $result[$concrsCmp]['TrnCount'] = $concrsTrnCount;
                $result[$concrsCmp]['TrnCerts'] = $concrsTrnCerts;
            }
        }
        mysqli_stmt_close($stmt);
    }
    $r = mysqli_query($dbc, $q);
    return ($result);
}

/* get Course full information including Course Guide name despite the course year
*
* @param   int     $CoursId
* @param   object  $dbc        database connection
*
* @return  array $result
*/
function getCrsInfo($dbc, $CoursId)
{

    $result = array();
    $q = "SELECT  CrsName,CoursCrsId,CoursType,CoursBulletin,CoursFromAct,CoursToAct,CoursFromPln,CouursToPln,CoursSupervisorInt,CoursSupervisorExt,CoursYear,CoursArea,CoursSection,CoursGenRept,CoursAreaRept,CoursLocation,CoursStatus,CoursDevidable,CoursDescription FROM Courses inner join CoursesGuide on CoursCrsId =CrsId where CoursId =? ";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $CoursId)) {
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_bind_result($stmt, $CrsName, $CoursCrsId, $CoursType, $CoursBulletin, $CoursFromAct, $CoursStatus, $CoursToAct, $CoursFromPln, $CouursToPln, $CoursSupervisorInt, $CoursSupervisorExt, $CoursYear, $CoursArea, $CoursSection, $CoursGenRept, $CoursAreaRept, $CoursLocation, $CoursDevidable, $CoursDescription)) {
                    if (mysqli_stmt_fetch($stmt)) {
                        $result['Name'] = $CrsName;
                        $result['CoursCrsId'] = $CoursCrsId;
                        $result['CoursType'] = $CoursType;
                        $result['CoursBulletin'] = $CoursBulletin;
                        $result['CoursFromAct'] = $CoursFromAct;
                        $result['CoursStatus'] = $CoursStatus;
                        $result['CoursToAct'] = $CoursToAct;
                        $result['CoursFromPln'] = $CoursFromPln;
                        $result['CouursToPln'] = $CouursToPln;
                        $result['CoursSupervisorInt'] = $CoursSupervisorInt;
                        $result['CoursSupervisorExt'] = $CoursSupervisorExt;
                        $result['CoursYear'] = $CoursYear;
                        $result['CoursArea'] = $CoursArea;
                        $result['CoursSection'] = $CoursSection;
                        $result['CoursGenRept'] = $CoursGenRept;
                        $result['CoursAreaRept'] = $CoursAreaRept;
                        $result['CoursLocation'] = $CoursLocation;
                        $result['CoursDevidable'] = $CoursDevidable;
                        $result['CoursDescription'] = $CoursDescription;
                    }
                } //end of execute
            }
            mysqli_stmt_close($stmt);
        }
    }
    return ($result);
}

/* get device types
* 
* @version 1.0.0
* 
* @author MKE <mr_mke@yahoo.com>
* 
* @param   object  $dbc        database connection
* 
* @return  array   ['Id'],['Desc']
*/
function getDevTypes($dbc){
    $result = array();
    $q = "select TypeId,TypeDesc from  DevTypes order by TypeDesc";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $TypeId,$TypeDesc);
        while(mysqli_stmt_fetch($stmt)){
            $result[$TypeId]=$TypeDesc;
        }
        mysqli_stmt_close($stmt);
    }
    $r = mysqli_query($dbc, $q);
    return ($result);
}

/**
 * readEmpNames function
 * 
 * Read employee names into array
 *
 * @param object $dbc
 * 
 * @return array
 */
function readEmpNames($dbc)
{
    $result = array();
    $q = "SELECT staffid,staffname FROM staff WHERE staffdeleted=0 ORDER BY staffname";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_bind_result($stmt, $staffid, $staffname)) {
                while (mysqli_stmt_fetch($stmt)) {
                    $result[$staffid] = $staffname;
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    return ($result);
}

/**
 * readBSYears function
 * 
 * Read years for basic studies into array
 *
 * @param object $dbc
 * 
 * @return array
 */
function readBSYears($dbc)
{
    $result = array();
    $q = "SELECT YearId,YearDesc,YearStart,YearEnd,YearNotificationNo FROM Years WHERE YearType='B' ORDER BY YearDesc";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_bind_result($stmt, $YearId,$YearDesc,$YearStart,$YearEnd,$YearNotificationNo)) {
                while (mysqli_stmt_fetch($stmt)) {
                    $result[$YearId]['desc'] = $YearDesc;
                    $result[$YearId]['start'] = $YearStart;
                    $result[$YearId]['end'] = $YearEnd;
                    $result[$YearId]['notification'] = $YearNotificationNo;
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    return ($result);
}

/**
 * getSignatureById function
 * 
 * returns an image of a signature
 * 
 * @param object    $dbc
 * @param int       $sigId
 * 
 * @return string   image tag of signature
 */
function getSignatureById($dbc,$sigimgId,$imgWidth=150){
    $result="";
    $q="SELECT sigimgFile FROM signatureImages WHERE sigimgId=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $sigimgId)) {
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_bind_result($stmt, $sigimgFile)) {
                    if (mysqli_stmt_fetch($stmt)) {
                        $result="<img src='$sigimgFile' width='".$imgWidth."px'>";
                    }
                } //end of execute
            }
            mysqli_stmt_close($stmt);
        }
    }
    return $result;
}

/**
 * getSignatureByUserId function
 * 
 * returns an image of a signature
 * 
 * @param object    $dbc
 * @param int       $sigId
 * 
 * @return string   image tag of signature
 */
function getSignatureByUserId($dbc,$sigimgUserId ,$imgWidth=150){
    $q="SELECT sigimgFile FROM signatureImages WHERE sigimgUserId=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $sigimgUserId)) {
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_bind_result($stmt, $sigimgFile)) {
                    if (mysqli_stmt_fetch($stmt)) {
                        $result="<img src='$sigimgFile' width='".$imgWidth."px'>";
                    }
                } //end of execute
            }
            mysqli_stmt_close($stmt);
        }
    }
    return $result;
}

/**
 * getSignatureByDocument function
 * 
 * returns an image of a signature
 * 
 * @param object    $dbc
 * @param int       $sigId
 * 
 * @return string   image tag of signature
 */
function getSignatureByDocument($dbc,$sigDoc,$sigOrder,$imgWidth=150){
    $q="SELECT sigimgFile FROM signatureImages INNER JOIN signatures ON sigimgId=sigImage WHERE sigDoc=? AND sigOrder=?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $sigDoc,$sigOrder)) {
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_bind_result($stmt, $sigimgFile)) {
                    if (mysqli_stmt_fetch($stmt)) {
                        $result="<img src='$sigimgFile' width='".$imgWidth."px'>";
                    }
                } //end of execute
            }
            mysqli_stmt_close($stmt);
        }
    }
    return $result;
}

/**
 * readPrograms function
 *
 * @param object $dbc database connection
 * 
 * @return array
 */
function readPrograms($dbc)
{
    $result = array();
    $q = "SELECT PrgId,PrgCode,PrgName FROM Programs ORDER BY PrgName";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_bind_result($stmt, $PrgId,$PrgCode,$PrgName)) {
                while (mysqli_stmt_fetch($stmt)) {
                    $result[$PrgId]['Name'] = $PrgName;
                    $result[$PrgId]['Code'] = $PrgCode;
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    return ($result);
}

/**
 * Save dean user Id on accept for courses
 * 
 * @param   $dbc    database connection
 * @param   $usrId  User's Id
 * @param   $crsId  Course Id
 * 
 */
function setDeanAccredation($dbc,$usrId,$crsId){
    $q="UPDATE `Courses` SET `CoursDeanAccreditor` = ? WHERE `CoursId` = ?";
     if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "ii", $usrId,$crsId)) {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}

/**
 * Save HR user Id on accept for courses
 * 
 * @param   $dbc    database connection
 * @param   $usrId  User's Id
 * @param   $crsId  Course Id
 * 
 */
function setHRAccredation($dbc,$usrId,$crsId){
    $q="UPDATE `Courses` SET `CoursHRAccreditor` = ? WHERE `CoursId` = ?";
     if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "ii", $usrId,$crsId)) {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}

/**
 * get Dean accreditor user Id by courses Id
 * 
 * @param   $dbc    database connection
 * @param   $crsId  Course Id
 * 
 * @return   $usrId  User's Id
 */
function getDeanAccredation($dbc,$crsId){
    $q="SELECT `CoursDeanAccreditor` FROM `Courses` WHERE `CoursId`=?";
     if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $crsId)) {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $userId);
            mysqli_stmt_fetch($stmt);           
            mysqli_stmt_close($stmt);
        }
    }
    return($userId);
}

/**
 * get HR accreditor user Id by courses Id
 * 
 * @param   $dbc    database connection
 * @param   $crsId  Course Id
 * 
 * @return   $usrId  User's Id
 */
function getHRAccredation($dbc,$crsId){
    $q="SELECT `CoursHRAccreditor` FROM `Courses` WHERE `CoursId`=?";
     if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $crsId)) {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $userId);
            mysqli_stmt_fetch($stmt);           
            mysqli_stmt_close($stmt);
        }
    }
    return($userId);
}

/**
 * Save dean user Id on accept for courses periods
 * 
 * @param   $dbc    database connection
 * @param   $usrId  User's Id
 * @param   $crprId Period Id
 * 
 */
function setPeriodDeanAccredation($dbc,$usrId,$crprId){
    $q="UPDATE `coursPeriods` SET `crprDeanAcredation` = ? WHERE `crprId` = ?";
     if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "ii", $usrId,$crprId)) {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}

/**
 * Save HR user Id on accept for courses periods
 * 
 * @param   $dbc    database connection
 * @param   $usrId  User's Id
 * @param   $crprId Period Id
 * 
 */
function setPeriodHRAccredation($dbc,$usrId,$crprId){
    $q="UPDATE `coursPeriods` SET `crprHRAcredation` = ? WHERE `crprId` = ?";
     if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "ii", $usrId,$crprId)) {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}

/**
 * get HR accreditor user Id by period Id
 * 
 * @param   $dbc    database connection
 * @param   $crsId  Course Id
 * 
 * @return   $usrId  User's Id
 */
function getPeriodHRAccredation($dbc,$crprId){
    $q="SELECT `crprHRAcredation` FROM `coursPeriods` WHERE `crprId`=?";
     if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $crprId)) {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $userId);
            mysqli_stmt_fetch($stmt);           
            mysqli_stmt_close($stmt);
        }
    }
    return($userId);
}

/**
 * get dean accreditor user Id by period Id
 * 
 * @param   $dbc    database connection
 * @param   $crsId  Course Id
 * 
 * @return   $usrId  User's Id
 */
function getPeriodDeanAccredation($dbc,$crprId){
    $q="SELECT `crprDeanAcredation` FROM `coursPeriods` WHERE `crprId`=?";
     if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $crprId)) {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $userId);
            mysqli_stmt_fetch($stmt);           
            mysqli_stmt_close($stmt);
        }
    }
    return($userId);
}

/**
 * get education constant
 * 
 * @param   $dbc    database connection
 * @param   $constantName  name of constant
 * 
 * @return   constant value
 */
function getEduConstant($dbc,$constantName){
    $q="SELECT `educValue` FROM `EduConstants` WHERE `educName`=?";
     if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "s", $constantName)) {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $educValue);
            mysqli_stmt_fetch($stmt);           
            mysqli_stmt_close($stmt);
        }
    }
    return($educValue);
}

/**
 * set education constant
 * 
 * @param   $dbc    database connection
 * @param   $constantName  name of constant
 * 
 * @return   constant value
 */
function setEduConstant($dbc,$constantName,$constantvalue){
    $q="UPDATE `EduConstants` SET `educValue`=? WHERE `educName`=?";
     if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "ss", $constantvalue, $constantName)) {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}

