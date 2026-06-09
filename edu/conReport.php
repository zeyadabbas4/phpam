<?php
ini_set('dsplay_errors', 1);
error_reporting(E_ALL);
session_start();
?>
<!DOCTYPE html>
<html lang='en'>

<head>
    <title></title>
    <meta charset='utf-8'>
    <style>
        .mainDoc {
            width: 18cm;
            height: 27cm;
            margin: auto;
        }

        body {
            direction: rtl;
            text-align: right;
        }

        .top-table {
            border-style: none;
            width: 100%;
            border-spacing: 5px;
        }

        .top-table tr:last-child {
            text-align: center;
        }

        .middle-table {
            border-style: solid;
            border-width: 1px;
            border-collapse: collapse;
            width: 100%;
        }

        .middle-table td {
            border-style: solid;
            border-width: 1px;
            text-align: center;
        }


        .titles {
            font-weight: bold;
        }

        .cnlBtn {
            background-color: red;
            color: white;
            padding: 10px 10px;
            border: none;
            cursor: pointer;
            width: 100px;
            opacity: 0.9;
        }

        .cnlBtn:hover {
            opacity: 1;
        }

        .prtBtn {
            background-color: blue;
            color: white;
            padding: 10px 10px;
            border: none;
            cursor: pointer;
            width: 100px;
            opacity: 0.9;
        }

        .prtBtn:hover {
            opacity: 1;
        }

        @media print {
            .footer {
                display: block;
                page-break-after: always;
            }

            .buttons {
                display: none;
            }
        }

        .buttons {
            text-align: center;
            direction: rtl;
        }
    </style>
</head>
<?php
$__sytemRoot = "../";
$__includeDir = "../include"; //path to include directory
include($__sytemRoot . "functions.php");
include('functions.php');
include('appdb.php');
foreach ($_GET as $key => $value) {
    $$key = $value;
}
if (!$dbc = dbConnect($db_host, $db_schema, $db_user, $db_password)) {
    die("Could not connect to database please contact system admin...");
}
if (isset($_SESSION['__uid'])) {
    $__uid = $_SESSION['__uid'];
    $user_year = getuseryear($__uid, $dbc);
}
if ($CoursId != -1) {
    $q = "select CoursId ,CoursCrsId,CoursType,CoursBulletin,CoursFromPln,CouursToPln,CoursYear,CoursArea,CoursSection,CoursLocation,CoursStatus from Courses where CoursId = ?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $CoursId)) {
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_bind_result($stmt, $CoursId, $CoursCrsId, $CoursType, $CoursBulletin, $CoursFromPln, $CouursToPln, $CoursYear, $CoursArea, $CoursSection, $CoursLocation, $CoursStatus)) {
                    if (mysqli_stmt_fetch($stmt)) {
                        $carryOn = true;
                    } else {
                        $carryOn = false;
                    }
                }
            }
        }

        mysqli_stmt_close($stmt);
    }

    $nomineesCount = 0;
    $q = "select concrsTrnCount from  conCourses WHERE  concrsId  =?";
    if ($stmt = mysqli_prepare($dbc, $q)) {
        if (mysqli_stmt_bind_param($stmt, "i", $CoursId)) {
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_bind_result($stmt, $concrsTrnCount)) {
                    while (mysqli_stmt_fetch($stmt)) {
                        $nomineesCount += $concrsTrnCount;
                    }
                }
            }
        }

        mysqli_stmt_close($stmt);
    }
}
///////////////////
// load ref data //
///////////////////

include("$__includeDir/readSections.php");
include("$__includeDir/readPrograms.php");
include("$__includeDir/readCurrency.php");
include("$__includeDir/readCompanies.php");
include("$__includeDir/readDistricts.php");


//ref data
$nomData = array();
$certTCount = 0;
$q = "SELECT TrnName,cmpname,crstrntrainee,crstrncompany,crstrnFees,crstrnCurrency,crstrnCertCount,CurName FROM  coursetrainees INNER JOIN Trainees ON crstrntrainee=TrnNo INNER JOIN companies ON crstrncompany=cmpId INNER JOIN Currencies ON crstrnCurrency=CurId WHERE crstrncourse=?";

if ($stmt = mysqli_prepare($dbc, $q)) {
    if (mysqli_stmt_bind_param($stmt, "i", $CoursId)) {
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_bind_result($stmt, $TrnName, $cmpname, $crstrntrainee, $crstrncompany, $crstrnFees, $crstrnCurrency, $crstrnCertCount, $CurName)) {
                $i = 0;
                $totFees = 0;
                $totCert = 0;
                while (mysqli_stmt_fetch($stmt)) {
                    $nomData[$i]['TrnName'] = $TrnName;
                    $nomData[$i]['cmpname'] = $cmpname;
                    $nomData[$i]['crstrnFees'] = $crstrnFees;
                    $nomData[$i]['crstrnCurrency'] = $crstrnCurrency;
                    $nomData[$i]['crstrnCertCount'] = $crstrnCertCount;
                    $nomData[$i]['CurName'] = $CurName;
                    $i++;
                    $totFees += $crstrnFees;
                    $totCert += $crstrnCertCount;
                }
            }
        }
    }

    mysqli_stmt_close($stmt);
}



?>

