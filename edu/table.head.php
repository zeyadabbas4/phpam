<!DOCTYPE html>
<html>
    <head>
        <title>جدول دورة</title>
        <meta charset='utf-8'>
        <style>
            hr{
                border-style: double;
                border-width: medium;
            }
            table{
                margin: auto;
            }
            .timeTable{
                border-style: solid;
                border-color: black;
                border-width: 1px;
                width: 90%;
            }
            .timeTableCell{
                border-style: solid;
                border-color: black;
                border-width: 1px;
                width: 14.28%;
            }
            .main-doc{
                width: 25cm;
                height: 16cm;
                margin: auto;
                direction:rtl;
                border-style: double;
                border-width: medium;
                text-align: center;
            }
            .logos{
                width:100%;
            }
            .pti-title{
                width: 10cm;
                margin: auto;
                text-align: center;
            }
            .pti-title-en{
                font-size: x-large;
            }
            .pti-title-ar{
                font-size: large;
            }
            .aast-logo{
                width:2cm;
                float:right;
            }
            .pti-logo{
                width:2cm;
                float:left;
            }
            .content{
                text-align:right;
            }
            .signature{
                width:6.5cm;
                text-align: center;
                margin-right: 11.5cm;
            }
            .line-title{
                font-weight: bold;
            }
            .footer {
                position: fixed;
                left: 0;
                bottom: 0;
                width: 100%;
                font-size: x-small;
                text-align: center;
                display:none;
            }
            .buttons{
                margin: auto;
                width:25cm;
                text-align: center;
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
                .footer{
                    display:block;
                    page-break-after: always;
                }
                .buttons{
                    display:none;
                }
            }
        </style>
    </head>