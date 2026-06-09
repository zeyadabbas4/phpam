<?php
echo "
<!DOCTYPE html>
<html>
    <head>
        <title>منشور تدريبي</title>
        <meta charset='utf-8'>
        <style>
            .main-doc{
                width: 17cm;
                height: 24cm;
                margin: auto;
                direction:rtl;
            }
            .doc-title{
                width: 6cm;
                border-width: 1px;
                border-style: solid;
                text-align: center;
                margin: auto;
                font-size: x-large;
            }
            .logos{
                width:100%;
            }
            .pti-title{
                width:7.5cm;
                margin: auto;
                text-align: center;
            }
            .pti-title-en{
                font-size: x-large;
            }
            .pti-title-ar{
                font-size: xx-large;
            }
            .aast-logo{
                width:2.5cm;
                float:right;
            }
            .pti-logo{
                width:2.5cm;
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
            .crs-title{
                font-weight: bold;
                font-size: x-large;
                text-align: center;
            }
            .centered{
                text-align: center;
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
                text-align: center;
                direction: rtl;
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
            .savBtn {
                background-color: green;
                color: white;
                padding: 10px 10px;
                border: none;
                cursor: pointer;
                width: 100px;
                opacity: 0.9;
            }
            .savBtn:hover {
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
    </head>";