<body>
    <?php include("letter.print.buttons.php"); ?>
    <div class='mainDoc'>
        <h1>بيانات الدورات التعاقدية</h1>
        <table class='top-table'>
            <tr>
                <td class='titles'>العام التدريبي</td>
                <td><?php echo $user_year['Desc']; ?></td>
                <td class='titles'>المنطقه</td>
                <td> <?php echo $dists[$CoursArea]; ?> </td>
            </tr>
            <tr>
                <td class='titles' colspan='4'>بيانات الدورة</td>
            </tr>
            <tr>
                <td class='titles'>تصنيف الدورة</td>
                <td><?php echo $secs[$CoursSection] ?></td>
                <td class='titles'>نوع الدورة</td>
                <td><?php echo $prgs[$CoursCrsId]['Name']; ?></td>
            </tr>
            <tr>

                <td class='titles'>رقم الاخطار</td>
                <td><?php echo $CoursBulletin; ?></td>
                <td class='titles'>عدد الساعات</td>
                <td> <?php echo $prgs[$CoursCrsId]['Hours']; ?> </td>
            </tr>
            <tr>
                <td class='titles'>خلال الفترة من</td>
                <td><?php echo $CoursFromPln; ?></td>
                <td class='titles'>الى</td>
                <td><?php echo $CouursToPln; ?></td>
            </tr>
            <tr>
                <td class='titles'>مكان الانعقاد</td>
                <td class='titles'>إجمالي المتدربين المنفذ</td>
                <td class='titles'>إجمالي الشهادات</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><?php echo $comps[$CoursLocation] ?></td>
                <td><?php echo count($nomData); ?></td>
                <td><?php echo $totCert ?></td>
                <td>&nbsp;</td>
            </tr>
        </table>
        <h2>المتدربين</h2>
        <table class='middle-table'>
            <tr>
                <td class='titles'>م</td>
                <td class='titles'>الاسم</td>
                <td class='titles'>الجهة</td>
                <td class='titles'>التكلفة</td>
                <td class='titles'>العمله</td>
                <td class='titles'>عدد الشهادات</td>
            </tr>
            <?php foreach ($nomData as $key => $value) { ?>
                <tr>
                    <td> <?php echo $key + 1; ?> </td>
                    <td> <?php echo $value['TrnName']; ?> </td>
                    <td> <?php echo $value['cmpname']; ?> </td>
                    <td> <?php echo $value['crstrnFees']; ?> </td>
                    <td> <?php echo $value['CurName']; ?></td>
                    <td> <?php echo $value['crstrnCertCount']; ?> </td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="3">&nbsp;</td><td><?php echo $totFees; ?> </td><td>&nbsp;</td><td><?php echo $totCert; ?></td>
            </tr>
        </table>

        <?php $staff = readLecturerNames($dbc); ?>
        <?php $lecs = readCourseLecturersNoPeriods($dbc, $CoursId);
        ksort($lecs);
        ?>
        <?php $yesNo = yesNo("Ar"); ?>
        <?php $periods = readPeriods($dbc, $CoursId); ?>
        <?php
        $hasPeriods = courseIsDevidable($dbc, $CoursId);
        $GroupBy = false;
        if ($hasPeriods) {
            $GroupBy = true;
        }
        ?>
        <?php
        if ($hasPeriods) {
            echo "<h2>الفترات</h2>";
            echo "<table class='middle-table'>";
            echo "<tr>";
            echo "<td class='titles'>الفترة</td>";
            echo "<td class='titles'>من</td>";
            echo "<td class='titles'>إلى</td>";
            echo "</tr>";
            ksort($periods);
            foreach ($periods as $key => $value) {
                echo "<tr>";
                echo "<td>" . $value['Description'] . "</td>";
                echo "<td>" . $value['From'] . "</td>";
                echo "<td>" . $value['To'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        ?>
        <h2>المحاضرين</h2>
        <table class=' middle-table'>
            <tr>
                <td class='titles'>المحاضر</td>
                <td class='titles'>النظري</td>
                <td class='titles'>العملي</td>
                <?php
                if ($GroupBy) {
                    echo "<td class='titles'>الفترة</td>";
                }
                ?>
                <td class='titles'>عدد الليالي</td>
                <td class='titles'>النسبة</td>
                <td class='titles'>الانتقال</td>
                <td class='titles'>عدد ايام الانتقال</td>
                <td class='titles'>العودة</td>
            </tr>
            <?php foreach ($lecs as $key => $value) { ?>
                <tr>
                    <td><?php echo $staff[$value['lecId']]; ?></td>
                    <td><?php echo $value['thhrs']; ?></td>
                    <td><?php echo $value['prhrs']; ?></td>
                    <?php
                    if ($GroupBy) {
                        echo "<td>" . $periods[$value['Periods']]['Description'] . "</td>";
                    }
                    ?>
                    <td><?php echo $value['Nights']; ?></td>
                    <td><?php echo $value['ratio']; ?>%</td>
                    <td><?php echo $dists[$value['To']]; ?></td>
                    <td><?php echo $value['Days']; ?></td>
                    <td><?php echo $yesNo[$value['Return']]; ?></td>
                </tr>
            <?php } ?>
        </table>
        <h4>إعتماد رئيس القسم البرامج الغير مخططة</h4>
        <h4>إعتماد مدير إدارة التعليم</h4>
    </div>
</body>

</html>
